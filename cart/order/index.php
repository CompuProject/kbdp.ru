<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Оформление заказа");?>
<?$APPLICATION->IncludeComponent(
	"aspro:basket.allcorp2",
	"order",
	Array(
		"COMPONENT_TEMPLATE" => "order",
		"PATH_TO_CATALOG" => SITE_DIR."product/"
	)
);?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>