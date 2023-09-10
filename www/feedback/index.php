<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Форма обратной связи");
?>

<?php
$APPLICATION->IncludeComponent(
    "custom:custom.form",
    "",
    [
        "HL_BLOCK_ID" => "1",
    ],
	false
);
?>

<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>