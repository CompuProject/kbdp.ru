<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if (!empty($arResult['ERROR']))
{
	echo $arResult['ERROR'];
	return false;
}
if($arResult):?>
	<div class="row profit-front-block">
		<?foreach($arResult['rows'] as $key => $arItem):?>
			<div class="col-lg-3 col-md-6 col-sm-6 col-xs-6">
				<div class="item props">
					<?if($arItem['UF_FILE']):?>
						<div class="image"><?=str_replace("border=\"0\"", "", $arItem['UF_FILE']);?></div>
					<?endif;?>
					<div class="body-info">
						<?if(isset($arItem['UF_DESCRIPTION']) && $arItem['UF_DESCRIPTION']):?>
							<div class="value"><?if(isset($arItem['UF_FULL_DESCRIPTION']) && $arItem['UF_FULL_DESCRIPTION']):?><?=str_replace("&nbsp;", "", $arItem['UF_FULL_DESCRIPTION']);?><?endif;?><span <?=((isset($arItem['UF_CLASS']) && $arItem['UF_CLASS']) ? "class=".$arItem['UF_CLASS'] : "")?> data-value="<?=$arItem['UF_DESCRIPTION'];?>"><?=((int)$arItem['UF_DESCRIPTION'] ? 0 : $arItem['UF_DESCRIPTION']);?></span><?if(isset($arItem['UF_AFTER_TEXT']) && $arItem['UF_AFTER_TEXT']):?><?=str_replace("&nbsp;", "", $arItem['UF_AFTER_TEXT']);?><?endif;?></div>
						<?endif;?>
					</div>
					<div class="title"><?=$arItem['UF_NAME']?></div>
				</div>
			</div>
		<?endforeach;?>
	</div>
<?endif;?>