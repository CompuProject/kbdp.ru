<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
$this->setFrameMode(true);
if ($arParams['BX_EDITOR_RENDER_MODE'] == 'Y'):
?>
<img src="/bitrix/components/bitrix/map.yandex.view/templates/.default/images/screenshot.png" alt="map" />
<?
else:

	$arTransParams = array(
		'KEY' => $arParams['KEY'],
		'INIT_MAP_TYPE' => $arParams['INIT_MAP_TYPE'],
		'INIT_MAP_LON' => $arResult['POSITION']['yandex_lon'],
		'INIT_MAP_LAT' => $arResult['POSITION']['yandex_lat'],
		'INIT_MAP_SCALE' => $arResult['POSITION']['yandex_scale'],
		'MAP_WIDTH' => $arParams['MAP_WIDTH'],
		'MAP_HEIGHT' => $arParams['MAP_HEIGHT'],
		'CONTROLS' => $arParams['CONTROLS'],
		'OPTIONS' => $arParams['OPTIONS'],
		'MAP_ID' => $arParams['MAP_ID'],
		'LOCALE' => $arParams['LOCALE'],
		'ONMAPREADY' => 'BX_SetPlacemarks_'.$arParams['MAP_ID'],
	);

	if ($arParams['DEV_MODE'] == 'Y')
	{
		$arTransParams['DEV_MODE'] = 'Y';
		if ($arParams['WAIT_FOR_EVENT'])
			$arTransParams['WAIT_FOR_EVENT'] = $arParams['WAIT_FOR_EVENT'];
	}
?>

<script type="text/javascript">
var geo_result;
var clusterer;

function BX_SetPlacemarks_<?echo $arParams['MAP_ID']?>(map)
{
	if(typeof window["BX_YMapAddPlacemark"] != 'function')
	{
		/* If component's result was cached as html,
		 * script.js will not been loaded next time.
		 * let's do it manualy.
		*/

		(function(d, s, id)
		{
			var js, bx_ym = d.getElementsByTagName(s)[0];
			if (d.getElementById(id)) return;
			js = d.createElement(s); js.id = id;
			js.src = "<?=$templateFolder.'/script.js'?>";
			bx_ym.parentNode.insertBefore(js, bx_ym);
		}(document, 'script', 'bx-ya-map-js'));

		var ymWaitIntervalId = setInterval( function(){
				if(typeof window["BX_YMapAddPlacemark"] == 'function')
				{
					BX_SetPlacemarks_<?echo $arParams['MAP_ID']?>(map);
					clearInterval(ymWaitIntervalId);
				}
			}, 300
		);

		return;
	}
	var arObjects = {PLACEMARKS:[],POLYLINES:[]};
	clusterer = new ymaps.Clusterer();

<?
if( is_array($arResult['POSITION']['PLACEMARKS']) && ($cnt = count($arResult['POSITION']['PLACEMARKS'])) ){
	for( $i = 0; $i < $cnt; $i++ ){
?>
	arObjects.PLACEMARKS[arObjects.PLACEMARKS.length] = BX_YMapAddPlacemark(map, <?echo CUtil::PhpToJsObject($arResult['POSITION']['PLACEMARKS'][$i])?><?if(count($arResult['POSITION']['PLACEMARKS'])>1):?>, true<?endif;?>);
<?	
	}
}
?>
	// map.geoObjects.add(clusterer);

<?
	if (is_array($arResult['POSITION']['POLYLINES']) && ($cnt = count($arResult['POSITION']['POLYLINES']))):
		for($i = 0; $i < $cnt; $i++):
?>
	arObjects.POLYLINES[arObjects.POLYLINES.length] = BX_YMapAddPolyline(map, <?echo CUtil::PhpToJsObject($arResult['POSITION']['POLYLINES'][$i])?>);
<?
		endfor;
	endif;	

	if ($arParams['ONMAPREADY']):
?>
	if (window.<?echo $arParams['ONMAPREADY']?>)
	{
		window.<?echo $arParams['ONMAPREADY']?>(map, arObjects);
	}
<?
	endif;
?>
	<?/*if(count($arResult['POSITION']['PLACEMARKS'])>1):?>
		clusterer = new ymaps.Clusterer();
		clusterer.add(arObjects.PLACEMARKS);
		map.geoObjects.add(clusterer);
	<?endif;*/?>
	/* set dynamic zoom for ballons */
	// map.setBounds(map.geoObjects.getBounds(), {checkZoomRange: true});
	geo_result = ymaps.geoQuery(map.geoObjects);
	if(geo_result.getLength() > 1){
		geo_result.applyBoundsToMap(map);
	}else
	{
		map.zoomRange.get().then(function(range){
	        // map.setZoom(range[1]);
	    });
	}
	/**/
}
</script>
<div class="bx-yandex-view-layout front_map swipeignore">
		<div class="maxwidth-theme">
			<div class="pane_info_wrapper">
				<div class="pane_info">
					<div class="title_block">
						<div class="title"><?=GetMessage('CONACT_TITLE')?></div>
					</div>
					<div class="info_block">
						<?global $arTheme;
						$bPhone = (intval($arTheme['HEADER_PHONES']) > 0 ? true : false);?>
						<?//check address text?>
						<?if(CAllcorp2::checkContentFile(SITE_DIR.'include/header/site-address.php')):?>
							<div class="value-block address">
								<div>
									<?=CAllcorp2::showIconSvg("address colored", SITE_TEMPLATE_PATH."/images/svg/Addres_black.svg");?>
									<div>
										<?$APPLICATION->IncludeFile(SITE_DIR."include/header/site-address.php", array(), array(
												"MODE" => "html",
												"NAME" => "Address in title",
												"TEMPLATE" => "include_area.php",
											)
										);?>
									</div>
								</div>
							</div>
						<?endif;?>
						<?//check phone text?>
						<?if($bPhone):?>
							<div class="phones">
								<?CAllcorp2::ShowHeaderPhones('big', 'Phone_black.svg');?>
								<div class="schedule">
									<?$APPLICATION->IncludeFile(SITE_DIR."include/header-schedule.php", array(), array(
											"MODE" => "html",
											"NAME" => GetMessage('HEADER_SCHEDULE'),
											"TEMPLATE" => "include_area.php"
										)
									);?>
								</div>
							</div>
						<?endif?>
						<?//check address text?>
						<?if(CAllcorp2::checkContentFile(SITE_DIR.'include/footer/site-email.php')):?>
							<div class="value-block email">
								<div>
									<?=CAllcorp2::showIconSvg("address colored", SITE_TEMPLATE_PATH."/images/svg/Email.svg");?>
									<div>
										<?$APPLICATION->IncludeFile(SITE_DIR."include/footer/site-email.php", array(), array(
												"MODE" => "html",
												"NAME" => "Address in title",
												"TEMPLATE" => "include_area.php",
											)
										);?>
									</div>
								</div>
							</div>
						<?endif;?>
						<div class="btn-block">
							<span class="ask-block animate-load colored  btn-transparent-bg btn-default btn" data-event="jqm" data-param-id="<?=CAllcorp2::getFormID('aspro_allcorp2_question')?>" data-name="ask"><?=GetMessage('S_ASK_QUESTION')?></span>
						</div>
					</div>
				</div>
			</div>
		</div>
	<div class="bx-yandex-view-map">
<?
	$APPLICATION->IncludeComponent('bitrix:map.yandex.system', 'front_map', $arTransParams, false, array('HIDE_ICONS' => 'Y'));
?>
	</div>
</div>
<?
endif;
?>