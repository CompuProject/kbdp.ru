<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
if(!CModule::IncludeModule("iblock")) return;

use \Bitrix\Main\Config\Option;

if (Option::get("aspro.allcorp2", "wizard_installed", "N", WIZARD_SITE_ID) == "Y" && !WIZARD_INSTALL_DEMO_DATA)
	return;

$bitrixTemplateDir = $_SERVER["DOCUMENT_ROOT"].BX_PERSONAL_ROOT."/templates/".WIZARD_TEMPLATE_ID."/";
//$bitrixTemplateDir = $_SERVER["DOCUMENT_ROOT"]."/local/templates/".WIZARD_TEMPLATE_ID."/";

// add iblock types
$arTypes = array(
	array(
		"ID" => "aspro_allcorp2_catalog",
		"SECTIONS" => "Y",
		"IN_RSS" => "N",
		"SORT" => 100,
		"LANG" => array(),
	),
	array(
		"ID" => "aspro_allcorp2_content",
		"SECTIONS" => "Y",
		"IN_RSS" => "N",
		"SORT" => 200,
		"LANG" => array(),
	),
	array(
		"ID" => "aspro_allcorp2_form",
		"SECTIONS" => "N",
		"IN_RSS" => "N",
		"SORT" => 300,
		"LANG" => array(),
	),
	array(
		"ID" => "aspro_allcorp2_adv",
		"SECTIONS" => "N",
		"IN_RSS" => "N",
		"SORT" => 300,
		"LANG" => array(),
	),
	array(
		"ID" => "aspro_allcorp2_regionality",
		"SECTIONS" => "Y",
		"IN_RSS" => "N",
		"SORT" => 400,
		"LANG" => array(),
	),
);

$arLanguages = array();
$rsLanguage = CLanguage::GetList($by, $order, array());
while($arLanguage = $rsLanguage->Fetch())
	$arLanguages[] = $arLanguage["LID"];

$iblockType = new CIBlockType;

foreach($arTypes as $arType){
	$dbType = CIBlockType::GetList(array(), array("=ID" => $arType["ID"]));
	if($dbType->Fetch()) // already exist - don`t add
		continue;

	foreach($arLanguages as $languageID)
	{
		$languageID_include = in_array($languageID, array('ru', 'en')) ? $languageID : 'en';
		WizardServices::IncludeServiceLang("types.php", $languageID_include);
		$code = strtoupper($arType["ID"]."_".$languageID_include);
		$arType["LANG"][$languageID]["NAME"] = GetMessage($code."_TYPE_NAME");
		$arType["LANG"][$languageID]["ELEMENT_NAME"] = GetMessage($code."_ELEMENT_NAME");
		if ($arType["SECTIONS"] == "Y")
			$arType["LANG"][$languageID]["SECTION_NAME"] = GetMessage($code."_SECTION_NAME");
		
	}

	$iblockType->Add($arType);
}

// replace macros IBLOCK_ALLCORP2_CATALOG_TYPE & IBLOCK_ALLCORP2_CONTENT_TYPE & IBLOCK_ALLCORP2_FORM_TYPE
CWizardUtil::ReplaceMacrosRecursive(WIZARD_SITE_PATH, Array("IBLOCK_ALLCORP2_CATALOG_TYPE" => "aspro_allcorp2_catalog"));
CWizardUtil::ReplaceMacrosRecursive(WIZARD_SITE_PATH, Array("IBLOCK_ALLCORP2_CONTENT_TYPE" => "aspro_allcorp2_content"));
CWizardUtil::ReplaceMacrosRecursive(WIZARD_SITE_PATH, Array("IBLOCK_ALLCORP2_FORM_TYPE" => "aspro_allcorp2_form"));
CWizardUtil::ReplaceMacrosRecursive(WIZARD_SITE_PATH, Array("IBLOCK_ALLCORP2_ADV_TYPE" => "aspro_allcorp2_adv"));
CWizardUtil::ReplaceMacrosRecursive($bitrixTemplateDir, Array("IBLOCK_ALLCORP2_CATALOG_TYPE" => "aspro_allcorp2_catalog"));
CWizardUtil::ReplaceMacrosRecursive($bitrixTemplateDir, Array("IBLOCK_ALLCORP2_CONTENT_TYPE" => "aspro_allcorp2_content"));
CWizardUtil::ReplaceMacrosRecursive($bitrixTemplateDir, Array("IBLOCK_ALLCORP2_FORM_TYPE" => "aspro_allcorp2_form"));
CWizardUtil::ReplaceMacrosRecursive($bitrixTemplateDir, Array("IBLOCK_ALLCORP2_ADV_TYPE" => "aspro_allcorp2_adv"));

Option::set('iblock','combined_list_mode','Y');
?>