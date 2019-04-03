<?
global $arSite, $isMenu, $isIndex, $isCabinet, $is404, $bBigBannersIndex, $bServicesIndex, $bPortfolioIndex, $bPartnersIndex, $bTeasersIndex, $bInstagrammIndex, $bReviewsIndex, $bConsultIndex, $bCompanyIndex, $bTeamIndex, $bNewsIndex, $bMapIndex, $bFloatBannersIndex, $bCatalogIndex, $bBlogIndex, $bActiveTheme, $bCatalogSectionsIndex;
global $bBigBannersIndexClass, $bServicesIndexClass, $bPartnersIndexClass, $bTeasersIndexClass, $bFloatBannersIndexClass, $bPortfolioIndexClass, $bCatalogIndexClass,  $bBlogIndexClass, $bInstagrammIndexClass, $bReviewsIndexClass, $bConsultIndexClass, $bCompanyIndexClass, $bTeamIndexClass, $bNewsIndexClass, $bMapIndexClass, $bCatalogSectionsIndexClass;
global $sMenuContent;
$sMenuContent = '';
?>

<?$is404 = defined("ERROR_404") && ERROR_404 === "Y"?>
<?$arSite = CSite::GetByID(SITE_ID)->Fetch();?>
<?$isMenu = ($APPLICATION->GetProperty('MENU') !== "N" ? true : false);?>
<?$isForm = CSite::inDir(SITE_DIR.'form/');?>
<?$isBlog = (CSite::inDir(SITE_DIR.'articles/') || $APPLICATION->GetProperty("BLOG_PAGE") == "Y");?>
<?$isCabinet = CSite::inDir(SITE_DIR.'cabinet/');?>
<?$isIndex = CSite::inDir(SITE_DIR."index.php");?>
<?$bActiveTheme = ($arTheme["THEME_SWITCHER"]["VALUE"] == 'Y');?>
<?if($isIndex = CSite::inDir(SITE_DIR."index.php")):?>
	<?$indexType = $arTheme["INDEX_TYPE"]["VALUE"];?>
	<?$bBigBannersIndex = ($bActiveTheme || (!$bActiveTheme && $arTheme["INDEX_TYPE"]["SUB_PARAMS"][$indexType]["BIG_BANNER_INDEX"]["VALUE"] == 'Y'));?>
	<?$bBigBannersIndexClass = ($arTheme["INDEX_TYPE"]["SUB_PARAMS"][$indexType]["BIG_BANNER_INDEX"]["VALUE"] == 'Y' ? '' : 'hidden');?>
	<?$bServicesIndex = ($bActiveTheme || (!$bActiveTheme && $arTheme["INDEX_TYPE"]["SUB_PARAMS"][$indexType]["SERVICES_INDEX"]["VALUE"] == 'Y'));?>
	<?$bServicesIndexClass = ($arTheme["INDEX_TYPE"]["SUB_PARAMS"][$indexType]["SERVICES_INDEX"]["VALUE"] == 'Y' ? '' : 'hidden');?>
	<?$bPartnersIndex = ($bActiveTheme || (!$bActiveTheme && $arTheme["INDEX_TYPE"]["SUB_PARAMS"][$indexType]["PARTNERS_INDEX"]["VALUE"] == 'Y'));?>
	<?$bPartnersIndexClass = ($arTheme["INDEX_TYPE"]["SUB_PARAMS"][$indexType]["PARTNERS_INDEX"]["VALUE"] == 'Y' ? '' : 'hidden');?>
	<?$bTeasersIndex = ($bActiveTheme || (!$bActiveTheme && $arTheme["INDEX_TYPE"]["SUB_PARAMS"][$indexType]["TEASERS_INDEX"]["VALUE"] == 'Y'));?>
	<?$bTeasersIndexClass = ($arTheme["INDEX_TYPE"]["SUB_PARAMS"][$indexType]["TEASERS_INDEX"]["VALUE"] == 'Y' ? '' : 'hidden');?>
	<?$bFloatBannersIndex = ($bActiveTheme || (!$bActiveTheme && $arTheme["INDEX_TYPE"]["SUB_PARAMS"][$indexType]["FLOAT_BANNERS_INDEX"]["VALUE"] == 'Y'));?>
	<?$bFloatBannersIndexClass = ($arTheme["INDEX_TYPE"]["SUB_PARAMS"][$indexType]["FLOAT_BANNERS_INDEX"]["VALUE"] == 'Y' ? '' : 'hidden');?>
	<?$bPortfolioIndex = ($bActiveTheme || (!$bActiveTheme && $arTheme["INDEX_TYPE"]["SUB_PARAMS"][$indexType]["PORTFOLIO_INDEX"]["VALUE"] == 'Y'));?>
	<?$bPortfolioIndexClass = ($arTheme["INDEX_TYPE"]["SUB_PARAMS"][$indexType]["PORTFOLIO_INDEX"]["VALUE"] == 'Y' ? '' : 'hidden');?>
	<?$bBlogIndex = ($bActiveTheme || (!$bActiveTheme && $arTheme["INDEX_TYPE"]["SUB_PARAMS"][$indexType]["BLOG_INDEX"]["VALUE"] == 'Y'));?>
	<?$bBlogIndexClass = ($arTheme["INDEX_TYPE"]["SUB_PARAMS"][$indexType]["BLOG_INDEX"]["VALUE"] == 'Y' ? '' : 'hidden');?>
	<?$bCatalogIndex = ($bActiveTheme || (!$bActiveTheme && $arTheme["INDEX_TYPE"]["SUB_PARAMS"][$indexType]["CATALOG_INDEX"]["VALUE"] == 'Y'));?>
	<?$bCatalogIndexClass = ($arTheme["INDEX_TYPE"]["SUB_PARAMS"][$indexType]["CATALOG_INDEX"]["VALUE"] == 'Y' ? '' : 'hidden');?>
	<?$bCatalogSectionsIndex = ($bActiveTheme || (!$bActiveTheme && $arTheme["INDEX_TYPE"]["SUB_PARAMS"][$indexType]["CATALOG_SECTIONS_INDEX"]["VALUE"] == 'Y'));?>
	<?$bCatalogSectionsIndexClass = ($arTheme["INDEX_TYPE"]["SUB_PARAMS"][$indexType]["CATALOG_SECTIONS_INDEX"]["VALUE"] == 'Y' ? '' : 'hidden');?>
	<?if(isset($arTheme["INDEX_TYPE"]["SUB_PARAMS"][$indexType]["INSTAGRAMM_INDEX"]))
	{
		$bInstagrammIndex = ($bActiveTheme || (!$bActiveTheme && $arTheme["INDEX_TYPE"]["SUB_PARAMS"][$indexType]["INSTAGRAMM_INDEX"]["VALUE"] == 'Y'));
		$bInstagrammIndexClass = ($arTheme["INDEX_TYPE"]["SUB_PARAMS"][$indexType]["INSTAGRAMM_INDEX"]["VALUE"] == 'Y' ? '' : 'hidden');
	}
	else
	{
		$bInstagrammIndex = true;
	}?>
	<?$bReviewsIndex = ($bActiveTheme || (!$bActiveTheme && $arTheme["INDEX_TYPE"]["SUB_PARAMS"][$indexType]["REVIEWS_INDEX"]["VALUE"] == 'Y'));?>
	<?$bReviewsIndexClass = ($arTheme["INDEX_TYPE"]["SUB_PARAMS"][$indexType]["REVIEWS_INDEX"]["VALUE"] == 'Y' ? '' : 'hidden');?>
	<?$bConsultIndex = ($bActiveTheme || (!$bActiveTheme && $arTheme["INDEX_TYPE"]["SUB_PARAMS"][$indexType]["CONSULT_INDEX"]["VALUE"] == 'Y'));?>
	<?$bConsultIndexClass = ($arTheme["INDEX_TYPE"]["SUB_PARAMS"][$indexType]["CONSULT_INDEX"]["VALUE"] == 'Y' ? '' : 'hidden');?>
	<?$bCompanyIndex = ($bActiveTheme || (!$bActiveTheme && $arTheme["INDEX_TYPE"]["SUB_PARAMS"][$indexType]["COMPANY_INDEX"]["VALUE"] == 'Y'));?>
	<?$bCompanyIndexClass = ($arTheme["INDEX_TYPE"]["SUB_PARAMS"][$indexType]["COMPANY_INDEX"]["VALUE"] == 'Y' ? '' : 'hidden');?>
	<?$bTeamIndex = ($bActiveTheme || (!$bActiveTheme && $arTheme["INDEX_TYPE"]["SUB_PARAMS"][$indexType]["TEAM_INDEX"]["VALUE"] == 'Y'));?>
	<?$bTeamIndexClass = ($arTheme["INDEX_TYPE"]["SUB_PARAMS"][$indexType]["TEAM_INDEX"]["VALUE"] == 'Y' ? '' : 'hidden');?>
	<?$bNewsIndex = ($bActiveTheme || (!$bActiveTheme && $arTheme["INDEX_TYPE"]["SUB_PARAMS"][$indexType]["NEWS_INDEX"]["VALUE"] == 'Y'));?>
	<?$bNewsIndexClass = ($arTheme["INDEX_TYPE"]["SUB_PARAMS"][$indexType]["NEWS_INDEX"]["VALUE"] == 'Y' ? '' : 'hidden');?>
	<?$bMapIndex = ($bActiveTheme || (!$bActiveTheme && $arTheme["INDEX_TYPE"]["SUB_PARAMS"][$indexType]["MAP_INDEX"]["VALUE"] == 'Y'));?>
	<?$bMapIndexClass = ($arTheme["INDEX_TYPE"]["SUB_PARAMS"][$indexType]["MAP_INDEX"]["VALUE"] == 'Y' ? '' : 'hidden');?>
<?endif;?>

<?$GLOBALS['arrPopularSections'] = array('UF_SHOW_ON_INDEX_PAG' => 1);?>
<?$GLOBALS['arFrontFilter'] = array('PROPERTY_SHOW_ON_INDEX_PAGE_VALUE' => 'Y');?>
<?$GLOBALS['arFilterLeftBlock'] = array('PROPERTY_SHOW_ON_LEFT_BLOCK_VALUE' => 'Y');?>
<?$GLOBALS['arFilterBestItem'] = array('PROPERTY_BEST_ITEM_VALUE' => 'Y');?>

<?
global $arRegion;
if($isIndex)
{
	$GLOBALS['arRegionLinkFront'] = array('PROPERTY_SHOW_ON_INDEX_PAGE_VALUE' => 'Y');
}
if($arRegion && $arTheme['USE_REGIONALITY']['DEPENDENT_PARAMS']['REGIONALITY_FILTER_ITEM']['VALUE'] == 'Y')
{
	$GLOBALS['arRegionLink'] = array('PROPERTY_LINK_REGION' => $arRegion['ID']);
	if($isIndex)
	{
		$GLOBALS['arRegionLinkFront']['PROPERTY_LINK_REGION'] = $arRegion['ID'];
	}
}
?>