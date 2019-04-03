<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true ) die();?>
<?$this->setFrameMode(true);?>
<?if($arResult['ITEMS']):?>
	<?if($arParams['SHOW_TITLE'] == 'Y'):?>
		<div class="title-tab-heading visible-xs"><?=$arParams['T_PROJECTS'];?></div>
	<?endif;?>
	<div class="projects item-views table <?=($arParams['BIG_BLOCK'] == 'Y' ? 'bblock' : '');?>">
		<div class="row items shadow flexbox">
			<?foreach($arResult['ITEMS'] as $i => $arItem):?>
				<?
				// edit/add/delete buttons for edit mode
				$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem['IBLOCK_ID'], 'ELEMENT_EDIT'));
				$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem['IBLOCK_ID'], 'ELEMENT_DELETE'), array('CONFIRM' => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
				// use detail link?
				$bDetailLink = $arParams['SHOW_DETAIL_LINK'] != 'N' && (!strlen($arItem['DETAIL_TEXT']) ? ($arParams['HIDE_LINK_WHEN_NO_DETAIL'] !== 'Y' && $arParams['HIDE_LINK_WHEN_NO_DETAIL'] != 1) : true);
				// preview image
				$thumb = CFile::GetPath($arItem['PREVIEW_PICTURE']['ID'] ? $arItem['PREVIEW_PICTURE']['ID'] : $arItem['DETAIL_PICTURE']['ID']);
				?>
				<div class="col-md-<?=floor(12 / $arParams['COUNT_IN_LINE'])?>">
					<div class="item" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
						<?// preview picture?>
						<div class="image <?=($thumb ? 'w-picture' : 'wo-picture');?> shine">
							<a href="<?=$arItem['DETAIL_PAGE_URL']?>">
								<?if($thumb):?>
									<img src="<?=$thumb?>" alt="<?=$arItem['NAME']?>" title="<?=$arItem['NAME']?>" class="img-responsive" />
								<?else:?>
									<img class="img-responsive" src="<?=SITE_TEMPLATE_PATH?>/images/noimage.png" alt="<?=$arItem['NAME']?>" title="<?=$arItem['NAME']?>" />
								<?endif;?>
							</a>
						</div>
						<?if(($arItem["DISPLAY_ACTIVE_FROM"] || $arItem["DISPLAY_PROPERTIES"]["PERIOD"]["VALUE"]) && $arParams["SHOW_DATE"]=="Y"){?>
							<div class="date"><?=($arItem["DISPLAY_PROPERTIES"]["PERIOD"]["VALUE"] ? $arItem["DISPLAY_PROPERTIES"]["PERIOD"]["VALUE"] : $arItem["DISPLAY_ACTIVE_FROM"]);?></div>
						<?}?>
						<div class="info">
							<?// element name?>
							<div class="title dark-color">
								<a href="<?=$arItem['DETAIL_PAGE_URL']?>">
									<span><?=$arItem['NAME']?></span>
								</a>
							</div>
						</div>
					</div>
				</div>
			<?endforeach;?>
		</div>
	</div>
<?endif;?>