$(document).ready(function(){
		if(arAllcorp2Options['THEME']['CAPTCHA_FORM_TYPE'] == 'RECAPTCHA' || arAllcorp2Options['THEME']['CAPTCHA_FORM_TYPE'] == 'RECAPTCHA2'){
			reCaptchaRender();
		}
		$('.contacts form').validate({
			ignore: ".ignore",
			highlight: function( element ){
				$(element).parent().addClass('error');
			},
			unhighlight: function( element ){
				$(element).parent().removeClass('error');
			},
			submitHandler: function( form ){
				if( $('.contacts form').valid() ){
					$(form).find('button[type="submit"]').attr('disabled', 'disabled');
					if(arAllcorp2Options['THEME']['CAPTCHA_FORM_TYPE'] == 'RECAPTCHA2')
					{
						if($(form).find('.g-recaptcha-response').val())
							form.submit();
						else
							grecaptcha.execute($(form).find('.g-recaptcha').attr('data-widgetid'));
					}
					else
						form.submit();
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
			$('.contacts form input.phone').inputmask("mask", { "mask": arAllcorp2Options['THEME']['PHONE_MASK'], 'showMaskOnHover': false });
			$('.contacts form input.phone').blur(function(){
				if( $(this).val() == base_mask || $(this).val() == '' ){
					if( $(this).hasClass('required') ){
						$(this).parent().find('div.error').html(BX.message("JS_REQUIRED"));
					}
				}
			});
		}

		if(arAllcorp2Options['THEME']['DATE_MASK'].length)
			$('.contacts form input.date').inputmask(arAllcorp2Options['THEME']['DATE_MASK'], { 'placeholder': arAllcorp2Options['THEME']['DATE_PLACEHOLDER'], 'showMaskOnHover': false });

		$("input[type=file]").uniform({ fileButtonHtml: BX.message("JS_FILE_BUTTON_NAME"), fileDefaultHtml: BX.message("JS_FILE_DEFAULT") });
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
			var index = $(this).closest('.input').find('input[type=file]').length+1;
			$('<input type="file" id="POPUP_FILE" name="FILE_n'+index+'"   class="inputfile" value="" />').insertBefore($(this));
			$('input[type=file]').uniform({fileButtonHtml: BX.message('JS_FILE_BUTTON_NAME'), fileDefaultHtml: BX.message('JS_FILE_DEFAULT')});
		})
	});