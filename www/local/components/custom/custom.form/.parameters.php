<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Loader,
    Bitrix\Highloadblock\HighloadBlockTable,
    Bitrix\Main\Localization\Loc;

if (!Loader::includeModule('highloadblock')) return;

$arHLBlock = [];
$arHlData = HighloadBlockTable::getList([
    'select' => ["ID", "NAME"],
    'order' => ['ID' => 'ASC'],
    'limit' => '50',
]);

while ($arHlbk = $arHlData->fetch()) {
    $arHLBlock[$arHlbk['ID']] = "[" . $arHlbk["ID"] . "] " . $arHlbk['NAME'];
}

$arComponentParameters = [
    "GROUPS" => [],
    "PARAMETERS" => [
        "HL_BLOCK_ID" => [
            "PARENT" => "BASE",
            "NAME" => Loc::getMessage("HL_BLOCK_ID"),
            "TYPE" => "LIST",
            "VALUES" => $arHLBlock,
            "REFRESH" => "Y",
            "ADDITIONAL_VALUES" => "Y",
        ],
    ],
];