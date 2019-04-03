<?if( !defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true ) die();?>
<?$this->setFrameMode(false);?>
<div class="form inline<?=($arResult['isFormNote'] == 'Y' ? ' success' : '')?><?=($arResult['isFormErrors'] == 'Y' ? ' error' : '')?>">
	<?if($arResult["isFormNote"] == "Y"){?>
		<div class="form-header">
			<div class="text">
				<div class="title"><?=GetMessage("SUCCESS_TITLE")?></div>
				<?=$arResult["FORM_NOTE"]?>
			</div>
		</div>
		<script>
			if(arAllcorp2Options['THEME']['USE_FORMS_GOALS'] !== 'NONE')
			{
				var eventdata = {goal: 'goal_webform_success' + (arAllcorp2Options['THEME']['USE_FORMS_GOALS'] === 'COMMON' ? '' : '_<?=$arParams["IBLOCK_ID"]?>'), params: <?=CUtil::PhpToJSObject($arParams, false)?>};
				BX.onCustomEvent('onCounterGoals', [eventdata]);
			}
		</script>
		<?if( $arParams["DISPLAY_CLOSE_BUTTON"] == "Y" ){?>
			<div class="form-footer" style="text-align: center;">
				<?=str_replace('class="', 'class="btn-lg ', $arResult["CLOSE_BUTTON"])?>
			</div>
		<?}
	}else{?>
		<?=$arResult["FORM_HEADER"]?>
			<div class="form-header">
				<div class="text">
					<?if( $arResult["isIblockTitle"] ){?>
						<div class="title"><?=$arResult["IBLOCK_TITLE"]?></div>
					<?}?>
					<?if( $arResult["isIblockDescription"] ){
						if( $arResult["IBLOCK_DESCRIPTION_TYPE"] == "text" ){?>
							<p><?=$arResult["IBLOCK_DESCRIPTION"]?></p>
						<?}else{?>
							<?=$arResult["IBLOCK_DESCRIPTION"]?>
						<?}
					}?>
				</div>
			</div>
			<?if($arResult['isFormErrors'] == 'Y'):?>
				<div class="form-error alert alert-danger">
					<?=$arResult['FORM_ERRORS_TEXT']?>
				</div>
			<?endif;?>
			<div class="form-body">
				<?if(is_array($arResult["QUESTIONS"])):?>
					<?foreach( $arResult["QUESTIONS"] as $FIELD_SID => $arQuestion ){
						if( $arQuestion['STRUCTURE'][0]['FIELD_TYPE'] == 'hidden' ){
							echo $arQuestion["HTML_CODE"];
						}else{?>
							<div class="row <?=(strpos($FIELD_SID, 'HIDDEN') !== false ? 'hidden' : '');?>" data-SID="<?=$FIELD_SID?>">
								<div class="col-md-12 <?=($arQuestion['FIELD_TYPE'] == 'checkbox' ? 'style_check bx_filter' : '');?>">
									<div class="form-group <?=($arQuestion['FIELD_TYPE'] != 'checkbox' && $arQuestion['FIELD_TYPE'] != 'file' && ($arQuestion['FIELD_TYPE'] == 'list' && $arQuestion['MULTIPLE'] != 'Y') ? 'animated-labels' : '');?> <?=( $arQuestion['VALUE'] || (in_array($arQuestion['FIELD_TYPE'], array('list', 'file', 'date', 'datetime', 'video', 'directory', 'sequence'))) ? "input-filed" : "");?>">
										<?=$arQuestion["CAPTION"]?>
										<div class="input">
											<?=$arQuestion["HTML_CODE"]?>
											<?if($arQuestion['FIELD_TYPE'] == "file" && $arQuestion['MULTIPLE'] == 'Y'):?>
												<div class="add_file"><span><?=GetMessage('JS_FILE_ADD');?></span></div>
											<?endif;?>
										</div>
										<?if( !empty( $arQuestion["HINT"] ) ){?>
											<div class="hint"><?=$arQuestion["HINT"]?></div>
										<?}?>
									</div>
								</div>
							</div>
						<?}
					}?>
				<?endif;?>
				<?if($arResult["isUseCaptcha"] === "Y"):?>
					<div class="row captcha-row">
						<div class="col-md-12">
							<?=$arResult["CAPTCHA_CAPTION"];?>
							<div class="captcha_image">
								<img src="/bitrix/tools/captcha.php?captcha_sid=<?=htmlspecialcharsbx($arResult["CAPTCHACode"])?>" class="captcha_img" border="0" />
								<input type="hidden" name="captcha_sid" class="captcha_sid" value="<?=htmlspecialcharsbx($arResult["CAPTCHACode"])?>" />
								<div class="captcha_reload"></div>
								<span class="refresh"><a href="javascript:;" rel="nofollow"><?=GetMessage("REFRESH")?></a></span>
							</div>
							<div class="captcha_input">
								<input type="text" class="inputtext form-control captcha" name="captcha_word" size="30" maxlength="50" value="" required />
							</div>
						</div>
					</div>
				<?endif;?>
			</div>
			<div class="form-footer clearfix">
				<?if($arParams["SHOW_LICENCE"] == "Y"):?>
					<div class="licence_block bx_filter">
						<input type="checkbox" id="licenses_inline_<?=$arParams["IBLOCK_ID"]?>" <?=(COption::GetOptionString("aspro.allcorp2", "LICENCE_CHECKED", "N") == "Y" ? "checked" : "");?> name="licenses" required value="Y">
						<label for="licenses_inline_<?=$arParams["IBLOCK_ID"]?>">
							<?$APPLICATION->IncludeFile(SITE_DIR."include/licenses_text.php", Array(), Array("MODE" => "html", "NAME" => "LICENSES")); ?>
						</label>
					</div>
				<?endif;?>
				<div class="">
					<?=str_replace('class="', 'class="btn-lg ', $arResult["SUBMIT_BUTTON"])?>
				</div>
			</div>
		<?=$arResult["FORM_FOOTER"]?>
	<?}?>
</div>

<script>
	$(document).ready(function(){

		$('.inline form[name="<?=$arResult["IBLOCK_CODE"]?>"]').validate({
			ignore: ".ignore",
			highlight: function( element ){
				$(element).parent().addClass('error');
			},
			unhighlight: function( element ){
				$(element).parent().removeClass('error');
			},
			submitHandler: function( form ){
				if( $('.inline form[name="<?=$arResult["IBLOCK_CODE"]?>"]').valid() ){
					$(form).find('button[type="submit"]').attr('disabled', 'disabled');
					var eventdata = {type: 'form_submit', form: form, form_name: '<?=$arResult["IBLOCK_CODE"]?>'};
					BX.onCustomEvent('onSubmitForm', [eventdata]);
				}
			},
			errorPlacement: function( error, element ){
				error.insertBefore(element);
			},
			messages:{
				licenses: {
					required : BX.message('JS_REQUIRED_LICENSES')
				}
			}
		});

		if(arAllcorp2Options['THEME']['PHONE_MASK'].length){
			var base_mask = arAllcorp2Options['THEME']['PHONE_MASK'].replace( /(\d)/g, '_' );
			$('.inline form[name="<?=$arResult['IBLOCK_CODE']?>"] input.phone').inputmask('mask', {'mask': arAllcorp2Options['THEME']['PHONE_MASK'], 'showMaskOnHover': false });
			$('.inline form[name="<?=$arResult['IBLOCK_CODE']?>"] input.phone').blur(function(){
				if( $(this).val() == base_mask || $(this).val() == '' ){
					if( $(this).hasClass('required') ){
						$(this).parent().find('div.error').html(BX.message('JS_REQUIRED'));
					}
				}
			});
		}

		if(arAllcorp2Options['THEME']['DATE_MASK'].length)
		{
			$('.inline form[name="<?=$arResult["IBLOCK_CODE"]?>"] input.date').inputmask('datetime', {
				'inputFormat':  arAllcorp2Options['THEME']['DATE_MASK'],
				'placeholder': arAllcorp2Options['THEME']['DATE_PLACEHOLDER'],
				'showMaskOnHover': false
			});
		}

		if(arAllcorp2Options['THEME']['DATETIME_MASK'].length)
		{
			$('.inline form[name="<?=$arResult["IBLOCK_CODE"]?>"] input.datetime').inputmask('datetime', {
				'inputFormat':  arAllcorp2Options['THEME']['DATETIME_MASK'],
				'placeholder': arAllcorp2Options['THEME']['DATETIME_PLACEHOLDER'],
				'showMaskOnHover': false
			});
		}

		$('.jqmClose').closest('.jqmWindow').jqmAddClose('.jqmClose');

		$('input[type=file]').uniform({fileButtonHtml: BX.message('JS_FILE_BUTTON_NAME'), fileDefaultHtml: BX.message('JS_FILE_DEFAULT')});
		$(document).on('change', 'input[type=file]', function(){
			if($(this).val())
			{
				$(this).closest('.uploader').addClass('files_add');
			}
			else
			{
				$(this).closest('.uploader').removeClass('files_add');
			}
		})
		$('.form .add_file').on('click', function(){
			var container = $(this).closest('.input'),
				index = container.find('input[type=file]').length+1,
				name = container.find('input[type=file]:first').attr('name');
			$('<input type="file" id="POPUP_FILE" name="'+name.replace('n0', 'n'+index)+'"   class="inputfile" value="" />').insertBefore($(this));
			$('input[type=file]').uniform({fileButtonHtml: BX.message('JS_FILE_BUTTON_NAME'), fileDefaultHtml: BX.message('JS_FILE_DEFAULT')});
		})
	});
</script>