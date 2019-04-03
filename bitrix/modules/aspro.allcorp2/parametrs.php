<?php
/**
 * CAllcorp2 module
 * @copyright 2017 Aspro
 */

IncludeModuleLangFile(__FILE__);
$moduleClass = 'CAllcorp2';
$solution = 'aspro.allcorp2';

// initialize module parametrs list and default values
$moduleClass::$arParametrsList = array(
	'MAIN' => array(
		'TITLE' => GetMessage('MAIN_OPTIONS_PARAMETERS'),
		'THEME' => 'Y',
		'OPTIONS' => array(
			'THEME_SWITCHER' =>	array(
				'TITLE' => GetMessage('THEME_SWITCHER'),
				'TYPE' => 'checkbox',
				'DEFAULT' => 'Y',
				'THEME' => 'N',
			),
			'BASE_COLOR' => array(
				'TITLE' => GetMessage('BASE_COLOR'),
				'TYPE' => 'selectbox',
				'LIST' => array(
					'CUSTOM' => array('COLOR' => '', 'TITLE' => GetMessage('BASE_COLOR_CUSTOM')),
					'1' => array('COLOR' => '#ffad00', 'TITLE' => GetMessage('BASE_COLOR_1')),
					'2' => array('COLOR' => '#ff6d00', 'TITLE' => GetMessage('BASE_COLOR_2')),
					'3' => array('COLOR' => '#e65100', 'TITLE' => GetMessage('BASE_COLOR_3')),
					'4' => array('COLOR' => '#de002b', 'TITLE' => GetMessage('BASE_COLOR_4')),
					'5' => array('COLOR' => '#b41818', 'TITLE' => GetMessage('BASE_COLOR_5')),
					'6' => array('COLOR' => '#bd1c3c', 'TITLE' => GetMessage('BASE_COLOR_6')),
					'7' => array('COLOR' => '#d75cb6', 'TITLE' => GetMessage('BASE_COLOR_7')),
					'8' => array('COLOR' => '#5f58ac', 'TITLE' => GetMessage('BASE_COLOR_8')),
					'9' => array('COLOR' => '#00569c', 'TITLE' => GetMessage('BASE_COLOR_9')),
					'10' => array('COLOR' => '#0088cc', 'TITLE' => GetMessage('BASE_COLOR_10')),
					'11' => array('COLOR' => '#107bb1', 'TITLE' => GetMessage('BASE_COLOR_11')),
					'12' => array('COLOR' => '#497c9d', 'TITLE' => GetMessage('BASE_COLOR_12')),
					'13' => array('COLOR' => '#0fa8ae', 'TITLE' => GetMessage('BASE_COLOR_13')),
					'14' => array('COLOR' => '#0d897f', 'TITLE' => GetMessage('BASE_COLOR_14')),
					'15' => array('COLOR' => '#1b9e77', 'TITLE' => GetMessage('BASE_COLOR_15')),
					'16' => array('COLOR' => '#188b30', 'TITLE' => GetMessage('BASE_COLOR_16')),
					'17' => array('COLOR' => '#48a216', 'TITLE' => GetMessage('BASE_COLOR_17')),

				),
				'DEFAULT' => '10',
				'TYPE_EXT' => 'colorpicker',
				'THEME' => 'Y',
			),
			'BASE_COLOR_CUSTOM' => array(
				'TITLE' => GetMessage('BASE_COLOR_CUSTOM'),
				'TYPE' => 'text',
				'DEFAULT' => 'de002b',
				'PARENT_PROP' => 'BASE_COLOR',
				'THEME' => 'Y',
			),
			'BGCOLOR_THEME' => array(
				'TITLE' => GetMessage('BGCOLOR_THEME_TITLE'),
				'TYPE' => 'selectbox',
				'LIST' => array(
					'CUSTOM' => array('COLOR' => '', 'TITLE' => GetMessage('BASE_COLOR_CUSTOM')),
					'LIGHT' => array('COLOR' => '#f6f6f7', 'TITLE' => GetMessage('BGCOLOR_THEME_LIGHT')),
					'DARK' => array('COLOR' => '#272a39', 'TITLE' => GetMessage('BGCOLOR_THEME_DARK')),

				),
				'DEFAULT' => 'LIGHT',
				'TYPE_EXT' => 'colorpicker',
				'THEME' => 'Y',
			),
			'CUSTOM_BGCOLOR_THEME' => array(
				'TITLE' => GetMessage('CUSTOM_BGCOLOR_THEME_TITLE'),
				'TYPE' => 'text',
				'DEFAULT' => 'f6f6f7',
				'PARENT_PROP' => 'BGCOLOR_THEME',
				'THEME' => 'Y',
			),
			'SHOW_BG_BLOCK' => array(
				'TITLE' => GetMessage('SHOW_BG_BLOCK_TITLE'),
				'TYPE' => 'checkbox',
				'DEFAULT' => 'N',
				'THEME' => 'Y',
			),
			'COLORED_LOGO' => array(
				'TITLE' => GetMessage('COLORED_LOGO'),
				'TYPE' => 'checkbox',
				'DEFAULT' => 'Y',
				'THEME' => 'N',
			),
			'LOGO_IMAGE' => array(
				'TITLE' => GetMessage('LOGO_IMAGE'),
				'TYPE' => 'file',
				'DEFAULT' => serialize(array()),
				'THEME' => 'N',
			),
			'LOGO_IMAGE_LIGHT' => array(
				'TITLE' => GetMessage('LOGO_IMAGE_LIGHT'),
				'TYPE' => 'file',
				'DEFAULT' => serialize(array()),
				'THEME' => 'N',
			),
			'FAVICON_IMAGE' => array(
				'TITLE' => GetMessage('FAVICON_IMAGE'),
				'TYPE' => 'file',
				'DEFAULT' => serialize(array()),
				'THEME' => 'N',
			),
			'APPLE_TOUCH_ICON_IMAGE' => array(
				'TITLE' => GetMessage('APPLE_TOUCH_ICON_IMAGE'),
				'TYPE' => 'file',
				'DEFAULT' => serialize(array()),
				'THEME' => 'N',
			),
			'CUSTOM_FONT' => array(
				'TITLE' => GetMessage('CUSTOM_FONT_TITLE'),
				'TYPE' => 'text',
				'SIZE' => '',
				'DEFAULT' => '',
				'THEME' => 'N',
			),
			'FONT_STYLE' => array(
				'TITLE' => GetMessage('FONT_STYLE'),
				'TYPE' => 'selectbox',
				'LIST' => array(
					'1' => array(
						'TITLE' => '15px Open Sans',
						'GROUP' => 'Open Sans',
						'LINK' => 'Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,500,600,700,800&subset=latin,cyrillic-ext',
						'VALUE' => '15 px',
					),
					'2' => array(
						'TITLE' => '14px Open Sans',
						'GROUP' => 'Open Sans',
						'LINK' => 'Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,500,600,700,800&subset=latin,cyrillic-ext',
						'VALUE' => '14 px',
					),
					'3' => array(
						'TITLE' => '13px Open Sans',
						'GROUP' => 'Open Sans',
						'LINK' => 'Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,500,600,700,800&subset=latin,cyrillic-ext',
						'VALUE' => '13 px',
					),
					'4' => array(
						'TITLE' => '15px PT Sans Caption',
						'GROUP' => 'PT Sans',
						'LINK' => 'PT+Sans+Caption:400italic,700italic,400,700&subset=latin,cyrillic-ext',
						'VALUE' => '15 px',
					),
					'5' => array(
						'TITLE' => '14px PT Sans Caption',
						'GROUP' => 'PT Sans',
						'LINK' => 'PT+Sans+Caption:400italic,700italic,400,700&subset=latin,cyrillic-ext',
						'VALUE' => '14 px',
					),
					'6' => array(
						'TITLE' => '13px PT Sans Caption',
						'GROUP' => 'PT Sans',
						'LINK' => 'PT+Sans+Caption:400italic,700italic,400,700&subset=latin,cyrillic-ext',
						'VALUE' => '13 px',
					),
					'7' => array(
						'TITLE' => '15px Ubuntu',
						'GROUP' => 'Ubuntu',
						'LINK' => 'Ubuntu:300italic,400italic,500italic,700italic,400,300,500,700subset=latin,cyrillic-ext',
						'VALUE' => '15 px',
					),
					'8' => array(
						'TITLE' => '14px Ubuntu',
						'GROUP' => 'Ubuntu',
						'LINK' => 'Ubuntu:300italic,400italic,500italic,700italic,400,300,500,700subset=latin,cyrillic-ext',
						'VALUE' => '14 px',
					),
					'9' => array(
						'TITLE' => '13px Ubuntu',
						'GROUP' => 'Ubuntu',
						'LINK' => 'Ubuntu:300italic,400italic,500italic,700italic,400,300,500,700subset=latin,cyrillic-ext',
						'VALUE' => '13 px',
					),
					'10' => array(
						'TITLE' => '15px Roboto',
						'GROUP' => 'Roboto',
						'LINK' => 'Roboto:300italic,400italic,500italic,700italic,400,300,500,700subset=latin,cyrillic-ext',
						'VALUE' => '15 px',
					),
					'11' => array(
						'TITLE' => '14px Roboto',
						'GROUP' => 'Roboto',
						'LINK' => 'Roboto:300italic,400italic,500italic,700italic,400,300,500,700subset=latin,cyrillic-ext',
						'VALUE' => '14 px',
					),
					'12' => array(
						'TITLE' => '13px Roboto',
						'GROUP' => 'Roboto',
						'LINK' => 'Roboto:300italic,400italic,500italic,700italic,400,300,500,700subset=latin,cyrillic-ext',
						'VALUE' => '13 px',
					),
				),
				'DEFAULT' => '10',
				'THEME' => 'Y',
				'GROUPS' => 'Y',
			),
			'PAGE_WIDTH' => array(
				'TITLE' => GetMessage('PAGE_WIDTH'),
				'TYPE' => 'selectbox',
				'LIST' => array(
					'1' => '1 700 px',
					'2' => '1 500 px',
					'3' => '1 344 px',
					'4' => '1 200 px'
				),
				'DEFAULT' => '3',
				'THEME' => 'Y',
			),
			'H1_STYLE' => array(
				'TITLE' => GetMessage('H1FONT'),
				'TYPE' => 'selectbox',
				'LIST' => array(
					'1' => array(
						'TITLE' => 'Bold',
						'GROUP' => GetMessage('H1FONT_STYLE'),
						'VALUE' => 'Bold',
					),
					'2' => array(
						'TITLE' => 'Normal',
						'GROUP' => GetMessage('H1FONT_STYLE'),
						'VALUE' => 'Normal',
					)
				),
				'DEFAULT' => '2',
				'THEME' => 'Y',
				'GROUPS' => 'Y',
			),

			'TYPE_SEARCH' => array(
				'TITLE' => GetMessage('TYPE_SEARCH'),
				'TYPE' => 'selectbox',
				'LIST' => array(
					// 'corp' => '1',
					'fixed' => '2',
					'custom' => array(
						'TITLE' => 'Custom',
						'HIDE' => 'Y'
					)
				),
				'DEFAULT' => 'fixed',
				'THEME' => 'N',
			),
			'PAGE_TITLE' => array(
				'TITLE' => GetMessage('PAGE_TITLE'),
				'TYPE' => 'selectbox',
				'LIST' => array(
					'1' => '1',
					'5' => '2',
					'2' => '3',				
					'custom' => array(
						'TITLE' => 'Custom',
						'HIDE' => 'Y'
					),
				),
				'DEFAULT' => '1',
				'THEME' => 'Y',
			),
			'HOVER_TYPE_IMG' => array(
				'TITLE' => GetMessage('HOVER_TYPE_IMG_TITLE'),
				'TYPE' => 'selectbox',
				'LIST' => array(
					'shine' => GetMessage('HOVER_TYPE_IMG_SHINE'),
					'blink' => GetMessage('HOVER_TYPE_IMG_BLINK'),
					'none' => GetMessage('HOVER_TYPE_IMG_NONE'),
				),
				'DEFAULT' => 'blink',
				'THEME' => 'Y',
			),
			'SHOW_LICENCE' => array(
				'TITLE' => GetMessage('SHOW_LICENCE_TITLE'),
				'TYPE' => 'checkbox',
				'DEFAULT' => 'Y',
				'ONE_ROW' => 'Y',
				'HINT' => GetMessage('LICENCE_TEXT_VALUE_HINT'),
				'DEPENDENT_PARAMS' => array(
					'LICENCE_CHECKED' => array(
						'TITLE' => GetMessage('LICENCE_CHECKED_TITLE'),
						'TYPE' => 'checkbox',
						'CONDITIONAL_VALUE' => 'Y',
						'DEFAULT' => 'N',
						'THEME' => 'N',
					),
					'LICENCE_TEXT' => array(
						'TITLE' => GetMessage('LICENCE_TEXT_TITLE'),
						'HIDE_TITLE' => 'Y',
						'TYPE' => 'includefile',
						'INCLUDEFILE' => '#SITE_DIR#include/licenses_text.php',
						'CONDITIONAL_VALUE' => 'Y',
						'PARAMS' => array(
							'WIDTH' => '100%'
						),
						'DEFAULT' => GetMessage('LICENCE_TEXT_VALUE'),
						'THEME' => 'N',
					),
				),
				'THEME' => 'Y',
			),
			'HIDE_SITE_NAME_TITLE' => array(
				'TITLE' => GetMessage('HIDE_SITE_NAME_TITLE'),
				'TYPE' => 'checkbox',
				'DEFAULT' => 'N',
				'THEME' => 'N',
			),
			'PRINT_BUTTON' => array(
				'TITLE' => GetMessage('PRINT_BUTTON'),
				'TYPE' => 'checkbox',
				'DEFAULT' => 'N',
				'HINT' => GetMessage('PRINT_BUTTON_VALUE_HINT'),
				'ONE_ROW' => 'Y',
				'THEME' => 'Y',
			),
			'DEFAULT_MAP_MARKET' => array(
				'TITLE' => GetMessage('DEFAULT_MAP_MARKET_TITLE'),
				'TYPE' => 'checkbox',
				'DEFAULT' => 'N',
				'ONE_ROW' => 'Y',
				'THEME' => 'N',
			),
			'CALLBACK_BUTTON' => array(
				'TITLE' => GetMessage('CALLBACK_BUTTON'),
				'TYPE' => 'checkbox',
				'DEFAULT' => 'Y',
				'HINT' => GetMessage('CALLBACK_BUTTON_VALUE_HINT'),
				'ONE_ROW' => 'Y',
				'THEME' => 'Y',
			),
			'RIGHT_FORM_BLOCK' => array(
				'TITLE' => GetMessage('RIGHT_FORM_BLOCK'),
				'TYPE' => 'checkbox',
				'DEFAULT' => 'Y',
				'HINT' => GetMessage('RIGHT_FORM_BLOCK_VALUE_HINT'),
				'ONE_ROW' => 'Y',
				'THEME' => 'Y',
			),
			'SCROLLTOTOP_TYPE' => array(
				'TITLE' => GetMessage('SCROLLTOTOP_TYPE'),
				'TYPE' => 'selectbox',
				'LIST' => array(
					'NONE' => GetMessage('SCROLLTOTOP_TYPE_NONE'),
					'ROUND_COLOR' => GetMessage('SCROLLTOTOP_TYPE_ROUND_COLOR'),
					'ROUND_GREY' => GetMessage('SCROLLTOTOP_TYPE_ROUND_GREY'),
					'ROUND_WHITE' => GetMessage('SCROLLTOTOP_TYPE_ROUND_WHITE'),
					'RECT_COLOR' => GetMessage('SCROLLTOTOP_TYPE_RECT_COLOR'),
					'RECT_GREY' => GetMessage('SCROLLTOTOP_TYPE_RECT_GREY'),
					'RECT_WHITE' => GetMessage('SCROLLTOTOP_TYPE_RECT_WHITE'),
				),
				'DEFAULT' => 'ROUND_COLOR',
				'THEME' => 'N',
			),
			'SCROLLTOTOP_POSITION' => array(
				'TITLE' => GetMessage('SCROLLTOTOP_POSITION'),
				'TYPE' => 'selectbox',
				'LIST' => array(
					'TOUCH' => GetMessage('SCROLLTOTOP_POSITION_TOUCH'),
					'PADDING' => GetMessage('SCROLLTOTOP_POSITION_PADDING'),
					'CONTENT' => GetMessage('SCROLLTOTOP_POSITION_CONTENT'),
				),
				'DEFAULT' => 'PADDING',
				'THEME' => 'N',
			),
			'USE_BITRIX_FORM' => array(
				'TITLE' => GetMessage('USE_BITRIX_FORM_TITLE'),
				'TYPE' => 'checkbox',
				'DEFAULT' => 'N',
				'HINT' => GetMessage('USE_BITRIX_FORM_VALUE_HINT'),
				'ONE_ROW' => 'Y',
				'THEME' => 'Y',
			),
		),
	),
	'INDEX_PAGE' => array(
		'TITLE' => GetMessage('INDEX_PAGE_OPTIONS'),
		'THEME' => 'Y',
		'OPTIONS' => array(
			'BIGBANNER_ANIMATIONTYPE' => array(
				'TITLE' => GetMessage('BIGBANNER_ANIMATIONTYPE'),
				'TYPE' => 'selectbox',
				'LIST' => array(
					'SLIDE_HORIZONTAL' => GetMessage('ANIMATION_SLIDE_HORIZONTAL'),
					'SLIDE_VERTICAL' => GetMessage('ANIMATION_SLIDE_VERTICAL'),
					'FADE' => GetMessage('ANIMATION_FADE'),
				),
				'DEFAULT' => 'SLIDE_HORIZONTAL',
				'THEME' => 'N',
			),
			'BIGBANNER_SLIDESSHOWSPEED' => array(
				'TITLE' => GetMessage('BIGBANNER_SLIDESSHOWSPEED'),
				'TYPE' => 'text',
				'DEFAULT' => '5000',
				'THEME' => 'N',
			),
			'BIGBANNER_ANIMATIONSPEED' => array(
				'TITLE' => GetMessage('BIGBANNER_ANIMATIONSPEED'),
				'TYPE' => 'text',
				'DEFAULT' => '600',
				'THEME' => 'N',
			),
			'BIGBANNER_HIDEONNARROW' => array(
				'TITLE' => GetMessage('BIGBANNER_HIDEONNARROW'),
				'TYPE' => 'checkbox',
				'DEFAULT' => 'N',
				'THEME' => 'N',
			),
			'PARTNERSBANNER_SLIDESSHOWSPEED' => array(
				'TITLE' => GetMessage('PARTNERSBANNER_SLIDESSHOWSPEED'),
				'TYPE' => 'text',
				'DEFAULT' => '5000',
				'THEME' => 'N',
			),
			'PARTNERSBANNER_ANIMATIONSPEED' => array(
				'TITLE' => GetMessage('PARTNERSBANNER_ANIMATIONSPEED'),
				'TYPE' => 'text',
				'DEFAULT' => '600',
				'THEME' => 'N',
			),
			'API_TOKEN_INSTAGRAMM' => array(
				'TITLE' => GetMessage('API_TOKEN_INSTAGRAMM_TITLE'),
				'TYPE' => 'text',
				'DEFAULT' => '1056017790.9b6cbfe.4dfb9d965b5c4c599121872c23b4dfd0',
				'THEME' => 'N',
			),
			'INDEX_TYPE' => array(
				'TITLE' => GetMessage('INDEX_TYPE'),
				'TYPE' => 'selectbox',
				'LIST' => array(
					'index1' => '1',
					'index2' => '2',
					'index3' => '3',
					'index4' => '4',
					'custom' => array(
						'TITLE' => 'Custom',
						'HIDE' => 'Y'
					),
				),
				'DEFAULT' => 'index1',
				'THEME' => 'Y',
				'REFRESH' => 'Y',
				'PREVIEW' => array(
					'URL' => ''
				),
				'SUB_PARAMS' => array(
					'index1' => array(
						'BIG_BANNER_INDEX' => array(
							'TITLE' => GetMessage('BIG_BANNER_INDEX'),
							'TYPE' => 'checkbox',
							'DEFAULT' => 'Y',
							'THEME' => 'Y',
							'ONE_ROW' => 'Y',
							'SMALL_TOGGLE' => 'Y',
							'DRAG' => 'N'
						),
						'FLOAT_BANNERS_INDEX' => array(
							'TITLE' => GetMessage('FLOAT_BANNERS_INDEX'),
							'TYPE' => 'checkbox',
							'DEFAULT' => 'Y',
							'THEME' => 'Y',
							'ONE_ROW' => 'Y',
							'SMALL_TOGGLE' => 'Y',
							'TEMPLATE' => array(
								'TITLE' => GetMessage('FLOAT_BANNERS_THEME_TITLE'),
								'TYPE' => 'selectbox',
								'IS_ROW' => 'Y',
								'LIST' => array(
									'front-services_1' => array(
										'TITLE' => GetMessage('FLOAT_BANNERS_THEME1'),
										'IMG' => '/bitrix/images/'.$solution.'/themes/services1.png',
										'ROW_CLASS' => 'col-md-4',
										'POSITION_BLOCK' => 'block',
									),
									'front-services_2' => array(
										'TITLE' => GetMessage('FLOAT_BANNERS_THEME2'),
										'IMG' => '/bitrix/images/'.$solution.'/themes/services2.png',
										'ROW_CLASS' => 'col-md-4',
										'POSITION_BLOCK' => 'block',
									),
									'front-services_3' => array(
										'TITLE' => GetMessage('FLOAT_BANNERS_THEME3'),
										'IMG' => '/bitrix/images/'.$solution.'/themes/services3.png',
										'ROW_CLASS' => 'col-md-4',
										'POSITION_BLOCK' => 'block',
									),
									'front-banners-float' => array(
										'TITLE' => GetMessage('FLOAT_BANNERS_THEME4'),
										'IMG' => '/bitrix/images/'.$solution.'/themes/services4.png',
										'ROW_CLASS' => 'col-md-4',
										'POSITION_BLOCK' => 'block',
									),
								),
								'DEFAULT' => 'front-banners-float',
								'THEME' => 'Y',
								'PREVIEW' => array(
									'URL' => '',
									'SCROLL_BLOCK' => '.float-banners',
								),
							)
						),
						'CATALOG_SECTIONS_INDEX' => array(
							'TITLE' => GetMessage('CATALOG_SECTIONS_INDEX'),
							'TYPE' => 'checkbox',
							'DEFAULT' => 'Y',
							'THEME' => 'Y',
							'ONE_ROW' => 'Y',
							'SMALL_TOGGLE' => 'Y',
						),
						'TEASERS_INDEX' => array(
							'TITLE' => GetMessage('TOP_SERVICES_INDEX'),
							'TYPE' => 'checkbox',
							'DEFAULT' => 'Y',
							'THEME' => 'Y',
							'ONE_ROW' => 'Y',
							'SMALL_TOGGLE' => 'Y',
						),
						'NEWS_INDEX' => array(
							'TITLE' => GetMessage('NEWS_INDEX'),
							'TYPE' => 'checkbox',
							'DEFAULT' => 'Y',
							'THEME' => 'Y',
							'ONE_ROW' => 'Y',
							'SMALL_TOGGLE' => 'Y',
						),
						'BLOG_INDEX' => array(
							'TITLE' => GetMessage('BLOG_INDEX'),
							'TYPE' => 'checkbox',
							'DEFAULT' => 'Y',
							'THEME' => 'Y',
							'ONE_ROW' => 'Y',
							'SMALL_TOGGLE' => 'Y',
						),
						'PORTFOLIO_INDEX' => array(
							'TITLE' => GetMessage('PORTFOLIO_INDEX'),
							'TYPE' => 'checkbox',
							'DEFAULT' => 'N',
							'THEME' => 'Y',
							'ONE_ROW' => 'Y',
							'SMALL_TOGGLE' => 'Y',
							'TEMPLATE' => array(
								'TITLE' => GetMessage('PORTFOLIO_THEME_TITLE'),
								'TYPE' => 'selectbox',
								// 'IS_ROW' => 'Y',
								'LIST' => array(
									'front-projects_1' => array(
										'TITLE' => GetMessage('PORTFOLIO_THEME1'),
										/*'IMG' => '/bitrix/images/'.$solution.'/themes/service_image_sections.png',
										'ROW_CLASS' => 'col-md-4',
										'POSITION_BLOCK' => 'block',*/
									),
									'front-projects_2' => array(
										'TITLE' => GetMessage('PORTFOLIO_THEME2'),
										/*'IMG' => '/bitrix/images/'.$solution.'/themes/service_image_sections.png',
										'ROW_CLASS' => 'col-md-4',
										'POSITION_BLOCK' => 'block',*/
									),
								),
								'DEFAULT' => 'front-projects_1',
								'THEME' => 'Y',
								'PREVIEW' => array(
									'URL' => '',
									'SCROLL_BLOCK' => '.PORTFOLIO_INDEX .portfolio',
								),
							),
						),
						'CATALOG_INDEX' => array(
							'TITLE' => GetMessage('CATALOG_INDEX'),
							'TYPE' => 'checkbox',
							'DEFAULT' => 'N',
							'THEME' => 'Y',
							'ONE_ROW' => 'Y',
							'SMALL_TOGGLE' => 'Y',
						),
						'REVIEWS_INDEX' => array(
							'TITLE' => GetMessage('REVIEWS_INDEX'),
							'TYPE' => 'checkbox',
							'DEFAULT' => 'Y',
							'THEME' => 'Y',
							'ONE_ROW' => 'Y',
							'SMALL_TOGGLE' => 'Y',
						),
						'INSTAGRAMM_INDEX' => array(
							'TITLE' => GetMessage('INSTAGRAMM_INDEX'),
							'TYPE' => 'checkbox',
							'DEFAULT' => 'N',
							'THEME' => 'Y',
							'ONE_ROW' => 'Y',
							'SMALL_TOGGLE' => 'Y',
						),
						
						'COMPANY_INDEX' => array(
							'TITLE' => GetMessage('COMPANY_INDEX'),
							'TYPE' => 'checkbox',
							'DEFAULT' => 'Y',
							'THEME' => 'Y',
							'ONE_ROW' => 'Y',
							'SMALL_TOGGLE' => 'Y',
						),
						'PARTNERS_INDEX' => array(
							'TITLE' => GetMessage('PARTNERS_INDEX'),
							'TYPE' => 'checkbox',
							'DEFAULT' => 'Y',
							'THEME' => 'Y',
							'ONE_ROW' => 'Y',
							'SMALL_TOGGLE' => 'Y',
						),
						'MAP_INDEX' => array(
							'TITLE' => GetMessage('MAP_INDEX'),
							'TYPE' => 'checkbox',
							'DEFAULT' => 'N',
							'THEME' => 'Y',
							'ONE_ROW' => 'Y',
							'SMALL_TOGGLE' => 'Y',
						),
					),
					'index2' => array(
						'BIG_BANNER_INDEX' => array(
							'TITLE' => GetMessage('BIG_BANNER_INDEX'),
							'TYPE' => 'checkbox',
							'DEFAULT' => 'Y',
							'THEME' => 'Y',
							'ONE_ROW' => 'Y',
							'SMALL_TOGGLE' => 'Y',
						),
						'PORTFOLIO_INDEX' => array(
							'TITLE' => GetMessage('PORTFOLIO_INDEX'),
							'TYPE' => 'checkbox',
							'DEFAULT' => 'Y',
							'THEME' => 'Y',
							'ONE_ROW' => 'Y',
							'SMALL_TOGGLE' => 'Y',
							'TEMPLATE' => array(
								'TITLE' => GetMessage('PORTFOLIO_THEME_TITLE'),
								'TYPE' => 'selectbox',
								// 'IS_ROW' => 'Y',
								'LIST' => array(
									'front-projects_1' => array(
										'TITLE' => GetMessage('PORTFOLIO_THEME1'),
										/*'IMG' => '/bitrix/images/'.$solution.'/themes/service_image_sections.png',
										'ROW_CLASS' => 'col-md-4',
										'POSITION_BLOCK' => 'block',*/
									),
									'front-projects_2' => array(
										'TITLE' => GetMessage('PORTFOLIO_THEME2'),
										/*'IMG' => '/bitrix/images/'.$solution.'/themes/service_image_sections.png',
										'ROW_CLASS' => 'col-md-4',
										'POSITION_BLOCK' => 'block',*/
									),
								),
								'DEFAULT' => 'front-projects_2',
								'THEME' => 'Y',
								'PREVIEW' => array(
									'URL' => '',
									'SCROLL_BLOCK' => '.PORTFOLIO_INDEX .portfolio',
								),
							),
						),
						'CATALOG_SECTIONS_INDEX' => array(
							'TITLE' => GetMessage('CATALOG_SECTIONS_INDEX'),
							'TYPE' => 'checkbox',
							'DEFAULT' => 'N',
							'THEME' => 'Y',
							'ONE_ROW' => 'Y',
							'SMALL_TOGGLE' => 'Y',
						),
						'TEASERS_INDEX' => array(
							'TITLE' => GetMessage('TOP_SERVICES_INDEX'),
							'TYPE' => 'checkbox',
							'DEFAULT' => 'N',
							'THEME' => 'Y',
							'ONE_ROW' => 'Y',
							'SMALL_TOGGLE' => 'Y',
						),
						'NEWS_INDEX' => array(
							'TITLE' => GetMessage('NEWS_INDEX'),
							'TYPE' => 'checkbox',
							'DEFAULT' => 'N',
							'THEME' => 'Y',
							'ONE_ROW' => 'Y',
							'SMALL_TOGGLE' => 'Y',
						),
						'BLOG_INDEX' => array(
							'TITLE' => GetMessage('BLOG_INDEX'),
							'TYPE' => 'checkbox',
							'DEFAULT' => 'N',
							'THEME' => 'Y',
							'ONE_ROW' => 'Y',
							'SMALL_TOGGLE' => 'Y',
						),
						'CATALOG_INDEX' => array(
							'TITLE' => GetMessage('CATALOG_INDEX'),
							'TYPE' => 'checkbox',
							'DEFAULT' => 'Y',
							'THEME' => 'Y',
							'ONE_ROW' => 'Y',
							'SMALL_TOGGLE' => 'Y',
						),
						'FLOAT_BANNERS_INDEX' => array(
							'TITLE' => GetMessage('FLOAT_BANNERS_INDEX'),
							'TYPE' => 'checkbox',
							'DEFAULT' => 'N',
							'THEME' => 'Y',
							'ONE_ROW' => 'Y',
							'SMALL_TOGGLE' => 'Y',
							'TEMPLATE' => array(
								'TITLE' => GetMessage('FLOAT_BANNERS_THEME_TITLE'),
								'TYPE' => 'selectbox',
								'IS_ROW' => 'Y',
								'LIST' => array(
									'front-services_1' => array(
										'TITLE' => GetMessage('FLOAT_BANNERS_THEME1'),
										'IMG' => '/bitrix/images/'.$solution.'/themes/services1.png',
										'ROW_CLASS' => 'col-md-4',
										'POSITION_BLOCK' => 'block',
									),
									'front-services_2' => array(
										'TITLE' => GetMessage('FLOAT_BANNERS_THEME2'),
										'IMG' => '/bitrix/images/'.$solution.'/themes/services2.png',
										'ROW_CLASS' => 'col-md-4',
										'POSITION_BLOCK' => 'block',
									),
									'front-services_3' => array(
										'TITLE' => GetMessage('FLOAT_BANNERS_THEME3'),
										'IMG' => '/bitrix/images/'.$solution.'/themes/services3.png',
										'ROW_CLASS' => 'col-md-4',
										'POSITION_BLOCK' => 'block',
									),
									'front-banners-float' => array(
										'TITLE' => GetMessage('FLOAT_BANNERS_THEME4'),
										'IMG' => '/bitrix/images/'.$solution.'/themes/services4.png',
										'ROW_CLASS' => 'col-md-4',
										'POSITION_BLOCK' => 'block',
									),
								),
								'DEFAULT' => 'front-services_3',
								'THEME' => 'Y',
								'PREVIEW' => array(
									'URL' => '',
									'SCROLL_BLOCK' => '.float-banners',
								),
							)
						),
						'REVIEWS_INDEX' => array(
							'TITLE' => GetMessage('REVIEWS_INDEX'),
							'TYPE' => 'checkbox',
							'DEFAULT' => 'Y',
							'THEME' => 'Y',
							'ONE_ROW' => 'Y',
							'SMALL_TOGGLE' => 'Y',
						),
						'INSTAGRAMM_INDEX' => array(
							'TITLE' => GetMessage('INSTAGRAMM_INDEX'),
							'TYPE' => 'checkbox',
							'DEFAULT' => 'Y',
							'THEME' => 'Y',
							'ONE_ROW' => 'Y',
							'SMALL_TOGGLE' => 'Y',
						),
						
						'COMPANY_INDEX' => array(
							'TITLE' => GetMessage('COMPANY_INDEX'),
							'TYPE' => 'checkbox',
							'DEFAULT' => 'N',
							'THEME' => 'Y',
							'ONE_ROW' => 'Y',
							'SMALL_TOGGLE' => 'Y',
						),
						'PARTNERS_INDEX' => array(
							'TITLE' => GetMessage('PARTNERS_INDEX'),
							'TYPE' => 'checkbox',
							'DEFAULT' => 'N',
							'THEME' => 'Y',
							'ONE_ROW' => 'Y',
							'SMALL_TOGGLE' => 'Y',
						),
						'MAP_INDEX' => array(
							'TITLE' => GetMessage('MAP_INDEX'),
							'TYPE' => 'checkbox',
							'DEFAULT' => 'N',
							'THEME' => 'Y',
							'ONE_ROW' => 'Y',
							'SMALL_TOGGLE' => 'Y',
						),
					),
					'index3' => array(
						'BIG_BANNER_INDEX' => array(
							'TITLE' => GetMessage('BIG_BANNER_INDEX'),
							'TYPE' => 'checkbox',
							'DEFAULT' => 'Y',
							'THEME' => 'Y',
							'ONE_ROW' => 'Y',
							'SMALL_TOGGLE' => 'Y',
							'DRAG' => 'N'
						),
						'TEASERS_INDEX' => array(
							'TITLE' => GetMessage('TOP_SERVICES_INDEX'),
							'TYPE' => 'checkbox',
							'DEFAULT' => 'Y',
							'THEME' => 'Y',
							'ONE_ROW' => 'Y',
							'SMALL_TOGGLE' => 'Y',
						),
						'FLOAT_BANNERS_INDEX' => array(
							'TITLE' => GetMessage('FLOAT_BANNERS_INDEX'),
							'TYPE' => 'checkbox',
							'DEFAULT' => 'Y',
							'THEME' => 'Y',
							'ONE_ROW' => 'Y',
							'SMALL_TOGGLE' => 'Y',
							'TEMPLATE' => array(
								'TITLE' => GetMessage('FLOAT_BANNERS_THEME_TITLE'),
								'TYPE' => 'selectbox',
								'IS_ROW' => 'Y',
								'LIST' => array(
									'front-services_1' => array(
										'TITLE' => GetMessage('FLOAT_BANNERS_THEME1'),
										'IMG' => '/bitrix/images/'.$solution.'/themes/services1.png',
										'ROW_CLASS' => 'col-md-4',
										'POSITION_BLOCK' => 'block',
									),
									'front-services_2' => array(
										'TITLE' => GetMessage('FLOAT_BANNERS_THEME2'),
										'IMG' => '/bitrix/images/'.$solution.'/themes/services2.png',
										'ROW_CLASS' => 'col-md-4',
										'POSITION_BLOCK' => 'block',
									),
									'front-services_3' => array(
										'TITLE' => GetMessage('FLOAT_BANNERS_THEME3'),
										'IMG' => '/bitrix/images/'.$solution.'/themes/services3.png',
										'ROW_CLASS' => 'col-md-4',
										'POSITION_BLOCK' => 'block',
									),
									'front-banners-float' => array(
										'TITLE' => GetMessage('FLOAT_BANNERS_THEME4'),
										'IMG' => '/bitrix/images/'.$solution.'/themes/services4.png',
										'ROW_CLASS' => 'col-md-4',
										'POSITION_BLOCK' => 'block',
									),
								),
								'DEFAULT' => 'front-services_2',
								'THEME' => 'Y',
								'PREVIEW' => array(
									'URL' => '',
									'SCROLL_BLOCK' => '.float-banners',
								),
							),
						),
						'COMPANY_INDEX' => array(
							'TITLE' => GetMessage('COMPANY_INDEX'),
							'TYPE' => 'checkbox',
							'DEFAULT' => 'N',
							'THEME' => 'Y',
							'ONE_ROW' => 'Y',
							'SMALL_TOGGLE' => 'Y',
						),
						'CATALOG_INDEX' => array(
							'TITLE' => GetMessage('CATALOG_INDEX'),
							'TYPE' => 'checkbox',
							'DEFAULT' => 'Y',
							'THEME' => 'Y',
							'ONE_ROW' => 'Y',
							'SMALL_TOGGLE' => 'Y',
						),
						'PORTFOLIO_INDEX' => array(
							'TITLE' => GetMessage('PORTFOLIO_INDEX'),
							'TYPE' => 'checkbox',
							'DEFAULT' => 'Y',
							'THEME' => 'Y',
							'ONE_ROW' => 'Y',
							'SMALL_TOGGLE' => 'Y',
							'TEMPLATE' => array(
								'TITLE' => GetMessage('PORTFOLIO_THEME_TITLE'),
								'TYPE' => 'selectbox',
								// 'IS_ROW' => 'Y',
								'LIST' => array(
									'front-projects_1' => array(
										'TITLE' => GetMessage('PORTFOLIO_THEME1'),
										/*'IMG' => '/bitrix/images/'.$solution.'/themes/service_image_sections.png',
										'ROW_CLASS' => 'col-md-4',
										'POSITION_BLOCK' => 'block',*/
									),
									'front-projects_2' => array(
										'TITLE' => GetMessage('PORTFOLIO_THEME2'),
										/*'IMG' => '/bitrix/images/'.$solution.'/themes/service_image_sections.png',
										'ROW_CLASS' => 'col-md-4',
										'POSITION_BLOCK' => 'block',*/
									),
								),
								'DEFAULT' => 'front-projects_2',
								'THEME' => 'Y',
								'PREVIEW' => array(
									'URL' => '',
									'SCROLL_BLOCK' => '.PORTFOLIO_INDEX .portfolio',
								),
							),
						),
						'PARTNERS_INDEX' => array(
							'TITLE' => GetMessage('PARTNERS_INDEX'),
							'TYPE' => 'checkbox',
							'DEFAULT' => 'N',
							'THEME' => 'N',
							'ONE_ROW' => 'Y',
							'SMALL_TOGGLE' => 'Y',
						),
						'MAP_INDEX' => array(
							'TITLE' => GetMessage('MAP_INDEX'),
							'TYPE' => 'checkbox',
							'DEFAULT' => 'N',
							'THEME' => 'Y',
							'ONE_ROW' => 'Y',
							'SMALL_TOGGLE' => 'Y',
						),
					),
					'index4' => array(
						'BIG_BANNER_INDEX' => array(
							'TITLE' => GetMessage('BIG_BANNER_INDEX'),
							'TYPE' => 'checkbox',
							'DEFAULT' => 'Y',
							'THEME' => 'Y',
							'ONE_ROW' => 'Y',
							'SMALL_TOGGLE' => 'Y',
						),
						'TEASERS_INDEX' => array(
							'TITLE' => GetMessage('TOP_SERVICES_INDEX'),
							'TYPE' => 'checkbox',
							'DEFAULT' => 'Y',
							'THEME' => 'Y',
							'ONE_ROW' => 'Y',
							'SMALL_TOGGLE' => 'Y',
						),
						/*'SERVICES_INDEX' => array(
							'TITLE' => GetMessage('TEASERS_INDEX'),
							'TYPE' => 'checkbox',
							'DEFAULT' => 'Y',
							'THEME' => 'Y',
							'ONE_ROW' => 'Y',
							'SMALL_TOGGLE' => 'Y',
						),*/
						'PORTFOLIO_INDEX' => array(
							'TITLE' => GetMessage('PORTFOLIO_INDEX'),
							'TYPE' => 'checkbox',
							'DEFAULT' => 'Y',
							'THEME' => 'Y',
							'ONE_ROW' => 'Y',
							'SMALL_TOGGLE' => 'Y',
							'TEMPLATE' => array(
								'TITLE' => GetMessage('PORTFOLIO_THEME_TITLE'),
								'TYPE' => 'selectbox',
								// 'IS_ROW' => 'Y',
								'LIST' => array(
									'front-projects_1' => array(
										'TITLE' => GetMessage('PORTFOLIO_THEME1'),
										/*'IMG' => '/bitrix/images/'.$solution.'/themes/service_image_sections.png',
										'ROW_CLASS' => 'col-md-4',
										'POSITION_BLOCK' => 'block',*/
									),
									'front-projects_2' => array(
										'TITLE' => GetMessage('PORTFOLIO_THEME2'),
										/*'IMG' => '/bitrix/images/'.$solution.'/themes/service_image_sections.png',
										'ROW_CLASS' => 'col-md-4',
										'POSITION_BLOCK' => 'block',*/
									),
								),
								'DEFAULT' => 'front-projects_1',
								'THEME' => 'Y',
								'PREVIEW' => array(
									'URL' => '',
									'SCROLL_BLOCK' => '.PORTFOLIO_INDEX .portfolio',
								),
							),
						),
						'CATALOG_SECTIONS_INDEX' => array(
							'TITLE' => GetMessage('CATALOG_SECTIONS_INDEX'),
							'TYPE' => 'checkbox',
							'DEFAULT' => 'Y',
							'THEME' => 'Y',
							'ONE_ROW' => 'Y',
							'SMALL_TOGGLE' => 'Y',
						),
						'FLOAT_BANNERS_INDEX' => array(
							'TITLE' => GetMessage('FLOAT_BANNERS_INDEX'),
							'TYPE' => 'checkbox',
							'DEFAULT' => 'Y',
							'THEME' => 'Y',
							'ONE_ROW' => 'Y',
							'SMALL_TOGGLE' => 'Y',
							'TEMPLATE' => array(
								'TITLE' => GetMessage('FLOAT_BANNERS_THEME_TITLE'),
								'TYPE' => 'selectbox',
								'IS_ROW' => 'Y',
								'LIST' => array(
									'front-services_1' => array(
										'TITLE' => GetMessage('FLOAT_BANNERS_THEME1'),
										'IMG' => '/bitrix/images/'.$solution.'/themes/services1.png',
										'ROW_CLASS' => 'col-md-4',
										'POSITION_BLOCK' => 'block',
									),
									'front-services_2' => array(
										'TITLE' => GetMessage('FLOAT_BANNERS_THEME2'),
										'IMG' => '/bitrix/images/'.$solution.'/themes/services2.png',
										'ROW_CLASS' => 'col-md-4',
										'POSITION_BLOCK' => 'block',
									),
									'front-services_3' => array(
										'TITLE' => GetMessage('FLOAT_BANNERS_THEME3'),
										'IMG' => '/bitrix/images/'.$solution.'/themes/services3.png',
										'ROW_CLASS' => 'col-md-4',
										'POSITION_BLOCK' => 'block',
									),
									'front-banners-float' => array(
										'TITLE' => GetMessage('FLOAT_BANNERS_THEME4'),
										'IMG' => '/bitrix/images/'.$solution.'/themes/services4.png',
										'ROW_CLASS' => 'col-md-4',
										'POSITION_BLOCK' => 'block',
									),
								),
								'DEFAULT' => 'front-services_1',
								'THEME' => 'Y',
								'PREVIEW' => array(
									'URL' => '',
									'SCROLL_BLOCK' => '.float-banners',
								),
							)
						),
						'CATALOG_INDEX' => array(
							'TITLE' => GetMessage('CATALOG_INDEX'),
							'TYPE' => 'checkbox',
							'DEFAULT' => 'Y',
							'THEME' => 'Y',
							'ONE_ROW' => 'Y',
							'SMALL_TOGGLE' => 'Y',
						),
						'PARTNERS_INDEX' => array(
							'TITLE' => GetMessage('PARTNERS_INDEX'),
							'TYPE' => 'checkbox',
							'DEFAULT' => 'Y',
							'THEME' => 'Y',
							'ONE_ROW' => 'Y',
							'SMALL_TOGGLE' => 'Y',
						),
						'INSTAGRAMM_INDEX' => array(
							'TITLE' => GetMessage('INSTAGRAMM_INDEX'),
							'TYPE' => 'checkbox',
							'DEFAULT' => 'Y',
							'THEME' => 'Y',
							'ONE_ROW' => 'Y',
							'SMALL_TOGGLE' => 'Y',
						),
						'NEWS_INDEX' => array(
							'TITLE' => GetMessage('NEWS_INDEX'),
							'TYPE' => 'checkbox',
							'DEFAULT' => 'Y',
							'THEME' => 'Y',
							'ONE_ROW' => 'Y',
							'SMALL_TOGGLE' => 'Y',
						),
						'REVIEWS_INDEX' => array(
							'TITLE' => GetMessage('REVIEWS_INDEX'),
							'TYPE' => 'checkbox',
							'DEFAULT' => 'Y',
							'THEME' => 'Y',
							'ONE_ROW' => 'Y',
							'SMALL_TOGGLE' => 'Y',
						),
						'BLOG_INDEX' => array(
							'TITLE' => GetMessage('BLOG_INDEX'),
							'TYPE' => 'checkbox',
							'DEFAULT' => 'Y',
							'THEME' => 'Y',
							'ONE_ROW' => 'Y',
							'SMALL_TOGGLE' => 'Y',
						),
						'COMPANY_INDEX' => array(
							'TITLE' => GetMessage('COMPANY_INDEX'),
							'TYPE' => 'checkbox',
							'DEFAULT' => 'Y',
							'THEME' => 'Y',
							'ONE_ROW' => 'Y',
							'SMALL_TOGGLE' => 'Y',
						),
						'MAP_INDEX' => array(
							'TITLE' => GetMessage('MAP_INDEX'),
							'TYPE' => 'checkbox',
							'DEFAULT' => 'Y',
							'THEME' => 'Y',
							'ONE_ROW' => 'Y',
							'SMALL_TOGGLE' => 'Y',
						),
					),
				)
			),
		),
	),
	'GOOGLE_RECAPTCHA' => array(
		'TITLE' => GetMessage('GOOGLE_RECAPTCHA'),
		'OPTIONS' => array(
			'USE_GOOGLE_RECAPTCHA' => array(
				'TITLE' => GetMessage('USE_GOOGLE_RECAPTCHA_TITLE'),
				'TYPE' => 'checkbox',
				'DEFAULT' => 'N',
				'THEME' => 'N',
			),
			'GOOGLE_RECAPTCHA_PUBLIC_KEY' => array(
				'TITLE' => GetMessage('GOOGLE_RECAPTCHA_PUBLIC_KEY_TITLE'),
				'TYPE' => 'text',
				'SIZE' => '75',
				'DEFAULT' => '',
				'THEME' => 'N',
			),
			'GOOGLE_RECAPTCHA_PRIVATE_KEY' => array(
				'TITLE' => GetMessage('GOOGLE_RECAPTCHA_PRIVATE_KEY_TITLE'),
				'TYPE' => 'text',
				'SIZE' => '75',
				'DEFAULT' => '',
				'THEME' => 'N',
			),
			'GOOGLE_RECAPTCHA_MASK_PAGE' => array(
				'TITLE' => GetMessage('GOOGLE_RECAPTCHA_MASK_PAGE_TITLE'),
				'TYPE' => 'textarea',
				'ROWS' => '5',
				'COLS' => '77',
				'DEFAULT' => '',
				'THEME' => 'N',
			),
			'GOOGLE_RECAPTCHA_COLOR' => array(
				'TITLE' => GetMessage('GOOGLE_RECAPTCHA_COLOR_TITLE'),
				'TYPE' => 'selectbox',
				'LIST' => array(
					'DARK' => GetMessage('GOOGLE_RECAPTCHA_COLOR_DARK_TITLE'),
					'LIGHT' => GetMessage('GOOGLE_RECAPTCHA_COLOR_LIGHT_TITLE'),
				),
				'DEFAULT' => 'LIGHT',
				'THEME' => 'N',
			),
			'GOOGLE_RECAPTCHA_SIZE' => array(
				'TITLE' => GetMessage('GOOGLE_RECAPTCHA_SIZE_TITLE'),
				'TYPE' => 'selectbox',
				'LIST' => array(
					'NORMAL' => GetMessage('GOOGLE_RECAPTCHA_SIZE_NORMAL_TITLE'),
					'COMPACT' => GetMessage('GOOGLE_RECAPTCHA_SIZE_COMPACT_TITLE'),
					'INVISIBLE' => GetMessage('GOOGLE_RECAPTCHA_SIZE_INVISIBLE_TITLE'),
				),
				'DEFAULT' => 'NORMAL',
				'THEME' => 'N',
			),
			'GOOGLE_RECAPTCHA_SHOW_LOGO' => array(
				'TITLE' => GetMessage('GOOGLE_RECAPTCHA_SHOW_LOGO_TITLE'),
				'TYPE' => 'checkbox',
				'DEFAULT' => 'Y',
				'THEME' => 'N',
			),
			'GOOGLE_RECAPTCHA_BADGE' => array(
				'TITLE' => GetMessage('GOOGLE_RECAPTCHA_BADGE_TITLE'),
				'TYPE' => 'selectbox',
				'LIST' => array(
					'BOTTOMRIGHT' => GetMessage('GOOGLE_RECAPTCHA_BADGE_BOTTOMRIGHT_TITLE'),
					'BOTTOMLEFT' => GetMessage('GOOGLE_RECAPTCHA_BADGE_BOTTOMLEFT_TITLE'),
					'INLINE' => GetMessage('GOOGLE_RECAPTCHA_BADGE_INLINE_TITLE'),
				),
				'DEFAULT' => 'BOTTOMRIGHT',
				'THEME' => 'N',
			),
			'GOOGLE_RECAPTCHA_NOTE' => array(
				'TYPE' => 'note',
				'TITLE' => GetMessage('GOOGLE_RECAPTCHA_NOTE_TEXT'),
				'THEME' => 'N',
			),
		),
	),
	'FORMS' => array(
		'TITLE' => GetMessage('FORMS_OPTIONS'),
		'OPTIONS' => array(
			'CAPTCHA_FORM_TYPE' => array(
				'TITLE' => GetMessage('CAPTCHA_FORM_TYPE'),
				'TYPE' => 'checkbox',
				'DEFAULT' => 'N',
				'THEME' => 'N',
			),
			'PHONE_MASK' => array(
				'TITLE' => GetMessage('PHONE_MASK'),
				'TYPE' => 'text',
				'DEFAULT' => '+7 (999) 999-99-99',
				'THEME' => 'N',
			),
			'VALIDATE_PHONE_MASK' => array(
				'TITLE' => GetMessage('VALIDATE_PHONE_MASK'),
				'TYPE' => 'text',
				'DEFAULT' => '^[+][0-9] [(][0-9]{3}[)] [0-9]{3}[-][0-9]{2}[-][0-9]{2}$',
				'THEME' => 'N',
			),
			'DATE_FORMAT' => array(
				'TITLE' => GetMessage('DATE_FORMAT'),
				'TYPE' => 'selectbox',
				'LIST' => array(
					'DOT' => GetMessage('DATE_FORMAT_DOT'),
					'HYPHEN' => GetMessage('DATE_FORMAT_HYPHEN'),
					'SPACE' => GetMessage('DATE_FORMAT_SPACE'),
					'SLASH' => GetMessage('DATE_FORMAT_SLASH'),
					'COLON' => GetMessage('DATE_FORMAT_COLON'),
				),
				'DEFAULT' => 'DOT',
				'THEME' => 'N',
			),
			'VALIDATE_FILE_EXT' => array(
				'TITLE' => GetMessage('VALIDATE_FILE_EXT'),
				'TYPE' => 'text',
				'DEFAULT' => 'png|jpg|jpeg|gif|doc|docx|xls|xlsx|txt|pdf|odt|rtf',
				'THEME' => 'N',
			),
		),
	),
	'SOCIAL' => array(
		'TITLE' => GetMessage('SOCIAL_OPTIONS'),
		'OPTIONS' => array(
			'SOCIAL_VK' => array(
				'TITLE' => GetMessage('SOCIAL_VK'),
				'TYPE' => 'text',
				'DEFAULT' => '',
				'THEME' => 'N',
			),
			'SOCIAL_FACEBOOK' => array(
				'TITLE' => GetMessage('SOCIAL_FACEBOOK'),
				'TYPE' => 'text',
				'DEFAULT' => '',
				'THEME' => 'N',
			),
			'SOCIAL_TWITTER' =>	array(
				'TITLE' => GetMessage('SOCIAL_TWITTER'),
				'TYPE' => 'text',
				'DEFAULT' => '',
				'THEME' => 'N',
			),
			'SOCIAL_INSTAGRAM' => array(
				'TITLE' => GetMessage('SOCIAL_INSTAGRAM'),
				'TYPE' => 'text',
				'DEFAULT' => '',
				'THEME' => 'N',
			),
			'SOCIAL_TELEGRAM' => array(
				'TITLE' => GetMessage('SOCIAL_TELEGRAM'),
				'TYPE' => 'text',
				'DEFAULT' => '',
				'THEME' => 'N',
			),
			'SOCIAL_YOUTUBE' => array(
				'TITLE' => GetMessage('SOCIAL_YOUTUBE'),
				'TYPE' => 'text',
				'DEFAULT' => '',
				'THEME' => 'N',
			),
			'SOCIAL_ODNOKLASSNIKI' => array(
				'TITLE' => GetMessage('SOCIAL_ODNOKLASSNIKI'),
				'TYPE' => 'text',
				'DEFAULT' => '',
				'THEME' => 'N',
			),
			'SOCIAL_GOOGLEPLUS' => array(
				'TITLE' => GetMessage('SOCIAL_GOOGLEPLUS'),
				'TYPE' => 'text',
				'DEFAULT' => '',
				'THEME' => 'N',
			),
			'SOCIAL_MAIL' => array(
				'TITLE' => GetMessage('SOCIAL_MAILRU'),
				'TYPE' => 'text',
				'DEFAULT' => '',
				'THEME' => 'N',
			),
		),
	),
	'HEADER' => array(
		'TITLE' => GetMessage('HEADER_OPTIONS'),
		'THEME' => 'Y',
		'OPTIONS' => array(
			'TOP_MENU_FIXED' => array(
				'TITLE' => GetMessage('TOP_MENU_FIXED'),
				'TYPE' => 'checkbox',
				'DEFAULT' => 'Y',
				'THEME' => 'Y',
				'ONE_ROW' => 'Y',
				'DEPENDENT_PARAMS' => array(
					'HEADER_FIXED' => array(
						'TITLE' => GetMessage('HEADER_FIXED'),
						'HIDE_TITLE' => 'Y',
						'TYPE' => 'selectbox',
						'LIST' => array(
							'1' => array(
								'IMG' => '/bitrix/images/'.$solution.'/themes/fixed_header1.png',
								'TITLE' => '1',
								'POSITION_BLOCK' => 'block',
								'POSITION_TITLE' => 'left',
							),
							'2' => array(
								'IMG' => '/bitrix/images/'.$solution.'/themes/fixed_header2.png',
								'TITLE' => '2',
								'POSITION_BLOCK' => 'block',
								'POSITION_TITLE' => 'left',
							),
							'custom' => array(
								'TITLE' => 'Custom',
								'POSITION_BLOCK' => 'block',
								'HIDE' => 'Y'
							),
						),
						'CONDITIONAL_VALUE' => 'Y',
						'DEFAULT' => '2',
						'THEME' => 'Y',
					),
				)
			),
			'MENU_COLOR' => array(
				'TITLE' => GetMessage('MENU_COLOR_TITLE'),
				'TYPE' => 'selectbox',
				'LIST' => array(
					'COLORED' => GetMessage('MENU_COLOR_COLORED'),
					'NONE' => array(
						'TITLE' => GetMessage('MENU_COLOR_NONE'),
						/*'DEPEND' => array(
							'TYPE' => 'INDEX_TYPE',
							'VALUE' => array('index1', 'index_custom')
						)*/
					),
					'LIGHT' => GetMessage('MENU_COLOR_LIGHT'),
					'DARK' => GetMessage('MENU_COLOR_DARK'),
				),
				'DEFAULT' => 'NONE',
				'THEME' => 'Y',
			),
			'HEADER_TYPE' => array(
				'TITLE' => GetMessage('HEADER_TYPE'),
				'TYPE' => 'selectbox',
				'LIST' => array(
					'1' => array(
						'IMG' => '/bitrix/images/'.$solution.'/themes/header1.png',
						'TITLE' => '1',
						'POSITION_BLOCK' => 'block',
						'POSITION_TITLE' => 'left',
					),
					'2' => array(
						'IMG' => '/bitrix/images/'.$solution.'/themes/header2.png',
						'TITLE' => '2',
						'POSITION_BLOCK' => 'block',
						'POSITION_TITLE' => 'left',
					),
					'3' => array(
						'IMG' => '/bitrix/images/'.$solution.'/themes/header3.png',
						'TITLE' => '3',
						'POSITION_BLOCK' => 'block',
						'POSITION_TITLE' => 'left',
					),
					'4' => array(
						'IMG' => '/bitrix/images/'.$solution.'/themes/header4.png',
						'TITLE' => '4',
						'POSITION_BLOCK' => 'block',
						'POSITION_TITLE' => 'left',
					),
					'5' => array(
						'IMG' => '/bitrix/images/'.$solution.'/themes/header5.png',
						'TITLE' => '5',
						'POSITION_BLOCK' => 'block',
						'POSITION_TITLE' => 'left',
					),
					'6' => array(
						'IMG' => '/bitrix/images/'.$solution.'/themes/header6.png',
						'TITLE' => '6',
						'POSITION_BLOCK' => 'block',
						'POSITION_TITLE' => 'left',
					),
					'7' => array(
						'IMG' => '/bitrix/images/'.$solution.'/themes/header7.png',
						'TITLE' => '7',
						'POSITION_BLOCK' => 'block',
						'POSITION_TITLE' => 'left',
					),
					'8' => array(
						'IMG' => '/bitrix/images/'.$solution.'/themes/header8.png',
						'TITLE' => '8',
						'POSITION_BLOCK' => 'block',
						'POSITION_TITLE' => 'left',
					),
					'9' => array(
						'IMG' => '/bitrix/images/'.$solution.'/themes/header9.png',
						'TITLE' => '9',
						'POSITION_BLOCK' => 'block',
						'POSITION_TITLE' => 'left',
					),
					'10' => array(
						'IMG' => '/bitrix/images/'.$solution.'/themes/header10.png',
						'TITLE' => '10',
						'POSITION_BLOCK' => 'block',
						'POSITION_TITLE' => 'left',
					),
					'custom' => array(
						'TITLE' => 'Custom',
						'POSITION_BLOCK' => 'block',
						'HIDE' => 'Y'
					),
				),
				'DEFAULT' => '1',
				'THEME' => 'Y',
			),
			'HEADER_PHONES' => array(
				'TITLE' => GetMessage('HEADER_PHONES_OPTIONS_TITLE'),
				'TYPE' => 'array',
				'THEME' => 'N',
				'OPTIONS' => $arContactOptions = array(
					'PHONE_VALUE' => array(
						'TITLE' => GetMessage('HEADER_PHONE_OPTION_VALUE_TITLE'),
						'TYPE' => 'text',
						'DEFAULT' => '',
						'THEME' => 'N',
						'REQUIRED' => 'Y',
					),
				),
			),
		),
	),
	'MENU_PAGE' => array(
		'TITLE' => GetMessage('MENU_PAGE_OPTIONS'),
		'THEME' => 'Y',
		'OPTIONS' => array(

			'SIDE_MENU' => array(
				'TITLE' => GetMessage('SIDE_MENU'),
				'TYPE' => 'selectbox',
				'LIST' => array(
					'LEFT' => GetMessage('SIDE_MENU_LEFT'),
					'RIGHT' => GetMessage('SIDE_MENU_RIGHT'),
				),
				'DEFAULT' => 'LEFT',
				'THEME' => 'Y',
			),
			'VIEW_TYPE_LEFT_BLOCK' => array(
				'TITLE' => GetMessage('VIEW_TYPE_LEFT_BLOCK_TITLE'),
				'TYPE' => 'selectbox',
				'LIST' => array(
					'normal' => GetMessage('VIEW_TYPE_LEFT_BLOCK_NORMAL'),
					'with_tizers' => GetMessage('VIEW_TYPE_LEFT_BLOCK_WITH_TIZERS'),
					'full' => GetMessage('VIEW_TYPE_LEFT_BLOCK_FULL'),
					'custom' => array(
						'TITLE' => 'Custom',
						'HIDE' => 'Y'
					),
				),
				'DEFAULT' => 'normal',
				'THEME' => 'Y',
			),
			'MAX_VISIBLE_ITEMS_MENU' => array(
				'TITLE' => GetMessage('MAX_VISIBLE_ITEMS_MENU_TITLE'),
				'TYPE' => 'text',
				'DEFAULT' => '10',
				'THEME' => 'N',
			),
			'SHOW_CATALOG_SECTIONS_ICONS' => array(
				'TITLE' => GetMessage('SHOW_CATALOG_SECTIONS_ICONS_TITLE'),
				'TYPE' => 'checkbox',
				'DEFAULT' => 'Y',
				'THEME' => 'Y',
			),
			'COUNT_ITEMS_IN_LINE_MENU' => array(
				'TITLE' => GetMessage('COUNT_ITEMS_IN_LINE_MENU_TITLE'),
				'TYPE' => 'selectbox',
				'IS_ROW' => 'Y',
				'LIST' => array(
					2 => array(
						'TITLE' => GetMessage("VIEW_TYPE_ITEM_2"),
						'IMG' => '/bitrix/images/'.$solution.'/themes/dropdown_columns2.png',
						'ROW_CLASS' => 'col-md-4',
						'POSITION_BLOCK' => 'block'
					),
					3 => array(
						'TITLE' => GetMessage("VIEW_TYPE_ITEM_3"),
						'IMG' => '/bitrix/images/'.$solution.'/themes/dropdown_columns3.png',
						'ROW_CLASS' => 'col-md-4',
						'POSITION_BLOCK' => 'block'
					),
					4 => array(
						'TITLE' => GetMessage("VIEW_TYPE_ITEM_4"),
						'IMG' => '/bitrix/images/'.$solution.'/themes/dropdown_columns4.png',
						'ROW_CLASS' => 'col-md-4',
						'POSITION_BLOCK' => 'block'
					),
				),
				'DEFAULT' => 4,
				'THEME' => 'Y',
			),
			
		)
	),
	'REGIONALITY_PAGE' => array(
		'TITLE' => GetMessage('REGIONALITY_PAGE_OPTIONS'),
		'THEME' => 'Y',
		'OPTIONS' => array(
			'USE_REGIONALITY' => array(
				'TITLE' => GetMessage('USE_REGIONALITY_TITLE'),
				'TYPE' => 'checkbox',
				'DEPENDENT_PARAMS' => array(
					'REGIONALITY_TYPE' => array(
						'TITLE' => GetMessage('REGIONALITY_TYPE_TITLE'),
						'HIDE_TITLE' => 'Y',
						'TYPE' => 'selectbox',
						'LIST' => array(
							'ONE_DOMAIN' => GetMessage('REGIONALITY_TYPE_ONE_DOMAIN'),
							'SUBDOMAIN' => GetMessage('REGIONALITY_TYPE_SUBDOMAIN'),
						),
						'DEFAULT' => 'ONE_DOMAIN',
						'THEME' => 'Y',
						'CONDITIONAL_VALUE' => 'Y',
					),
					'REGIONALITY_VIEW' => array(
						'TITLE' => GetMessage('REGIONALITY_VIEW_TITLE'),
						'TOP_BORDER' => 'Y',
						'TYPE' => 'selectbox',
						'LIST' => array(
							'SELECT' => GetMessage('REGIONALITY_VIEW_SELECT'),
							'POPUP_REGIONS' => GetMessage('REGIONALITY_VIEW_POPUP_EXT'),
							'POPUP_REGIONS_SMALL' => GetMessage('REGIONALITY_VIEW_POPUP_SMALL'),
						),
						'DEFAULT' => 'POPUP_REGIONS',
						'THEME' => 'Y',
						'CONDITIONAL_VALUE' => 'Y',
					),
					'SHOW_REGION_CONTACT' => array(
						'TITLE' => GetMessage('SHOW_REGION_CONTACT_TITLE'),
						'TYPE' => 'checkbox',
						'DEFAULT' => 'N',
						'THEME' => 'N',
						'CONDITIONAL_VALUE' => 'Y',
					),
					'REGIONALITY_FILTER_ITEM' => array(
						'TITLE' => GetMessage('REGIONALITY_FILTER_ITEM_TITLE'),
						'TYPE' => 'checkbox',
						'DEFAULT' => 'N',
						'THEME' => 'N',
						'CONDITIONAL_VALUE' => 'Y',
					),
					'REGIONALITY_FILTER_ITEM_NOTE' => array(
						'NOTE' => GetMessage('REGIONALITY_FILTER_ITEM_NOTE_TEXT'),
						'TYPE' => 'note',
						'DEFAULT' => 'N',
						'THEME' => 'N',
						'CONDITIONAL_VALUE' => 'Y',
					),
					'REGIONALITY_SEARCH_ROW' => array(
						'TITLE' => GetMessage('REGIONALITY_SEARCH_ROW_TITLE'),
						'TYPE' => 'checkbox',
						'DEFAULT' => 'N',
						'THEME' => 'N',
						'CONDITIONAL_VALUE' => 'Y',
					),
					'REGIONALITY_SEARCH_ROW_NOTE' => array(
						'NOTE' => GetMessage('REGIONALITY_SEARCH_ROW_NOTE_TEXT'),
						'TYPE' => 'note',
						'DEFAULT' => 'N',
						'THEME' => 'N',
						'CONDITIONAL_VALUE' => 'Y',
					),
				),
				'DEFAULT' => 'N',
				'THEME' => 'Y',
			),
		)
	),
	'CATALOG_PAGE' => array(
		'TITLE' => GetMessage('CATALOG_PAGE_OPTIONS'),
		'THEME' => 'Y',
		'OPTIONS' => array(
			'ORDER_VIEW' => array(
				'TITLE' => GetMessage('ORDER_VIEW_TITLE'),
				'TYPE' => 'checkbox',
				'DEFAULT' => 'N',
				'THEME' => 'Y',
				'ONE_ROW' => 'Y',
				'DEPENDENT_PARAMS' => array(
					'ORDER_BASKET_VIEW' => array(
						'TITLE' => GetMessage('ORDER_BASKET_VIEW_TITLE'),
						'HIDE_TITLE' => 'Y',
						'TYPE' => 'selectbox',
						'LIST' => array(
							'HEADER' => GetMessage('ORDER_BASKET_VIEW_HEADER_TITLE'),
							'FLY' => GetMessage('ORDER_BASKET_VIEW_FLY_TITLE'),
							'FLY2' => GetMessage('ORDER_BASKET_VIEW_FLY2_TITLE'),
						),
						'DEFAULT' => 'FLY',
						'CONDITIONAL_VALUE' => 'Y',
						'THEME' => 'Y',
					),
					'URL_BASKET_SECTION' => array(
						'TITLE' => GetMessage('URL_BASKET_SECTION_TITLE'),
						'TYPE' => 'text',
						'DEFAULT' => '#SITE_DIR#cart/',
						'CONDITIONAL_VALUE' => 'Y',
						'THEME' => 'N',
					),
					'URL_ORDER_SECTION' => array(
						'TITLE' => GetMessage('URL_ORDER_SECTION_TITLE'),
						'TYPE' => 'text',
						'DEFAULT' => '#SITE_DIR#cart/order/',
						'CONDITIONAL_VALUE' => 'Y',
						'THEME' => 'N',
					),
					'PERSONAL_PAGE_URL' => array(
						'TITLE' => GetMessage('PERSONAL_PAGE_URL_TITLE'),
						'TYPE' => 'text',
						'DEFAULT' => '#SITE_DIR#cabinet/',
						'CONDITIONAL_VALUE' => 'Y',
						'THEME' => 'N',
					),
				)
			),
			'SHOW_SMARTFILTER' => array(
				'TITLE' => GetMessage('SHOW_FILTER_TITLE'),
				'TYPE' => 'checkbox',
				'DEFAULT' => 'Y',
				'THEME' => 'Y',
				'ONE_ROW' => 'Y',
				'DEPENDENT_PARAMS' => array(
					'FILTER_VIEW' => array(
						'TITLE' => GetMessage('M_FILTER_VIEW'),
						'HIDE_TITLE' => 'Y',
						'TYPE' => 'selectbox',
						'LIST' => array(
							'VERTICAL' => GetMessage('M_FILTER_VIEW_VERTICAL'),
							'HORIZONTAL' => GetMessage('M_FILTER_VIEW_HORIZONTAL'),
							// 'NONE' => GetMessage('M_FILTER_VIEW_NONE'),
						),
						'DEFAULT' => 'VERTICAL',
						'CONDITIONAL_VALUE' => 'Y',
						'THEME' => 'Y',
					),
				)
			),
			'SHOW_LEFT_BLOCK' => array(
				'TITLE' => GetMessage('SHOW_LEFT_BLOCK_TITLE'),
				'TYPE' => 'checkbox',
				'DEFAULT' => 'Y',
				'THEME' => 'Y',
			),
			'URL_CATALOG_SECTION' => array(
				'TITLE' => GetMessage('URL_CATALOG_SECTION_TITLE'),
				'TYPE' => 'text',
				'DEFAULT' => '#SITE_DIR#product/',
				'CONDITIONAL_VALUE' => 'Y',
				'THEME' => 'N',
			),
			'SECTIONS_TYPE_VIEW_CATALOG' => array(
				'TITLE' => GetMessage('SECTIONS_LIST_TITLE'),
				'TYPE' => 'selectbox',
				'IS_ROW' => 'Y',
				'LIST' => array(
					'sections_1' => array(
						'TITLE' => GetMessage('PAGE_BLOCK_LIST'),
						'IMG' => '/bitrix/images/'.$solution.'/themes/catalog_sections_1.png',
						'ROW_CLASS' => 'col-md-4',
						'POSITION_BLOCK' => 'block',
					),
					'sections_2' => array(
						'TITLE' => GetMessage('PAGE_BLOCK_LIST2'),
						'IMG' => '/bitrix/images/'.$solution.'/themes/catalog_sections_2.png',
						'ROW_CLASS' => 'col-md-4',
						'POSITION_BLOCK' => 'block',
					),
					'sections_3' => array(
						'TITLE' => GetMessage('PAGE_BLOCK'),
						'IMG' => '/bitrix/images/'.$solution.'/themes/catalog_sections_3.png',
						'ROW_CLASS' => 'col-md-4',
						'POSITION_BLOCK' => 'block',
					),
				),
				'DEFAULT' => 'sections_2',
				'THEME' => 'Y',
				'PREVIEW' => array(
					'URL' => 'product/'
				),
			),
			'SECTION_TYPE_VIEW_CATALOG' => array(
				'TITLE' => GetMessage('SECTION_LIST_TITLE'),
				'TYPE' => 'selectbox',
				'IS_ROW' => 'Y',
				'LIST' => array(
					'section_1' => array(
						'TITLE' => GetMessage('PAGE_BLOCK_LIST'),
						'IMG' => '/bitrix/images/'.$solution.'/themes/catalog_sections_1.png',
						'ROW_CLASS' => 'col-md-4',
						'POSITION_BLOCK' => 'block',
					),
					'section_2' => array(
						'TITLE' => GetMessage('PAGE_BLOCK_LIST2'),
						'IMG' => '/bitrix/images/'.$solution.'/themes/catalog_sections_2.png',
						'ROW_CLASS' => 'col-md-4',
						'POSITION_BLOCK' => 'block',
					),
					'section_3' => array(
						'TITLE' => GetMessage('PAGE_BLOCK'),
						'IMG' => '/bitrix/images/'.$solution.'/themes/catalog_sections_3.png',
						'ROW_CLASS' => 'col-md-4',
						'POSITION_BLOCK' => 'block',
					),
				),
				'DEFAULT' => 'section_2',
				'THEME' => 'Y',
			),
			'ELEMENTS_CATALOG_PAGE' => array(
				'TITLE' => GetMessage('LIST_ELEMENTS_TITLE'),
				'TYPE' => 'selectbox',
				'IS_ROW' => 'Y',
				'LIST' => array(
					'list_elements_1' => array(
						'TITLE' => GetMessage('PAGE_BLOCK_NORMAL'),
						'IMG' => '/bitrix/images/'.$solution.'/themes/catalog_elements_1.png',
						'ROW_CLASS' => 'col-md-4',
						'POSITION_BLOCK' => 'block',
					),
				),
				'DEFAULT' => 'list_elements_1',
				'THEME' => 'N',
			),
			'ELEMENTS_TABLE_TYPE_VIEW' => array(
				'TITLE' => GetMessage('ELEMENTS_TABLE_TYPE_VIEW_TITLE'),
				'TYPE' => 'selectbox',
				'IS_ROW' => 'Y',
				'LIST' => array(
					'catalog_table' => array(
						'TITLE' => GetMessage("VIEW_TYPE_ITEM_NORMAL"),
						'IMG' => '/bitrix/images/'.$solution.'/themes/catalog_table_1.png',
						'ROW_CLASS' => 'col-md-4',
						'POSITION_BLOCK' => 'block',
					),
					'catalog_table_2' => array(
						'TITLE' => GetMessage("VIEW_TYPE_ITEM_PROP"),
						'IMG' => '/bitrix/images/'.$solution.'/themes/catalog_table_2.gif',
						'ROW_CLASS' => 'col-md-4',
						'POSITION_BLOCK' => 'block',
					),
				),
				'DEFAULT' => 'catalog_table',
				'THEME' => 'Y',
				/*'PREVIEW' => array(
					'SCROLL_BLOCK' => '.item-views.catalog.blocks',
					'URL' => '',
				),*/
			),
			'ELEMENTS_LIST_TYPE_VIEW' => array(
				'TITLE' => GetMessage('ELEMENTS_LIST_TYPE_VIEW_TITLE'),
				'TYPE' => 'selectbox',
				'LIST' => array(
					'catalog_list' => array(
						'TITLE' => 1,
					),
				),
				'DEFAULT' => 'catalog_list',
				'THEME' => 'N',
			),
			'ELEMENTS_PRICE_TYPE_VIEW' => array(
				'TITLE' => GetMessage('ELEMENTS_PRICE_TYPE_VIEW_TITLE'),
				'TYPE' => 'selectbox',
				'LIST' => array(
					'catalog_price' => array(
						'TITLE' => 1,
					),
				),
				'DEFAULT' => 'catalog_price',
				'THEME' => 'N',
			),
			'CATALOG_PAGE_DETAIL' => array(
				'TITLE' => GetMessage('CATALOG_DETAIL_PAGE_TITLE'),
				'TYPE' => 'selectbox',
				'IS_ROW' => 'Y',
				'LIST' => array(
					'element_1' => array(
						'TITLE' => GetMessage('PAGE_TAB'),
						'IMG' => '/bitrix/images/'.$solution.'/themes/element_tab.png',
						'ROW_CLASS' => 'col-md-4',
						'POSITION_BLOCK' => 'block',
					),
					'element_2' => array(
						'TITLE' => GetMessage('PAGE_NOTAB'),
						'IMG' => '/bitrix/images/'.$solution.'/themes/element_notab.png',
						'ROW_CLASS' => 'col-md-4',
						'POSITION_BLOCK' => 'block',
					),
					'element_3' => array(
						'TITLE' => GetMessage('PAGE_FURNITURE'),
						'IMG' => '/bitrix/images/'.$solution.'/themes/element_mebel.png',
						'ROW_CLASS' => 'col-md-4',
						'POSITION_BLOCK' => 'block',
					),
					'element_4' => array(
						'TITLE' => GetMessage('PAGE_PO'),
						'IMG' => '/bitrix/images/'.$solution.'/themes/element_software.png',
						'ROW_CLASS' => 'col-md-4',
						'POSITION_BLOCK' => 'block',
					),
					'element_5' => array(
						'TITLE' => GetMessage('PAGE_STROY'),
						'IMG' => '/bitrix/images/'.$solution.'/themes/element_stroymat.png',
						'ROW_CLASS' => 'col-md-4',
						'POSITION_BLOCK' => 'block',
					),
					'element_6' => array(
						'TITLE' => GetMessage('PAGE_MEDICAL'),
						'IMG' => '/bitrix/images/'.$solution.'/themes/element_medtech.png',
						'ROW_CLASS' => 'col-md-4',
						'POSITION_BLOCK' => 'block',
					),
				),
				'DEFAULT' => 'element_1',
				'THEME' => 'Y',
			),
			'GRUPPER_PROPS' => array(
				'TITLE' => GetMessage('GRUPPER_PROPS_TITLE'),
				'TYPE' => 'selectbox',
				'LIST' => array(
					'NOT' => array(
						"TITLE" => GetMessage('GRUPPER_PROPS_NO')
					),
				),
				'DEFAULT' => 'NOT',
				'THEME' => 'N',
			),
		),
	),
	'SERVICES' => array(
		'TITLE' => GetMessage('SERVICES_OPTIONS'),
		'THEME' => 'Y',
		'OPTIONS' => array(
			'SECTIONS_TYPE_VIEW' => array(
				'TITLE' => GetMessage('SECTIONS_LIST_TITLE'),
				'TYPE' => 'selectbox',
				'IS_ROW' => 'Y',
				'LIST' => array(
					'sections_1' => array(
						'TITLE' => GetMessage('PAGE_BLOCK_LIST'),
						'IMG' => '/bitrix/images/'.$solution.'/themes/services_sections_1.png',
						'ROW_CLASS' => 'col-md-4',
						'POSITION_BLOCK' => 'block',
					),
					'sections_2' => array(
						'TITLE' => GetMessage('PAGE_BLOCK_1'),
						'IMG' => '/bitrix/images/'.$solution.'/themes/services_sections_2.png',
						'ROW_CLASS' => 'col-md-4',
						'POSITION_BLOCK' => 'block',
					),
					'sections_3' => array(
						'TITLE' => GetMessage('PAGE_BLOCK_2'),
						'IMG' => '/bitrix/images/'.$solution.'/themes/services_sections_3.png',
						'ROW_CLASS' => 'col-md-4',
						'POSITION_BLOCK' => 'block',
					),
				),
				'DEFAULT' => 'sections_1',
				'THEME' => 'Y',
				'PREVIEW' => array(
					'URL' => 'services/'
				),
			),
			'SECTION_TYPE_VIEW' => array(
				'TITLE' => GetMessage('SECTION_LIST_TITLE'),
				'TYPE' => 'selectbox',
				'IS_ROW' => 'Y',
				'LIST' => array(
					'section_1' => array(
						'TITLE' => GetMessage('PAGE_BLOCK_LIST'),
						'IMG' => '/bitrix/images/'.$solution.'/themes/services_sections_1.png',
						'ROW_CLASS' => 'col-md-4',
						'POSITION_BLOCK' => 'block',
					),
					'section_2' => array(
						'TITLE' => GetMessage('PAGE_BLOCK_1'),
						'IMG' => '/bitrix/images/'.$solution.'/themes/services_sections_2.png',
						'ROW_CLASS' => 'col-md-4',
						'POSITION_BLOCK' => 'block',
					),
					'section_3' => array(
						'TITLE' => GetMessage('PAGE_BLOCK_2'),
						'IMG' => '/bitrix/images/'.$solution.'/themes/services_sections_3.png',
						'ROW_CLASS' => 'col-md-4',
						'POSITION_BLOCK' => 'block',
					),
				),
				'DEFAULT' => 'section_2',
				'THEME' => 'Y',
			),
			'ELEMENTS_PAGE' => array(
				'TITLE' => GetMessage('LIST_ELEMENTS_TITLE'),
				'TYPE' => 'selectbox',
				'IS_ROW' => 'Y',
				'LIST' => array(
					'list_elements_1' => array(
						'TITLE' => GetMessage('PAGE_BLOCK_LIST'),
						'IMG' => '/bitrix/images/'.$solution.'/themes/services_elements_1.png',
						'ROW_CLASS' => 'col-md-4',
						'POSITION_BLOCK' => 'block',
					),
					'list_elements_2' => array(
						'TITLE' => GetMessage('PAGE_BLOCK_LIST2'),
						'IMG' => '/bitrix/images/'.$solution.'/themes/services_elements_2.png',
						'ROW_CLASS' => 'col-md-4',
						'POSITION_BLOCK' => 'block',
					),
					'list_elements_3' => array(
						'TITLE' => GetMessage('PAGE_BLOCK'),
						'IMG' => '/bitrix/images/'.$solution.'/themes/services_elements_3.png',
						'ROW_CLASS' => 'col-md-4',
						'POSITION_BLOCK' => 'block',
					),
				),
				'DEFAULT' => 'list_elements_1',
				'THEME' => 'Y',
			),
			'ELEMENT_PAGE_DETAIL' => array(
				'TITLE' => GetMessage('DETAIL_PAGE_TITLE'),
				'TYPE' => 'selectbox',
				'IS_ROW' => 'Y',
				'LIST' => array(
					'element_1' => array(
						'TITLE' => GetMessage('PAGE_TAB'),
						'IMG' => '/bitrix/images/'.$solution.'/themes/element_tab.png',
						'ROW_CLASS' => 'col-md-4',
						'POSITION_BLOCK' => 'block',
					),
					'element_2' => array(
						'TITLE' => GetMessage('PAGE_NOTAB'),
						'IMG' => '/bitrix/images/'.$solution.'/themes/element_notab.png',
						'ROW_CLASS' => 'col-md-4',
						'POSITION_BLOCK' => 'block',
					),
				),
				'DEFAULT' => 'element_1',
				'THEME' => 'Y',
			),
		),
	),
	'PROJECTS' => array(
		'TITLE' => GetMessage('PROJECTS_OPTIONS'),
		'THEME' => 'Y',
		'OPTIONS' => array(
			'ELEMENTS_PROJECT_PAGE' => array(
				'TITLE' => GetMessage('LIST_ELEMENTS_TITLE'),
				'TYPE' => 'selectbox',
				'IS_ROW' => 'Y',
				'LIST' => array(
					'list_elements_4' => array(
						'TITLE' => GetMessage('PAGE_BLOCK_LIST_MENU'),
						'IMG' => '/bitrix/images/'.$solution.'/themes/projects_page_1.png',
						'ROW_CLASS' => 'col-md-4',
						'POSITION_BLOCK' => 'block',
					),
					'list_elements_2' => array(
						'TITLE' => GetMessage('PAGE_BLOCK_SECTION'),
						'IMG' => '/bitrix/images/'.$solution.'/themes/projects_page_2.png',
						'ROW_CLASS' => 'col-md-4',
						'POSITION_BLOCK' => 'block',
					),
					'list_elements_1' => array(
						'TITLE' => GetMessage('PAGE_BLOCK_MENU'),
						'IMG' => '/bitrix/images/'.$solution.'/themes/projects_page_3.png',
						'ROW_CLASS' => 'col-md-4',
						'POSITION_BLOCK' => 'block',
					),
					'list_elements_3' => array(
						'TITLE' => GetMessage('PAGE_BLOCK_YEAR'),
						'IMG' => '/bitrix/images/'.$solution.'/themes/projects_page_4.png',
						'ROW_CLASS' => 'col-md-4',
						'POSITION_BLOCK' => 'block',
					),
				),
				'DEFAULT' => 'list_elements_2',
				'THEME' => 'Y',
				'PREVIEW' => array(
					'URL' => 'projects/'
				),
			),
			'ELEMENT_PROJECT_PAGE_DETAIL' => array(
				'TITLE' => GetMessage('DETAIL_PAGE_TITLE'),
				'TYPE' => 'selectbox',
				'IS_ROW' => 'Y',
				'LIST' => array(
					'element_1' => array(
						'TITLE' => GetMessage('PAGE_BLOCK_NORMAL'),
						'IMG' => '/bitrix/images/'.$solution.'/themes/projects2.png',
						'ROW_CLASS' => 'col-md-4',
						'POSITION_BLOCK' => 'block',
					),
				),
				'DEFAULT' => 'element_1',
				'THEME' => 'N',
			),
			'SHOW_PROJECTS_MAP' => array(
				'TITLE' => GetMessage('SHOW_PROJECTS_MAP_TITLE'),
				'TYPE' => 'checkbox',
				'DEFAULT' => 'Y',
				'THEME' => 'Y',
			),
		),
	),
	'SECTION' => array(
		'TITLE' => GetMessage('SECTION_OPTIONS'),
		'THEME' => 'Y',
		'OPTIONS' => array(
			'PAGE_CONTACTS' => array(
				'TITLE' => GetMessage('PAGE_CONTACTS'),
				'TYPE' => 'selectbox',
				'IS_ROW' => 'Y',
				'LIST' => array(
					'1' => array(
						'TITLE' => GetMessage('PAGE_CONTACT1'),
						'IMG' => '/bitrix/images/'.$solution.'/themes/contact1.png',
						'ROW_CLASS' => 'col-md-4',
						'POSITION_BLOCK' => 'block',
					),
					'2' => array(
						'TITLE' => GetMessage('PAGE_CONTACT2'),
						'IMG' => '/bitrix/images/'.$solution.'/themes/contact2.png',
						'ROW_CLASS' => 'col-md-4',
						'POSITION_BLOCK' => 'block',
					),
					'3' => array(
						'TITLE' => GetMessage('PAGE_CONTACT3'),
						'IMG' => '/bitrix/images/'.$solution.'/themes/contact3.png',
						'ROW_CLASS' => 'col-md-4',
						'POSITION_BLOCK' => 'block',
					),
					/*'4' => array(
						'TITLE' => GetMessage('PAGE_CONTACT4'),
						'IMG' => '/bitrix/images/'.$solution.'/themes/contact4.png',
						'ROW_CLASS' => 'col-md-4',
						'POSITION_BLOCK' => 'block',
					),*/
					'4' => array(
						'TITLE' => GetMessage('PAGE_CONTACT4'),
						'IMG' => '/bitrix/images/'.$solution.'/themes/contact4.png',
						'ROW_CLASS' => 'col-md-4',
						'POSITION_BLOCK' => 'block',
					),
				),
				'DEFAULT' => '1',
				'THEME' => 'Y',
				'PREVIEW' => array(
					'URL' => 'contacts/'
				),
			),
			'CONTACTS_EDIT_LINK_NOTE' => array(
				'TITLE' => GetMessage('CONTACTS_OPTIONS_EDIT_LINK_NOTE'),
				'TYPE' => 'note',
				'THEME' => 'N',
			),
			'CONTACTS_ADDRESS' => array(
				'TITLE' => GetMessage('CONTACTS_OPTIONS_ADDRESS_TITLE'),
				'TYPE' => 'includefile',
				'INCLUDEFILE' => '#SITE_DIR#include/contacts-site-address.php',
				'THEME' => 'N',
			),
			'CONTACTS_PHONE' => array(
				'TITLE' => GetMessage('CONTACTS_OPTIONS_PHONE_TITLE'),
				'TYPE' => 'includefile',
				'INCLUDEFILE' => '#SITE_DIR#include/contacts-site-phone.php',
				'THEME' => 'N',
			),
			'CONTACTS_REGIONAL_PHONE' => array(
				'TITLE' => GetMessage('CONTACTS_OPTIONS_REGIONAL_PHONE_TITLE'),
				'TYPE' => 'includefile',
				'INCLUDEFILE' => '#SITE_DIR#include/contacts-site-phone-one.php',
				'THEME' => 'N',
			),
			'CONTACTS_EMAIL' => array(
				'TITLE' => GetMessage('CONTACTS_OPTIONS_EMAIL_TITLE'),
				'TYPE' => 'includefile',
				'INCLUDEFILE' => '#SITE_DIR#include/contacts-site-email.php',
				'THEME' => 'N',
			),
			'CONTACTS_SCHEDULE12' => array(
				'TITLE' => GetMessage('CONTACTS_OPTIONS_SCHEDULE12_TITLE'),
				'TYPE' => 'includefile',
				'INCLUDEFILE' => '#SITE_DIR#include/contacts-site-schedule.php',
				'THEME' => 'N',
			),
			'CONTACTS_DESCRIPTION12' => array(
				'TITLE' => GetMessage('CONTACTS_OPTIONS_DESCRIPTION12_TITLE'),
				'TYPE' => 'includefile',
				'INCLUDEFILE' => '#SITE_DIR#include/contacts-about.php',
				'THEME' => 'N',
			),
			'CONTACTS_REGIONAL_DESCRIPTION34' => array(
				'TITLE' => GetMessage('CONTACTS_OPTIONS_REGIONAL_DESCRIPTION34_TITLE'),
				'TYPE' => 'includefile',
				'INCLUDEFILE' => '#SITE_DIR#include/contacts-regions-title.php',
				'THEME' => 'N',
			),
			'CONTACTS_REGIONAL_DESCRIPTION5' => array(
				'TITLE' => GetMessage('CONTACTS_OPTIONS_REGIONAL_DESCRIPTION5_TITLE'),
				'TYPE' => 'includefile',
				'INCLUDEFILE' => '#SITE_DIR#include/contacts-regions.php',
				'THEME' => 'N',
			),
			'CONTACTS_USE_FEEDBACK' => array(
				'TITLE' => GetMessage('CONTACTS_OPTIONS_USE_FEEDBACK_TITLE'),
				'TYPE' => 'checkbox',
				'DEFAULT' => 'Y',
				'THEME' => 'N',
			),
			'CONTACTS_USE_MAP' => array(
				'TITLE' => GetMessage('CONTACTS_OPTIONS_USE_MAP_TITLE'),
				'TYPE' => 'checkbox',
				'DEFAULT' => 'Y',
				'THEME' => 'N',
			),
			'CONTACTS_MAP' => array(
				'TITLE' => GetMessage('CONTACTS_OPTIONS_MAP_TITLE'),
				'TYPE' => 'includefile',
				'INCLUDEFILE' => '#SITE_DIR#include/contacts-site-map.php',
				'THEME' => 'N',
			),
			'CONTACTS_MAP_NOTE' => array(
				'TITLE' => GetMessage('CONTACTS_OPTIONS_MAP_NOTE'),
				'TYPE' => 'note',
				'ALIGN' => 'center',
				'THEME' => 'N',
			),
			'BLOG_PAGE' => array(
				'TITLE' => GetMessage('BLOG_PAGE_TITLE'),
				'TYPE' => 'selectbox',
				'IS_ROW' => 'Y',
				'LIST' => array(
					'list_elements_2' => array(
						'TITLE' => GetMessage('PAGE_BLOCK'),
						'IMG' => '/bitrix/images/'.$solution.'/themes/blog2.png',
						'ROW_CLASS' => 'col-md-4',
						'POSITION_BLOCK' => 'block',
					),
					'list_elements_1' => array(
						'TITLE' => GetMessage('PAGE_LIST'),
						'IMG' => '/bitrix/images/'.$solution.'/themes/blog1.png',
						'ROW_CLASS' => 'col-md-4',
						'POSITION_BLOCK' => 'block',
					),
				),
				'DEFAULT' => 'list_elements_2',
				'THEME' => 'Y',
				'PREVIEW' => array(
					'URL' => 'info/articles/'
				),
			),
			'NEWS_PAGE' => array(
				'TITLE' => GetMessage('NEWS_PAGE_TITLE'),
				'TYPE' => 'selectbox',
				'IS_ROW' => 'Y',
				'LIST' => array(
					'list_elements_1' => array(
						'TITLE' => GetMessage('PAGE_LIST'),
						'IMG' => '/bitrix/images/'.$solution.'/themes/news1.png',
						'ROW_CLASS' => 'col-md-4',
						'POSITION_BLOCK' => 'block',
					),
					'list_elements_2' => array(
						'TITLE' => GetMessage('PAGE_TILE'),
						'IMG' => '/bitrix/images/'.$solution.'/themes/news2.png',
						'ROW_CLASS' => 'col-md-4',
						'POSITION_BLOCK' => 'block',
					),
					'list_elements_3' => array(
						'TITLE' => GetMessage('PAGE_BLOCK'),
						'IMG' => '/bitrix/images/'.$solution.'/themes/blog_news.png',
						'ROW_CLASS' => 'col-md-4',
						'POSITION_BLOCK' => 'block',
					),
				),
				'DEFAULT' => 'list_elements_2',
				'THEME' => 'Y',
				'PREVIEW' => array(
					'URL' => 'info/news/'
				),
			),
			'STAFF_PAGE' => array(
				'TITLE' => GetMessage('STAFF_PAGE_TITLE'),
				'TYPE' => 'selectbox',
				'IS_ROW' => 'Y',
				'LIST' => array(
					'list_elements_1' => array(
						'TITLE' => GetMessage('PAGE_BLOCK'),
						'IMG' => '/bitrix/images/'.$solution.'/themes/company_employees1.png',
						'ROW_CLASS' => 'col-md-4',
						'POSITION_BLOCK' => 'block',
					),
					'list_elements_2' => array(
						'TITLE' => GetMessage('PAGE_LIST'),
						'IMG' => '/bitrix/images/'.$solution.'/themes/company_employees2.png',
						'ROW_CLASS' => 'col-md-4',
						'POSITION_BLOCK' => 'block',
					),
				),
				'DEFAULT' => 'list_elements_1',
				'THEME' => 'Y',
				'PREVIEW' => array(
					'URL' => 'company/staff/'
				),
			),
			'PARTNERS_PAGE' => array(
				'TITLE' => GetMessage('PARTNERS_PAGE_TITLE'),
				'TYPE' => 'selectbox',
				'IS_ROW' => 'Y',
				'LIST' => array(
					'list_elements_1' => array(
						'TITLE' => GetMessage('PAGE_BLOCK'),
						'IMG' => '/bitrix/images/'.$solution.'/themes/company_partners1.png',
						'ROW_CLASS' => 'col-md-4',
						'POSITION_BLOCK' => 'block',
					),
					'list_elements_2' => array(
						'TITLE' => GetMessage('PAGE_LIST'),
						'IMG' => '/bitrix/images/'.$solution.'/themes/company_partners2.png',
						'ROW_CLASS' => 'col-md-4',
						'POSITION_BLOCK' => 'block',
					),
					'list_elements_3' => array(
						'TITLE' => GetMessage('PAGE_LOGO'),
						'IMG' => '/bitrix/images/'.$solution.'/themes/company_partners3.png',
						'ROW_CLASS' => 'col-md-4',
						'POSITION_BLOCK' => 'block',
					),
				),
				'DEFAULT' => 'list_elements_1',
				'THEME' => 'Y',
				'PREVIEW' => array(
					'URL' => 'company/partners/'
				),
			),
			'VACANCY_PAGE' => array(
				'TITLE' => GetMessage('VACANCY_PAGE_TITLE'),
				'TYPE' => 'selectbox',
				'IS_ROW' => 'Y',
				'LIST' => array(
					'list_elements_1' => array(
						'TITLE' => GetMessage('PAGE_ACCORDION'),
						'IMG' => '/bitrix/images/'.$solution.'/themes/company_vacancy1.png',
						'ROW_CLASS' => 'col-md-4',
						'POSITION_BLOCK' => 'block',
					),
					'list_elements_2' => array(
						'TITLE' => GetMessage('PAGE_LIST'),
						'IMG' => '/bitrix/images/'.$solution.'/themes/company_vacancy2.png',
						'ROW_CLASS' => 'col-md-4',
						'POSITION_BLOCK' => 'block',
					),
				),
				'DEFAULT' => 'list_elements_1',
				'THEME' => 'Y',
				'PREVIEW' => array(
					'URL' => 'company/vacancy/'
				),
			),
			'LICENSES_PAGE' => array(
				'TITLE' => GetMessage('LICENSES_PAGE_TITLE'),
				'TYPE' => 'selectbox',
				'IS_ROW' => 'Y',
				'LIST' => array(
					'list_elements_1' => array(
						'TITLE' => GetMessage('PAGE_BLOCK'),
						'IMG' => '/bitrix/images/'.$solution.'/themes/company_licenses1.png',
						'ROW_CLASS' => 'col-md-4',
						'POSITION_BLOCK' => 'block',
					),
					'list_elements_2' => array(
						'TITLE' => GetMessage('PAGE_LIST'),
						'IMG' => '/bitrix/images/'.$solution.'/themes/company_licenses2.png',
						'ROW_CLASS' => 'col-md-4',
						'POSITION_BLOCK' => 'block',
					),
				),
				'DEFAULT' => 'list_elements_1',
				'THEME' => 'Y',
				'PREVIEW' => array(
					'URL' => 'company/licenses/'
				),
			),
		)
	),
	'FOOTER' => array(
		'TITLE' => GetMessage('FOOTER_OPTIONS'),
		'THEME' => 'Y',
		'OPTIONS' => array(
			'FOOTER_TYPE' => array(
				'TITLE' => GetMessage('FOOTER_TYPE'),
				'TYPE' => 'selectbox',
				'LIST' => array(
					'1' => array(
						'IMG' => '/bitrix/images/'.$solution.'/themes/footer1.png',
						'TITLE' => '1',
						'POSITION_BLOCK' => 'block',
						'POSITION_TITLE' => 'left',
					),
					'2' => array(
						'IMG' => '/bitrix/images/'.$solution.'/themes/footer2.png',
						'TITLE' => '2',
						'POSITION_BLOCK' => 'block',
						'POSITION_TITLE' => 'left',
					),
					'3' => array(
						'IMG' => '/bitrix/images/'.$solution.'/themes/footer3.png',
						'TITLE' => '3',
						'POSITION_BLOCK' => 'block',
						'POSITION_TITLE' => 'left',
					),
					'4' => array(
						'IMG' => '/bitrix/images/'.$solution.'/themes/footer4.png',
						'TITLE' => '4',
						'POSITION_BLOCK' => 'block',
						'POSITION_TITLE' => 'left',
					),
					'5' => array(
						'IMG' => '/bitrix/images/'.$solution.'/themes/footer5.png',
						'TITLE' => '5',
						'POSITION_BLOCK' => 'block',
						'POSITION_TITLE' => 'left',
					),
					'6' => array(
						'IMG' => '/bitrix/images/'.$solution.'/themes/footer6.png',
						'TITLE' => '6',
						'POSITION_BLOCK' => 'block',
						'POSITION_TITLE' => 'left',
					),
					'custom' => array(
						'TITLE' => 'Custom',
						'POSITION_BLOCK' => 'block',
						'HIDE' => 'Y'
					),
				),
				'DEFAULT' => '1',
				'THEME' => 'Y',
				'PREVIEW' => array(
					'SCROLL_BLOCK' => '#footer'
				),
			),
		)
	),

	'ADV' => array(
		'TITLE' => GetMessage('ADV_OPTIONS'),
		'THEME' => 'Y',
		'OPTIONS' => array(
			'ADV_TOP_HEADER' => array(
				'TITLE' => GetMessage('ADV_TOP_HEADER_TITLE'),
				'IMG' => '/bitrix/images/'.$solution.'/themes/banner_position1.png',
				'TYPE' => 'checkbox',
				'DEFAULT' => 'N',
				'THEME' => 'Y',
				'GROUP' => 'ADV_GROUP_TITLE',
				'ROW_CLASS' => 'col-md-6',
				'POSITION_BLOCK' => 'block',
				'IS_ROW' => 'Y',
				'SMALL_TOGGLE' => 'Y',
			),
			'ADV_TOP_UNDERHEADER' => array(
				'TITLE' => GetMessage('ADV_TOP_UNDERHEADER_TITLE'),
				'IMG' => '/bitrix/images/'.$solution.'/themes/banner_position2.png',
				'TYPE' => 'checkbox',
				'DEFAULT' => 'N',
				'THEME' => 'Y',
				'GROUP' => 'ADV_GROUP_TITLE',
				'ROW_CLASS' => 'col-md-6',
				'POSITION_BLOCK' => 'block',
				'IS_ROW' => 'Y',
				'SMALL_TOGGLE' => 'Y',
			),
			'ADV_SIDE' => array(
				'TITLE' => GetMessage('ADV_SIDE_TITLE'),
				'IMG' => '/bitrix/images/'.$solution.'/themes/banner_position5.png',
				'TYPE' => 'checkbox',
				'DEFAULT' => 'N',
				'THEME' => 'Y',
				'GROUP' => 'ADV_GROUP_TITLE',
				'ROW_CLASS' => 'col-md-6',
				'POSITION_BLOCK' => 'block',
				'IS_ROW' => 'Y',
				'SMALL_TOGGLE' => 'Y',
			),
			'ADV_CONTENT_TOP' => array(
				'TITLE' => GetMessage('ADV_CONTENT_TOP_TITLE'),
				'IMG' => '/bitrix/images/'.$solution.'/themes/banner_position3.png',
				'TYPE' => 'checkbox',
				'DEFAULT' => 'N',
				'THEME' => 'Y',
				'GROUP' => 'ADV_GROUP_TITLE',
				'ROW_CLASS' => 'col-md-6',
				'POSITION_BLOCK' => 'block',
				'IS_ROW' => 'Y',
				'SMALL_TOGGLE' => 'Y',
			),
			'ADV_CONTENT_BOTTOM' => array(
				'TITLE' => GetMessage('ADV_CONTENT_BOTTOM_TITLE'),
				'IMG' => '/bitrix/images/'.$solution.'/themes/banner_position4.png',
				'TYPE' => 'checkbox',
				'DEFAULT' => 'N',
				'THEME' => 'Y',
				'GROUP' => 'ADV_GROUP_TITLE',
				'ROW_CLASS' => 'col-md-6',
				'POSITION_BLOCK' => 'block',
				'IS_ROW' => 'Y',
				'SMALL_TOGGLE' => 'Y',
			),
			'ADV_FOOTER' => array(
				'TITLE' => GetMessage('ADV_FOOTER_TITLE'),
				'IMG' => '/bitrix/images/'.$solution.'/themes/banner_position6.png',
				'TYPE' => 'checkbox',
				'DEFAULT' => 'N',
				'THEME' => 'Y',
				'GROUP' => 'ADV_GROUP_TITLE',
				'ROW_CLASS' => 'col-md-6',
				'POSITION_BLOCK' => 'block',
				'IS_ROW' => 'Y',
				'SMALL_TOGGLE' => 'Y',
			)
		),
	),
	'MOBILE' => array(
		'TITLE' => GetMessage('MOBILE_OPTIONS'),
		'THEME' => 'Y',
		'OPTIONS' => array(
			'HEADER_MOBILE' => array(
				'TITLE' => GetMessage('HEADER_MOBILE'),
				'TYPE' => 'selectbox',
				'IS_ROW' => 'N',
				'LIST' => array(
					'1' => array(
						'IMG' => '/bitrix/images/'.$solution.'/themes/header_mobile_white.png',
						'TITLE' => GetMessage('HEADER_MOBILE_WHITE'),
						'ROW_CLASS' => 'col-md-4',
						'POSITION_BLOCK' => 'block',
						'POSITION_TITLE' => 'left',
						'TITLE_WIDTH' => '75px',
					),
					'2' => array(
						'IMG' => '/bitrix/images/'.$solution.'/themes/header_mobile_color.png',
						'TITLE' => GetMessage('HEADER_MOBILE_COLOR'),
						'ROW_CLASS' => 'col-md-4',
						'POSITION_BLOCK' => 'block',
						'POSITION_TITLE' => 'left',
						'TITLE_WIDTH' => '75px',
					),
					'custom' => array(
						'TITLE' => 'Custom',
						'ROW_CLASS' => 'col-md-4',
						'POSITION_BLOCK' => 'block',
						'POSITION_TITLE' => 'left',
						'TITLE_WIDTH' => '75px',
						'HIDE' => 'Y'
					),
				),
				'DEFAULT' => '1',
				'THEME' => 'Y',
			),
			'HEADER_MOBILE_MENU' => array(
				'TITLE' => GetMessage('HEADER_MOBILE_MENU'),
				'TYPE' => 'selectbox',
				// 'IS_ROW' => 'Y',
				'LIST' => array(
					'1' => array(
						'TITLE' => GetMessage('HEADER_MOBILE_MENU_FULL'),
					),
					'2' => array(
						'TITLE' => GetMessage('HEADER_MOBILE_MENU_TOP'),
					),
					'custom' => array(
						'TITLE' => 'Custom',
						'HIDE' => 'Y',
					),
				),
				'DEFAULT' => '1',
				'THEME' => 'Y',
			),
			'HEADER_MOBILE_MENU_OPEN' => array(
				'TITLE' => GetMessage('HEADER_MOBILE_MENU_OPEN'),
				'TYPE' => 'selectbox',
				'IS_ROW' => 'Y',
				'LIST' => array(
					'1' => array(
						'TITLE' => GetMessage('HEADER_MOBILE_MENU_OPEN_LEFT'),
						'IMG' => '/bitrix/images/'.$solution.'/themes/mobile_menu1.png',
						'ROW_CLASS' => 'col-md-4',
						'POSITION_BLOCK' => 'block',
					),
					'2' => array(
						'TITLE' => GetMessage('HEADER_MOBILE_MENU_OPEN_TOP'),
						'IMG' => '/bitrix/images/'.$solution.'/themes/mobile_menu2.png',
						'ROW_CLASS' => 'col-md-4',
						'POSITION_BLOCK' => 'block',
					),
				),
				'DEFAULT' => '1',
				'THEME' => 'Y',
			),
		)
	),
	'LK' => array(
		'TITLE' => GetMessage('LK_OPTIONS'),
		'THEME' => 'Y',
		'OPTIONS' => array(
			'CABINET' => array(
				'TITLE' => GetMessage('CABINET'),
				'TYPE' => 'checkbox',
				'DEFAULT' => 'Y',
				'THEME' => 'Y',
				'ONE_ROW' => 'Y',
				'DEPENDENT_PARAMS' => array(
					'PERSONAL_ONEFIO' => array(
						'TITLE' => GetMessage('PERSONAL_ONEFIO_TITLE'),
						'TYPE' => 'checkbox',
						'DEFAULT' => 'Y',
						'THEME' => 'Y',
						'ONE_ROW' => 'Y',
						'CONDITIONAL_VALUE' => 'Y',
					),
				)
			),
			'LOGIN_EQUAL_EMAIL' => array(
				'TITLE' => GetMessage('LOGIN_EQUAL_EMAIL_TITLE'),
				'TYPE' => 'checkbox',
				'DEFAULT' => 'N',
				'THEME' => 'Y',
				'ONE_ROW' => 'Y',
			),
		)
	),
	'COUNTERS_GOALS' => array(
		'TITLE' => GetMessage('COUNTERS_GOALS_OPTIONS'),
		'THEME' => 'N',
		'OPTIONS' => array(
			'ALL_COUNTERS' => array(
				'TITLE' => GetMessage('ALL_COUNTERS_TITLE'),
				'TYPE' => 'includefile',
				'INCLUDEFILE' => '#SITE_DIR#include/invis-counter.php',
			),
			'YA_GOLAS' => array(
				'TITLE' => GetMessage('YA_GOLAS_TITLE'),
				'TYPE' => 'checkbox',
				'DEFAULT' => 'N',
				'DEPENDENT_PARAMS' => array(
					'YA_COUNTER_ID' => array(
						'TITLE' => GetMessage('YA_COUNTER_ID_TITLE'),
						'TYPE' => 'text',
						'DEFAULT' => '',
						'THEME' => 'N',
						'CONDITIONAL_VALUE' => 'Y',
					),
					'USE_FORMS_GOALS' => array(
						'TITLE' => GetMessage('USE_FORMS_GOALS_TITLE'),
						'TYPE' => 'selectbox',
						'LIST' => array(
							'NONE' => GetMessage('USE_FORMS_GOALS_NONE'),
							'COMMON' => GetMessage('USE_FORMS_GOALS_COMMON'),
							'SINGLE' => GetMessage('USE_FORMS_GOALS_SINGLE'),
						),
						'DEFAULT' => 'COMMON',
						'THEME' => 'N',
						'CONDITIONAL_VALUE' => 'Y',
					),
					'USE_FORMS_GOALS_NOTE' => array(
						'NOTE' => GetMessage('USE_FORM_GOALS_NOTE_TITLE'),
						'TYPE' => 'note',
						'THEME' => 'N',
						// 'CONDITIONAL_VALUE' => 'Y',
					),
					'USE_SALE_GOALS' => array(
						'TITLE' => GetMessage('USE_SALE_GOALS_TITLE'),
						'TYPE' => 'checkbox',
						'DEFAULT' => 'Y',
						'THEME' => 'N',
						'CONDITIONAL_VALUE' => 'Y',
					),
					'USE_SALE_GOALS_NOTE' => array(
						'NOTE' => GetMessage('USE_SALE_GOALS_NOTE_TITLE'),
						'TYPE' => 'note',
						'THEME' => 'N',
						// 'CONDITIONAL_VALUE' => 'Y',
					),
					'USE_DEBUG_GOALS' => array(
						'TITLE' => GetMessage('USE_DEBUG_GOALS_TITLE'),
						'TYPE' => 'checkbox',
						'DEFAULT' => 'N',
						'THEME' => 'N',
						'CONDITIONAL_VALUE' => 'Y',
					),
					'USE_DEBUG_GOALS_NOTE' => array(
						'NOTE' => GetMessage('USE_DEBUG_GOALS_NOTE_TITLE'),
						'TYPE' => 'note',
						'THEME' => 'N',
						// 'CONDITIONAL_VALUE' => 'Y',
					),
				)
			)
		)
	),
);

foreach(GetModuleEvents(ALLCORP2_MODULE_ID, 'OnAsproParameters', true) as $arEvent) // event for manipulation arMainPageOrder
	ExecuteModuleEventEx($arEvent, array(&$moduleClass::$arParametrsList));
?>