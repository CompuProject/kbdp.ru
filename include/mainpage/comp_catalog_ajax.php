<?$bAjaxMode = (isset($_POST["AJAX_POST"]) && $_POST["AJAX_POST"] == "Y");
if($bAjaxMode)
{
	require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
	global $APPLICATION;
	\Bitrix\Main\Loader::includeModule("aspro.allcorp2");
}?>
<?if((isset($arParams["IBLOCK_ID"]) && $arParams["IBLOCK_ID"]) || $bAjaxMode):?>
	<?
	$arIncludeParams = ($bAjaxMode ? $_POST["AJAX_PARAMS"] : $arParamsTmp);
	$arGlobalFilter = ($bAjaxMode ? unserialize(urldecode($_POST["GLOBAL_FILTER"])) : array());
	$arComponentParams = unserialize(urldecode($arIncludeParams));
	?>
	
	<?
	if($bAjaxMode && (is_array($arGlobalFilter) && $arGlobalFilter))
		$GLOBALS[$arComponentParams["FILTER_NAME"]] = $arGlobalFilter;
	$GLOBALS[$arComponentParams["FILTER_NAME"]]["!PROPERTY_SHOW_ON_INDEX_PAGE"] = false;
	$template = (isset($arComponentParams["ELEMENTS_TABLE_TYPE_VIEW"]) ? ($arComponentParams["ELEMENTS_TABLE_TYPE_VIEW"] == "FROM_MODULE" ? CAllcorp2::GetFrontParametrValue("ELEMENTS_TABLE_TYPE_VIEW") : $arComponentParams["ELEMENTS_TABLE_TYPE_VIEW"]) : "front-catalog-slider");
	if(isset($arComponentParams["COMPONENT_TEMPLATE"]))
	{
		if(isset($arComponentParams["ELEMENTS_TABLE_TYPE_VIEW"]))
		{
			if($arComponentParams["COMPONENT_TEMPLATE"] == "slider")
			{
				$template = str_replace('catalog_table', 'front-catalog-slider', $template);
			}
		}
	}
?>
	
	<?$APPLICATION->IncludeComponent(
		"bitrix:news.list", 
		$template,
		$arComponentParams,
		false, array('HIDE_ICONS' => 'Y')
	);?>
	
<?endif;?>