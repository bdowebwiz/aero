<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

if(!CModule::IncludeModule("iblock"))
	return;

if($arCurrentValues["IBLOCK_ID"] > 0) {
	$arIBlock = CIBlock::GetArrayByID($arCurrentValues["IBLOCK_ID"]);


}

$arIBlockType = CIBlockParameters::GetIBlockTypes();
$arIBlock=array();
$rsIBlock = CIBlock::GetList(Array("sort" => "asc"), Array("TYPE" => $arCurrentValues["IBLOCK_TYPE"], "ACTIVE"=>"Y"));
while($arr=$rsIBlock->Fetch())
{
	$arIBlock[$arr["ID"]] = "[".$arr["ID"]."] ".$arr["NAME"];
}

$arProperty_LNSF = array(

);
$arVirtualProperties = $arProperty_LNSF;

$rsProp = CIBlockProperty::GetList(Array("sort"=>"asc", "name"=>"asc"), Array("ACTIVE"=>"Y", "IBLOCK_ID"=>$arCurrentValues["IBLOCK_ID"], "PROPERTY_TYPE"=>"S" ));
while ($arr=$rsProp->Fetch())
{
	$arProperty[$arr["ID"]] = "[".$arr["CODE"]."] ".$arr["NAME"];
	if (in_array($arr["PROPERTY_TYPE"], array("L", "N", "S", "F")))
	{
		$arProperty_LNSF[$arr["ID"]] = "[".$arr["CODE"]."] ".$arr["NAME"];
	}
}



$arComponentParameters = array(
	"GROUPS" => array(
		"PARAMS" => array(
			"NAME" => GetMessage("IBLOCK_PARAMS"),
			"SORT" => "200"
		),
		"ACCESS" => array(
			"NAME" => GetMessage("IBLOCK_ACCESS"),
			"SORT" => "400",
		),
		"FIELDS" => array(
			"NAME" => GetMessage("IBLOCK_FIELDS"),
			"SORT" => "300",
		),
		"TITLES" => array(
			"NAME" => GetMessage("IBLOCK_TITLES"),
			"SORT" => "1000",
		),
	),

	"PARAMETERS" => array(
		"IBLOCK_TYPE" => array(
			"PARENT" => "DATA_SOURCE",
			"NAME" => GetMessage("IBLOCK_TYPE"),
			"TYPE" => "LIST",
			"ADDITIONAL_VALUES" => "Y",
			"VALUES" => $arIBlockType,
			"REFRESH" => "Y",
		),

		"IBLOCK_ID" => array(
			"PARENT" => "DATA_SOURCE",
			"NAME" => GetMessage("IBLOCK_IBLOCK"),
			"TYPE" => "LIST",
			"ADDITIONAL_VALUES" => "Y",
			"VALUES" => $arIBlock,
			"REFRESH" => "Y",
		),

		"PROPERTY_CODES" => array(
			"PARENT" => "FIELDS",
			"NAME" => GetMessage("IBLOCK_PROPERTY"),
			"TYPE" => "LIST",
			"MULTIPLE" => "Y",
			"VALUES" => $arProperty_LNSF,
		),

		"PROPERTY_CODES_REQUIRED" => array(
			"PARENT" => "FIELDS",
			"NAME" => GetMessage("IBLOCK_PROPERTY_REQUIRED"),
			"TYPE" => "LIST",
			"MULTIPLE" => "Y",
			"ADDITIONAL_VALUES" => "N",
			"VALUES" => $arProperty_LNSF,
		),

	),
);
/*

$arComponentParameters["PARAMETERS"]["USE_CAPTCHA"] = array(
	"PARENT" => "PARAMS",
	"NAME" => GetMessage("IBLOCK_USE_CAPTCHA"),
	"TYPE" => "CHECKBOX",
);



foreach ($arVirtualProperties as $key => $title)
{
	$arComponentParameters["PARAMETERS"]["CUSTOM_TITLE_".$key] = array(
		"PARENT" => "TITLES",
		"NAME" => $title,
		"TYPE" => "STRING",
	);
}

*/

?>