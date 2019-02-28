
// LUCKYBUYS 
// ADMIN JAVASCRIPT

// JAVASCRIPT FUNCTIONS

(function($) {

	// Variables and DOM Caching
	
	var $body = $('body'),
		$tabsContainer = $(".luckytabs"),
		$dateRanges = $("#DateRangeFilters"),
		accordionMenu = $('.accordion-menu')
	;
	
	
	// Document Ready
	
	$(document).ready(function() {
	
		console.log("Document ready!");	
		
		// GLOBAL SETUPS
		
		// Tell CSS that JS is present
		$("html").removeClass("no-js");
		
		
		// Details and Summary		
		if ($("details").length) 
			setupDetailSummary();
		
		
		// Tabs
		if ($(".luckytabs.toptabs").length) {
			
			$(".luckytabs.toptabs").easytabs({ 
				animate: false, 
				tabActiveClass: "nav-tab-active", 
				updateHash: true  
			});

			$(window).on('hashchange', function() {
				hashslice = location.hash.slice(1);
				
				if (hashslice.length > 0)
				    $(".luckytabs.toptabs").easytabs("select", hashslice);
				else
					$(".luckytabs.toptabs").easytabs("select", $(".luckytabs li:first a").attr("href"));
			});

		}
		

		if ($(".luckytabs.sidetabs").length) {
			
			$(".luckytabs.sidetabs").easytabs({ 
				animate: false, 
				tabActiveClass: "nav-tab-active", 
				updateHash: false  
			});
		}
		

		// Date Ranges 
		if ($dateRanges.length)
			setupDateRanges();
		
		
		if (accordionMenu.length)
			setupAccordionMenus();			
		
	});
	
	
	// Setup Date Ranges
	
	function setupDateRanges() {
		$("label", $dateRanges).each(function() {
			if ($("input", this).prop('checked'))
				$(this).addClass("checked");
		});
		
		$("input:radio", $dateRanges).on("change", function(e) {				
			$("label", $dateRanges).removeClass("checked");
							
			if ($(this).prop('checked'))					
				$(this).parent().addClass("checked");
		});
	}
	
	// Setup Details and Summary
		// Handle <details> and <summary> tags
		// that only have partial support
		
	function setupDetailSummary() {
		console.log("Setting up Detail/Summary elements...");
		$('details').details();
		// Conditionally add a classname to the `<html>` element, based on native support
		$('html').addClass($.fn.details.support ? 'details' : 'no-details');
		$(document).on("keyup click", "summary", function(e) {
			$(window).trigger('resize').trigger('scroll');
		});
	}
	
	
	
	// Setup Accordion Menus
	
	function setupAccordionMenus() {
		
		$("> li > a", accordionMenu).on("click", function(e) {
			
			if ($(this).parent().hasClass("active")) {
				$(this).parent().removeClass("active");
			} else {
				$("li", accordionMenu).removeClass("active");
				$(this).parent().addClass("active");
			}
		});
		
		$("li.disabled a, li.menu-item-has-children a", accordionMenu).on("click", function(e) {
			e.preventDefault();
		});

	}
	
})(jQuery);






// LISTY Plugin


( function($) {

	$.listy = function(container, options) {

		// Attach to plugin anything that should be available via
		// the $container.data('easytabs') object
		var plugin = this,
			$container = $(container);
        
		var settings, timer, delay;

		// Default settings
		var defaults = {
			delay: 500,
		};


	    /**
	     * Register our triggers
	     * 
	     * We want to capture clicks on specific links, but also value change in
	     * the pagination input field. The links contain all the information we
	     * need concerning the wanted page number or ordering, so we'll just
	     * parse the URL to extract these variables.
	     * 
	     * The page number input is trickier: it has no URL so we have to find a
	     * way around. We'll use the hidden inputs added in List_Table::display()
	     * to recover the ordering variables, and the default paged input added
	     * automatically by WordPress.
	     */
    
		plugin.init = function() {
 
			plugin.settings = settings = $.extend({}, defaults, options);
		
			// Store listy object on container so we can easily set
			// properties throughout
			$container.data('listy', {});
	       
	       // Pagination links, sortable link
	        $('.tablenav-pages a, .manage-column.sortable a, .manage-column.sorted a', $container).on('click', function(e) {
	            
	            // We don't want to actually follow these links
	            e.preventDefault();
	            
	            // Simple way: use the URL to extract our needed variables
	            var searchquery = this.search.substring( 1 );
	             
	            var data = {
	                paged: query( searchquery, 'paged' ) || '1',
	                order: query( searchquery, 'order' ) || '',
	                orderby: query( searchquery, 'orderby' ) || '',
	                id: query( searchquery, 'id' ) || '',
	                status: query( searchquery, 'status' ) || '',
	                merchantstatus: query( searchquery, 'merchantstatus' ) || '',
	                code: query( searchquery, 'code' ) || '',
	                report: query( searchquery, 'report' ) || '',
	            };
	   
	            plugin.update( data );
	        });
	 
	        // Page number input
	        $('input[name=paged]', $container).on('keyup', function(e) {
	 	 
	            // This time we fetch the variables in inputs
	            var data = {
	                paged: parseInt( $('input[name=paged]', $container).val() ) || '1',
	                order: $('input[name=order]', $container).val() || '',
	                orderby: $('input[name=orderby]', $container).val() || '',
	                id: $('input[name=id]', $container).val() || '',
	                status: $('select[name=status]', $container).val() || '',
					merchantstatus: $('select[name=merchantstatus]', $container).val() || '',
	                code: $('select[name=code]', $container).val() || '',
	                report: $('select[name=report]', $container).val() || '',
	            };
	 
	            window.clearTimeout( timer );
	            timer = window.setTimeout(function() {
	                plugin.update( data );
	            }, delay);
	            
	        });
	        
	        
	        $container.on("submit", function(e) {
	        	
	        	// We don't want to actually submit
	        	e.preventDefault();
	        	
	        	// This time we fetch the variables in inputs
	        	/*
	            var data = {
	                paged: parseInt( $('input[name=paged]', $container).val() ) || '1',
	                order: $('input[name=order]', $container).val() || '',
	                orderby: $('input[name=orderby]', $container).val() || '',
	                id: $('input[name=id]', $container).val() || '',
	                status: $('select[name=status]', $container).val() || 1,
	                merchantstatus: $('select[name=merchantstatus]', $container).val() || 1,
	                sales: $('select[name=sales]', $container).val() || 0,
	                code: $('select[name=code]', $container).val() || '',
	                report: $('select[name=report]', $container).val() || '',
	            };*/
				
				var data = $container.serialize();
				
				console.log(data);
				/*
				jQuery.each( dataArray, function( i, field ) {
			      $( "#results" ).append( field.value + " " );
			    });
	           
				
				*/
	            window.clearTimeout( timer );
	            timer = window.setTimeout(function() {
	                plugin.update( data );
	            }, delay);

	        });
	    };
	 
 
 
	    /** AJAX call
	     * 
	     * Send the call and replace table parts with updated version!
	     * 
	     * @param    object    data The data to pass through AJAX
	     */
	    plugin.update = function( data ) {
	    	$(".wp-list-table", $container).addClass("updating");
	    	fulldata = $.extend(
	                {
	                    _ajax_custom_list_nonce: $('#_ajax_custom_list_nonce', $container).val(),
	                    action: $('#ajaxaction', $container).val(),
	                },
	                data);
	                
	        
	        
	        console.log(data);
	        
	        $.ajax({
	            // /wp-admin/admin-ajax.php
	            url: ajaxurl,
	            method: "POST", 
	            // Add action and nonce to our collected data
	            data: data
	            ,
	            
	            // Handle the successful result
	            success: function( response ) {
	 				$(".wp-list-table", $container).removeClass("updating");
	 				//console.log(response);
	 				
	                // WP_List_Table::ajax_response() returns json
	                var response = $.parseJSON( response );
	 
	                // Add the requested rows
	                if ( response.rows.length )
	                    $('#the-list', $container).html( response.rows );
	                // Update column headers for sorting
	                if ( response.column_headers.length )
	                    $('thead tr, tfoot tr', $container).html( response.column_headers );
	                // Update pagination for navigation
	                if ( response.pagination.bottom.length )
	                    $('.tablenav.top .tablenav-pages', $container).html( $(response.pagination.top).html() );
	                if ( response.pagination.top.length )
	                    $('.tablenav.bottom .tablenav-pages', $container).html( $(response.pagination.bottom).html() );
	 
	                // Init back our event handlers
	                plugin.init();
	            },
				
				error: function(response) {
					$(".wp-list-table", $container).removeClass("updating");
					console.log(response["statusText"]);
				},
            
			});
		};
 
 
	     // Convenient public methods
	    plugin.publicMethods = {

	    };
 
		// =============================================================
		// Private functions
	    // =============================================================
 
	    /**
	     * Filter the URL Query to extract variables
	     * 
	     * @see http://css-tricks.com/snippets/javascript/get-url-variables/
	     * 
	     * @param    string    query The URL query part containing the variables
	     * @param    string    variable Name of the variable we want to get
	     * 
	     * @return   string|boolean The variable value if available, false else.
	     */
		var query = function( query, variable ) {
			var vars = query.split("&");
			for ( var i = 0; i <vars.length; i++ ) {
			    var pair = vars[ i ].split("=");
			    if ( pair[0] == variable )
			        return pair[1];
			}
			return false;
		};

		plugin.init();

	};
  
	$.fn.listy = function(options) {
		var args = arguments;

		return this.each(function() {
			var $this = $(this),
				plugin = $this.data('listy');

			// Initialization was called with $(el).listy( { options } );
			if (undefined === plugin) {
				plugin = new $.listy(this, options);
				$this.data('listy', plugin);
			}
	
			// User called public method
			if ( plugin.publicMethods[options] ){
				return plugin.publicMethods[options](Array.prototype.slice.call( args, 1 ));
			}
		});
	
	};

})(jQuery);



// FILTERY Plugin


( function($) {

	$.filtery = function(container, options) {

		var plugin = this,
			$container = $(container);
        
		var settings, timer, delay;

		// Default settings
		var defaults = {
			delay: 300,
		};
    
		plugin.init = function() {
 
			plugin.settings = settings = $.extend({}, defaults, options);
		
			$container.data('filtery', {});
			
			$container.on("submit", function(e) {
				e.preventDefault();
	            
	            var data = $container.serializeArray();
	   			
	            plugin.update( data );
			});
			
	    };
	 
 
 
	    /** AJAX call
	     * 
	     * Send the call and replace table parts with updated version!
	     * 
	     * @param    object    data The data to pass through AJAX
	     */
	    plugin.update = function( data ) {
	    	
	    	var target = "#" + data.report + ".report";
	    	//console.log(target);
	    	//console.log($(target).length);
	    	
	    	if ($(target).length == 0) {
	    		
	    		$(".report").addClass("updating");
	    		$(".report").last().after("<div class='report' id='" + data.report + "' style='display:none;'></div>");
	    	
		        $.ajax({
		            // /wp-admin/admin-ajax.php
		            url: ajaxurl,
		            // Add action and nonce to our collected data
		            data: $.extend(
		                {
		                    _ajax_report_nonce: $('#_ajax_report_nonce', $container).val(),
		                    action: $('#ajaxaction', $container).val(),
		                },
		                data
		            ),
		            
		            // Handle the successful result
		            success: function( response ) {
		 				
		 				console.log(response);
		 				$(".report").hide(0).removeClass("updating");
		 				
		 				// Update Report
		 				$(target).html(response).show(0);
		                
		                // Init back our event handlers
		              	//plugin.init();
		            },
					
					error: function(response) {
						$(".report").removeClass("updating");
						//console.log(response["statusText"]);
					},
	            
				});
				
			} else {
				
				$(".report")
					.addClass("updating")
					.delay(300)
					.hide(0, function() {
						$(target)
							.show(0)
							.delay(300)
							.removeClass("updating");
					});
			}
			
			
			
		};
 
 
	     // Convenient public methods
	    plugin.publicMethods = {

	    };
 
		// =============================================================
		// Private functions
	    // =============================================================
 
	    /**
	     * Filter the URL Query to extract variables
	     * 
	     * @see http://css-tricks.com/snippets/javascript/get-url-variables/
	     * 
	     * @param    string    query The URL query part containing the variables
	     * @param    string    variable Name of the variable we want to get
	     * 
	     * @return   string|boolean The variable value if available, false else.
	     */
		var query = function( query, variable ) {
			var vars = query.split("&");
			for ( var i = 0; i <vars.length; i++ ) {
			    var pair = vars[ i ].split("=");
			    if ( pair[0] == variable )
			        return pair[1];
			}
			return false;
		};

		plugin.init();

	};
  
	$.fn.filtery = function(options) {
		var args = arguments;

		return this.each(function() {
			var $this = $(this),
				plugin = $this.data('filtery');

			// Initialization was called with $(el).filtery( { options } );
			if (undefined === plugin) {
				plugin = new $.filtery(this, options);
				$this.data('filtery', plugin);
			}
	
			// User called public method
			if ( plugin.publicMethods[options] ){
				return plugin.publicMethods[options](Array.prototype.slice.call( args, 1 ));
			}
		});
	
	};

})(jQuery);

