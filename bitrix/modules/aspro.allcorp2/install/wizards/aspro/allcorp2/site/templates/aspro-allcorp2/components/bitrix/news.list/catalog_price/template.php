<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>
<?
$frame = $this->createFrame()->begin();
$frame->setAnimation(true);
global $arTheme;
$bShowImage = in_array('PREVIEW_PICTURE', $arParams['FIELD_CODE']);
$bShowOrderButton = in_array('FORM_ORDER', $arParams['PROPERTY_CODE']);
$bOrderViewBasket = $arParams['ORDER_VIEW'];
$basketURL = (strlen(trim($arTheme['ORDER_VIEW']['DEPENDENT_PARAMS']['URL_BASKET_SECTION']['VALUE'])) ? trim($arTheme['ORDER_VIEW']['DEPENDENT_PARAMS']['URL_BASKET_SECTION']['VALUE']) : '');
?>
<?
$bFromAjax = (isset($arParams['AJAX_REQUEST']) && $arParams['AJAX_REQUEST'] == 'Y');
$bHasSection = false;
if(isset($arResult['SECTION_CURRENT']) && $arResult['SECTION_CURRENT'] && !$bFromAjax)
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
<?if(!$bFromAjax):?>
	<table class="module_products_list"  itemscope itemtype="http://schema.org/ItemList">
		<tbody>
<?endif;?>
	<?if($arResult['ITEMS']):?>
		<?foreach($arResult['ITEMS'] as $arItem):?>
			<?
			// edit/add/delete buttons for edit mode
			$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem['IBLOCK_ID'], 'ELEMENT_EDIT'));
			$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem['IBLOCK_ID'], 'ELEMENT_DELETE'), array('CONFIRM' => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
			// use detail link?
			$bDetailLink = $arParams['SHOW_DETAIL_LINK'] != 'N' && (!strlen($arItem['DETAIL_TEXT']) ? ($arParams['HIDE_LINK_WHEN_NO_DETAIL'] !== 'Y' && $arParams['HIDE_LINK_WHEN_NO_DETAIL'] != 1) : true);

			// preview image
			if($bShowImage)
			{
				$bImage = (isset($arItem['FIELDS']['PREVIEW_PICTURE']) && $arItem['PREVIEW_PICTURE']['SRC']);
				$nImageID = ($bImage ? (is_array($arItem['FIELDS']['PREVIEW_PICTURE']) ? $arItem['FIELDS']['PREVIEW_PICTURE']['ID'] : $arItem['FIELDS']['PREVIEW_PICTURE']) : "");
				$arImage = ($bImage ? CFile::ResizeImageGet($nImageID, array('width' => 50, 'height' => 50), BX_RESIZE_IMAGE_PROPORTIONAL_ALT, true) : array());
				$imageSrc = ($bImage ? $arImage['src'] : SITE_TEMPLATE_PATH.'/images/noimage_product.png');
				$imageDetailSrc = ($bImage ? $arItem['DETAIL_PICTURE']['SRC'] : false);
			}

			// use order button?
			$bOrderButton = ($arItem['DISPLAY_PROPERTIES']['FORM_ORDER']['VALUE_XML_ID'] == 'YES');
			// use status label?
			$bStatusLabel = strlen($arItem['DISPLAY_PROPERTIES']['STATUS']['VALUE']);
			// show price?
			$bPrice = strlen($arItem['DISPLAY_PROPERTIES']['PRICE']['VALUE']);
			$dataItem = ($bOrderViewBasket ? CAllcorp2::getDataItem($arItem) : false);
			$elementName = ((isset($arItem['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE']) && $arItem['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE']) ? $arItem['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE'] : $arItem['NAME']);
			?>
			<tr class="item main_item_wrapper" id="<?=$this->GetEditAreaId($arItem['ID']);?>" <?=($bOrderViewBasket ? ' data-item="'.$dataItem.'"' : '')?> itemprop="itemListElement" itemscope="" itemtype="http://schema.org/Product">
				<td class="wrapper_td">
					<table>
						<tbody>
							<tr>
								<?// element picture?>
								<?if($bShowImage):?>
									<td class="foto-cell">
										<div class="image_wrapper_block">
											<?
											$a_alt=($arItem["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_ALT"] ? $arItem["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_ALT"] : $arItem["NAME"] );
											$a_title=($arItem["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_TITLE"] ? $arItem["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_TITLE"] : $arItem["NAME"] );
											?>
											<?if($bDetailLink):?><a href="<?=$arItem['DETAIL_PAGE_URL']?>">
											<?elseif($imageDetailSrc):?><a href="<?=$imageDetailSrc?>" alt="<?=$a_alt;?>" title="<?=$a_title;?>" class="img-inside fancybox">
											<?endif;?>

											<img class="img-responsive" src="<?=$imageSrc?>" alt="<?=$a_alt;?>" title="<?=$a_title;?>" />

											<?if($bDetailLink):?></a>
											<?elseif($imageDetailSrc):?><span class="zoom"></span></a>
											<?endif;?>
										</div>
									</td>
								<?endif;?>
								<td class="info-td">
									<table>
										<tbody>
											<tr>
												<?// element name, status, article?>
												<td class="item-name-cell">
													<div class="title"><?if($bDetailLink):?><a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="dark_link" itemprop="url"><?endif;?><span itemprop="name"><?=$elementName?></span><?if($bDetailLink):?></a><?endif;?></div>
													<?if(strlen($arItem['DISPLAY_PROPERTIES']['STATUS']['VALUE'])):?>
														<span class="status-icon <?=$arItem['DISPLAY_PROPERTIES']['STATUS']['VALUE_XML_ID']?>" itemprop="description"><?=$arItem['DISPLAY_PROPERTIES']['STATUS']['VALUE']?></span>
													<?endif;?>
													<?// element article?>
													<?if(strlen($arItem['DISPLAY_PROPERTIES']['ARTICLE']['VALUE'])):?>
														<span class="article" itemprop="description"><?=GetMessage('S_ARTICLE')?>&nbsp;<span><?=$arItem['DISPLAY_PROPERTIES']['ARTICLE']['VALUE']?></span></span>
													<?endif;?>
												</td>
												<?// element price?>
												<td class="price-cell">
													<div class="price-block">
														<?=\Aspro\Functions\CAsproAllcorp2::showPrice($arItem, $arParams, false);?>
													</div>
												</td>
												<td class="buy_block_wrapper">
													<?=\Aspro\Functions\CAsproAllcorp2::showBasketButton($arItem, $arParams, $bOrderButton, $bOrderViewBasket, $basketURL);?>
												</td>
											</tr>
										</tbody>
									</table>
								</td>
							</tr>
						</tbody>
					</table>
				</td>
			</tr>
		<?endforeach;?>
	<?endif;?>
<?if(!$bFromAjax){?>
		</tbody>
	</table>
<?}?>

<?if($bFromAjax){?>
	<div class="wrap_nav">
	<tr <?=($arResult["NavPageCount"]>1 ? "" : "style='display: none;'");?>><td>
<?}?>

	<div>
	<div class="bottom_nav <?=$arParams["DISPLAY_TYPE"];?>" <?=($bFromAjax  && $arResult["NavPageCount"]<=1 ? "style='display: none; '" : "");?>>
		<?if( $arParams["DISPLAY_BOTTOM_PAGER"] == "Y" ){?><?=$arResult["NAV_STRING"]?><?}?>
	</div>
	</div>

<?if($bFromAjax){?>
	</td></tr>
	</div>
<?}?>

<?if($bHasSection):?>
	</div>
<?endif;?>
<?$frame->end();?>