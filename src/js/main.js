
// Javascript document

// RDR2 Progress Tracker
// Plugin
// Frontend Scripting


jQuery( document ).ready( function( $ ) {
	
	var 
		$PortalContainer = $( "#PlayerPortal" ),
		$RandomTip = $( "#RandomTip" ),
		$tabsContainer = $( ".luckytabs" )
	;
	
	// Tell CSS that JS is present
	$( "html" ).removeClass( "no-js" );


	// Random Tips
	if ( $RandomTip.length )
		$RandomTip.tippy();

	//  Portal 
	if ( $PortalContainer.length )
		$PortalContainer.tracky();		
	

});


