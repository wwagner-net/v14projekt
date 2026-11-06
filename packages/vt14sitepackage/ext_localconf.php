<?php

defined('TYPO3') || die();

$GLOBALS['TYPO3_CONF_VARS']['RTE']['Presets']['mypreset'] = 'EXT:vt14sitepackage/Configuration/RTE/mypreset.yaml';

$GLOBALS['TYPO3_CONF_VARS']['SYS']['passwordPolicies']['mypolicy'] = [
    'validators' => [
        \TYPO3\CMS\Core\PasswordPolicy\Validator\CorePasswordValidator::class => [
            'options' => [
                'digitCharacterRequired' => true,
                'lowerCaseCharacterRequired' => true,
                'minimumLength' => 20,
                'specialCharacterRequired' => true,
                'upperCaseCharacterRequired' => true
            ]
        ]
    ],
    'generator' => [
        'className' => \TYPO3\CMS\Core\PasswordPolicy\Generator\PasswordGenerator::class,
        'options' => [
            'length' => 60,
            'upperCaseCharacters' => true,
            'lowerCaseCharacters' => true,
            'digitCharacters' => true,
            'specialCharacters' => true,
        ],
    ],
];

$GLOBALS['TYPO3_CONF_VARS']['BE']['passwordPolicy'] = 'mypolicy';
$GLOBALS['TYPO3_CONF_VARS']['FE']['passwordPolicy'] = 'mypolicy';

$GLOBALS['TYPO3_CONF_VARS']['GFX']['imageFileConversionFormats'] = [
    'jpg' => 'webp',
    'jpeg' => 'webp',
    'gif' => 'webp',
    'svg' => 'svg',
    'png' => 'webp',
    'default' => 'webp'
];

