<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var CBitrixComponentTemplate $this */
/** @var array $arParams */
/** @var array $arResult */
/** @global CDatabase $DB */

$this->setFrameMode(true);

use \Bitrix\Main\Localization\Loc;

global $arTheme;
if($arResult['REGIONS']):?>
	<div class="region_wrapper">
		<div class="io_wrapper">
			<div>
				<div class="js_city_chooser dark-color list with_dropdown" data-param-url="<?=urlencode($APPLICATION->GetCurUri());?>" data-param-form_id="city_chooser">
					<?//=CAllcorp2::showIconSvg('region colored', SITE_TEMPLATE_PATH.'/images/svg/region.svg');?>
						<span><?=$arResult['CURRENT_REGION']['NAME'];?></span><span class="arrow"><?=CAllcorp2::showIconSvg("", SITE_TEMPLATE_PATH."/images/svg/more_arrow.svg", "", "", false);?></span>
				</div>
			</div>
			<div class="dropdown">
				<div class="wrap">
					<?foreach($arResult['REGIONS'] as $id => $arItem):?>
						<div class="more_item <?=($id == $arResult['CURRENT_REGION']['ID'] ? 'current' : '');?>">
							<span data-region_id="<?=$arItem['ID']?>" data-href="<?=$arItem['URL'];?>"><?=$arItem['NAME'];?></span>
						</div>
					<?endforeach;?>
				</div>
			</div>
			<?if($arResult['SHOW_REGION_CONFIRM']):?>
				<div class="confirm_region">
					<?
					$href = 'data-href="'.$arResult['REGIONS'][$arResult['REAL_REGION']['ID']]['URL'].'"';
					if($arTheme['USE_REGIONALITY']['DEPENDENT_PARAMS']['REGIONALITY_TYPE']['VALUE'] == 'SUBDOMAIN' && ($arResult['HOST'].$_SERVER['HTTP_HOST'].$arResult['URI'] == $arResult['REGIONS'][$arResult['REAL_REGION']['ID']]['URL']))
					$href = '';?>
					<div class="title"><?=Loc::getMessage('CITY_TITLE');?><span class="city"><?=$arResult['REAL_REGION']['NAME'];?>?</span></div>
					<div class="buttons">
						<span><span class="btn btn-default aprove" data-id="<?=$arResult['REAL_REGION']['ID'];?>" <?=$href;?>><?=Loc::getMessage('CITY_YES');?></span></span>
						<span><span class="btn btn-default btn-transparent-bg js_city_change"><?=Loc::getMessage('CITY_CHANGE');?></span></span>
					</div>
				</div>
			<?endif;?>
		</div>
	</div>
<?endif;?>