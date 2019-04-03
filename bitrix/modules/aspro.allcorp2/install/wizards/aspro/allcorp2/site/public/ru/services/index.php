<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Услуги");
?>
<?$APPLICATION->IncludeComponent(
	"bitrix:news", 
	"services", 
	array(
		"IBLOCK_TYPE" => "aspro_allcorp2_content",
		"IBLOCK_ID" => "#IBLOCK_SERVICES_ID#",
		"NEWS_COUNT" => "20",
		"USE_SEARCH" => "N",
		"USE_RSS" => "N",
		"USE_RATING" => "N",
		"USE_CATEGORIES" => "N",
		"USE_FILTER" => "Y",
		"FILTER_NAME" => "arrFilter",
		"FILTER_FIELD_CODE" => array(
			0 => "",
			1 => "",
		),
		"FILTER_PROPERTY_CODE" => array(
			0 => "",
			1 => "",
		),
		"SORT_BY1" => "SORT",
		"SORT_ORDER1" => "ASC",
		"SORT_BY2" => "ID",
		"SORT_ORDER2" => "DESC",
		"CHECK_DATES" => "Y",
		"SEF_MODE" => "Y",
		"SEF_FOLDER" => "#SITE_DIR#services/",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"AJAX_OPTION_HISTORY" => "N",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "100000",
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
			3 => "",
		),
		"LIST_PROPERTY_CODE" => array(
			0 => "PRICEOLD",
			1 => "PRICE",
			2 => "FORM_ORDER",
			3 => "",
		),
		"HIDE_LINK_WHEN_NO_DETAIL" => "N",
		"DISPLAY_NAME" => "N",
		"META_KEYWORDS" => "-",
		"META_DESCRIPTION" => "-",
		"BROWSER_TITLE" => "-",
		"DETAIL_ACTIVE_DATE_FORMAT" => "d.m.Y",
		"DETAIL_FIELD_CODE" => array(
			0 => "PREVIEW_TEXT",
			1 => "DETAIL_TEXT",
			2 => "DETAIL_PICTURE",
			3 => "",
		),
		"DETAIL_PROPERTY_CODE" => array(
			0 => "LINK_FAQ",
			1 => "AUTHOR_CONTROL",
			2 => "LINK_SALE",
			3 => "PROP2",
			4 => "DISEGHNER_AT_PLACE",
			5 => "LINK_STUDY",
			6 => "for_show_1",
			7 => "LINK_NEWS",
			8 => "MATERIAL_PICK",
			9 => "for_show_2",
			10 => "for_show_3",
			11 => "PRICEOLD",
			12 => "LINK_ARTICLES",
			13 => "PROP1",
			14 => "TYPE_OF_BUILD",
			15 => "LINK_SERVICES",
			16 => "PRICE",
			17 => "LINK_GOODS",
			18 => "LINK_STAFF",
			19 => "LINK_REVIEWS",
			20 => "LINK_PROJECTS",
			21 => "FORM_ORDER",
			22 => "FORM_QUESTION",
			23 => "STATUS",
			24 => "ARTICLE",
			25 => "SUPPLIED",
			26 => "AGE",
			27 => "KARTOPR",
			28 => "HEIGHT",
			29 => "DEPTH",
			30 => "DEEP",
			31 => "GRUZ_STRELI",
			32 => "GRUZ",
			33 => "DIAGONAL",
			34 => "DLINA_STRELI",
			35 => "DLINA",
			36 => "CATEGORY",
			37 => "CLASS",
			38 => "CLIMAT_CLASS",
			39 => "KOL_FORMULA",
			40 => "USERS",
			41 => "EXTENSION",
			42 => "MARK_STEEL",
			43 => "MASS",
			44 => "MODEL",
			45 => "POWER",
			46 => "UPDATES",
			47 => "VOLUME",
			48 => "PROIZVODSTVO",
			49 => "SIZE",
			50 => "PLACE",
			51 => "RESOLUTION",
			52 => "LIGHTS_LOCATION",
			53 => "RECOMMEND",
			54 => "SERIES",
			55 => "SPEED",
			56 => "DURATION",
			57 => "TEMPERATURE",
			58 => "LINK_TIZERS",
			59 => "TYPE",
			60 => "TYPE_TUR",
			61 => "THICKNESS",
			62 => "MARK",
			63 => "INCREASE",
			64 => "COLOR",
			65 => "FREQUENCY",
			66 => "FREQUENCE",
			67 => "WIDTH_PROHOD",
			68 => "WIDTH_PROEZD",
			69 => "WIDTH",
			70 => "LANGUAGES",
			71 => "DOCUMENTS",
			72 => "PHOTOS",
			73 => "",
		),
		"DETAIL_DISPLAY_TOP_PAGER" => "N",
		"DETAIL_DISPLAY_BOTTOM_PAGER" => "Y",
		"DETAIL_PAGER_TITLE" => "Страница",
		"DETAIL_PAGER_TEMPLATE" => "",
		"DETAIL_PAGER_SHOW_ALL" => "Y",
		"PAGER_TEMPLATE" => ".default",
		"DISPLAY_TOP_PAGER" => "N",
		"DISPLAY_BOTTOM_PAGER" => "Y",
		"PAGER_TITLE" => "Новости",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALL" => "N",
		"IMAGE_POSITION" => "left",
		"USE_SHARE" => "Y",
		"AJAX_OPTION_ADDITIONAL" => "",
		"USE_REVIEW" => "N",
		"ADD_ELEMENT_CHAIN" => "Y",
		"IMAGE_CATALOG_POSITION" => "left",
		"SHOW_DETAIL_LINK" => "Y",
		"S_ASK_QUESTION" => "",
		"S_ORDER_SERVICE" => "",
		"T_GALLERY" => "",
		"T_DOCS" => "",
		"T_GOODS" => "Товары",
		"T_SERVICES" => "",
		"T_PROJECTS" => "",
		"T_REVIEWS" => "",
		"T_STAFF" => "Получите консультацию специалиста",
		"COMPONENT_TEMPLATE" => "services",
		"SET_LAST_MODIFIED" => "Y",
		"T_VIDEO" => "",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO",
		"VIEW_TYPE" => "row_block",
		"DETAIL_SET_CANONICAL_URL" => "N",
		"PAGER_BASE_LINK_ENABLE" => "N",
		"SHOW_404" => "N",
		"MESSAGE_404" => "",
		"VIEW_TYPE_SECTION" => "row_block",
		"SHOW_CHILD_SECTIONS" => "Y",
		"GALLERY_TYPE" => "small",
		"PREVIEW_REVIEW_TRUNCATE_LEN" => "255",
		"SECTIONS_TYPE_VIEW" => "FROM_MODULE",
		"SECTION_TYPE_VIEW" => "FROM_MODULE",
		"ELEMENT_TYPE_VIEW" => "FROM_MODULE",
		"SHOW_SECTION_PREVIEW_DESCRIPTION" => "Y",
		"LINE_ELEMENT_COUNT" => "2",
		"SECTION_ELEMENTS_TYPE_VIEW" => "FROM_MODULE",
		"SHOW_SECTION_DESCRIPTION" => "Y",
		"LINE_ELEMENT_COUNT_LIST" => "3",
		"S_ORDER_SERVISE" => "",
		"FORM_ID_ORDER_SERVISE" => "",
		"T_NEXT_LINK" => "",
		"T_PREV_LINK" => "",
		"SHOW_NEXT_ELEMENT" => "N",
		"IMAGE_WIDE" => "N",
		"DETAIL_STRICT_SECTION_CHECK" => "N",
		"STRICT_SECTION_CHECK" => "Y",
		"REVIEWS_IBLOCK_ID" => CCache::$arIBlocks[SITE_ID]["aspro_allcorp2_content"]["aspro_allcorp2_reviews"][0],
		"PROJECTS_IBLOCK_ID" => CCache::$arIBlocks[SITE_ID]["aspro_allcorp2_content"]["aspro_allcorp2_projects"][0],
		"SERVICES_IBLOCK_ID" => CCache::$arIBlocks[SITE_ID]["aspro_allcorp2_content"]["aspro_allcorp2_services"][0],
		"STAFF_IBLOCK_ID" => CCache::$arIBlocks[SITE_ID]["aspro_allcorp2_content"]["aspro_allcorp2_staff"][0],
		"PARTNERS_IBLOCK_ID" => CCache::$arIBlocks[SITE_ID]["aspro_allcorp2_content"]["aspro_allcorp2_partners"][0],
		"CATALOG_IBLOCK_ID" => CCache::$arIBlocks[SITE_ID]["aspro_allcorp2_catalog"]["aspro_allcorp2_catalog"][0],
		"NEWS_IBLOCK_ID" => CCache::$arIBlocks[SITE_ID]["aspro_allcorp2_content"]["aspro_allcorp2_news"][0],
		"DETAIL_USE_COMMENTS" => "Y",
		"DETAIL_BLOG_USE" => "Y",
		"DETAIL_BLOG_URL" => "catalog_comments",
		"COMMENTS_COUNT" => "5",
		"BLOG_TITLE" => "Комментарии",
		"DETAIL_BLOG_EMAIL_NOTIFY" => "Y",
		"DETAIL_VK_USE" => "N",
		"DETAIL_FB_USE" => "Y",
		"SHOW_CHILD_ELEMENTS" => "Y",
		"LINE_ELEMENT_COUNT" => "2",
		"SHOW_ELEMENTS_IN_LAST_SECTION" => "Y",
		"LIST_PRODUCT_BLOCKS_TAB_ORDER" => "char,desc,video,projects,docs,faq,articles,dops",
		"LIST_PRODUCT_BLOCKS_ALL_ORDER" => "desc,sale,char,projects,faq,docs,video,gallery,services,goods,comments",
		"T_CHARACTERISTICS" => "",
		"T_FAQ" => "",
		"T_DESC" => "",
		"LIST_PRODUCT_BLOCKS_ORDER" => "sale,tab,gallery,services,reviews,news,staff,goods,articles,study,docs,comments",
		"FAQ_IBLOCK_ID" => CCache::$arIBlocks[SITE_ID]["aspro_allcorp2_content"]["aspro_allcorp2_faq"][0],
		"T_NEWS" => "",
		"SALES_IBLOCK_ID" => CCache::$arIBlocks[SITE_ID]["aspro_allcorp2_content"]["aspro_allcorp2_sales"][0],
		"ELEMENTS_TABLE_TYPE_VIEW" => "FROM_MODULE",
		"FB_TITLE" => "Facebook",
		"DETAIL_FB_APP_ID" => "",
		"T_STUDY" => "",
		"T_ARTICLES" => "",
		"TAB_DOPS_NAME" => "",
		"STUDY_IBLOCK_ID" => CCache::$arIBlocks[SITE_ID]["aspro_allcorp2_catalog"]["aspro_allcorp2_timetable"][0],
		"ARTICLES_IBLOCK_ID" => CCache::$arIBlocks[SITE_ID]["aspro_allcorp2_content"]["aspro_allcorp2_articles"][0],
		"SHOW_ADDITIONAL_TAB" => "N",
		"SEF_URL_TEMPLATES" => array(
			"news" => "",
			"section" => "#SECTION_CODE_PATH#/",
			"detail" => "#SECTION_CODE_PATH#/#ELEMENT_CODE#/",
		)
	),
	false
);?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>