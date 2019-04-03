<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Каталог");
?>
<?$APPLICATION->IncludeComponent(
	"bitrix:news", 
	"catalog", 
	array(
		"IBLOCK_TYPE" => "aspro_allcorp2_catalog",
		"IBLOCK_ID" => "#IBLOCK_CATALOG_ID#",
		"NEWS_COUNT" => "15",
		"USE_SEARCH" => "N",
		"USE_RSS" => "N",
		"USE_RATING" => "N",
		"USE_CATEGORIES" => "N",
		"USE_FILTER" => "Y",
		"SORT_BY1" => "SORT",
		"SORT_ORDER1" => "ASC",
		"SORT_BY2" => "ID",
		"SORT_ORDER2" => "DESC",
		"CHECK_DATES" => "Y",
		"SEF_MODE" => "Y",
		"SEF_FOLDER" => "#SITE_DIR#product/",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"AJAX_OPTION_HISTORY" => "N",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "36000000",
		"CACHE_FILTER" => "N",
		"CACHE_GROUPS" => "N",
		"SET_TITLE" => "Y",
		"SET_STATUS_404" => "Y",
		"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
		"ADD_SECTIONS_CHAIN" => "Y",
		"USE_PERMISSIONS" => "N",
		"PREVIEW_TRUNCATE_LEN" => "",
		"LIST_ACTIVE_DATE_FORMAT" => "d.m.Y",
		"LIST_FIELD_CODE" => array(
			0 => "NAME",
			1 => "PREVIEW_TEXT",
			2 => "PREVIEW_PICTURE",
			3 => "DETAIL_TEXT",
			4 => "DETAIL_PICTURE",
			5 => "",
		),
		"LIST_PROPERTY_CODE" => array(
			0 => "STATUS",
			1 => "PRICE",
			2 => "ARTICLE",
			3 => "PRICEOLD",
			4 => "CATEGORY",
			5 => "RECOMMEND",
			6 => "FORM_ORDER",
			7 => "LINK_PROJECTS",
			8 => "LINK_TIZERS",
			9 => "LINK_GOODS",
			10 => "SUPPLIED",
			11 => "USERS",
			12 => "LANGUAGES",
			13 => "DURATION",
			14 => "EXTENSION",
			15 => "UPDATES",
			16 => "GRUZ_STRELI",
			17 => "MASS",
			18 => "POWER",
			19 => "VOLUME",
			20 => "DEPTH",
			21 => "TYPE_TUR",
			22 => "WIDTH_PROHOD",
			23 => "KARTOPR",
			24 => "WIDTH_PROEZD",
			25 => "CLASS",
			26 => "FREQUENCY",
			27 => "MARK",
			28 => "PROIZVODSTVO",
			29 => "DLINA",
			30 => "WIDTH",
			31 => "THICKNESS",
			32 => "AGE",
			33 => "MARK_STEEL",
			34 => "SIZE",
			35 => "HEIGHT",
			36 => "PLACE",
			37 => "SERIES",
			38 => "DEEP",
			39 => "DIAGONAL",
			40 => "RESOLUTION",
			41 => "TYPE",
			42 => "COLOR",
			43 => "FREQUENCE",
			44 => "CLIMAT_CLASS",
			45 => "TEMPERATURE",
			46 => "INCREASE",
			47 => "DLINA_STRELI",
			48 => "KOL_FORMULA",
			49 => "SPEED",
			50 => "GRUZ",
			51 => "MODEL",
			52 => "LIGHTS_LOCATION",
			53 => "",
		),
		"HIDE_LINK_WHEN_NO_DETAIL" => "Y",
		"SORT_PROP" => array(
			0 => "name",
			1 => "sort",
			2 => "FILTER_PRICE",
		),
		"SORT_PROP_DEFAULT" => "sort",
		"SORT_DIRECTION" => "desc",
		"DISPLAY_NAME" => "N",
		"META_KEYWORDS" => "-",
		"META_DESCRIPTION" => "-",
		"BROWSER_TITLE" => "-",
		"DETAIL_ACTIVE_DATE_FORMAT" => "d.m.Y",
		"DETAIL_FIELD_CODE" => array(
			0 => "NAME",
			1 => "PREVIEW_TEXT",
			2 => "DETAIL_TEXT",
			3 => "DETAIL_PICTURE",
			4 => "",
		),
		"DETAIL_PROPERTY_CODE" => array(
			0 => "STATUS",
			1 => "PRICE",
			2 => "ARTICLE",
			3 => "PRICEOLD",
			4 => "CATEGORY",
			5 => "RECOMMEND",
			6 => "VIDEO",
			7 => "FORM_QUESTION",
			8 => "VIDEO_IFRAME",
			9 => "FORM_ORDER",
			10 => "LINK_PROJECTS",
			11 => "LINK_STAFF",
			12 => "LINK_ARTICLES",
			13 => "LINK_STUDY",
			14 => "BRAND",
			15 => "LINK_TARIF",
			16 => "LINK_TIZERS",
			17 => "LINK_REVIEWS",
			18 => "LINK_NEWS",
			19 => "LINK_SERVICES",
			20 => "LINK_FAQ",
			21 => "LINK_SALE",
			22 => "LINK_GOODS",
			23 => "SUPPLIED",
			24 => "USERS",
			25 => "LANGUAGES",
			26 => "DURATION",
			27 => "UPDATES",
			28 => "GRUZ_STRELI",
			29 => "MASS",
			30 => "POWER",
			31 => "VOLUME",
			32 => "DEPTH",
			33 => "TYPE_TUR",
			34 => "WIDTH_PROHOD",
			35 => "KARTOPR",
			36 => "WIDTH_PROEZD",
			37 => "CLASS",
			38 => "FREQUENCY",
			39 => "MARK",
			40 => "PROIZVODSTVO",
			41 => "DLINA",
			42 => "WIDTH",
			43 => "THICKNESS",
			44 => "AGE",
			45 => "MARK_STEEL",
			46 => "SIZE",
			47 => "HEIGHT",
			48 => "PLACE",
			49 => "SERIES",
			50 => "DEEP",
			51 => "DIAGONAL",
			52 => "RESOLUTION",
			53 => "TYPE",
			54 => "COLOR",
			55 => "FREQUENCE",
			56 => "CLIMAT_CLASS",
			57 => "TEMPERATURE",
			58 => "INCREASE",
			59 => "DLINA_STRELI",
			60 => "KOL_FORMULA",
			61 => "SPEED",
			62 => "GRUZ",
			63 => "MODEL",
			64 => "LIGHTS_LOCATION",
			65 => "test_41",
			66 => "PLACE_CLOUD",
			67 => "DOCUMENTS",
			68 => "",
		),
		"DETAIL_DISPLAY_TOP_PAGER" => "N",
		"DETAIL_DISPLAY_BOTTOM_PAGER" => "Y",
		"DETAIL_PAGER_TITLE" => "Страница",
		"DETAIL_PAGER_TEMPLATE" => "",
		"DETAIL_PAGER_SHOW_ALL" => "Y",
		"PAGER_TEMPLATE" => "main",
		"DISPLAY_TOP_PAGER" => "N",
		"DISPLAY_BOTTOM_PAGER" => "Y",
		"PAGER_TITLE" => "Новости",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALL" => "N",
		"SHOW_IMAGE" => "Y",
		"SHOW_NAME" => "Y",
		"SHOW_DETAIL" => "Y",
		"IMAGE_POSITION" => "top",
		"COUNT_IN_LINE" => "3",
		"AJAX_OPTION_ADDITIONAL" => "",
		"USE_REVIEW" => "N",
		"ADD_ELEMENT_CHAIN" => "Y",
		"SHOW_DETAIL_LINK" => "Y",
		"USE_SHARE" => "Y",
		"S_ASK_QUESTION" => "",
		"S_ORDER_SERVISE" => "",
		"T_GALLERY" => "Фото",
		"T_DOCS" => "",
		"T_PROJECTS" => "Проекты",
		"T_CHARACTERISTICS" => "",
		"COMPONENT_TEMPLATE" => "catalog",
		"DETAIL_SET_CANONICAL_URL" => "N",
		"SET_LAST_MODIFIED" => "Y",
		"T_VIDEO" => "",
		"PAGER_BASE_LINK_ENABLE" => "N",
		"SHOW_404" => "Y",
		"MESSAGE_404" => "",
		"FILTER_URL_TEMPLATE" => "#SECTION_CODE_PATH#/filter/#SMART_FILTER_PATH#/apply/",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO",
		"FILE_404" => "",
		"SECTIONS_COUNT_IN_LINE" => "2",
		"VIEW_TYPE_SECTION" => "list_block",
		"VIEW_TYPE" => "table",
		"SHOW_CHILD_SECTIONS" => "Y",
		"SHOW_CHILD_ELEMENTS" => "N",
		"IMAGE_CATALOG_POSITION" => "left",
		"DETAIL_BRAND_USE" => "Y",
		"DETAIL_BRAND_PROP_CODE" => array(
			0 => "",
			1 => "TIZERS",
			2 => "",
		),
		"T_SERVICES" => "",
		"T_FAQ" => "",
		"T_TARIF" => "",
		"T_DESC" => "",
		"T_DEV" => "Производитель",
		"T_ITEMS" => "",
		"GALLERY_TYPE" => "small",
		"SECTIONS_TYPE_VIEW" => "FROM_MODULE",
		"ELEMENT_TYPE_VIEW" => "FROM_MODULE",
		"SHOW_SECTION_PREVIEW_DESCRIPTION" => "Y",
		"SECTION_TYPE_VIEW" => "FROM_MODULE",
		"LINE_ELEMENT_COUNT" => "3",
		"DETAIL_STRICT_SECTION_CHECK" => "Y",
		"SHOW_BRAND_DETAIL" => "Y",
		"FORM_ID_ORDER_SERVISE" => "",
		"SECTION_ELEMENTS_TYPE_VIEW" => "FROM_MODULE",
		"STRICT_SECTION_CHECK" => "Y",
		"SHOW_ELEMENTS_IN_LAST_SECTION" => "Y",
		"LANDING_IBLOCK_ID" => CCache::$arIBlocks[SITE_ID]["aspro_allcorp2_catalog"]["aspro_allcorp2_catalog_info"][0],
		"LANDING_SECTION_COUNT" => "20",
		"LANDING" => "Похожие посадочные страницы",
		"LANDING_SECTION_COUNT_VISIBLE" => "1",
		"LANDING_TIZER_IBLOCK_ID" => CCache::$arIBlocks[SITE_ID]["aspro_allcorp2_content"]["aspro_allcorp2_tizers_landing"][0],
		"FILTER_NAME" => "",
		"FILTER_FIELD_CODE" => array(
			0 => "",
			1 => "",
		),
		"FILTER_PROPERTY_CODE" => array(
			0 => "LINK_GOODS",
			1 => "FREQUENCE",
			2 => "CLIMAT_CLASS",
			3 => "TEMPERATURE",
			4 => "",
		),
		"S_TO_ALL" => "",
		"REVIEWS_IBLOCK_ID" => CCache::$arIBlocks[SITE_ID]["aspro_allcorp2_content"]["aspro_allcorp2_reviews"][0],
		"PROJECTS_IBLOCK_ID" => CCache::$arIBlocks[SITE_ID]["aspro_allcorp2_content"]["aspro_allcorp2_projects"][0],
		"SERVICES_IBLOCK_ID" => CCache::$arIBlocks[SITE_ID]["aspro_allcorp2_content"]["aspro_allcorp2_services"][0],
		"CATALOG_IBLOCK_ID" => "",
		"STAFF_IBLOCK_ID" => CCache::$arIBlocks[SITE_ID]["aspro_allcorp2_content"]["aspro_allcorp2_staff"][0],
		"PARTNERS_IBLOCK_ID" => CCache::$arIBlocks[SITE_ID]["aspro_allcorp2_content"]["aspro_allcorp2_partners"][0],
		"NEWS_IBLOCK_ID" => CCache::$arIBlocks[SITE_ID]["aspro_allcorp2_content"]["aspro_allcorp2_news"][0],
		"FAQ_IBLOCK_ID" => CCache::$arIBlocks[SITE_ID]["aspro_allcorp2_content"]["aspro_allcorp2_faq"][0],
		"TARIF_IBLOCK_ID" => CCache::$arIBlocks[SITE_ID]["aspro_allcorp2_catalog"]["aspro_allcorp2_tarifs"][0],
		"DETAIL_USE_COMMENTS" => "Y",
		"DETAIL_BLOG_USE" => "Y",
		"DETAIL_BLOG_URL" => "catalog_comments",
		"COMMENTS_COUNT" => "5",
		"BLOG_TITLE" => "Комментарии",
		"DETAIL_BLOG_EMAIL_NOTIFY" => "Y",
		"DETAIL_VK_USE" => "N",
		"VK_TITLE" => "Вконтакте",
		"DETAIL_VK_API_ID" => "API_ID",
		"DETAIL_FB_USE" => "Y",
		"FB_TITLE" => "Facebook",
		"DETAIL_FB_APP_ID" => "",
		"LIST_PRODUCT_BLOCKS_ORDER" => "gallery,tizers,tab,sale,reviews,study,news,services,staff,goods,comments",
		"LIST_PRODUCT_BLOCKS_TAB_ORDER" => "desc,char,tarif,faq,projects,docs,articles,video,dops",
		"LIST_PRODUCT_BLOCKS_ALL_ORDER" => "tizers,sale,tarif,desc,faq,char,news,projects,articles,docs,video,gallery,study,staff,reviews,services,dops,goods,comments",
		"ELEMENTS_TABLE_TYPE_VIEW" => "FROM_MODULE",
		"ELEMENTS_PRICE_TYPE_VIEW" => "FROM_MODULE",
		"ELEMENTS_LIST_TYPE_VIEW" => "FROM_MODULE",
		"INCLUDE_SUBSECTIONS" => "N",
		"TIZERS_IBLOCK_ID" => CCache::$arIBlocks[SITE_ID]["aspro_allcorp2_content"]["aspro_allcorp2_front_tizers"][0],
		"T_REVIEWS" => "",
		"T_NEWS" => "",
		"T_STAFF" => "",
		"SALE_IBLOCK_ID" => CCache::$arIBlocks[SITE_ID]["aspro_allcorp2_content"]["aspro_allcorp2_sales"][0],
		"T_ARTICLES" => "",
		"ARTICLES_IBLOCK_ID" => CCache::$arIBlocks[SITE_ID]["aspro_allcorp2_content"]["aspro_allcorp2_articles"][0],
		"TAB_DOPS_NAME" => "",
		"SHOW_ADDITIONAL_TAB" => "N",
		"T_STUDY" => "",
		"STUDY_IBLOCK_ID" => CCache::$arIBlocks[SITE_ID]["aspro_allcorp2_catalog"]["aspro_allcorp2_timetable"][0],
		"SHOW_TOP_DESCRIPTION" => "Y",
		"DETAIL_LINKED_TEMPLATE" => "table",
		"SEF_URL_TEMPLATES" => array(
			"news" => "",
			"section" => "#SECTION_CODE_PATH#/",
			"detail" => "#SECTION_CODE_PATH#/#ELEMENT_CODE#/",
		)
	),
	false
);?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>