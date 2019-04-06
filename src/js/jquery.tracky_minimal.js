

// RDR2 PROGRESS TRACKER 

// jQuery Plugin


( function( $ ) {

	$.tracky = function( container, options ) {
	

		var 
			// Handles
			plugin = this,
			$container = $( container ),
			slug = 'tracky',
			defaults = {
				
			}

		;

		/////////////////////////////////////////////////////////////////////////
		// Initiate Plugin
		
		plugin.init = function( ) {
			
			plugin.settings = $.extend( {}, defaults, options );
			
			$container.data( slug, {} );
	    };
	    
	    

		// UPDATE PLUGIN
		// This is where we send the latest player game form data to the database
	    plugin.update = function( response ) {
			
		};
		
		// Public Methods
	    plugin.publicMethods = {
	
	    };
	
		
		
		// Initiate Plugin
		plugin.init();
	
		
		
		
		
		
		
		
		
		// Tracky jQuery Function
		
		$.fn.tracky = function( options ) {
			var args = arguments;
	
			return this.each( function() {
				var $this = $( this ),
					plugin = $this.data( 'tracky' );
	
				if ( undefined === plugin ) {
					plugin = new $.tracky( this, options );
					$this.data( 'tracky', plugin );
				}
	
				if ( plugin.publicMethods[ options ] )
					return plugin.publicMethods[ options ]( Array.prototype.slice.call( args, 1 ) ); 
			
				return;
			});
		};
		
	};

})( jQuery );
