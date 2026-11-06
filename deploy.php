<?php

declare(strict_types=1);

namespace Deployer;

// Include base recipes
const CACHE_WARMUP = 'cache:warmup --';
require 'recipe/common.php';
require 'contrib/cachetool.php';
require 'contrib/rsync.php';

// Include hosts
import('.hosts.yml');

// Hosterabhängig
set('http_user', 'web35');
set('http_group', 'web35g');

// Define binaries
set('bin/php', '/usr/bin/php8.5');
set('bin/typo3', '{{release_path}}/vendor/bin/typo3');

// Set maximum number of releases
set('keep_releases', 5);

// Since updating the ReferenceIndex takes a very long time for some projects, this value must be increased.
set('default_timeout', 1200); // 20 minutes instead of the standard 5 minutes

// Set TYPO3 docroot
set('typo3_webroot', 'public');

// Set shared directories
$sharedDirectories = [
    '{{typo3_webroot}}/fileadmin',
    '{{typo3_webroot}}/typo3temp',
];
set('shared_dirs', $sharedDirectories);

// Set shared files
$sharedFiles = [
    //'{{typo3_webroot}}/.htaccess',
    'config/system/additional.php',
];
set('shared_files', $sharedFiles);

// Define all rsync excludes
$exclude = [
    // OS specific files
    '.DS_Store',
    'Thumbs.db',
    // Project specific files and directories
    '.ddev',
    '.editorconfig',
    '.fleet',
    '.git*',
    '.idea',
    '.php-cs-fixer.dist.php',
    '.vscode',
    'auth.json',
    'deploy.php',
    '.hosts.yml',
    'phpstan.neon',
    'phpunit.xml',
    'README*',
    'rector.php',
    'typoscript-lint.yml',
    '/.deployment',
    '/var',
    '/**/Tests/*',
    'fetchdatabase.sh',
    'pushdatabase.sh',
    'fetchfileadmin.sh',
    'renovate.json',
    'additional.php',
    'sync.sh',
    'config.sh',
    'config.example.sh',
    'rector.php',
    'fractor.php',
    'CLAUDE.md',
    'webpack.config.js',
    'WEBPACK.md',
    '.claude',
    'package.json',
    '.env.example',
    'helperscripts'
];

// Define rsync options
set('rsync', [
    'exclude' => array_merge($sharedDirectories, $sharedFiles, $exclude),
    'exclude-file' => false,
    'include' => [],
    'include-file' => false,
    'filter' => [],
    'filter-file' => false,
    'filter-perdir' => false,
    'flags' => 'az',
    'options' => ['delete'],
    'timeout' => 300,
]);
set('rsync_src', './');

// Use rsync to update code during deployment
task('deploy:update_code', function () {
    invoke('rsync:warmup');
    invoke('rsync');
});

// TYPO3 tasks
desc('Flush page cache');
task('typo3:cache_flush', function () {
    run('php -d memory_limit=512M {{bin/typo3}} cache:flush --group pages');
});

desc('Warm up caches');
task('typo3:cache_warmup', function () {
    run('php -d memory_limit=512M {{bin/typo3}} ' . CACHE_WARMUP . 'group system');
});

desc('Set up all installed extensions');
task('typo3:extension_setup', function () {
    run('php -d memory_limit=512M {{bin/typo3}} extension:setup');
});

desc('Fix folder structure');
task('typo3:fix_folder_structure', function () {
    run('php -d memory_limit=512M {{bin/typo3}} install:fixfolderstructure');
});

desc('Update language files');
task('typo3:language_update', function () {
    run('php -d memory_limit=512M {{bin/typo3}} language:update');
});

desc('Update database schema');
task('typo3:update_database', function () {
    run("php -d memory_limit=512M {{bin/typo3}} database:updateschema '*.add,*.change'");
});

desc('Execute upgrade wizards');
task('typo3:upgrade_all', function () {
    run('php -d memory_limit=512M {{bin/typo3}} upgrade:run');
});

desc('Update reference index');
task('typo3:update_reference_index', function () {
    run("php -d memory_limit=512M {{bin/typo3}} referenceindex:update");
});

task('correct_permissions', function () {
    run('find {{release_path}} -type d -not -path "{{release_path}}/vendor/bin*" -print0 | xargs -0 chmod 0755');
    run('find {{release_path}} -type f -not -path "{{release_path}}/vendor/bin*" -print0 | xargs -0 chmod 0644');
});

// Register TYPO3 tasks
before('deploy:symlink', function () {
    invoke('correct_permissions');
    invoke('typo3:fix_folder_structure');
    invoke('typo3:extension_setup');
    invoke('typo3:upgrade_all');
    invoke('typo3:update_database');
    invoke('typo3:update_reference_index');
    invoke('typo3:language_update');
    invoke('typo3:cache_warmup');
});
after('deploy:symlink', function () {
    invoke('typo3:cache_flush');
});

// Main deployment task
desc('Deploy TYPO3 project');
task('deploy', [
    'deploy:prepare',
    'deploy:publish',
]);

// Unlock on failed deployment
after('deploy:failed', 'deploy:unlock');

