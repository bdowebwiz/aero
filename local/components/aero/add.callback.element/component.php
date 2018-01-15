<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/** @var CBitrixComponent $this */
/** @var array $arParams */
/** @var array $arResult */
/** @var string $componentPath */
/** @var string $componentName */
/** @var string $componentTemplate */
/** @global CDatabase $DB */
/** @global CUser $USER */
/** @global CMain $APPLICATION */
$this->setFrameMode(false);

if(!CModule::IncludeModule("iblock"))
{
	ShowError(GetMessage("CC_BIEAF_IBLOCK_MODULE_NOT_INSTALLED"));
	return;
}

$arParams["ID"] = intval($_REQUEST["CODE"]);

$rsIBLockPropertyList = CIBlockProperty::GetList(
	array("sort"=>"asc"),
	array("ACTIVE"=>"Y","PROPERTY_TYPE"=>"S", "IBLOCK_ID"=>$arParams["IBLOCK_ID"]),
	array("ttl" => 360, "cache_joins" => true)
);
while ($arProperty = $rsIBLockPropertyList->GetNext())
{

	if (in_array($arProperty["ID"], $arParams["PROPERTY_CODES"])){
		$arResult["PROPERTY_LIST"][] = $arProperty["ID"];
	}
	$arResult["PROPERTY_LIST_FULL"][$arProperty["ID"]] = $arProperty;
}

$arResult["PROPERTY_REQUIRED"] = is_array($arParams["PROPERTY_CODES_REQUIRED"]) ? $arParams["PROPERTY_CODES_REQUIRED"] : array();

if ((!empty($_REQUEST["iblock_submit"]) || !empty($_REQUEST["iblock_apply"]))) {
	$el = new CIBlockElement;
	foreach ($_REQUEST['PROPERTY'] as $key => $arItem) {

		if($arResult['PROPERTY_LIST_FULL'][$key]['CODE'] == 'EMAIL'){
			if ( filter_var($arItem, FILTER_VALIDATE_EMAIL) === false) {
				$arResult["ERRORS"][$key] = GetMessage("IBLOCK_ERROR_EMAIL");
			}
		}elseif($arResult['PROPERTY_LIST_FULL'][$key]['CODE'] == 'PHONE'){

			if( preg_match('/^[0-9]{11,20}$/', $arItem) <= 0 ) {
				$arResult["ERRORS"][$key] = GetMessage("IBLOCK_ERROR_PHONE");
			}
		}

		if(in_array($key, $arParams["PROPERTY_CODES_REQUIRED"])){
			if (strlen($arItem) <= 0) {
				$arResult["ERRORS"][$key] = GetMessage("IBLOCK_ERROR_INPUT");
			}
		}
	}

    if (empty($arResult["ERRORS"])){

		$fields = array(
			'IBLOCK_ID' => $arParams['IBLOCK_ID'],
			'NAME' =>"Заявка на обратный звонок от ".date('d.m.Y', time()),
			'ACTIVE' => "N",
			"PROPERTY_VALUES" => $_REQUEST['PROPERTY']
		);

		if ($PRODUCT_ID = $el->Add($fields)) {

			$arTextMessage = 'Информация о пользователе: <br><br>';
				foreach ($_REQUEST['PROPERTY'] as $key => $arItemPro ) {
					$arTextMessage = $arTextMessage." ".$arResult['PROPERTY_LIST_FULL'][$key]['NAME'].":  ".$arItemPro." <br>";
				}
			CEvent::Send("callback", "s1", array("DATA"  =>  $arTextMessage));
			$sRedirectUrl = $APPLICATION->GetCurPageParam("add=Y&result=".$PRODUCT_ID, array("add", "result"), $get_index_page=false);
			LocalRedirect($sRedirectUrl);
		} else {
			$arResult["ERRORS"] =  "При сохранении заявки произошла ошибка [" . $PRODUCT_ID . "]: " . $el->LAST_ERROR . '<br />';
		}
    }
}

$this->includeComponentTemplate();