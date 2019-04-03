<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>
<?
$frame = $this->createFrame()->begin();
$frame->setAnimation(true);
global $arTheme;
$bShowImage = in_array('PREVIEW_PICTURE', $arParams['FIELD_CODE']);
$bOrderViewBasket = $arParams['ORDER_VIEW'];

$basketURL = (strlen(trim($arTheme['ORDER_VIEW']['DEPENDENT_PARAMS']['URL_BASKET_SECTION']['VALUE'])) ? trim($arTheme['ORDER_VIEW']['DEPENDENT_PARAMS']['URL_BASKET_SECTION']['VALUE']) : '');
?>
<?
$bHasSection = false;
if(isset($arResult['SECTION_CURRENT']) && $arResult['SECTION_CURRENT'] && $arParams['AJAX_REQUEST'] != 'Y')
	$bHasSection = true;
if($bHasSection)
{
	// edit/add/delete buttons for edit mode
	$arSectionButtons = CIBlock::GetPanelButtons($arParams['IBLOCK_ID'], 0, $arResult['SECTION_CURRENT']['ID'], array('SESSID' => false, 'CATALOG' => true));
	$this->AddEditAction($arResult['SECTION_CURRENT']['ID'], $arSectionButtons['edit']['edit_section']['ACTION_URL'], CIBlock::GetArrayByID($arParams['IBLOCK_ID'], 'SECTION_EDIT'));
	$this->AddDeleteAction($arResult['SECTION_CURRENT']['ID'], $arSectionButtons['edit']['delete_section']['ACTION_URL'], CIBlock::GetArrayByID($arParams['IBLOCK_ID'], 'SECTION_DELETE'), array('CONFIRM' => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
	?>
	<div class="section" id="<?=$this->GetEditAreaId($arResult['SECTION_CURRENT']['ID'])?>">
	<?
}?>
<?if($arResult['ITEMS']):?>
	<?if($arParams['AJAX_REQUEST'] != 'Y'):?>
	<div class="catalog item-views list image-<?=$arParams['IMAGE_POSITION']?>">
		<?if($arParams['DISPLAY_TOP_PAGER']):?>
			<?=$arResult['NAV_STRING']?>
		<?endif;?>
		<div class="row items" itemscope itemtype="http://schema.org/ItemList">
	<?endif;?>
			<?foreach($arResult['ITEMS'] as $arItem):?>
				<?
				// edit/add/delete buttons for edit mode
				$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem['IBLOCK_ID'], 'ELEMENT_EDIT'));
				$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem['IBLOCK_ID'], 'ELEMENT_DELETE'), array('CONFIRM' => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
				// use detail link?
				$bDetailLink = $arParams['SHOW_DETAIL_LINK'] != 'N' && (!strlen($arItem['DETAIL_TEXT']) ? ($arParams['HIDE_LINK_WHEN_NO_DETAIL'] !== 'Y' && $arParams['HIDE_LINK_WHEN_NO_DETAIL'] != 1) : true);
				// preview image
				if($bShowImage){
					$bImage = (isset($arItem['FIELDS']['PREVIEW_PICTURE']) && $arItem['PREVIEW_PICTURE']['SRC']);
					$nImageID = ($bImage ? (is_array($arItem['FIELDS']['PREVIEW_PICTURE']) ? $arItem['FIELDS']['PREVIEW_PICTURE']['ID'] : $arItem['FIELDS']['PREVIEW_PICTURE']) : "");
					$arImage = ($bImage ? CFile::ResizeImageGet($nImageID, array('width' => 244, 'height' => 244), BX_RESIZE_IMAGE_PROPORTIONAL_ALT, true) : array());
					$imageSrc = ($bImage ? $arImage['src'] : SITE_TEMPLATE_PATH.'/images/noimage_product.png');
					$imageDetailSrc = ($bImage ? $arItem['DETAIL_PICTURE']['SRC'] : false);
				}
				// use order button?
				$bOrderButton = ($arItem['DISPLAY_PROPERTIES']['FORM_ORDER']['VALUE_XML_ID'] == 'YES');
				$dataItem = ($bOrderViewBasket ? CAllcorp2::getDataItem($arItem) : false);
				?>
				<?ob_start();?>
					<?if($bShowImage):?>
						<div class="image-wrapper">
							<?if($arItem['PROPERTIES']['HIT']['VALUE']):?>
								<div class="stickers">
									<div class="stickers-wrapper">
										<?foreach($arItem['PROPERTIES']['HIT']['VALUE_XML_ID'] as $key => $class):?>
											<div class="sticker_<?=strtolower($class);?>"><?=$arItem['PROPERTIES']['HIT']['VALUE'][$key]?></div>
										<?endforeach;?>
									</div>
								</div>
							<?endif;?>
							<div class="image">
								<?
								$a_alt=($arItem["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_ALT"] ? $arItem["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_ALT"] : $arItem["NAME"] );
								$a_title=($arItem["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_TITLE"] ? $arItem["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_TITLE"] : $arItem["NAME"] );
								?>
								<?if($bDetailLink):?><a href="<?=$arItem['DETAIL_PAGE_URL']?>" itemprop="url">
								<?elseif($imageDetailSrc):?><a href="<?=$imageDetailSrc?>" alt="<?=$a_alt;?>" title="<?=$a_title;?>" class="img-inside fancybox" itemprop="url">
								<?endif;?>
									<img class="img-responsive" src="<?=$imageSrc?>" alt="<?=$a_alt;?>" title="<?=$a_title;?>" itemprop="image" />
								<?if($bDetailLink):?></a>
								<?elseif($imageDetailSrc):?><span class="zoom"></span></a>
								<?endif;?>
							</div>
						</div>
					<?endif;?>
				<?$imgPart = ob_get_clean();?>

				<?ob_start();?>
					<div class="text">
						<div class="row">
							<?$colmd = 12 - (strlen($arItem['DISPLAY_PROPERTIES']['PRICE']['VALUE']) || $bOrderButton ? ($bShowImage ? 3 : 2) : 0);?>
							<div class="col-md-7 col-sm-6 col-xs-12">
								<div class="cont">
									<?// element name?>
									<?if(strlen($arItem['FIELDS']['NAME'])):?>
										<div class="title">
											<?if($bDetailLink):?><a href="<?=$arItem['DETAIL_PAGE_URL']?>" itemprop="url"><?endif;?>
												<span itemprop="name"><?=$arItem['NAME']?></span>
											<?if($bDetailLink):?></a><?endif;?>
										</div>
									<?endif;?>

									<?// element status?>
									<?if(strlen($arItem['DISPLAY_PROPERTIES']['STATUS']['VALUE'])):?>
										<span class="status-icon <?=$arItem['DISPLAY_PROPERTIES']['STATUS']['VALUE_XML_ID']?>" itemprop="description"><?=$arItem['DISPLAY_PROPERTIES']['STATUS']['VALUE']?></span>
									<?endif;?>

									<?// element article?>
									<?if(strlen($arItem['DISPLAY_PROPERTIES']['ARTICLE']['VALUE'])):?>
										<span class="article" itemprop="description"><?=GetMessage('S_ARTICLE')?>&nbsp;<span><?=$arItem['DISPLAY_PROPERTIES']['ARTICLE']['VALUE']?></span></span>
									<?endif;?>

									<?// element preview text?>
									<?if(strlen($arItem['FIELDS']['PREVIEW_TEXT'])):?>
										<div class="description" itemprop="description">
											<?if($arItem['PREVIEW_TEXT_TYPE'] == 'text'):?>
												<p><?=$arItem['FIELDS']['PREVIEW_TEXT']?></p>
											<?else:?>
												<?=$arItem['FIELDS']['PREVIEW_TEXT']?>
											<?endif;?>
										</div>
									<?endif;?>
									<?if($arItem['HOVER_PROPS']):?>
										<div class="props_list_wrapp">
											<div class="props_list prod">
												<?foreach( $arItem["HOVER_PROPS"] as $arProp ){?>
													<?if( !empty( $arProp["VALUE"] ) ){?>
														<div class="char">
															<span class="name"><?=$arProp["NAME"]?>:</span>
															<span>
															<?
															if(count($arProp["DISPLAY_VALUE"])>1) { foreach($arProp["DISPLAY_VALUE"] as $key => $value) { if ($arProp["DISPLAY_VALUE"][$key+1]) {echo $value.", ";} else {echo $value;} }}
															else { echo $arProp["DISPLAY_VALUE"]; }
															?>
															</span>
														</div>
													<?}?>
												<?}?>
											</div>
											<div class="show_props">
												<span class="icons_fa char_title dark_link">
													<span><?=GetMessage('PROPERTIES')?></span>
													<?=CAllcorp2::showIconSvg('char op', SITE_TEMPLATE_PATH.'/images/svg/more_arrow.svg');?>
													<?=CAllcorp2::showIconSvg('char cl', SITE_TEMPLATE_PATH.'/images/svg/less_arrow.svg');?>
												</span>
											</div>
										</div>
									<?endif;?>
								</div>
							</div>

							<?if(strlen($arItem['DISPLAY_PROPERTIES']['PRICE']['VALUE']) || $bOrderButton):?>
								<div class="col-md-5 col-sm-6 col-xs-12">
									<div class="foot pull-right">
										<?=\Aspro\Functions\CAsproAllcorp2::showPrice($arItem, $arParams, false);?>
										<?=\Aspro\Functions\CAsproAllcorp2::showBasketButton($arItem, $arParams, $bOrderButton, $bOrderViewBasket, $basketURL);?>
									</div>
								</div>
							<?endif;?>
						</div>
					</div>
				<?$textPart = ob_get_clean();?>

				<div class="col-md-12 col-sm-12 col-xs-12">
					<div class="item<?=($bShowImage ? '' : ' wti')?>" id="<?=$this->GetEditAreaId($arItem['ID'])?>"<?=($bOrderViewBasket ? ' data-item="'.$dataItem.'"' : '')?> itemprop="itemListElement" itemscope="" itemtype="http://schema.org/Product">
						<div class="row">
							<?if(!$bShowImage):?>
								<div class="col-md-12">
									<?=$textPart?>
								</div>
							<?elseif($arParams['IMAGE_POSITION'] == 'right'):?>
								<div class="col-md-9 col-sm-9">
									<?=$textPart?>
								</div>
								<div class="col-md-3 col-sm-3">
									<?=$imgPart?>
								</div>
							<?else:?>
								<div class="col-md-3 col-sm-3">
									<?=$imgPart?>
								</div>
								<div class="col-md-9 col-sm-9">
									<?=$textPart?>
								</div>
							<?endif;?>
						</div>
					</div>
				</div>
			<?endforeach;?>
			<script>
			$(document).ready(function(){
				setBasketItemsClasses();
			});
			</script>
	<?if($arParams['AJAX_REQUEST'] != 'Y'):?>
		</div>
	<?endif;?>
		<?if($arParams["AJAX_REQUEST"]=="Y"):?>
			<div class="wrap_nav">
		<?endif;?>
		<div class="bottom_nav <?=$arParams["DISPLAY_TYPE"];?>" <?=($arParams["AJAX_REQUEST"]=="Y" ? "style='display: none; '" : "");?>>
			<?if( $arParams["DISPLAY_BOTTOM_PAGER"] == "Y" ){?><?=$arResult["NAV_STRING"]?><?}?>
		</div>
		<?if($arParams["AJAX_REQUEST"]=="Y"):?>
			</div>
		<?endif;?>
	<?if($arParams['AJAX_REQUEST'] != 'Y'):?>
	</div>
	<?endif;?>
<?endif;?>
<?if($bHasSection):?>
	</div>
<?endif;?>
<?$frame->end();?>