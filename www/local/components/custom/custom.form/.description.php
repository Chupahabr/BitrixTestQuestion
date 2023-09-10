<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

$arComponentDescription = [
    "NAME" => Loc::getMessage("COMPONENT_NAME"),
    "DESCRIPTION" => Loc::getMessage("COMPONENT_DESCRIPTION"),
    "CACHE_PATH" => "Y",
    "SORT" => 50,
    "PATH" => [
        "ID" => "custom",
    ],
];
?>