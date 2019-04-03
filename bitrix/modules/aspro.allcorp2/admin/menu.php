<?
AddEventHandler('main', 'OnBuildGlobalMenu', 'OnBuildGlobalMenuHandlerAllcorp2');
function OnBuildGlobalMenuHandlerAllcorp2(&$arGlobalMenu, &$arModuleMenu){
	if(!defined('ALLCORP2_MENU_INCLUDED')){
		define('ALLCORP2_MENU_INCLUDED', true);

		IncludeModuleLangFile(__FILE__);
		$moduleID = 'aspro.allcorp2';

		$GLOBALS['APPLICATION']->SetAdditionalCss("/bitrix/css/".$moduleID."/menu.css");

		if($GLOBALS['APPLICATION']->GetGroupRight($moduleID) >= 'R'){
			$arGenerate = array(
						'text' => GetMessage('ALLCORP2_MENU_GENERATE_FILES_TEXT'),
						'title' => GetMessage('ALLCORP2_MENU_GENERATE_FILES_TITLE'),
						'sort' => 20,
						'url' => '/bitrix/admin/'.$moduleID.'_generate_robots.php?mid=main',
						'icon' => 'imi_marketing',
						'page_icon' => 'pi_typography',
						'items_id' => 'gfiles',
						"items" => array(
							array(
								'text' => GetMessage('ALLCORP2_MENU_GENERATE_ROBOTS_TEXT'),
								'title' => GetMessage('ALLCORP2_MENU_GENERATE_ROBOTS_TITLE'),
								'sort' => 20,
								'url' => '/bitrix/admin/'.$moduleID.'_generate_robots.php?mid=main',
								'icon' => '',
								'page_icon' => 'pi_typography',
								'items_id' => 'grobots',
							)
						)
					);
			if(\Bitrix\Main\Loader::includeModule('seo'))
			{
				$arGenerate["items"][] = array(
					'text' => GetMessage('ALLCORP2_MENU_GENERATE_SITEMAP_TEXT'),
					'title' => GetMessage('ALLCORP2_MENU_GENERATE_SITEMAP_TITLE'),
					'sort' => 20,
					'url' => '/bitrix/admin/'.$moduleID.'_generate_sitemap.php?mid=main',
					'icon' => '',
					'page_icon' => 'pi_typography',
					'items_id' => 'gsitemap',
				);
			}
			$arMenu = array(
				'menu_id' => 'global_menu_aspro_allcorp2',
				'text' => GetMessage('ALLCORP2_GLOBAL_MENU_TEXT'),
				'title' => GetMessage('ALLCORP2_GLOBAL_MENU_TITLE'),
				'sort' => 1000,
				'items_id' => 'global_menu_aspro_allcorp2_items',
				'icon' => 'imi_allcorp2',
				'items' => array(
					array(
						'text' => GetMessage('ALLCORP2_MENU_CONTROL_CENTER_TEXT'),
						'title' => GetMessage('ALLCORP2_MENU_CONTROL_CENTER_TITLE'),
						'sort' => 10,
						'url' => '/bitrix/admin/'.$moduleID.'_mc.php',
						'icon' => 'imi_control_center',
						'page_icon' => 'pi_control_center',
						'items_id' => 'control_center',
					),
					array(
						'text' => GetMessage('ALLCORP2_MENU_TYPOGRAPHY_TEXT'),
						'title' => GetMessage('ALLCORP2_MENU_TYPOGRAPHY_TITLE'),
						'sort' => 20,
						'url' => '/bitrix/admin/'.$moduleID.'_options.php?mid=main',
						'icon' => 'imi_typography',
						'page_icon' => 'pi_typography',
						'items_id' => 'main',
					),
					array(
						'text' => GetMessage('ALLCORP2_MENU_CRM_TEXT'),
						'title' => GetMessage('ALLCORP2_MENU_CRM_TITLE'),
						'sort' => 20,
						'url' => '/bitrix/admin/'.$moduleID.'_crm_amo.php?mid=main',
						'icon' => 'imi_marketing',
						'page_icon' => 'pi_typography',
						'items_id' => 'gfiles',
						"items" => array(
							array(
								'text' => GetMessage('ALLCORP2_MENU_AMO_CRM_TEXT'),
								'title' => GetMessage('ALLCORP2_MENU_AMO_CRM_TITLE'),
								'sort' => 20,
								'url' => '/bitrix/admin/'.$moduleID.'_crm_amo.php?mid=main',
								'icon' => '',
								'page_icon' => 'pi_typography',
								'items_id' => 'grobots',
							),
							array(
								'text' => GetMessage('ALLCORP2_MENU_FLOWLU_CRM_TEXT'),
								'title' => GetMessage('ALLCORP2_MENU_FLOWLU_CRM_TITLE'),
								'sort' => 20,
								'url' => '/bitrix/admin/'.$moduleID.'_crm_flowlu.php?mid=main',
								'icon' => '',
								'page_icon' => 'pi_typography',
								'items_id' => 'gsitemap',
							),	
						)
					),
					$arGenerate
				),
			);

			if(!isset($arGlobalMenu['global_menu_aspro'])){
				$arGlobalMenu['global_menu_aspro'] = array(
					'menu_id' => 'global_menu_aspro',
					'text' => GetMessage('ASPRO_ALLCORP2_GLOBAL_ASPRO_MENU_TEXT'),
					'title' => GetMessage('ASPRO_ALLCORP2_GLOBAL_ASPRO_MENU_TITLE'),
					'sort' => 1000,
					'items_id' => 'global_menu_aspro_items',
				);
			}
			
			$arGlobalMenu['global_menu_aspro']['items'][$moduleID] = $arMenu;
		}
	}
}
?>