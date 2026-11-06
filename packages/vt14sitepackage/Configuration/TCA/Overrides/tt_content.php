<?php

defined('TYPO3') || die();

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use B13\Container\Tca\Registry;
use B13\Container\Tca\ContainerConfiguration;

$disallowedContentTypes = '2cols,3cols,4cols,2cols-left,2cols-right';

GeneralUtility::makeInstance(Registry::class)->configureContainer(

    new ContainerConfiguration(
        '1cols', // CType
        '1 Column Container', // label
        '100%', // description
        [
            [
                ['name' => 'Container 100%', 'colPos' => 100]
            ]
        ] // grid configuration

    )
        // set an optional icon configuration
        ->setIcon('EXT:container/Resources/Public/Icons/container-1col.svg')
);

GeneralUtility::makeInstance(Registry::class)->configureContainer(

    new ContainerConfiguration(
        '2cols', // CType
        '2 Column Container', // label
        '50% / 50%', // description
        [
            [
                ['name' => 'Linke Spalte', 'colPos' => 100, 'disallowedContentTypes' => $disallowedContentTypes],
                ['name' => 'Rechte Spalte', 'colPos' => 200, 'disallowedContentTypes' => $disallowedContentTypes]
            ]
        ] // grid configuration

    )
        // set an optional icon configuration
        ->setIcon('EXT:container/Resources/Public/Icons/container-2col.svg')
);

GeneralUtility::makeInstance(Registry::class)->configureContainer(

    new ContainerConfiguration(
        '3cols', // CType
        '3 Column Container', // label
        '33% / 33% / 33%', // description
        [
            [
                ['name' => 'Linke Spalte', 'colPos' => 100, 'disallowedContentTypes' => $disallowedContentTypes],
                ['name' => 'Mittlere Spalte', 'colPos' => 200, 'disallowedContentTypes' => $disallowedContentTypes],
                ['name' => 'Rechte Spalte', 'colPos' => 300, 'disallowedContentTypes' => $disallowedContentTypes]
            ]
        ] // grid configuration

    )
        // set an optional icon configuration
        ->setIcon('EXT:container/Resources/Public/Icons/container-3col.svg')
);

GeneralUtility::makeInstance(Registry::class)->configureContainer(

    new ContainerConfiguration(
        '4cols', // CType
        '4 Column Container', // label
        '25% / 25% / 25% / 25%', // description
        [
            [
                ['name' => 'Linke Spalte', 'colPos' => 100, 'disallowedContentTypes' => $disallowedContentTypes],
                ['name' => 'Linke Mittlere Spalte', 'colPos' => 200, 'disallowedContentTypes' => $disallowedContentTypes],
                ['name' => 'Linke Rechte Spalte', 'colPos' => 300, 'disallowedContentTypes' => $disallowedContentTypes],
                ['name' => 'Rechte Spalte', 'colPos' => 400, 'disallowedContentTypes' => $disallowedContentTypes]
            ]
        ] // grid configuration

    )
        // set an optional icon configuration
        ->setIcon('EXT:container/Resources/Public/Icons/container-4col.svg')
);

GeneralUtility::makeInstance(Registry::class)->configureContainer(

    new ContainerConfiguration(
        '2cols-left', // CType
        '2 Column Container 66% / 33%', // label
        '66% / 33%', // description
        [
            [
                ['name' => 'Linke Spalte', 'colPos' => 100, 'colspan' => 2, 'disallowedContentTypes' => $disallowedContentTypes],
                ['name' => 'Rechte Spalte', 'colPos' => 200, 'colspan' => 1, 'disallowedContentTypes' => $disallowedContentTypes]
            ]
        ] // grid configuration

    )
        // set an optional icon configuration
        ->setIcon('EXT:container/Resources/Public/Icons/container-2col-left.svg')
);

GeneralUtility::makeInstance(Registry::class)->configureContainer(

    new ContainerConfiguration(
        '2cols-right', // CType
        '2 Column Container 33% / 66%', // label
        '33% / 66%', // description
        [
            [
                ['name' => 'Linke Spalte', 'colPos' => 100, 'colspan' => 1, 'disallowedContentTypes' => $disallowedContentTypes],
                ['name' => 'Rechte Spalte', 'colPos' => 200, 'colspan' => 2, 'disallowedContentTypes' => $disallowedContentTypes]
            ]
        ] // grid configuration

    )
        // set an optional icon configuration
        ->setIcon('EXT:container/Resources/Public/Icons/container-2col-right.svg')
);



ExtensionManagementUtility::addTcaSelectItemGroup(
    'tt_content', // table
    'CType', // typeField
    'marketing', // group
    'Marketing', // label
    'before:default', // position
);
