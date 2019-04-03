<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true)die();?>
<?$this->setFrameMode(true);?>
<div class="social-icons">
	<?if($arParams["SOCIAL_TITLE"] && (!empty($arResult["SOCIAL_VK"]) || !empty($arResult["SOCIAL_ODNOKLASSNIKI"]) || !empty($arResult["SOCIAL_FACEBOOK"]) || !empty($arResult["SOCIAL_TWITTER"]) || !empty($arResult["SOCIAL_INSTAGRAM"]) || !empty($arResult["SOCIAL_MAIL"]) || !empty($arResult["SOCIAL_YOUTUBE"]) || !empty($arResult["SOCIAL_GOOGLEPLUS"]))):?>
		<div class="small_title"><?=$arParams["SOCIAL_TITLE"];?></div>
	<?endif;?>
	<!-- noindex -->
	<ul>
		<?if(!empty($arResult['SOCIAL_VK'])):?>
			<li class="vk">
				<a href="<?=$arResult['SOCIAL_VK']?>" target="_blank" rel="nofollow" title="<?=GetMessage('TEMPL_SOCIAL_VK')?>">
					<?=CAllcorp2::showIconSvg("vk", SITE_TEMPLATE_PATH."/images/svg/social/Vk.svg");?>
				</a>
			</li>
		<?endif;?>
		<?if(!empty($arResult['SOCIAL_FACEBOOK'])):?>
			<li class="facebook">
				<a href="<?=$arResult['SOCIAL_FACEBOOK']?>" target="_blank" rel="nofollow" title="<?=GetMessage('TEMPL_SOCIAL_FACEBOOK')?>">
					<?=CAllcorp2::showIconSvg("fb", SITE_TEMPLATE_PATH."/images/svg/social/Facebook.svg");?>
				</a>
			</li>
		<?endif;?>
		<?if(!empty($arResult['SOCIAL_TWITTER'])):?>
			<li class="twitter">
				<a href="<?=$arResult['SOCIAL_TWITTER']?>" target="_blank" rel="nofollow" title="<?=GetMessage('TEMPL_SOCIAL_TWITTER')?>">
					<?=CAllcorp2::showIconSvg("tw", SITE_TEMPLATE_PATH."/images/svg/social/Twitter.svg");?>
				</a>
			</li>
		<?endif;?>
		<?if(!empty($arResult['SOCIAL_INSTAGRAM'])):?>
			<li class="instagram">
				<a href="<?=$arResult['SOCIAL_INSTAGRAM']?>" target="_blank" rel="nofollow" title="<?=GetMessage('TEMPL_SOCIAL_INSTAGRAM')?>">
					<?=CAllcorp2::showIconSvg("inst", SITE_TEMPLATE_PATH."/images/svg/social/Instagram.svg");?>
				</a>
			</li>
		<?endif;?>
		<?if(!empty($arResult['SOCIAL_TELEGRAM'])):?>
			<li class="telegram">
				<a href="<?=$arResult['SOCIAL_TELEGRAM']?>" target="_blank" rel="nofollow" title="<?=GetMessage('TEMPL_SOCIAL_TELEGRAM')?>">
					<?=CAllcorp2::showIconSvg("tel", SITE_TEMPLATE_PATH."/images/svg/social/Telegram.svg");?>
				</a>
			</li>
		<?endif;?>
		<?if(!empty($arResult['SOCIAL_YOUTUBE'])):?>
			<li class="ytb">
				<a href="<?=$arResult['SOCIAL_YOUTUBE']?>" target="_blank" rel="nofollow" title="<?=GetMessage('TEMPL_SOCIAL_YOUTUBE')?>">
					<?=CAllcorp2::showIconSvg("yt", SITE_TEMPLATE_PATH."/images/svg/social/Youtube.svg");?>
				</a>
			</li>
		<?endif;?>
		<?if(!empty($arResult['SOCIAL_ODNOKLASSNIKI'])):?>
			<li class="odn">
				<a href="<?=$arResult['SOCIAL_ODNOKLASSNIKI']?>" target="_blank" rel="nofollow" title="<?=GetMessage('TEMPL_SOCIAL_ODNOKLASSNIKI')?>">
					<?=CAllcorp2::showIconSvg("ok", SITE_TEMPLATE_PATH."/images/svg/social/Odnoklassniki.svg");?>
				</a>
			</li>
		<?endif;?>
		<?if(!empty($arResult['SOCIAL_GOOGLEPLUS'])):?>
			<li class="gplus">
				<a href="<?=$arResult['SOCIAL_GOOGLEPLUS']?>" target="_blank" rel="nofollow" title="<?=GetMessage('TEMPL_SOCIAL_GOOGLEPLUS')?>">
					<?=CAllcorp2::showIconSvg("gp", SITE_TEMPLATE_PATH."/images/svg/social/Googleplus.svg");?>
				</a>
			</li>
		<?endif;?>
		<?if(!empty($arResult['SOCIAL_MAIL'])):?>
			<li class="mail">
				<a href="<?=$arResult['SOCIAL_MAIL']?>" target="_blank" rel="nofollow" title="<?=GetMessage('TEMPL_SOCIAL_MAILRU')?>">
					<?=CAllcorp2::showIconSvg("ml", SITE_TEMPLATE_PATH."/images/svg/social/Mailru.svg");?>
				</a>
			</li>
		<?endif;?>
	</ul>
	<!-- /noindex -->
</div>