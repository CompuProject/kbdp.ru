<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$arServices = Array(
	"main" => array(
		"NAME" => GetMessage("SERVICE_MAIN_SETTINGS"),
		"STAGES" => array(
			"public.php",
			"template.php",
			"theme.php",
			"menu.php",
			"settings.php",
		),
	),
	"iblock" => Array(
		"NAME" => GetMessage("SERVICE_IBLOCK_DEMO_DATA"),
		"STAGES" => Array(
			"types.php",
			"regions.php",
			"advtbig.php",
			"banners.php",
			"bg_images.php",
			"smbanners.php",
			"float_banners.php",
			"front_tizers.php",
			"tizers_landing.php",
			"history.php",
			"reviews.php",
			"staff.php",
			"vacancy.php",
			"faq.php",
			"licenses.php",
			"hl_company.php",
			"hl_company_content.php",
			"hl_contact.php",
			"hl_contact_content.php",
			"news_personal.php",
			"partners.php",
			"forms.php",
			"contact.php",
			"news.php",
			"sales.php",
			"projects.php",
			"services.php",
			"articles.php",
			"tarifs.php",
			"timetable.php",
			"catalog.php",
			"catalog_info.php",
			"landing.php",
			"props.php",
			//"links.php",
		),
	),
	"form" => array(
		"NAME" => GetMessage("SERVICE_FORM_DEMO_DATA"),
		"STAGES" => array(
			"toorder.php",
			"director.php",
			"order_product.php",
			"order_project.php",
			"order_services.php",
			"order_study.php",
			"question.php",
			"resume.php",
			"callback.php",
			"callstaff.php",
			"feedback.php",
			//"errors_updates.php",
		)
	),
	/*"search" => array(
		"NAME" => GetMessage("SERVICE_SEARCH"),
		"STAGES" => array(
			"search.php",
		),
	),*/
);
?>