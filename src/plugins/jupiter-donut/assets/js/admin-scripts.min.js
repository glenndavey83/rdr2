!function($){function isDOMAttrModifiedSupported(){var p=document.createElement("p"),flag=!1;if(p.addEventListener)p.addEventListener("DOMAttrModified",function(){flag=!0},!1);else{if(!p.attachEvent)return!1;p.attachEvent("onDOMAttrModified",function(){flag=!0})}return p.setAttribute("id","target"),flag}function checkAttributes(chkAttr,e){if(chkAttr){var attributes=this.data("attr-old-value");if(e.attributeName.indexOf("style")>=0){attributes.style||(attributes.style={});var keys=e.attributeName.split(".");e.attributeName=keys[0],e.oldValue=attributes.style[keys[1]],e.newValue=keys[1]+":"+this.prop("style")[$.camelCase(keys[1])],attributes.style[keys[1]]=e.newValue}else e.oldValue=attributes[e.attributeName],e.newValue=this.attr(e.attributeName),attributes[e.attributeName]=e.newValue;this.data("attr-old-value",attributes)}}var MutationObserver=window.MutationObserver||window.WebKitMutationObserver;$.fn.attrchange=function(a,b){if("object"==typeof a){var cfg={trackValues:!1,callback:$.noop};if("function"==typeof a?cfg.callback=a:$.extend(cfg,a),cfg.trackValues&&this.each(function(i,el){for(var attr,attributes={},i=0,attrs=el.attributes,l=attrs.length;i<l;i++)attr=attrs.item(i),attributes[attr.nodeName]=attr.value;$(this).data("attr-old-value",attributes)}),MutationObserver){var mOptions={subtree:!1,attributes:!0,attributeOldValue:cfg.trackValues},observer=new MutationObserver(function(mutations){mutations.forEach(function(e){var _this=e.target;cfg.trackValues&&(e.newValue=$(_this).attr(e.attributeName)),"connected"===$(_this).data("attrchange-status")&&cfg.callback.call(_this,e)})});return this.data("attrchange-method","Mutation Observer").data("attrchange-status","connected").data("attrchange-obs",observer).each(function(){observer.observe(this,mOptions)})}return isDOMAttrModifiedSupported()?this.data("attrchange-method","DOMAttrModified").data("attrchange-status","connected").on("DOMAttrModified",function(event){event.originalEvent&&(event=event.originalEvent),event.attributeName=event.attrName,event.oldValue=event.prevValue,"connected"===$(this).data("attrchange-status")&&cfg.callback.call(this,event)}):"onpropertychange"in document.body?this.data("attrchange-method","propertychange").data("attrchange-status","connected").on("propertychange",function(e){e.attributeName=window.event.propertyName,checkAttributes.call($(this),cfg.trackValues,e),"connected"===$(this).data("attrchange-status")&&cfg.callback.call(this,e)}):this}if("string"==typeof a&&$.fn.attrchange.hasOwnProperty("extensions")&&$.fn.attrchange.extensions.hasOwnProperty(a))return $.fn.attrchange.extensions[a].call(this,b)}}(jQuery);
(function($) {

  var locolized_data = icon_selector_locolized_data,
      ajax_url = locolized_data.ajax_url,
      $icon_selector = $('.mk-ip'),
      $search_input = $('.mk-ip-search-input'),
      $close_btn = $('.mk-ip-header-close-btn'),
      $cancel_btn = $('.mk-ip-cancel-btn'),
      $save_btn = $('.mk-ip-save-btn'),
      $small_view = $('.mk-ip-lib-view-small'),
      $large_view = $('.mk-ip-lib-view-large'),
      $lib_container = $('.mk-ip-lib-wrap'),
      $filter_bar = $('.mk-ip-filter'),
      $filters = $filter_bar.find('a'),
      $filter_all_btn = $filter_bar.find('.mk-ip-filter-all'),
      pag_start = 0,
      pag_count = 200,
      displayed_icons_index = pag_start,
      icons_index = pag_start,
      is_inf_scroll_initiated = false,
      is_inf_scroll_active = true,
      $last_selected_icon = '',
      $vc_value_input = '',
      $vc_value_view = '',
      $vc_value_view_wrap = '',
      $current_svg = '',
      $spinner = $('.mk-ip-spinner'),
      all_icons = '',
      filtered_cat = 'all';
      $lib = $('.mk-ip-lib');

  // Open Icon Selector
  $('body').on('click', '.mk-vc-icon-selector-btn', function(e) {
      e.preventDefault();
      $vc_value_input = $(this).siblings('.icon_selector_field');
      $vc_value_view_wrap = $(this).siblings('.mk-vc-icon-selector-view-wrap');
      $vc_value_view = $vc_value_view_wrap.find('.mk-vc-icon-selector-view');
      $current_svg = $vc_value_view.children('svg').clone();
      $icon_selector.fadeIn(300);
      $search_input.focus();
      $filters.removeClass('mk-selected');
      $filter_all_btn.addClass('mk-selected');
      filtered_cat = 'all';
      init_icon_selector();
  });

  // Save Icon Selector
  $save_btn.on('click', function(e) {
      e.preventDefault();
      $lib_container.off('scroll');
      is_inf_scroll_initiated = false;
      var icon_class_name = $last_selected_icon.find('svg').attr('data-name'),
          $icon_srouce = $last_selected_icon.find('svg').clone();
      $vc_value_input.val(icon_class_name);
      $vc_value_view_wrap.removeClass('mka-hidden');
      $vc_value_view_wrap.siblings('.mk-vc-icon-selector-btn').text( icon_selector_locolized_data.replace_icon_string );
      $vc_value_view.empty().append($icon_srouce);
      $icon_selector.fadeOut(300);
      setTimeout(function() {
          $lib.empty();
      }, 400);
  });

  // Close Icon Selector
  $close_btn.add( $cancel_btn ).on('click', function(e) {
      e.preventDefault();
      $lib_container.off('scroll');
      is_inf_scroll_initiated = false;
      $icon_selector.fadeOut(300);
      setTimeout(function() {
          $lib.empty();
      }, 400);
  });

  // On VC window close
  $('body').on('click', '.vc_ui-close-button', function(e) {
      $close_btn.trigger('click');
  });

  // Search Icon Selector
  $search_input.on('keyup', _.debounce(function (e) {
      if ( $.trim( $search_input.val() ) === '' ) {
          is_inf_scroll_active = true;
          display_list_of_icons(true);
      } else {
          display_search_of_icons( $search_input.val() );
      }
  }, 500));

  // View buttons
  $small_view.on('click', function(e) {
      e.preventDefault();
      $(this).addClass('mk-selected').siblings().removeClass('mk-selected');
      $lib.removeClass('mk-ip-lib-large').addClass('mk-ip-lib-small');
  });
  $large_view.on('click', function(e) {
      $(this).addClass('mk-selected').siblings().removeClass('mk-selected');
      $lib.removeClass('mk-ip-lib-small').addClass('mk-ip-lib-large');
      e.preventDefault();
  });

  // Filtering
  $filters.on('click', function(e) {
      e.preventDefault();
      $filters.removeClass('mk-selected');
      $(this).addClass('mk-selected');
      filtered_cat = $(this).attr('data-filter');
      display_list_of_icons(true);
  });

  // Select Icon
  $lib.on('click', '.mk-ip-lib-item', function() {
      handle_selected_icon( this );
  });

  // Remove Icon in VC
  $('body').on('click', '.mk-vc-icon-selector-view-remove', function(e) {
      e.preventDefault();
      $(this).closest('.mk-vc-icon-selector-view-wrap').siblings('.wpb_vc_param_value').val('');
      $(this).closest('.mk-vc-icon-selector-view-wrap').siblings('.mk-vc-icon-selector-btn').text( icon_selector_locolized_data.select_icon_string );
      $(this).closest('.mk-vc-icon-selector-view-wrap').addClass('mka-hidden');
  });


  function init_icon_selector() {
      localforage.getItem('mk_jupiter_icons').then( function(value) {
          if ( value ) {
              display_list_of_icons(true);
          } else {
              cache_all_icons();
          }
      }).catch(function(err) {
          console.log(err);
      });
  }

  function display_list_of_icons(clear) {

      var clear = clear || false;

      if ( clear ) {
          $lib.empty();
          displayed_icons_index = pag_start;
          icons_index = pag_start;
          is_inf_scroll_active = true;
          $search_input.val('');
          if ( $current_svg.length > 0 ) {
              $lib.append( '<li class="mk-ip-lib-item mk-ip-lib-item-first"><div class="mk-ip-lib-item-inner"><div class="mk-ip-lib-item-icon">' + $current_svg[0].outerHTML + '</div></div></li>' );
          }
          handle_selected_icon( $lib.find('.mk-ip-lib-item-first')[0] );
      }

      var initated_start_index = icons_index;

      localforage.getItem('mk_jupiter_icons').then( function(data) {
          var icons = '';
          var loop_index = 0;
          $.each( data, function(name, source) {
              if ( loop_index > initated_start_index + pag_count ) {
                  return false;  // Break when loaded the amount of pag_count
              }
              if ( loop_index < initated_start_index  ) {
                  loop_index++;  // Loop until index is at desired position
              } else {

                  var current_cat_name = name.substr(0, nthIndex(name,'-', 2)),
                      cat_class = 'mk-ip-cat-' + current_cat_name;

                  if ( current_cat_name === filtered_cat || filtered_cat === 'all' ) {
                      icons += '<li class="mk-ip-lib-item ' + cat_class + '"><div class="mk-ip-lib-item-inner"><div class="mk-ip-lib-item-icon">' + source + '</div></div></li>';
                      displayed_icons_index++;  // Keep track of loaded icons
                      icons_index++;
                      loop_index++;
                  } else {
                      icons_index++;
                  }

              }
          });
          if ( is_inf_scroll_active ) {
              $lib.append(icons);
          }
          init_infinite_scrolling(); // only runs on the first initation
      });
  }

  function display_search_of_icons(icon_name) {
      is_inf_scroll_active = false;
      $lib.empty()
      localforage.getItem('mk_jupiter_icons').then( function(data) {
          var icons = '';
          var regex = new RegExp('-' + icon_name, 'i');
          $.each( data, function(name, source) {
              var current_cat_name = name.substr(0, nthIndex(name,'-', 2));
              if ( current_cat_name === filtered_cat || filtered_cat === 'all' ) {
                  if ( regex.test(name) ) {
                      icons += '<li class="mk-ip-lib-item"><div class="mk-ip-lib-item-inner"><div class="mk-ip-lib-item-icon">' + source + '</div></div></li>';
                  }
              }
          });
          $lib.append(icons);
      });
  }

  function cache_all_icons() {
      $lib.empty();
      $spinner.show();
      jQuery.ajax({
          method: "POST",
          url: ajax_url,
          data: {
              pagination_start: 0,
              pagination_count: -1,
              icon_family: 'all',
              action: 'mk_get_icons_list',
          }
      }).success( function( obj ) {
          localforage.setItem('mk_jupiter_icons', obj.data).then( function (value) {
              console.log('Icons were cached.');
              $spinner.hide();
              display_list_of_icons(true);
          }).catch(function(err) {
              console.log('Icons were NOT cached: ' + err);
          });
      });
  }

  function handle_selected_icon(elem) {
      var $this = $(elem);
      if ( $last_selected_icon instanceof jQuery ) {
          $last_selected_icon.removeClass('mk-selected');
      }
      $last_selected_icon = $this;
      $this.addClass('mk-selected');
  }

  // Initiate Infinite Scrolling, Helps to prevent jerky behavior on lib's height changes when initiating icon selector window
  function init_infinite_scrolling() {
      if ( !is_inf_scroll_initiated ) {
          $lib_container.scroll( function() {
             if ( $lib_container.scrollTop() + $lib_container.height() > $lib.prop('scrollHeight') - 100 && is_inf_scroll_active) {
                  display_list_of_icons(false);
             }
          });
          is_inf_scroll_initiated = true;
      }
  }

  function nthIndex(str, pat, n){
      var L= str.length, i= -1;
      while(n-- && i++<L){
          i= str.indexOf(pat, i);
          if (i < 0) break;
      }
      return i;
  }



}(jQuery));

(function($) {
	'use strict';

	// Use for dependency management for admin panel.
	// Heavily based on attrchange plugin which helped not rolling back to legacy code 
	// for triggering change event whenever val() was updated
 
 	var Dependency = function(el) {
 		this.el = el;
 	};

 	Dependency.prototype = {
 		init: function init() {
 			this.cacheElements();
 			this.bindEvents();
 		},

 		cacheElements: function cacheElements() {
 			this.$child = $(this.el);
 			this.vals = this.$child.data('dependency-value');

 			var motherName = this.$child.data('dependency-mother');
 			this.$mother = $('#' + motherName);
 		},

 		bindEvents: function bindEvents() {
 			var self = this;
 			
 			// For handling user input
 			this.$mother.on('change', this.resolveDependency.bind(this));
 			
 			// For handling programmatic val() updates
 			this.$mother.attrchange({
 				callback: self.resolveDependency.bind(self)
 			});

 			// Wait a little after load and hide what is not needed
 			$(window).on('load', function() {
 				setTimeout( self.resolveDependency.bind(self), 100);
 			});
 		},

 		resolveDependency: function resolveDependency() {
 			var val = this.$mother.val();

 			if ( this.hasValue(val) ) this.show();
 			else this.hide();
 		},

 		hasValue: function hasValue(val) {
 			return this.vals.indexOf(val) !== -1;
 		},

 		show: function show() {
 			this.$child.show();
 		},

 		hide: function hide() {
 			this.$child.hide();
 		},
 	};


 	var $dependencyChildren = $('[data-dependency-mother]');

	$dependencyChildren.each( function() {
		var dep = new Dependency( this );
		dep.init();
	});

}( jQuery ));
function mk_upload_option(option_id) {
	if (typeof wp.media != 'undefined') {
		var frame,
			_custom_media = true,
			_orig_send_attachment = wp.media.editor.send.attachment,
			option_selector = option_id ? ("#" + option_id + "_button") : '.option-upload-button';

		jQuery(option_selector).click(function(e) {
			var send_attachment_bkp = wp.media.editor.send.attachment;
			var button = jQuery(this);
			var id = button.attr('id').replace('_button', '');
			_custom_media = true;

			// Create a new media frame
			frame = wp.media({
				title: 'Set Image',
				button: {
					text: 'Set Image'
				},
				multiple: false  // Set to true to allow multiple files to be selected
			});

			// When an image is selected in the media frame...
			frame.on( 'select', function() {

				// Get media attachment details from the frame state
				var attachment = frame.state().get('selection').first().toJSON();
				if (_custom_media) {
					jQuery("#" + id).val(attachment.url);
					jQuery("#" + id + "-preview img").attr("src", attachment.url);
				} else {
					return _orig_send_attachment.apply(this, [props, attachment]);
				};
			});

			// Finally, open the modal on click
			frame.open();

			return false;
		});

		jQuery('.add_media').on('click', function() {
			_custom_media = false;
		});
	}
}

function mk_upload_width() {
	jQuery('.mk-upload-width').each(function() {
		var $this = jQuery( this ),
			value = $this.data('value'),
			$hiddenInput = jQuery( 'input[id=' + value + ']' )

		$this.val( $hiddenInput.val() )
		$this.on('keyup', function() {
			$hiddenInput.val( $this.val() );
		})
	});
}

function mk_range_option(option_id) {
    var range_wrapper = jQuery("#rangeInput-" + option_id);
    var mk_min = parseFloat(range_wrapper.attr("data-min"));
    var mk_max = parseFloat(range_wrapper.attr("data-max"));
    var mk_step = parseFloat(range_wrapper.attr("data-step"));
    var mk_value = parseFloat(range_wrapper.attr("data-value"));
    range_wrapper.slider({
        value: mk_value,
        min: mk_min,
        max: mk_max,
        step: mk_step,
        slide: function(event, ui) {
            range_wrapper.siblings(".range-input-selector").val(ui.value);
        }
    });
}

function mk_toggle_option(option_id) {
    var $this = jQuery("#toggle-switch-" + option_id),
        $input = $this.find("input");
    if ($input.val() == "true") {
        $this.addClass("mk-toggle-on");
    } else {
        $this.addClass("mk-toggle-off");
    }
    $this.click(function() {
        if ($this.hasClass("mk-toggle-on")) {
            $this.removeClass("mk-toggle-on").addClass("mk-toggle-off");
            $input.val("false").trigger("change");
        } else {
            $this.removeClass("mk-toggle-off").addClass("mk-toggle-on");
            $input.val("true").trigger("change");
        }
    });
}


function mk_shortcode_fonts() {
    jQuery("#font_family").change(function() {
        jQuery("#font_family option:selected").each(function() {
            var type = jQuery(this).attr('data-type');
            jQuery("#font_type").val(type).trigger("change");
        });
    }).change();
}

function mk_range_input() {
    jQuery('.mk-range-input').each(function() {
        var range_input = jQuery(this).siblings('.range-input-selector'),
            mk_min = parseFloat(jQuery(this).attr('data-min')),
            mk_max = parseFloat(jQuery(this).attr('data-max')),
            mk_step = parseFloat(jQuery(this).attr('data-step')),
            mk_value = parseFloat(jQuery(this).attr('data-value'));
        jQuery(this).slider({
            value: mk_value,
            min: mk_min,
            max: mk_max,
            step: mk_step,
            slide: function(event, ui) {
                range_input.val(ui.value).trigger("change");
            }
        });
    });
}

function mk_visual_selector() {
    jQuery('.mk-visual-selector').find('a').each(function() {
        var $this = jQuery(this),
            default_value = jQuery(this).siblings('input').val();
        if ($this.attr('rel') == default_value) {
            $this.addClass('current');
            $this.append('<div class="selector-tick"></div>');
        }
        jQuery(this).click(function() {
            $this.siblings('input').val(jQuery(this).attr('rel')).trigger("change");
            $this.parent('.mk-visual-selector').find('.current').removeClass('current');
            $this.parent('.mk-visual-selector').find('.selector-tick').remove();
            $this.addClass('current');
            $this.append('<div class="selector-tick"></div>');
            return false;
        });
    });
}

function mk_header_selector() {
    var header_style = jQuery('#theme_header_style').val(),
        header_align = jQuery('#theme_header_align').val(),
        header_toolbar = jQuery('#theme_toolbar_toggle').val();
    if (header_style == '4') {
        jQuery('.header-align-center').hide();
    } else {
        jQuery('.header-align-center').show();
    }
    jQuery('#mk-header-switcher').addClass('style-' + header_style + '-align-' + header_align + ' toolbar-' + header_toolbar);
    jQuery('.mk-header-styles-number').find('span').each(function() {
        var $this = jQuery(this);
        if ($this.attr('rel') == header_style) {
            $this.addClass('active');
            //console.log('style-'+header_style+'-align-'+header_align+'-toolbar-'+header_toolbar);
        }
        $this.on('click', function() {
            var header_style = jQuery('#theme_header_style').val(),
                header_align = jQuery('#theme_header_align').val(),
                header_toolbar = jQuery('#theme_toolbar_toggle').val();
            $this.siblings().removeClass('active').end().addClass('active');
            jQuery('#mk-header-switcher').attr('class', '').addClass('style-' + $this.attr('rel') + '-align-' + header_align + ' toolbar-' + header_toolbar);
            //console.log('style-'+$this.attr('rel')+'-align-'+header_align+' toolbar-'+header_toolbar);
            jQuery('#theme_header_style').val($this.attr('rel'));
            if ($this.attr('rel') == '4') {
                jQuery('.header-align-center').hide();
            } else {
                jQuery('.header-align-center').show();
            }
        });
    });
    jQuery('.mk-header-align').find('span').each(function() {
        var $this = jQuery(this);
        if ($this.attr('rel') == header_align) {
            $this.addClass('active');
        }
        $this.on('click', function() {
            var header_style = jQuery('#theme_header_style').val(),
                header_align = jQuery('#theme_header_align').val(),
                header_toolbar = jQuery('#theme_toolbar_toggle').val();
            $this.siblings().removeClass('active').end().addClass('active');
            jQuery('#mk-header-switcher').attr('class', '').addClass('style-' + header_style + '-align-' + $this.attr('rel') + ' toolbar-' + header_toolbar);
            jQuery('#theme_header_align').val($this.attr('rel'));
        });
    });
    if (header_toolbar == 'true') {
        jQuery('.header-toolbar-toggle-button').addClass('enabled');
    } else {
        jQuery('.header-toolbar-toggle-button').removeClass('enabled').addClass('disabled');
    }
    jQuery('.header-toolbar-toggle-button').on('click', function() {
        var $this = jQuery(this),
            header_style = jQuery('#theme_header_style').val(),
            header_align = jQuery('#theme_header_align').val(),
            header_toolbar = jQuery('#theme_toolbar_toggle').val();
        $this.removeClass('active').addClass('active');
        if ($this.hasClass('enabled')) {
            $this.removeClass('enabled').addClass('disabled');
            toggle_value = 'false';
            jQuery('#theme_toolbar_toggle').val('false');
        } else {
            $this.removeClass('disabled').addClass('enabled');
            toggle_value = 'true';
            jQuery('#theme_toolbar_toggle').val('true');
        }
        jQuery('#mk-header-switcher').attr('class', '').addClass('style-' + header_style + '-align-' + header_align + ' toolbar-' + toggle_value);
    });
}
jQuery.expr[':'].Contains = function(a, i, m) {
    return (a.textContent || a.innerText || "").toUpperCase().indexOf(m[3].toUpperCase()) >= 0;
};

function mk_header_builder_link() {
    // Declare selector.
    var selectEl = jQuery( '#_hb_override_template_id' );
    var linkEl = jQuery( '#_hb_header_builder_link' );

    // Remove select option.
    selectEl.find('option[value=""]').remove();

    // Set id parameter on load.
    var value = selectEl.val();
    var href = linkEl.attr( 'href' );
    var newHref = href + '&id=' + value;
    linkEl.attr( 'href', newHref );

    // Update id parameter on change.
    selectEl.on( 'change', function(){
        var value = jQuery( this ).val();
        var href = linkEl.attr( 'href' );
        var newHref = href.replace( /(id=)[0-9]+/, '$1' + value );
        linkEl.attr( 'href', newHref );
    } );
}

function icon_filter_name() {
    jQuery('.page-composer-icon-filter').each(function() {
        jQuery(this).change(function() {
            var filter = jQuery(this).val();
            var list = jQuery(this).siblings('.mk-font-icons-wrapper');
            if (filter) {
                jQuery(list).find("span:not(:Contains(" + filter + "))").parent('a').hide();
                jQuery(list).find("span:Contains(" + filter + ")").parent('a').show();
            } else {
                jQuery(list).find("a").show();
            }
            return false;
        }).keyup(function() {
            jQuery(this).change();
        });
    });
}

function mk_color_picker() {
    var $ = jQuery;
    Color.prototype.toString = function() {
        if (this._alpha < 1) {
            return this.toCSS('rgba', this._alpha).replace(/\s+/g, '');
        }
        var hex = parseInt(this._color, 10).toString(16);
        if (this.error) return '';
        if (hex.length < 6) {
            for (var i = 6 - hex.length - 1; i >= 0; i--) {
                hex = '0' + hex;
            }
        }
        return '#' + hex;
    };
    $('.color-picker').each(function() {
        var $control = $(this),
            value = $control.val().replace(/\s+/g, ''),
            alpha_val = 100,
            enableRGBA = $control.attr( 'data-rgba' ) || 'true',
            $alpha, $alpha_output;
        if (value.match(/rgba\(\d+\,\d+\,\d+\,([^\)]+)\)/)) {
            alpha_val = parseFloat(value.match(/rgba\(\d+\,\d+\,\d+\,([^\)]+)\)/)[1]) * 100;
        }
        $control.wpColorPicker({
            clear: function(event, ui) {
                $alpha.val(100);
                $alpha_output.val(100 + '%');
            }
        });
        if (enableRGBA === "true") {
        $('<div class="vc_alpha-container">' + '<label>Alpha: <output class="rangevalue">' + alpha_val + '%</output></label>' + '<input type="range" min="1" max="100" value="' + alpha_val + '" name="alpha" class="vc_alpha-field">' + '</div>').appendTo($control.parents('.wp-picker-container:first').addClass('vc_color-picker').find('.iris-picker'));
        }
        $alpha = $control.parents('.wp-picker-container:first').find('.vc_alpha-field');
        $alpha_output = $control.parents('.wp-picker-container:first').find('.vc_alpha-container output')
        $alpha.bind('change keyup', function() {
            var alpha_val = parseFloat($alpha.val()),
                iris = $control.data('a8cIris'),
                color_picker = $control.data('wpWpColorPicker');
            $alpha_output.val($alpha.val() + '%');
            iris._color._alpha = alpha_val / 100.0;
            $control.val(iris._color.toString());
            color_picker.toggler.css({
                backgroundColor: $control.val()
            });
        }).val(alpha_val).trigger('change');
    });
}
jQuery(document).ready(function() {
    mk_upload_option();
    mk_color_picker();
    mk_header_selector();
    mk_header_builder_link();
    mk_upload_width();
    /*
**
** Toggle Button Option
-------------------------------------------------------------*/
    jQuery('.mk-toggle-button').each(function() {
        var $this = jQuery(this),
            default_value = $this.find('input').val();
        if (default_value == 'true') {
            $this.addClass('mk-toggle-on').trigger('change');
        } else {
            $this.addClass('mk-toggle-off').trigger('change');
        }
        $this.click(function() {
            var $this = jQuery(this);
            if ($this.hasClass('mk-toggle-on')) {
                $this.removeClass('mk-toggle-on').addClass('mk-toggle-off');
                $this.find('input').val('false').trigger('change');
            } else {
                $this.removeClass('mk-toggle-off').addClass('mk-toggle-on');
                $this.find('input').val('true').trigger('change');
            }
        });
    });
    /*
**
** Range Input Plugin
-------------------------------------------------------------*/
    mk_range_input();
    /*
**
Chosen Plugin
-------------------------------------------------------------*/
    jQuery(".mk-chosen").select2({
        placeholder: "Select Options"
    });
    /*
**
** Non-safe fonts type change
-------------------------------------------------------------*/
    if (jQuery('#special_fonts_type_1').val() == 'google') {
        jQuery('#google_font_subset_1_wrapper').show();
    } else {
        jQuery('#google_font_subset_1_wrapper').hide();
    }
    jQuery("#special_fonts_list_1").change(function() {
        jQuery("#special_fonts_list_1 option:selected").each(function() {
            var type = jQuery(this).attr('data-type');
            jQuery('#special_fonts_type_1').val(type);
            if (type == 'google') {
                jQuery('#google_font_subset_1_wrapper').show();
            } else {
                jQuery('#google_font_subset_1_wrapper').hide();
            }
        });
    }).change();
    if (jQuery('#special_fonts_type_2').val() == 'google') {
        jQuery('#google_font_subset_2_wrapper').show();
    } else {
        jQuery('#google_font_subset_2_wrapper').hide();
    }
    jQuery("#special_fonts_list_2").change(function() {
        jQuery("#special_fonts_list_2 option:selected").each(function() {
            var type = jQuery(this).attr('data-type');
            jQuery('#special_fonts_type_2').val(type);
            if (type == 'google') {
                jQuery('#google_font_subset_2_wrapper').show();
            } else {
                jQuery('#google_font_subset_2_wrapper').hide();
            }
        });
    }).change();
    /*
**
Custom Sidebar
-------------------------------------------------------------*/
    jQuery("#add_sidebar_item").click(function(e) {
        e.preventDefault();
        var clone_item = jQuery(this).parents('.custom-sidebar-wrapper').siblings('#selected-sidebar').find('.default-sidebar-item').clone(true);
        var clone_val = jQuery(this).siblings('#add_sidebar').val();
        if (clone_val == '') return;
        if (jQuery('#sidebars').val()) {
            jQuery('#sidebars').val(jQuery('#sidebars').val() + ',' + jQuery("#add_sidebar").val());
        } else {
            jQuery('#sidebars').val(jQuery("#add_sidebar").val());
        }
        clone_item.removeClass('default-sidebar-item').addClass('sidebar-item');
        clone_item.find('.sidebar-item-value').attr('value', clone_val);
        clone_item.find('.slider-item-text').html(clone_val);
        jQuery("#selected-sidebar").append(clone_item);
        jQuery(".sidebar-item").fadeIn(300);
        jQuery("#add_sidebar").val("");
    });
    jQuery(".sidebar-item").css('display', 'block');
    jQuery(".delete-sidebar").click(function(e) {
        e.preventDefault();
        jQuery(this).parent("#sidebar-item").slideUp(300, function() {
            jQuery(this).remove();
            jQuery('#sidebars').val('');
            jQuery(".sidebar-item-value").each(function() {
                if (jQuery('#sidebars').val()) {
                    jQuery('#sidebars').val(jQuery('#sidebars').val() + ',' + jQuery(this).val());
                } else {
                    jQuery('#sidebars').val(jQuery(this).val());
                }
            });
        });
    });
    /*
**
Header Social Netowrks
-------------------------------------------------------------*/
    jQuery("#add_header_social_item").click(function(e) {
        e.preventDefault();
        var clone_item = jQuery('#mk-current-social').find('.default-social-item').clone(true);
        var clone_url_val = jQuery('#header_social_url').val();
        var clone_select_value = jQuery('#header_social_sites_select').val();
        if (clone_url_val === '') {
            return;
        }
        if (jQuery('#header_social_networks_site').val()) {
            jQuery('#header_social_networks_site').val(jQuery('#header_social_networks_site').val() + ',' + jQuery("#header_social_sites_select").val());
        } else {
            jQuery('#header_social_networks_site').val(jQuery("#header_social_sites_select").val());
        }
        if (jQuery('#header_social_networks_url').val()) {
            jQuery('#header_social_networks_url').val(jQuery('#header_social_networks_url').val() + ',' + jQuery("#header_social_url").val());
        } else {
            jQuery('#header_social_networks_url').val(jQuery("#header_social_url").val());
        }
        clone_item.removeClass('default-social-item').addClass('mk-social-item');
        clone_item.find('.mk-social-item-site').attr('value', clone_select_value);
        clone_item.find('.mk-social-item-url').attr('value', clone_url_val);
        clone_item.find('.social-item-url').html(clone_url_val);
        clone_item.find('.social-item-icon').html(clone_select_value);
        jQuery("#mk-current-social").append(clone_item);
        jQuery(".mk-social-item").fadeIn(300);
        jQuery("#header_social_url").val("");
    });
    jQuery(".mk-social-item").css('display', 'block');
    jQuery(".delete-social-item").click(function(e) {
        e.preventDefault();
        jQuery(this).parent(".mk-social-item").slideUp(200, function() {
            jQuery(this).remove();
            jQuery('#header_social_networks_url').val('');
            jQuery('#header_social_networks_site').val('');
            jQuery(".mk-social-item-site").each(function() {
                if (jQuery('#header_social_networks_site').val()) {
                    jQuery('#header_social_networks_site').val(jQuery('#header_social_networks_site').val() + ',' + jQuery(this).val());
                } else {
                    jQuery('#header_social_networks_site').val(jQuery(this).val());
                }
            });
            jQuery(".mk-social-item-url").each(function() {
                if (jQuery('#header_social_networks_url').val()) {
                    jQuery('#header_social_networks_url').val(jQuery('#header_social_networks_url').val() + ',' + jQuery(this).val());
                } else {
                    jQuery('#header_social_networks_url').val(jQuery(this).val());
                }
            });
        });
    });
    /*
**
Option : Super links
-------------------------------------------------------------*/
    function super_link() {
        var wrap = jQuery(".superlink-wrap");
        wrap.each(function() {
            var field = jQuery(this).siblings('input:hidden');
            var selector = jQuery(this).siblings('select');
            var name = field.attr('name');
            var items = jQuery(this).children();
            selector.change(function() {
                items.hide();
                jQuery("#" + name + "_" + jQuery(this).val()).show();
                field.val('');
            });
            items.change(function() {
                field.val(selector.val() + '||' + jQuery(this).val());
            });
        });
    }
    super_link();
    /*
**
Visual Selector Option
-------------------------------------------------------------*/
    mk_visual_selector();
    /*
    **
    Masterkey tabs
    -------------------------------------------------------------*/
    // jQuery(".masterkey-options-page, .mk-main-pane, .mk-options-container").tabs();
    // /* Removes jQuery UI unwanted Classes to prevent conflicts */
    // jQuery('.masterkey-options-page, .mk-main-pane, .mk-options-container, .mk-sub-pane').removeClass('ui-tabs-panel ui-widget-content ui-corner-bottom ui-tabs ui-widget ui-widget-content ui-corner-all')
    // jQuery('.mk-main-navigator, .mk-sub-navigator').removeClass('ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all');
    // jQuery('.mk-main-navigator li, .mk-sub-navigator li').removeClass('ui-state-default ui-corner-top ui-corner-bottom');

    /**
     * TL;DR: "Fake" nested tabs.
     *
     * Continue using "jQuery UI Tabs" in existing theme options and just "fake" multilevel tabbed navigation
     * since "jQuery UI Tabs" does not work on multi-level lists. This approach now assumes and expects that the entire
     * sidebar navigation to be completely flat. The nested effect is accomplished with some CSS and the JS code
     * below.
     */
    jQuery('.mk-main-sidebar-navigator-ul').on('click', 'li', function(){
        if (jQuery(this).hasClass('menu')) {
            /* Hide all sub-menus.*/
            jQuery('.mk-main-sidebar-navigator-ul').children().filter('.sub-menu').hide();

            /* Show every successing secondary level element until another top level element is reached. */
            jQuery(this).nextUntil('.menu').show();
            jQuery(this).next('.sub-menu').addClass('active');

            if (!jQuery(this).hasClass('active')) {
                jQuery('.mk-main-sidebar-navigator-ul').children().filter('.menu').removeClass('active');
                jQuery(this).addClass('active');
            }
        } else if (jQuery(this).hasClass('sub-menu')) {
            /* Remove CSS active state hook for all menus and sub-menus. */
            jQuery('.mk-main-sidebar-navigator-ul').children().filter('.menu, .sub-menu').removeClass('active');

            /* Re-apply CSS active state hook to active menu and sub-menu. */
            jQuery(this).addClass('active');
            jQuery('li.'+jQuery(this).data('parent')).addClass('active');
        }
    });

    /* Open up the associated sub-menus of the active tab on load. */
    jQuery('.mk-main-sidebar-navigator-ul .ui-state-active').nextUntil('.menu').show();

    /* Make the next immediate sub-menu highlighted, since the parent tab points to this one. */
    jQuery('.mk-main-sidebar-navigator-ul .ui-state-active').next('.sub-menu').addClass('active');

    /*
**
General Background Selector
-------------------------------------------------------------*/
    function mk_background_selector_orientation() {
        var orientation = jQuery('#background_selector_orientation').val(),
            options = jQuery('#boxed_layout_shadow_size_wrapper, #boxed_layout_shadow_intensity_wrapper');
        //console.log(orientation);
        if (orientation === 'full_width_layout') {
            options.hide();
        } else {
            options.show();
        }
        /* update background viewer accordingly */
        jQuery('.mk-general-bg-selector').addClass(jQuery('#background_selector_orientation').val());
        jQuery('#background_selector_orientation_wrapper a, #background_selector_orientation_container a').click(function() {
            if (jQuery(this).attr('rel') === 'full_width_layout') {
                jQuery('.mk-general-bg-selector').removeClass('boxed_layout').addClass('full_width_layout');
                options.hide();
            } else {
                jQuery('.mk-general-bg-selector').removeClass('full_width_layout').addClass('boxed_layout');
                body_section_width = jQuery('.mk-general-bg-selector .outer-wrapper').width();
                jQuery('.mk-general-bg-selector.boxed_layout .body-section').css('width', body_section_width);
                options.show();
            }
        });
    }
    mk_background_selector_orientation();
    /* Background selector Edit panel */
    function select_current_element() {
        var options_parent_div = jQuery('.bg-repeat-option, .bg-attachment-option, .bg-position-option');
        options_parent_div.each(function() {
            jQuery(this).find('a').on('click', function(event) {
                event.preventDefault();
                jQuery(this).siblings().removeClass('selected').end().addClass('selected');
            });
        });
    }
    select_current_element();
    /* Call background Edit panel */
    function call_background_edit() {
        var sections = jQuery('.header-section, .page-section, .footer-section, .body-section, .banner-section');
        sections.each(function() {
            jQuery(this).on('click', function(event) {
                event.preventDefault();
                this_panel = jQuery(this);
                this_panel_rel = jQuery(this).attr('rel');
                jQuery('#mk-bg-edit-panel').fadeIn(200);
                // gets current section input IDs
                color_id = '#' + this_panel_rel + '_color';
                color_id_2 = '#' + this_panel_rel + '_color_2';
                gradient_id = '#' + this_panel_rel + '_color_gradient';
                gradient_angle_id = '#' + this_panel_rel + '_color_gradient_angle';
                gradient_style_id = '#' + this_panel_rel + '_color_gradient_style';
                image_id = '#' + this_panel_rel + '_image';
                size_id = '#' + this_panel_rel + '_size';
                parallax_id = '#' + this_panel_rel + '_parallax';
                position_id = '#' + this_panel_rel + '_position';
                repeat_id = '#' + this_panel_rel + '_repeat';
                attachment_id = '#' + this_panel_rel + '_attachment';
                source_id = '#' + this_panel_rel + '_source';
                color_value = jQuery(color_id).val();
                color_2_value = jQuery(color_id_2).val();
                gradient_value = jQuery(gradient_id).val();
                gradient_angle_value = jQuery(gradient_angle_id).val();
                gradient_style_value = jQuery(gradient_style_id).val();
                image_value = jQuery(image_id).val();
                size_value = jQuery(size_id).val();
                parallax_value = jQuery(parallax_id).val();
                position_value = jQuery(position_id).val();
                repeat_value = jQuery(repeat_id).val();
                attachment_value = jQuery(attachment_id).val();
                source_value = jQuery(source_id).val();
                jQuery('#bg_panel_color_style').attr('value', gradient_value);
                jQuery('#grandient_color_style').attr('value', gradient_style_value);
                jQuery('#grandient_color_angle').attr('value', gradient_angle_value);
                jQuery('#bg_panel_color_2').attr('value', color_value);
                jQuery('#bg_panel_color').attr('value', color_value);
                jQuery('#bg_panel_color').parent().siblings('.wp-color-result').css('background-color', color_value);
                jQuery('#bg_panel_color_2').parent().siblings('.wp-color-result').css('background-color', color_2_value);
                jQuery('#bg_panel_stretch').attr('value', size_value);
                if (size_value == 'true') {
                    jQuery('#bg_panel_stretch').parent().removeClass('mk-toggle-off').addClass('mk-toggle-on');
                } else {
                    jQuery('#bg_panel_stretch').parent().removeClass('mk-toggle-on').addClass('mk-toggle-off');
                }
                jQuery('#bg_panel_parallax').attr('value', parallax_value);
                if (parallax_value == 'true') {
                    jQuery('#bg_panel_parallax').parent().removeClass('mk-toggle-off').addClass('mk-toggle-on');
                } else {
                    jQuery('#bg_panel_parallax').parent().removeClass('mk-toggle-on').addClass('mk-toggle-off');
                }

                jQuery('#mk-bg-edit-panel a[rel="' + position_value + '"]').siblings().removeClass('selected').end().addClass('selected');
                jQuery('#mk-bg-edit-panel a[rel="' + repeat_value + '"]').siblings().removeClass('selected').end().addClass('selected');
                jQuery('#mk-bg-edit-panel a[rel="' + attachment_value + '"]').siblings().removeClass('selected').end().addClass('selected');
                if (source_value == 'custom' && image_value != '') {
                    jQuery('#bg_panel_upload').attr('value', image_value);
                    jQuery('.custom-image-preview-block img').attr('src', jQuery('#bg_panel_upload').val());
                }
                jQuery('#mk-bg-edit-panel').attr('rel', jQuery(this).attr('rel'));
                jQuery('#mk-bg-edit-panel').find('.mk-edit-panel-heading').text(jQuery(this).attr('rel'));
                jQuery('.bg-background-type-tabs').find('a[rel="' + source_value + '"]').parent().siblings().removeClass('current').end().addClass('current');
                jQuery('#mk-bg-edit-panel').find('.bg-background-type-panes').children('.bg-background-type-pane').hide();
                if (source_value == 'no-image') {
                    jQuery('#mk-bg-edit-panel').find('.bg-background-type-pane.bg-no-image').show();
                } else if (source_value == 'custom') {
                    jQuery('#mk-bg-edit-panel').find('.bg-background-type-pane.bg-edit-panel-upload').show();
                }
                if (gradient_value == 'gradient') {
                    jQuery('.panel-gradient-element').removeClass('is-hidden');
                } else {
                    jQuery('.panel-gradient-element').addClass('is-hidden');
                }
                if (gradient_style_value == 'linear' && gradient_value == 'gradient') {
                    jQuery('.panel-linear-gradient-el').removeClass('is-hidden');
                } else {
                    jQuery('.panel-linear-gradient-el').addClass('is-hidden');
                }
                jQuery('#mk-bg-edit-panel').find('.bg-background-type-tabs a').on('click', function(event) {
                    event.preventDefault();
                    jQuery('#mk-bg-edit-panel').find('.bg-background-type-panes').children('.bg-background-type-pane').hide();
                    jQuery(this).parent().siblings().removeClass('current').end().addClass('current');
                    if (jQuery(this).attr('rel') == 'no-image') {
                        jQuery('#mk-bg-edit-panel').find('.bg-background-type-pane.bg-no-image').show();
                    } else if (jQuery(this).attr('rel') == 'custom') {
                        jQuery('#mk-bg-edit-panel').find('.bg-background-type-pane.bg-edit-panel-upload').show();
                    }
                });
            });
        });
        jQuery('#bg_panel_color_style').change(function() {
            var this_value = jQuery(this).val();
            if (this_value == 'gradient') {
                jQuery('.panel-gradient-element').removeClass('is-hidden');
            } else {
                jQuery('.panel-gradient-element').addClass('is-hidden');
            }
        });
        jQuery('#grandient_color_style').change(function() {
            var this_value = jQuery(this).val();
            if (this_value == 'linear') {
                jQuery('.panel-linear-gradient-el').removeClass('is-hidden');
            } else {
                jQuery('.panel-linear-gradient-el').addClass('is-hidden');
            }
        });
    }
    call_background_edit();
    /* Background edit panel cancel and back buttons */
    jQuery('#mk_cancel_bg_selector, .mk-bg-edit-panel-heading-cancel').on('click', function(event) {
        event.preventDefault();
        jQuery('#mk-bg-edit-panel').fadeOut(200);
    });
    /* Triggers cancel button for background panel when escape key is pressed */
    jQuery(document).keyup(function(e) {
        if (e.keyCode == 27) {
            jQuery('#mk_cancel_bg_selector, .mk-bg-edit-panel-heading-cancel').click();
        }
    });
    /* Triggers Apply button for background panel when enter key is pressed */
    jQuery(document).keyup(function(e) {
        if (e.keyCode == 13) {
            jQuery('#mk_apply_bg_selector').click();
        }
    });
    /* Sends Panel Modifications into inputs and updates preview panel background */
    function update_panel_to_preview() {
        jQuery('#mk_apply_bg_selector').on('click', function(event) {
            event.preventDefault();
            panel = jQuery('#mk-bg-edit-panel');
            panel_source = panel.attr('rel');
            section_preview_class = '.' + panel_source + '-section';
            color = panel.find('#bg_panel_color').val();
            color_2 = panel.find('#bg_panel_color_2').val();
            color_gradient = panel.find('#bg_panel_color_style').val();
            color_gradient_style = panel.find('#grandient_color_style').val();
            color_gradient_angle = panel.find('#grandient_color_angle').val();
            bg_size = panel.find('#bg_panel_stretch').val();
            bg_parallax = panel.find('#bg_panel_parallax').val();
            position = jQuery('.bg-position-option').find('.selected').attr('rel');
            repeat = jQuery('.bg-repeat-option').find('.selected').attr('rel');
            attachment = jQuery('.bg-attachment-option').find('.selected').attr('rel');
            image_source = jQuery('.bg-background-type-tabs').find('.current').children('a').attr('rel');
            if (image_source == 'custom') {
                image = jQuery('#bg_panel_upload').val();
            } else if (image_source == 'no-image') {
                image = '';
            }
            // gets current section input IDs
            color_id = '#' + panel_source + '_color';
            color_2_id = '#' + panel_source + '_color_2';
            color_gradient_id = '#' + panel_source + '_color_gradient';
            color_grandient_style_id = '#' + panel_source + '_color_gradient_style';
            color_grandient_angle_id = '#' + panel_source + '_color_gradient_angle';
            image_id = '#' + panel_source + '_image';
            size_id = '#' + panel_source + '_size';
            parallax_id = '#' + panel_source + '_parallax';
            position_id = '#' + panel_source + '_position';
            repeat_id = '#' + panel_source + '_repeat';
            attachment_id = '#' + panel_source + '_attachment';
            source_id = '#' + panel_source + '_source';
            // Updates Input values
            jQuery(color_id).attr('value', color);
            jQuery(color_2_id).attr('value', color_2);
            jQuery(color_gradient_id).attr('value', color_gradient);
            jQuery(color_grandient_style_id).attr('value', color_gradient_style);
            jQuery(color_grandient_angle_id).attr('value', color_gradient_angle);
            jQuery(image_id).attr('value', image);
            jQuery(size_id).attr('value', bg_size);
            jQuery(parallax_id).attr('value', bg_parallax);
            jQuery(position_id).attr('value', position);
            jQuery(repeat_id).attr('value', repeat);
            jQuery(attachment_id).attr('value', attachment);
            jQuery(source_id).attr('value', image_source);

            if (bg_size == 'true') {
                stretch_option = 'cover';
            } else {
                stretch_option = 'contain';
            }
            //update preview panel background
            if (image != '') {
                jQuery(section_preview_class).find('.mk-bg-preview-layer').css({
                    'background-image': 'url(' + image + ')',
                    'background-size': stretch_option,
                });
            }

            if (image_source == 'no-image') {
                jQuery(section_preview_class).find('.mk-bg-preview-layer').css({
                    'background-image': 'none',
                });
            }
            if (color_gradient == 'single') {
                jQuery(section_preview_class).find('.mk-bg-preview-layer').css({
                    'background-color': color,
                    'background-position': position,
                    'background-repeat': repeat,
                    'background-attachment': attachment,
                });
            } else {
                if (color_gradient_style == 'linear') {
                    var gradient_style = 'linear';
                    if (color_gradient_angle == 'vertical') {
                        var gradient_angle_1 = 'top,',
                            gradient_angle_2 = 'to bottom,';
                    } else if (color_gradient_angle == 'horizontal') {
                        var gradient_angle_1 = 'left,',
                            gradient_angle_2 = 'to right,';
                    } else if (color_gradient_angle == 'diagonal_left_bottom') {
                        var gradient_angle_1 = 'top left,',
                            gradient_angle_2 = 'to bottom right,';
                    } else if (color_gradient_angle == 'diagonal_left_top') {
                        var gradient_angle_1 = 'bottom left,',
                            gradient_angle_2 = 'to top right,';
                    }
                } else if (color_gradient_style == 'radial') {
                    var gradient_style = 'radial',
                        gradient_angle_1 = '';
                    gradient_angle_2 = '';
                }
                var webkit_gradient = '-webkit-' + color_gradient_style + '-gradient(' + gradient_angle_1 + color + ' 0%, ' + color_2 + ' 100%)',
                    native_gradient = color_gradient_style + '-gradient(' + gradient_angle_2 + color + ' 0%, ' + color_2 + ' 100%)'
                jQuery(section_preview_class).find('.mk-bg-preview-layer').css({
                    'background': webkit_gradient,
                    'background': native_gradient,
                });
            }
            panel.fadeOut(200);
            panel.find('#bg_panel_color').val('');
            jQuery('.bg-position-option').find('.selected').removeClass('selected');
            jQuery('.bg-repeat-option').find('.selected').removeClass('selected');
            jQuery('.bg-attachment-option').find('.selected').removeClass('selected');
            jQuery('#bg_panel_upload').val('');
            jQuery('.custom-image-preview-block img').attr('src', '');
        });
    }
    update_panel_to_preview();
    /* Update the preview panel backgrounds on load */
    function update_preview_on_load() {
        jQuery('.page-section, .body-section, .header-section, .footer-section, .banner-section').each(function() {
            this_panel = jQuery(this);
            this_panel_rel = this_panel.attr('rel');
            // gets current section input IDs
            color_id = '#' + this_panel_rel + '_color';
            color_2_id = '#' + this_panel_rel + '_color_2';
            color_gradient_id = '#' + this_panel_rel + '_color_gradient';
            color_grandient_style_id = '#' + this_panel_rel + '_color_gradient_style';
            color_grandient_angle_id = '#' + this_panel_rel + '_color_gradient_angle';
            image_id = '#' + this_panel_rel + '_image';
            position_id = '#' + this_panel_rel + '_position';
            repeat_id = '#' + this_panel_rel + '_repeat';
            attachment_id = '#' + this_panel_rel + '_attachment';
            color = jQuery(color_id).val();
            color_2 = jQuery(color_2_id).val();
            color_gradient = jQuery(color_gradient_id).val();
            color_gradient_style = jQuery(color_grandient_style_id).val();
            color_gradient_angle = jQuery(color_grandient_angle_id).val();
            image = jQuery(image_id).val();
            position = jQuery(position_id).val();
            repeat = jQuery(repeat_id).val();
            attachment = jQuery(attachment_id).val();

            size_id = '#' + this_panel_rel + '_size';
            size_value = jQuery(size_id).val();
            if (size_value == 'true') {
                stretch_option = 'cover';
            } else {
                stretch_option = 'contain';
            }
            //update preview panel background
            if (image != '') {
                jQuery(this_panel).find('.mk-bg-preview-layer').css({
                    'background-image': 'url(' + image + ')',
                    'background-size': stretch_option,
                });
            }

            if (color_gradient == 'single') {
                jQuery(this_panel).find('.mk-bg-preview-layer').css({
                    'background-color': color,
                    'background-position': position,
                    'background-repeat': repeat,
                    'background-attachment': attachment,
                });
            } else {
                if (color_gradient_style == 'linear') {
                    var gradient_style = 'linear';
                    if (color_gradient_angle == 'vertical') {
                        var gradient_angle_1 = 'top,',
                            gradient_angle_2 = 'to bottom,';
                    } else if (color_gradient_angle == 'horizontal') {
                        var gradient_angle_1 = 'left,',
                            gradient_angle_2 = 'to right,';
                    } else if (color_gradient_angle == 'diagonal_left_bottom') {
                        var gradient_angle_1 = 'top left,',
                            gradient_angle_2 = 'to bottom right,';
                    } else if (color_gradient_angle == 'diagonal_left_top') {
                        var gradient_angle_1 = 'bottom left,',
                            gradient_angle_2 = 'to top right,';
                    }
                } else if (color_gradient_style == 'radial') {
                    var gradient_style = 'radial',
                        gradient_angle_1 = '';
                    gradient_angle_2 = '';
                }
                var webkit_gradient = '-webkit-' + color_gradient_style + '-gradient(' + gradient_angle_1 + color + ' 0%, ' + color_2 + ' 100%)',
                    native_gradient = color_gradient_style + '-gradient(' + gradient_angle_2 + color + ' 0%, ' + color_2 + ' 100%)'
                jQuery(this_panel).find('.mk-bg-preview-layer').css({
                    'background': webkit_gradient,
                    'background': native_gradient,
                });
            }
        });
    }
    update_preview_on_load();
});
/*
**
Save Masterkey Options
-------------------------------------------------------------*/
jQuery(document).ready(function() {
    var form = jQuery('.mk-options-container form');
    form.find('.mk-main-panes').removeClass('hidden-view');
    jQuery("button", form).bind("click keypress", function() {
        form.data("callerid", this.name);
    });
    jQuery('form#masterkey_settings').submit(function() {
        var callerId = jQuery(this).data("callerid");
        window.progressCircle().play();

        function newValues() {
            var serializedValues = jQuery('#masterkey_settings input, #masterkey_settings select, #masterkey_settings textarea[name!=theme_export_options]').serialize();
            return serializedValues;
        }
        jQuery(":hidden").change(newValues);
        jQuery("select").change(newValues);
        var serializedReturn = newValues();
        jQuery('#mk-saving-settings').show();
        data = serializedReturn + '&button_clicked=' + callerId;
        //console.log(callerId);
        //alert(serializedReturn);
        jQuery.post(ajaxurl, data, function(response) {
            //console.log(response);
            show_message(response);
        });
        return false;
    });
    /* History Section */
    jQuery("#mk_history_modal").click(function(event) {
        event.preventDefault();
        jQuery.post(ajaxurl, {
            action: 'mk_list_theme_option_revision',
        }).done(function(response) {
            if (response.status === true) {
                var rev_item_list = '';
                jQuery.each(response.data, function(key, val) {
                    rev_item_list += '<a href="#" data-name="'+val+'" class="mk_revision_item">Revision Number : <strong>'+ (key+1) +'</strong> @ <strong>'+val+'</strong></a><br>'
                });
                jQuery('#mk-list-of-theme-option-revisions').html(rev_item_list);
                jQuery('#mk-list-of-revisions').show();
                jQuery('.mk-main-panes').addClass('hidden-view');
                return false;
            }
        }).fail(function(data) {
            console.log('Failed msg : ', data);
        });
    });
    jQuery(document).on('click', '.mk_revision_item', function(event) {
        jQuery('#mk-list-of-revisions').hide();
        jQuery('.mk-main-panes').removeClass('hidden-view');
        window.progressCircle().play();

        jQuery.post(ajaxurl, {
            action: 'mk_restore_theme_option_revision',
            revision_name: jQuery(this).data('name'),
        }).done(function(response) {
            show_message(response);
            if (response.status == true) {
                location.reload();
            }
        }).fail(function(data) {
            console.log('Failed msg : ', data);
        });
    });
    /* Confirm Reset to default box */
    jQuery("#mk_reset_confirm").click(function() {
        jQuery('#mk-are-u-sure').show();
        jQuery('.mk-main-panes').addClass('hidden-view');
        return false;
    });
    jQuery("#mk_reset_confirm").click(function() {
        jQuery('#mk-are-u-sure').show();
        jQuery('.mk-main-panes').addClass('hidden-view');
        return false;
    });
    jQuery(".popup-toggle-close").click(function() {
        jQuery(this).parent('.mk-message-box').hide();
        jQuery('.mk-main-panes').removeClass('hidden-view');
        return false;
    });
    jQuery("#mk_reset_ok").click(function() {
        jQuery('#mk-are-u-sure').hide();
        jQuery('.mk-main-panes').addClass('hidden-view');
        jQuery('#reset_theme_options').trigger('click');
        return false;
    });
    /**************/
    /* Disables enter key on masterkey options to prevent any unwilling submittions */
    jQuery("#masterkey_settings input").keypress(function(event) {
        if (event.which == 13) {
            event.preventDefault();
        }
    });
});
/* Show Box Messages */
function show_message(n) {
    console.log('Sys msg : ' , n);
        if (n.hasOwnProperty('status')) {
            if (n.data != null) {
                jQuery('#' + n.data.element).show();
                if(n.data.modal) {
                    setTimeout(function() {
                        jQuery('#' + n.data.element).hide();
                    }, 1500);
                } else if (! n.data.reload) {
                    jQuery('#' + n.data.element).hide();
                    window.progressCircle().status(n.status, n.message);
                }else {
                    jQuery('.mk-main-panes').addClass('hidden-view');
                    setTimeout(function() {
                        location.reload();
                    }, 2000);
                }
            } else {
                jQuery('#mk-message-modal-txt').html(n.message);
                if (n.status == false) {
                    jQuery('#mk-message-modal > img').attr('src', jQuery('#mk-message-modal > img').attr('src').replace('success-icon', 'warning-icon'));
                }
                jQuery('#mk-message-modal').show();
            }
        }
}

// VC Admin JS for Edge Slider
(function($) {
    'use strict';

    $(document).on('ajaxComplete.mk_edgeslider_lazyload_option', function(e) {
        if ( typeof theme_backend_localized_data !== 'undefined' && theme_backend_localized_data.mk_global_lazyload == 'true' ) {
            var $lazyload = $('.vc_shortcode-param').filter(function(index) {
                return $(this).attr('data-vc-shortcode-param-name') === 'lazyload';
            });
            $lazyload.hide();
        } else {
            var $disable_lazyload = $('.vc_shortcode-param').filter(function(index) {
                return $(this).attr('data-vc-shortcode-param-name') === 'disable_lazyload';
            });
            $disable_lazyload.hide();
        }
    });

})(jQuery);
