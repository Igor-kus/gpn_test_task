<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Localization\Loc;

$arComponentDescription = [
    'NAME' => Loc::getMessage('GPN_DISPATCHER_LIST_NAME'),
    'DESCRIPTION' => '',
    'PATH' => [
        'ID' => 'gpn',
        'NAME' => Loc::getMessage('GPN_GROUP_NAME'),
    ],
    'CACHE_PATH' => 'Y',
    'COMPLEX' => 'N',
];
