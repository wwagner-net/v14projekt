<?php

defined('TYPO3') || die();

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

ExtensionManagementUtility::registerUserGroupTSConfigFile(
    'vt14sitepackage',
    'Configuration/Sets/vt14sitepackage/TsConfig/User/be_groups.tsconfig',
    'Allgemeines TsConfig für Redakteure'
);
