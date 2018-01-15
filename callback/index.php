<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Заказать обратный звонок");
?><?$APPLICATION->IncludeComponent(
	"aero:add.callback.element", 
	".default", 
	array(
		"IBLOCK_ID" => "5",
		"IBLOCK_TYPE" => "callback",
		"PROPERTY_CODES" => array(
			0 => "9",
			1 => "10",
			2 => "11",
			3 => "12",
		),
		"PROPERTY_CODES_REQUIRED" => array(
			0 => "9",
			1 => "10",
			2 => "11",
			3 => "12",
		),
		"USE_CAPTCHA" => "N",
		"COMPONENT_TEMPLATE" => ".default"
	),
	false
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>