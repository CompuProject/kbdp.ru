<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
if(!CModule::IncludeModule("iblock")) return;

if(!defined("WIZARD_SITE_ID")) return;
if(!defined("WIZARD_SITE_DIR")) return;
if(!defined("WIZARD_SITE_PATH")) return;
if(!defined("WIZARD_TEMPLATE_ID")) return;
if(!defined("WIZARD_TEMPLATE_ABSOLUTE_PATH")) return;
if(!defined("WIZARD_THEME_ID")) return;

$bitrixTemplateDir = $_SERVER["DOCUMENT_ROOT"].BX_PERSONAL_ROOT."/templates/".WIZARD_TEMPLATE_ID."/";
//$bitrixTemplateDir = $_SERVER["DOCUMENT_ROOT"]."/local/templates/".WIZARD_TEMPLATE_ID."/";

$iblockShortCODE = "catalog";
$iblockXMLFile = WIZARD_SERVICE_RELATIVE_PATH."/xml/".LANGUAGE_ID."/".$iblockShortCODE.".xml";
$iblockTYPE = "aspro_allcorp2_catalog";
$iblockXMLID = "aspro_allcorp2_".$iblockShortCODE."_".WIZARD_SITE_ID;
$iblockCODE = "aspro_allcorp2_".$iblockShortCODE;
$iblockID = false;

$rsIBlock = CIBlock::GetList(array(), array("XML_ID" => $iblockXMLID, "TYPE" => $iblockTYPE));
if ($arIBlock = $rsIBlock->Fetch()) {
	$iblockID = $arIBlock["ID"];
	if (WIZARD_INSTALL_DEMO_DATA) {
		// delete if already exist & need install demo
		CIBlock::Delete($arIBlock["ID"]);
		$iblockID = false;
	}
}

if(WIZARD_INSTALL_DEMO_DATA){
	if(!$iblockID){
		// add new iblock
		$permissions = array("1" => "X", "2" => "R");
		$dbGroup = CGroup::GetList($by = "", $order = "", array("STRING_ID" => "content_editor"));
		if($arGroup = $dbGroup->Fetch()){
			$permissions[$arGroup["ID"]] = "W";
		};
		
		// replace macros IN_XML_SITE_ID & IN_XML_SITE_DIR in xml file - for correct url links to site
		if(file_exists($_SERVER["DOCUMENT_ROOT"].$iblockXMLFile.".back")){
			@copy($_SERVER["DOCUMENT_ROOT"].$iblockXMLFile.".back", $_SERVER["DOCUMENT_ROOT"].$iblockXMLFile);
		}
		@copy($_SERVER["DOCUMENT_ROOT"].$iblockXMLFile, $_SERVER["DOCUMENT_ROOT"].$iblockXMLFile.".back");
		CWizardUtil::ReplaceMacros($_SERVER["DOCUMENT_ROOT"].$iblockXMLFile, Array("IN_XML_SITE_DIR" => WIZARD_SITE_DIR));
		CWizardUtil::ReplaceMacros($_SERVER["DOCUMENT_ROOT"].$iblockXMLFile, Array("IN_XML_SITE_ID" => WIZARD_SITE_ID));
		$iblockID = WizardServices::ImportIBlockFromXML($iblockXMLFile, $iblockCODE, $iblockTYPE, WIZARD_SITE_ID, $permissions);
		if(file_exists($_SERVER["DOCUMENT_ROOT"].$iblockXMLFile.".back")){
			@copy($_SERVER["DOCUMENT_ROOT"].$iblockXMLFile.".back", $_SERVER["DOCUMENT_ROOT"].$iblockXMLFile);
		}
		if ($iblockID < 1)	return;
			
		// iblock fields
		$iblock = new CIBlock;
		$arFields = array(
			"ACTIVE" => "Y",
			"CODE" => $iblockCODE,
			"XML_ID" => $iblockXMLID,
			"FIELDS" => array(
				"IBLOCK_SECTION" => array(
					"IS_REQUIRED" => "N",
					"DEFAULT_VALUE" => "",
				),
				"ACTIVE" => array(
					"IS_REQUIRED" => "Y",
					"DEFAULT_VALUE"=> "Y",
				),
				"ACTIVE_FROM" => array(
					"IS_REQUIRED" => "N",
					"DEFAULT_VALUE" => "",
				),
				"ACTIVE_TO" => array(
					"IS_REQUIRED" => "N",
					"DEFAULT_VALUE" => "",
				),
				"SORT" => array(
					"IS_REQUIRED" => "N",
					"DEFAULT_VALUE" => "0",
				), 
				"NAME" => array(
					"IS_REQUIRED" => "Y",
					"DEFAULT_VALUE" => "",
				), 
				"PREVIEW_PICTURE" => array(
					"IS_REQUIRED" => "N",
					"DEFAULT_VALUE" => array(
						"FROM_DETAIL" => "Y",
						"SCALE" => "Y",
						"WIDTH" => "700",
						"HEIGHT" => "700",
						"IGNORE_ERRORS" => "N",
						"METHOD" => "resample",
						"COMPRESSION" => 85,
						"DELETE_WITH_DETAIL" => "N",
						"UPDATE_WITH_DETAIL" => "N",
					),
				), 
				"PREVIEW_TEXT_TYPE" => array(
					"IS_REQUIRED" => "Y",
					"DEFAULT_VALUE" => "text",
				), 
				"PREVIEW_TEXT" => array(
					"IS_REQUIRED" => "N",
					"DEFAULT_VALUE" => "",
				), 
				"DETAIL_PICTURE" => array(
					"IS_REQUIRED" => "N",
					"DEFAULT_VALUE" => array(
						"SCALE" => "Y",
						"WIDTH" => "2000",
						"HEIGHT" => "2000",
						"IGNORE_ERRORS" => "N",
						"METHOD" => "resample",
						"COMPRESSION" => 85,
					),
				), 
				"DETAIL_TEXT_TYPE" => array(
					"IS_REQUIRED" => "Y",
					"DEFAULT_VALUE" => "text",
				), 
				"DETAIL_TEXT" => array(
					"IS_REQUIRED" => "N",
					"DEFAULT_VALUE" => "",
				), 
				"XML_ID" =>  array(
					"IS_REQUIRED" => "N",
					"DEFAULT_VALUE" => "",
				), 
				"CODE" => array(
					"IS_REQUIRED" => "Y",
					"DEFAULT_VALUE" => array(
						"UNIQUE" => "Y",
						"TRANSLITERATION" => "Y",
						"TRANS_LEN" => 100,
						"TRANS_CASE" => "L",
						"TRANS_SPACE" => "-",
						"TRANS_OTHER" => "-",
						"TRANS_EAT" => "Y",
						"USE_GOOGLE" => "N",
					),
				),
				"TAGS" => array(
					"IS_REQUIRED" => "N",
					"DEFAULT_VALUE" => "",
				), 
				"SECTION_NAME" => array(
					"IS_REQUIRED" => "Y",
					"DEFAULT_VALUE" => "",
				), 
				"SECTION_PICTURE" => array(
					"IS_REQUIRED" => "N",
					"DEFAULT_VALUE" => array(
						"FROM_DETAIL" => "Y",
						"SCALE" => "Y",
						"WIDTH" => "700",
						"HEIGHT" => "700",
						"IGNORE_ERRORS" => "N",
						"METHOD" => "resample",
						"COMPRESSION" => 85,
						"DELETE_WITH_DETAIL" => "N",
						"UPDATE_WITH_DETAIL" => "N",
					),
				), 
				"SECTION_DESCRIPTION_TYPE" => array(
					"IS_REQUIRED" => "Y",
					"DEFAULT_VALUE" => "text",
				), 
				"SECTION_DESCRIPTION" => array(
					"IS_REQUIRED" => "N",
					"DEFAULT_VALUE" => "",
				), 
				"SECTION_DETAIL_PICTURE" => array(
					"IS_REQUIRED" => "N",
					"DEFAULT_VALUE" => array(
						"SCALE" => "Y",
						"WIDTH" => "2000",
						"HEIGHT" => "2000",
						"IGNORE_ERRORS" => "N",
						"METHOD" => "resample",
						"COMPRESSION" => 85,
					),
				), 
				"SECTION_XML_ID" => array(
					"IS_REQUIRED" => "N",
					"DEFAULT_VALUE" => "",
				), 
				"SECTION_CODE" => array(
					"IS_REQUIRED" => "Y",
					"DEFAULT_VALUE" => array(
						"UNIQUE" => "Y",
						"TRANSLITERATION" => "Y",
						"TRANS_LEN" => 100,
						"TRANS_CASE" => "L",
						"TRANS_SPACE" => "-",
						"TRANS_OTHER" => "-",
						"TRANS_EAT" => "Y",
						"USE_GOOGLE" => "N",
					),
				), 
			),
		);
		
		$iblock->Update($iblockID, $arFields);
	}
	else{
		// attach iblock to site
		$arSites = array(); 
		$db_res = CIBlock::GetSite($iblockID);
		while ($res = $db_res->Fetch())
			$arSites[] = $res["LID"]; 
		if (!in_array(WIZARD_SITE_ID, $arSites)){
			$arSites[] = WIZARD_SITE_ID;
			$iblock = new CIBlock;
			$iblock->Update($iblockID, array("LID" => $arSites));
		}
	}

	// iblock user fields
	$dbSite = CSite::GetByID(WIZARD_SITE_ID);
	if($arSite = $dbSite -> Fetch()) $lang = $arSite["LANGUAGE_ID"];
	if(!strlen($lang)) $lang = "ru";
	WizardServices::IncludeServiceLang("editform_useroptions.php", $lang);

	$ibp = new CIBlockProperty;
	$dbProperty = CIBlockProperty::GetList(array(), array("IBLOCK_ID" => $iblockID, "CODE" => "INCLUDE_TEXT"));
	if(!$dbProperty->SelectedRowsCount())
	{
		$arFields = Array(
			"NAME" => GetMessage("WZD_OPTION_326"),
			"ACTIVE" => "Y",
			"SORT" => "100",
			"CODE" => "INCLUDE_TEXT",
			"PROPERTY_TYPE" => "S",
			"USER_TYPE" => "HTML",
			"IBLOCK_ID" => $iblockID
		);
		$PropID = $ibp->Add($arFields);
	}

	$dbProperty = CIBlockProperty::GetList(array(), array("IBLOCK_ID" => $iblockID, "CODE" => "POPUP_VIDEO"));
	if(!$dbProperty->SelectedRowsCount())
	{
		$arFields = Array(
			"NAME" => GetMessage("WZD_OPTION_327"),
			"ACTIVE" => "Y",
			"SORT" => "100",
			"CODE" => "POPUP_VIDEO",
			"PROPERTY_TYPE" => "S",
			"IBLOCK_ID" => $iblockID
		);
		$PropID = $ibp->Add($arFields);
	}

	$arProperty = array();
	$dbProperty = CIBlockProperty::GetList(array(), array("IBLOCK_ID" => $iblockID));
	while($arProp = $dbProperty->Fetch())
		$arProperty[$arProp["CODE"]] = $arProp["ID"];

	// edit form user oprions
	CUserOptions::SetOption("form", "form_element_".$iblockID, array(
		"tabs" => 'edit1--#--'.GetMessage("WZD_OPTION_70").'--,--ACTIVE--#--'.GetMessage("WZD_OPTION_2").'--,--NAME--#--'.GetMessage("WZD_OPTION_8").'--,--CODE--#--'.GetMessage("WZD_OPTION_10").'--,--XML_ID--#--'.GetMessage("WZD_OPTION_12").'--,--SORT--#--'.GetMessage("WZD_OPTION_14").'--,--IBLOCK_ELEMENT_PROP_VALUE--#--'.GetMessage("WZD_OPTION_16").'--,--PROPERTY_'.$arProperty["LINK_REGION"].'--#--'.GetMessage("WZD_OPTION_326").'--,--PROPERTY_'.$arProperty["PRICE"].'--#--'.GetMessage("WZD_OPTION_72").'--,--PROPERTY_'.$arProperty["PRICEOLD"].'--#--'.GetMessage("WZD_OPTION_74").'--,--PROPERTY_'.$arProperty["FILTER_PRICE"].'--#--'.GetMessage("WZD_OPTION_184").'--,--PROPERTY_'.$arProperty["ARTICLE"].'--#--'.GetMessage("WZD_OPTION_76").'--,--PROPERTY_'.$arProperty["FORM_QUESTION"].'--#--'.GetMessage("WZD_OPTION_20").'--,--PROPERTY_'.$arProperty["FORM_ORDER"].'--#--'.GetMessage("WZD_OPTION_78").'--,--PROPERTY_'.$arProperty["POPUP_VIDEO"].'--#--'.GetMessage("WZD_OPTION_327").'--,--PROPERTY_'.$arProperty["SHOW_ON_INDEX_PAGE"].'--#--'.GetMessage("WZD_OPTION_80").'--,--PROPERTY_'.$arProperty["HIT"].'--#--'.GetMessage("WZD_OPTION_238").'--,--PROPERTY_'.$arProperty["BRAND"].'--#--'.GetMessage("WZD_OPTION_84").'--,--PROPERTY_'.$arProperty["BEST_ITEM"].'--#--'.GetMessage("WZD_OPTION_275").'--,--edit1_csection1--#---'.GetMessage("WZD_OPTION_239").'--,--PROPERTY_'.$arProperty["SUPPLIED"].'--#--'.GetMessage("WZD_OPTION_240").'--,--PROPERTY_'.$arProperty["CATEGORY"].'--#--'.GetMessage("WZD_OPTION_241").'--,--PROPERTY_'.$arProperty["USERS"].'--#--'.GetMessage("WZD_OPTION_242").'--,--PROPERTY_'.$arProperty["EXTENSION"].'--#--'.GetMessage("WZD_OPTION_243").'--,--PROPERTY_'.$arProperty["UPDATES"].'--#--'.GetMessage("WZD_OPTION_244").'--,--PROPERTY_'.$arProperty["RECOMMEND"].'--#--'.GetMessage("WZD_OPTION_245").'--,--PROPERTY_'.$arProperty["DURATION"].'--#--'.GetMessage("WZD_OPTION_246").'--,--PROPERTY_'.$arProperty["LANGUAGES"].'--#--'.GetMessage("WZD_OPTION_247").'--,--PROPERTY_'.$arProperty["STATUS"].'--#--'.GetMessage("WZD_OPTION_82").'--,--PROPERTY_'.$arProperty["GRUZ"].'--#--'.GetMessage("WZD_OPTION_276").'--,--PROPERTY_'.$arProperty["DLINA_STRELI"].'--#--'.GetMessage("WZD_OPTION_277").'--,--PROPERTY_'.$arProperty["SPEED"].'--#--'.GetMessage("WZD_OPTION_278").'--,--PROPERTY_'.$arProperty["KOL_FORMULA"].'--#--'.GetMessage("WZD_OPTION_279").'--,--PROPERTY_'.$arProperty["MODEL"].'--#--'.GetMessage("WZD_OPTION_280").'--,--PROPERTY_'.$arProperty["GRUZ_STRELI"].'--#--'.GetMessage("WZD_OPTION_281").'--,--PROPERTY_'.$arProperty["MASS"].'--#--'.GetMessage("WZD_OPTION_282").'--,--PROPERTY_'.$arProperty["POWER"].'--#--'.GetMessage("WZD_OPTION_283").'--,--PROPERTY_'.$arProperty["VOLUME"].'--#--'.GetMessage("WZD_OPTION_284").'--,--PROPERTY_'.$arProperty["DEPTH"].'--#--'.GetMessage("WZD_OPTION_285").'--,--PROPERTY_'.$arProperty["TYPE_TUR"].'--#--'.GetMessage("WZD_OPTION_286").'--,--PROPERTY_'.$arProperty["WIDTH_PROHOD"].'--#--'.GetMessage("WZD_OPTION_287").'--,--PROPERTY_'.$arProperty["KARTOPR"].'--#--'.GetMessage("WZD_OPTION_288").'--,--PROPERTY_'.$arProperty["WIDTH_PROEZD"].'--#--'.GetMessage("WZD_OPTION_289").'--,--PROPERTY_'.$arProperty["CLASS"].'--#--'.GetMessage("WZD_OPTION_290").'--,--PROPERTY_'.$arProperty["FREQUENCY"].'--#--'.GetMessage("WZD_OPTION_291").'--,--PROPERTY_'.$arProperty["MARK"].'--#--'.GetMessage("WZD_OPTION_292").'--,--PROPERTY_'.$arProperty["PROIZVODSTVO"].'--#--'.GetMessage("WZD_OPTION_293").'--,--PROPERTY_'.$arProperty["DLINA"].'--#--'.GetMessage("WZD_OPTION_294").'--,--PROPERTY_'.$arProperty["WIDTH"].'--#--'.GetMessage("WZD_OPTION_295").'--,--PROPERTY_'.$arProperty["THICKNESS"].'--#--'.GetMessage("WZD_OPTION_296").'--,--PROPERTY_'.$arProperty["AGE"].'--#--'.GetMessage("WZD_OPTION_297").'--,--PROPERTY_'.$arProperty["MARK_STEEL"].'--#--'.GetMessage("WZD_OPTION_298").'--,--PROPERTY_'.$arProperty["SIZE"].'--#--'.GetMessage("WZD_OPTION_299").'--,--PROPERTY_'.$arProperty["HEIGHT"].'--#--'.GetMessage("WZD_OPTION_300").'--,--PROPERTY_'.$arProperty["PLACE"].'--#--'.GetMessage("WZD_OPTION_301").'--,--PROPERTY_'.$arProperty["SERIES"].'--#--'.GetMessage("WZD_OPTION_302").'--,--PROPERTY_'.$arProperty["DEEP"].'--#--'.GetMessage("WZD_OPTION_303").'--,--PROPERTY_'.$arProperty["DIAGONAL"].'--#--'.GetMessage("WZD_OPTION_304").'--,--PROPERTY_'.$arProperty["RESOLUTION"].'--#--'.GetMessage("WZD_OPTION_305").'--,--PROPERTY_'.$arProperty["TYPE"].'--#--'.GetMessage("WZD_OPTION_306").'--,--PROPERTY_'.$arProperty["COLOR"].'--#--'.GetMessage("WZD_OPTION_307").'--,--PROPERTY_'.$arProperty["FREQUENCE"].'--#--'.GetMessage("WZD_OPTION_308").'--,--PROPERTY_'.$arProperty["CLIMAT_CLASS"].'--#--'.GetMessage("WZD_OPTION_309").'--,--PROPERTY_'.$arProperty["TEMPERATURE"].'--#--'.GetMessage("WZD_OPTION_310").'--,--PROPERTY_'.$arProperty["INCREASE"].'--#--'.GetMessage("WZD_OPTION_311").'--,--PROPERTY_'.$arProperty["LIGHTS_LOCATION"].'--#--'.GetMessage("WZD_OPTION_312").'--;--cedit2--#--'.GetMessage("WZD_OPTION_248").'--,--PROPERTY_'.$arProperty["LINK_TARIF"].'--#--'.GetMessage("WZD_OPTION_248").'--;--cedit4--#--'.GetMessage("WZD_OPTION_186").'--,--PROPERTY_'.$arProperty["VIDEO"].'--#--'.GetMessage("WZD_OPTION_196").'--,--PROPERTY_'.$arProperty["VIDEO_IFRAME"].'--#--'.GetMessage("WZD_OPTION_195").'--;--edit5--#--'.GetMessage("WZD_OPTION_32").'--,--PREVIEW_PICTURE--#--'.GetMessage("WZD_OPTION_34").'--,--PREVIEW_TEXT--#--'.GetMessage("WZD_OPTION_36").'--;--edit6--#--'.GetMessage("WZD_OPTION_38").'--,--DETAIL_PICTURE--#--'.GetMessage("WZD_OPTION_40").'--,--PROPERTY_'.$arProperty["PHOTOS"].'--#--'.GetMessage("WZD_OPTION_18").'--,--PROPERTY_'.$arProperty["GALLEY_BIG"].'--#--'.GetMessage("WZD_OPTION_230").'--,--PROPERTY_'.$arProperty["DOCUMENTS"].'--#--'.GetMessage("WZD_OPTION_92").'--,--PROPERTY_'.$arProperty["INCLUDE_TEXT"].'--#--'.GetMessage("WZD_OPTION_326").'--,--DETAIL_TEXT--#--'.GetMessage("WZD_OPTION_42").'--;--cedit1--#--'.GetMessage("WZD_OPTION_94").'--,--PROPERTY_'.$arProperty["LINK_GOODS"].'--#--'.GetMessage("WZD_OPTION_26").'--,--PROPERTY_'.$arProperty["LINK_SERVICES"].'--#--'.GetMessage("WZD_OPTION_148").'--,--PROPERTY_'.$arProperty["LINK_SALE"].'--#--'.GetMessage("WZD_OPTION_270").'--,--PROPERTY_'.$arProperty["LINK_NEWS"].'--#--'.GetMessage("WZD_OPTION_269").'--,--PROPERTY_'.$arProperty["LINK_PROJECTS"].'--#--'.GetMessage("WZD_OPTION_271").'--,--PROPERTY_'.$arProperty["LINK_REVIEWS"].'--#--'.GetMessage("WZD_OPTION_272").'--,--PROPERTY_'.$arProperty["LINK_STAFF"].'--#--'.GetMessage("WZD_OPTION_273").'--,--PROPERTY_'.$arProperty["LINK_FAQ"].'--#--'.GetMessage("WZD_OPTION_274").'--,--PROPERTY_'.$arProperty["LINK_TIZERS"].'--#--'.GetMessage("WZD_OPTION_235").'--,--PROPERTY_'.$arProperty["LINK_STUDY"].'--#--'.GetMessage("WZD_OPTION_28").'--,--PROPERTY_'.$arProperty["LINK_ARTICLES"].'--#--'.GetMessage("WZD_OPTION_319").'--,--LINKED_PROP--#--'.GetMessage("WZD_OPTION_98").'--;--cedit3--#--'.GetMessage("WZD_OPTION_199").'--,--PROPERTY_'.$arProperty["BNR_TOP"].'--#--'.GetMessage("WZD_OPTION_223").'--,--PROPERTY_'.$arProperty["BNR_TOP_IMG"].'--#--'.GetMessage("WZD_OPTION_224").'--,--PROPERTY_'.$arProperty["BNR_TOP_BG"].'--#--'.GetMessage("WZD_OPTION_225").'--,--PROPERTY_'.$arProperty["CODE_TEXT"].'--#--'.GetMessage("WZD_OPTION_226").'--;--edit14--#--'.GetMessage("WZD_OPTION_44").'--,--IPROPERTY_TEMPLATES_ELEMENT_META_TITLE--#--'.GetMessage("WZD_OPTION_46").'--,--IPROPERTY_TEMPLATES_ELEMENT_META_KEYWORDS--#--'.GetMessage("WZD_OPTION_48").'--,--IPROPERTY_TEMPLATES_ELEMENT_META_DESCRIPTION--#--'.GetMessage("WZD_OPTION_50").'--,--IPROPERTY_TEMPLATES_ELEMENT_PAGE_TITLE--#--'.GetMessage("WZD_OPTION_52").'--,--IPROPERTY_TEMPLATES_ELEMENTS_PREVIEW_PICTURE--#--'.GetMessage("WZD_OPTION_54").'--,--IPROPERTY_TEMPLATES_ELEMENT_PREVIEW_PICTURE_FILE_ALT--#--'.GetMessage("WZD_OPTION_56").'--,--IPROPERTY_TEMPLATES_ELEMENT_PREVIEW_PICTURE_FILE_TITLE--#--'.GetMessage("WZD_OPTION_58").'--,--IPROPERTY_TEMPLATES_ELEMENT_PREVIEW_PICTURE_FILE_NAME--#--'.GetMessage("WZD_OPTION_60").'--,--IPROPERTY_TEMPLATES_ELEMENTS_DETAIL_PICTURE--#--'.GetMessage("WZD_OPTION_62").'--,--IPROPERTY_TEMPLATES_ELEMENT_DETAIL_PICTURE_FILE_ALT--#--'.GetMessage("WZD_OPTION_56").'--,--IPROPERTY_TEMPLATES_ELEMENT_DETAIL_PICTURE_FILE_TITLE--#--'.GetMessage("WZD_OPTION_58").'--,--IPROPERTY_TEMPLATES_ELEMENT_DETAIL_PICTURE_FILE_NAME--#--'.GetMessage("WZD_OPTION_60").'--,--SEO_ADDITIONAL--#--'.GetMessage("WZD_OPTION_64").'--,--TAGS--#--'.GetMessage("WZD_OPTION_66").'--;--edit2--#--'.GetMessage("WZD_OPTION_68").'--,--SECTIONS--#--'.GetMessage("WZD_OPTION_68").'--;--;--',
	));
	// list user options
	CUserOptions::SetOption("list", "tbl_iblock_list_".md5($iblockTYPE.".".$iblockID), array(
		'columns' => 'NAME,PREVIEW_PICTURE,PROPERTY_'.$arProperty["PRICE"].',PROPERTY_'.$arProperty["PRICEOLD"].',PROPERTY_'.$arProperty["FILTER_PRICE"].',PROPERTY_'.$arProperty["SHOW_ON_INDEX_PAGE"].',PROPERTY_'.$arProperty["LINK_REGION"].',ACTIVE,SORT,TIMESTAMP_X,ID', 'by' => 'timestamp_x', 'order' => 'desc', 'page_size' => '20',
	));
}

if($iblockID){
	// replace macros IBLOCK_TYPE & IBLOCK_ID & IBLOCK_CODE
	CWizardUtil::ReplaceMacrosRecursive(WIZARD_SITE_PATH, Array("IBLOCK_CATALOG_TYPE" => $iblockTYPE));
	CWizardUtil::ReplaceMacrosRecursive(WIZARD_SITE_PATH, Array("IBLOCK_CATALOG_ID" => $iblockID));
	CWizardUtil::ReplaceMacrosRecursive(WIZARD_SITE_PATH, Array("IBLOCK_CATALOG_CODE" => $iblockCODE));
	CWizardUtil::ReplaceMacrosRecursive($bitrixTemplateDir, Array("IBLOCK_CATALOG_TYPE" => $iblockTYPE));
	CWizardUtil::ReplaceMacrosRecursive($bitrixTemplateDir, Array("IBLOCK_CATALOG_ID" => $iblockID));
	CWizardUtil::ReplaceMacrosRecursive($bitrixTemplateDir, Array("IBLOCK_CATALOG_CODE" => $iblockCODE));
}
?>
