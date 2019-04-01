
// Javascript document

// RDR2 Progress Tracker
// Plugin
// Frontend Scripting


jQuery(document).ready(function($) {
	
	var 
		PortalContainer = $("#PlayerPortal"),
		RandomTip = $("#RandomTip"),
		$tabsContainer = $(".luckytabs")
	;
	
	// Tell CSS that JS is present
	$("html").removeClass("no-js");
	

	//  Portal 
	if (PortalContainer.length)
		PortalContainer.tracky();		
	
	
	// Random Tips
	if (RandomTip.length)
		RandomTip.tippy();
	

});




















// TIPPY Plugin

( function($) {

	$.tippy = function(container, options) {

		var plugin = this,
			$container = $(container);

		var tipRecord = [];

		var defaults = {
			delay: 300,
		};
    
		plugin.init = function() {
 
			plugin.settings = settings = $.extend({}, defaults, options);
		
			$container.data('tippy', {});
			
			$container.addClass("updating");
			
			$container.html("<div class='app-section'></div>");
			
			$section = $(".app-section", $container);
			
			var data = {
				game_id: 1,
			};
			
	        $.ajax({
	        	
				url: ajaxurl,
				dataType: 'json',
				contentType: 'application/json',

				data: $.extend(
					{
						_ajax_tip_nonce: $('#_ajax_tip_nonce').val(),
						action: "_ajax_fetch_tips",
					},
					data
				),
	            
				success: function( response ) {
	 				plugin.update( response );
	              				
					$container.on("click", function() {
						$container.addClass("updating");
						
						plugin.update( response );
					});
	            },
				
				error: function( response ) { 
					$container.removeClass("updating");
				},
            
			});	

			
	    };
	 

	    plugin.update = function( response ) {
	    	
	    	setTimeout(function() {
	    		var responseNumber = getRandomTipNumber( response );
	    		tipRecord.push(responseNumber);
		    	$section
		    		.html("<p>" + response[responseNumber].Description + "</p>")
	    			.removeClass("updating")
	    		;
	    	}, 300);
	    	
		};
 
 
	    plugin.publicMethods = {

	    };
 		
 		
 		var getRandomTipNumber = function( tips ) {
 			if (!tips.length) 
 				return false;
 			
 			var newTipNumber = getRandomNumber(tips.length);
 			
 			// If we have previous tips
 			if (tipRecord.length) {
 				
 				halfTipLength = tips.length / 2;
 				limitStart = (tipRecord.length > halfTipLength) ? tipRecord.length - halfTipLength : 0;
 				
 				if (tipRecord.slice(limitStart, tipRecord.length).indexOf(newTipNumber) !== -1 )
 					newTipNumber = getRandomTipNumber( tips );
 			}
 			
 			return newTipNumber;
 		};
 		
 		
 		
 		var getRandomNumber = function( limit ) {
 			return Math.floor(Math.random() * limit);
 		};
 		
 
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
  
  
	$.fn.tippy = function(options) {
		var args = arguments;

		return this.each(function() {
			var $this = $(this),
				plugin = $this.data('tippy');

			if (undefined === plugin) {
				plugin = new $.tippy(this, options);
				$this.data('tippy', plugin);
			}
	
			// User called public method
			if ( plugin.publicMethods[options] ) 
				return plugin.publicMethods[options](Array.prototype.slice.call( args, 1 ));
		});
	
	};

})( jQuery );





Array.prototype.unique = function() {
	return this.filter(function (value, index, self) { 
		return self.indexOf(value) === index;
	});
};









