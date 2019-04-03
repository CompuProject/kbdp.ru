var timerHide = false;

showToggles = function()
{
	//toggle
	new DG.OnOffSwitchAuto({
        cls:'.block-item.active .custom-switch',
        textOn:"",
        height:33,
        heightTrack:16,
        textOff:"",
        trackColorOff:"f5f5f5",
        listener:function(name, checked){
        	var bNested = $('input[name='+name+']').closest('.values').length;
        	if(checked)
				$('input[name='+name+']').val('Y');
			else
				$('input[name='+name+']').val('N');
			
			if(bNested)
			{
				var ajax_btn = $('<div class="btn-ajax-block animation-opacity"></div>'),
					option_wrapper = $('input[name='+name+']').closest('.option-wrapper'),
					pos = BX.pos(option_wrapper[0], true),
					top = 0,
					current_index = $('input[name='+name+']').closest('.inner-wrapper').data('key'),
					div_class = name.replace(current_index+'_','');

				ajax_btn.html($('.values > .apply-block').html());
				option_wrapper.toggleClass('disabled');
				top = pos.top+$('.style-switcher .header').actual('outerHeight');
				ajax_btn.css('top',top);
				if($('.btn-ajax-block').length)
					$('.btn-ajax-block').remove();
				ajax_btn.appendTo($('.style-switcher'));
				ajax_btn.addClass('opacity1');

				if(checked)
				{
					if(div_class == 'INSTAGRAMM_INDEX')
					{
						if(!$('.instagram_ajax .instagram').length)
						{
							$('.instagram_ajax').removeClass('loaded');
							$.ajax({
								type:"POST",
								url:arAllcorp2Options['SITE_DIR']+"include/mainpage/comp_instagramm.php",
								data:{'SHOW_INSTAGRAM':'Y', 'AJAX_REQUEST_INSTAGRAM':'Y'},
								success:function(html){
									$('.instagram_ajax').html(html);
								}
							})
						}
					}

					$('.drag-block[data-class='+div_class+'_drag]').removeClass('hidden');
					$('.templates_block .item.'+name+'').removeClass('hidden');

					InitFlexSlider();
					$(window).resize();

					if(div_class == 'BIG_BANNER_INDEX' && $('.long-banner').length)
					{
						$('body').addClass('header_opacity');
					}
				}
				else
				{
					$('.drag-block[data-class='+div_class+'_drag]').addClass('hidden');
					$('.templates_block .item.'+name+'').addClass('hidden');

					if(div_class == 'BIG_BANNER_INDEX' && $('.long-banner').length)
					{
						$('body').removeClass('header_opacity');
					}
				}

				//save option
				$.post(
					arAllcorp2Options['SITE_DIR']+"ajax/options_save_mainpage.php",
					{
						VALUE: $('input[name='+name+']').val(),
						NAME: name
					}
				);
			}

			setTimeout(function(){
				if(!bNested)
					$('form[name=style-switcher]').submit();
			},200);
        }
    });
}

$(document).ready(function() {
	$('.style-switcher .item input[type=checkbox]').on('change', function(){
		var _this =  $(this);
		if(_this.is(':checked'))
			_this.val('Y');
		else
			_this.val('N');
		$('form[name=style-switcher]').submit();
	})

	showToggles(); //replace checkbox in custom toggle

	//admin save
	$('.style-switcher .can_save .save_btn').on('click', function(){
		var _this = $(this);

		if(timerHide){
			clearTimeout(timerHide);
			timerHide = false;
		}		

		$.ajax({
			type:"POST",
			url:arAllcorp2Options['SITE_DIR']+"ajax/options_save.php",
			data:{'SAVE_OPTIONS':'Y'},
			dataType:"json",
			success:function(response){
				if("STATUS" in response)
				{
					if(!$('.save_config_status').length)
						$('<div class="save_config_status"><span></span></div>').appendTo(_this.parent());
					if(response.STATUS === 'OK')
						$('.save_config_status').addClass('success');
					else
						$('.save_config_status').addClass('error');

					$('.save_config_status span').text(BX.message(response.MESSAGE));

					$('.save_config_status').slideDown(200);
					timerHide = setTimeout(function(){
						// here delayed functions in event
						$('.save_config_status').slideUp(200, function(){
							$(this).remove();
						})
					}, 1000);
				}
			}
		})
	})

	//sort order for main page
	$('.refresh-block.sup-params .values .inner-wrapper').each(function(){
		var _th = $(this),
			sort_block = _th[0];
		Sortable.create(sort_block,{
			handle: '.drag',
			animation: 150,
			forceFallback: true,
			filter: '.no_drag',
			// Element dragging started
			onStart: function (/**Event*/evt){
				evt.oldIndex;  // element index within parent
				window.getSelection().removeAllRanges();
			},
			onMove: function (evt) {
				return evt.related.className.indexOf('no_drag') === -1;
			},
			// Changed sorting within list
			onUpdate: function (/**Event*/evt){
				var itemEl = evt.item;  // dragged HTMLElement
				var order = [],
					current_type = _th.data('key'),
					name = 'SORT_ORDER_INDEX_TYPE_'+current_type;

				_th.find('.option-wrapper').each(function(){
					order.push($(this).find('input[type="checkbox"]').attr('name').replace(current_type+'_', ''));
					$('div[data-class="'+$(this).find('input[type="checkbox"]').attr('name').replace(current_type+'_', '')+'_drag"]').attr('data-order', $(this).index()+1);
				})

				$('input[name='+name+']').val(order.join(','));

				//save option
				$.post(
					arAllcorp2Options['SITE_DIR']+"ajax/options_save_mainpage.php",
					{
						VALUE: order.join(','),
						NAME: name
					}
				);
			},
		});
	})

	if($.cookie('styleSwitcher') == 'open')
		$('.style-switcher').addClass('active');

	if($('.base_color_custom input[type=hidden]').length)
	{
		$('.base_color_custom input[type=hidden]').each(function(){
			var _this = $(this),
				parent = $(this).closest('.base_color_custom');
			_this.spectrum({
				preferredFormat: 'hex',
				showButtons: true,
				showInput: true,
				showPalette: false,
				appendTo: parent,
				chooseText: BX.message('CUSTOM_COLOR_CHOOSE'),
				cancelText: BX.message('CUSTOM_COLOR_CANCEL'),
				containerClassName: 'custom_picker_container',
				replacerClassName: 'custom_picker_replacer',
				clickoutFiresChange: false,
				move: function(color) {
					var colorCode = color.toHexString();
					parent.find('span span').attr('style', 'background:' + colorCode);
				},
				hide: function(color) {
					var colorCode = color.toHexString();
					parent.find('span span').attr('style', 'background:' + colorCode);
				},
				change: function(color) {
					parent.addClass('current').siblings().removeClass('current');

					$('form[name=style-switcher] input[name=' + parent.find('.click_block').data('option-id') + ']').val(parent.find('.click_block').data('option-value'));
					$('form[name=style-switcher]').submit();
				}
			});
		})
	}

	$('.base_color_custom').click(function(e) {
		e.preventDefault();
		$('input[name='+$(this).data('name')+']').spectrum('toggle');
		return false;
	});

	if($('.base_color.current').length)
	{
		$('.base_color.current').each(function(){
			var color_block = $(this).closest('.options').find('.base_color_custom'),
				curcolor = $(this).data('color');
			if(curcolor != undefined && curcolor.length)
			{
				$('input[name='+color_block.data('name')+']').spectrum('set', curcolor);
				color_block.find('span span').attr('style', 'background:' + curcolor);
			}
		})
	}

	$('.style-switcher .switch').click(function(e){
		e.preventDefault();
		var styleswitcher = $(this).closest('.style-switcher');
		
		HideHintBlock();

		if(styleswitcher.hasClass('active')){
			styleswitcher.addClass('closes');
			setTimeout(function(){
				styleswitcher.removeClass('active');
			},500)
			$.removeCookie('styleSwitcher', {path: '/'});
		}
		else{
			ShowOverlay();
			styleswitcher.removeClass('closes').addClass('active');
			$.cookie('styleSwitcher', 'open', {path: '/'});
		}
	});

	/* close search block */
	$("html, body").on('mousedown', function(e){
		if(typeof e.target.className == 'string' && e.target.className.indexOf('adm') < 0)
		{
			e.stopPropagation();
			var config_target = $(e.target).closest('.style-switcher');
			if(!config_target.length && $('.style-switcher').hasClass('active'))
			{
				$('.style-switcher .switch').trigger('click');
			}
		}
	});

	$('.style-switcher .tooltip-link').on('show.bs.tooltip', function () {
		$(this).closest('.item').siblings().find('.tooltip').remove();
	})

	HideHintBlock = function()
	{
		HideOverlay();
		$.cookie('clickedSwitcher', 'Y', {path: '/'});
		if($('.style-switcher .tooltip.in').length)
		{
			$('.style-switcher .tooltip.in').fadeIn(300, function(){
				$('.style-switcher .tooltip.in').remove();
			});
		}
	}

	$(document).on('click', '.close-overlay', function(){
		HideHintBlock()
	})

	$(document).on('click', '.jqmOverlay', function(){
		var styleswitcher = $('.style-switcher');
		if(!$('.hint-theme').length)
			HideOverlay();
		styleswitcher.each(function(){
			var _this = $(this);
			_this.addClass('closes');
			setTimeout(function(){
				_this.removeClass('active');
			},500);
			$('.form_demo-switcher').animate({left: '-' + $('.form_demo-switcher').outerWidth() + 'px'}, 100).removeClass('active abs');
		})
		$.removeCookie('styleSwitcher', {path: '/'});
	})

	$('.style-switcher .section-block').on('click', function(){
		$(this).siblings().removeClass('active');
		$(this).addClass('active');
		$('.style-switcher .right-block .block-item').removeClass('active');
		$('.style-switcher .right-block .block-item:eq('+$(this).index()+')').addClass('active');
		$.cookie('styleSwitcherTabIndex', $(this).index(), {path: '/'});

		//replace checkbox in custom toggle
		if(!$(this).hasClass('toggle_initied'))
			showToggles();
		$(this).addClass('toggle_initied');
	})

	$('.style-switcher .reset').click(function(e){
		$('form[name=style-switcher]').append('<input type="hidden" name="THEME" value="default" />');
		$('form[name=style-switcher]').submit();
	});

	$(document).on('click', '.style-switcher .apply', function(){
		$('form[name=style-switcher]').submit();
	})
	$('.style-switcher .sup-params.options .block-title').click(function(){
		$(this).next().slideToggle();
	})

	$('.style-switcher .options > a,.style-switcher .options > div:not(.base_color_custom) a, .style-switcher .options > div:not(.base_color_custom) .click_block').click(function(e){
		var _this = $(this);
		if(_this.hasClass('current') || _this.hasClass('disabled'))
			return;

		_this.addClass('current').siblings().removeClass('current');
		$('form[name=style-switcher] input[name=' + _this.data('option-id') + ']').val(_this.data('option-value'));

		if(typeof($(this).data('option-type')) != 'undefined') // set cookie for scroll block
			$.cookie('scoll_block', $(this).data('option-type'));

		if(typeof($(this).data('option-url')) != 'undefined') // set action form for redirect
			$('form[name=style-switcher]').prepend('<input type="hidden" name="backurl" value='+$(this).data('option-url')+' />');

		if(_this.closest('.options').hasClass('refresh-block'))
		{
			if(!_this.closest('.options').hasClass('sup-params'))
				var index = _this.index()-1;


			/*if(_this.data('option-value') == 'custom' || (typeof(index) != 'undefined' && !$('.sup-params.options:eq('+index+')').length))
			{
				$('.sup-params.options').removeClass('active');
				$('form[name=style-switcher]').submit();
			}
			else
			{*/
				/*if($('.sup-params.options').length && typeof(index) != 'undefined')
				{*/
					_this.closest('.item').find('.sup-params.options').removeClass('active');
					_this.closest('.item').find('.sup-params.options.s_'+_this.data('option-value')+'').addClass('active');
					// _this.closest('.item').find('.sup-params.options:eq('+index+')').addClass('active');
				//}
			//}
			$('form[name=style-switcher]').submit();
		}
		else
			$('form[name=style-switcher]').submit();
	});

	$('.tooltip-link').on('shown.bs.tooltip', function (e) {
		var tooltip_block = $(this).next(),
			wihdow_height = $(window).height(),
			scroll = $(this).closest('form').scrollTop(),
			pos = BX.pos($(this)[0], true),
			pos_tooltip = BX.pos(tooltip_block[0], true),
			pos_item_wrapper = BX.pos($(this).closest('.item')[0], true);

		if(!$(this).closest('.item').next().length && pos_tooltip.bottom > pos_item_wrapper.bottom)
		{
			tooltip_block.removeClass('bottom').addClass('top');
			tooltip_block.css({'top':(pos.top-tooltip_block.actual('outerHeight'))});
		}
	})
});