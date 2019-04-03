<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
if(!CModule::IncludeModule("iblock")) return;
if(!CModule::IncludeModule("aspro.allcorp2")) return;
	
if(!defined("WIZARD_SITE_ID")) return;
if(!defined("WIZARD_SITE_DIR")) return;
if(!defined("WIZARD_SITE_PATH")) return;
if(!defined("WIZARD_TEMPLATE_ID")) return;
if(!defined("WIZARD_TEMPLATE_ABSOLUTE_PATH")) return;
if(!defined("WIZARD_THEME_ID")) return;

// iblock user fields
$dbSite = CSite::GetByID(WIZARD_SITE_ID);
if($arSite = $dbSite -> Fetch()) $lang = $arSite["LANGUAGE_ID"];
if(!strlen($lang)) $lang = "ru";
WizardServices::IncludeServiceLang("props", $lang);

$bitrixTemplateDir = $_SERVER["DOCUMENT_ROOT"].BX_PERSONAL_ROOT."/templates/".WIZARD_TEMPLATE_ID."/";
//$bitrixTemplateDir = $_SERVER["DOCUMENT_ROOT"]."/local/templates/".WIZARD_TEMPLATE_ID."/";

// iblocks ids
$arIBlocks = array(
	CCache::$arIBlocks[WIZARD_SITE_ID]["aspro_allcorp2_adv"]["aspro_allcorp2_advtbig"][0],
	CCache::$arIBlocks[WIZARD_SITE_ID]["aspro_allcorp2_adv"]["aspro_allcorp2_banners"][0],
	CCache::$arIBlocks[WIZARD_SITE_ID]["aspro_allcorp2_adv"]["aspro_allcorp2_smbanners"][0],
	CCache::$arIBlocks[WIZARD_SITE_ID]["aspro_allcorp2_adv"]["aspro_allcorp2_float_banners"][0],
	CCache::$arIBlocks[WIZARD_SITE_ID]['aspro_allcorp2_adv']['aspro_allcorp2_bg_images'][0],
	CCache::$arIBlocks[WIZARD_SITE_ID]['aspro_allcorp2_catalog']['aspro_allcorp2_catalog_info'][0],
	CCache::$arIBlocks[WIZARD_SITE_ID]['aspro_allcorp2_catalog']['aspro_allcorp2_catalog'][0],
	CCache::$arIBlocks[WIZARD_SITE_ID]['aspro_allcorp2_catalog']['aspro_allcorp2_timetable'][0],
	CCache::$arIBlocks[WIZARD_SITE_ID]['aspro_allcorp2_content']['aspro_allcorp2_reviews'][0],
	CCache::$arIBlocks[WIZARD_SITE_ID]['aspro_allcorp2_content']['aspro_allcorp2_vacancy'][0],
	CCache::$arIBlocks[WIZARD_SITE_ID]['aspro_allcorp2_content']['aspro_allcorp2_faq'][0],
	CCache::$arIBlocks[WIZARD_SITE_ID]['aspro_allcorp2_content']['aspro_allcorp2_partners'][0],
	CCache::$arIBlocks[WIZARD_SITE_ID]['aspro_allcorp2_content']['aspro_allcorp2_articles'][0],
	CCache::$arIBlocks[WIZARD_SITE_ID]['aspro_allcorp2_content']['aspro_allcorp2_news'][0],
	CCache::$arIBlocks[WIZARD_SITE_ID]['aspro_allcorp2_content']['aspro_allcorp2_news_personal'][0],
	CCache::$arIBlocks[WIZARD_SITE_ID]['aspro_allcorp2_content']['aspro_allcorp2_licenses'][0],
	CCache::$arIBlocks[WIZARD_SITE_ID]['aspro_allcorp2_content']['aspro_allcorp2_history'][0],
	CCache::$arIBlocks[WIZARD_SITE_ID]['aspro_allcorp2_content']['aspro_allcorp2_sales'][0],
	CCache::$arIBlocks[WIZARD_SITE_ID]['aspro_allcorp2_content']['aspro_allcorp2_staff'][0],
	CCache::$arIBlocks[WIZARD_SITE_ID]['aspro_allcorp2_content']['aspro_allcorp2_contact'][0],
);

$regionIBlockID = CCache::$arIBlocks[WIZARD_SITE_ID]["aspro_allcorp2_regionality"]["aspro_allcorp2_regions"][0];

$ibp = new CIBlockProperty;

foreach($arIBlocks as $iblockID)
{
	$dbProperty = CIBlockProperty::GetList(array(), array("IBLOCK_ID" => $iblockID, "CODE" => "LINK_REGION"));

	if(!$dbProperty->SelectedRowsCount())
	{
		$arFields = Array(
			"NAME" => GetMessage("CITY"),
			"ACTIVE" => "Y",
			"SORT" => "100",
			"CODE" => "LINK_REGION",
			"PROPERTY_TYPE" => "E",
			"LIST_TYPE" => "L",
			"MULTIPLE" => "Y",
			"LINK_IBLOCK_ID" => $regionIBlockID,
			"MULTIPLE_CNT" => "2",
			"IBLOCK_ID" => $iblockID
		);
		$PropID = $ibp->Add($arFields);
	}

}

$catalogIBlockID = CCache::$arIBlocks[WIZARD_SITE_ID]['aspro_allcorp2_catalog']['aspro_allcorp2_catalog'][0];
$dbProperty = CIBlockProperty::GetList(array(), array("IBLOCK_ID" => $catalogIBlockID, "CODE" => "INCLUDE_TEXT"));
if(!$dbProperty->SelectedRowsCount())
{
	$arFields = Array(
		"NAME" => GetMessage("GARANTY_TEXT"),
		"ACTIVE" => "Y",
		"SORT" => "100",
		"CODE" => "INCLUDE_TEXT",
		"PROPERTY_TYPE" => "S",
		"USER_TYPE" => "HTML",
		"IBLOCK_ID" => $catalogIBlockID
	);
	$PropID = $ibp->Add($arFields);
}

$arUserFieldViewType = CUserTypeEntity::GetList(array(), array("ENTITY_ID" => "IBLOCK_".$catalogIBlockID."_SECTION", "FIELD_NAME" => "UF_INCLUDE_TEXT"))->Fetch();
if(!$arUserFieldViewType)
{
	$arFields = array(
		"FIELD_NAME" => "UF_INCLUDE_TEXT",
		"USER_TYPE_ID" => "string",
		"XML_ID" => "UF_INCLUDE_TEXT",
		"SORT" => 100,
		"MULTIPLE" => "N",
		"MANDATORY" => "N",
		"SHOW_FILTER" => "N",
		"SHOW_IN_LIST" => "Y",
		"EDIT_IN_LIST" => "Y",
		"IS_SEARCHABLE" => "N",
		"SETTINGS" => array(
			"DISPLAY" => "LIST",
			"LIST_HEIGHT" => 5,
		)
	);
	$arLangs = array(
		"EDIT_FORM_LABEL" => array(
			"ru" => GetMessage("GARANTY_TEXT"),
			"en" => "Garanty text",
		),
		"LIST_COLUMN_LABEL" => array(
			"ru" => GetMessage("GARANTY_TEXT"),
			"en" => "Garanty text",
		)
	);
	$ob = new CUserTypeEntity();
	$FIELD_ID = $ob->Add(array_merge($arFields, array("ENTITY_ID" => "IBLOCK_".$catalogIBlockID."_SECTION"), $arLangs));
}

$arUserFieldViewType = CUserTypeEntity::GetList(array(), array("ENTITY_ID" => "IBLOCK_".$catalogIBlockID."_SECTION", "FIELD_NAME" => "UF_POPUP_VIDEO"))->Fetch();
if(!$arUserFieldViewType)
{
	$arFields = array(
		"FIELD_NAME" => "UF_POPUP_VIDEO",
		"USER_TYPE_ID" => "string",
		"XML_ID" => "UF_POPUP_VIDEO",
		"SORT" => 100,
		"MULTIPLE" => "N",
		"MANDATORY" => "N",
		"SHOW_FILTER" => "N",
		"SHOW_IN_LIST" => "Y",
		"EDIT_IN_LIST" => "Y",
		"IS_SEARCHABLE" => "N",
		"SETTINGS" => array(
			"DISPLAY" => "LIST",
			"LIST_HEIGHT" => 5,
		)
	);
	$arLangs = array(
		"EDIT_FORM_LABEL" => array(
			"ru" => GetMessage("POPUP_VIDEO"),
			"en" => "Garanty text",
		),
		"LIST_COLUMN_LABEL" => array(
			"ru" => GetMessage("POPUP_VIDEO"),
			"en" => "Garanty text",
		)
	);
	$ob = new CUserTypeEntity();
	$FIELD_ID = $ob->Add(array_merge($arFields, array("ENTITY_ID" => "IBLOCK_".$catalogIBlockID."_SECTION"), $arLangs));
}


//catalog detail type
$arUserFieldViewType = CUserTypeEntity::GetList(array(), array("ENTITY_ID" => "IBLOCK_".$catalogIBlockID."_SECTION", "FIELD_NAME" => "UF_ELEMENT_DETAIL"))->Fetch();
if(!$arUserFieldViewType)
{
	$arFields = array(
		"FIELD_NAME" => "UF_ELEMENT_DETAIL",
		"USER_TYPE_ID" => "enumeration",
		"XML_ID" => "UF_ELEMENT_DETAIL",
		"SORT" => 100,
		"MULTIPLE" => "N",
		"MANDATORY" => "N",
		"SHOW_FILTER" => "N",
		"SHOW_IN_LIST" => "Y",
		"EDIT_IN_LIST" => "Y",
		"IS_SEARCHABLE" => "N",
		"SETTINGS" => array(
			"DISPLAY" => "LIST",
			"LIST_HEIGHT" => 5,
		)
	);
	$arLangs = array(
		"EDIT_FORM_LABEL" => array(
			"ru" => GetMessage("CATALOG_DETAIL_TYPE"),
			"en" => "Catalog detail type",
		),
		"LIST_COLUMN_LABEL" => array(
			"ru" => GetMessage("CATALOG_DETAIL_TYPE"),
			"en" => "Catalog detail type",
		)
	);

	$ob = new CUserTypeEntity();
	$FIELD_ID = $ob->Add(array_merge($arFields, array("ENTITY_ID" => "IBLOCK_".$catalogIBlockID."_SECTION"), $arLangs));
	if($FIELD_ID)
	{
		$obEnum = new CUserFieldEnum;
		$obEnum->SetEnumValues($FIELD_ID, array(
			"n0" => array(
				"VALUE" => GetMessage("CATALOG_DETAIL_TYPE1"),
				"XML_ID" => "element_1",
			),
			"n1" => array(
				"VALUE" => GetMessage("CATALOG_DETAIL_TYPE2"),
				"XML_ID" => "element_2",
			),
			"n2" => array(
				"VALUE" => GetMessage("CATALOG_DETAIL_TYPE3"),
				"XML_ID" => "element_3",
			),
			"n3" => array(
				"VALUE" => GetMessage("CATALOG_DETAIL_TYPE4"),
				"XML_ID" => "element_4",
			),
			"n4" => array(
				"VALUE" => GetMessage("CATALOG_DETAIL_TYPE5"),
				"XML_ID" => "element_5",
			),
			"n5" => array(
				"VALUE" => GetMessage("CATALOG_DETAIL_TYPE6"),
				"XML_ID" => "element_6",
			),
		));
	}
}
?>