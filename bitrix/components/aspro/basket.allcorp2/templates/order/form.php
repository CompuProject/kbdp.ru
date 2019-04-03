<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?global $arTheme;?>
<?$APPLICATION->IncludeComponent(
	"aspro:form.allcorp2", 
	"order", 
	array(
		"IBLOCK_TYPE" => "aspro_allcorp2_form",
		"IBLOCK_ID" => CAllcorp2::getFormID("aspro_allcorp2_order_page"),
		"USE_CAPTCHA" => "N",
		"IS_PLACEHOLDER" => "N",
		"SEND_BUTTON_NAME" => GetMessage('T_BASKET_BUTTON_ORDER'),
		"SEND_BUTTON_CLASS" => "btn btn-default",
		"DISPLAY_CLOSE_BUTTON" => "N",
		"SHOW_LICENCE" => $arTheme["SHOW_LICENCE"]["VALUE"],
		"LICENCE_TEXT" => $arTheme["SHOW_LICENCE"]["DEPENDENT_PARAMS"]["LICENCE_TEXT"]["VALUE"],
		"AJAX_MODE" => "N",
		"TOTAL_SUMM" => $arResult['ALL_SUM'],
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "N",
		"AJAX_OPTION_HISTORY" => "N",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "3600000",
		"AJAX_OPTION_ADDITIONAL" => "",
		"COMPONENT_TEMPLATE" => "order",
	),
	false,
	array('HIDE_ICONS' => 'Y')
);?>