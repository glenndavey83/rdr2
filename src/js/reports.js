
// JAVASCRIPT
// LUCKYBUYS
// REPORTS

(function($) {
	
	var 
		MerchantStoresFilter = $("#merchant-stores-filter"),
		MerchantCustomersFilter = $("#merchant-customers-filter"),
		MerchantTransactionsFilter = $("#merchant-transactions-filter"),
		MerchantReportsFilter = $("#merchant-reports-filter")
	;
	
	$(document).ready(function() {
		/*
		$(".lb-toggle-show").on("change", function() {
			$("#merchants-filter").submit();
		});*/
		
		if (MerchantStoresFilter.length)
			MerchantStoresFilter.listy();

		if (MerchantCustomersFilter.length)
			MerchantCustomersFilter.listy();

		if (MerchantTransactionsFilter.length)
			MerchantTransactionsFilter.listy();
			
		if (MerchantReportsFilter.length)
			MerchantReportsFilter.reporty();
		
	});

} )( jQuery );



// REPORTY Plugin


( function($) {

	$.reporty = function(container, options) {

		var plugin = this,
			$container = $(container);
        
		var settings, timer, delay;

		// Default settings
		var defaults = {
			delay: 300,
		};
    
		plugin.init = function() {
 
			plugin.settings = settings = $.extend({}, defaults, options);
		
			$container.data('reporty', {});
			
			$('select[name=report]', $container).on("change", function() {
	             
	            var data = {
	                report: $(this).val() || '1',
	                id: $("input[name=id]", $container).val(),
	            };
	   			
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
  
	$.fn.reporty = function(options) {
		var args = arguments;

		return this.each(function() {
			var $this = $(this),
				plugin = $this.data('reporty');

			// Initialization was called with $(el).reporty( { options } );
			if (undefined === plugin) {
				plugin = new $.reporty(this, options);
				$this.data('reporty', plugin);
			}
	
			// User called public method
			if ( plugin.publicMethods[options] ){
				return plugin.publicMethods[options](Array.prototype.slice.call( args, 1 ));
			}
		});
	
	};

})(jQuery);