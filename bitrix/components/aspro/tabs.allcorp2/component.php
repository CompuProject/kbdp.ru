<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>

<?\Bitrix\Main\Loader::includeModule('iblock');
$arTabs = $arShowProp = array();

if(strlen($arParams["FILTER_NAME"])<=0 || !preg_match("/^[A-Za-z_][A-Za-z01-9_]*$/", $arParams["FILTER_NAME"]))
	$arrFilter = array();
else
{
	$arrFilter = $GLOBALS[$arParams["FILTER_NAME"]];
	if(!is_array($arrFilter))
		$arrFilter = array();
}

$arFilter = array("ACTIVE" => "Y", "IBLOCK_ID" => $arParams["IBLOCK_ID"], "!PROPERTY_SHOW_ON_INDEX_PAGE" => false);
if($arParams["SECTION_ID"])
	$arFilter[]=array("SECTION_ID"=>$arParams["SECTION_ID"],"INCLUDE_SUBSECTIONS"=>"Y" );
elseif($arParams["SECTION_CODE"])
	$arFilter[]=array("SECTION_CODE"=>$arParams["SECTION_CODE"],"INCLUDE_SUBSECTIONS"=>"Y" );
	
global $arTheme, $bCatalogIndex;
$bOrderViewBasket = (trim($arTheme["ORDER_VIEW"]["VALUE"]) === "Y");
if(!isset($arTheme["INDEX_TYPE"]["SUB_PARAMS"][$arTheme["INDEX_TYPE"]["VALUE"]]))
	$bCatalogIndex = true;
$arParams["ORDER_VIEW"] = $bOrderViewBasket;
$arParams["DISPLAY_TOP_PAGER"] = $arParams["DISPLAY_BOTTOM_PAGER"] = "N";

foreach($arParams as $key => $value)
{
	if(strpos($key, "~"))
		unset($arParams[$key]);
}
if($bCatalogIndex && $arParams["HIT_PROP"])
{
	$rsProp = CIBlockPropertyEnum::GetList(Array("sort" => "asc", "id" => "desc"), Array("ACTIVE" => "Y", "IBLOCK_ID" => $arParams["IBLOCK_ID"], "CODE" => $arParams["HIT_PROP"]));
	while($arProp = $rsProp->Fetch())
	{
		$arShowProp[$arProp["EXTERNAL_ID"]] = $arProp["VALUE"];
	}

	if($arShowProp)
	{
		foreach($arShowProp as $key => $prop)
		{
			$arItems = array();
			$arFilterProp = array("PROPERTY_".$arParams["HIT_PROP"]."_VALUE" => array($prop));

			$arItems = CCache::CIBLockElement_GetList(array('CACHE' => array("MULTI" => "N", "TAG" => CCache::GetIBlockCacheTag($arParams["IBLOCK_ID"]))), array_merge($arFilter, $arrFilter, $arFilterProp), false, array("nTopCount" => 1), array("ID"));
			if($arItems)
			{
				$arTabs[$key] = array(
					"TITLE" => $prop,
					"FILTER" => array_merge($arFilterProp, $arFilter)
				);
			}
		}
	}
	else
		return;

	$arParams["PROP_CODE"] = $arParams["HIT_PROP"];
	$arResult["TABS"] = $arTabs;

	$this->IncludeComponentTemplate();
}
else
	return;
?>