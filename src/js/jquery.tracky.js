
// RDR2 PROGRESS TRACKER 
// jQuery Plugin


( function( $ ) {

$.tracky = function( container, options ) {

	var 
		// Handles
		plugin = this,
		$container = $( container ),
		$app = false,
		$UpdatingFlag = false
		
		// Variables
		name = 'Tracky',
		slug = 'tracky',
    	title = name + ' jQuery Plugin',
		logging = true, 
		logPrefix = name,
		delay = 2500, 
		defaults = {
			delay: delay,
			Swiper : {
				direction : 'horizontal', 
				loop : false, 
				get wrapperClass() { return classes.App.Sections.Wrapper; }, 
				get slideClass() { return classes.App.Section; }, 
				autoHeight : false,
				slidesPerView : 1,
				slidesPerColumn : 1,
				spaceBetween : 20,
				shortSwipes : false,
				resistanceRatio : 0,
				simulateTouch : false,
			},
		},
		swiper,
		
		
		// Data Variables
		craftable_sources = JSON.parse( database.craftable_sources ), 
		craftable_categories = JSON.parse( database.craftable_categories ), 
		craftable_groups = JSON.parse( database.craftable_groups ), 
		collectables = JSON.parse( database.collectables ), 
		craftables = JSON.parse( database.craftables ), 
		craftable_collectables = JSON.parse( database.craftable_collectables ), 

		ingredients = JSON.parse( database.ingredients ), 
		ingredient_types = JSON.parse( database.ingredient_types ), 
		ingredient_qualities = JSON.parse( database.ingredient_qualities ), 
		ingredient_parts = JSON.parse( database.ingredient_parts ), 
	
		player_game_craftables = JSON.parse( database.player_game_craftables ), 
		player_game_collected = JSON.parse( database.player_game_collected ), 
		player_game_submitted = JSON.parse( database.player_game_submitted ), 
		
		// Content
		content = {
			Screens : [
				{ name: 'Source' },
				{ name: 'IngredientType' },
			],
			UpdatingLabel : 'Updating...'
		},
		
		// Classes
		classes = {
			Active : 'active',
			Available : 'available',
			App : {
				Container : 'app-container',
				Section : 'app-section',
				Sections : {
					Container : 'app-sections-container',
					List : 'app-sections-list',
					Filter : 'app-sections-filter',
					Wrapper : 'app-sections-wrapper',
				},
				Tabs : {
					Wrapper : 'nav-tab-wrapper',
					Nav : 'nav-tab',
					Active : 'nav-tab-active',
				},
				CheckboxList : 'app-checkbox-list'
			},
			Category : {
				Name : 'category-name',
				Groups : 'category-groups',
				Group : {
					Container : 'category-group',
					Input : 'category-group-input',
					Checkbox : 'category-group-checkbox',
				},
			},
			CheckboxList : 'checkbox-list',
			Collectable : {
				Input : 'collectable-input',
				Item : {
					Container : 'collectable-item',
					Input : 'collectable-item-input',
					Checkbox : 'collectable-item-checkbox',
				},
			},
			Craftable : {
				Collectable : {
					Input : 'craftable-collectable-input',
				},
			},
			Dashicon : 'dashicons',
			Dashicons : {
				Yes : 'dashicons-yes',
			},
			DeadCheckbox : 'DeadCheckbox',
			Disabled : 'Disabled', 
			Filters : {
				Remaining : 'filter-remaining',
			},
			Group : {
				Craftable : {
					Input : 'group-craftable-input',
					Label : 'group-craftable-label',
					Name : 'group-craftable-name',
				},
				Craftables : {
					Input : 'group-craftables-input',
				},
			},
			Ingredient : {
				Label : 'ingredient-label',				
			},
			Input : {
				Craftable : 'craftable-input',
			},
			List : {
				Horizontal : 'horizontal',
				Pill : 'pill',
			},
			
			Names : 'name',
			Name : {
				Quality : 'quality-name',
				Ingredient : 'ingredient-name',
				Part : 'part-name',
				Quantity : 'quantity',
			},
			Open : 'open',
			ScreenReader : 'screen-reader-text',
			Source : {
				Icons : {
					1: 'fas fa-campground',
					2: 'fas fa-dollar-sign',
					3: 'far fa-envelope',
					4: 'fas fa-paw',
				},
				Tab : 'source-tab',
				Category : {
					Container : 'source-category',
					Name : 'source-category-name',
				},
			},
			Sources : {
				
			},
			Swiper : {
				Container : 'swiper-container',
				Slide : 'swiper-slide',
			},
			Tab : {
				Lucky : 'luckytab',
				Content : 'tabContent',
			},
			UpdatingFlag : 'updating-flag',
			UpdatingLabel : 'updating-label',
		},
		
		// Events
		events = {
			Click : 'click',
			Change : 'change',
			Resize : 'resize',
			OrientationChange : 'orientationchange',
			Blur : 'blur',
			Focus : 'focus',
		},		
		
		// HTML
		html = {
			App : {
				Container : '<div class="' + classes.App.Container + '" id="' + ids.App + '"></div>',
				List : '<div class="' + classes.App.Sections.List + '"><ul class="' + classes.List.Horizontal + ' ' + classes.List.Pill + '"><li><a data-id="0" class="' + classes.Active + '">Sources</a></li><li><a data-id="1">Collectables</li></ul></div>',
				get Filters() { return '<div class="' + classes.App.Sections.Filter + '"><ul class="' + classes.List.Horizontal + ' ' + classes.List.Pill + '"><li><label class=""><input type="checkbox" name="' + name.Filter + '" value="1" id="' + ids.Filters.Remaining + '"> Show remaining only </label></li></ul></div>'; },
				Sections : '<div class="' + classes.App.Sections.Container + '"><div class="' + classes.App.Sections.Wrapper + ' swiper-wrapper"></div></div>',
			},
			UpdatingFlag : '<div class="' + classes.UpdatingFlag + '" id="' + ids.UpdatingFlag + '"><span class="' + classes.UpdatingLabel + '">' + content.UpdatingLabel + '</span></div>',
			Sources : {
				Heading : '<h3 class="' + classes.ScreenReader + '">Sources</h3>',
			},	
		},
		
		// IDs
		ids = {
			App : 'App',
			Filters : {
				Remaining : 'FilterRemaining',
			},
			Game : 'game_id',
			UpdatingFlag : 'UpdatingFlag',
			Source : {
				get Name( text ) { return ( text !== false ) ? 'Source-' + text : false; }, 
			},
			Sources : {
				Craftable : 'CraftableSources',	
			},
		},
		
		
		// Names (Inputs)
		name = {
			Collectable : 'collectableitems',
			Craftables : 'craftableitems',
			Filter : 'filter',
		},
		
		// Properties	
		property = {
			Checked : 'checked',
			Disabled : 'disabled',
			Open : 'open',
		},
		
		
		// States
		state = {
			Interval : {
				Modify : {
					Page : false,
				},	
				Update : {},
			},	
		}
	;







	/////////////////////////////////////////////////////////////////////////
	// Initiate Plugin
	
	plugin.init = function( ) {
 
		plugin.settings = $.extend( {}, defaults, options );
		
		$container.data( slug, {} );

		var data = {
			game_id: $( '#' + ids.Game ).val(),
		};
		
		
		// Updating Flag
		
		// APPEND Updating Flag for all screens
		$container.append( html.UpdatingFlag );
		
		// IDENTIFY Updating Flag element to handle
		$UpdatingFlag = $( ids.UpdatingFlag, $container );
		
		
		// App Container

		// APPEND App Container
		$container.append( html.App.Container );
		
		// IDENTIFY App Container to handle
		$app = $( '#' + ids.App, $container );
		
		
		// App Screens
		
		// Setup App Screens
		plugin.setupAppScreens( data );

		
		
		// Details/Summary
		
		// Setup all Details and Summary elements		
		setupDetailSummary();
		
    };	
	
	
	
	
	
	
	
	/////////////////////////////////////////////////////////////////////////
	// Setup App Screens			
	
	plugin.setupAppScreens = function( data ) {
		
		// ABORT if App not found
		if ( ! $app.length ) {
			error( 'Cannot find App to setup Screens!' );
			return false;
		}
		
		
		
		// Section List
		
		// APPEND Section list to App
		$app.append( html.App.List );
		
		// IDENTIFY Section list to handle
		$list = $( '.' + classes.App.Sections.List, $app );
		
		
		
		// App Filters
		
		// APPEND App Filters to App
		$app.append( html.App.Filters );
		
		// IDENTIFY App Filters to handle
		$filters = $( '.' + classes.App.Sections.Filter, $app );
		
		
		
		// App Sections
		
		// APPEND App Sections to App
		$app.append( html.App.Sections );
		
		// Prepare Sections to use Swiper
		
		// IDENTIFY Swiper to handle
		$swiper = $( '.' + classes.App.Sections.Container, $app );
		
		// ADD CLASS to Swiper
		$swiper.addClass( classes.Swiper.Container );
		
		// IDENTIFY Sections to handle
		$sections = $( '.' + classes.App.Sections.Wrapper, $swiper );
		
		
		// APPEND Screens to Sections
		
		// APPEND Show By SOURCE Screen
		showBySource( data );
		
		// APPEND Show By INGREDIENT TYPE Screen
		showByIngredientType( data );
		
		
		// INITIATE Swiper on SECTIONS
		swiper = new Swiper( '.' + classes.Swiper.Container, $.extend( {}, plugin.settings.Swiper, {		
				on: {
					slideChange: function () {
						$( 'a:eq(' + swiper.activeIndex + ')', $list ).click();
					},
				},
			})
		);
		
		
		// Actions
		
		// Nav Tab Wrapper
		
		// Click Nav Tab
		$( '.' + classes.App.Tabs.Nav + ' a', $container ).on( 'click', function( e ) {
			e.stopPropagation();

			var wrapper = $( this ).closest( '.' + classes.App.Tabs.Wrapper );
			
			if (wrapper.hasClass( classes.Open ))
				wrapper.removeClass( classes.Open );
		});
		
		
		// Click Nav Tab Wrapper
		$( '.' + classes.App.Tabs.Wrapper, $container ).on( 'click', function( e ) {

			if ( ! $( this ).hasClass( classes.Open ) )
				$( this ).addClass( classes.Open );
		});
		
		
		
		// Filters
		$( '#' + ids.Filters.Remaining, $container ).on( event.Change, function( e ) {
			
			if ( $( this ).prop( property.Checked ) == true )
				$app.addClass( classes.Filters.Remaining );
			else
				$app.removeClass( classes.Filters.Remaining );
		});
		
		

		// Screen Switcher
		$( 'a', $list ).on( event.Click, function() {
			
			$( "a", $list ).removeClass( classes.Active );
			
			$( this ).addClass( classes.Active );
			
			swiper.slideTo( $( this ).data( 'id' ) );
		});
	};
	

	
	
	
	
	
	
	
	
	
	/////////////////////////////////////////////////////////////////////////
	// SOURCE
	
	// Prints by Source first
	var showBySource = function( data ) { 
		
		// Variables
		var 
			tabsWrapper = '',
			navTabs = '',
			tabs = ''
		;
		
		// If no Craftable Sources, abort showing by Source
		if ( ! craftable_sources.length )
			return;
		
		// For each Craftable Source
		for ( var l = 0; l < craftable_sources.length; l++ ) {
			
			// Get this Source's Categories
			var 
				sourceCategories = craftable_categories.filter( function( obj ) { return obj.Source == craftable_sources[ l ].ID; } ),
				sourceContent = ''
			;
			
			// If no Source Categories, skip giving this Source a tab
			if ( ! sourceCategories.length )
				continue; 
			
			// For each of this Source's Categories 
			for ( var c = 0; c < sourceCategories.length; c++ ) { 
				
				// Get this Category's Groups
				var 
					categoryGroups = craftable_groups.filter( function( obj ) { return obj.Category == sourceCategories[ c ].ID; } ),
					categoryContent = ''
				;

				// If no Category Groups, skip this Category
				if ( ! categoryGroups.length ) 
					continue;
				
				
				// Begin generating this Category's Group content
				categoryContent += '<ul class="' + classes.Category.Groups + ' ' + classes.CheckboxList + ' ' + classes.App.CheckboxList + '">';

				// For each of this Category's Groups
				for ( var g = 0; g < categoryGroups.length; g++ ) {
					
					// Get this Group's Craftables
					var 
						groupCraftables = craftables.filter( function( obj ) { return obj.Group == categoryGroups[ g ].ID; } ),
						groupsContent = ''
					;
					
					// If no Group Craftables, skip this Group
					if ( ! groupCraftables.length ) 
						continue;
					
					// Skip same-named Category Groups 
					if ( categoryGroups[ g ].Name !== sourceCategories[ c ].Name )
						groupsContent += '<ul>';
					
					// For each of this Group's Craftables
					for ( var i = 0; i < groupCraftables.length; i++ ) {
						
						var
							// Get this Craftable's Collectables 
							craftableCollectables = craftable_collectables.filter( function( obj ) { return obj.Craftable == groupCraftables[ i ].ID; }),
							
							totalCollectable = 0,
							totalCollected = 0,
							totalSubmitted = 0,
							totalRemaining = false,
							
							craftableContent = ''
						;

						// If no Craftable Collectables, skip this Craftable 
						if ( ! craftableCollectables.length ) 
							continue;
						
						// Begin generating this Craftable's contents
						craftableContent += '<ul class="' + classes.CheckboxList + ' ' + classes.App.CheckboxList + '">';
						
						// For each of this Craftable's Collectables
						for ( var s = 0; s < craftableCollectables.length; s++ ) { 
							
							var 
								// Get Collectable info
								collectable = collectables.filter( function( obj ) { return obj.ID == craftableCollectables[ s ].Collectable; } )[ 0 ],
								
								// Get Ingredient
								ingredient = ingredients.filter( function( obj ) { return obj.ID == collectable.Ingredient; } )[ 0 ],
								
								// Get Quality
								quality = ingredient_qualities.filter( function( obj ) { return obj.ID == collectable.Quality; } )[ 0 ],
								
								// Get Part
								part = ingredient_parts.filter( function( obj ) { return obj.ID == collectable.Part; } )[ 0 ],
								
								// Get Quantity
								quantity = craftableCollectables[ s ].Quantity,
								
								// Get Collected Collectables
								collectableCollected = player_game_collected.filter( function( obj ) { return obj.Collectable == craftableCollectables[ s ].Collectable; } )[ 0 ],
								
								// Get Collected Quantity 
								quantityCollected = ( typeof collectableCollected !== 'undefined' ) ? collectableCollected.Collected : 0,
								
								// Get Submitted Collectables
								collectableSubmitted = player_game_submitted.filter( function( obj ) { return obj.ItemCollectable == craftableCollectables[ s ].ID; } )[ 0 ],
								
								// Get Submitted Quantity
								quantitySubmitted = ( typeof collectableSubmitted !== 'undefined' ) ? collectableSubmitted.Quantity : 0,
								
								collectableContent = ''
							;
							
							// Increment Total Collectable
							totalCollectable = totalCollectable + parseInt( quantity );
							
							// Increment Total Collected
							totalCollected = totalCollected + parseInt( quantityCollected );
							
							// Increment Total Submitted
							totalSubmitted = totalSubmitted + parseInt( quantitySubmitted );
							
							// For each Item of Collectable required
							for ( var q = 0; q < quantity; q++ ) {
								var 
									// Determine if Item is Collected
									isCollected = ( q < quantityCollected ),
									
									// If Collected set Available 
									availableVal = ( isCollected ) ? classes.Available : '',
									
									// Determine if Item is submitted
									isSubmitted = ( q < quantitySubmitted ),
 									
 									// If Submitted set Checked
									checkedVal = ( isSubmitted ) ? property.Checked : '',
									
									// If Submmitted but not Collected set Disabled
									disabledVal = ( isSubmitted && ! isCollected ) ? classes.Disabled : ''
								;
								
								// Add a Checkbox for this Collectable Item
								collectableContent += '<label class="' + classes.DeadCheckbox + ' ' + classes.Collectable.Item.Checkbox + ' ' + availableVal + disabledVal + '"> <input type="checkbox" name="' + name.Collectable + '" class="' + classes.Collectable.Item.Input + '" value="' + craftableCollectables[ s ].ID + '" data-craftable="' + groupCraftables[ i ].ID + '" data-collectable="' + collectable.ID + '" ' + checkedVal + '> </label>';
							}
							
							// Add Ingredient and include its Collectable content to this Craftable content
							craftableContent += '<li><label class="' + classes.Ingredient.Label + '"><span class="' + classes.Name.Quality + '">' + quality.Name + '</span> <span class="' + classes.Name.Ingredient + '">' + ingredient.Name + '</span> <span class="' + classes.Name.Part + '">' + part.Name + '</span> x <span class="' + classes.Name.Quantity + '">' + craftableCollectables[ s ].Quantity + '</span></label> ' + collectableContent + '</li>';
						}
						
						craftableContent += '</ul>';
						
						var
							// Get Player Game Craftables  
							craftedCraftable = player_game_craftables.filter( function( obj ) { return obj.Craftable == groupCraftables[i].ID; } )[ 0 ], 
							
							// Calculate Total Remaining to Submit
							totalRemaining = totalCollectable - totalSubmitted,
							
							// Determine if Submittable
							isSubmittable = ( totalSubmitted < totalCollectable ), 
							
							// Determine if Craftable
							isCraftable = !isSubmittable,
							
							// Determine if Crafted 
							isCrafted = ( typeof craftedCraftable !== 'undefined' && craftedCraftable.Acquired == 1 ),
							
							// If Submittable set Disabled 
							abled = ( isSubmittable ) ? property.Disabled : '',
							
							// If Submittable set Open 
							opened = ( isSubmittable ) ? property.Open : '',
							
							// If Craftable set Available 
							available = ( isCraftable ) ? classes.Available : '',
							
							// If Crafted set Checked 
							checkedVal = ( isCrafted ) ? property.Checked : '',
							
							// If None Remaining set Disabled 
							groupClasses = ( totalRemaining == 0 ) ? classes.Disabled : '' 
						;
						
						// Add Craftable content to this Group
						groupsContent += '<li><details class="' + groupClasses + ' " ' + opened + '><summary class="' + abled + '"><label class="' + classes.DeadCheckbox + ' ' + classes.Category.Group.Checkbox + ' ' + abled + ' ' + available + '"> <span class="' + classes.Dashicon + ' ' + classes.Dashicons.Yes + '"></span> <input type="checkbox" class="' + classes.Group.Craftable.Input + '" name="' + name.Craftables + '" value="' + groupCraftables[ i ].ID + '" ' + abled + ' ' + checkedVal + ' > </label> <label class="' + classes.Group.Craftable.Label + ' item-label"> <span class="' + classes.Group.Craftable.Name + '">' + groupCraftables[ i ].Name + '</span></label></summary> ' + craftableContent + '</details> </li>';
					}
					
					// Skip same-named Category Groups 
					if ( categoryGroups[ g ].Name !== sourceCategories[ c ].Name ) {
						
						// End Groups content
						groupsContent += '</ul>';
												
						// Add Category Group Name, Checkbox and Details/Summary to Category content
						categoryContent += '<li><details class="' + classes.Category.Group.Container + '"><summary><label> <span class="' + classes.DeadCheckbox + ' disabled"> <span class="dashicons dashicons-yes"></span> <input type="checkbox" class="collectable-group" name="group" value="' + categoryGroups[g].ID + '" ' + abled + ' > </span> </label> <span class="collection-group-name">' + categoryGroups[ g ].Name + '</span> </summary>';
					}
					
					// Add Category Group Content to Category Content
					categoryContent += groupsContent;
					
					// Skip same-named Category Groups 
					if ( categoryGroups[ g ].Name !== sourceCategories[ c ].Name )
						
						// End Category Group content
						categoryContent += '</details></li>';
				}
				
				// Close this Category's content
				categoryContent += '</ul>';
				
				// Add Category content to Source content
				sourceContent += '<details class="' + classes.Source.Category.Container + '"><summary><span class="' + classes.Source.Category.Name + ' ' + classes.Source.Category.Name + '">' + sourceCategories[ c ].Name + '</span></summary> ' + categoryContent + '</details>';
			}
			
			// Add this Source to tabs
			navTabs += '<li class="' + classes.App.Tabs.Nav + '"><a href="#source-' + craftable_sources[ l ].Name + '" class="" data-sourceid="' + craftable_sources[ l ].ID + '" data-tabid="' + l + '"> <i class="' + classes.Source.Icons[ craftable_sources[ l ].ID ] + '"></i> ' + craftable_sources[ l ].Name + '</a></li>';
			
			// Add this Source content to tabs content
			tabs += '<div class="' + classes.Source.Tab + ' ' + classes.Tab.Lucky + '" id="' + ids.Source.Name( craftable_sources[ l ].Name ) + '"><h3 class="' + classes.ScreenReader + ' source-name">' + craftable_sources[ l ].Name + '</h3><div class="' + classes.Tab.Content + ' source-content">' + sourceContent + '</div></div>';					
		}
		
		// Add Tabs and Tab Nav to Tab wrapper
		tabsWrapper += '<div id="' + ids.Sources.Craftable + '" class="' + classes.App.Section + ' ' + classes.Swiper.Slide + '">' + html.Sources.Heading + '<div id="source-tabs" class="tabs-container luckytabs toptabs"><ul class="pill source-selector ' + classes.App.Tabs.Wrapper + '">' + navTabs + '</ul>' + tabs + '</div></div>';	 				

		// Add Tab wrapper to Sections
		$sections.append( tabsWrapper );
		
		// IDENTIFY Source Tabs
		$div = $( '#source-tabs' );
		
		// Attach event listeners to Source Tabs
		_setupSourceActions( data , $div ); 
		
	};



	/////////////////////////////////////////////////////////////////////////
	// SOURCE Behaviours

	var _setupSourceActions = function( data , $div ) {
		
		// Checkboxes
		$( '.' + classes.DeadCheckbox, $div ).each( function() { 
			// Initially mark as checked if input is checked
			if ( $( 'input', this ).prop( 'checked' ) ) 
				$( this ).addClass( 'checked' ); 
		} );
		
		
		// CLICK - Item Collectable Checkbox
		$( '.item-collectable-checkbox', $div ).on( 'click', function( e ) {
			// Don't do anything by default
			e.preventDefault();
			
			// Variables
			var 
				// Identify the Input inside This to handle
				$input = $( 'input', $( this ) ),
				checked = $input.prop( 'checked' ),
				$all = $( this ).parent().children(),
				$siblings = $(this).siblings(),
				$sold = $( 'input:checked', $(this).parent() ).parent(),
				$notSold = $( 'input', $all ).not( ':checked' ).parent(),
				$available = $( '.available', $(this).parent() ), 
				$availableAny = $( this ).siblings( '.available' ), 
				$availableAnyNotSold = $( 'input', $availableAny).not( ':checked' ).parent(),
				$availableNotSold = $( 'input', $available ).not( ':checked' ).parent(),
				$notAvailableNotSold = $notSold.not( '.available' ),
				availableAny = ( $availableAnyNotSold.length > 0 ),
				availableThis = $( this ).hasClass( 'available' ),
				collectable = $input.val()
			;
			
			// "Logic"
			
			// Is checked...
			if ( checked == true ) {
				// Uncheck the last checked sibling (might be self)
				unCheck( $( 'input', $sold.last() ) );
				
				// And we're done
				return;
			}
			
			// Is not checked...
			
			// Is available though...
			if ( availableThis ) {

				// Check the first non-checked available sibling (might be self)
				check( $('input', $availableNotSold.first() ) );
				
				// And we're done
				return;
			}
			
			
			// Not available...
			
			// But any available...
			if ( availableAny ) {
				
				// Check first any available and not sold
				check( $( 'input', $availableAnyNotSold.first() ) );
				
				// And we're done
				return;
			}
			
			// Or if none available... 
			
			// Prompt user to confirm collection and sale 
			if ( ! confirm( 'This item has not been collected. Do you want to mark as collected and sell?' ) ) 
				return;
				
			// TODO: Mark collectable as collected before selling 
			// Involves interacting with the Ingredients View
			

			// Check first not sold (might be self)
			check( $('input', $notSold.first() ) );
			
			// And we're really done
		});
		
		
		
		// CHANGE - Item Collectable Checkbox
		$( 'input', $( '.item-collectable-checkbox', $div ) ).on( 'change', function( e ) { 
			e.preventDefault();
			
			// Activate Updating Flag
			activateUpdatingFlag();
			
			clicked = {
				itemcollectable: $( this ).val()
			};
			
			data = $.extend( data, clicked );
			
			// Send data for update
			_updateItemCollectable( data ) ;
			
			var 
				$checkboxList = $( this ).parentsUntil( '.app-checkbox-list' ).parent(), 
				$collectableItem = $checkboxList.parentsUntil( 'details' ).parent(), 
				$collectableItemSummary = $( 'summary', $collectableItem ), 
				$collectableItemCheckbox = $( 'label', $collectableItemSummary ), 
				$collectableItemCheckboxInput = $( 'input', $collectableItemCheckbox ), 
				unchecked = $( 'input', $checkboxList ).not( ':checked' ).length
			;
			
			
			if ( $( this ).prop( property.Checked ) ) { 
				$( this ).parent().addClass( classes.Checked );
				
				if ( unchecked )
					return;
				
				// Make the parent Collectable Item "craftable"						
				$collectableItemSummary
					.removeClass( classes.Disabled );
				
				$collectableItemCheckbox
					.removeClass( classes.Disabled )
					.addClass( classes.Available );
					
				$collectableItemCheckboxInput
					.prop( property.Disabled, '');
				
				return;
			}
			
			$( this ).parent().removeClass( classes.Checked );
			
			if ( unchecked > 1 )
				return;
			
			// Make the parent Collectable Item "un-craftable"						
			$collectableItemSummary
				.addClass( classes.Disabled );
			
			$collectableItemCheckbox
				.addClass( classes.Disabled )
				.removeClass( classes.Available );
				
			$collectableItemCheckboxInput
				.prop( property.Disabled, property.Disabled);
			
		});
		
		
		
		// CLICK - Collectable Item Label
		$( '.collectable-item-label', $div ).on( 'click', function(e) {
			e.preventDefault();
			
			$(this).parent()
				.click();
		});
		
		
		
		// CLICK - Collectable Item Checkbox
		$( '.collectable-item-checkbox', $div ).on( 'click', function( e ) { 
			
			// Don't do anything by default
			e.preventDefault();
			
			// Variables
			var 
				$input = $( 'input', $( this ) ),
				checked = $input.prop( 'checked' ),
				availableThis = $( this ).hasClass( classes.Available )
			;
			
			// "Logic"
			
			
			// Is checked...
			if ( checked == true ) {
				// Uncheck the input
				unCheck( $input );
				
				// And we're done
				return;
			}
			
			
			// Is not checked...
			
			// Is not available
			if ( ! availableThis ) {
				$( this ).parent()
					.click();
				
				// And we're done
				return;
			}
			
			
			// Is available though...
			
			// Prompt user to confirm purchase of item 
			//if ( !confirm( 'Are you sure you wish to purchase this item?' ) ) 
			//	return;

			// Check 
			check( $input );
			
			
			// And we're really done
		});
		
		
		
		$( 'input', $( '.collectable-item-checkbox', $div ) ).on( 'change', function( e ) { 
			e.preventDefault();
			
			var checked = $( this ).prop( 'checked' );
			
			if ( checked ) 
				$( this ).parent().addClass( 'checked' );
			else
				$( this ).parent().removeClass( 'checked' );
		
		
			activateUpdatingFlag();
			
			//log(player_game_craftables, false);
		
			
			clicked = {
				item: $( this ).val(),
				acquired : ( (checked == true) ? 1 : 0 ),
			};
			
			data = $.extend( data, clicked );
			
			updateItem( data ) ;
			
		});
		
		
		// Setup Tabs
		setupTabs( $div );

	};

















	/////////////////////////////////////////////////////////////////////////
	// SHOW BY INGREDIENT TYPE

	// Prints out list of Collectables by Ingredient type
	
	var showByIngredientType = function( data ) {
		
		// Get Ingredient Types
		var 
			ingredientTypes = ingredient_types,
			typeIcons = {
				1 : 'fas fa-hippo',
				2 : 'fas fa-crow',
				3 : 'fas fa-fish',
				4 : 'far fa-gem',
				5 : 'far fa-snowflake',
			},
			heading = '<h3 class="' + classes.ScreenReader + '">Collectables</h3>',
			
			tabsWrapper = '',
			navTabs = '',
			tabs = '',
			 
			remaining = []
		;
		
		// Abort if no Ingredient Types found
		if ( ! ingredientTypes.length )
			return;
		
		
		// Ingredient Types
		for ( var t = 0; t < ingredientTypes.length; t++ ) {
			
			// Get Ingredients for this Type
			var 
				typeIngredients = ingredients.filter( function( obj ) { return obj.Type == ingredientTypes[ t ].ID; } ),
				typesContent = ''
			;
			
			// If no Ingredients, skip this Ingredient Type
			if ( ! typeIngredients.length ) 
				continue; 
			
			// For each Ingredient of this Ingredient Type
			for ( var n = 0; n < typeIngredients.length; n++ ) {
				
				// Get Collectables matching this Ingredient
				var 
					ingredientCollectables = collectables.filter( function( obj ) { return obj.Ingredient == typeIngredients[ n ].ID; } ),
					totalCollectable = 0,
					totalCollected = 0,
					totalSubmitted = 0
					
					ingredientsContent = '',
					collectablesContent = '',
				;
				
				// If no Collectables, skip this Ingredient
				if ( ! ingredientCollectables.length ) 
					continue; 
				
				// For each Collectable requiring this Ingredient
				for ( var c = 0; c < ingredientCollectables.length; c++ ) {
					
					var 
						// Get Ingredient name
						ingredient = ingredients.filter( function( obj ) { return obj.ID == ingredientCollectables[ c ].Ingredient; } )[ 0 ],
						
						// Get Part info
						part = ingredient_parts.filter( function( obj ) { return obj.ID == ingredientCollectables[ c ].Part; } )[ 0 ],
						
						// Get Quality info
						quality = ingredient_qualities.filter( function( obj ) { return obj.ID == ingredientCollectables[ c ].Quality; })[ 0 ],
						
						// Get Craftables matching this Collectable
						collectableCraftables = craftable_collectables.filter( function(obj) { return obj.Collectable == ingredientCollectables[ c ].ID; } ),
						
						// Get Collectable Quantity from Craftables
						quantity = ( ingredientCollectables[ c ].Quality == 2 ) ? 1 : sumColumn( collectableCraftables, 'Quantity' ),
						
						// Get Collected Collectables
						collectableCollected = player_game_collected.filter( function( obj ) { return obj.Collectable == ingredientCollectables[ c ].ID; } )[0],
						
						// Get Quantity of Collected 
						quantityCollected = ( typeof collectableCollected !== 'undefined' ) ? collectableCollected.Collected : 0
					;
					
					// Increment Total Collectable
					totalCollectable = totalCollectable + parseInt( quantity );
					
					// Increment Total Collected
					totalCollected = totalCollected + parseInt( quantityCollected );
					
					// Increment Total Remaining
					totalRemaining = totalCollectable - totalCollected;
					
					var remainingObject = { 'Collectable' : ingredientCollectables[ c ].ID, 'Remaining' : totalRemaining };
					
					remaining.push( remainingObject ); 
					
					
					// Generate Collectables Content for this Ingredient
					
					collectablesContent += '<ul class="collectable-list ' + classes.CheckboxList + ' ' + classes.App.CheckboxList + '">';
					
					collectablesContent += '<li><label><span class="quantity">' + quantity + '</span> x <span class="quality-name">' + quality.Name + '</span> <span class="' + classes.Name.Ingredient + '">' + ingredient.Name + '</span> <span class="part-name">' + part.Name + '</span></label>';
				
					// For each Item of Collectable
					for ( var q = 0; q < quantity; q++ ) {
						var checkedVal = ( q < quantityCollected ) ? 'checked ' : '';	
						
						// Add checkbox for this Item to Collectables content
						collectablesContent += '<label class="' classes.DeadCheckbox + ' ' + classes.Collectable.Item.Checkbox + '"> <span class="dashicons dashicons-yes"></span> <input type="checkbox" name="collectables" class="' + classes.Collectable.Input + '" value="' + ingredientCollectables[ c ].ID + '" ' + checkedVal + ' > </label>';
					}
					
					collectablesContent += '</li>';
					
					collectablesContent += '</ul>';
				}
				
				var ingredientClasses = ( totalRemaining == 0 ) ? 'disabled ' : '';
				
				ingredientsContent += '<details class="ingredient ' + ingredientClasses + '" data-ingredient="' + typeIngredients[ n ].ID + '" data-remaining="' + totalRemaining + '"><summary><span class="' + classes.Name.Ingredient + '">' + ingredient.Name + '</span> <span class="remaining-amount">' + totalRemaining + '</span></summary> ' + collectablesContent + '</details>';
				
				typesContent += ingredientsContent;
			}
			
			// Add this Ingredient Type to Tab Nav
			navTabs += '<li class="' + classes.App.Tabs.Nav + '"><a href="#type-' + ingredient_types[t].Plural + '" class="" data-typeid="' + ingredient_types[ t ].ID + '" data-tabid="' + t + '"><i class="' + typeIcons[ ingredient_types[ t ].ID ] + '"></i>' + ingredient_types[ t ].Plural + '</a></li>';
			
			// Add this Ingredient Type to Tab Content
			tabs += '<div class="ingredient-type ingredient-type-tab luckytab" id="type-' + ingredient_types[ t ].Plural + '"><h3 class="' + classes.ScreenReader + '">' + ingredient_types[ t ].Plural + '</h3><div class="' + classes.Tab.Content + '">' + typesContent + '</div></div>';
		}
		
		// Add all tabs to Tabs wrapper
		var tabsWrapper = '<div id="ingredients" class="' + classes.App.Section + ' ' + classes.Swiper.Slide + '">' + heading + '<div id="collectable-tabs" class="tabs-container luckytabs toptabs"><ul class="pill source-selector ' + classes.App.Tabs.Wrapper + '">' + navTabs + '</ul>' + tabs + '</div></div>';	 				
		
		// APPEND all content to Sections
		$sections.append( tabsWrapper );
		
		//log(remaining, false);
		
		//log(player_game_collected, false);
		
		// IDENTIFY Ingredient Tabs
		$div = $( '#collectable-tabs' );								
		
		// Setup Ingredient Actions
		_setupIngredientActions( data , $div );
				

	};
	
	
	
	
	
	/////////////////////////////////////////////////////////////////////////
	// Setup Ingredient Actions
	
	var _setupIngredientActions = function( data , $div ) { 
		
		
		// Checkboxes
		
		$( '.' + classes.DeadCheckbox, $div ).each( function() { 
			if ( $( 'input', this ).prop( 'checked' ) ) 
				$( this ).addClass( 'checked' ); 
			
			
			// CLICK - Checkbox
			
			$( this ).on( 'click', function( e ) {
				e.preventDefault();
				
				var 
					input = $( 'input', $( this ) ), 
					collectable = '', 
					checked = ( input.prop( 'checked' ) == true ), 
					collectable = input.val(), 
					$source = $( '#source-tabs' ), 
					$prevCollectable = false 
				;
				
				// Ingredient quantity clicked is currently checked
				
				if ( checked == true ) { 
					
					$( 'input:checked', $( this ).parent() ).last() 
						.prop( 'checked', '' ) 
						.trigger( 'change' ); 
					
					if ( ! $source.length )
						return;
					
					$collectable = $( 'input.itemcollectable[value=' + collectable + ']:not(:checked)', $source );
					
					//log($collectable, false);
					
					if ( ! $collectable )
						return;
						
					$collectable.last().parent().removeClass( 'available' );
									
				} 
				
				
				// Ingredient not currently checked
				
				else {
					
					var next = $( 'input', $(this).parent() ).not( ':checked' ).first(); 
					
					if ( input == next )
						input
							.prop( 'checked', 'checked' )
							.trigger( 'change' );
					else 
						next
							.prop( 'checked', 'checked' )
							.trigger( 'change' );
					
					if ( ! $source.length )
						return;
					
					$collectable = $( 'input.itemcollectable[value=' + collectable + ']', $source );
					
					if ( ! $collectable )
						return;
						
					//log($collectable, false);
					
					var curItem = false;
					
					$collectable.each(function() {
						if ( $( this ).parent().hasClass( 'available' ) )
							return;
								
						var item = $( this ).data( 'item' );
						
						if ( item == curItem ) 
							return;
																
						$( this ).parent()
							.addClass( 'available' );
												
						curItem = item;
					});							
				}
				
				
				// Get updated number of remaining
				var remainingIngredients = getRemaining( $( this ) ); 
				
				//log(remainingIngredients);
				
				updateRemaining( $( this ), remainingIngredients );
				
			});
		});
		
		
		// CHANGE - Input
		
		$( 'input', $( '.' + classes.DeadCheckbox, $div ) ).on( 'change', function( e ) { 
			if ( $( this ).prop( 'checked' ) ) 
				$( this ).parent().addClass( 'checked' );
			else
				$( this ).parent().removeClass( 'checked' );
		});

		
		
		// CHANGE - Collectable 
		
		$( '.' + classes.Collectable.Input, $div ).on( 'change', function() {
			
			activateUpdatingFlag();
			
			clicked = {
				collectable: $( this ).val()
			};
			
			data = $.extend( data, clicked );
			
			updateCollectable( data ) ;
		});
		
		
				
		// Setup Tabs
		setupTabs( $div );
		
	};
	
	
	
	
	
	
	
	
	
	
	/////////////////////////////////////////////////////////////////////////
	// UPDATE Remaining
	
	var updateRemaining = function( $el, remaining ) {
		if ( ! $el || remaining == null ) {
			error( 'No element or remaining' );
			return;
		}
		
		var 
			$details = $el.closest( 'details' ),
			$remainingAmountSpan = $( '.remaining-amount', $details )
		;
		
		if ( ! $remainingAmountSpan.length ) {
			error( 'Cannot find .remaining-amount span!' );
			return;
		}
		
		
		if ( ! remaining || remaining < 1 ) 
			$details
				.addClass( 'disabled' );
		else
			$details
				.removeClass( 'disabled' );
		
		//log(remainingAmountSpan, false);
		
		// Update Remaining amount
		$remainingAmountSpan.html( remaining );
	};
	
	
	
	// Get Remaining
	
	var getRemaining = function( $el ) {
		if ( ! $el ) 
			return;
			
		var $details = $el.closest( 'details' );
			
		return $( 'input', $details ).not( ':checked' ).length;
	};



	// Get Distinct
	
	var getDistinct = function( array, key, value ) {
		if ( ! array || ! key || ! value )
			return false;
		
		var 
			result = [],
			map = new Map()
		;
		
		for ( var i = 0; i < array.length; i++ ) {
		    if ( map.has( array[ i ].key ) ) 
		    	continue;
		    	
	        map.set( array[ i ].key, true );
	        
	        result.push({
	            ID: array[ i ].key,
	            Ingredient: array[ i ].value
	        });
		    
		}
		
		return ( result ) ? result : false;
	};
	
	
	
	// Check checkbox
	
	var check = function( element ) {
		element
			.prop( 'checked', 'checked' )
			.trigger( 'change' );
	};
	
	
	
	// Uncheck checkbox
	
	var unCheck = function( element ) {
		element
			.prop( 'checked', '' )
			.trigger( 'change' );
	};
	
	
	
	// Sum Column
	
	var sumColumn = function sumColumn( array, col ) {
	    var sum = 0;
	    
	    array.forEach( function ( value, index, array ) {
	        sum += parseInt( value[ col ] );
	    });
	    
	    return sum;
	};
	
	
	
	// Read Query String	
	
	var query = function( query, variable ) {
		var vars = query.split( '&' );
		
		for ( var i = 0; i < vars.length; i++ ) {
		    var pair = vars[ i ].split( '=' );
		    
		    if ( pair[ 0 ] == variable )
		        return pair[ 1 ];
		}
		
		return false;
	};
	
    
    
    
    
    /////////////////////////////////////////////////////////////////////////
    // UPDATE PLUGIN
	// This is where we send the latest player game form data to the database
    plugin.update = function( data ) {
		updateItem( data ) ;
		return;
	};
	



	// Update Item

	var updateItem = function ( data ) { 
		if ( typeof data.item == 'undefined' || ! data.item ) {
			deactivateUpdatingFlag();
			return false;
		}
		
		log( player_game_craftables, false );
		
		var 
			item = data.item,
			game_id = $( '#game_id' ).val(), 
			itemCurrent = ( player_game_craftables.length ) ? player_game_craftables.filter( function( obj ) { return obj.Item == item; } ) : false,
			ajaxActions = { update: '_ajax_update_player_game_item', insert: '_ajax_insert_player_game_item' }
		;
		
		if ( typeof state.Interval.Update[ item ] !== 'undefined' && state.Interval.Update[ item ] !== false ) 
			clearInterval( state.Interval.Update[ item ] ); 
		
		// If not found in previously collected item list
		// Insert this item into the player game item table
		var action = 'insert';
			
		// Otherwise
		// Update this item in the player game item table
		if ( itemCurrent.length ) 
			var action = 'update';
		
		extra = {
			_ajax_items_nonce: $( '#_ajax_update_items_nonce' ).val(), 
			action: ajaxActions[ action ], 
			game_id: game_id, 
		};
			
		data = $.extend( data, extra );
		
		state.Interval.Update[ item ] = setTimeout( function() {
			log( ( action == 'update' ) ? 'Updating...' : 'Inserting...' );
			
			$.ajax({
	        	
				url: ajaxurl,
				type: 'POST',
				data: data,
	            
				success: function( response ) {
					deactivateUpdatingFlag();
					log( response, false );
					
					log( ( action == 'update' ) ? 'Updated!' : 'Inserted!' );
					
					var updatedLocally = false;
					
					$.each( player_game_craftables, function() { 
						if ( this.Item === data.item ) {
							this.Acquired == data.acquired;
							updatedLocally = true;
						}
					});

					if ( updatedLocally == false && player_game_craftables !== false )
						player_game_craftables.push( {
							Item: data.item,
							Acquired: data.acquired
						} );
	            },
				
				error: function( response ) {
					deactivateUpdatingFlag();
					error( 'Error ' + ( ( action == 'update' ) ? 'Updating...' : 'Inserting...' ) );
					error( response, false );
				}
				
			});
			
		}, plugin.settings.delay );
	};









	/////////////////////////////////////////////////////////////////////////
	// Update Item Collectable
	
	var _updateItemCollectable = function ( data ) { 
		if ( typeof data.itemcollectable == 'undefined' || ! data.itemcollectable ) {
			deactivateUpdatingFlag();
			return false;
		}
		
		var 
			itemcollectable = data.itemcollectable, 
			checked = $( 'input.itemcollectable[value=' + itemcollectable + ']:checked' ), 
			quantity = checked.length, 
			game_id = $( '#game_id' ).val(), 
			submittedCurrent = player_game_submitted.filter( function(obj) { return obj.ItemCollectable == itemcollectable; } )[ 0 ],
			ajaxActions = { update: '_ajax_update_player_game_item_collectable', insert: '_ajax_insert_player_game_item_collectable' } 
		;
		
		if ( typeof state.Interval.Update[ itemcollectable ] !== 'undefined' && state.Interval.Update[ itemcollectable ] !== ( false || null ) ) 
			clearInterval( state.Interval.Update[ itemcollectable ] ); 
		
		// If not found in previously collected list
		// Insert this collectable into the player game item collected table
		if ( ! submittedCurrent ) 
			var action = 'insert';
			
		// Otherwise
		// Update this collectable in the player game item collected table
		else {
			var action = 'update';
			
			log( submittedCurrent, false );
			
			// Find out if this is actually an update
			if ( submittedCurrent.Quantity == quantity ) {
				
				log( 'No change to Item Collectable! Cancelling update.' );
				
				deactivateUpdatingFlag();
				
				return false;
			}
		}

		var data = { 
			_ajax_collectables_nonce: $( '#_ajax_collectables_nonce' ).val(), 
			action: ajaxActions[ action ], 
			itemcollectable: itemcollectable, 
			quantity: quantity, 
			game_id: game_id, 
		};
		
		//log(data, false);				
		
		state.Interval.Update[ itemcollectable ] = setTimeout( function() {
			log( ( action == 'update' ) ? 'Updating...' : 'Inserting...' );

			$.ajax({
	        	
				url: ajaxurl,
				type: 'POST',
				data: data,
	            
				success: function( response ) { 
					//log( response , false );
					var updatedLocally = false;
					var itemcollectable = data.itemcollectable;
					
					$.each( player_game_submitted, function() { 
						if ( this.ItemCollectable == itemcollectable ) { 
							this.Quantity = data.quantity;
							updatedLocally = true;
						}
					});

					if ( updatedLocally == false )
						player_game_submitted.push({
							Collectable: itemcollectable,
							Quantity: data.quantity
						});
					
					deactivateUpdatingFlag();
					
					log( 'Item Collectable ' + itemcollectable + ' ' + ( ( action == 'update' ) ? 'updated!' : 'inserted!' ) );
	            },
				
				error: function( response ) {
					deactivateUpdatingFlag();
					error( 'Error ' + ( ( action == 'update' ) ? 'updating' : 'inserting' ) + ' Item Collectable ' + itemcollectable + '!' );
					error( response, false );
				}
			});
			
		}, plugin.settings.delay );
	};











	/////////////////////////////////////////////////////////////////////////
	// Update Collectable

	var updateCollectable = function ( data ) { 
		if ( typeof data.collectable == 'undefined' || ! data.collectable ) {
			deactivateUpdatingFlag();
			return false;
		}
		
		var 
			collectable = data.collectable, 
			checked = $( '.collectable[value=' + collectable + ']:checked' ), 
			quantity = checked.length, 
			game_id = $( '#game_id' ).val(), 
			collectableCurrent = player_game_collected.filter( function( obj ) { return obj.Collectable == collectable; } ),
			ajaxActions = { update: '_ajax_update_player_game_collectable', insert: '_ajax_insert_player_game_collectable' }
		;
		
		if ( typeof state.Interval.Update[ collectable ] !== 'undefined' && state.Interval.Update[ collectable ] !== false ) 
			clearInterval( state.Interval.Update[ collectable ] ); 
		
		// If not found in previously collected list
		// Insert this collectable into the player game collected table
		if ( ! collectableCurrent.length ) 
			var action = 'insert';
			
		// Otherwise
		// Update this collectable in the player game collected table
		else {
			var action = 'update';
			
			// Find out if this is actually an update
			if ( collectableCurrent[ 0 ].Collected == quantity ) {
				log( 'No change! Cancelling update.' );
				deactivateUpdatingFlag();
				return false;
			}
		}

		var data = { 
			_ajax_collectables_nonce: $( '#_ajax_collectables_nonce' ).val(), 
			action: ajaxActions[ action ], 
			collectable: collectable, 
			quantity: quantity, 
			game_id: game_id, 
		};
		
		//log(data, false);				
		
		state.Interval.Update[ collectable ] = setTimeout( function() {
			log( ( action == 'update') ? 'Updating...' : 'Inserting...' );

			$.ajax( {
	        	
				url: ajaxurl,
				type: 'POST',
				data: data,
	            
				success: function( response ) { 
					//log( response , false );
					var updatedLocally = false;
					
					$.each( player_game_collected, function() { 
						if ( this.Collectable == data.collectable ) { 
							this.Collected = data.quantity;
							updatedLocally = true;
						}
					});

					if ( updatedLocally == false )
						player_game_collected.push( {
							Collectable: data.collectable,
							Collected: data.quantity
						} );
					
					deactivateUpdatingFlag();
					
					log( ( action == 'update' ) ? 'Updated!' : 'Inserted!' );
	            },
				
				error: function( response ) {
					deactivateUpdatingFlag();
					error( 'Error ' + ( ( action == 'update' ) ? 'updating!' : 'inserting!' ) );
					error( response, false );
				}
			});
			
		}, plugin.settings.delay );
	};
	
	
	
	
	
	
	
	
	// Toggle Updating Flag
	
	var toggleUpdatingFlag = function() {
		$UpdatingFlag.toggleClass( 'top' );
		$UpdatingFlag.toggleClass( 'show' );
	};
	
	
	
	// Activate Updating Flag
	
	var activateUpdatingFlag = function() {
		$UpdatingFlag.addClass( 'top' );
		$UpdatingFlag.addClass( 'show' );
	};
	
	
	
	// Deactivate Updating Flag
	
	var deactivateUpdatingFlag = function() {
		$UpdatingFlag.removeClass( 'show' );
		
		setTimeout( function() {
			$UpdatingFlag.removeClass( 'top' );
		}, 300 );
	};



	// Setup Tabs
	
	var setupTabs = function( tabElement ) {
		if ( ! tabElement.length ) 
			return;
			
		tabElement.easytabs( { 
			animate: false, 
			tabActiveClass: classes.App.Tabs.Active, 
			updateHash: false 
		} );
		
		/*
		$( window ).on( 'hashchange', function() {
			hashslice = location.hash.slice( 1 );
			
			if ( hashslice.length > 0 && $( '#' + hashslice, tabElement ).length)
				tabElement.easytabs( 'select', hashslice );
			//else
			//	tabElement.easytabs( 'select', $( 'li:first a', tabElement ).attr( 'href' ) );
		});
		*/
	};
	
	
	
	
	// Setup Details/Summary elements
	
	var setupDetailSummary = function() {
		if ( ! $( 'details' ).length )
			return false;
		 	
		$( 'details' ).details();
		
		// Conditionally add a classname to the `<html>` element, based on native support
		$( 'html' ).addClass( $.fn.details.support ? 'details' : 'no-details' );
		
		$( document ).on( 'keyup click', 'summary', function( e ) {
			$( window ).trigger( 'resize' ).trigger( 'scroll' );
		});
	};
	
	
	// Get Fields
	var getFields = function( input, field ) {
	    var output = [];
	    
	    for ( var i = 0; i < input.length; i++ )
	        output.push( input[ i ][ field ] );
	        
	    return output;
	};
	
	
	
	// Postpone
	
	var postpone = function( func ) {
		if ( typeof func !== 'function' )
			return false;
		
		window.setTimeout( func, 0 );
	};



	// Log
	
	var log = function( text, showPrefix ) {
		if ( logging !== true )
			return;
		
		var message = ( showPrefix == false ) ? text : logPrefix + ': ' + text;
			
		console.log( message );
	};
	
	
	
	// Error
	
	var error = function( text, showPrefix ) {
		if ( logging !== true )
			return;

		var message = ( showPrefix == false ) ? text : logPrefix + ': ' + text;

		console.error( message );
	};
	
	
		
	
	// Public Methods
    plugin.publicMethods = {

    };

	
	
	// Initiate Plugin
	plugin.init();

	
	
	
	
	
	
	
	/////////////////////////////////////////////////////////////////////////
	// Tracky jQuery Function
	
	$.fn.tracky = function( options ) {
		var args = arguments;

		return this.each(function() {
			var $this = $(this),
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





})( jQuery );
