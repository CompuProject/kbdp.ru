<?
/**
 * Allcorp2 module
 * @copyright 2017 Aspro
 */

if(!defined('ALLCORP2_MODULE_ID'))
	define('ALLCORP2_MODULE_ID', 'aspro.allcorp2');

IncludeModuleLangFile(__FILE__);
use \Bitrix\Main\Type\Collection,
	\Bitrix\Main\IO\File,
	\Bitrix\Main\Localization\Loc,
	\Bitrix\Main\Config\Option;

// initialize module parametrs list and default values
include_once __DIR__.'/../../parametrs.php';

class CAllcorp2{
	const MODULE_ID = ALLCORP2_MODULE_ID;
	const PARTNER_NAME = 'aspro';
	const SOLUTION_NAME = 'allcorp2';
	const devMode = false; // set to false before release

	static $arParametrsList = array();
	private static $arMetaParams = array();

	public function checkModuleRight($reqRight = 'R', $bShowError = false){
		global $APPLICATION;

		if($APPLICATION->GetGroupRight(self::MODULE_ID) < $reqRight){
			if($bShowError){
				$APPLICATION->AuthForm(GetMessage('ALLCORP2_ACCESS_DENIED'));
			}
			return false;
		}

		return true;
	}

	public static function ClearSomeComponentsCache($SITE_ID){
		CBitrixComponent::clearComponentCache('bitrix:news.list', $SITE_ID);
		CBitrixComponent::clearComponentCache('bitrix:news.detail', $SITE_ID);
	}

	public static function AjaxAuth(){
		if(!defined('ADMIN_SECTION') && isset($_REQUEST['auth_service_id']) && $_REQUEST['auth_service_id'])
		{
			if($_REQUEST['auth_service_id']):
				global $APPLICATION, $CACHE_MANAGER;?>
				<?$APPLICATION->IncludeComponent(
					"bitrix:system.auth.form",
					"popup",
					array(
						"PROFILE_URL" => "",
						"SHOW_ERRORS" => "Y",
						"POPUP_AUTH" => "Y"
					)
				);?>
			<?endif;?>
		<?}
	}

	public static function GetSections($arItems, $arParams){
		$arSections = array(
			'PARENT_SECTIONS' => array(),
			'CHILD_SECTIONS' => array(),
			'ALL_SECTIONS' => array(),
		);
		if(is_array($arItems) && $arItems)
		{
			$arSectionsIDs = array();
			foreach($arItems as $arItem)
			{
				if($SID = $arItem['IBLOCK_SECTION_ID'])
					$arSectionsIDs[] = $SID;
			}
			if($arSectionsIDs)
			{
				$arSections['ALL_SECTIONS'] = CCache::CIBLockSection_GetList(array('SORT' => 'ASC', 'NAME' => 'ASC', 'CACHE' => array('TAG' => CCache::GetIBlockCacheTag($arParams['IBLOCK_ID']), 'GROUP' => array('ID'), 'MULTI' => 'N')), array('ID' => $arSectionsIDs));
				$bCheckRoot = false;
				foreach($arSections['ALL_SECTIONS'] as $key => $arSection)
				{
					if($arSection['DEPTH_LEVEL'] > 1)
					{
						$bCheckRoot = true;
						$arSections['CHILD_SECTIONS'][$key] = $arSection;
						unset($arSections['ALL_SECTIONS'][$key]);

						$arFilter = array('IBLOCK_ID'=>$arSection['IBLOCK_ID'], '<=LEFT_BORDER' => $arSection['LEFT_MARGIN'], '>=RIGHT_BORDER' => $arSection['RIGHT_MARGIN'], 'DEPTH_LEVEL' => 1);
						$arSelect = array('ID', 'SORT', 'IBLOCK_ID', 'NAME');
						$arParentSection = CCache::CIBLockSection_GetList(array('SORT' => 'ASC', 'NAME' => 'ASC', 'CACHE' => array('TAG' => CCache::GetIBlockCacheTag($arParams['IBLOCK_ID']), 'MULTI' => 'N')), $arFilter, false, $arSelect);

						$arSections['ALL_SECTIONS'][$arParentSection['ID']]['SECTION'] = $arParentSection;
						$arSections['ALL_SECTIONS'][$arParentSection['ID']]['CHILD_IDS'][$arSection['ID']] = $arSection['ID'];

						$arSections['PARENT_SECTIONS'][$arParentSection['ID']] = $arParentSection;
					}
					else
					{
						$arSections['ALL_SECTIONS'][$key]['SECTION'] = $arSection;
						$arSections['PARENT_SECTIONS'][$key] = $arSection;
					}
				}

				if($bCheckRoot)
				{
					// get root sections
					$arFilter = array('IBLOCK_ID' => $arParams['IBLOCK_ID'], 'ACTIVE' => 'Y', 'DEPTH_LEVEL' => 1, 'ID' => array_keys($arSections['ALL_SECTIONS']));
					$arSelect = array('ID', 'SORT', 'IBLOCK_ID', 'NAME');
					$arRootSections = CCache::CIBLockSection_GetList(array('SORT' => 'ASC', 'NAME' => 'ASC', 'CACHE' => array('TAG' => CCache::GetIBlockCacheTag($arParams['IBLOCK_ID']))), $arFilter, false, $arSelect);
					foreach($arRootSections as $arSection)
					{
						$arSections['ALL_SECTIONS']['SORTED'][$arSection['ID']] = $arSections['ALL_SECTIONS'][$arSection['ID']];
						unset($arSections['ALL_SECTIONS'][$arSection['ID']]);
					}
					foreach($arSections['ALL_SECTIONS']['SORTED'] as $key => $arSection)
					{
						$arSections['ALL_SECTIONS'][$key] = $arSection;
					}
					unset($arSections['ALL_SECTIONS']['SORTED']);
				}
			}
		}
		return $arSections;
	}

	public static function ShowPageType($type = 'indexblocks', $subtype = ''){
		global $APPLICATION, $arTheme;
		$path = $_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH.'/page_blocks/'.$type.'_';
		$file = null;

		if(is_array($arTheme) && $arTheme)
		{
			switch($type):
				case 'page_contacts':
					$path = str_replace('//', '/', $_SERVER['DOCUMENT_ROOT'].'/'.SITE_DIR.'contacts/'.$type);
					$file = $path.'_'.$arTheme['PAGE_CONTACTS']['VALUE'].'.php';
					break;
				case 'mainpage':
					$path = str_replace('//', '/', $_SERVER['DOCUMENT_ROOT'].'/'.SITE_DIR.'include/'.$type.'/comp_');
					$file = $path.$subtype.'.php';
					break;
				case 'search_title_component':
					$path = str_replace('//', '/', $_SERVER['DOCUMENT_ROOT'].'/'.SITE_DIR.'include/footer/');
					$file = $path.'site-search.php';
					break;
				case 'basket_component':
					$path = str_replace('//', '/', $_SERVER['DOCUMENT_ROOT'].'/'.SITE_DIR.'include/footer/');
					$file = $path.'site-basket.php';
					break;
				case 'auth_component':
					$path = str_replace('//', '/', $_SERVER['DOCUMENT_ROOT'].'/'.SITE_DIR.'include/footer/');
					$file = $path.'site-auth.php';
					break;
				case 'bottom_counter':
					$bIndexBot = (isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) && strpos($_SERVER['HTTP_ACCEPT_LANGUAGE'], ',') === false); // is indexed yandex/google bot
					if(!$bIndexBot)
					{
						$path = str_replace('//', '/', $_SERVER['DOCUMENT_ROOT'].'/'.SITE_DIR.'include/');
						$file = $path.'invis-counter.php';
					}
					break;
				case 'page_width':
					$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH.'/css/width-'.$arTheme['PAGE_WIDTH']['VALUE'].'.css');
					break;
				case 'left_block':
					$file = $path.$arTheme['VIEW_TYPE_LEFT_BLOCK']['VALUE'].'.php';
					break;
				case 'h1_style':
					if($arTheme['H1_STYLE']['VALUE']=='Normal')
						$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH.'/css/h1-normal.css');
					else
						$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH.'/css/h1-bold.css');
					break;
				case 'footer':
					$file = $path.(isset($arTheme['FOOTER_TYPE']['VALUE']) && $arTheme['FOOTER_TYPE']['VALUE'] ? $arTheme['FOOTER_TYPE']['VALUE'] : $arTheme['FOOTER_TYPE']).'.php';
					break;
				case 'header':
					$file = $path.$arTheme['HEADER_TYPE']['VALUE'].'.php';
					break;
				case 'header_fixed':
					$file = $path.$arTheme['TOP_MENU_FIXED']['DEPENDENT_PARAMS']['HEADER_FIXED']['VALUE'].'.php';
					break;
				case 'header_mobile':
					$file = $path.$arTheme['HEADER_MOBILE']['VALUE'].'.php';
					break;
				case 'header_mobile_menu':
					$file = $path.$arTheme['HEADER_MOBILE_MENU']['VALUE'].'.php';
					break;
				case 'page_title':
					$file = $path.$arTheme['PAGE_TITLE']['VALUE'].'.php';
					break;
				default:
					global $arMainPageOrder;
					if(isset($arTheme['INDEX_TYPE']['SUB_PARAMS'][$arTheme['INDEX_TYPE']['VALUE']]))
					{
						$order = $arTheme["SORT_ORDER_INDEX_TYPE_".$arTheme["INDEX_TYPE"]["VALUE"]];
						if($order)
							$arMainPageOrder = explode(",", $order);
						else
							$arMainPageOrder = array_keys($arTheme['INDEX_TYPE']['SUB_PARAMS'][$arTheme['INDEX_TYPE']['VALUE']]);
					}
					foreach(GetModuleEvents(ALLCORP2_MODULE_ID, 'OnAsproShowPageType', true) as $arEvent) // event for manipulation arMainPageOrder
						ExecuteModuleEventEx($arEvent, array($arTheme, &$arMainPageOrder));

					if($arTheme['INDEX_TYPE']['VALUE'] == 'custom')
					{
						global $arSite, $isMenu, $isIndex, $isCabinet, $is404, $bBigBannersIndex, $bServicesIndex, $bPortfolioIndex, $bPartnersIndex, $bTeasersIndex, $bInstagrammIndex, $bReviewsIndex, $bConsultIndex, $bCompanyIndex, $bTeamIndex, $bNewsIndex, $bMapIndex, $bFloatBannersIndex, $bCatalogIndex, $bBlogIndex, $bActiveTheme, $bCatalogSectionsIndex;
						global $bBigBannersIndexClass, $bServicesIndexClass, $bPartnersIndexClass, $bTeasersIndexClass, $bFloatBannersIndexClass, $bPortfolioIndexClass, $bCatalogIndexClass,  $bBlogIndexClass, $bInstagrammIndexClass, $bReviewsIndexClass, $bConsultIndexClass, $bCompanyIndexClass, $bTeamIndexClass, $bNewsIndexClass, $bMapIndexClass, $bCatalogSectionsIndexClass;

						$bBigBannersIndex = $bServicesIndex = $bPortfolioIndex = $bPartnersIndex = $bTeasersIndex = $bInstagrammIndex = $bReviewsIndex = $bConsultIndex = $bCompanyIndex = $bTeamIndex = $bNewsIndex = true;
						$bMapIndex = $bFloatBannersIndex = $bCatalogIndex = $bBlogIndex = $bCatalogSectionsIndex = $bPortfolioIndex = false;

						$bBigBannersIndexClass = $bServicesIndexClass = $bPartnersIndexClass = $bTeasersIndexClass = $bFloatBannersIndexClass = $bPortfolioIndexClass = $bCatalogIndexClass = $bBlogIndexClass = $bInstagrammIndexClass = $bReviewsIndexClass = $bConsultIndexClass = $bCompanyIndexClass = $bTeamIndexClass = $bNewsIndexClass = $bMapIndexClass = $bCatalogSectionsIndexClass = '';
					}

					$bIndexBot = (isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) && strpos($_SERVER['HTTP_USER_AGENT'], 'Lighthouse') !== false); // is indexed yandex/google bot
					if($bIndexBot)
					{
						global $arSite, $isMenu, $isIndex, $isCabinet, $is404, $bBigBannersIndex, $bServicesIndex, $bPortfolioIndex, $bPartnersIndex, $bTeasersIndex, $bInstagrammIndex, $bReviewsIndex, $bConsultIndex, $bCompanyIndex, $bTeamIndex, $bNewsIndex, $bMapIndex, $bFloatBannersIndex, $bCatalogIndex, $bBlogIndex, $bActiveTheme, $bCatalogSectionsIndex;
						
						$bMapIndex = $bFloatBannersIndex = $bCatalogIndex = $bBlogIndex = $bCatalogSectionsIndex = $bPortfolioIndex = $bTeamIndex = $bInstagrammIndex = false;
					}

					$path = str_replace('//', '/', $_SERVER['DOCUMENT_ROOT'].'/'.SITE_DIR.$type);
					$file = $path.'_'.$arTheme['INDEX_TYPE']['VALUE'].'.php';
					break;
			endswitch;
			if ($file) {
				if($type == 'indexblocks') echo '<div>';
				@include_once $file;
				if($type == 'indexblocks') echo '</div>';
			}
		}
	}

	public static function ShowLogo(){
		global $arSite;
		$arTheme = self::GetFrontParametrsValues(SITE_ID);
		$text = '<a href="'.SITE_DIR.'">';
		if($arImg = unserialize(Option::get(ALLCORP2_MODULE_ID, "LOGO_IMAGE", serialize(array()))))
			$text .= '<img src="'.CFile::GetPath($arImg[0]).'" alt="'.$arSite["SITE_NAME"].'" title="'.$arSite["SITE_NAME"].'" />';
		elseif(self::checkContentFile(SITE_DIR.'/include/logo_svg.php'))
			$text .= File::getFileContents($_SERVER['DOCUMENT_ROOT'].SITE_DIR.'/include/logo_svg.php');
		else
			$text .= '<img src="'.$arTheme["LOGO_IMAGE"].'" alt="'.$arSite["SITE_NAME"].'" title="'.$arSite["SITE_NAME"].'" />';
		$text .= '</a>';

		return $text;
	}

	public static function showIconSvg($class = 'phone', $path, $title = '', $class_icon = '', $show_wrapper = true){
		$text ='';
		if(self::checkContentFile($path))
		{
			static $svg_call;
			$iSvgID = ++$svg_call;
			if($show_wrapper)
				$text = '<i class="svg inline '.$class_icon.' svg-inline-'.$class.'" aria-hidden="true" '.($title ? 'title="'.$title.'"' : '').'>';

				$text .= str_replace('markID', $iSvgID, File::getFileContents($_SERVER['DOCUMENT_ROOT'].$path));

			if($show_wrapper)
				$text .= '</i>';
		}

		return $text;
	}

	public static function GetBackParametrsValues($SITE_ID, $bStatic = true){
		if($bStatic)
			static $arValues;

		if($bStatic && $arValues === NULL || !$bStatic){
			$arDefaultValues = $arValues = $arNestedValues = array();
			$bNestedParams = false;

			// get site template
			$arTemplate = self::GetSiteTemplate($SITE_ID);

			// add custom values for PAGE_CONTACTS
			if(isset(self::$arParametrsList['SECTION']['OPTIONS']['PAGE_CONTACTS']['LIST'])){
				// get site dir
				$arSite = CSite::GetByID($SITE_ID)->Fetch();
				$siteDir = str_replace('//', '/', $arSite['DIR']).'/';
				if($arPageBlocks = self::GetIndexPageBlocks($_SERVER['DOCUMENT_ROOT'].$siteDir.'contacts', 'page_contacts_', '')){
					foreach($arPageBlocks as $page => $value){
						$value_ = str_replace('page_contacts_', '', $value);
						if(!isset(self::$arParametrsList['SECTION']['OPTIONS']['PAGE_CONTACTS']['LIST'][$value_])){
							self::$arParametrsList['SECTION']['OPTIONS']['PAGE_CONTACTS']['LIST'][$value_] = array(
								'TITLE' => $value,
								'HIDE' => 'Y',
								'IS_CUSTOM' => 'Y',
							);
						}
					}
					if(!self::$arParametrsList['SECTION']['OPTIONS']['PAGE_CONTACTS']['DEFAULT']){
						self::$arParametrsList['SECTION']['OPTIONS']['PAGE_CONTACTS']['DEFAULT'] = key(self::$arParametrsList['SECTION']['OPTIONS']['PAGE_CONTACTS']['LIST']);
					}
				}
			}

			if($arTemplate && $arTemplate['PATH']){
				// add custom values for BLOG_PAGE
				if(isset(self::$arParametrsList['SECTION']['OPTIONS']['BLOG_PAGE'])){
					self::Add2OptionCustomComponentTemplatePageBlocks(self::$arParametrsList['SECTION']['OPTIONS']['BLOG_PAGE'], $arTemplate['PATH'].'/components/bitrix/news/blog');
				}

				// add custom values for PROJECTS_PAGE
				if(isset(self::$arParametrsList['SECTION']['OPTIONS']['PROJECTS_PAGE'])){
					self::Add2OptionCustomComponentTemplatePageBlocks(self::$arParametrsList['SECTION']['OPTIONS']['PROJECTS_PAGE'], $arTemplate['PATH'].'/components/bitrix/news/projects');
				}

				// add custom values for NEWS_PAGE
				if(isset(self::$arParametrsList['SECTION']['OPTIONS']['NEWS_PAGE'])){
					self::Add2OptionCustomComponentTemplatePageBlocks(self::$arParametrsList['SECTION']['OPTIONS']['NEWS_PAGE'], $arTemplate['PATH'].'/components/bitrix/news/news');
				}

				// add custom values for STAFF_PAGE
				if(isset(self::$arParametrsList['SECTION']['OPTIONS']['STAFF_PAGE'])){
					self::Add2OptionCustomComponentTemplatePageBlocks(self::$arParametrsList['SECTION']['OPTIONS']['STAFF_PAGE'], $arTemplate['PATH'].'/components/bitrix/news/staff');
				}

				// add custom values for PARTNERS_PAGE
				if(isset(self::$arParametrsList['SECTION']['OPTIONS']['PARTNERS_PAGE'])){
					self::Add2OptionCustomComponentTemplatePageBlocks(self::$arParametrsList['SECTION']['OPTIONS']['PARTNERS_PAGE'], $arTemplate['PATH'].'/components/bitrix/news/partners');
				}

				// add custom values for VACANCY_PAGE
				if(isset(self::$arParametrsList['SECTION']['OPTIONS']['VACANCY_PAGE'])){
					self::Add2OptionCustomComponentTemplatePageBlocks(self::$arParametrsList['SECTION']['OPTIONS']['VACANCY_PAGE'], $arTemplate['PATH'].'/components/bitrix/news/vacancy');
				}

				// add custom values for LICENSES_PAGE
				if(isset(self::$arParametrsList['SECTION']['OPTIONS']['LICENSES_PAGE'])){
					self::Add2OptionCustomComponentTemplatePageBlocks(self::$arParametrsList['SECTION']['OPTIONS']['LICENSES_PAGE'], $arTemplate['PATH'].'/components/bitrix/news/licenses');
				}

				// add custom values for CATALOG_PAGE_DETAIL
				if(isset(self::$arParametrsList['CATALOG_PAGE']['OPTIONS']['CATALOG_PAGE_DETAIL'])){
					self::Add2OptionCustomComponentTemplatePageBlocksElement(self::$arParametrsList['CATALOG_PAGE']['OPTIONS']['CATALOG_PAGE_DETAIL'], $arTemplate['PATH'].'/components/bitrix/news/catalog');
				}

				// add custom values for SECTIONS_TYPE_VIEW_CATALOG
				if(isset(self::$arParametrsList['CATALOG_PAGE']['OPTIONS']['SECTIONS_TYPE_VIEW_CATALOG'])){
					self::Add2OptionCustomComponentTemplatePageBlocksElement(self::$arParametrsList['CATALOG_PAGE']['OPTIONS']['SECTIONS_TYPE_VIEW_CATALOG'], $arTemplate['PATH'].'/components/bitrix/news/catalog', 'SECTIONS');
				}

				// add custom values for SECTION_TYPE_VIEW_CATALOG
				if(isset(self::$arParametrsList['CATALOG_PAGE']['OPTIONS']['SECTION_TYPE_VIEW_CATALOG'])){
					self::Add2OptionCustomComponentTemplatePageBlocksElement(self::$arParametrsList['CATALOG_PAGE']['OPTIONS']['SECTION_TYPE_VIEW_CATALOG'], $arTemplate['PATH'].'/components/bitrix/news/catalog', 'SUBSECTIONS');
				}

				// add custom values for ELEMENTS_CATALOG_PAGE
				if(isset(self::$arParametrsList['CATALOG_PAGE']['OPTIONS']['ELEMENTS_CATALOG_PAGE'])){
					self::Add2OptionCustomComponentTemplatePageBlocksElement(self::$arParametrsList['CATALOG_PAGE']['OPTIONS']['ELEMENTS_CATALOG_PAGE'], $arTemplate['PATH'].'/components/bitrix/news/catalog', 'ELEMENTS');
				}

				// add custom values for ELEMENTS_TABLE_TYPE_VIEW
				if(isset(self::$arParametrsList['CATALOG_PAGE']['OPTIONS']['ELEMENTS_TABLE_TYPE_VIEW'])){
					self::Add2OptionCustomComponentTemplatePageBlocksElement(self::$arParametrsList['CATALOG_PAGE']['OPTIONS']['ELEMENTS_TABLE_TYPE_VIEW'], $arTemplate['PATH'].'/components/bitrix/news/catalog', 'ELEMENTS_TABLE');
				}

				// add custom values for ELEMENTS_LIST_TYPE_VIEW
				if(isset(self::$arParametrsList['CATALOG_PAGE']['OPTIONS']['ELEMENTS_LIST_TYPE_VIEW'])){
					self::Add2OptionCustomComponentTemplatePageBlocksElement(self::$arParametrsList['CATALOG_PAGE']['OPTIONS']['ELEMENTS_LIST_TYPE_VIEW'], $arTemplate['PATH'].'/components/bitrix/news/catalog', 'ELEMENTS_LIST');
				}

				// add custom values for ELEMENTS_PRICE_TYPE_VIEW
				if(isset(self::$arParametrsList['CATALOG_PAGE']['OPTIONS']['ELEMENTS_PRICE_TYPE_VIEW'])){
					self::Add2OptionCustomComponentTemplatePageBlocksElement(self::$arParametrsList['CATALOG_PAGE']['OPTIONS']['ELEMENTS_PRICE_TYPE_VIEW'], $arTemplate['PATH'].'/components/bitrix/news/catalog', 'ELEMENTS_PRICE');
				}

				// add custom values for SECTIONS_TYPE_VIEW
				if(isset(self::$arParametrsList['SERVICES']['OPTIONS']['SECTIONS_TYPE_VIEW'])){
					self::Add2OptionCustomComponentTemplatePageBlocksElement(self::$arParametrsList['SERVICES']['OPTIONS']['SECTIONS_TYPE_VIEW'], $arTemplate['PATH'].'/components/bitrix/news/services', 'SECTIONS');
				}
				// add custom values for SECTION_TYPE_VIEW
				if(isset(self::$arParametrsList['SERVICES']['OPTIONS']['SECTION_TYPE_VIEW'])){
					self::Add2OptionCustomComponentTemplatePageBlocksElement(self::$arParametrsList['SERVICES']['OPTIONS']['SECTION_TYPE_VIEW'], $arTemplate['PATH'].'/components/bitrix/news/services', 'SUBSECTIONS');
				}
				// add custom values for ELEMENTS_PAGE
				if(isset(self::$arParametrsList['SERVICES']['OPTIONS']['ELEMENTS_PAGE'])){
					self::Add2OptionCustomComponentTemplatePageBlocksElement(self::$arParametrsList['SERVICES']['OPTIONS']['ELEMENTS_PAGE'], $arTemplate['PATH'].'/components/bitrix/news/services', 'ELEMENTS');
				}
				// add custom values for ELEMENT_PAGE_DETAIL
				if(isset(self::$arParametrsList['SERVICES']['OPTIONS']['ELEMENT_PAGE_DETAIL'])){
					self::Add2OptionCustomComponentTemplatePageBlocksElement(self::$arParametrsList['SERVICES']['OPTIONS']['ELEMENT_PAGE_DETAIL'], $arTemplate['PATH'].'/components/bitrix/news/services');
				}

				// add custom values for ELEMENTS_PROJECT_PAGE
				if(isset(self::$arParametrsList['PROJECTS']['OPTIONS']['ELEMENTS_PROJECT_PAGE'])){
					self::Add2OptionCustomComponentTemplatePageBlocksElement(self::$arParametrsList['PROJECTS']['OPTIONS']['ELEMENTS_PROJECT_PAGE'], $arTemplate['PATH'].'/components/bitrix/news/projects', 'ELEMENTS');
				}

				// add custom values for ELEMENT_PAGE_DETAIL
				if(isset(self::$arParametrsList['PROJECTS']['OPTIONS']['ELEMENT_PROJECT_PAGE_DETAIL'])){
					self::Add2OptionCustomComponentTemplatePageBlocksElement(self::$arParametrsList['PROJECTS']['OPTIONS']['ELEMENT_PROJECT_PAGE_DETAIL'], $arTemplate['PATH'].'/components/bitrix/news/projects');
				}
			}

			if(self::$arParametrsList && is_array(self::$arParametrsList))
			{
				foreach(self::$arParametrsList as $blockCode => $arBlock)
				{
					if($arBlock['OPTIONS'] && is_array($arBlock['OPTIONS']))
					{
						foreach($arBlock['OPTIONS'] as $optionCode => $arOption)
						{
							if($arOption['TYPE'] !== 'note' && $arOption['TYPE'] !== 'includefile'){
								if($arOption['TYPE'] === 'array'){
									$itemsKeysCount = Option::get(self::MODULE_ID, $optionCode, '0', $SITE_ID);
									if($arOption['OPTIONS'] && is_array($arOption['OPTIONS'])){
										for($itemKey = 0, $cnt = $itemsKeysCount + 1; $itemKey < $cnt; ++$itemKey){
											$_arParameters = array();
											$arOptionsKeys = array_keys($arOption['OPTIONS']);
											foreach($arOptionsKeys as $_optionKey){
												$arrayOptionItemCode = $optionCode.'_array_'.$_optionKey.'_'.$itemKey;
												$arValues[$arrayOptionItemCode] = Option::get(self::MODULE_ID, $arrayOptionItemCode, '', $SITE_ID);
												$arDefaultValues[$arrayOptionItemCode] = $arOption['OPTIONS'][$_optionKey]['DEFAULT'];
											}
										}
									}
									$arValues[$optionCode] = $itemsKeysCount;
									$arDefaultValues[$optionCode] = 0;
								}
								else{
									$arDefaultValues[$optionCode] = $arOption['DEFAULT'];
									$arValues[$optionCode] = Option::get(self::MODULE_ID, $optionCode, $arOption['DEFAULT'], $SITE_ID);

									if(isset($arOption['SUB_PARAMS']) && $arOption['SUB_PARAMS']) //get nested params default value
									{
										if($arOption['TYPE'] == 'selectbox' && (isset($arOption['LIST'])) && $arOption['LIST'])
										{
											$bNestedParams = true;
											$arNestedValues[$optionCode] = $arOption['LIST'];
											foreach($arOption['LIST'] as $key => $value)
											{
												if($arOption['SUB_PARAMS'][$key])
												{
													foreach($arOption['SUB_PARAMS'][$key] as $key2 => $arSubOptions)
													{
														$arDefaultValues[$key.'_'.$key2] = $arSubOptions['DEFAULT'];
														
														//set default template index components
														if(isset($arSubOptions['TEMPLATE']) && $arSubOptions['TEMPLATE'])
														{
															$code_tmp = $key.'_'.$key2.'_TEMPLATE';
															$arDefaultValues[$code_tmp] = $arSubOptions['TEMPLATE']['DEFAULT'];
															$arValues[$code_tmp] = Option::get(self::MODULE_ID, $code_tmp, $arSubOptions['TEMPLATE']['DEFAULT'], $SITE_ID);
														}
													}

													//sort order prop for main page
													$param = 'SORT_ORDER_'.$optionCode.'_'.$key;
													$arValues[$param] = Option::get(self::MODULE_ID, $param, '', $SITE_ID);
													$arDefaultValues[$param] = '';
												}
											}
										}
									}

									if(isset($arOption['DEPENDENT_PARAMS']) && $arOption['DEPENDENT_PARAMS']) //get dependent params default value
									{
										foreach($arOption['DEPENDENT_PARAMS'] as $key => $arSubOption)
										{
											$arDefaultValues[$key] = $arSubOption['DEFAULT'];
											$arValues[$key] = Option::get(self::MODULE_ID, $key, $arSubOption['DEFAULT'], $SITE_ID);
										}
									}
								}
							}
						}
					}
				}
			}
			if($arNestedValues && $bNestedParams) //get nested params bd value
			{
				foreach($arNestedValues as $key => $arAllValues)
				{
					$arTmpValues = array();
					foreach($arAllValues as $key2 => $arOptionValue)
					{
						$arTmpValues = unserialize(Option::get(self::MODULE_ID, 'NESTED_OPTIONS_'.$key.'_'.$key2, serialize(array()), $SITE_ID));
						if($arTmpValues)
						{
							foreach($arTmpValues as $key3 => $value)
							{
								$arValues[$key2.'_'.$key3] = $value;
							}
						}
					}

				}
			}

			if($arValues && is_array($arValues))
			{
				foreach($arValues as $optionCode => $arOption)
				{
					if(!isset($arDefaultValues[$optionCode]))
						unset($arValues[$optionCode]);
				}
			}

			if($arDefaultValues && is_array($arDefaultValues))
			{
				foreach($arDefaultValues as $optionCode => $arOption)
				{
					if(!isset($arValues[$optionCode]))
						$arValues[$optionCode] = $arOption;
				}
			}

			foreach($arValues as $key => $value)
			{
				if($key == 'LOGO_IMAGE' || $key == 'LOGO_IMAGE_LIGHT' || $key == 'FAVICON_IMAGE' || $key == 'APPLE_TOUCH_ICON_IMAGE'){
					$arValue = unserialize(Option::get(self::MODULE_ID, $key, serialize(array()), $SITE_ID));
					$arValue = (array)$arValue;
					$fileID = $arValue ? current($arValue) : false;

					if($key === 'FAVICON_IMAGE')
						$arValues[$key] = str_replace('//', '/', SITE_DIR.'/favicon.ico');

					if($fileID)
					{
						if($key !== 'FAVICON_IMAGE')
							$arValues[$key] = CFIle::GetPath($fileID);
					}
					else
					{
						if($key === 'APPLE_TOUCH_ICON_IMAGE')
							$arValues[$key] = str_replace('//', '/', SITE_DIR.'/include/apple-touch-icon.png');
						elseif($key === 'LOGO_IMAGE')
							$arValues[$key] = str_replace('//', '/', SITE_DIR.'/logo.png');
					}

					if(!file_exists(str_replace('//', '/', $_SERVER['DOCUMENT_ROOT'].$arValues[$key]))){
						$arValues[$key] = '';
					}
					else
					{
						if($key === 'FAVICON_IMAGE')
							$arValues[$key] .= '?'.filemtime(str_replace('//', '/', $_SERVER['DOCUMENT_ROOT'].$arValues[$key]));
					}

				}
			}

			if(!defined('ADMIN_SECTION'))
			{
				// replace #SITE_DIR#
				if($arValues && is_array($arValues))
				{
					foreach($arValues as $optionCode => $arOption)
					{
						if(!is_array($arOption))
							$arValues[$optionCode] = str_replace('#SITE_DIR#', SITE_DIR, $arOption);
					}
				}
			}
		}

		return $arValues;
	}

	public static function GetFrontParametrsValues($SITE_ID = SITE_ID){
		if(!strlen($SITE_ID))
			$SITE_ID = SITE_ID;
		$arBackParametrs = self::GetBackParametrsValues($SITE_ID);
		if($arBackParametrs['THEME_SWITCHER'] === 'Y')
			$arValues = array_merge((array)$arBackParametrs, (array)$_SESSION['THEME'][$SITE_ID]);
		else
			$arValues = (array)$arBackParametrs;

		if($arValues['REGIONALITY_SEARCH_ROW'] == 'Y' && $arValues['REGIONALITY_VIEW'] != 'POPUP_REGIONS')
			$arValues['REGIONALITY_VIEW'] = 'POPUP_REGIONS';

		return $arValues;
	}

	public static function GetFrontParametrValue($optionCode, $SITE_ID = SITE_ID){
		static $arFrontParametrs;

		if(!isset($arFrontParametrs)){
			$arFrontParametrs = self::GetFrontParametrsValues($SITE_ID);
		}

		return $arFrontParametrs[$optionCode];
	}

	public static function ShowAdminRow($optionCode, $arOption, $arTab, $arControllerOption, $bShowIcon = false){
		$optionName = $arOption["TITLE"];
		$optionType = $arOption["TYPE"];
		$optionList = $arOption["LIST"];
		$optionDefault = $arOption["DEFAULT"];
		$optionVal = $arTab["OPTIONS"][$optionCode];
		$optionSize = $arOption["SIZE"];
		$optionCols = $arOption["COLS"];
		$optionRows = $arOption["ROWS"];
		$optionChecked = $optionVal == "Y" ? "checked" : "";
		$optionDisabled = $optionSup_text = '';
		if(is_array($arOption))
		{
			$optionDisabled = isset($arControllerOption[$optionCode]) || array_key_exists("DISABLED", $arOption) && $arOption["DISABLED"] == "Y" ? "disabled" : "";
			$optionSup_text = array_key_exists("SUP", $arOption) ? $arOption["SUP"] : "";
		}
		$optionController = isset($arControllerOption[$optionCode]) ? "title='".GetMessage("MAIN_ADMIN_SET_CONTROLLER_ALT")."'" : "";
		$optionsSiteID = $arTab["SITE_ID"];
		$isArrayItem = strpos($optionCode, '_array_') !== false;
		if($optionCode == 'USE_BITRIX_FORM')
		{
			if(!\Bitrix\Main\ModuleManager::isModuleInstalled('form'))
				return;
		}
		?>
		<?if($optionType == "dynamic_iblock"):?>
			<?if(\Bitrix\Main\Loader::IncludeModule('iblock')):?>
				<td colspan="2">
					<div class="title"  align="center"><b><?=$optionName;?></b></div>
					<?
					$arIblocks = array();
					$arSort = array(
						"SORT" => "ASC",
						"ID" => "ASC"
					);
					$arFilter = array(
						"ACTIVE" => "Y",
						"SITE_ID" => $optionsSiteID,
						"TYPE" => "aspro_allcorp2_form"
					);
					$rsItems = CIBlock::GetList($arSort, $arFilter);
					while($arItem = $rsItems->Fetch()){
						if($arItem["CODE"] != "aspro_allcorp2_example" && $arItem["CODE"] != "aspro_allcorp2_order_page")
						{
							$arItem['THEME_VALUE'] = Option::get(self::MODULE_ID, htmlspecialcharsbx($optionCode)."_".htmlspecialcharsbx(strtoupper($arItem['CODE'])), $optionsSiteID);
							$arIblocks[] = $arItem;
						}
					}
					if($arIblocks):?>
						<table width="100%">
							<?foreach($arIblocks as $arIblock):?>
								<tr>
									<td class="adm-detail-content-cell-l" width="50%">
										<?=GetMessage("SUCCESS_SEND_FORM", array("#IBLOCK_CODE#" => $arIblock["NAME"]));?>
									</td>
									<td class="adm-detail-content-cell-r" width="50%">
										<input type="text" <?=((isset($arOption['PARAMS']) && isset($arOption['PARAMS']['WIDTH'])) ? 'style="width:'.$arOption['PARAMS']['WIDTH'].'"' : '');?> <?=$optionController?> size="<?=$optionSize?>" maxlength="255" value="<?=htmlspecialcharsbx($arIblock['THEME_VALUE'])?>" name="<?=htmlspecialcharsbx($optionCode)."_".htmlspecialcharsbx($arIblock['CODE'])."_".$optionsSiteID?>" <?=$optionDisabled?>>
									</td>
								</tr>
							<?endforeach;?>
						</table>
					<?endif;?>
				</td>
			<?endif;?>
		<?elseif($optionType == "note"):?>
			<?if($optionCode == 'USE_FORMS_GOALS_NOTE'){
				$FORMS_GOALS_LIST = '';
				$arIblocksIDs = array();
				$bUseForm = (\Bitrix\Main\Config\Option::get(self::MODULE_ID, 'USE_BITRIX_FORM', 'N', $optionsSiteID) == 'Y' && \Bitrix\Main\Loader::includeModule('form'));
				if($bUseForm)
				{
					$arOption["NOTE"] = GetMessage("USE_FORM_GOALS_NOTE_TITLE2");
					$rsForm = CForm::GetList($by = 'id', $order = 'asc', array('ACTIVE' => 'Y', 'SITE' => array($optionsSiteID)), $is_filtered);
					while($arForm = $rsForm->Fetch())
						$FORMS_GOALS_LIST .= $arForm['NAME'].' - <i>goal_webform_success_'.$arForm['ID'].'</i><br />';
				}
				else
				{
					if(CCache::$arIBlocks[$optionsSiteID]['aspro_allcorp2_form'] && is_array(CCache::$arIBlocks[$optionsSiteID]['aspro_allcorp2_form'])){
						foreach(CCache::$arIBlocks[$optionsSiteID]['aspro_allcorp2_form'] as $arIDs){
							if($arIDs && is_array($arIDs)){
								foreach($arIDs as $IBLOCK_ID){
									if(CCache::$arIBlocksInfo && CCache::$arIBlocksInfo[$IBLOCK_ID] && is_array(CCache::$arIBlocksInfo[$IBLOCK_ID])){
										$FORMS_GOALS_LIST .= CCache::$arIBlocksInfo[$IBLOCK_ID]['NAME'].' - <i>goal_webform_success_'.$IBLOCK_ID.'</i><br />';
									}
								}
							}
						}
					}
				}
				$arOption["NOTE"] = str_replace('#FORMS_GOALS_LIST#', $FORMS_GOALS_LIST, $arOption["NOTE"]);
			}
			?>
			<td colspan="2" align="center">
				<?=BeginNote('align="center"');?>
				<?=$arOption["NOTE"]?>
				<?=EndNote();?>
			</td>
		<?else:?>
			<?if(!$isArrayItem):?>
				<td class="adm-detail-content-cell-l <?=(in_array($optionType, array("multiselectbox", "textarea", "statictext", "statichtml")) ? "adm-detail-valign-top" : "")?>" width="50%">
					<?if($optionType == "checkbox"):?>
						<label for="<?=htmlspecialcharsbx($optionCode)."_".$optionsSiteID?>"><?=$optionName?></label>
					<?else:?>
						<?=$optionName.($optionCode == "BASE_COLOR_CUSTOM" ? ' #' : '')?>
					<?endif;?>
					<?if(strlen($optionSup_text)):?>
						<span class="required"><sup><?=$optionSup_text?></sup></span>
					<?endif;?>
				</td>
			<?endif;?>
			<td<?=(!$isArrayItem ? ' width="50%" ' : '')?>>
				<?
				if($optionCode == 'PAGE_CONTACTS')
				{
					$siteDir = str_replace('//', '/', $arTab['SITE_DIR']).'/';
					if($arPageBlocks = self::GetIndexPageBlocks($_SERVER['DOCUMENT_ROOT'].$siteDir.'contacts', 'page_contacts_', '')){
						$arTmp = array();
						foreach($arPageBlocks as $page => $value)
						{
							$value_ = str_replace('page_contacts_', '', $value);
							$arTmp[$value_] = $value;
						}
						foreach($arOption['LIST'] as $key_list => $arValue)
						{
							if(isset($arTmp[$key_list]))
								;
							else
								unset($arOption['LIST'][$key_list]);
						}
					}
					$optionList = $arOption['LIST'];
				}
				elseif($optionCode == 'BLOG_PAGE')
				{
					$optionList = self::getActualParamsValue( $arTab, $arOption, '/components/bitrix/news/blog');
				}
				elseif($optionCode == 'NEWS_PAGE')
				{
					$optionList = self::getActualParamsValue( $arTab, $arOption, '/components/bitrix/news/news');
				}
				elseif($optionCode == 'PROJECTS_PAGE')
				{
					$optionList = self::getActualParamsValue( $arTab, $arOption, '/components/bitrix/news/projects');
				}
				elseif($optionCode == 'STAFF_PAGE')
				{
					$optionList = self::getActualParamsValue( $arTab, $arOption, '/components/bitrix/news/staff');
				}
				elseif($optionCode == 'PARTNERS_PAGE')
				{
					$optionList = self::getActualParamsValue( $arTab, $arOption, '/components/bitrix/news/partners');
				}
				elseif($optionCode == 'VACANCY_PAGE')
				{
					$optionList = self::getActualParamsValue( $arTab, $arOption, '/components/bitrix/news/vacancy');
				}
				elseif($optionCode == 'LICENSES_PAGE')
				{
					$optionList = self::getActualParamsValue( $arTab, $arOption, '/components/bitrix/news/licenses');
				}
				elseif($optionCode == 'CATALOG_PAGE_DETAIL')
				{
					$optionList = self::getActualParamsValue( $arTab, $arOption, '/components/bitrix/news/catalog', 'ELEMENT');
				}
				elseif($optionCode == 'GRUPPER_PROPS')
				{
					// webdebug.utilities
					$optionList['WEBDEBUG']['TITLE'] = Loc::getMessage('GRUPPER_PROPS_WEBDEBUG');
					if(!\Bitrix\Main\Loader::includeModule('webdebug.utilities'))
					{
						$optionList['WEBDEBUG']['DISABLED'] = 'Y';
						$optionList['WEBDEBUG']['TITLE'] .= Loc::getMessage('NOT_INSTALLED', array('#MODULE_NAME#' => 'webdebug.utilities'));
					}

					// yenisite.infoblockpropsplus
					$optionList['YENISITE_GRUPPER']['TITLE'] = Loc::getMessage('GRUPPER_PROPS_YENISITE_GRUPPER');
					if(!\Bitrix\Main\Loader::includeModule('yenisite.infoblockpropsplus'))
					{
						$optionList['YENISITE_GRUPPER']['DISABLED'] = 'Y';
						$optionList['YENISITE_GRUPPER']['TITLE'] .= Loc::getMessage('NOT_INSTALLED', array('#MODULE_NAME#' => 'yenisite.infoblockpropsplus'));
					}
				}
				?>
				<?if($optionType == "checkbox"):?>
					<input type="checkbox" <?=((isset($arOption['DEPENDENT_PARAMS']) && $arOption['DEPENDENT_PARAMS']) ? "class='depend-check'" : "");?> <?=$optionController?> id="<?=htmlspecialcharsbx($optionCode)."_".$optionsSiteID?>" name="<?=htmlspecialcharsbx($optionCode)."_".$optionsSiteID?>" value="Y" <?=$optionChecked?> <?=$optionDisabled?> <?=(strlen($optionDefault) ? $optionDefault : "")?>>
					<?if($bShowIcon):?>
						<span class="drag"></span>
					<?endif;?>
				<?elseif($optionType == "text" || $optionType == "password"):?>
					<input type="<?=$optionType?>" <?=((isset($arOption['PARAMS']) && isset($arOption['PARAMS']['WIDTH'])) ? 'style="width:'.$arOption['PARAMS']['WIDTH'].'"' : '');?> <?=$optionController?> size="<?=$optionSize?>" maxlength="255" value="<?=htmlspecialcharsbx($optionVal)?>" name="<?=htmlspecialcharsbx($optionCode)."_".$optionsSiteID?>" <?=$optionDisabled?> <?=($optionCode == "password" ? "autocomplete='off'" : "")?>>
				<?elseif($optionType == "selectbox"):?>
					<?
					if(!is_array($optionList)) $optionList = (array)$optionList;
					$arr_keys = array_keys($optionList);
					?>
					<select name="<?=htmlspecialcharsbx($optionCode)."_".$optionsSiteID?>" <?=$optionController?> <?=$optionDisabled?>>
						<?if($optionCode == 'GRUPPER_PROPS')
						{
							foreach($optionList as $key => $arValue):
								$selected="";
								if($optionVal && $optionVal==$key)
									$selected="selected";
								?>
								<option value="<?=$key;?>" <?=$selected;?> <?=(isset($arValue['DISABLED']) ? 'disabled' : '');?>><?=htmlspecialcharsbx($arValue["TITLE"]);?></option>
							<?endforeach;?>
						<?}
						else{?>
							<?for($j = 0, $c = count($arr_keys); $j < $c; ++$j):?>
								<option value="<?=$arr_keys[$j]?>" <?if($optionVal == $arr_keys[$j]) echo "selected"?>><?=htmlspecialcharsbx((is_array($optionList[$arr_keys[$j]]) ? $optionList[$arr_keys[$j]]["TITLE"] : $optionList[$arr_keys[$j]]))?></option>
							<?endfor;?>
						<?}?>
					</select>
				<?elseif($optionType == "multiselectbox"):?>
					<?
					if(!is_array($optionList)) $optionList = (array)$optionList;
					$arr_keys = array_keys($optionList);
					if(!is_array($optionVal)) $optionVal = (array)$optionVal;
					?>
					<select size="<?=$optionSize?>" <?=$optionController?> <?=$optionDisabled?> multiple name="<?=htmlspecialcharsbx($optionCode)."_".$optionsSiteID?>[]" >
						<?for($j = 0, $c = count($arr_keys); $j < $c; ++$j):?>
							<option value="<?=$arr_keys[$j]?>" <?if(in_array($arr_keys[$j], $optionVal)) echo "selected"?>><?=htmlspecialcharsbx((is_array($optionList[$arr_keys[$j]]) ? $optionList[$arr_keys[$j]]["TITLE"] : $optionList[$arr_keys[$j]]))?></option>
						<?endfor;?>
					</select>
				<?elseif($optionType == "textarea"):?>
					<textarea <?=$optionController?> <?=$optionDisabled?> rows="<?=$optionRows?>" cols="<?=$optionCols?>" name="<?=htmlspecialcharsbx($optionCode)."_".$optionsSiteID?>"><?=htmlspecialcharsbx($optionVal)?></textarea>
				<?elseif($optionType == "statictext"):?>
					<?=htmlspecialcharsbx($optionVal)?>
				<?elseif($optionType == "statichtml"):?>
					<?=$optionVal?>
				<?elseif($optionType == "file"):?>
					<?$val = unserialize(Option::get(self::MODULE_ID, $optionCode, serialize(array()), $optionsSiteID));

					$arOption['MULTIPLE'] = 'N';
					if($optionCode == 'LOGO_IMAGE' || $optionCode == 'LOGO_IMAGE_LIGHT'){
						$arOption['WIDTH'] = 394;
						$arOption['HEIGHT'] = 140;
					}
					elseif($optionCode == 'FAVICON_IMAGE'){
						$arOption['WIDTH'] = 16;
						$arOption['HEIGHT'] = 16;
					}
					elseif($optionCode == 'APPLE_TOUCH_ICON_IMAGE'){
						$arOption['WIDTH'] = 180;
						$arOption['HEIGHT'] = 180;
					}
					self::__ShowFilePropertyField($optionCode."_".$optionsSiteID, $arOption, $val);?>
				<?elseif($optionType === 'includefile'):?>
					<?
					if(!is_array($arOption['INCLUDEFILE'])){
						$arOption['INCLUDEFILE'] = array($arOption['INCLUDEFILE']);
					}
					foreach($arOption['INCLUDEFILE'] as $includefile){
						$includefile = str_replace('//', '/', str_replace('#SITE_DIR#', $arTab['SITE_DIR'].'/', $includefile));
						if(strpos($includefile, '#') === false){
							$template = (isset($arOption['TEMPLATE']) && strlen($arOption['TEMPLATE']) ? 'include_area.php' : $arOption['TEMPLATE']);
							$href = (!strlen($includefile) ? "javascript:;" : "javascript: new BX.CAdminDialog({'content_url':'/bitrix/admin/public_file_edit.php?site=".$arTab['SITE_ID']."&bxpublic=Y&from=includefile&templateID=".TEMPLATE_NAME."&path=".$includefile."&lang=".LANGUAGE_ID."&template=".$template."&subdialog=Y&siteTemplateId=".TEMPLATE_NAME."','width':'1009','height':'503'}).Show();");
							?><a class="adm-btn" href="<?=$href?>" name="<?=htmlspecialcharsbx($optionCode)."_".$optionsSiteID?>" title="<?=GetMessage('OPTIONS_EDIT_BUTTON_TITLE')?>"><?=GetMessage('OPTIONS_EDIT_BUTTON_TITLE')?></a>&nbsp;<?
						}
					}
					?>
				<?endif;?>
			</td>
		<?endif;?>
		<?
	}

	public static function getActualParamsValue($arTab, $arOption, $path, $field = 'ELEMENTS'){
		$optionList = $arOption['LIST'];
		// get site template
		$arTemplate = self::GetSiteTemplate($arTab['SITE_ID']);
		if($arTemplate && $arTemplate['PATH'])
		{
			if($arPageBlocks = self::GetComponentTemplatePageBlocks($arTemplate['PATH'].$path))
			{
				foreach($arOption['LIST'] as $key_list => $arValue)
				{
					if(isset($arPageBlocks[$field][$key_list]))
						;
					else
						unset($arOption['LIST'][$key_list]);
				}
			}
			$optionList = $arOption['LIST'];
		}
		return $optionList;
	}

	public static function CheckColor($strColor){
		$strColor = substr(str_replace('#', '', $strColor), 0, 6);
		$strColor = base_convert(base_convert($strColor, 16, 2), 2, 16);
		for($i = 0, $l = 6 - (function_exists('mb_strlen') ? mb_strlen($strColor) : strlen($strColor)); $i < $l; ++$i)
			$strColor = '0'.$strColor;
		return $strColor;
	}

	public static function UpdateFrontParametrsValues(){
		$arBackParametrs = self::GetBackParametrsValues(SITE_ID);
		if($arBackParametrs['THEME_SWITCHER'] === 'Y')
		{
			if($_REQUEST && isset($_REQUEST['BASE_COLOR']))
			{
				if($_REQUEST['THEME'] === 'default')
				{
					if(self::$arParametrsList && is_array(self::$arParametrsList))
					{
						foreach(self::$arParametrsList as $blockCode => $arBlock)
						{
							unset($_SESSION['THEME'][SITE_ID]);
							$_SESSION['THEME'][SITE_ID] = null;

							if(isset($_SESSION['THEME_ACTION']))
							{
								unset($_SESSION['THEME_ACTION'][SITE_ID]);
								$_SESSION['THEME_ACTION'][SITE_ID] = null;
							}
						}
					}
					Option::set(self::MODULE_ID, "NeedGenerateCustomTheme", 'Y', SITE_ID);
					Option::set(self::MODULE_ID, 'NeedGenerateCustomThemeBG', 'Y', SITE_ID);
				}
				else
				{
					if(self::$arParametrsList && is_array(self::$arParametrsList))
					{
						foreach(self::$arParametrsList as $blockCode => $arBlock)
						{
							if($arBlock['OPTIONS'] && is_array($arBlock['OPTIONS']))
							{
								foreach($arBlock['OPTIONS'] as $optionCode => $arOption)
								{
									if($arOption['THEME'] === 'Y')
									{
										if(isset($_REQUEST[$optionCode]))
										{
											if($optionCode == 'BASE_COLOR_CUSTOM' || $optionCode == 'CUSTOM_BGCOLOR_THEME')
												$_REQUEST[$optionCode] = self::CheckColor($_REQUEST[$optionCode]);
											
											if($optionCode == 'BASE_COLOR' && $_REQUEST[$optionCode] === 'CUSTOM')
												Option::set(self::MODULE_ID, "NeedGenerateCustomTheme", 'Y', SITE_ID);
											
											if($optionCode == 'CUSTOM_BGCOLOR_THEME' && $_REQUEST[$optionCode] === 'CUSTOM')
												Option::set(self::MODULE_ID, "NeedGenerateCustomThemeBG", 'Y', SITE_ID);

											if(isset($arOption['LIST']))
											{
												if(isset($arOption['LIST'][$_REQUEST[$optionCode]]))
												{
													$_SESSION['THEME'][SITE_ID][$optionCode] = $_REQUEST[$optionCode];
												}
												else
												{
													$_SESSION['THEME'][SITE_ID][$optionCode] = $arOption['DEFAULT'];
												}
											}
											else
											{
												$_SESSION['THEME'][SITE_ID][$optionCode] = $_REQUEST[$optionCode];
											}
											if($optionCode == 'ORDER_VIEW')
												self::ClearSomeComponentsCache(SITE_ID);
											if(isset($arOption['SUB_PARAMS']) && $arOption['SUB_PARAMS']) //nested params
											{

												if($arOption['TYPE'] == 'selectbox' && isset($arOption['LIST']))
												{
													$propValue = $_SESSION['THEME'][SITE_ID][$optionCode];
													if($arOption['SUB_PARAMS'][$propValue])
													{
														foreach($arOption['SUB_PARAMS'][$propValue] as $subkey => $arSubvalue)
														{
															if($_REQUEST[$propValue.'_'.$subkey])
																$_SESSION['THEME'][SITE_ID][$propValue.'_'.$subkey] = $_REQUEST[$propValue.'_'.$subkey];
															else
															{
																if($arSubvalue['TYPE'] == 'checkbox')
																	$_SESSION['THEME'][SITE_ID][$propValue.'_'.$subkey] = 'N';
																else
																	$_SESSION['THEME'][SITE_ID][$propValue.'_'.$subkey] = $arSubvalue['DEFAULT'];
															}

															//set default template index components
															if(isset($arSubvalue['TEMPLATE']) && $arSubvalue['TEMPLATE'])
															{
																
																$code_tmp = $propValue.'_'.$subkey.'_TEMPLATE';
																if($_REQUEST[$code_tmp])
																	$_SESSION['THEME'][SITE_ID][$code_tmp] = $_REQUEST[$code_tmp];
															}
														}

														//sort order prop for main page
														$param = 'SORT_ORDER_'.$optionCode.'_'.$propValue;
														if(isset($_REQUEST[$param]))
														{
															if($_REQUEST[$param])
																$_SESSION['THEME'][SITE_ID][$param] = $_REQUEST[$param];
															else
																$_SESSION['THEME'][SITE_ID][$param] = '';
														}
													}
												}
											}
											

											if(isset($arOption['DEPENDENT_PARAMS']) && $arOption['DEPENDENT_PARAMS']) //dependent params
											{
												foreach($arOption['DEPENDENT_PARAMS'] as $key => $arSubOptions)
												{
													if($arSubOptions['THEME'] == 'Y')
													{
														if($_REQUEST[$key])
															$_SESSION['THEME'][SITE_ID][$key] = $_REQUEST[$key];
														else
														{
															if($arSubOptions['TYPE'] == 'checkbox')
															{
																if(isset($_SESSION['THEME_ACTION']) && (isset($_SESSION['THEME_ACTION'][SITE_ID][$key]) && $_SESSION['THEME_ACTION'][SITE_ID][$key]))
																{
																	$_SESSION['THEME'][SITE_ID][$key] = $_SESSION['THEME_ACTION'][SITE_ID][$key];
																	unset($_SESSION['THEME_ACTION'][SITE_ID][$key]);
																}
																else
																	$_SESSION['THEME'][SITE_ID][$key] = 'N';
															}
															else
															{
																if(isset($_SESSION['THEME_ACTION']) && (isset($_SESSION['THEME_ACTION'][SITE_ID][$key]) && $_SESSION['THEME_ACTION'][SITE_ID][$key]))
																{
																	$_SESSION['THEME'][SITE_ID][$key] = $_SESSION['THEME_ACTION'][SITE_ID][$key];
																	unset($_SESSION['THEME_ACTION'][SITE_ID][$key]);
																}
																else
																	$_SESSION['THEME'][SITE_ID][$key] = $arSubOptions['DEFAULT'];
															}
														}
													}
												}
											}

											$bChanged = true;
										}
										else
										{
											if($arOption['TYPE'] == 'checkbox' && !$_REQUEST[$optionCode])
											{
												$_SESSION['THEME'][SITE_ID][$optionCode] = 'N';
												if(isset($arOption['DEPENDENT_PARAMS']) && $arOption['DEPENDENT_PARAMS']) //dependent params save
												{
													foreach($arOption['DEPENDENT_PARAMS'] as $key => $arSubOptions)
													{
														if($arSubOptions['THEME'] == 'Y')
														{
															if(isset($_SESSION['THEME'][SITE_ID][$key]))
																$_SESSION['THEME_ACTION'][SITE_ID][$key] = $_SESSION['THEME'][SITE_ID][$key];
															else
																$_SESSION['THEME_ACTION'][SITE_ID][$key] = $arBackParametrs[$key];
														}
													}
												}
											}

											if(isset($arOption['SUB_PARAMS']) && $arOption['SUB_PARAMS']) //nested params
											{

												if($arOption['TYPE'] == 'selectbox' && isset($arOption['LIST']))
												{
													$propValue = $_SESSION['THEME'][SITE_ID][$optionCode];
													if($arOption['SUB_PARAMS'][$propValue])
													{
														foreach($arOption['SUB_PARAMS'][$propValue] as $subkey => $arSubvalue)
														{
															if($_REQUEST[$propValue.'_'.$subkey])
																$_SESSION['THEME'][SITE_ID][$propValue.'_'.$subkey] = $_REQUEST[$propValue.'_'.$subkey];
															else
																$_SESSION['THEME'][SITE_ID][$propValue.'_'.$subkey] = 'N';
														}
													}
												}

											}
										}
									}
								}
							}
						}
					}
					if(isset($_REQUEST["backurl"]) && $_REQUEST["backurl"])
						LocalRedirect($_REQUEST["backurl"]);
				}
				if(isset($_REQUEST["BASE_COLOR"]) && $_REQUEST["BASE_COLOR"])
					LocalRedirect($_SERVER["HTTP_REFERER"]);
			}
		}
		else
		{
			unset($_SESSION['THEME'][SITE_ID]);
			if(isset($_SESSION['THEME_ACTION'][SITE_ID]))
				unset($_SESSION['THEME_ACTION'][SITE_ID]);
		}
	}

	public static function GenerateMinCss($file){
		if(file_exists($file))
		{
			$content = @file_get_contents($file);
			if($content !== false)
			{
				$content = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $content);
				$content = str_replace(array("\r\n", "\r", "\n", "\t"), '', $content);
				$content = preg_replace('/ {2,}/', ' ', $content);
				$content = str_replace(array(' : ', ': ', ' :',), ':', $content);
				$content = str_replace(array(' ; ', '; ', ' ;'), ';', $content);
				$content = str_replace(array(' > ', '> ', ' >'), '>', $content);
				$content = str_replace(array(' + ', '+ ', ' +'), '+', $content);
				$content = str_replace(array(' { ', '{ ', ' {'), '{', $content);
				$content = str_replace(array(' } ', '} ', ' }'), '}', $content);
				$content = str_replace(array(' ( ', '( ', ' ('), '(', $content);
				$content = str_replace(array(' ) ', ') ', ' )'), ')', $content);
				$content = str_replace('and(', 'and (', $content);
				$content = str_replace(')li', ') li', $content);
				$content = str_replace(').', ') .', $content);
				@file_put_contents(dirname($file).'/'.basename($file, '.css').'.min.css', $content);
			}
		}
		return false;
	}

	public static function GenerateThemes(){
		$arBackParametrs = self::GetBackParametrsValues(SITE_ID);
		$arBaseColors = self::$arParametrsList['MAIN']['OPTIONS']['BASE_COLOR']['LIST'];
		$arBaseBgColors = self::$arParametrsList['MAIN']['OPTIONS']['BGCOLOR_THEME']['LIST'];
		$isCustomThemeBG = $_SESSION['THEME'][SITE_ID]['BGCOLOR_THEME'] === 'CUSTOM';
		$isCustomTheme = $_SESSION['THEME'][SITE_ID]['BASE_COLOR'] === 'CUSTOM';

		$bNeedGenerateAllThemes = Option::get(self::MODULE_ID, 'NeedGenerateThemes', 'N', SITE_ID) === 'Y';
		$bNeedGenerateCustomTheme = Option::get(self::MODULE_ID, 'NeedGenerateCustomTheme', 'N', SITE_ID) === 'Y';
		$bNeedGenerateCustomThemeBG = Option::get(self::MODULE_ID, 'NeedGenerateCustomThemeBG', 'N', SITE_ID) === 'Y';

		$baseColorCustom = $baseColorBGCustom = '';
		$lastGeneratedBaseColorCustom = Option::get(self::MODULE_ID, 'LastGeneratedBaseColorCustom', '', SITE_ID);
		if(isset(self::$arParametrsList['MAIN']['OPTIONS']['BASE_COLOR_CUSTOM']))
		{
			$baseColorCustom = $arBackParametrs['BASE_COLOR_CUSTOM'] = str_replace('#', '', $arBackParametrs['BASE_COLOR_CUSTOM']);
			if($arBackParametrs['THEME_SWITCHER'] === 'Y' && strlen($_SESSION['THEME'][SITE_ID]['BASE_COLOR_CUSTOM']))
				$baseColorCustom = $_SESSION['THEME'][SITE_ID]['BASE_COLOR_CUSTOM'] = str_replace('#', '', $_SESSION['THEME'][SITE_ID]['BASE_COLOR_CUSTOM']);
		}

		$lastGeneratedBaseColorBGCustom = Option::get(self::MODULE_ID, 'LastGeneratedBaseColorBGCustom', '', SITE_ID);
		if(isset(self::$arParametrsList['MAIN']['OPTIONS']['CUSTOM_BGCOLOR_THEME']))
		{
			$baseColorBGCustom = $arBackParametrs['CUSTOM_BGCOLOR_THEME'] = str_replace('#', '', $arBackParametrs['CUSTOM_BGCOLOR_THEME']);
			if($arBackParametrs['THEME_SWITCHER'] === 'Y' && strlen($_SESSION['THEME'][SITE_ID]['CUSTOM_BGCOLOR_THEME']))
				$baseColorBGCustom = $_SESSION['THEME'][SITE_ID]['CUSTOM_BGCOLOR_THEME'] = str_replace('#', '', $_SESSION['THEME'][SITE_ID]['CUSTOM_BGCOLOR_THEME']);
		}

		$bGenerateAll = self::devMode || $bNeedGenerateAllThemes;
		$bGenerateCustom = $bGenerateAll || $bNeedGenerateCustomTheme || ($arBackParametrs['THEME_SWITCHER'] === 'Y' && $isCustomTheme && strlen($baseColorCustom) && $baseColorCustom != $lastGeneratedBaseColorCustom);
		$bGenerateCustomBG = $bGenerateAll || $bNeedGenerateCustomThemeBG || ($arBackParametrs['THEME_SWITCHER'] === 'Y' && $isCustomThemeBG && strlen($baseColorBGCustom) && $baseColorBGCustom != $lastGeneratedBaseColorBGCustom);

		if($arBaseColors && is_array($arBaseColors) && ($bGenerateAll || $bGenerateCustom || $bGenerateCustomBG)){
			if(!class_exists('lessc')){
				include_once 'lessc.inc.php';
			}
			$less = new lessc;
			try{
				foreach($arBaseColors as $colorCode => $arColor)
				{
					if(($bCustom = ($colorCode == 'CUSTOM')) && $bGenerateCustom)
					{
						if(strlen($baseColorCustom))
						{
							$less->setVariables(array('bcolor' => (strlen($baseColorCustom) ? '#'.$baseColorCustom : $arBaseColors[self::$arParametrsList['MAIN']['OPTIONS']['BASE_COLOR']['DEFAULT']]['COLOR'])));
						}
					}
					elseif($bGenerateAll)
					{
						$less->setVariables(array('bcolor' => $arColor['COLOR']));
					}

					if($bGenerateAll || ($bCustom && $bGenerateCustom))
					{
						if(defined('SITE_TEMPLATE_PATH'))
						{
							$themeDirPath = $_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH.'/themes/'.$colorCode.($colorCode !== 'CUSTOM' ? '' : '_'.SITE_ID).'/';
							if(!is_dir($themeDirPath)) mkdir($themeDirPath, 0755, true);
								$output = $less->compileFile(__DIR__.'/../../css/colors.less', $themeDirPath.'colors.css');
							if($output)
							{
								if($bCustom)
									Option::set(self::MODULE_ID, 'LastGeneratedBaseColorCustom', $baseColorCustom, SITE_ID);
								
								self::GenerateMinCss($themeDirPath.'colors.css');
							}
						}
					}
				}
				foreach($arBaseBgColors as $colorCode => $arColor)
				{
					if(($bCustom = ($colorCode == 'CUSTOM')) && $bGenerateCustomBG)
					{
						if(strlen($baseColorBGCustom))
						{
							$footerBgColor = $baseColorBGCustom === "FFFFFF" ? "F6F6F7" : $baseColorBGCustom;
							$less->setVariables(array('bcolor' => (strlen($baseColorBGCustom) ? '#'.$baseColorBGCustom : $arBaseBgColors[self::$arParametrsList['MAIN']['OPTIONS']['BGCOLOR_THEME']['DEFAULT']]['COLOR']), 'fcolor' => '#'.$footerBgColor));
						}
					}
					elseif($bGenerateAll)
					{
						$less->setVariables(array('bcolor' => $arColor['COLOR'], 'fcolor' => $arColor['COLOR']));
					}

					if($bGenerateAll || ($bCustom && $bGenerateCustomBG))
					{
						if(defined('SITE_TEMPLATE_PATH'))
						{
							$themeDirPath = $_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH.'/bg_color/'.strToLower($colorCode.($colorCode !== 'CUSTOM' ? '' : '_'.SITE_ID)).'/';
							if(!is_dir($themeDirPath))
								mkdir($themeDirPath, 0755, true);
							$output = $less->compileFile(__DIR__.'/../../css/bgtheme.less', $themeDirPath.'bgcolors.css');
							if($output)
							{
								if($bCustom)
									Option::set(self::MODULE_ID, 'LastGeneratedBaseColorBGCustom', $baseColorBGCustom, SITE_ID);
								
								self::GenerateMinCss($themeDirPath.'bgcolors.css');
							}
						}
					}
				}
			}
			catch(exception $e){
				echo 'Fatal error: '.$e->getMessage();
				die();
			}

			if($bNeedGenerateAllThemes)
				Option::set(self::MODULE_ID, "NeedGenerateThemes", 'N', SITE_ID);
			if($bNeedGenerateCustomTheme)
				Option::set(self::MODULE_ID, "NeedGenerateCustomTheme", 'N', SITE_ID);
			if($bNeedGenerateCustomThemeBG)
				Option::set(self::MODULE_ID, "NeedGenerateCustomThemeBG", 'N', SITE_ID);
		}
	}

	public static function sendAsproBIAction($action = 'unknown') {
		if(CModule::IncludeModule('main')){

		}
	}

	public static function correctInstall(){
		if(CModule::IncludeModule('main')){
			if(Option::get(self::MODULE_ID, 'WIZARD_DEMO_INSTALLED') == 'Y'){
				require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/classes/general/wizard.php');
				@set_time_limit(0);
				if(!CWizardUtil::DeleteWizard(self::PARTNER_NAME.':'.self::SOLUTION_NAME)){
					if(!DeleteDirFilesEx($_SERVER['DOCUMENT_ROOT'].'/bitrix/wizards/'.self::PARTNER_NAME.'/'.self::SOLUTION_NAME.'/')){
						self::removeDirectory($_SERVER['DOCUMENT_ROOT'].'/bitrix/wizards/'.self::PARTNER_NAME.'/'.self::SOLUTION_NAME.'/');
					}
				}

				UnRegisterModuleDependences('main', 'OnBeforeProlog', self::MODULE_ID, __CLASS__, 'correctInstall');
				Option::set(self::MODULE_ID, 'WIZARD_DEMO_INSTALLED', 'N');
			}
		}
	}

	protected function getBitrixEdition(){
		$edition = 'UNKNOWN';

		if(CModule::IncludeModule('main')){
			include_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/classes/general/update_client.php');
			$arUpdateList = CUpdateClient::GetUpdatesList(($errorMessage = ''), 'ru', 'Y');
			if(array_key_exists('CLIENT', $arUpdateList) && $arUpdateList['CLIENT'][0]['@']['LICENSE']){
				$edition = $arUpdateList['CLIENT'][0]['@']['LICENSE'];
			}
		}

		return $edition;
	}

	protected function removeDirectory($dir){
		if($objs = glob($dir.'/*')){
			foreach($objs as $obj){
				if(is_dir($obj)){
					self::removeDirectory($obj);
				}
				else{
					if(!@unlink($obj)){
						if(chmod($obj, 0777)){
							@unlink($obj);
						}
					}
				}
			}
		}
		if(!@rmdir($dir)){
			if(chmod($dir, 0777)){
				@rmdir($dir);
			}
		}
	}

	public static function get_file_info($fileID){
		$file = CFile::GetFileArray($fileID);
		$pos = strrpos($file['FILE_NAME'], '.');
		$file['FILE_NAME'] = substr($file['FILE_NAME'], $pos);
		if(!$file['FILE_SIZE']){
			// bx bug in some version
			$file['FILE_SIZE'] = filesize($_SERVER['DOCUMENT_ROOT'].$file['SRC']);
		}
		$frm = explode('.', $file['FILE_NAME']);
		$frm = $frm[1];
		if($frm == 'doc' || $frm == 'docx'){
			$type = 'doc';
		}
		elseif($frm == 'xls' || $frm == 'xlsx'){
			$type = 'xls';
		}
		elseif($frm == 'jpg' || $frm == 'jpeg'){
			$type = 'jpg';
		}
		elseif($frm == 'png'){
			$type = 'png';
		}
		elseif($frm == 'ppt'){
			$type = 'ppt';
		}
		elseif($frm == 'tif'){
			$type = 'tif';
		}
		elseif($frm == 'txt'){
			$type = 'txt';
		}
		elseif($frm == 'rtf'){
			$type = 'rtf';
		}
		elseif($frm == 'pdf'){
			$type = 'pdf';
		}
		else{
			$type = 'none';
		}
		return $arr = array('TYPE' => $type, 'FILE_SIZE' => $file['FILE_SIZE'], 'SRC' => $file['SRC'], 'DESCRIPTION' => $file['DESCRIPTION'], 'ORIGINAL_NAME' => $file['ORIGINAL_NAME']);
	}

	public static function filesize_format($filesize){
		$formats = array(GetMessage('CT_NAME_b'), GetMessage('CT_NAME_KB'), GetMessage('CT_NAME_MB'), GetMessage('CT_NAME_GB'), GetMessage('CT_NAME_TB'));
		$format = 0;
		while($filesize > 1024 && count($formats) != ++$format){
			$filesize = round($filesize / 1024, 1);
		}
		$formats[] = GetMessage('CT_NAME_TB');
		return $filesize.' '.$formats[$format];
	}

	public static function getChilds($input, &$start = 0, $level = 0){
		$arIblockItemsMD5 = array();

		if(!$level){
			$lastDepthLevel = 1;
			if($input && is_array($input)){
				foreach($input as $i => $arItem){
					if($arItem['DEPTH_LEVEL'] > $lastDepthLevel){
						if($i > 0){
							if(!$input[$i - 1]['IS_PARENT'])
								$input[$i - 1]['NO_PARENT'] = false;
							$input[$i - 1]['IS_PARENT'] = 1;
						}
					}
					$lastDepthLevel = $arItem['DEPTH_LEVEL'];
				}
			}
		}

		$childs = array();
		$count = count($input);
		for($i = $start; $i < $count; ++$i){
			$item = $input[$i];
			if(!isset($item)){
				continue;
			}
			if($level > $item['DEPTH_LEVEL'] - 1){
				break;
			}
			else{
				if(!empty($item['IS_PARENT'])){
					$i++;
					$item['CHILD'] = self::getChilds($input, $i, $level + 1);
					$i--;
				}

				$childs[] = $item;
			}
		}
		$start = $i;

		if(is_array($childs)){
			foreach($childs as $j => $item){
				if($item['PARAMS']){
					$md5 = md5($item['TEXT'].$item['LINK'].$item['SELECTED'].$item['PERMISSION'].$item['ITEM_TYPE'].$item['IS_PARENT'].serialize($item['ADDITIONAL_LINKS']).serialize($item['PARAMS']));

					// check if repeat in one section chids list
					if(isset($arIblockItemsMD5[$md5][$item['PARAMS']['DEPTH_LEVEL']])){
						if(isset($arIblockItemsMD5[$md5][$item['PARAMS']['DEPTH_LEVEL']][$level]) || ($item['DEPTH_LEVEL'] === 1 && !$level)){
							unset($childs[$j]);
							continue;
						}
					}
					if(!isset($arIblockItemsMD5[$md5])){
						$arIblockItemsMD5[$md5] = array($item['PARAMS']['DEPTH_LEVEL'] => array($level => true));
					}
					else{
						$arIblockItemsMD5[$md5][$item['PARAMS']['DEPTH_LEVEL']][$level] = true;
					}
				}
			}
		}

		if(!$level){
			$arIblockItemsMD5 = array();
		}

		return $childs;
	}

	public static function sort_sections_by_field($arr, $name){
		$count = count($arr);
		for($i = 0; $i < $count; $i++){
			for($j = 0; $j < $count; $j++){
				if(strtoupper($arr[$i]['NAME']) < strtoupper($arr[$j]['NAME'])){
					$tmp = $arr[$i];
					$arr[$i] = $arr[$j];
					$arr[$j] = $tmp;
				}
			}
		}
		return $arr;
	}

	public static function getIBItems($prop, $checkNoImage){
		$arID = array();
		$arItems = array();
		$arAllItems = array();

		if($prop && is_array($prop)){
			foreach($prop as $reviewID){
				$arID[]=$reviewID;
			}
		}
		if($checkNoImage) $empty=false;
		$arItems = self::cacheElement(false, array('ID' => $arID, 'ACTIVE' => 'Y'));
		if($arItems && is_array($arItems)){
			foreach($arItems as $key => $arItem){
				if($checkNoImage){
					if(empty($arProject['PREVIEW_PICTURE'])){
						$empty=true;
					}
				}
				$arAllItems['ITEMS'][$key] = $arItem;
				if($arItem['DETAIL_PICTURE']) $arAllItems['ITEMS'][$key]['DETAIL'] = CFile::GetFileArray( $arItem['DETAIL_PICTURE'] );
				if($arItem['PREVIEW_PICTURE']) $arAllItems['ITEMS'][$key]['PREVIEW'] = CFile::ResizeImageGet( $arItem['PREVIEW_PICTURE'], array('width' => 425, 'height' => 330), BX_RESIZE_IMAGE_EXACT, true );
			}
		}
		if($checkNoImage) $arAllItems['NOIMAGE'] = 'YES';

		return $arAllItems;
	}

	public static function showBgImage($siteID, $arTheme){
		global $APPLICATION;
		if($arTheme['SHOW_BG_BLOCK'] == 'Y')
		{
			$arBanner = self::checkBgImage($siteID);

			if($arBanner)
			{
				$image = CFile::GetFileArray($arBanner['PREVIEW_PICTURE']);
				$class = 'bg_image_site opacity1';
				if($arBanner['PROPERTY_FIXED_BANNER_VALUE'] == 'Y')
					$class .= ' fixed';
				if(self::IsMainPage())
					$class .= ' opacity';
				echo '<span class=\''.$class.'\' style=\'background-image:url('.$image["SRC"].');\'></span>';

				global $showBgBanner;
				$showBgBanner = true;
			}
		}
		return true;
	}

	public static function checkBgImage($siteID){
		global $APPLICATION, $arRegion;
		static $arBanner;
		if($arBanner === NULL)
		{
			$bgIbockID = (CCache::$arIBlocks[$siteID]['aspro_allcorp2_content']['aspro_allcorp2_bg_images'][0] ? CCache::$arIBlocks[$siteID]['aspro_allcorp2_content']['aspro_allcorp2_bg_images'][0] : CCache::$arIBlocks[$siteID]['aspro_allcorp2_adv']['aspro_allcorp2_bg_images'][0]);

			$arFilterBanner = array('IBLOCK_ID' => $bgIbockID, 'ACTIVE'=>'Y');

			if($arRegion && isset($arTheme['REGIONALITY_FILTER_ITEM']) && $arTheme['REGIONALITY_FILTER_ITEM']['VALUE'] == 'Y')
				$arFilterBanner['PROPERTY_LINK_REGION'] = $arRegion['ID'];

			$arItems = CCache::CIBLockElement_GetList(array('SORT' => 'ASC', 'CACHE' => array('TAG' => $bgIbockID)), $arFilterBanner, false, false, array('ID', 'NAME', 'PREVIEW_PICTURE', 'PROPERTY_URL', 'PROPERTY_FIXED_BANNER', 'PROPERTY_URL_NOT_SHOW'));
			$arBanner = array();

			if($arItems)
			{
				$curPage = $APPLICATION->GetCurPage();
				foreach($arItems as $arItem)
				{
					if(isset($arItem['PROPERTY_URL_VALUE']) && $arItem['PREVIEW_PICTURE'])
					{
						if(!is_array($arItem['PROPERTY_URL_VALUE']))
							$arItem['PROPERTY_URL_VALUE'] = array($arItem['PROPERTY_URL_VALUE']);
						if($arItem['PROPERTY_URL_VALUE'])
						{
							foreach($arItem['PROPERTY_URL_VALUE'] as $url)
							{
								$url=str_replace('SITE_DIR', SITE_DIR, $url);
								if($arItem['PROPERTY_URL_NOT_SHOW_VALUE'])
								{
									if(!is_array($arItem['PROPERTY_URL_NOT_SHOW_VALUE']))
										$arItem['PROPERTY_URL_NOT_SHOW_VALUE'] = array($arItem['PROPERTY_URL_NOT_SHOW_VALUE']);
									foreach($arItem['PROPERTY_URL_NOT_SHOW_VALUE'] as $url_not_show)
									{
										$url_not_show=str_replace('SITE_DIR', SITE_DIR, $url_not_show);
										if(CSite::InDir($url_not_show))
											break 2;
									}
									foreach($arItem['PROPERTY_URL_NOT_SHOW_VALUE'] as $url_not_show)
									{
										$url_not_show = str_replace('SITE_DIR', SITE_DIR, $url_not_show);
										if(CSite::InDir($url_not_show))
										{
											// continue;
											break 2;
										}
										else
										{
											if(CSite::InDir($url))
											{
												$arBanner = $arItem;
												break;
											}
										}
									}
								}
								else
								{
									if(CSite::InDir($url))
									{
										$arBanner = $arItem;
										break;
									}
								}
							}
						}
					}
				}
			}
		}
		return $arBanner;
	}

	public static function getSectionChilds($PSID, &$arSections, &$arSectionsByParentSectionID, &$arItemsBySectionID, &$aMenuLinksExt){
		if($arSections && is_array($arSections)){
			foreach($arSections as $arSection){
				if($arSection['IBLOCK_SECTION_ID'] == $PSID){
					$arItem = array($arSection['NAME'], $arSection['SECTION_PAGE_URL'], array(), array('FROM_IBLOCK' => 1, 'DEPTH_LEVEL' => $arSection['DEPTH_LEVEL']));
					$arItem[3]['IS_PARENT'] = (isset($arItemsBySectionID[$arSection['ID']]) || isset($arSectionsByParentSectionID[$arSection['ID']]) ? 1 : 0);
					if($arSection["PICTURE"])
						$arItem[3]["PICTURE"]=$arSection["PICTURE"];
					$aMenuLinksExt[] = $arItem;
					if($arItem[3]['IS_PARENT']){
						// subsections
						self::getSectionChilds($arSection['ID'], $arSections, $arSectionsByParentSectionID, $arItemsBySectionID, $aMenuLinksExt);
						// section elements
						if($arItemsBySectionID[$arSection['ID']] && is_array($arItemsBySectionID[$arSection['ID']])){
							foreach($arItemsBySectionID[$arSection['ID']] as $arItem){
								if(is_array($arItem['DETAIL_PAGE_URL'])){
									if(isset($arItem['CANONICAL_PAGE_URL'])){
										$arItem['DETAIL_PAGE_URL'] = $arItem['CANONICAL_PAGE_URL'];
									}
									else{
										$arItem['DETAIL_PAGE_URL'] = $arItem['DETAIL_PAGE_URL'][key($arItem['DETAIL_PAGE_URL'])];
									}
								}
								$arTmpLink = array();
								if($arItem['LINK_REGION'])
									$arTmpLink['LINK_REGION'] = $arItem['LINK_REGION'];
								$aMenuLinksExt[] = array($arItem['NAME'], $arItem['DETAIL_PAGE_URL'], array(), array_merge(array('FROM_IBLOCK' => 1, 'DEPTH_LEVEL' => ($arSection['DEPTH_LEVEL'] + 1), 'IS_ITEM' => 1), $arTmpLink));
							}
						}
					}
				}
			}
		}
	}

	public static function isChildsSelected($arChilds){
		if($arChilds && is_array($arChilds)){
			foreach($arChilds as $arChild){
				if($arChild['SELECTED']){
					return $arChild;
				}
			}
		}
		return false;
	}

	public static function SetJSOptions(){
		$arFrontParametrs = CAllcorp2::GetFrontParametrsValues(SITE_ID);
		$tmp = $arFrontParametrs['DATE_FORMAT'];
		$DATE_MASK = ($tmp == 'DOT' ? 'dd.mm.yyyy' : ($tmp == 'HYPHEN' ? 'dd-mm-yyyy' : ($tmp == 'SPACE' ? 'dd mm yyyy' : ($tmp == 'SLASH' ? 'dd/mm/yyyy' : 'dd:mm:yyyy'))));
		$VALIDATE_DATE_MASK = ($tmp == 'DOT' ? '^[0-9]{1,2}\.[0-9]{1,2}\.[0-9]{4}$' : ($tmp == 'HYPHEN' ? '^[0-9]{1,2}\-[0-9]{1,2}\-[0-9]{4}$' : ($tmp == 'SPACE' ? '^[0-9]{1,2} [0-9]{1,2} [0-9]{4}$' : ($tmp == 'SLASH' ? '^[0-9]{1,2}\/[0-9]{1,2}\/[0-9]{4}$' : '^[0-9]{1,2}\:[0-9]{1,2}\:[0-9]{4}$'))));
		$DATE_PLACEHOLDER = ($tmp == 'DOT' ? GetMessage('DATE_FORMAT_DOT') : ($tmp == 'HYPHEN' ? GetMessage('DATE_FORMAT_HYPHEN') : ($tmp == 'SPACE' ? GetMessage('DATE_FORMAT_SPACE') : ($tmp == 'SLASH' ? GetMessage('DATE_FORMAT_SLASH') : GetMessage('DATE_FORMAT_COLON')))));
		$DATETIME_MASK = $DATE_MASK.' h:s';
		$DATETIME_PLACEHOLDER = ($tmp == 'DOT' ? GetMessage('DATE_FORMAT_DOT') : ($tmp == 'HYPHEN' ? GetMessage('DATE_FORMAT_HYPHEN') : ($tmp == 'SPACE' ? GetMessage('DATE_FORMAT_SPACE') : ($tmp == 'SLASH' ? GetMessage('DATE_FORMAT_SLASH') : GetMessage('DATE_FORMAT_COLON'))))).' '.GetMessage('TIME_FORMAT_COLON');
		$VALIDATE_DATETIME_MASK = ($tmp == 'DOT' ? '^[0-9]{1,2}\.[0-9]{1,2}\.[0-9]{4} [0-9]{1,2}\:[0-9]{1,2}$' : ($tmp == 'HYPHEN' ? '^[0-9]{1,2}\-[0-9]{1,2}\-[0-9]{4} [0-9]{1,2}\:[0-9]{1,2}$' : ($tmp == 'SPACE' ? '^[0-9]{1,2} [0-9]{1,2} [0-9]{4} [0-9]{1,2}\:[0-9]{1,2}$' : ($tmp == 'SLASH' ? '^[0-9]{1,2}\/[0-9]{1,2}\/[0-9]{4} [0-9]{1,2}\:[0-9]{1,2}$' : '^[0-9]{1,2}\:[0-9]{1,2}\:[0-9]{4} [0-9]{1,2}\:[0-9]{1,2}$'))));

		//get domains
		$arSite = CSite::GetByID(SITE_ID)->Fetch();
		$arDomains = array();
		if(strlen($arSite['DOMAINS']) > 1)
			$arDomains = explode("\n", $arSite['DOMAINS']);
		if($arSite['SERVER_NAME'])
			$arDomains[] = $arSite['SERVER_NAME'];
		if($arDomains)
			array_unique($arDomains);
		$strListDomains = implode(',', $arDomains);

		?>
		<script type='text/javascript'>
		var arBasketItems = {};
		var arAllcorp2Options = ({
			'SITE_DIR' : '<?=SITE_DIR?>',
			'SITE_ID' : '<?=SITE_ID?>',
			'SITE_TEMPLATE_PATH' : '<?=SITE_TEMPLATE_PATH?>',
			'SITE_ADDRESS' : "<?=($strListDomains ? CUtil::PhpToJsObject($strListDomains) : $strListDomains);?>",
			'THEME' : ({
				'THEME_SWITCHER' : '<?=$arFrontParametrs['THEME_SWITCHER']?>',
				'BASE_COLOR' : '<?=$arFrontParametrs['BASE_COLOR']?>',
				'BASE_COLOR_CUSTOM' : '<?=$arFrontParametrs['BASE_COLOR_CUSTOM']?>',
				'LOGO_IMAGE' : '<?=$arFrontParametrs['LOGO_IMAGE']?>',
				'LOGO_IMAGE_LIGHT' : '<?=$arFrontParametrs['LOGO_IMAGE_LIGHT']?>',
				'TOP_MENU' : '<?=$arFrontParametrs['TOP_MENU']?>',
				'TOP_MENU_FIXED' : '<?=$arFrontParametrs['TOP_MENU_FIXED']?>',
				'COLORED_LOGO' : '<?=$arFrontParametrs['COLORED_LOGO']?>',
				'SIDE_MENU' : '<?=$arFrontParametrs['SIDE_MENU']?>',
				'SCROLLTOTOP_TYPE' : '<?=$arFrontParametrs['SCROLLTOTOP_TYPE']?>',
				'SCROLLTOTOP_POSITION' : '<?=$arFrontParametrs['SCROLLTOTOP_POSITION']?>',
				'CAPTCHA_FORM_TYPE' : '<?=$arFrontParametrs['CAPTCHA_FORM_TYPE']?>',
				'PHONE_MASK' : '<?=$arFrontParametrs['PHONE_MASK']?>',
				'VALIDATE_PHONE_MASK' : '<?=$arFrontParametrs['VALIDATE_PHONE_MASK']?>',
				'DATE_MASK' : '<?=$DATE_MASK?>',
				'DATE_PLACEHOLDER' : '<?=$DATE_PLACEHOLDER?>',
				'VALIDATE_DATE_MASK' : '<?=($VALIDATE_DATE_MASK)?>',
				'DATETIME_MASK' : '<?=$DATETIME_MASK?>',
				'DATETIME_PLACEHOLDER' : '<?=$DATETIME_PLACEHOLDER?>',
				'VALIDATE_DATETIME_MASK' : '<?=($VALIDATE_DATETIME_MASK)?>',
				'VALIDATE_FILE_EXT' : '<?=$arFrontParametrs['VALIDATE_FILE_EXT']?>',
				'SOCIAL_VK' : '<?=$arFrontParametrs['SOCIAL_VK']?>',
				'SOCIAL_FACEBOOK' : '<?=$arFrontParametrs['SOCIAL_FACEBOOK']?>',
				'SOCIAL_TWITTER' : '<?=$arFrontParametrs['SOCIAL_TWITTER']?>',
				'SOCIAL_YOUTUBE' : '<?=$arFrontParametrs['SOCIAL_YOUTUBE']?>',
				'SOCIAL_ODNOKLASSNIKI' : '<?=$arFrontParametrs['SOCIAL_ODNOKLASSNIKI']?>',
				'SOCIAL_GOOGLEPLUS' : '<?=$arFrontParametrs['SOCIAL_GOOGLEPLUS']?>',
				'BANNER_WIDTH' : '<?=$arFrontParametrs['BANNER_WIDTH']?>',
				'TEASERS_INDEX' : '<?=$arFrontParametrs[$arFrontParametrs['INDEX_TYPE'].'_TEASERS_INDEX']?>',
				'CATALOG_INDEX' : '<?=$arFrontParametrs[$arFrontParametrs['INDEX_TYPE'].'_CATALOG_INDEX']?>',
				'PORTFOLIO_INDEX' : '<?=$arFrontParametrs[$arFrontParametrs['INDEX_TYPE'].'_PORTFOLIO_INDEX']?>',
				'INSTAGRAMM_INDEX' : '<?=(isset($arFrontParametrs[$arFrontParametrs['INDEX_TYPE'].'_INSTAGRAMM_INDEX']) ? $arFrontParametrs[$arFrontParametrs['INDEX_TYPE'].'_INSTAGRAMM_INDEX'] : 'Y')?>',
				'BIGBANNER_ANIMATIONTYPE' : '<?=$arFrontParametrs['BIGBANNER_ANIMATIONTYPE']?>',
				'BIGBANNER_SLIDESSHOWSPEED' : '<?=$arFrontParametrs['BIGBANNER_SLIDESSHOWSPEED']?>',
				'BIGBANNER_ANIMATIONSPEED' : '<?=$arFrontParametrs['BIGBANNER_ANIMATIONSPEED']?>',
				'PARTNERSBANNER_SLIDESSHOWSPEED' : '<?=$arFrontParametrs['PARTNERSBANNER_SLIDESSHOWSPEED']?>',
				'PARTNERSBANNER_ANIMATIONSPEED' : '<?=$arFrontParametrs['PARTNERSBANNER_ANIMATIONSPEED']?>',
				'ORDER_VIEW' : '<?=$arFrontParametrs['ORDER_VIEW']?>',
				'ORDER_BASKET_VIEW' : '<?=$arFrontParametrs['ORDER_BASKET_VIEW']?>',
				'URL_BASKET_SECTION' : '<?=$arFrontParametrs['URL_BASKET_SECTION']?>',
				'URL_ORDER_SECTION' : '<?=$arFrontParametrs['URL_ORDER_SECTION']?>',
				'PAGE_WIDTH' : '<?=$arFrontParametrs['PAGE_WIDTH']?>',
				'PAGE_CONTACTS' : '<?=$arFrontParametrs['PAGE_CONTACTS']?>',
				'CATALOG_BLOCK_TYPE' : '<?=$arFrontParametrs['ELEMENTS_TABLE_TYPE_VIEW']?>',
				'HEADER_TYPE' : '<?=$arFrontParametrs['HEADER_TYPE']?>',
				'HEADER_TOP_LINE' : '<?=$arFrontParametrs['HEADER_TOP_LINE']?>',
				'HEADER_FIXED' : '<?=$arFrontParametrs['HEADER_FIXED']?>',
				'HEADER_MOBILE' : '<?=$arFrontParametrs['HEADER_MOBILE']?>',
				'HEADER_MOBILE_MENU' : '<?=$arFrontParametrs['HEADER_MOBILE_MENU']?>',
				'HEADER_MOBILE_MENU_SHOW_TYPE' : '<?=$arFrontParametrs['HEADER_MOBILE_MENU_SHOW_TYPE']?>',
				'TYPE_SEARCH' : '<?=$arFrontParametrs['TYPE_SEARCH']?>',
				'PAGE_TITLE' : '<?=$arFrontParametrs['PAGE_TITLE']?>',
				'INDEX_TYPE' : '<?=$arFrontParametrs['INDEX_TYPE']?>',
				'FOOTER_TYPE' : '<?=$arFrontParametrs['FOOTER_TYPE']?>',
				'REGIONALITY_SEARCH_ROW' : '<?=$arFrontParametrs['REGIONALITY_SEARCH_ROW']?>',
				'FOOTER_TYPE' : '<?=$arFrontParametrs['FOOTER_TYPE']?>',
				'PRINT_BUTTON' : '<?=$arFrontParametrs['PRINT_BUTTON']?>',
				'SHOW_SMARTFILTER' : '<?=$arFrontParametrs['SHOW_SMARTFILTER']?>',
				'LICENCE_CHECKED' : '<?=$arFrontParametrs['LICENCE_CHECKED']?>',
				'FILTER_VIEW' : '<?=$arFrontParametrs['FILTER_VIEW']?>',
				'YA_GOLAS' : '<?=$arFrontParametrs['YA_GOLAS']?>',
				'YA_COUNTER_ID' : '<?=$arFrontParametrs['YA_COUNTER_ID']?>',
				'USE_FORMS_GOALS' : '<?=$arFrontParametrs['USE_FORMS_GOALS']?>',
				'USE_SALE_GOALS' : '<?=$arFrontParametrs['USE_SALE_GOALS']?>',
				'USE_DEBUG_GOALS' : '<?=$arFrontParametrs['USE_DEBUG_GOALS']?>',
				'DEFAULT_MAP_MARKET' : '<?=$arFrontParametrs['DEFAULT_MAP_MARKET']?>',
				'IS_BASKET_PAGE' : '<?=CAllcorp2::IsBasketPage($arFrontParametrs["URL_BASKET_SECTION"])?>',
				'IS_ORDER_PAGE' : '<?=CAllcorp2::IsBasketPage($arFrontParametrs["URL_ORDER_SECTION"])?>',
			})
		});
		if(arAllcorp2Options.SITE_ADDRESS)
			arAllcorp2Options.SITE_ADDRESS = arAllcorp2Options.SITE_ADDRESS.replace(/'/g, "");
		</script>
		<?
		Bitrix\Main\Page\Frame::getInstance()->startDynamicWithID('options-block');
		self::checkBasketItems();
		Bitrix\Main\Page\Frame::getInstance()->finishDynamicWithID('options-block', '');
	}

	public static function __ShowFilePropertyField($name, $arOption, $values){
		global $bCopy, $historyId;
		if(!is_array($values)){
			$values = array($values);
		}

		if($bCopy || empty($values)){
			$values = array('n0' => 0);
		}

		$optionWidth = $arOption['WIDTH'] ? $arOption['WIDTH'] : 200;
		$optionHeight = $arOption['HEIGHT'] ? $arOption['HEIGHT'] : 100;


		if($arOption['MULTIPLE'] == 'N'){
			foreach($values as $key => $val){
				if(is_array($val)){
					$file_id = $val['VALUE'];
				}
				else{
					$file_id = $val;
				}
				if($historyId > 0){
					echo CFileInput::Show($name.'['.$key.']', $file_id,
						array(
							'IMAGE' => $arOption['IMAGE'],
							'PATH' => 'Y',
							'FILE_SIZE' => 'Y',
							'DIMENSIONS' => 'Y',
							'IMAGE_POPUP' => 'Y',
							'MAX_SIZE' => array(
								'W' => $optionWidth,
								'H' => $optionHeight,
							),
						)
					);
				}
				else{

					echo CFileInput::Show($name.'['.$key.']', $file_id,
						array(
							'IMAGE' => $arOption['IMAGE'],
							'PATH' => 'Y',
							'FILE_SIZE' => 'Y',
							'DIMENSIONS' => 'Y',
							'IMAGE_POPUP' => 'Y',
							'MAX_SIZE' => array(
							'W' => $optionWidth,
							'H' => $optionHeight,
							),
						),
						array(
							'upload' => true,
							'medialib' => true,
							'file_dialog' => true,
							'cloud' => true,
							'del' => true,
							'description' => $arOption['WITH_DESCRIPTION'] == 'Y',
						)
					);
				}
				break;
			}
		}
		else{
			$inputName = array();
			foreach($values as $key => $val){
				if(is_array($val)){
					$inputName[$name.'['.$key.']'] = $val['VALUE'];
				}
				else{
					$inputName[$name.'['.$key.']'] = $val;
				}
			}
			if($historyId > 0){
				echo CFileInput::ShowMultiple($inputName, $name.'[n#IND#]',
					array(
						'IMAGE' => $arOption['IMAGE'],
						'PATH' => 'Y',
						'FILE_SIZE' => 'Y',
						'DIMENSIONS' => 'Y',
						'IMAGE_POPUP' => 'Y',
						'MAX_SIZE' => array(
							'W' => $optionWidth,
							'H' => $optionHeight,
						),
					),
				false);
			}
			else{
				echo CFileInput::ShowMultiple($inputName, $name.'[n#IND#]',
					array(
						'IMAGE' => $arOption['IMAGE'],
						'PATH' => 'Y',
						'FILE_SIZE' => 'Y',
						'DIMENSIONS' => 'Y',
						'IMAGE_POPUP' => 'Y',
						'MAX_SIZE' => array(
							'W' => $optionWidth,
							'H' => $optionHeight,
						),
					),
				false,
					array(
						'upload' => true,
						'medialib' => true,
						'file_dialog' => true,
						'cloud' => true,
						'del' => true,
						'description' => $arOption['WITH_DESCRIPTION'] == 'Y',
					)
				);
			}
		}
	}

	public static function IsCompositeEnabled(){
		if(class_exists('CHTMLPagesCache')){
			if(method_exists('CHTMLPagesCache', 'GetOptions')){
				if($arHTMLCacheOptions = CHTMLPagesCache::GetOptions()){
					if(method_exists('CHTMLPagesCache', 'isOn')){
						if (CHTMLPagesCache::isOn()){
							if(isset($arHTMLCacheOptions['AUTO_COMPOSITE']) && $arHTMLCacheOptions['AUTO_COMPOSITE'] === 'Y'){
								return 'AUTO_COMPOSITE';
							}
							else{
								return 'COMPOSITE';
							}
						}
					}
					else{
						if($arHTMLCacheOptions['COMPOSITE'] === 'Y'){
							return 'COMPOSITE';
						}
					}
				}
			}
		}

		return false;
	}

	public static function EnableComposite($auto = false){
		if(class_exists('CHTMLPagesCache')){
			if(method_exists('CHTMLPagesCache', 'GetOptions')){
				if($arHTMLCacheOptions = CHTMLPagesCache::GetOptions()){
					$arHTMLCacheOptions['COMPOSITE'] = 'Y';
					$arHTMLCacheOptions['AUTO_UPDATE'] = 'Y'; // standart mode
					$arHTMLCacheOptions['AUTO_UPDATE_TTL'] = '0'; // no ttl delay
					$arHTMLCacheOptions['AUTO_COMPOSITE'] = ($auto ? 'Y' : 'N'); // auto composite mode
					CHTMLPagesCache::SetEnabled(true);
					CHTMLPagesCache::SetOptions($arHTMLCacheOptions);
					bx_accelerator_reset();
				}
			}
		}
	}

	public static function GetCurrentElementFilter(&$arVariables, &$arParams){
        $arFilter = array('IBLOCK_ID' => $arParams['IBLOCK_ID'], 'INCLUDE_SUBSECTIONS' => 'Y');
        if($arParams['CHECK_DATES'] == 'Y'){
            $arFilter = array_merge($arFilter, array('ACTIVE' => 'Y', 'SECTION_GLOBAL_ACTIVE' => 'Y', 'ACTIVE_DATE' => 'Y'));
        }
        if($arVariables['ELEMENT_ID']){
            $arFilter['ID'] = $arVariables['ELEMENT_ID'];
        }
        elseif(strlen($arVariables['ELEMENT_CODE'])){
            $arFilter['CODE'] = $arVariables['ELEMENT_CODE'];
        }
		if($arVariables['SECTION_ID']){
			$arFilter['SECTION_ID'] = ($arVariables['SECTION_ID'] ? $arVariables['SECTION_ID'] : false);
		}
		if($arVariables['SECTION_CODE']){
			$arFilter['SECTION_CODE'] = ($arVariables['SECTION_CODE'] ? $arVariables['SECTION_CODE'] : false);
		}
        if(!$arFilter['SECTION_ID'] && !$arFilter['SECTION_CODE']){
            unset($arFilter['SECTION_GLOBAL_ACTIVE']);
        }
        return $arFilter;
    }

	public static function GetCurrentSectionFilter(&$arVariables, &$arParams){
		$arFilter = array('IBLOCK_ID' => $arParams['IBLOCK_ID']);
		if($arParams['CHECK_DATES'] == 'Y'){
			$arFilter = array_merge($arFilter, array('ACTIVE' => 'Y', 'GLOBAL_ACTIVE' => 'Y', 'ACTIVE_DATE' => 'Y'));
		}
		if($arVariables['SECTION_ID']){
			$arFilter['ID'] = $arVariables['SECTION_ID'];
		}
		if(strlen($arVariables['SECTION_CODE'])){
			$arFilter['CODE'] = $arVariables['SECTION_CODE'];
		}
		if(!$arVariables['SECTION_ID'] && !strlen($arFilter['CODE'])){
			$arFilter['ID'] = 0; // if section not found
		}
		return $arFilter;
	}

	public static function GetCurrentSectionElementFilter(&$arVariables, &$arParams, $CurrentSectionID = false){
		$arFilter = array('IBLOCK_ID' => $arParams['IBLOCK_ID'], 'INCLUDE_SUBSECTIONS' => 'N');

		if(isset($arParams['INCLUDE_SUBSECTIONS']))
		{
			$arFilter['INCLUDE_SUBSECTIONS'] = $arParams['INCLUDE_SUBSECTIONS'];
			if($arParams['INCLUDE_SUBSECTIONS'] == 'A')
			{
				$arFilter['SECTION_GLOBAL_ACTIVE'] = $arFilter['INCLUDE_SUBSECTIONS'] = 'Y';
			}
		}
		if($arParams['CHECK_DATES'] == 'Y')
			$arFilter = array_merge($arFilter, array('ACTIVE' => 'Y', 'SECTION_GLOBAL_ACTIVE' => 'Y', 'ACTIVE_DATE' => 'Y'));
		
		if(!$arFilter['SECTION_ID'] = ($CurrentSectionID !== false ? $CurrentSectionID : ($arVariables['SECTION_ID'] ? $arVariables['SECTION_ID'] : false))){
			unset($arFilter['SECTION_GLOBAL_ACTIVE']);
		}
		if(!$arFilter['SECTION_ID'] && $arVariables['SECTION_CODE'])
			$arFilter['SECTION_CODE'] = $arVariables['SECTION_CODE'];

		if(strlen($arParams['FILTER_NAME'])){
			$GLOBALS[$arParams['FILTER_NAME']] = (array)$GLOBALS[$arParams['FILTER_NAME']];
			// print_r($GLOBALS[$arParams['FILTER_NAME']]);
			foreach($arUnsetFilterFields = array('SECTION_ID', 'SECTION_CODE', 'SECTION_ACTIVE', 'SECTION_GLOBAL_ACTIVE') as $filterUnsetField){
				foreach($GLOBALS[$arParams['FILTER_NAME']] as $filterField => $filterValue){
					if(($p = strpos($filterUnsetField, $filterField)) !== false && $p < 2){
						unset($GLOBALS[$arParams['FILTER_NAME']][$filterField]);
					}
				}
			}
			if($GLOBALS[$arParams['FILTER_NAME']]){
				$arFilter = array_merge($arFilter, $GLOBALS[$arParams['FILTER_NAME']]);
			}
		}
		return $arFilter;
	}

	public static function GetCurrentSectionSubSectionFilter(&$arVariables, &$arParams, $CurrentSectionID = false){
		$arFilter = array('IBLOCK_ID' => $arParams['IBLOCK_ID']);
		if($arParams['CHECK_DATES'] == 'Y'){
			$arFilter = array_merge($arFilter, array('ACTIVE' => 'Y', 'GLOBAL_ACTIVE' => 'Y', 'ACTIVE_DATE' => 'Y'));
		}
		if(!$arFilter['SECTION_ID'] = ($CurrentSectionID !== false ? $CurrentSectionID : ($arVariables['SECTION_ID'] ? $arVariables['SECTION_ID'] : false))){
			$arFilter['INCLUDE_SUBSECTIONS'] = 'N';array_merge($arFilter, array('INCLUDE_SUBSECTIONS' => 'N', 'DEPTH_LEVEL' => '1'));
			$arFilter['DEPTH_LEVEL'] = '1';
			unset($arFilter['GLOBAL_ACTIVE']);
		}
		return $arFilter;
	}

	public static function GetIBlockAllElementsFilter(&$arParams){
		$arFilter = array('IBLOCK_ID' => $arParams['IBLOCK_ID'], 'INCLUDE_SUBSECTIONS' => 'Y');
		if($arParams['CHECK_DATES'] == 'Y'){
			$arFilter = array_merge($arFilter, array('ACTIVE' => 'Y', 'ACTIVE_DATE' => 'Y'));
		}
		if(strlen($arParams['FILTER_NAME']) && (array)$GLOBALS[$arParams['FILTER_NAME']]){
			$arFilter = array_merge($arFilter, (array)$GLOBALS[$arParams['FILTER_NAME']]);
		}
		return $arFilter;
	}

	public static function CheckSmartFilterSEF($arParams, $component){
		if($arParams['SEF_MODE'] === 'Y' && strlen($arParams['FILTER_URL_TEMPLATE']) && is_object($component)){
			$arVariables = $arDefaultUrlTemplates404 = $arDefaultVariableAliases404 = $arDefaultVariableAliases = array();
			$smartBase = ($arParams["SEF_URL_TEMPLATES"]["section"] ? $arParams["SEF_URL_TEMPLATES"]["section"] : "#SECTION_ID#/");
			$arParams["SEF_URL_TEMPLATES"]["smart_filter"] = $smartBase."filter/#SMART_FILTER_PATH#/apply/";
			$arComponentVariables = array("SECTION_ID", "SECTION_CODE", "ELEMENT_ID", "ELEMENT_CODE", "action");
			$engine = new CComponentEngine($component);
			$engine->addGreedyPart("#SECTION_CODE_PATH#");
			$engine->addGreedyPart("#SMART_FILTER_PATH#");
			$engine->setResolveCallback(array("CIBlockFindTools", "resolveComponentEngine"));
			$arUrlTemplates = CComponentEngine::MakeComponentUrlTemplates($arDefaultUrlTemplates404, $arParams["SEF_URL_TEMPLATES"]);
			$componentPage = $engine->guessComponentPath($arParams["SEF_FOLDER"], $arUrlTemplates, $arVariables);
			if($componentPage === 'smart_filter'){
				$arVariableAliases = CComponentEngine::MakeComponentVariableAliases($arDefaultVariableAliases404, $arParams["VARIABLE_ALIASES"]);
				CComponentEngine::InitComponentVariables($componentPage, $arComponentVariables, $arVariableAliases, $arVariables);
				return $arResult = array("FOLDER" => $arParams["SEF_FOLDER"], "URL_TEMPLATES" => $arUrlTemplates, "VARIABLES" => $arVariables, "ALIASES" => $arVariableAliases);
			}
		}

		return false;
	}

	public static function AddMeta($arParams = array()){
		self::$arMetaParams = array_merge((array)self::$arMetaParams, (array)$arParams);
	}

	public static function SetMeta(){
		global $APPLICATION, $arSite, $arRegion;

		$PageH1 = $APPLICATION->GetTitle();
		$PageMetaTitleBrowser = $APPLICATION->GetPageProperty('title');
		$DirMetaTitleBrowser = $APPLICATION->GetDirProperty('title');
		$PageMetaDescription = $APPLICATION->GetPageProperty('description');
		$DirMetaDescription = $APPLICATION->GetDirProperty('description');

		$bShowSiteName = (Option::get(self::MODULE_ID, "HIDE_SITE_NAME_TITLE", "N") == "N");
		$site_name = $arSite['SITE_NAME'];
		if(!$bShowSiteName)
			$site_name = '';
		// set title
		if(!CSite::inDir(SITE_DIR.'index.php')){
			// var_dump($PageH1);
			if(!strlen($PageMetaTitleBrowser))
			{
				if(!strlen($DirMetaTitleBrowser)){
					$PageMetaTitleBrowser = $PageH1.((strlen($PageH1) && strlen($site_name)) ? ' - ' : '' ).$site_name;
					$APPLICATION->SetPageProperty('title', $PageMetaTitleBrowser);
				}
			}
			else
			{
				$PageMetaTitleBrowser .= (strlen($site_name) ? ' - ' : '' ).$site_name;
				$APPLICATION->SetPageProperty('title', $PageMetaTitleBrowser);
			}
		}
		else{
			if(!strlen($PageMetaTitleBrowser)){
				if(!strlen($DirMetaTitleBrowser)){
					$PageMetaTitleBrowser = $site_name.((strlen($site_name) && strlen($PageH1)) ? ' - ' : '' ).$PageH1;
					$APPLICATION->SetPageProperty('title', $PageMetaTitleBrowser);
				}
			}
		}

		// check Open Graph required meta properties
		$addr = (CMain::IsHTTPS() ? 'https' : 'http').'://'.$_SERVER['HTTP_HOST'];
		if(!strlen(self::$arMetaParams['og:title'])){
			self::$arMetaParams['og:title'] = $PageMetaTitleBrowser;
		}
		if(!strlen(self::$arMetaParams['og:type'])){
			self::$arMetaParams['og:type'] = 'website';
		}
		if(!strlen(self::$arMetaParams['og:image'])){
			self::$arMetaParams['og:image'] = SITE_DIR.'logo.png'; // site logo
		}
		if(!strlen(self::$arMetaParams['og:url'])){
			self::$arMetaParams['og:url'] = $_SERVER['REQUEST_URI'];
		}
		if(!strlen(self::$arMetaParams['og:description'])){
			self::$arMetaParams['og:description'] = (strlen($PageMetaDescription) ? $PageMetaDescription : $DirMetaDescription);
		}

		foreach(self::$arMetaParams as $metaName => $metaValue){
			if(strlen($metaValue = strip_tags($metaValue))){
				$metaValue = str_replace('//', '/', $metaValue);
				if($metaName === 'og:image' || $metaName === 'og:url')
					$metaValue = $addr.$metaValue;
				$APPLICATION->AddHeadString('<meta property="'.$metaName.'" content="'.$metaValue.'" />', true);
				if($metaName === 'og:image'){
					$APPLICATION->AddHeadString('<link rel="image_src" href="'.$metaValue.'"  />', true);
				}
			}
		}

		if($arRegion)
		{
			$arTagSeoMarks = array();
			foreach($arRegion as $key => $value)
			{
				if(strpos($key, 'PROPERTY_REGION_TAG') !== false && strpos($key, '_VALUE_ID') === false)
				{
					$tag_name = str_replace(array('PROPERTY_', '_VALUE'), '', $key);
					$arTagSeoMarks['#'.$tag_name.'#'] = $key;
				}
			}

			if($arTagSeoMarks)
				CAllcorp2Regionality::addSeoMarks($arTagSeoMarks);
		}

	}

	public static function PrepareItemProps($arProps){
		if(is_array($arProps) && $arProps)
		{
			foreach($arProps as $PCODE => $arProperty)
			{
				if(in_array($PCODE, array('PERIOD', 'TITLE_BUTTON', 'LINK_BUTTON', 'REDIRECT', 'LINK_PROJECTS', 'LINK_REVIEWS', 'DOCUMENTS', 'FORM_ORDER', 'FORM_QUESTION', 'PHOTOPOS', 'TASK_PROJECT', 'PHOTOS', 'LINK_COMPANY', 'GALLEY_BIG', 'LINK_SERVICES', 'LINK_GOODS', 'LINK_STAFF', 'LINK_SALE', 'LINK_FAQ', 'PRICE', 'PRICEOLD', 'LINK_NEWS', 'LINK_TIZERS', 'LINK_ARTICLES', 'LINK_STUDY', 'SEND_MESS')))
					unset($arProps[$PCODE]);
				elseif(!$arProperty["VALUE"])
					unset($arProps[$PCODE]);
			}
		}
		else
			$arProps = array();

		return $arProps;
	}

	public static function ShowCabinetLink($icon=true, $text=true, $class_icon='', $show_mess=false, $message=''){
		global $APPLICATION;
		static $cabinet_call;
		$iCalledID = ++$cabinet_call;

		$type_svg = '';
		if($class_icon)
		{
			$tmp = explode(' ', $class_icon);
			$type_svg = '_'.$tmp[0];
		}
		$userID = self::GetUserID();
		if(!$message)
			$message = GetMessage('CABINET_LINK');
		?>
		<?Bitrix\Main\Page\Frame::getInstance()->startDynamicWithID('cabinet-link'.$iCalledID);?>
		<!-- noindex -->
		<?
		if($userID)
		{
			global $USER;?>
			<a class="personal-link dark-color avt" title="<?=$message;?>" href="<?=SITE_DIR;?>cabinet/">
				<?if($icon)
				{
					echo  self::showIconSvg('cabinet', SITE_TEMPLATE_PATH.'/images/svg/User'.$type_svg.'_black.svg', $message, $class_icon);
				}
				if($text):?>
					<span class="wrap">
						<span class="name"><?=($USER->GetFirstName() ? $USER->GetFirstName() : $USER->GetEmail() );?></span>
						<?if($show_mess):?>
							<span class="title"><?=$message;?></span>
						<?endif;?>
					</span>
				<?endif;?>
			</a>
			<?$APPLICATION->IncludeComponent(
				"bitrix:menu",
				"cabinet_dropdown",
				Array(
					"COMPONENT_TEMPLATE" => "cabinet_dropdown",
					"MENU_CACHE_TIME" => "3600000",
					"MENU_CACHE_TYPE" => "A",
					"MENU_CACHE_USE_GROUPS" => "Y",
					"MENU_CACHE_GET_VARS" => array(
					),
					"DELAY" => "N",
					"MAX_LEVEL" => "4",
					"ALLOW_MULTI_SELECT" => "Y",
					"ROOT_MENU_TYPE" => "cabinet",
					"CHILD_MENU_TYPE" => "left",
					"USE_EXT" => "Y"
				)
			);?>
		<?}
		else
		{
			$url = ((isset($_GET['backurl']) && $_GET['backurl']) ? $_GET['backurl'] : $APPLICATION->GetCurUri());?>
			<a class="personal-link dark-color animate-load" data-event="jqm" title="<?=$message;?>" data-param-type="auth" data-param-backurl="<?=$url;?>" data-name="auth" href="<?=SITE_DIR;?>cabinet/">
				<?if($icon)
				{
					echo self::showIconSvg('cabinet', SITE_TEMPLATE_PATH.'/images/svg/Lock'.$type_svg.'_black.svg', $message, $class_icon);
				}
				if($text):?>
					<span class="wrap">
						<span class="name"><?=GetMessage('LOGIN');?></span>
						<?if($show_mess):?>
							<span class="title"><?=$message;?></span>
						<?endif;?>
					</span>
				<?endif;?>
			</a>
		<?}?>
		<!-- /noindex -->
		<?Bitrix\Main\Page\Frame::getInstance()->finishDynamicWithID('cabinet-link'.$iCalledID);?>
	<?}

	public static function ShowPrintLink($txt=''){
		$html = '';

		$arTheme = self::GetFrontParametrsValues(SITE_ID);
		if($arTheme['PRINT_BUTTON'] == 'Y')
		{
			if(!$txt)
				$txt = GetMessage('PRINT_LINK');
			$html = '<div class="print-link"><i class="icon"><svg id="Print.svg" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16"><path class="cls-1" d="M1553,287h-2v3h-8v-3h-2a2,2,0,0,1-2-2v-5a2,2,0,0,1,2-2h2v-4h8v4h2a2,2,0,0,1,2,2v5A2,2,0,0,1,1553,287Zm-8,1h4v-4h-4v4Zm4-12h-4v2h4v-2Zm4,4h-12v5h2v-3h8v3h2v-5Z" transform="translate(-1539 -274)"/></svg></i>';
			if($txt)
				$html .= '<span class="text">'.$txt.'</span>';
			$html .= '</div>';
		}
		return $html;
	}

	public static function ShowBasketLink($class_link='top-btn hover', $class_icon='', $txt='', $show_price = false, $always_show = false){
		static $basket_call;
		$iCalledID = ++$basket_call;

		$userID = self::GetUserID();
		$type_svg = ($class_icon ? '_'.$class_icon : '');

		$arTheme = self::GetFrontParametrsValues(SITE_ID);
		$arItems = ((isset($_SESSION[SITE_ID][$userID]['BASKET_ITEMS']) && is_array($_SESSION[SITE_ID][$userID]['BASKET_ITEMS']) && $_SESSION[SITE_ID][$userID]['BASKET_ITEMS']) ? $_SESSION[SITE_ID][$userID]['BASKET_ITEMS'] : array());
		$count = ($arItems ? count($arItems) : 0 );
		$allSumm = 0;
		if($arItems)
		{
			foreach($arItems as $arItem)
			{
				if(strlen(trim($arItem['PROPERTY_PRICE_VALUE'])))
					$allSumm += floatval(str_replace(' ', '', $arItem['PROPERTY_FILTER_PRICE_VALUE'])) * $arItem['QUANTITY'];
			}
		}
		$title_text = GetMessage("TITLE_BASKET", array("#SUMM#" => self::FormatSumm($allSumm, 1)));
		$summ_text = GetMessage("BASKET_SUMM", array("#SUMM#" => self::FormatSumm($allSumm, 1)));
		if((int)$count <= 0)
			$title_text = GetMessage("EMPTY_BASKET");
		?>
		<?Bitrix\Main\Page\Frame::getInstance()->startDynamicWithID('basket-link'.$iCalledID);?>
		<!-- noindex -->
		<?
		if($arTheme['ORDER_VIEW'] == 'Y' && (($arTheme['ORDER_BASKET_VIEW'] == 'HEADER' || $always_show == true) && (!self::IsBasketPage($arTheme['URL_BASKET_SECTION']) && !self::IsOrderPage($arTheme['URL_ORDER_SECTION']))))
		{?>
			<a rel="nofollow" title="<?=$title_text;?>" href="<?=$arTheme['URL_BASKET_SECTION'];?>" class="basket-link <?=$arTheme['ORDER_BASKET_VIEW'];?> <?=$class_link.' '.$class_icon.($count ? ' basket-count' : '');?>">
				<span class="js-basket-block">
					<?=self::showIconSvg('basket', SITE_TEMPLATE_PATH.'/images/svg/Basket'.$type_svg.'_black.svg', '', $class_icon);?>
					<?if($show_price):?>
						<span class="wrap">
					<?endif;?>
						<?if($txt):?>
							<span class="title dark_link"><?=$txt;?></span>
						<?endif;?>
						<?if($show_price):?>
							<span class="prices"><?=($allSumm ? $summ_text : GetMessage('EMPTY_BASKET'));?></span>
						<?endif;?>
					<?if($show_price):?>
						</span>
					<?endif;?>
					<span class="count"><?=$count;?></span>
				</span>
			</a>
		<?}?>
		<!-- /noindex -->
		<?Bitrix\Main\Page\Frame::getInstance()->finishDynamicWithID('basket-link'.$iCalledID);?>
	<?}

	public static function ShowMobileMenuCabinet(){
		global $APPLICATION, $arTheme;

		if($arTheme['CABINET']['VALUE'] === 'Y'):?>
			<?$APPLICATION->IncludeComponent(
				"bitrix:menu",
				"cabinet_mobile",
				Array(
					"COMPONENT_TEMPLATE" => "cabinet_mobile",
					"MENU_CACHE_TIME" => "3600000",
					"MENU_CACHE_TYPE" => "A",
					"MENU_CACHE_USE_GROUPS" => "Y",
					"MENU_CACHE_GET_VARS" => array(
					),
					"DELAY" => "N",
					"MAX_LEVEL" => "4",
					"ALLOW_MULTI_SELECT" => "Y",
					"ROOT_MENU_TYPE" => "cabinet",
					"CHILD_MENU_TYPE" => "left",
					"USE_EXT" => "Y"
				)
			);?>
		<?endif;
	}

	public static function ShowMobileMenuBasket(){
		global $arTheme;

		$basketUrl = trim($arTheme['ORDER_VIEW']['DEPENDENT_PARAMS']['URL_BASKET_SECTION']['VALUE']);
		$orderUrl = trim($arTheme['ORDER_VIEW']['DEPENDENT_PARAMS']['URL_ORDER_SECTION']['VALUE']);
		$bShowBasket = $arTheme['ORDER_VIEW']['VALUE'] === 'Y' && strlen($basketUrl) && (!CSite::inDir($basketUrl) && (strlen($orderUrl) ? !CSite::inDir($orderUrl) : true));
		$userID = CUser::GetID();
		$userID = $userID > 0 ? $userID : 0;
		$cntItems = isset($_SESSION[SITE_ID][$userID]['BASKET_ITEMS']) && is_array($_SESSION[SITE_ID][$userID]['BASKET_ITEMS']) ? count($_SESSION[SITE_ID][$userID]['BASKET_ITEMS']) : 0;

		if($bShowBasket):?>
			<div class="menu middle">
				<ul>
					<li class="counters">
						<a class="dark-color ready" href="<?=$basketUrl?>">
							<?=self::showIconSvg("basket", SITE_TEMPLATE_PATH."/images/svg/Basket_black.svg");?>
							<span><?=GetMessage('BASKET')?><span class="count<?=(!$cntItems ? ' empted' : '')?>"><?=$cntItems?></span></span>
						</a>
					</li>
				</ul>
			</div>
		<?endif;
	}

	public static function ShowMobileMenuContacts(){
		global $APPLICATION, $arRegion, $arTheme;
		$arBackParametrs = self::GetBackParametrsValues(SITE_ID);
		$iCountPhones = ($arRegion ? count($arRegion['PHONES']) : $arBackParametrs['HEADER_PHONES']);
		?>
		<?// show regions
			self::ShowMobileRegions();?>
			<?if($iCountPhones): // count of phones?>
			<?
			$phone = $arBackParametrs['HEADER_PHONES_array_PHONE_VALUE_0'];
			$href = 'tel:'.str_replace(array(' ', '-', '(', ')'), '', $phone);
			?>
			<div class="menu middle">
				<ul>
					<li>
						<a href="<?=$href?>" class="dark-color<?=($iCountPhones > 1 ? ' parent' : '')?>">
							<?=CAllcorp2::showIconSvg("phone", SITE_TEMPLATE_PATH."/images/svg/Phone_black.svg");?>
							<span><?=$phone?></span>
							<?if($iCountPhones > 1):?>
								<span class="arrow"><i class="svg svg_triangle_right"></i></span>
							<?endif;?>
						</a>
						<?if($iCountPhones > 1): // if more than one?>
							<ul class="dropdown">
								<li class="menu_back"><a href="" class="dark-color" rel="nofollow"><?=self::showIconSvg("arrow-back", SITE_TEMPLATE_PATH."/images/svg/Arrow_right_white.svg");?><?=GetMessage('ALLCORP2_T_MENU_BACK')?></a></li>
								<li class="menu_title"><?=GetMessage('ALLCORP2_T_MENU_CALLBACK')?></li>
								<?for($i = 0; $i < $iCountPhones; ++$i):?>
									<?
									$phone = ($arRegion ? $arRegion['PHONES'][$i] : $arBackParametrs['HEADER_PHONES_array_PHONE_VALUE_'.$i]);
									$href = 'tel:'.str_replace(array(' ', '-', '(', ')'), '', $phone);
									?>
									<li><a href="<?=$href?>" class="dark-color"><?=$phone?></a></li>
								<?endfor;?>
								<li><a class="dark-color" href="" rel="nofollow" data-event="jqm" data-param-id="<?=CAllcorp2::getFormID("aspro_allcorp2_callback");?>" data-name="callback"><?=GetMessage('S_CALLBACK')?></a></li>
							</ul>
						<?endif;?>
					</li>
				</ul>
			</div>
		<?endif;?>
		<div class="contacts">
			<div class="title"><?=GetMessage('ALLCORP2_T_MENU_CONTACTS_TITLE')?></div>
			<?self::showAddress('address');?>
			<?self::showEmail('email');?>
		</div>
		<?
	}

	public static function checkContentBlock($file, $prop = 'PROPERTY_ADDRESS_VALUE'){
		global $arRegion;
		if((CAllcorp2::checkContentFile($file) && !$arRegion) || ($arRegion && $arRegion[$prop]))
			return true;
		return false;
	}

	public static function ShowMobileRegions(){
		global $APPLICATION, $arRegion, $arRegions;

		if($arRegion):
			$type_regions = self::GetFrontParametrValue('REGIONALITY_TYPE');
			static $mregions_call;

			$iCalledID = ++$mregions_call;
			$arRegions = CAllcorp2Regionality::getRegions();
			$regionID = ($arRegion ? $arRegion['ID'] : '');
			$iCountRegions = count($arRegions);?>
			<?Bitrix\Main\Page\Frame::getInstance()->startDynamicWithID('mobile-region-block'.$iCalledID);?>
			<!-- noindex -->
			<div class="menu middle mobile_regions">
				<ul>
					<li>
						<?if(self::GetFrontParametrValue('REGIONALITY_SEARCH_ROW') != 'Y'):?>
							<a rel="nofollow" href="" class="dark-color<?=($iCountRegions > 1 ? ' parent' : '')?>">
						<?else:?>
							<a rel="nofollow" href="" class="js_city_chooser dark-color" data-event="jqm" data-name="city_chooser" data-param-url="<?=urlencode($APPLICATION->GetCurUri());?>" data-param-form_id="city_chooser">
						<?endif;?>
							<i class="svg inline  svg-inline-phone">
								<?=self::showIconSvg('', SITE_TEMPLATE_PATH.'/images/svg/region.svg');?>
							</i>
							<span><?=$arRegion['NAME'];?></span>
							<?if($iCountRegions > 1):?>
								<span class="arrow"><i class="svg svg_triangle_right"></i></span>
							<?endif;?>
						</a>
						<?if(self::GetFrontParametrValue('REGIONALITY_SEARCH_ROW') != 'Y'):?>
							<?if($iCountRegions > 1): // if more than one?>
								<?$host = (CMain::IsHTTPS() ? 'https://' : 'http://');
								$uri = $APPLICATION->GetCurUri();?>
								<ul class="dropdown">
									<li class="menu_back">
										<a href="" class="dark-color" rel="nofollow">
											<?=self::showIconSvg("arrow-back", SITE_TEMPLATE_PATH."/images/svg/Arrow_right_white.svg");?>
											<?=GetMessage('ALLCORP2_T_MENU_BACK')?>
										</a>
									</li>
									<li class="menu_title"><span class="title"><?=\Bitrix\Main\Localization\Loc::getMessage('ALLCORP2_T_MENU_REGIONS')?></span></li>
									<?foreach($arRegions as $arItem):?>
										<?$href = $uri;
										if($arItem['PROPERTY_MAIN_DOMAIN_VALUE'] && $type_regions == 'SUBDOMAIN')
											$href = $host.$arItem['PROPERTY_MAIN_DOMAIN_VALUE'].$uri;
										?>
										<li><a rel="nofollow" href="<?=$href?>" class="dark-color city_item" data-id="<?=$arItem['ID'];?>"><?=$arItem['NAME'];?></a></li>
									<?endforeach;?>
								</ul>
							<?endif;?>
						<?endif;?>
					</li>
				</ul>
			</div>
			<!-- /noindex -->
			<?Bitrix\Main\Page\Frame::getInstance()->finishDynamicWithID('mobile-region-block'.$iCalledID);?>
		<?endif;
	}

	public static function ShowTopDetailBanner($arResult, $arParams){
		$bg = ((isset($arResult['PROPERTIES']['BNR_TOP_BG']) && $arResult['PROPERTIES']['BNR_TOP_BG']['VALUE']) ? CFile::GetPath($arResult['PROPERTIES']['BNR_TOP_BG']['VALUE']) : SITE_TEMPLATE_PATH.'/images/top-bnr.jpg');
		$bShowBG = (isset($arResult['PROPERTIES']['BNR_TOP_IMG']) && $arResult['PROPERTIES']['BNR_TOP_IMG']['VALUE']);
		$title = ($arResult['IPROPERTY_VALUES'] && strlen($arResult['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE']) ? $arResult['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE'] : $arResult['NAME']);
		$text_color_style = ((isset($arResult['PROPERTIES']['CODE_TEXT']) && $arResult['PROPERTIES']['CODE_TEXT']['VALUE']) ? 'style="color:'.$arResult['PROPERTIES']['CODE_TEXT']['VALUE'].'"' : '');
		$bLanding = (isset($arResult['IS_LANDING']) && $arResult['IS_LANDING'] == 'Y');
		?>
		<div class="banners-content">
			<div class="maxwidth-banner" style="background: url(<?=$bg;?>) 50% 50% no-repeat;">
				<div class="row">
					<div class="maxwidth-theme">
						<div class="col-md-<?=($bShowBG ? 5 : 12);?> text animated delay06 duration08 item_block fadeInUp">
							<h1 <?=$text_color_style;?>><?=$title?></h1>
							<div class="intro-text" <?=$text_color_style;?>>
								<?if($bLanding):?>
									<p><?=$arResult['PROPERTIES']['ANONS']['VALUE'];?></p>
								<?else:?>
									<?if($arResult['PREVIEW_TEXT_TYPE'] == 'text'):?>
										<p><?=$arResult['FIELDS']['PREVIEW_TEXT'];?></p>
									<?else:?>
										<?=$arResult['FIELDS']['PREVIEW_TEXT'];?>
									<?endif;?>
								<?endif;?>
							</div>
							<p>
								<?if($bLanding):?>
									<?if($arResult['PROPERTIES']['BUTTON_TEXT']['VALUE']):?>
										<span>
											<span class="btn btn-default btn-lg scroll_btn"><?=$arResult['PROPERTIES']['BUTTON_TEXT']['VALUE'];?></span>
										</span>
									<?endif;?>
								<?else:?>
									<?if($arResult['DISPLAY_PROPERTIES']['FORM_ORDER']['VALUE_XML_ID'] == 'YES'):?>
										<span>
											<span class="btn btn-default animate-load" data-event="jqm" data-param-id="<?=($arParams["FORM_ID_ORDER_SERVISE"] ? $arParams["FORM_ID_ORDER_SERVISE"] : self::getFormID("aspro_allcorp2_order_services"));?>" data-name="order_services" data-autoload-service="<?=self::formatJsName($arResult['NAME'])?>" data-autoload-study="<?=self::formatJsName($arResult['NAME'])?>" data-autoload-project="<?=self::formatJsName($arResult['NAME'])?>" data-autoload-product="<?=self::formatJsName($arResult['NAME'])?>"><span><?=(strlen($arParams['S_ORDER_SERVISE']) ? $arParams['S_ORDER_SERVISE'] : \Bitrix\Main\Localization\Loc::getMessage('S_ORDER_SERVISE'))?></span></span>
										</span>
									<?endif;?>

									<?if($arResult['DISPLAY_PROPERTIES']['FORM_QUESTION']['VALUE_XML_ID'] == 'YES'):?>
										<span>
											<span class="btn btn-default white2 animate-load" data-event="jqm" data-param-id="<?=self::getFormID("aspro_allcorp2_question");?>" data-autoload-need_product="<?=self::formatJsName($arResult['NAME'])?>" data-name="question"><span><?=(strlen($arParams['S_ASK_QUESTION']) ? $arParams['S_ASK_QUESTION'] : \Bitrix\Main\Localization\Loc::getMessage('S_ASK_QUESTION'))?></span></span>
										</span>
									<?endif;?>
								<?endif;?>
							</p>
						</div>
						<?if($bShowBG):?>
							<div class="col-md-7 hidden-xs hidden-sm img animated delay09 duration08 item_block fadeInUp">
								<div class="inner">
									<img src="<?=CFile::GetPath($arResult['PROPERTIES']['BNR_TOP_IMG']['VALUE']);?>" alt="<?=$title;?>" title="<?=$title;?>" draggable="false">
								</div>
							</div>
						<?endif;?>
					</div>
				</div>
			</div>
		</div>
	<?}

	public static function formatJsName($name = ''){
		$name = str_replace('\\', '%99', $name); // replace symbol \
		return htmlspecialcharsbx($name);
	}

	public static function GetUserID(){
		static $userID;
		if($userID === NULL)
		{
			global $USER;
			$userID = CUser::GetID();
			$userID = ($userID > 0 ? $userID : 0);
		}
		return $userID;
	}

	public static function CheckAdditionalChainInMultiLevel(&$arResult, &$arParams, &$arElement){
		global $APPLICATION;
		$APPLICATION->arAdditionalChain = false;
		if($arParams['INCLUDE_IBLOCK_INTO_CHAIN'] == 'Y' && isset(CCache::$arIBlocksInfo[$arParams['IBLOCK_ID']]['NAME']))
			$APPLICATION->AddChainItem(CCache::$arIBlocksInfo[$arParams['IBLOCK_ID']]['NAME'], $arElement['~LIST_PAGE_URL']);

		if($arParams['ADD_SECTIONS_CHAIN'] == 'Y')
		{
			if($arSection = CCache::CIBlockSection_GetList(array('CACHE' => array('TAG' => CCache::GetIBlockCacheTag($arElement['IBLOCK_ID']), 'MULTI' => 'N')), self::GetCurrentSectionFilter($arResult['VARIABLES'], $arParams), false, array('ID', 'NAME')))
			{
				$rsPath = CIBlockSection::GetNavChain($arParams['IBLOCK_ID'], $arSection['ID']);
				$rsPath->SetUrlTemplates('', $arParams['SECTION_URL']);
				while($arPath = $rsPath->GetNext())
				{
					$ipropValues = new \Bitrix\Iblock\InheritedProperty\SectionValues($arParams['IBLOCK_ID'], $arPath['ID']);
					$arPath['IPROPERTY_VALUES'] = $ipropValues->getValues();
					$arSection['PATH'][] = $arPath;
					$arSection['SECTION_URL'] = $arPath['~SECTION_PAGE_URL'];
				}

				foreach($arSection['PATH'] as $arPath)
				{
					if($arPath['IPROPERTY_VALUES']['SECTION_PAGE_TITLE'] != '')
						$APPLICATION->AddChainItem($arPath['IPROPERTY_VALUES']['SECTION_PAGE_TITLE'], $arPath['~SECTION_PAGE_URL']);
					else
						$APPLICATION->AddChainItem($arPath['NAME'], $arPath['~SECTION_PAGE_URL']);
				}
			}
		}
		if($arParams['ADD_ELEMENT_CHAIN'] == 'Y')
		{
			$ipropValues = new \Bitrix\Iblock\InheritedProperty\ElementValues($arParams['IBLOCK_ID'], $arElement['ID']);
			$arElement['IPROPERTY_VALUES'] = $ipropValues->getValues();
			if($arElement['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE'] != '')
				$APPLICATION->AddChainItem($arElement['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE']);
			else
				$APPLICATION->AddChainItem($arElement['NAME']);
		}
	}

	public static function CheckDetailPageUrlInMultilevel(&$arResult){
		if($arResult['ITEMS']){
			$arItemsIDs = $arItems = array();
			$CurrentSectionID = false;
			foreach($arResult['ITEMS'] as $arItem)
				$arItemsIDs[] = $arItem['ID'];

			$arItems = CCache::CIBLockElement_GetList(array('CACHE' => array('TAG' => CCache::GetIBlockCacheTag($arParams['IBLOCK_ID']), 'GROUP' => array('ID'), 'MULTI' => 'N')), array('ID' => $arItemsIDs), false, false, array('ID', 'IBLOCK_SECTION_ID', 'DETAIL_PAGE_URL'));
			if($arResult['SECTION']['PATH'])
			{
				for($i = count($arResult['SECTION']['PATH']) - 1; $i >= 0; --$i)
				{
					if(CSite::InDir($arResult['SECTION']['PATH'][$i]['SECTION_PAGE_URL']))
					{
						$CurrentSectionID = $arResult['SECTION']['PATH'][$i]['ID'];
						break;
					}
				}
			}
			foreach($arResult['ITEMS'] as $i => $arItem)
			{
				if(is_array($arItems[$arItem['ID']]['DETAIL_PAGE_URL']))
				{
					if($arItems[$arItem['ID']]['DETAIL_PAGE_URL'][$CurrentSectionID])
						$arResult['ITEMS'][$i]['DETAIL_PAGE_URL'] = $arItems[$arItem['ID']]['DETAIL_PAGE_URL'][$CurrentSectionID];
				}
				if(is_array($arItems[$arItem['ID']]['IBLOCK_SECTION_ID']))
					$arResult['ITEMS'][$i]['IBLOCK_SECTION_ID'] = $CurrentSectionID;
			}
		}
	}

	public static function unique_multidim_array($array, $key) {
	    $temp_array = array();
	    $i = 0;
	    $key_array = array();

	    foreach($array as $val) {
	        if (!in_array($val[$key], $key_array)) {
	            $key_array[$i] = $val[$key];
	            $temp_array[$i] = $val;
	        }
	        $i++;
	    }
	    return $temp_array;
	}

	public static function Start($siteID = 's1'){
		global $APPLICATION, $arRegion;
		if(CModule::IncludeModuleEx(self::MODULE_ID) == 1)
		{
			if(!defined('ASPRO_USE_ONENDBUFFERCONTENT_HANDLER')){
				define('ASPRO_USE_ONENDBUFFERCONTENT_HANDLER', 'Y');
			}

			$APPLICATION->SetPageProperty("viewport", "initial-scale=1.0, width=device-width");
			$APPLICATION->SetPageProperty("HandheldFriendly", "true");
			$APPLICATION->SetPageProperty("apple-mobile-web-app-capable", "yes");
			$APPLICATION->SetPageProperty("apple-mobile-web-app-status-bar-style", "black");
			$APPLICATION->SetPageProperty("SKYPE_TOOLBAR", "SKYPE_TOOLBAR_PARSER_COMPATIBLE");

			$bIndexBot = (isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) && strpos($_SERVER['HTTP_USER_AGENT'], 'Lighthouse') !== false); // is indexed yandex/google bot

			if(!$bIndexBot)
			{
				self::UpdateFrontParametrsValues(); //update theme values
				
				if(!defined('NOT_GENERATE_THEME') && (!isset($_SERVER["HTTP_X_REQUESTED_WITH"]) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != "xmlhttprequest"))
					self::GenerateThemes($siteID); //generate theme.css and bgtheme.css
			}

			$arTheme = self::GetFrontParametrsValues($siteID); //get site options

			if(!$arTheme['FONT_STYLE'] || !self::$arParametrsList['MAIN']['OPTIONS']['FONT_STYLE']['LIST'][$arTheme['FONT_STYLE']])
				$font_family = 'Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,500,600,700,800&subset=latin,cyrillic-ext';
			else
				$font_family = self::$arParametrsList['MAIN']['OPTIONS']['FONT_STYLE']['LIST'][$arTheme['FONT_STYLE']]['LINK'];

			if(!$bIndexBot)
			{
				if(!$arTheme['CUSTOM_FONT'])
					$APPLICATION->SetAdditionalCSS((CMain::IsHTTPS() ? 'https' : 'http').'://fonts.googleapis.com/css?family='.$font_family);
				else
					$APPLICATION->AddHeadString('<'.$arTheme['CUSTOM_FONT'].'>');
			}

			if($arTheme['USE_REGIONALITY'] == 'Y')
				$arRegion = CAllcorp2Regionality::getCurrentRegion(); //get current region from regionality module

			$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH.'/css/bootstrap.css');
			$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH.'/css/fonts/font-awesome/css/font-awesome.min.css');

			if(!$bIndexBot)
			{
				$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH.'/vendor/flexslider/flexslider.css');
				$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH.'/css/jquery.fancybox.css');
				$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH.'/css/theme-elements.css');
				$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH.'/css/jqModal.css');
			}

			$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH.'/css/theme-responsive.css');

			if(!$bIndexBot)
			{
				$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH.'/css/print.css');
				$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH.'/css/animation/animate.min.css');
				$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH.'/css/animation/animation_ext.css');
			}

			if ($arTheme['H1_STYLE']=='2') // 2 - Normal
				$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH.'/css/h1-normal.css');
			elseif(1) // 1 - Bold
				$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH.'/css/h1-bold.css');

			if(!$bIndexBot)
			{
				$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/jquery.actual.min.js');
				$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/jquery.fancybox.js');
				$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/vendor/jquery.easing.js');
				$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/vendor/jquery.appear.js');
				$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/vendor/jquery.cookie.js');
				$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/vendor/bootstrap.js');
				$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/vendor/flexslider/jquery.flexslider.js');
				$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/vendor/jquery.validate.min.js');
				$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/jquery.uniform.min.js');
				$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/jquery-ui.min.js');
				$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/jqModal.js');
				$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/jquery.inputmask.bundle.min.js', true);
				$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/detectmobilebrowser.js');
				$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/matchMedia.js');
				
				if(self::IsMainPage())
				{
					$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/jquery.waypoints.min.js');
					$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/jquery.counterup.js');
				}
				
				$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/jquery.alphanumeric.js');
				$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/jquery.autocomplete.js');
				// $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/jquery.mCustomScrollbar.js');
				$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/jquery.mobile.custom.touch.min.js');
				$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/general.js');
				$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/custom.js');
			}

			if(strlen($arTheme['FAVICON_IMAGE']))
				$APPLICATION->AddHeadString('<link rel="shortcut icon" href="'.$arTheme['FAVICON_IMAGE'].'" type="image/x-icon" />', true);
			
			if(strlen($arTheme['APPLE_TOUCH_ICON_IMAGE']))
				$APPLICATION->AddHeadString('<link rel="apple-touch-icon" sizes="180x180" href="'.$arTheme['APPLE_TOUCH_ICON_IMAGE'].'" />', true);

			CJSCore::Init(array('jquery2'));
			if(!$bIndexBot)
				CAjax::Init();

			self::showBgImage($siteID, $arTheme);
		}
		else
		{
			$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH.'/css/styles.css');
			$APPLICATION->SetTitle(GetMessage("ERROR_INCLUDE_MODULE"));
			$APPLICATION->IncludeFile(SITE_DIR."include/error_include_module.php", Array(), Array()); die();
		}
	}

	public static function ShowPageProps($prop){
		/** @global CMain $APPLICATION */
		global $APPLICATION;
		$APPLICATION->AddBufferContent(array("CAllcorp2", "GetPageProps"), $prop);
	}

	public static function GetPageProps($prop){
		/** @global CMain $APPLICATION */
		global $APPLICATION;

		if($prop == 'ERROR_404')
		{
			return (defined($prop) ? 'with_error' : '');
		}
		else
		{
			$val = $APPLICATION->GetProperty($prop);
			if(!empty($val))
				return $val;
		}
		return '';
	}

	public static function CopyFaviconToSiteDir($arValue, $siteID = ''){
		if(($siteID)){
			if(!is_array($arValue))
				$arValue=unserialize($arValue);
			if($arValue[0]){
				$imageSrc = $_SERVER['DOCUMENT_ROOT'].CFile::GetPath($arValue[0]);
			}
			else{
				if($arTemplate = self::GetSiteTemplate($siteID)){
					$imageSrc = str_replace('//', '/', $arTemplate['PATH'].'/images/favicon.ico');
				}
			}
			$arSite = CSite::GetByID($siteID)->Fetch();

			@unlink($imageDest = $arSite['ABS_DOC_ROOT'].'/'.$arSite['DIR'].'/favicon.ico');
			if(file_exists($imageSrc)){
				@copy($imageSrc, $arSite['ABS_DOC_ROOT'].'/'.$arSite['DIR'].'/favicon.ico');
			}else{
				@copy($arSite['ABS_DOC_ROOT'].'/'.$arSite['DIR'].'/include/favicon.ico', $arSite['ABS_DOC_ROOT'].'/'.$arSite['DIR'].'/favicon.ico');
			}
		}
	}

	public static function GetSiteTemplate($siteID = ''){
		$arTemplate = array();

		if(strlen($siteID)){
			$dbRes = CSite::GetTemplateList($siteID);
			while($arTemplate = $dbRes->Fetch()){
				if(!strlen($arTemplate['CONDITION'])){
					if(file_exists(($arTemplate['PATH'] = $_SERVER['DOCUMENT_ROOT'].'/bitrix/templates/'.$arTemplate['TEMPLATE']))){
						break;
					}
					elseif(file_exists(($arTemplate['PATH'] = $_SERVER['DOCUMENT_ROOT'].'/local/templates/'.$arTemplate['TEMPLATE']))){
						break;
					}
				}
			}
		}

		return $arTemplate;
	}

	public static function FormatSumm($strPrice, $quantity){
		$strSumm = '';

		if(strlen($strPrice = trim($strPrice))){
			$currency = '';
			$price = floatval(str_replace(' ', '', $strPrice));
			$summ = $price * $quantity;

			$strSumm = str_replace(trim(str_replace($currency, '', $strPrice)), str_replace('.00', '', number_format($summ, 2, '.', ' ')), $strPrice);
		}

		return $strSumm;
	}

	public static function ShowListRegions(){?>
		<?global $arTheme, $APPLICATION;
		static $list_regions_call;
		$iCalledID = ++$list_regions_call;?>
		<?$frame = new \Bitrix\Main\Page\FrameHelper('header-regionality-block'.$iCalledID);?>
		<?$frame->begin();?>
		<?$APPLICATION->IncludeComponent(
			"aspro:regionality.list.allcorp2",
			strtolower($arTheme["USE_REGIONALITY"]["DEPENDENT_PARAMS"]["REGIONALITY_VIEW"]["VALUE"]),
			Array(
				
			),false, array('HIDE_ICONS' => 'Y')
		);?>
		<?$frame->end();?>
	<?}

	public static function FormatPriceShema($strPrice = '', $bShowSchema = true){
		if(strlen($strPrice = trim($strPrice))){
			$arCur = array(
				'$' => 'USD',
				GetMessage('ALLCORP2_CUR_EUR1') => 'EUR',
				GetMessage('ALLCORP2_CUR_RUB1') => 'RUB',
				GetMessage('ALLCORP2_CUR_RUB2') => 'RUB',
				GetMessage('ALLCORP2_CUR_UAH1') => 'UAH',
				GetMessage('ALLCORP2_CUR_UAH2') => 'UAH',
				GetMessage('ALLCORP2_CUR_RUB3') => 'RUB',
				GetMessage('ALLCORP2_CUR_RUB4') => 'RUB',
				GetMessage('ALLCORP2_CUR_RUB5') => 'RUB',
				GetMessage('ALLCORP2_CUR_RUB6') => 'RUB',
				GetMessage('ALLCORP2_CUR_RUB3') => 'RUB',
				GetMessage('ALLCORP2_CUR_UAH3') => 'UAH',
				GetMessage('ALLCORP2_CUR_RUB5') => 'RUB',
				GetMessage('ALLCORP2_CUR_UAH6') => 'UAH',
			);
			foreach($arCur as $curStr => $curCode){
				if(strpos($strPrice, $curStr) !== false){
					$priceVal = str_replace($curStr, '', $strPrice);
					if($bShowSchema)
						return str_replace(array($curStr, $priceVal), array('<span class="currency" itemprop="priceCurrency" content="'.$curCode.'">'.$curStr.'</span>', '<span itemprop="price" content="'.$priceVal.'">'.$priceVal.'</span>'), $strPrice);
					else
						return str_replace(array($curStr, $priceVal), array('<span class="currency">'.$curStr.'</span>', '<span>'.$priceVal.'</span>'), $strPrice);
				}
			}
		}
		return $strPrice;
	}

	public static function GetBannerStyle($bannerwidth, $topmenu){
       /* $style = "";

        if($bannerwidth == "WIDE"){
            $style = ".maxwidth-banner{max-width: 1550px;}";
        }
        elseif($bannerwidth == "MIDDLE"){
            $style = ".maxwidth-banner{max-width: 1450px;}";
        }
        elseif($bannerwidth == "NARROW"){
            $style = ".maxwidth-banner{max-width: 1343px; padding: 0 16px;}";
			if($topmenu !== 'LIGHT'){
				$style .= ".banners-big{margin-top:20px;}";
			}
        }
        else{
            $style = ".maxwidth-banner{max-width: auto;}";
        }

        return "<style>".$style."</style>";*/
    }

    public static function GetIndexPageBlocks($pageAbsPath, $pageBlocksPrefix, $pageBlocksDirName = 'page_blocks'){
    	$arResult = array();

    	if($pageAbsPath && $pageBlocksPrefix){
    		$pageAbsPath = str_replace('//', '//', $pageAbsPath).'/';
    		if(is_dir($pageBlocksAbsPath = str_replace('', '', $pageAbsPath.(strlen($pageBlocksDirName) ? $pageBlocksDirName : '')))){
    			if($arPageBlocks = glob($pageBlocksAbsPath.'/*.php')){
		    		foreach($arPageBlocks as $file){
						$file = str_replace('.php', '', basename($file));
						if(strpos($file, $pageBlocksPrefix) !== false){
							$arResult[$file] = $file;
						}
					}
    			}
    		}
    	}

    	return $arResult;
    }

    public static function GetComponentTemplatePageBlocks($templateAbsPath, $pageBlocksDirName = 'page_blocks'){
    	$arResult = array('SECTIONS' => array(), 'SUBSECTIONS' => array(), 'ELEMENTS' => array(), 'ELEMENTS_TABLE' => array(), 'ELEMENTS_LIST' => array(), 'ELEMENTS_PRICE' => array(), 'ELEMENT' => array());

    	if($templateAbsPath){
    		$templateAbsPath = str_replace('//', '//', $templateAbsPath).'/';
    		if(is_dir($pageBlocksAbsPath = str_replace('//', '/', $templateAbsPath.(strlen($pageBlocksDirName) ? $pageBlocksDirName : '')))){
    			if($arPageBlocks = glob($pageBlocksAbsPath.'/*.php')){
		    		foreach($arPageBlocks as $file){
						$file = str_replace('.php', '', basename($file));
						if(strpos($file, 'sections_') !== false){
							$arResult['SECTIONS'][$file] = $file;
						}
						elseif(strpos($file, 'section_') !== false){
							$arResult['SUBSECTIONS'][$file] = $file;
						}
						elseif(strpos($file, 'list_elements_') !== false){
							$arResult['ELEMENTS'][$file] = $file;
						}
						elseif(strpos($file, 'catalog_table') !== false){
							$arResult['ELEMENTS_TABLE'][$file] = $file;
						}
						elseif(strpos($file, 'catalog_list') !== false){
							$arResult['ELEMENTS_LIST'][$file] = $file;
						}
						elseif(strpos($file, 'catalog_price') !== false){
							$arResult['ELEMENTS_PRICE'][$file] = $file;
						}
						elseif(strpos($file, 'element_') !== false){
							$arResult['ELEMENT'][$file] = $file;
						}
					}
    			}
    		}
    	}

    	return $arResult;
    }

    public static function GetComponentTemplatePageBlocksParams($arPageBlocks){
    	$arResult = array();

    	if($arPageBlocks && is_array($arPageBlocks)){
    		if(isset($arPageBlocks['SECTIONS']) && $arPageBlocks['SECTIONS'] && is_array($arPageBlocks['SECTIONS'])){
    			$arResult['SECTIONS_TYPE_VIEW'] = array(
					'PARENT' => 'BASE',
					'SORT' => 1,
					'NAME' => GetMessage('M_SECTIONS_TYPE_VIEW'),
					'TYPE' => 'LIST',
					'VALUES' => $arPageBlocks['SECTIONS'],
					'DEFAULT' => key($arPageBlocks['SECTIONS']),
				);
    		}
    		if(isset($arPageBlocks['SUBSECTIONS']) && $arPageBlocks['SUBSECTIONS'] && is_array($arPageBlocks['SUBSECTIONS'])){
    			$arResult['SECTION_TYPE_VIEW'] = array(
					'PARENT' => 'BASE',
					'SORT' => 1,
					'NAME' => GetMessage('M_SECTION_TYPE_VIEW'),
					'TYPE' => 'LIST',
					'VALUES' => $arPageBlocks['SUBSECTIONS'],
					'DEFAULT' => key($arPageBlocks['SUBSECTIONS']),
				);
    		}
    		if(isset($arPageBlocks['ELEMENTS']) && $arPageBlocks['ELEMENTS'] && is_array($arPageBlocks['ELEMENTS'])){
    			$arResult['SECTION_ELEMENTS_TYPE_VIEW'] = array(
					'PARENT' => 'BASE',
					'SORT' => 1,
					'NAME' => GetMessage('M_SECTION_ELEMENTS_TYPE_VIEW'),
					'TYPE' => 'LIST',
					'VALUES' => $arPageBlocks['ELEMENTS'],
					'DEFAULT' => key($arPageBlocks['ELEMENTS']),
				);
    		}
    		if(isset($arPageBlocks['ELEMENTS_PRICE']) && $arPageBlocks['ELEMENTS_PRICE'] && is_array($arPageBlocks['ELEMENTS_PRICE'])){
    			$arResult['ELEMENTS_PRICE_TYPE_VIEW'] = array(
					'PARENT' => 'BASE',
					'SORT' => 1,
					'NAME' => GetMessage('M_ELEMENTS_PRICE_TYPE_VIEW'),
					'TYPE' => 'LIST',
					'VALUES' => $arPageBlocks['ELEMENTS_PRICE'],
					'DEFAULT' => key($arPageBlocks['ELEMENTS_PRICE']),
				);
    		}
    		if(isset($arPageBlocks['ELEMENTS_LIST']) && $arPageBlocks['ELEMENTS_LIST'] && is_array($arPageBlocks['ELEMENTS_LIST'])){
    			$arResult['ELEMENTS_LIST_TYPE_VIEW'] = array(
					'PARENT' => 'BASE',
					'SORT' => 1,
					'NAME' => GetMessage('M_ELEMENTS_LIST_TYPE_VIEW'),
					'TYPE' => 'LIST',
					'VALUES' => $arPageBlocks['ELEMENTS_LIST'],
					'DEFAULT' => key($arPageBlocks['ELEMENTS_LIST']),
				);
    		}
    		if(isset($arPageBlocks['ELEMENTS_TABLE']) && $arPageBlocks['ELEMENTS_TABLE'] && is_array($arPageBlocks['ELEMENTS_TABLE'])){
    			$arResult['ELEMENTS_TABLE_TYPE_VIEW'] = array(
					'PARENT' => 'BASE',
					'SORT' => 1,
					'NAME' => GetMessage('M_ELEMENTS_TABLE_TYPE_VIEW'),
					'TYPE' => 'LIST',
					'VALUES' => $arPageBlocks['ELEMENTS_TABLE'],
					'DEFAULT' => key($arPageBlocks['ELEMENTS_TABLE']),
				);
    		}
    		if(isset($arPageBlocks['ELEMENT']) && $arPageBlocks['ELEMENT'] && is_array($arPageBlocks['ELEMENT'])){
    			$arResult['ELEMENT_TYPE_VIEW'] = array(
					'PARENT' => 'BASE',
					'SORT' => 1,
					'NAME' => GetMessage('M_ELEMENT_TYPE_VIEW'),
					'TYPE' => 'LIST',
					'VALUES' => $arPageBlocks['ELEMENT'],
					'DEFAULT' => key($arPageBlocks['ELEMENT']),
				);
    		}
    	}

    	return $arResult;
    }

   protected function IsComponentTemplateHasModuleElementsPageBlocksParam($templateName, $arExtParams = array()){
    	$section_param = ((isset($arExtParams['SECTION']) && $arExtParams['SECTION']) ? $arExtParams['SECTION'] : 'SECTION');
    	$template_param = ((isset($arExtParams['OPTION']) && $arExtParams['OPTION']) ? $arExtParams['OPTION'] : strtoupper($templateName));
	    return $templateName && isset(self::$arParametrsList[$section_param]['OPTIONS'][$template_param.'_PAGE']);
    }

    protected function IsComponentTemplateHasModuleElementPageBlocksParam($templateName, $arExtParams = array()){
    	$section_param = ((isset($arExtParams['SECTION']) && $arExtParams['SECTION']) ? $arExtParams['SECTION'] : 'SECTION');
    	$template_param = ((isset($arExtParams['OPTION']) && $arExtParams['OPTION']) ? $arExtParams['OPTION'] : strtoupper($templateName));
	    return $templateName && isset(self::$arParametrsList[$section_param]['OPTIONS'][$template_param.'_PAGE_DETAIL']);
    }

    protected function IsComponentTemplateHasModuleElementsTemplatePageBlocksParam($templateName, $arExtParams = array()){
    	$section_param = ((isset($arExtParams['SECTION']) && $arExtParams['SECTION']) ? $arExtParams['SECTION'] : 'SECTION');
    	$template_param = ((isset($arExtParams['OPTION']) && $arExtParams['OPTION']) ? $arExtParams['OPTION'] : strtoupper($templateName));
	    return $templateName && isset(self::$arParametrsList[$section_param]['OPTIONS'][$template_param]);
    }

    public static function AddComponentTemplateModulePageBlocksParams($templateAbsPath, &$arParams, $arExtParams = array(), $listParam = ''){
    	if($templateAbsPath && $arParams && is_array($arParams)){
    		$templateAbsPath = str_replace('//', '//', $templateAbsPath).'/';
    		$templateName = basename($templateAbsPath);
    		if(self::IsComponentTemplateHasModuleElementsPageBlocksParam($templateName, $arExtParams)){
    			$arParams['SECTION_ELEMENTS_TYPE_VIEW']['VALUES'] = array_merge(array('FROM_MODULE' => GetMessage('M_FROM_MODULE_PARAMS')), $arParams['SECTION_ELEMENTS_TYPE_VIEW']['VALUES']);
    			$arParams['SECTION_ELEMENTS_TYPE_VIEW']['DEFAULT'] = 'FROM_MODULE';
    		}
    		if(self::IsComponentTemplateHasModuleElementPageBlocksParam($templateName, $arExtParams)){
    			$arParams['ELEMENT_TYPE_VIEW']['VALUES'] = array_merge(array('FROM_MODULE' => GetMessage('M_FROM_MODULE_PARAMS')), $arParams['ELEMENT_TYPE_VIEW']['VALUES']);
    			$arParams['ELEMENT_TYPE_VIEW']['DEFAULT'] = 'FROM_MODULE';
    		}
    		if(self::IsComponentTemplateHasModuleElementsTemplatePageBlocksParam($templateName, $arExtParams)){
    			$param = $arExtParams['OPTION'];
    			if($listParam)
    				$param = $listParam;

    			$arParams[$param]['VALUES'] = array_merge(array('FROM_MODULE' => GetMessage('M_FROM_MODULE_PARAMS')), $arParams[$param]['VALUES']);
    			$arParams[$param]['DEFAULT'] = 'FROM_MODULE';
    		}
    	}
    }

    public static function CheckComponentTemplatePageBlocksParams(&$arParams, $templateAbsPath, $pageBlocksDirName = 'page_blocks'){
    	$arPageBlocks = self::GetComponentTemplatePageBlocks($templateAbsPath, $pageBlocksDirName);

    	if(!isset($arParams['SECTIONS_TYPE_VIEW']) || !$arParams['SECTIONS_TYPE_VIEW'] || (!isset($arPageBlocks['SECTIONS'][$arParams['SECTIONS_TYPE_VIEW']]) && $arParams['SECTIONS_TYPE_VIEW'] !== 'FROM_MODULE')){
    		$arParams['SECTIONS_TYPE_VIEW'] = key($arPageBlocks['SECTIONS']);
    	}
    	if(!isset($arParams['SECTION_TYPE_VIEW']) || !$arParams['SECTION_TYPE_VIEW'] || (!isset($arPageBlocks['SUBSECTIONS'][$arParams['SECTION_TYPE_VIEW']]) && $arParams['SECTION_TYPE_VIEW'] !== 'FROM_MODULE')){
    		$arParams['SECTION_TYPE_VIEW'] = key($arPageBlocks['SUBSECTIONS']);
    	}
    	if(!isset($arParams['SECTION_ELEMENTS_TYPE_VIEW']) || !$arParams['SECTION_ELEMENTS_TYPE_VIEW'] || (!isset($arPageBlocks['ELEMENTS'][$arParams['SECTION_ELEMENTS_TYPE_VIEW']]) && $arParams['SECTION_ELEMENTS_TYPE_VIEW'] !== 'FROM_MODULE')){
    		$arParams['SECTION_ELEMENTS_TYPE_VIEW'] = key($arPageBlocks['ELEMENTS']);
    	}
    	if(!isset($arParams['ELEMENTS_TABLE_TYPE_VIEW']) || !$arParams['ELEMENTS_TABLE_TYPE_VIEW'] || (!isset($arPageBlocks['ELEMENTS_TABLE'][$arParams['ELEMENTS_TABLE_TYPE_VIEW']]) && $arParams['ELEMENTS_TABLE_TYPE_VIEW'] !== 'FROM_MODULE')){
    		$arParams['ELEMENTS_TABLE_TYPE_VIEW'] = key($arPageBlocks['ELEMENTS_TABLE']);
    	}
    	if(!isset($arParams['ELEMENTS_LIST_TYPE_VIEW']) || !$arParams['ELEMENTS_LIST_TYPE_VIEW'] || (!isset($arPageBlocks['ELEMENTS_LIST'][$arParams['ELEMENTS_LIST_TYPE_VIEW']]) && $arParams['ELEMENTS_LIST_TYPE_VIEW'] !== 'FROM_MODULE')){
    		$arParams['ELEMENTS_LIST_TYPE_VIEW'] = key($arPageBlocks['ELEMENTS_LIST']);
    	}
    	if(!isset($arParams['ELEMENTS_PRICE_TYPE_VIEW']) || !$arParams['ELEMENTS_PRICE_TYPE_VIEW'] || (!isset($arPageBlocks['ELEMENTS_PRICE'][$arParams['ELEMENTS_PRICE_TYPE_VIEW']]) && $arParams['ELEMENTS_PRICE_TYPE_VIEW'] !== 'FROM_MODULE')){
    		$arParams['ELEMENTS_PRICE_TYPE_VIEW'] = key($arPageBlocks['ELEMENTS_PRICE']);
    	}
    	if(!isset($arParams['ELEMENT_TYPE_VIEW']) || !$arParams['ELEMENT_TYPE_VIEW'] || (!isset($arPageBlocks['ELEMENT'][$arParams['ELEMENT_TYPE_VIEW']]) && $arParams['ELEMENT_TYPE_VIEW'] !== 'FROM_MODULE')){
    		$arParams['ELEMENT_TYPE_VIEW'] = key($arPageBlocks['ELEMENT']);
    	}
    }

    public static function Add2OptionCustomComponentTemplatePageBlocks(&$arOption, $templateAbsPath){
		if($arOption && isset($arOption['LIST'])){
			if($arPageBlocks = self::GetComponentTemplatePageBlocks($templateAbsPath)){
				foreach($arPageBlocks['ELEMENTS'] as $page => $value){
					if(!isset($arOption['LIST'][$page])){
						$arOption['LIST'][$page] = array(
							'TITLE' => $value,
							'HIDE' => 'Y',
							'IS_CUSTOM' => 'Y',
						);
					}
				}
				if(!$arOption['DEFAULT'] && $arOption['LIST']){
					$arOption['DEFAULT'] = key($arOption['LIST']);
				}
			}
		}
    }

    public static function Add2OptionCustomComponentTemplatePageBlocksElement(&$arOption, $templateAbsPath, $field = 'ELEMENT'){
		if($arOption && isset($arOption['LIST'])){
			if($arPageBlocks = self::GetComponentTemplatePageBlocks($templateAbsPath)){

				foreach($arPageBlocks[$field] as $page => $value){
					if(!isset($arOption['LIST'][$page])){
						$arOption['LIST'][$page] = array(
							'TITLE' => $value,
							'HIDE' => 'Y',
							'IS_CUSTOM' => 'Y',
						);
					}
				}
				if(!$arOption['DEFAULT'] && $arOption['LIST']){
					$arOption['DEFAULT'] = key($arOption['LIST']);
				}
			}
		}
    }

    public static function formatProps($arItem){
    	$arProps = array();
    	foreach($arItem['DISPLAY_PROPERTIES'] as $code => $arProp)
    	{
    		if($arProp['VALUE'])
    		{
    			if(!in_array($arProp['CODE'], array('PERIOD', 'PHOTOS', 'PRICE', 'PRICEOLD', 'ARTICLE', 'STATUS', 'DOCUMENTS', 'LINK_GOODS', 'LINK_STAFF', 'LINK_REVIEWS', 'LINK_PROJECTS', 'LINK_SERVICES', 'FORM_ORDER', 'FORM_QUESTION', 'PHOTOPOS', 'FILTER_PRICE', 'SHOW_ON_INDEX_PAGE', 'BNR_TOP', 'BNR_TOP_IMG', 'BNR_TOP_BG', 'CODE_TEXT', 'HIT', 'VIDEO', 'VIDEO_IFRAME', 'GALLEY_BIG')) && ($arProp['PROPERTY_TYPE'] != 'E' && $arProp['PROPERTY_TYPE'] != 'G'))
    			{
    				if(is_array($arProp['DISPLAY_VALUE']))
    					$arProp['VALUE'] = implode(', ', $arProp['DISPLAY_VALUE']);
    				$arProps[$code] = $arProp;
    			}
    		}
    	}
    	return $arProps;
    }

    public static function FormatNewsUrl($arItem){
    	$url = $arItem['DETAIL_PAGE_URL'];
    	if(strlen($arItem['DISPLAY_PROPERTIES']['REDIRECT']['VALUE']))
		{
			$url = $arItem['DISPLAY_PROPERTIES']['REDIRECT']['VALUE'];
			return $url;
		}
    	if($arItem['ACTIVE_FROM'])
    	{
    		if($arDateTime = ParseDateTime($arItem['ACTIVE_FROM'], FORMAT_DATETIME))
    		{
		        $url = str_replace("#YEAR#", $arDateTime['YYYY'], $arItem['DETAIL_PAGE_URL']);
		        return $url;
    		}
    	}
    	return $url;
    }

    public static function GetItemsYear($arParams){
    	$arResult = array();
    	$arItems = CCache::CIBLockElement_GetList(array('SORT' => 'ASC', 'NAME' => 'ASC', 'CACHE' => array('TAG' => CCache::GetIBlockCacheTag($arParams['IBLOCK_ID']))), array('IBLOCK_ID' => $arParams['IBLOCK_ID'], 'ACTIVE' => 'Y'), false, false, array('ID', 'NAME', 'ACTIVE_FROM'));
		if($arItems)
		{
			foreach($arItems as $arItem)
			{
				if($arItem['ACTIVE_FROM'])
				{
					if($arDateTime = ParseDateTime($arItem['ACTIVE_FROM'], FORMAT_DATETIME))
						$arResult[$arDateTime['YYYY']] = $arDateTime['YYYY'];
				}
			}
		}
		return $arResult;
    }

	public static function GetDirMenuParametrs($dir){
		if(strlen($dir))
		{
			$file = str_replace('//', '/', $dir.'/.section.php');
			if(file_exists($file)){
				@include($file);
				return $arDirProperties;
			}
		}

		return false;
	}

	public static function IsMainPage(){
		static $result;

		if(!isset($result))
			$result = CSite::InDir(SITE_DIR.'index.php');

		return $result;
	}

	public static function IsBasketPage($url_link = ''){
		static $result;

		if(!isset($result))
		{
			if(!$url_link)
			{
				$arOptions = self::GetBackParametrsValues(SITE_ID);
				if(!strlen($arOptions["URL_BASKET_SECTION"]))
					$arOptions["URL_BASKET_SECTION"] = SITE_DIR."cart/";
				$url_link = $arOptions["URL_BASKET_SECTION"];
			}
			$result = CSite::InDir($url_link);
		}

		return $result;
	}

	public static function IsOrderPage($url_link = ''){
		static $result;

		if(!isset($result))
		{
			if(!$url_link)
			{
				$arOptions = self::GetBackParametrsValues(SITE_ID);
				if(!strlen($arOptions["URL_ORDER_SECTION"]))
					$arOptions["URL_ORDER_SECTION"] = SITE_DIR."cart/order/";
				$url_link = $arOptions["URL_ORDER_SECTION"];
			}
			$result = CSite::InDir($url_link);
		}

		return $result;
	}

	public static function getConditionClass(){
		global $APPLICATION;

		$class = $APPLICATION->AddBufferContent(array('CAllcorp2', 'showPageClass'));

		if($APPLICATION->GetProperty('MENU') === 'N')
			$class = ' hide_menu_page';
		if($APPLICATION->GetProperty('HIDETITLE') === 'Y')
			$class .= ' hide_title_page';
		if($APPLICATION->GetProperty('FULLWIDTH') === 'Y')
			$class .= ' wide_page';

		$arSiteThemeOptions = self::GetFrontParametrsValues(SITE_ID);
		$class .= ' header_fill_'.strtolower($arSiteThemeOptions['MENU_COLOR']);
		$class .= ' side_'.strtolower($arSiteThemeOptions['SIDE_MENU']);
		$class .= ' all_title_'.strtolower($arSiteThemeOptions['H1_STYLE']);

		global $showBgBanner;
		if($showBgBanner)
			$class .= ' visible_banner';

		return $class;
	}

	public static function showPageClass(){
		global $bannerTemplate, $bWideImg;

		$class = $bannerTemplate;
		$class .= (strpos($bannerTemplate, 'big-long') !== false ? ' header_opacity' : ' header_nopacity');
		$class .= ((strpos($bannerTemplate, 'big-short_mix') !== false || strpos($bannerTemplate, 'big-short_small') !== false) ? ' banner_offset' : '');

		if($bWideImg)
			$class .= ' with_custom_img';

		return $class;
	}

	public static function goto404Page(){
		global $APPLICATION;

		if($_SESSION['SESS_INCLUDE_AREAS']){
			echo '</div>';
		}
		echo '</div>';
		$APPLICATION->IncludeFile(SITE_DIR.'404.php', array(), array('MODE' => 'html'));
		die();
	}

	public static function checkRestartBuffer(){
		global $APPLICATION;
		static $bRestarted;

		if($bRestarted)
			die();


		if((isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == "xmlhttprequest") || (strtolower($_REQUEST['ajax']) == 'y'))
		{
			$APPLICATION->RestartBuffer();
			$bRestarted = true;
		}
	}

	public static function UpdateFormEvent(&$arFields){
		if($arFields['ID'] && $arFields['IBLOCK_ID'])
		{
			// find aspro form event for this iblock
			$arEventIDs = array('ASPRO_SEND_FORM_'.$arFields['IBLOCK_ID'], 'ASPRO_SEND_FORM_ADMIN_'.$arFields['IBLOCK_ID']);
			$arLangIDs = array('ru', 'en');
			static $arEvents;
			if($arEvents == NULL)
			{
				foreach($arEventIDs as $EVENT_ID)
				{
					foreach($arLangIDs as $LANG_ID)
					{
						$resEvents = CEventType::GetByID($EVENT_ID, $LANG_ID);
						$arEvents[$EVENT_ID][$LANG_ID] = $resEvents->Fetch();
					}
				}
			}
			if($arEventIDs)
			{
				foreach($arEventIDs as $EVENT_ID)
				{
					foreach($arLangIDs as $LANG_ID)
					{
						if($arEvent = &$arEvents[$EVENT_ID][$LANG_ID])
						{
							if(strpos($arEvent['DESCRIPTION'], $arFields['NAME'].': #'.$arFields['CODE'].'#') === false){
								$arEvent['DESCRIPTION'] = str_replace('#'.$arFields['CODE'].'#', '-', $arEvent['DESCRIPTION']);
								$arEvent['DESCRIPTION'] .= $arFields['NAME'].': #'.$arFields['CODE']."#\n";
								CEventType::Update(array('ID' => $arEvent['ID']), $arEvent);
							}
						}
					}
				}
			}
		}
	}

	public static function ShowHeaderPhones($class = '', $icon = ''){
		global $arRegion;
		$iCalledID = ++$hphones_call;
		$arBackParametrs = self::GetBackParametrsValues(SITE_ID);
		$iCountPhones = ($arRegion ? count($arRegion['PHONES']) : $arBackParametrs['HEADER_PHONES']);
		?>
		<?if($arRegion):?>
			<?$frame = new \Bitrix\Main\Page\FrameHelper('header-allphones-block'.$iCalledID);?>
			<?$frame->begin();?>
		<?endif;?>

		<?if($iCountPhones): // count of phones?>
			<?
			$phone = ($arRegion ? $arRegion['PHONES'][0] : $arBackParametrs['HEADER_PHONES_array_PHONE_VALUE_0']);
			$href = 'tel:'.str_replace(array(' ', '-', '(', ')'), '', $phone);
			$bDropDownPhones = ((int)$iCountPhones > 1);
			?>
			<div class="phone<?=($bDropDownPhones ? ' with_dropdown' : '')?><?=($class ? ' '.$class : '')?>">
				<?$phone_icon = ($icon ? $icon : 'Phone_sm.svg');?>
				<?=self::showIconSvg("phone colored", SITE_TEMPLATE_PATH."/images/svg/".$phone_icon);?>
				
				<a href="<?=$href?>"><?=$phone?></a>
				<?if($iCountPhones > 1): // if more than one?>
					<div class="dropdown">
						<div class="wrap">
							<?for($i = 1; $i < $iCountPhones; ++$i):?>
								<?
								$phone = ($arRegion ? $arRegion['PHONES'][$i] : $arBackParametrs['HEADER_PHONES_array_PHONE_VALUE_'.$i]);
								$href = 'tel:'.str_replace(array(' ', '-', '(', ')'), '', $phone);
								?>
								<div class="more_phone"><a href="<?=$href?>"><?=$phone?></a></div>
							<?endfor;?>
						</div>
					</div>
					<?=self::showIconSvg("", SITE_TEMPLATE_PATH."/images/svg/more_arrow.svg", "", "", false);?>
				<?endif;?>
			</div>
		<?endif;?>
		<?if($arRegion):?>
			<?$frame->end();?>
		<?endif;?>
		<?
	}

	public static function showContactPhones($txt = '', $wrapTable = true, $class = '', $icon = 'Phone_black.svg'){
		global $arRegion, $APPLICATION;
		$iCalledID = ++$cphones_call;
		$bRegionContact = (\Bitrix\Main\Config\Option::get(self::MODULE_ID, 'SHOW_REGION_CONTACT', 'N') == 'Y');
		$iCountPhones = ($arRegion && $bRegionContact ? count($arRegion['PHONES']) : self::checkContentFile(SITE_DIR.'include/contacts-site-phone-one.php'));
		?>
		<?if($arRegion):?>
			<?$frame = new \Bitrix\Main\Page\FrameHelper('header-allcphones-block'.$iCalledID);?>
			<?$frame->begin();?>
		<?endif;?>

		<?if($iCountPhones): // count of phones?>
			<?if($wrapTable):?>
				<table>
			<?endif;?>
				<tr>
					<td class="icon"><i class="fa big-icon s45"><?=self::showIconSvg("phone", SITE_TEMPLATE_PATH."/images/svg/".$icon);?></i></td>
					<td>
						<span class="dark_table"><?=($txt ? $txt : Loc::getMessage('SPRAVKA'));?></span>
						<?if($arRegion && $bRegionContact):?>
							<div class="<?=($class ? ' '.$class : '')?>">
								<?for($i = 0; $i < $iCountPhones; ++$i):?>
									<?
									$phone = $arRegion['PHONES'][$i];
									$href = 'tel:'.str_replace(array(' ', '-', '(', ')'), '', $phone);
									?>
									<div itemprop="telephone"><a href="<?=$href?>"><?=$phone?></a></div>
								<?endfor;?>
							</div>
						<?else:?>
							<div itemprop="telephone"><?$APPLICATION->IncludeFile(SITE_DIR."include/contacts-site-phone-one.php", Array(), Array("MODE" => "html", "NAME" => "Phone"));?></div>
						<?endif;?>
					</td>
				</tr>
			<?if($wrapTable):?>
				</table>
			<?endif;?>
		<?endif;?>
		<?if($arRegion):?>
			<?$frame->end();?>
		<?endif;?>
		<?
	}

	public static function showContactEmail($txt = '', $wrapTable = true, $class = '', $icon = 'Email.svg'){
		global $arRegion, $APPLICATION;
		$iCalledID = ++$cemail_call;
		$bRegionContact = (\Bitrix\Main\Config\Option::get(self::MODULE_ID, 'SHOW_REGION_CONTACT', 'N') == 'Y');
		$bEmail = ($arRegion && $bRegionContact ? $arRegion['PROPERTY_EMAIL_VALUE'] : self::checkContentFile(SITE_DIR.'include/contacts-site-email.php'));
		?>
		<?if($arRegion):?>
			<?$frame = new \Bitrix\Main\Page\FrameHelper('header-allcemail-block'.$iCalledID);?>
			<?$frame->begin();?>
		<?endif;?>
		<?if($bEmail): // count of phones?>
			<?if($wrapTable):?>
				<table>
			<?endif;?>
				<tr>
					<td class="icon"><i class="fa big-icon s45"><?=self::showIconSvg("email", SITE_TEMPLATE_PATH."/images/svg/".$icon);?></i></td>
					<td>
						<span class="dark_table"><?=($txt ? $txt : Loc::getMessage('SPRAVKA'));?></span>
						<?if($arRegion && $bRegionContact):?>
							<div class="<?=($class ? ' '.$class : '')?>">
								<?foreach($arRegion['PROPERTY_EMAIL_VALUE'] as $value):?>
									<div itemprop="email">
										<a href="mailto:<?=$value;?>"><?=$value;?></a>
									</div>
								<?endforeach;?>
							</div>
						<?else:?>
							<div itemprop="email"><?$APPLICATION->IncludeFile(SITE_DIR."include/contacts-site-email.php", Array(), Array("MODE" => "html", "NAME" => "email"));?></div>
						<?endif;?>
					</td>
				</tr>
			<?if($wrapTable):?>
				</table>
			<?endif;?>
		<?endif;?>
		<?if($arRegion):?>
			<?$frame->end();?>
		<?endif;?>
		<?
	}

	public static function showContactAddr($txt = '', $wrapTable = true, $class = '', $icon = 'Addres_black.svg'){
		global $arRegion, $APPLICATION;
		$iCalledID = ++$caddr_call;
		$bRegionContact = (\Bitrix\Main\Config\Option::get(self::MODULE_ID, 'SHOW_REGION_CONTACT', 'N') == 'Y');
		$bAddr = ($arRegion && $bRegionContact ? $arRegion['PROPERTY_ADDRESS_VALUE']['TEXT'] : self::checkContentFile(SITE_DIR.'include/contacts-site-address.php'));
		?>
		<?if($arRegion):?>
			<?$frame = new \Bitrix\Main\Page\FrameHelper('header-allcaddr-block'.$iCalledID);?>
			<?$frame->begin();?>
		<?endif;?>
		<?if($bAddr): // count of phones?>
			<?if($wrapTable):?>
				<table>
			<?endif;?>
				<tr>
					<td class="icon"><i class="fa big-icon s45"><?=self::showIconSvg("address", SITE_TEMPLATE_PATH."/images/svg/".$icon);?></i></td>
					<td>
						<span class="dark_table"><?=$txt;?></span>
						<?if($arRegion && $bRegionContact):?>
							<div itemprop="address" class="<?=($class ? ' '.$class : '')?>">
								<?=$arRegion['PROPERTY_ADDRESS_VALUE']['TEXT'];?>
							</div>
						<?else:?>
							<div itemprop="address"><?$APPLICATION->IncludeFile(SITE_DIR."include/contacts-site-address.php", Array(), Array("MODE" => "html", "NAME" => "address"));?></div>
						<?endif;?>
					</td>
				</tr>
			<?if($wrapTable):?>
				</table>
			<?endif;?>
		<?endif;?>
		<?if($arRegion):?>
			<?$frame->end();?>
		<?endif;?>
		<?
	}

	public static function showContactShcedule($txt = '', $wrapTable = true, $class = '', $icon = 'WorkingHours_lg.svg'){
		global $arRegion, $APPLICATION;
		$iCalledID = ++$cshc_call;
		$bRegionContact = (\Bitrix\Main\Config\Option::get(self::MODULE_ID, 'SHOW_REGION_CONTACT', 'N') == 'Y');
		$bAddr = ($arRegion && $bRegionContact ? $arRegion['PROPERTY_SHCEDULE_VALUE']['TEXT'] : self::checkContentFile(SITE_DIR.'include/contacts-site-schedule.php'));
		?>
		<?if($arRegion):?>
			<?$frame = new \Bitrix\Main\Page\FrameHelper('header-allcaddr-block'.$iCalledID);?>
			<?$frame->begin();?>
		<?endif;?>
		<?if($bAddr): // count of phones?>
			<?if($wrapTable):?>
				<table>
			<?endif;?>
				<tr>
					<td class="icon"><i class="fa big-icon s45"><?=self::showIconSvg("schedule", SITE_TEMPLATE_PATH."/images/svg/".$icon);?></i></td>
					<td>
						<span class="dark_table"><?=$txt;?></span>
						<?if($arRegion && $bRegionContact):?>
							<div class="<?=($class ? ' '.$class : '')?>">
								<?=$arRegion['PROPERTY_SHCEDULE_VALUE']['TEXT'];?>
							</div>
						<?else:?>
							<div><?$APPLICATION->IncludeFile(SITE_DIR."include/contacts-site-schedule.php", Array(), Array("MODE" => "html", "NAME" => "schedule"));?></div>
						<?endif;?>
					</td>
				</tr>
			<?if($wrapTable):?>
				</table>
			<?endif;?>
		<?endif;?>
		<?if($arRegion):?>
			<?$frame->end();?>
		<?endif;?>
		<?
	}

	public static function showCompanyFront(){
		global $arRegion, $APPLICATION;
		$iCalledID = ++$companyfr_call;
		?>
		<?if($arRegion):?>
			<?$frame = new \Bitrix\Main\Page\FrameHelper('company-front-block'.$iCalledID);?>
			<?$frame->begin();?>
		<?endif;?>		
		<?if($arRegion):?>
			<?=$arRegion['DETAIL_TEXT'];?>
		<?else:?>
			<div class="col-md-8 col-sm-12 col-xs-12">
				<?$APPLICATION->IncludeFile(SITE_DIR."include/mainpage/company_text.php", Array(), Array(
				    "MODE"      => "html",
				    "NAME"      => GetMessage("COMPANY_TEXT"),
				    ));
				?>
			</div>
			<div class="col-md-4 hidden-xs hidden-sm">
				<?$APPLICATION->IncludeFile(SITE_DIR."include/mainpage/company_img.php", Array(), Array(
				    "MODE"      => "html",
				    "NAME"      => GetMessage("COMPANY_IMG"),
				    ));
				?>
			</div>
		<?endif;?>
		<?if($arRegion):?>
			<?$frame->end();?>
		<?endif;?>
		<?
	}

	public static function showHeaderSchedule($class = 'schedule'){
		global $arRegion, $APPLICATION;
		$iCalledID = ++$hshc_call;
		$bBlock = ($arRegion ? $arRegion['PROPERTY_SHCEDULE_VALUE']['TEXT'] : self::checkContentFile(SITE_DIR.'include/header-schedule.php'));
		?>
		<?if($arRegion):?>
			<?$frame = new \Bitrix\Main\Page\FrameHelper('header-allhsch-block'.$iCalledID);?>
			<?$frame->begin();?>
		<?endif;?>
		<?if($bBlock):?>
			<div class="<?=($class ? ' '.$class : '')?>">
				<?if($arRegion):?>
					<?=$arRegion['PROPERTY_SHCEDULE_VALUE']['TEXT'];?>
				<?else:?>
					<?$APPLICATION->IncludeFile(SITE_DIR."include/header-schedule.php", Array(), Array("MODE" => "html", "NAME" => "schedule"));?>
				<?endif;?>
			</div>
		<?endif;?>
		<?if($arRegion):?>
			<?$frame->end();?>
		<?endif;?>
		<?
	}

	public static function showAddress($class = '', $classSVG = 'address', $svgName = 'Addres_black.svg'){
		global $arRegion, $APPLICATION;
		static $addr_call;
		$iCalledID = ++$addr_call;
		$regionID = ($arRegion ? $arRegion['ID'] : '');?>

		<?if($arRegion):?>
		<?$frame = new \Bitrix\Main\Page\FrameHelper('address-block'.$iCalledID);?>
		<?$frame->begin();?>
		<?endif;?>

			<?if($arRegion):?>
				<?if($arRegion['PROPERTY_ADDRESS_VALUE']):?>
					<div <?=($class ? 'class="'.$class.'"' : '')?>>
						<?if($svgName):?>
							<?=self::showIconSvg($classSVG, SITE_TEMPLATE_PATH."/images/svg/".$svgName);?>
						<?endif;?>
						<div>
							<?=$arRegion['PROPERTY_ADDRESS_VALUE']['TEXT'];?>
						</div>
					</div>
				<?endif;?>
			<?else:?>
				<?if(self::checkContentFile(SITE_DIR.'include/header/site-address.php')):?>
					<div <?=($class ? 'class="'.$class.'"' : '')?>>
						<?if($svgName):?>
							<?=self::showIconSvg($classSVG, SITE_TEMPLATE_PATH."/images/svg/".$svgName);?>
						<?endif;?>
						<div>
							<?$APPLICATION->IncludeFile(SITE_DIR."include/header/site-address.php", array(), array(
									"MODE" => "html",
									"NAME" => "Address in title",
									"TEMPLATE" => "include_area.php",
								)
							);?>
						</div>
					</div>
				<?endif;?>
			<?endif;?>

		<?if($arRegion):?>
		<?$frame->end();?>
		<?endif;?>
	<?}

	public static function showEmail($class = 'email', $classSVG = 'email', $svgName = 'Email.svg'){
		global $arRegion, $APPLICATION;
		static $addr_call;
		$iCalledID = ++$addr_call;
		$regionID = ($arRegion ? $arRegion['ID'] : '');?>

		<?if($arRegion):?>
		<?$frame = new \Bitrix\Main\Page\FrameHelper('email-block'.$iCalledID);?>
		<?$frame->begin();?>
		<?endif;?>

			<?if($arRegion):?>
				<?if($arRegion['PROPERTY_EMAIL_VALUE']):?>
					<div <?=($class ? 'class="'.$class.'"' : '')?>>
						<?=self::showIconSvg($classSVG, SITE_TEMPLATE_PATH."/images/svg/".$svgName);?>
						<?foreach($arRegion['PROPERTY_EMAIL_VALUE'] as $value):?>
							<div>
								<a href="mailto:<?=$value;?>"><?=$value;?></a>
							</div>
						<?endforeach;?>
					</div>
				<?endif;?>
			<?else:?>
				<?if(self::checkContentFile(SITE_DIR.'include/header/site-address.php')):?>
					<div <?=($class ? 'class="'.$class.'"' : '')?>>
						<?=self::showIconSvg($classSVG, SITE_TEMPLATE_PATH."/images/svg/".$svgName);?>
						<div>
							<?$APPLICATION->IncludeFile(SITE_DIR."include/footer/site-email.php", array(), array(
									"MODE" => "html",
									"NAME" => "E-mail",
									"TEMPLATE" => "include_area",
								)
							);?>
						</div>
					</div>
				<?endif;?>
			<?endif;?>

		<?if($arRegion):?>
		<?$frame->end();?>
		<?endif;?>
	<?}

	public static function showRightDok(){
		global $arTheme;
		$rightBlock = (isset($arTheme['RIGHT_FORM_BLOCK']['VALUE']) ? $arTheme['RIGHT_FORM_BLOCK']['VALUE'] : $arTheme['RIGHT_FORM_BLOCK']);
		$callbackBlock = (isset($arTheme['CALLBACK_BUTTON']['VALUE']) ? $arTheme['CALLBACK_BUTTON']['VALUE'] : $arTheme['CALLBACK_BUTTON']);
		?>
		<?if($rightBlock == 'Y'):?>
			<div class="right_dok">
				<?if($callbackBlock == 'Y'):?>
					<span class="link" title="<?=GetMessage("S_CALLBACK")?>">
						<span class="animate-load" data-event="jqm" data-param-id="<?=self::getFormID("aspro_allcorp2_callback");?>" data-name="callback"><?=self::showIconSvg("call", SITE_TEMPLATE_PATH."/images/svg/back_call.svg");?></span>
					</span>
				<?endif;?>
				<span class="link" title="<?=GetMessage("S_QUESTION")?>">
					<span class="animate-load" data-event="jqm" data-param-id="<?=self::getFormID("aspro_allcorp2_question");?>" data-name="question"><?=self::showIconSvg("ask", SITE_TEMPLATE_PATH."/images/svg/ask_question.svg");?></span>
				</span>
			</div>
		<?endif;?>
	<?}

	public static function checkBasketItems(){
		if(!defined('ADMIN_SECTION') && !CSite::inDir(SITE_DIR.'/ajax/')):?>
			<script>
				var arBasketItems = <?=CUtil::PhpToJSObject(self::getBasketItems(), false)?>;
			</script>
		<?endif;
	}

	public static function getBasketItems(){
		global $APPLICATION, $arSite, $USER;
		CModule::IncludeModule('iblock');

		if(!defined('ADMIN_SECTION')){
			$userID = CUser::GetID();
			$userID = ($userID > 0 ? $userID : 0);
			$arBackParametrs = self::GetFrontParametrsValues(SITE_ID);
			$bOrderViewBasket = ($arBackParametrs['ORDER_VIEW'] == 'Y' ? true : false);

			if($bOrderViewBasket && isset($_SESSION[SITE_ID][$userID]['BASKET_ITEMS']) && is_array($_SESSION[SITE_ID][$userID]['BASKET_ITEMS']) && $_SESSION[SITE_ID][$userID]['BASKET_ITEMS']){
				$arIBlocks = $arBasketItemsIDs = array();

				foreach($_SESSION[SITE_ID][$userID]['BASKET_ITEMS'] as $arBasketItem){
					if(isset($arBasketItem['IBLOCK_ID']) && intval($arBasketItem['IBLOCK_ID']) > 0 && !in_array($arBasketItem['IBLOCK_ID'], $arIBlocks))
						$arIBlocks[] = $arBasketItem['IBLOCK_ID'];

					$arBasketItemsIDs[] = $arBasketItem['ID'];
				}

				$dbRes = CIBlockElement::GetList(array(), array('IBLOCK_ID' => $arIBlocks, 'ID' => $arBasketItemsIDs, 'PROPERTY_FORM_ORDER_VALUE' => false), false, false, array('ID'));
				while($arRes = $dbRes->Fetch()){
					unset($_SESSION[SITE_ID][$userID]['BASKET_ITEMS'][$arRes['ID']]);
				}

				return $_SESSION[SITE_ID][$userID]['BASKET_ITEMS'];
			}

			return array();
		}

		return false;
	}

	// DO NOT USE - FOR OLD VERSIONS
	public static function linkShareImage($previewPictureID = false, $detailPictureID = false){
		global $APPLICATION;

		if($linkSaherImageID = ($detailPictureID ? $detailPictureID : ($previewPictureID ? $previewPictureID : false)))
			$APPLICATION->AddHeadString('<link rel="image_src" href="'.CFile::GetPath($linkSaherImageID).'"  />', true);
	}

	public static function processBasket(){
		global $USER;
		$userID = CUser::GetID();
		$userID = ($userID > 0 ? $userID : 0);

		if(isset($_REQUEST['itemData']) && is_array($_REQUEST['itemData']))
			$_REQUEST['itemData'] = array_map('self::conv', $_REQUEST['itemData']);

		if(isset($_REQUEST['removeAll']) && $_REQUEST['removeAll'] === 'Y')
		{
			unset($_SESSION[SITE_ID][$userID]['BASKET_ITEMS']);
		}
		elseif(isset($_REQUEST['itemData']['ID']) && intval($_REQUEST['itemData']['ID']) > 0)
		{
			if(!is_array($_SESSION[SITE_ID][$userID]['BASKET_ITEMS']))
				$_SESSION[SITE_ID][$userID]['BASKET_ITEMS'] = array();


			if(isset($_REQUEST['remove']) && $_REQUEST['remove'] === 'Y')
			{
				if(isset($_SESSION[SITE_ID][$userID]['BASKET_ITEMS']) && isset($_SESSION[SITE_ID][$userID]['BASKET_ITEMS'][$_REQUEST['itemData']['ID']])){
					unset($_SESSION[SITE_ID][$userID]['BASKET_ITEMS'][$_REQUEST['itemData']['ID']]);
				}
			}
			elseif(isset($_REQUEST['quantity']) && floatval($_REQUEST['quantity']) > 0)
			{
				$_SESSION[SITE_ID][$userID]['BASKET_ITEMS'][$_REQUEST['itemData']['ID']] = (isset($_SESSION[SITE_ID][$userID]['BASKET_ITEMS'][$_REQUEST['itemData']['ID']]) ? $_SESSION[SITE_ID][$userID]['BASKET_ITEMS'][$_REQUEST['itemData']['ID']] : $_REQUEST['itemData']);
				$_SESSION[SITE_ID][$userID]['BASKET_ITEMS'][$_REQUEST['itemData']['ID']]['QUANTITY'] = $_REQUEST['quantity'];

			}
		}
		return $_SESSION[SITE_ID][$userID]['BASKET_ITEMS'];
	}

	public static function conv($n){
		return iconv('UTF-8', SITE_CHARSET, $n);
	}

	public static function getDataItem($el){
		$dataItem = array(
			"IBLOCK_ID" => $el['IBLOCK_ID'],
			"ID" => $el['ID'],
			"NAME" => $el['NAME'],
			"DETAIL_PAGE_URL" => $el['DETAIL_PAGE_URL'],
			"PREVIEW_PICTURE" => $el['PREVIEW_PICTURE']['ID'],
			"DETAIL_PICTURE" => $el['DETAIL_PICTURE']['ID'],
			"PROPERTY_FILTER_PRICE_VALUE" => $el['PROPERTIES']['FILTER_PRICE']['VALUE'],
			"PROPERTY_PRICE_VALUE" => $el['PROPERTIES']['PRICE']['VALUE'],
			"PROPERTY_PRICEOLD_VALUE" => $el['PROPERTIES']['PRICEOLD']['VALUE'],
			"PROPERTY_ARTICLE_VALUE" => $el['PROPERTIES']['ARTICLE']['VALUE'],
			"PROPERTY_STATUS_VALUE" => $el['PROPERTIES']['STATUS']['VALUE_ENUM_ID'],
		);

		global $APPLICATION;
		$dataItem = $APPLICATION->ConvertCharsetArray($dataItem, SITE_CHARSET, 'UTF-8');
		$dataItem = htmlspecialchars(json_encode($dataItem));
		return $dataItem;
	}

	public static function utf8_substr_replace($original, $replacement, $position, $length){
		$startString = mb_substr($original, 0, $position, "UTF-8");
		$endString = mb_substr($original, $position + $length, mb_strlen($original), "UTF-8");

		$out = $startString.$replacement.$endString;

		return $out;
	}

	public static function ShowRSSIcon($href){?>
		<script type="text/javascript">
			$(document).ready(function () {
				$('h1').before('<a class="rss" href="<?=$href?>" title="rss" target="_blank">RSS</a>');
			});
		</script>
		<?
		$GLOBALS['APPLICATION']->AddHeadString('<link rel="alternate" type="application/rss+xml" title="rss" href="'.$href.'" />');
	}

	public static function getFieldImageData(array &$arItem, array $arKeys, $entity = 'ELEMENT', $ipropertyKey = 'IPROPERTY_VALUES'){
		if (empty($arItem) || empty($arKeys))
            return;

        $entity = (string)$entity;
        $ipropertyKey = (string)$ipropertyKey;

        foreach ($arKeys as $fieldName)
        {
            if(!isset($arItem[$fieldName]) || (!isset($arItem['~'.$fieldName]) || !$arItem['~'.$fieldName]))
                continue;
            $imageData = false;
            $imageId = (int)$arItem['~'.$fieldName];
            if ($imageId > 0)
                $imageData = \CFile::getFileArray($imageId);
            unset($imageId);
            if (is_array($imageData))
            {
                if (isset($imageData['SAFE_SRC']))
                {
                    $imageData['UNSAFE_SRC'] = $imageData['SRC'];
                    $imageData['SRC'] = $imageData['SAFE_SRC'];
                }
                else
                {
                    $imageData['UNSAFE_SRC'] = $imageData['SRC'];
                    $imageData['SRC'] = \CHTTP::urnEncode($imageData['SRC'], 'UTF-8');
                }
                $imageData['ALT'] = '';
                $imageData['TITLE'] = '';

                if ($ipropertyKey != '' && isset($arItem[$ipropertyKey]) && is_array($arItem[$ipropertyKey]))
                {
                    $entityPrefix = $entity.'_'.$fieldName;
                    if (isset($arItem[$ipropertyKey][$entityPrefix.'_FILE_ALT']))
                        $imageData['ALT'] = $arItem[$ipropertyKey][$entityPrefix.'_FILE_ALT'];
                    if (isset($arItem[$ipropertyKey][$entityPrefix.'_FILE_TITLE']))
                        $imageData['TITLE'] = $arItem[$ipropertyKey][$entityPrefix.'_FILE_TITLE'];
                    unset($entityPrefix);
                }
                if ($imageData['ALT'] == '' && isset($arItem['NAME']))
                    $imageData['ALT'] = $arItem['NAME'];
                if ($imageData['TITLE'] == '' && isset($arItem['NAME']))
                    $imageData['TITLE'] = $arItem['NAME'];
            }
            $arItem[$fieldName] = $imageData;
            unset($imageData);
        }

        unset($fieldName);
	}

	public static function drawFormField($FIELD_SID, $arQuestion, $type = 'POPUP', $arParams = array()){?>
		<?$arQuestion["HTML_CODE"] = str_replace('name=', 'data-sid="'.$FIELD_SID.'" name=', $arQuestion["HTML_CODE"]);?>
		<?$arQuestion["HTML_CODE"] = str_replace('left', '', $arQuestion["HTML_CODE"]);?>
		<?$arQuestion["HTML_CODE"] = str_replace('size="0"', '', $arQuestion["HTML_CODE"]);?>
		<?if($arQuestion['STRUCTURE'][0]['FIELD_TYPE'] == 'hidden'):?>
			<?if($FIELD_SID == 'TOTAL_SUMM')
			{
				if($arParams['TOTAL_SUMM'])
				{
					$arQuestion["HTML_CODE"] = str_replace('value="', 'value="'.$arParams['TOTAL_SUMM'], $arQuestion["HTML_CODE"]);
				}
			}?>
			<?=$arQuestion["HTML_CODE"];?>
		<?else:?>
			<div class="row" data-SID="<?=$FIELD_SID?>">
				<div class="col-md-12 <?=(in_array($arQuestion['STRUCTURE'][0]['FIELD_TYPE'], array("checkbox", "radio")) ? "style_check bx_filter" : "");?>">
					<div class="form-group <?=(!in_array($arQuestion['STRUCTURE'][0]['FIELD_TYPE'], array("file", "image", "checkbox", "radio", "multiselect", "date1")) ? "animated-labels" : "");?> <?=( $arQuestion['VALUE'] || $_REQUEST['form_'.$arQuestion['STRUCTURE'][0]['FIELD_TYPE'].'_'.$arQuestion['STRUCTURE'][0]['ID']] || $arQuestion['STRUCTURE'][0]['VALUE'] || $arQuestion["STRUCTURE"][0]["FIELD_TYPE"] == "email" || (in_array($FIELD_SID, array('FIO', 'NAME', 'PHONE', 'EMAIL')) && $GLOBALS['USER']->isAuthorized()) ? "input-filed" : "");?><?=( $arQuestion['STRUCTURE'][0]['FIELD_TYPE'] == 'dropdown' || $arQuestion['STRUCTURE'][0]['FIELD_TYPE'] == 'multiselect' ? "input-filed" : "");?>">
						<label for="<?=$type.'_'.$FIELD_SID?>"><span><?=$arQuestion["CAPTION"]?>:<?=($arQuestion["REQUIRED"] == "Y" ? '&nbsp;<span class="required-star">*</span>' : '')?></span></label>
						<div class="input <?=(($arQuestion['STRUCTURE'][0]['FIELD_TYPE'] == 'date') ? 'dates' : '')?>">
							<?
							/*if($arQuestion['STRUCTURE'][0]['FIELD_TYPE'] != 'date')
							{*/
								if(strpos($arQuestion["HTML_CODE"], "class=") === false)
								{
									$arQuestion["HTML_CODE"] = str_replace('input', 'input class=""', $arQuestion["HTML_CODE"]);
								}
								$arQuestion["HTML_CODE"] = str_replace('class="', 'class="form-control ', $arQuestion["HTML_CODE"]);
								$arQuestion["HTML_CODE"] = str_replace('class="', 'id="'.$type.'_'.$FIELD_SID.'" class="', $arQuestion["HTML_CODE"]);
							//}


							if(is_array($arResult["FORM_ERRORS"]) && array_key_exists($FIELD_SID, $arResult['FORM_ERRORS']))
								$arQuestion["HTML_CODE"] = str_replace('class="', 'class="error ', $arQuestion["HTML_CODE"]);
							
							if($arQuestion["REQUIRED"] == "Y")
								$arQuestion["HTML_CODE"] = str_replace('name=', 'required name=', $arQuestion["HTML_CODE"]);

							if($arQuestion["STRUCTURE"][0]["FIELD_TYPE"] == "email")
								$arQuestion["HTML_CODE"] = str_replace('type="text"', 'type="email" placeholder=""', $arQuestion["HTML_CODE"]);

							if(strpos($arQuestion["HTML_CODE"], "phone") !== false)
								$arQuestion["HTML_CODE"] = str_replace('type="text"', 'type="tel"', $arQuestion["HTML_CODE"]);
							
							?>
							<?if($arQuestion['STRUCTURE'][0]['FIELD_TYPE'] == "checkbox" || $arQuestion['STRUCTURE'][0]['FIELD_TYPE'] == "radio"):?>
								<?foreach($arQuestion['STRUCTURE'] as $arTmpQuestion):?>
									<?$name = $arTmpQuestion["FIELD_TYPE"]."_".$FIELD_SID;?>
									<?$name .= ($arQuestion['STRUCTURE'][0]['FIELD_TYPE'] == "checkbox" ? "[]" : "")?>
									<div class="filter <?=$arTmpQuestion["FIELD_TYPE"];?>">
										<input id="s<?=$arTmpQuestion["ID"];?>" name="form_<?=$name;?>" type="<?=$arTmpQuestion["FIELD_TYPE"];?>" value="<?=$arTmpQuestion["ID"];?>"/>
										<label for="s<?=$arTmpQuestion["ID"];?>"><?=$arTmpQuestion["MESSAGE"];?></label>
									</div>
								<?endforeach;?>
							<?else:?>
								<?=$arQuestion["HTML_CODE"]?>
							<?endif;?>
						</div>
						<?if( !empty( $arQuestion["HINT"] ) ){?>
							<div class="hint"><?=$arQuestion["HINT"]?></div>
						<?}?>
					</div>
				</div>
			</div>
		<?endif;?>
	<?}

	public static function getFormID($code = '', $site = SITE_ID){
		global $arTheme;
		$form_id = 0;
		if($code)
		{
			if(self::GetFrontParametrValue('USE_BITRIX_FORM') == 'Y' && \Bitrix\Main\Loader::includeModule('form'))
			{
				$rsForm = CForm::GetList($by = 'id', $order = 'asc', array('ACTIVE' => 'Y', 'SID' => $code, 'SITE' => array($site), 'SID_EXACT_MATCH' => 'N'), $is_filtered);
				if($item = $rsForm->Fetch())
					$form_id = $item['ID'];
				else
					$form_id = CCache::$arIBlocks[$site]["aspro_allcorp2_form"][$code][0];
			}
			else
			{
				$form_id = CCache::$arIBlocks[$site]["aspro_allcorp2_form"][$code][0];
			}
		}
		return $form_id;
	}

	public static function checkContentFile($path){
		if(File::isFileExists($_SERVER['DOCUMENT_ROOT'].$path))
			$content = File::getFileContents($_SERVER['DOCUMENT_ROOT'].$path);
		return (!empty($content));
	}

	public static function get_banners_position($position){
		$arTheme = self::GetFrontParametrsValues(SITE_ID);
		if ($arTheme["ADV_".$position] == 'Y') {
			global $APPLICATION;
			$APPLICATION->IncludeComponent(
				"bitrix:news.list",
				"banners",
				array(
					"IBLOCK_TYPE" => "aspro_allcorp2_adv",
					"IBLOCK_ID" => CCache::$arIBlocks[SITE_ID]["aspro_allcorp2_adv"]["aspro_allcorp2_banners"][0],
					"POSITION"	=> $position,
					"PAGE"		=> $APPLICATION->GetCurPage(),
					"NEWS_COUNT" => "100",
					"SORT_BY1" => "SORT",
					"SORT_ORDER1" => "ASC",
					"SORT_BY2" => "ID",
					"SORT_ORDER2" => "ASC",
					"FIELD_CODE" => array(
						0 => "NAME",
						2 => "PREVIEW_PICTURE",
					),
					"PROPERTY_CODE" => array(
						0 => "LINK",
						1 => "TARGET",
						2 => "BGCOLOR",
						3 => "SHOW_SECTION",
						4 => "SHOW_PAGE",
						5 => "HIDDEN_XS",
						6 => "HIDDEN_SM",
						7 => "POSITION",
						8 => "SIZING",
					),
					"CHECK_DATES" => "Y",
					"FILTER_NAME" => "arRegionLink",
					"DETAIL_URL" => "",
					"AJAX_MODE" => "N",
					"AJAX_OPTION_JUMP" => "N",
					"AJAX_OPTION_STYLE" => "Y",
					"AJAX_OPTION_HISTORY" => "N",
					"CACHE_TYPE" => "A",
					"CACHE_TIME" => "3600000",
					"CACHE_FILTER" => "Y",
					"CACHE_GROUPS" => "N",
					"PREVIEW_TRUNCATE_LEN" => "150",
					"ACTIVE_DATE_FORMAT" => "d.m.Y",
					"SET_TITLE" => "N",
					"SET_STATUS_404" => "N",
					"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
					"ADD_SECTIONS_CHAIN" => "N",
					"HIDE_LINK_WHEN_NO_DETAIL" => "N",
					"PARENT_SECTION" => "",
					"PARENT_SECTION_CODE" => "",
					"INCLUDE_SUBSECTIONS" => "Y",
					"PAGER_TEMPLATE" => ".default",
					"DISPLAY_TOP_PAGER" => "N",
					"DISPLAY_BOTTOM_PAGER" => "N",
					"PAGER_TITLE" => "",
					"PAGER_SHOW_ALWAYS" => "N",
					"PAGER_DESC_NUMBERING" => "N",
					"PAGER_DESC_NUMBERING_CACHE_TIME" => "3600000",
					"PAGER_SHOW_ALL" => "N",
					"AJAX_OPTION_ADDITIONAL" => "",
					"SHOW_DETAIL_LINK" => "N",
					"SET_BROWSER_TITLE" => "N",
					"SET_META_KEYWORDS" => "N",
					"SET_META_DESCRIPTION" => "N",
					"COMPONENT_TEMPLATE" => "banners",
					"SET_LAST_MODIFIED" => "N",
					"COMPOSITE_FRAME_MODE" => "A",
					"COMPOSITE_FRAME_TYPE" => "AUTO",
					"PAGER_BASE_LINK_ENABLE" => "N",
					"SHOW_404" => "N",
					"MESSAGE_404" => ""
				),
				false, array('ACTIVE_COMPONENT' => 'Y')
			);
		}
	}
}?>