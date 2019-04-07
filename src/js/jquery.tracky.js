

// RDR2 PROGRESS TRACKER 

// jQuery Plugin


( function( $ ) {

	$.tracky = function( container, options ) {
	
		var 
			// Handles
			plugin = this,
			$container = $( container ),
			$app = false,
			
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
				
				EasyTabs : {
					animate: false, 
					get tabActiveClass() { return classes.App.Tabs.Active; }, 	
					updateHash: false,
				},
			},
			
	
			// Data Variables
			
			
			//////////////////
			// Craftables Data
			
			// Craftables
			craftables = JSON.parse( database.craftables ),
			// Craftable Sources
			craftable_sources = JSON.parse( database.craftable_sources ),
			// Craftable Categories 
			craftable_categories = JSON.parse( database.craftable_categories ),
			// Craftable Groups 
			craftable_groups = JSON.parse( database.craftable_groups ),
			// Craftable Collectables 
			craftable_collectables = JSON.parse( database.craftable_collectables ), 
			
			
			//////////////////
			// Collectables Data
			
			// Collectables
			collectables = JSON.parse( database.collectables ), 
			
			
			//////////////////
			// Ingredients Data
			
			// Ingredients 
			ingredients = JSON.parse( database.ingredients ),
			// Ingredient Types 
			ingredient_types = JSON.parse( database.ingredient_types ),
			// Ingredient Qualities 
			ingredient_qualities = JSON.parse( database.ingredient_qualities ), 
			// Ingredient Parts
			ingredient_parts = JSON.parse( database.ingredient_parts ), 
			
			
			//////////////////
			// Player Game Data
			
			// Craftables
			player_game_crafted = JSON.parse( database.player_game_crafted ),
			// Collected 
			player_game_collected = JSON.parse( database.player_game_collected ),
			// Submitted 
			player_game_submitted = JSON.parse( database.player_game_submitted ), 
			
			// Player Info
			player_info = JSON.parse( database.player_info ), 
			
			// Actions
			
			actions = {
				Insert : {
					Collected : '_ajax_insert_player_game_collected',
					Submitted : '_ajax_insert_player_game_submitted',
					Crafted : '_ajax_insert_player_game_crafted',
				},
				Update : {
					Collected : '_ajax_update_player_game_collected',
					Submitted : '_ajax_update_player_game_submitted',
					Crafted : '_ajax_update_player_game_crafted',
					
					Filter : '_ajax_update_player_filter',
				},
			},
			
			// Content
			content = {
				Screens : [
					{ name: 'Source' },
					{ name: 'IngredientType' },
				],
				Label : {
					Inserted : 'Inserted!',
					Inserting : 'Inserting...',
					
					Updated : 'Updated!',
					Updating : 'Updating...',
				},
			},
			
			// Classes
			classes = {				
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
					Checkbox : {
						Container : 'app-checkbox',
						List : 'app-checkbox-list',
					},
				},
				Category : {
					Name : 'category-name',
					Groups : 'category-groups',
					Group : {
						Container : 'category-group',
						Input : 'category-group-input',
						Checkbox : 'category-group-checkbox',
						Name : 'collection-group-name',
					},
				},
				Checkbox : {
					List : 'checkbox-list',
				},
				Collectable : {
					Input : 'collectable-input',
					Item : {
						Container : 'collectable-item',
						Input : 'collectable-item-input',
						Checkbox : 'collectable-item-checkbox',
					},
					List : {
						Container : 'collectable-list',
					},
				},
				Craftable : {
					Collectable : {
						Container : 'craftable-collectable',
						Input : 'craftable-collectable-input',
						Checkbox : 'craftable-collectable-checkbox',
					},
				},
				Dashicon : 'dashicons',
				Dashicons : {
					Yes : 'dashicons-yes',
				},
				DeadCheckbox : 'DeadCheckbox',
				
				Filters : {
					Remaining : 'filter-remaining',
				}, 
				Flag : { 
					Active : 'active', 
					Available : 'available', 
					Checked : 'checked', 
					Disabled : 'disabled', 
					Show : 'show', 
					Top : 'top', 
					Updating : 'updating-flag',
				}, 
				Group : { 
					Craftable : { 
						Container : 'group-craftable',
						Checkbox : 'group-craftable-checkbox', 
						Input : 'group-craftable-input', 
						Label : 'group-craftable-label', 
						Name : 'group-craftable-name', 
					}, 
					Craftables : { 
						Input : 'group-craftables-input', 
					},  
				},  
				Ingredient : { 
					Container : 'ingredient', 
					Label : 'ingredient-label', 
					Type : { 
						Selector : 'ingredient-type-selector', 
						Tab : { 
							Content : 'ingredient-type-tab-content', 
							Nav : 'ingredient-type-tab-nav', 
						}, 
					}, 
				}, 
				Input : {
					Craftable : 'craftable-input',
				},
				Item : {
					Label : 'item-label',
				},
				Label : {
					Updating : 'updating-label',
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
					Remaining : 'remaining-amount',
				},
				Open : 'open',
				ScreenReader : 'screen-reader-text',
				Source : {
					Content : 'source-content',
					Icons : {
						1 : 'fas fa-campground',
						2 : 'fas fa-dollar-sign',
						3 : 'far fa-envelope',
						4 : 'fas fa-paw',
					},
					Tab : 'source-tab',
					Category : {
						Container : 'source-category',
						Name : 'source-category-name',
					},
					Selector : 'source-selector',
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
				Tabs : {
					Container : 'tabs-container',
					Lucky : 'luckytabs',
					Top : 'toptabs',
				},
				
				
			},
			
			
			// Elements 
			elements = {
				Flag : { 
					get Updating() { return $( '#' + ids.Flag.Updating, $container ); },
				}
			},
			
			// Events
			events = {
				Click : 'click',
				Change : 'change',
				Resize : 'resize',
				OrientationChange : 'orientationchange',
				Blur : 'blur',
				Focus : 'focus',
				Key : {
					Up : 'keyup',
				},
			},		
			
			// HTML
			html = {
				App : {
					get Container() { return '<div class="' + classes.App.Container + '" id="' + ids.App + '"></div>'; },
					List : '<div class="' + classes.App.Sections.List + '"><ul class="' + classes.List.Horizontal + ' ' + classes.List.Pill + '"><li><a data-id="0" class="' + classes.Flag.Active + '">Sources</a></li><li><a data-id="1">Collectables</li></ul></div>',
					get Filters() { return '<div class="' + classes.App.Sections.Filter + '"><ul class="' + classes.List.Horizontal + ' ' + classes.List.Pill + '"><li><label class=""><input type="checkbox" name="' + name.Filter + '" value="1" id="' + ids.Filters.Remaining + '" ' + (( player_info.FilterRemaining == 1 ) ? 'checked' : '') + ' > Show remaining only </label></li></ul></div>'; },
					Sections : '<div class="' + classes.App.Sections.Container + '"><div class="' + classes.App.Sections.Wrapper + ' swiper-wrapper"></div></div>',
				},
				get Dashicon() { return ( ! state.Dashicon || ! classes.Dashicons[ state.Dashicon ] ) ? false : '<span class="' + classes.Dashicon + ' ' + classes.Dashicons[ state.Dashicon ] + '"></span>'; },
				Flag : {
					get Updating() { return '<div class="' + classes.Flag.Updating + '" id="' + ids.Flag.Updating + '"> <span class="' + classes.Label.Updating + '"> ' + content.Label.Updating + ' </span> </div>'; },
				},
				Icon : {
					get Yes() { 
						state.Dashicon = 'Yes';
						return html.Dashicon; 
					},
				},
				Sources : {
					Heading : '<h3 class="' + classes.ScreenReader + '">Sources</h3>',
				},	
			},
			
			// IDs
			ids = {
				App : 'App',
				Collectable : {
					Tabs : 'CollectableTabs',
				},
				Collected : {
					Nonce : '_ajax_collected_nonce',
				},
				Craftable : {
					Nonce : '_ajax_craftables_nonce',
				},
				Filters : {
					Remaining : 'FilterRemaining',
					Nonce : '_ajax_player_filters_nonce',
				},
				Flag : {
					Updating : 'UpdatingFlag',
				},
				Game : 'game_id',
				Ingredient : {
					Types : 'IngredientTypes',
				},
				Player : 'player_id',
				Source : {
					Tabs : 'SourceTabs',
					Name : 'Source-', 
				},
				Sources : {
					Craftable : 'CraftableSources',	
				},
				Submitted : {
					Nonce : '_ajax_submitted_nonce',
				},
				Type : {
					Tabs : 'TypeTabs',
					Name : 'Type-',	
				},
				
			},
			
			
			// Names (Inputs)
			name = {
				Collectable : 'collectableitems',
				Craftables : 'craftableitems',
				Filter : 'filter',
				Group : 'groupitems',
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
				Player : {
					Filter : {
						Remaining : false,
					},
				},
			}
		;
	
	
	
	
	
	
	
		/////////////////////////////////////////////////////////////////////////
		// Initiate Plugin
		
		plugin.init = function( ) {
	 		
	 		// Combine Defaults with passed Options into plugin var settings
			plugin.settings = $.extend( {}, defaults, options );
			
			$container.data( slug, {} );
	
			var data = {
				game_id: $( '#' + ids.Game ).val(),
			};
			
			
			// APPEND Updating Flag
			
			// APPEND Updating Flag for all screens
			$container.append( html.Flag.Updating );
			
			
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
			swiper = new Swiper( '.' + classes.Swiper.Container, $.extend( {
					on: {
						slideChange: function () {
							$( 'a:eq(' + swiper.activeIndex + ')', $list ).click();
						},
					},
				
				}, plugin.settings.Swiper, {} )
			);
			
			
			// Actions
			
			// Nav Tab Wrapper
			
			// Click Nav Tab
			$( '.' + classes.App.Tabs.Nav + ' a', $container ).on( events.Click, function( e ) {
				e.stopPropagation();
	
				var wrapper = $( this ).closest( '.' + classes.App.Tabs.Wrapper );
				
				if ( wrapper.hasClass( classes.Open ) )
					wrapper.removeClass( classes.Open );
			});
			
			
			// Click Nav Tab Wrapper
			$( '.' + classes.App.Tabs.Wrapper, $container ).on( events.Click, function( e ) {
	
				if ( ! $( this ).hasClass( classes.Open ) )
					$( this ).addClass( classes.Open );
			});
			
			
			
			
			// Filters
			
			// Add Filter Remaining class to App container if it is initially checked
			if ( $( '#' + ids.Filters.Remaining, $container ).prop( property.Checked ) )
				$app.addClass( classes.Filters.Remaining );
			
			
			// Track changes to Remaining Filter
			$( '#' + ids.Filters.Remaining, $container ).on( events.Change, function( e ) {
				var filter = null;
				
				// Activate Updating Flag
				_activateUpdatingFlag();
				
				if ( $( this ).prop( property.Checked ) == true ) {
					$app.addClass( classes.Filters.Remaining );
					filter = 1;
				}
				
				else {
					$app.removeClass( classes.Filters.Remaining );
					filter = 0;
				}
				
				// UPDATE Local Player Info data
				player_info.FilterRemaining = filter;
								
				// Add filter value to AJAX data
				data = $.extend( data, { filter : filter } );
				
				// UPDATE Filter setting to Player database record
				_updateFilter( data );
			});
			
			
			
			
			// Screen Switcher
			
			$( 'a', $list ).on( events.Click, function() {
				
				$( "a", $list ).removeClass( classes.Flag.Active );
				
				$( this ).addClass( classes.Flag.Active );
				
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
					categoryContent += '<ul class="' + classes.Category.Groups + ' ' + classes.Checkbox.List + ' ' + classes.App.Checkbox.List + '">';
	
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
								
								// Total Collectable starts at 0
								totalCollectable = 0,
								// Total Collected starts at 0
								totalCollected = 0,
								// Total Submitted starts at 0
								totalSubmitted = 0,
								// Total Remaining starts at false
								totalRemaining = false,
								
								// Start Craftable Content 
								craftableContent = ''
							;
	
							// If no Craftable Collectables, skip this Craftable 
							if ( ! craftableCollectables.length ) 
								continue;
							
							// Begin generating this Craftable's contents
							craftableContent += '<ul class="' + classes.Checkbox.List + ' ' + classes.App.Checkbox.List + '">';
							
							
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
									collectableSubmitted = player_game_submitted.filter( function( obj ) { return obj.CraftableCollectable == craftableCollectables[ s ].ID; } )[ 0 ],
									
									// Get Submitted Quantity
									quantitySubmitted = ( typeof collectableSubmitted !== 'undefined' ) ? collectableSubmitted.Quantity : 0,
									
									
									// Start Collectable Content
									collectableContent = ''
								;
								
								//log( "Collectable Submitted: ");
								//log( collectableSubmitted, false );
								
								// Increment Total Collectable
								totalCollectable = totalCollectable + parseInt( quantity );
								
								// Increment Total Collected
								totalCollected = totalCollected + parseInt( quantityCollected );
								
								// Increment Total Submitted
								totalSubmitted = totalSubmitted + parseInt( quantitySubmitted );
								
								
								// For each Craftable Collectable required
								for ( var q = 0; q < quantity; q++ ) {
									
									var 
										// Determine if Item is Collected
										isCollected = ( q < quantityCollected ), 
										
										// If Collected set Available 
										availableVal = ( isCollected ) ? classes.Flag.Available : '',
										
										// Determine if Item is submitted
										isSubmitted = ( q < quantitySubmitted ),
	 									
	 									// If Submitted set Input Checked
										checkedVal = ( isSubmitted ) ? property.Checked : '',
										
										// If Submitted set Checkbox Label submitted
										checkedClass = ( isSubmitted ) ? classes.Flag.Checked : '',
										
										// If Submmitted but not Collected set Disabled
										disabledVal = ( isSubmitted && ! isCollected ) ? classes.Flag.Disabled : ''
									;
									
									//log( "Is submitted: ", false);
									//log( isSubmitted, false );
									
									// Add a Checkbox for this Collectable Item
									collectableContent += '<label class="' + classes.DeadCheckbox + ' ' + classes.Craftable.Collectable.Checkbox + ' ' + availableVal + ' ' + disabledVal + '" > <input type="checkbox" name="' + name.Collectable + '" class="' + classes.Craftable.Collectable.Input + '" value="' + craftableCollectables[ s ].ID + '" data-craftable="' + groupCraftables[ i ].ID + '" data-collectable="' + collectable.ID + '" ' + checkedVal + ' > </label>';
								}
								
								// Add Ingredient and include its Collectable content to this Craftable content
								craftableContent += '<li> <label class="' + classes.Ingredient.Label + '"> <span class="' + classes.Name.Quality + '">' + quality.Name + '</span> <span class="' + classes.Name.Ingredient + '">' + ingredient.Name + '</span> <span class="' + classes.Name.Part + '">' + part.Name + '</span> x <span class="' + classes.Name.Quantity + '">' + craftableCollectables[ s ].Quantity + '</span> </label> ' + collectableContent + ' </li>';
							}
							
							craftableContent += '</ul>';
							
							var
								// Get Player Game Craftables  
								craftedCraftable = player_game_crafted.filter( function( obj ) { return obj.Craftable == groupCraftables[ i ].ID; } )[ 0 ], 
								
								// Calculate Total Remaining to Submit
								totalRemaining = totalCollectable - totalSubmitted,
								
								// Determine if Submittable
								isSubmittable = ( totalSubmitted < totalCollectable ), 
								
								// Determine if Craftable
								isCraftable = ! isSubmittable,
								
								// Determine if Crafted 
								isCrafted = ( typeof craftedCraftable !== 'undefined' && craftedCraftable.Acquired == 1 ),
								
								// If Submittable set Disabled 
								abled = ( isSubmittable ) ? property.Disabled : '',
								
								// If Submittable set Open 
								opened = ( isSubmittable ) ? property.Open : '',
								
								// If Craftable set Available 
								available = ( isCraftable ) ? classes.Flag.Available : '',
								
								// If Crafted set Checked 
								checkedVal = ( isCrafted ) ? property.Checked : '',
								
								// If None Remaining set Disabled 
								craftableClass = ( totalRemaining == 0 ) ? classes.Flag.Disabled : '',
								
								// Checked Class
								checkedClass = ( isCrafted ) ? classes.Flag.Checked : ''
							;
							
							// Add this Craftable to the Groups content
							groupsContent += '<li> <details class="' + classes.Group.Craftable.Container + ' ' + craftableClass + '" ' + opened + ' > <summary class="' + abled + '"> <label class="' + classes.DeadCheckbox + ' ' + classes.Group.Craftable.Checkbox + ' ' + abled + ' ' + available + ' ' + checkedClass + '"> ' + html.Icon.Yes + ' <input type="checkbox" class="' + classes.Group.Craftable.Input + '" name="' + name.Craftables + '" value="' + groupCraftables[ i ].ID + '" ' + abled + ' ' + checkedVal + ' > </label> <label class="' + classes.Group.Craftable.Label + ' ' + classes.Item.Label + '"> <span class="' + classes.Group.Craftable.Name + '">' + groupCraftables[ i ].Name + '</span> </label> </summary> ' + craftableContent + ' </details> </li>';
						}
						
						
						// Skip same-named Category Groups 
						if ( categoryGroups[ g ].Name !== sourceCategories[ c ].Name ) {
							
							// End Groups content
							groupsContent += '</ul>';
													
							// Add Category Group Name, Checkbox and Details/Summary to Category content
							categoryContent += '<li><details class="' + classes.Category.Group.Container + '"> <summary> <label class="' + classes.DeadCheckbox + ' ' + classes.Category.Group.Checkbox + ' ' + classes.Flag.Disabled + '"> ' + html.Icon.Yes + ' <input type="checkbox" class="' + classes.Category.Group.Input + '" name="' + name.Group + '" value="' + categoryGroups[g].ID + '" ' + abled + ' > </label> <span class="' + classes.Category.Group.Name + '">' + categoryGroups[ g ].Name + '</span> </summary>';
						}
						
						// Add Category Group Content to Category Content 
						categoryContent += groupsContent;
						
						// Skip same-named Category Groups 
						if ( categoryGroups[ g ].Name !== sourceCategories[ c ].Name )
							
							// End Category Group content
							categoryContent += '</details> </li>';
					}
					
					// Close this Category's content
					categoryContent += '</ul>'; 
					
					// Add Category content to Source content 
					sourceContent += '<details class="' + classes.Source.Category.Container + '"> <summary> <span class="' + classes.Source.Category.Name + ' ' + classes.Source.Category.Name + '">' + sourceCategories[ c ].Name + '</span> </summary> ' + categoryContent + ' </details>';
				}
				
				// Add this Source to tabs
				navTabs += '<li class="' + classes.App.Tabs.Nav + '"> <a href="#' + ids.Source.Name + craftable_sources[ l ].Name + '" class="" data-sourceid="' + craftable_sources[ l ].ID + '" data-tabid="' + l + '"> <i class="' + classes.Source.Icons[ craftable_sources[ l ].ID ] + '"> </i> ' + craftable_sources[ l ].Name + '</a> </li>';
				
				// Add this Source content to tabs content
				tabs += '<div class="' + classes.Source.Tab + ' ' + classes.Tab.Lucky + '" id="' + ids.Source.Name + craftable_sources[ l ].Name + '"> <h3 class="' + classes.ScreenReader + ' ' + classes.Source.Name + '">' + craftable_sources[ l ].Name + '</h3> <div class="' + classes.Tab.Content + ' ' + classes.Source.Content + '" > ' + sourceContent + ' </div> </div>';					
			}
			
			// Add Tabs and Tab Nav to Tab wrapper
			tabsWrapper += '<div id="' + ids.Sources.Craftable + '" class="' + classes.App.Section + ' ' + classes.Swiper.Slide + '" > ' + html.Sources.Heading + ' <div id="' + ids.Source.Tabs + '" class="' + classes.Tabs.Container + ' ' + classes.Tabs.Lucky + ' ' + classes.Tabs.Top + '"> <ul class="' + classes.Source.Selector + ' ' + classes.List.Pill + ' ' + classes.App.Tabs.Wrapper + '">' + navTabs + '</ul>' + tabs + '</div> </div>';	 				
	
			// Add Tab wrapper to Sections
			$sections.append( tabsWrapper );
			
			// IDENTIFY Source Tabs
			$div = $( '#' + ids.Source.Tabs );




			
			// Attach event listeners to Source Tabs
			_setupSourceActions( data , $div ); 
			
			

			
		};
	
	
	
	
		/////////////////////////////////////////////////////////////////////////
		// SOURCE Actions
	
		var _setupSourceActions = function( data , $div ) {
			
			
			// For every Checkbox...
			$( '.' + classes.DeadCheckbox, $div ).each( function() { 
				
				// Initially mark as checked if input is checked
				if ( $( 'input', this ).prop( property.Checked ) ) 
					$( this ).addClass( property.Checked ); 
					
			} );
			
			
			
			// CLICK - Collectable Item Checkbox
			$( '.' + classes.Collectable.Item.Checkbox, $div ).on( events.Click, function( e ) {
				
				// Don't do anything by default
				e.preventDefault();
				
				// Variables
				var 
					// Identify the Input inside This to handle
					$input = $( 'input', $( this ) ),
					
					// Get Checked value
					checked = $input.prop( property.Checked ),
					
					// All including self and siblings
					$all = $( this ).parent().children(),
					
					// All siblings
					$siblings = $(this).siblings(),
					
					// All sold
					$sold = $( 'input:' + property.Checked, $( this ).parent() ).parent(),
					
					// All not Sold including self and siblings
					$notSold = $( 'input', $all ).not( ':' + property.Checked ).parent(),
					
					// Available all including self and siblings
					$available = $( '.' + classes.Flag.Available, $( this ).parent() ),
					
					// Are any OTHERS Available? 
					$availableAny = $( this ).siblings( '.' + classes.Flag.Available ),
					
					// Are any Available siblings not Sold?
					$availableAnyNotSold = $( 'input', $availableAny ).not( ':' + property.Checked ).parent(),
					
					// Available but not Sold
					$availableNotSold = $( 'input', $available ).not( ':' + property.Checked ).parent(),
					
					// Not Available and not Sold
					$notAvailableNotSold = $notSold.not( '.' + classes.Flag.Available ),
					
					// Is THIS Collectable Item Available?
					availableThis = $( this ).hasClass( classes.Flag.Available ),
					
					// Are ANY Collectable Items available?
					availableAny = ( $availableAnyNotSold.length > 0 ),
					
					// Collectable ID is the Input value
					collectable = $input.val()
				;
				
				// Input clicked logic
				
				// Input is checked...
				if ( checked == true ) {
					
					// Uncheck the last checked sibling (might be self)
					unCheck( $( 'input', $sold.last() ) );
					
					// And we're done
					return;
				}
				
				
				// Input is not Checked...
				
				// Input is available though...
				if ( availableThis ) {
	
					// Check the first non-checked available sibling (might be self)
					check( $('input', $availableNotSold.first() ) );
					
					// And we're done
					return;
				}
				
				
				// This input is not available...
				
				// But some other inputs are available...
				if ( availableAny ) {
					
					// Check first any available and not sold
					check( $( 'input', $availableAnyNotSold.first() ) );
					
					// And we're done
					return;
				}
				
				
				// Or if no inputs are available... 
				
				// Prompt user to confirm collection and sale 
				
				if ( ! confirm( 'This item has not been collected. Do you want to mark as collected and sell?' ) ) 
					return;
					
				
				// TODO: Mark collectable as collected before selling 
				// Involves interacting with the Ingredients View
				
	
	
				// Check first not sold (might be self)
				check( $('input', $notSold.first() ) );
				
				
				// And now we're really done
			});
			
			
			
			
			// CHANGE - Craftable Collectable Input
			
			$( '.' + classes.Craftable.Collectable.Input, $div ).on( events.Change, function( e ) { 
				
				e.preventDefault();
				
				// Activate Updating Flag
				_activateUpdatingFlag();
				
				// Register Collectable Item as click
				clicked = {
					collectable: $( this ).val() 
				};
				
				// Add clicked registry to data
				data = $.extend( data, clicked );
				
				// Submit Craftable Collectable
				_submitCollectable( data ) ;
				
				
				
				// Siblings 
				
				var 
					// Identify the parent list
					$checkboxList = $( this ).parentsUntil( '.' + classes.App.Checkbox.List ).parent(),
					
					// Identify the Collectable Item 
					$collectableItem = $checkboxList.parentsUntil( 'details' ).parent(), 
					
					// Identify the Collectable Item Summary
					$collectableItemSummary = $( 'summary', $collectableItem ),
					
					// Identify the Collectable Item Checkbox 
					$collectableItemCheckbox = $( 'label', $collectableItemSummary ),
					
					// Identify the Collectable Item Input 
					$collectableItemCheckboxInput = $( 'input', $collectableItemCheckbox ),
					
					// Identify unchecked inputs 
					unchecked = $( 'input', $checkboxList ).not( ':' + property.Checked ).length
				;
				
				
				// If this is Checked
				if ( $( this ).prop( property.Checked ) ) {
					
					// Add Checked class to parent label 
					$( this ).parent().addClass( classes.Flag.Checked );
					
					// ABORT if there are still unchecked siblings...
					if ( unchecked )
						return;
					
					// Make the parent Collectable Item "craftable" 
					$collectableItemSummary
						.removeClass( classes.Flag.Disabled );
					
					// Make Checkbox "craftable"
					$collectableItemCheckbox
						.removeClass( classes.Flag.Disabled )
						.addClass( classes.Flag.Available );
						
					// Make Input "abled"
					$collectableItemCheckboxInput
						.prop( property.Disabled, '');
					
					// And we're done...
					return;
				}
				
				
				// If this is NOT Checked
				
				// Remove Checked class from parent label 
				$( this ).parent().removeClass( classes.Flag.Checked );
				
				// ABORT if there are other unchecked...
				if ( unchecked > 1 )
					return;
				
				// Make the parent Collectable Item "un-craftable"						
				$collectableItemSummary
					.addClass( classes.Flag.Disabled );
				
				// Make the Checkbox "un-craftable"
				$collectableItemCheckbox
					.addClass( classes.Flag.Disabled )
					.removeClass( classes.Flag.Available );
				
				// Make the Input disabled
				$collectableItemCheckboxInput
					.prop( property.Disabled, property.Disabled);
				
				
				// And we're really done
			});
			
			
			
			// CLICK - Category Group Item Checkbox
			$( '.' + classes.Category.Group.Checkbox, $div ).on( events.Click, function(e) {
				
				// Prevent any default actions
				e.preventDefault();
				
				// Trigger click on parent
				$(this).parent()
					.click();
			});
			
			
			
			// CLICK - Group Craftable Item Checkbox
			$( '.' + classes.Group.Craftable.Checkbox, $div ).on( events.Click, function( e ) { 
				
				// Don't do anything by default
				e.preventDefault();
				
				log( "Clicked a Group Craftable!" );
				
				// Variables
				var 
					// Identify child Input
					$input = $( 'input', $( this ) ),
					
					// Get checked value
					checked = $input.prop( property.Checked ),
					
					// Get availability
					availableThis = $( this ).hasClass( classes.Flag.Available )
				;
				
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
					
					// Click the parent label
					$( this ).parent()
						.click();
					
					// And we're done
					return;
				}
				
				
				// Is available though...
				
				// Prompt user to confirm purchase of item 
				//if ( !confirm( 'Are you sure you wish to purchase this item?' ) ) 
				//	return;
	
				// Check this
				check( $input );
				
				
				// And now we're really done
			});
			
			
			
			// CHANGE - Group Craftable Input
			$( '.' + classes.Group.Craftable.Input, $div ).on( events.Change, function( e ) { 
				
				// Prevent any default actions
				e.preventDefault();
				
				log( "Group Craftable changed!" );
				
				// Get checked property
				var checked = $( this ).prop( property.Checked );
				
				// If this is checked
				if ( checked ) 
					// Add checked flag to parent
					$( this ).parent().addClass( classes.Flag.Checked );
					
				// Otherwise
				else
					// Remove checked flag from parent
					$( this ).parent().removeClass( classes.Flag.Checked );
			
				
				// ACTIVATE Updating Flag
				_activateUpdatingFlag();
				
				//log(player_game_crafted, false);
			
				// Register clicked
				clicked = {
					craftable: $( this ).val(),
					acquired : ( ( checked == true ) ? 1 : 0 ),
				};
				
				// Add clicked registry to data
				data = $.extend( data, clicked );
				
				// UPDATE Craftable with clicked data
				_updateCraftable( data ) ;
				
			});
	
			
						
			// Setup Tabs
			setupTabs( $div );

	
		};
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
		/////////////////////////////////////////////////////////////////////////
		// SHOW BY INGREDIENT TYPE
		// Prints out list of Collectables by Ingredient type
		
		var showByIngredientType = function( data ) {
	
			var 
				// Get Ingredient Types
				ingredientTypes = ingredient_types,
				
				// Ingredient Type Icons
				typeIcons = {
					1 : 'fas fa-hippo',
					2 : 'fas fa-crow',
					3 : 'fas fa-fish',
					4 : 'far fa-gem',
					5 : 'far fa-snowflake',
				},
				
				// Ingredient Types Heading 
				heading = '<h3 class="' + classes.ScreenReader + '">Collectables</h3>',
				
				// Open Tabs wrapper
				tabsWrapper = '',
				
				// Open Tab Nav
				navTabs = '',
				
				// Open Tabs
				tabs = '',
				
				// Remaining count
				remaining = []
			;
			
			// Abort if no Ingredient Types found
			if ( ! ingredientTypes.length ) 
				return;
			
			
			// Ingredient Types
			for ( var t = 0; t < ingredientTypes.length; t++ ) {
				
				var
					// Get Ingredients for this Type 
					typeIngredients = ingredients.filter( function( obj ) { return obj.Type == ingredientTypes[ t ].ID; } ),
					
					// Open Types content
					typesContent = ''
				;
				
				// If no Ingredients for this Ingredient Type, skip this Ingredient Type
				if ( ! typeIngredients.length ) 
					continue; 
				
				// For each Ingredient of this Ingredient Type
				for ( var n = 0; n < typeIngredients.length; n++ ) {
					
					var
						// Get Collectables matching this Ingredient 
						ingredientCollectables = collectables.filter( function( obj ) { return obj.Ingredient == typeIngredients[ n ].ID; } ),
						
						// Total Collectable count starts at zero 
						totalCollectable = 0,
						
						// Total Submittable count starts at zero 
						totalCollected = 0,
						
						// Total Submitted count starts at zero 
						totalSubmitted = 0,
						
						// Open Ingredients content
						ingredientsContent = '',
						
						// Opent Collectables Content
						collectablesContent = ''
					;
					
					// If no Collectables for this Ingredient, skip this Ingredient
					if ( ! ingredientCollectables.length ) 
						continue; 
					
					// For each Collectable including this Ingredient
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
						
						// Register Remaining info for this Collectable as object
						var remainingObject = { 
							'Collectable' : ingredientCollectables[ c ].ID, 
							'Remaining' : totalRemaining 
						};
						
						// Add Remaining info for this Collectable to All Remaining
						remaining.push( remainingObject ); 
						
						
						// Generate Collectables Content for this Ingredient
						
						// Open Ingredient Collectable checkbox list
						collectablesContent += '<ul class="' + classes.Collectable.List.Container + ' ' + classes.Checkbox.List + ' ' + classes.App.Checkbox.List + '">';
						
						// Ingredient info
						collectablesContent += '<li class="' + classes.Ingredient.Container + '"> <label class="' + classes.Ingredient.Label + '" > <span class="' + classes.Name.Quantity + '">' + quantity + '</span> x <span class="' + classes.Name.quality + '">' + quality.Name + '</span> <span class="' + classes.Name.Ingredient + '">' + ingredient.Name + '</span> <span class="' + classes.Name.Part + '">' + part.Name + '</span> </label>';
					
						// For each Item of Collectable
						for ( var q = 0; q < quantity; q++ ) {
							
							// Is Checked if we still haven't gone past quantity collected
							var checkedVal = ( q < quantityCollected ) ? property.Checked : '';	
							
							// Add checkbox for this Item to Collectables content
							collectablesContent += '<label class="' + classes.DeadCheckbox + ' ' + classes.Collectable.Item.Checkbox + '"> ' + html.Icon.Yes + ' <input type="checkbox" name="' + name.Collectable + '" class="' + classes.Collectable.Input + '" value="' + ingredientCollectables[ c ].ID + '" ' + checkedVal + ' > </label>';
						}
						
						// End Collectables content for this Ingredient
						collectablesContent += '</li>';
						collectablesContent += '</ul>';
					}
					
					// Flag Ingredient as disabled if Total Remaining is zero 
					var ingredientClasses = ( totalRemaining == 0 ) ? classes.Flag.Disabled : '';
					
					// Add Collectables Content to Ingredient content
					ingredientsContent += '<details class="' + classes.Ingredient.Container + ' ' + ingredientClasses + ' " data-ingredient="' + typeIngredients[ n ].ID + '" data-remaining="' + totalRemaining + '"> <summary> <span class="' + classes.Name.Ingredient + '">' + ingredient.Name + '</span> <span class="' + classes.Name.Remaining + '">' + totalRemaining + '</span> </summary> ' + collectablesContent + ' </details>';
					
					// Add Ingredient content to Types content
					typesContent += ingredientsContent;
				}
				
				// Add this Ingredient Type to Tab Nav
				navTabs += '<li class="' + classes.Ingredient.Type.Tab.Nav + ' ' + classes.App.Tabs.Nav + '"><a href="#' + ids.Type.Name + ingredient_types[ t ].Plural + '" class="" data-typeid="' + ingredient_types[ t ].ID + '" data-tabid="' + t + '"> <i class="' + typeIcons[ ingredient_types[ t ].ID ] + '"></i> ' + ingredient_types[ t ].Plural + '</a> </li>';
				
				// Add this Ingredient Type to Tab content
				tabs += '<div class="' + classes.Ingredient.Type.Tab.Content + ' ' + classes.Tab.Lucky + '" id="' + ids.Type.Name + ingredient_types[ t ].Plural + '"><h3 class="' + classes.Ingredient.Type.Name + ' ' + classes.ScreenReader + '">' + ingredient_types[ t ].Plural + '</h3> <div class="' + classes.Tab.Content + '">' + typesContent + '</div> </div>';
			}
			
			// Add all Tabs content to Tabs wrapper
			var tabsWrapper = '<div id="' + ids.Ingredient.Types + '" class="' + classes.App.Section + ' ' + classes.Swiper.Slide + '">' + heading + '<div id="' + ids.Collectable.Tabs + '" class="' + classes.Tabs.Container + ' ' + classes.Tabs.Lucky + ' ' + classes.Tabs.Top + '"><ul class="' + classes.Source.Selector + ' ' + classes.List.Pill + ' ' + classes.App.Tabs.Wrapper + '">' + navTabs + '</ul>' + tabs + '</div></div>';	 				
			
			// APPEND all content to Sections
			$sections.append( tabsWrapper );
			
			
			//log(remaining, false);
			
			//log(player_game_collected, false);
			
			
			// IDENTIFY Ingredient Tabs
			$div = $( '#' + ids.Collectable.Tabs ); 
			
			
			// Setup Ingredient Actions
			_setupIngredientActions( data, $div );
					
	
		};
		
		
		
		
		
		/////////////////////////////////////////////////////////////////////////
		// Setup Ingredient Actions
		
		var _setupIngredientActions = function( data, $div ) { 
			
			
			// SETUP - Checkboxes
			$( '.' + classes.DeadCheckbox, $div ).each( function() {
				 
				// If the Input is checked
				if ( $( 'input', $( this ) ).prop( property.Checked ) ) 
					
					// Add Checked class
					$( this ).addClass( classes.Flag.Checked ); 
				
				
				// CLICK - Checkbox
				$( this ).on( events.Click, function( e ) {
					
					log( "Clicked checkbox!");
					
					// Prevent any default action
					e.preventDefault();
					
					var 
						// Identify child Input to variable
						input = $( 'input', $( this ) ), 
						
						// Open Collectable
						collectable = '', 
						
						// Get checked value
						checked = ( input.prop( property.Checked ) == true ),
						
						// Get Collectable ID 
						collectable = input.val(),
						
						// Identify Source Tabs 
						$source = $( '#' + ids.Source.Tabs ),
						
						// Previous Collectable starts as false 
						$prevCollectable = false 
					;
					
					// Ingredient quantity clicked is currently checked
					if ( checked == true ) { 
						
						// UNCHECK last checked siblings (may be self)
						$( 'input:' + property.Checked, $( this ).parent() ).last() 
							.prop( property.Checked, '' ) 
							.trigger( events.Change ); 
						
						// ABORT if no Source found
						if ( ! $source.length )
							return;
						
						// Identify Collectable's unchecked Items
						$collectable = $( classes.Collectable.Input + '[value=' + collectable + ']:not(:' + property.Checked + ')', $source );
						
						//log($collectable, false);
						
						// ABORT if there are no unchecked collectable items
						if ( ! $collectable )
							return;
						
						// Otherwise remove the Available flag from the last unchecked sibling
						$collectable.last().parent().removeClass( classes.Flag.Available );
										
					} 
					
					
					// Ingredient not currently checked
					else {
						log ( "Ingredient not currently checked..." );
						
						// Identify Next Input that is not checked
						var next = $( 'input', $(this).parent() ).not( ':' + property.Checked ).first(); 
						
						// If this element is the same as next
						if ( input == next )
							// Check this element
							input
								.prop( property.Checked, property.Checked )
								.trigger( events.Change );
								
						// Otherwise							
						else
							// Check the next element 
							next
								.prop( property.Checked, property.Checked )
								.trigger( events.Change );
						
						// ABORT if no Source is found
						if ( ! $source.length ) {
							
							error( "No source found!" );
							
							return;
							
						}
						
						// Identify Collectable input items
						$collectable = $( classes.Collectable.Input + '[value=' + collectable + ']', $source );
						
						// ABOUT if no Collectable input items found
						if ( ! $collectable ) {
							error( "No Collectable input items found!" );
							
							return;
						}
							
						//log($collectable, false);
						
						var curItem = false;
						
						// For each Collectable
						$collectable.each(function() {
							
							// Do nothing if the current Collectable is Available
							if ( $( this ).parent().hasClass( classes.Flag.Available ) )
								return;
							
							// Identify the Craftable this Collectable belongs to
							var item = $( this ).data( 'item' );
							
							// ABORT if item equals curItem 
							if ( item == curItem ) 
								return;
							
							// Add Available class to the parent							
							$( this ).parent()
								.addClass( classes.Flag.Available );
							
							// Register curItem as this item
							curItem = item;
						}); 
					}
					
					
					// Get updated number of remaining
					var remainingIngredients = getRemaining( $( this ) ); 
					
					//log(remainingIngredients);
					
					// Update the Remaining amount
					updateRemaining( $( this ), remainingIngredients );
					
				});
			});
			
			
			// CHANGE - Input
			$( 'input', $( '.' + classes.DeadCheckbox, $div ) ).on( events.Change, function( e ) {
				
				// If this is checked 
				if ( $( this ).prop( property.Checked ) )
					// Add "checked" class to parent 
					$( this ).parent().addClass( classes.Flag.Checked );
					
				// Otherwise
				else
					// Remove "checked" class from parent
					$( this ).parent().removeClass( classes.Flag.Checked );
			});
	
			
			
			// CHANGE - Collectable 
			$( '.' + classes.Collectable.Input, $div ).on( events.Change, function() {
				
				// Activate the Updating flag
				_activateUpdatingFlag();
				
				// Register this collectable to clicked object
				clicked = {
					collectable: $( this ).val()
				};
				
				// Add clicked info to data
				data = $.extend( data, clicked );
				
				
				
				// Update Collectable to db using data
				_collectCollectable( data ) ;
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
				// Identify the parent Details element
				$details = $el.closest( 'details' ),
				// Identify the Remaining Amount element
				$remainingAmountSpan = $( '.' + classes.Name.Remaining, $details )
			;
			
			// ABOUT if no Remaining Amount element found
			if ( ! $remainingAmountSpan.length ) { 
				error( 'Cannot find .' + classes.Name.Remaining + ' span!' );
				return;
			}
			
			// If no Remaining or Remaining is less than 1
			if ( ! remaining || remaining < 1 ) 
				// ADD CLASS Disabled to parent Details element
				$details
					.addClass( classes.Flag.Disabled );
			
			// Otherwise
			else
				// REMOVE CLASS Disabled from parent Details element
				$details
					.removeClass( classes.Flag.Disabled );
			
			//log(remainingAmountSpan, false);
			
			// UPDATE remaining amount in Remaining Amount element
			$remainingAmountSpan.html( remaining );
		};
		
		
		
		// Get Remaining
		
		var getRemaining = function( $el ) {
			// ABORT if no element passed
			if ( ! $el ) 
				return;
			
			// Identify closest parent Details element 
			var $details = $el.closest( 'details' );
				
			// RETURN the amount of inputs not checked inside parent Details element
			return $( 'input', $details ).not( ':' + property.Checked ).length;
		};
	
	
	
	
		
	    
	    
	    
	    
	    /////////////////////////////////////////////////////////////////////////
	    // UPDATE PLUGIN
		// This is where we send the latest player game form data to the database
	    plugin.update = function( data ) {
			_updateCraftable( data ) ;
			return;
		};
		
	
	
	
		// UPDATE - Craftable Item
		
		var _updateCraftable = function ( data ) {
			 
			// ABORT if no Item
			if ( typeof data.craftable == 'undefined' || ! data.craftable ) {
				
				error( "No Craftable ID passed!" );
				
				// Deactivate Updating flag
				_deactivateUpdatingFlag();
				
				return false;
			}
			
			//log( player_game_crafted, false );
			
			var 
				// Item is the passed item in data
				craftable = data.craftable,
				
				// Game ID is on the page as a hidden input
				game_id = $( '#' + ids.Game ).val(), 
				
				// Get the current Player Game data for this Craftable 
				craftableCurrent = ( player_game_crafted.length ) ? player_game_crafted.filter( function( obj ) { return obj.Craftable == craftable; } ) : false,
				
				// Define ajax actions for updating or inserting a Craftable
				ajaxActions = { update: actions.Update.Crafted, insert: actions.Insert.Crafted }
			;
			
			// Clear updating interval variable if a new click is registered
			if ( typeof state.Interval.Update[ craftable ] !== 'undefined' && state.Interval.Update[ craftable ] !== false ) 
				// Clear update interval
				clearInterval( state.Interval.Update[ craftable ] ); 
			
			// If not found in previously collected item list
			// Insert this item into the player game item table
			var action = 'insert';
				
			// Otherwise
			// Update this item in the player game item table
			if ( craftableCurrent.length ) 
				var action = 'update';
			
			
			extra = {
				_ajax_items_nonce: $( '#' + ids.Craftable.Nonce ).val(), 
				
				action: ajaxActions[ action ], 
				
				game_id: game_id, 
			};
			
			// ADD Craftable data to data
			data = $.extend( data, extra );
			
			log( data, false );
			
			// Start update delay
			state.Interval.Update[ craftable ] = setTimeout( function() {
				
				log( ( action == 'update' ) ? content.Label.Updating : content.Label.Inserting );
				
				$.ajax({
		        	
					url: ajaxurl,
					type: 'POST',
					data: data,
		            
					success: function( response ) {
						
						// Deactivate Updating flag
						_deactivateUpdatingFlag();
						
						log( response, false );
						
						log( ( action == 'update' ) ? content.Label.Updated : content.Label.Inserted );
						
						// Updated Locally is currently false until update happens
						var updatedLocally = false;
						
						// For each Player Game Craftable we know about
						$.each( player_game_crafted, function() {
							 
							// If matching Craftable found
							if ( this.Craftable === data.craftable ) {
								
								// Set Craftable Acquired
								this.Acquired = data.acquired;
								
								// Updated Locally is now true
								updatedLocally = true;
								
								return;
							}
						});
						
						// If the Craftable hasn't been updated yet and we have a Player Game Craftables object
						if ( updatedLocally == false && player_game_crafted !== false ) {
							
							// Add Craftable Acquired data to Player Game Craftables object
							player_game_crafted.push( {
								Craftable: data.craftable,
								Acquired: data.acquired
							} );
						}
		            },
					
					// If there's an error in the response
					error: function( response ) {
						
						// Deactivate Updating flag
						_deactivateUpdatingFlag();
						
						// Log error
						error( 'Error ' + ( ( action == 'update' ) ? content.Label.Updating : content.Label.Inserting ) );
						
						// Log response as error
						error( response, false );
					}
					
				});
			
			// Update delay 
			}, plugin.settings.delay );
		};
	
	
	
	
	
	
	
	
	
		/////////////////////////////////////////////////////////////////////////
		// Submit Collectable
		
		var _submitCollectable = function ( data ) {
			 
			log( "Submitting Collectable");
			 
			// ABORT if no Collectable data received
			if ( typeof data.collectable == 'undefined' || ! data.collectable ) {
				
				error( "No Collectable ID received!" );
				
				// Deactivate Updating flag
				_deactivateUpdatingFlag();
				
				// RETURN false
				return false;
			}
			
			var 
				// Get Collectable ID
				collectable = data.collectable,
				
				// Get checked Collectable items 
				checked = $( '.' + classes.Craftable.Collectable.Input + '[value=' + collectable + ']:' + property.Checked ),
				
				// Get quantity of Collectables checked 
				quantity = checked.length,
				
				// Get Game ID from page input  
				game_id = $( '#' + ids.Game ).val(),
				
				// Get amount Submitted amount for current Collectable
				submittedCurrent = player_game_submitted.filter( function(obj) { return obj.CraftableCollectable == collectable; } )[ 0 ],
				
				// Define AJAX actions
				ajaxActions = { update: actions.Update.Submitted, insert: actions.Insert.Submitted } 
			;
			
			// If update interval is currently set
			if ( typeof state.Interval.Update[ collectable ] !== 'undefined' && state.Interval.Update[ collectable ] !== false && state.Interval.Update[ collectable ] !== null )
				
				// Clear the update interval 
				clearInterval( state.Interval.Update[ collectable ] ); 
			
			
			log( player_game_submitted, false );
			
			log( submittedCurrent, false );		
			
			log( quantity, false );		
			
			
			// If not found in previously collected list
			if ( ! submittedCurrent ) { 
			
				// Insert this collectable into the player game item collected table
				var action = 'insert';
			
			}
				
			// Otherwise
			else {
				
				// Update this collectable in the player game item collected table
				var action = 'update';
				
				// Find out if this is actually an update
				if ( submittedCurrent.Quantity == quantity ) {
					
					log( 'No change to Item Collectable! Cancelling update.' );
					
					// Deactivate Updating flag
					_deactivateUpdatingFlag();
					
					// And we're done
					return false;
				}
			}
			
			// Identify Collectable data for update
			var data = { 
				
				// Get Collectable nonce from page element
				_ajax_submitted_nonce: $( '#' + ids.Submitted.Nonce ).val(), 
				
				// Set action
				action: ajaxActions[ action ],
				
				// Collectable ID 
				collectable: collectable,
				
				// Collectable collected quantity  
				quantity: quantity, 
				
				// Game ID
				game_id: game_id, 
			};
			
			log( data, false );				
			
			// Start a new update interval delay
			state.Interval.Update[ collectable ] = setTimeout( function() {
				
				log( ( action == 'update' ) ? content.Label.Updating : content.Label.Inserting );
				
				// Start AJAX call 
				$.ajax({
		        	
					url: ajaxurl,
					type: 'POST',
					data: data,
		            
		            // Successful response
					success: function( response ) { 
						
						log( response , false );
						
						// Updated Locally starts as false
						var updatedLocally = false;
						
						// Identify Collectable ID
						var collectable = data.collectable;
						
						// For each Player Game submitted Collectable
						$.each( player_game_submitted, function() { 
							
							// If matching Collectable found 
							if ( this.CraftableCollectable == collectable ) {
								
								// Set quantity of Collectable submitted 
								this.Quantity = data.quantity;
								
								// Updated Locally is now true
								updatedLocally = true;
							}
						});
						
						// If still not Updated Locally
						if ( updatedLocally == false )
						
							// Add Collectable to Player Game Submitted list
							player_game_submitted.push({
								
								// Collectable ID
								CraftableCollectable: collectable,
								
								// Submitted quantity
								Quantity: data.quantity
							});
						
						// Deactivate Updating Flag
						_deactivateUpdatingFlag();
						
						log( 'Craftable Collectable ' + collectable + ' ' + ( ( action == 'update' ) ? content.Label.Updated : content.Label.Inserted ) );
		            },
					
					// If there's an error in the response
					error: function( response ) {
						
						// Deactivate Updating flag
						_deactivateUpdatingFlag();
						
						// Log error
						error( 'Error ' + ( ( action == 'update' ) ? content.Label.Updating : content.Label.Inserting ) + ' Item Collectable ' + collectable + '!' );
						
						// Log response as error
						error( response, false );
					}
				});
			
			
			// Update delay
			}, plugin.settings.delay );
		};
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
		/////////////////////////////////////////////////////////////////////////
		// Collect Collectable
		
		var _collectCollectable = function ( data ) {
			
			// ABORT if no collectable passed 
			if ( typeof data.collectable == 'undefined' || ! data.collectable ) {
				
				error( "No collectable passed!" );
				
				// Deactivate Updating flag
				_deactivateUpdatingFlag();
				
				// RETURN false
				return false;
			}
			
			var 
				// Identify Collectable ID
				collectable = data.collectable,
				
				// Get all checked Collectables 
				checkedSelector = '.' + classes.Collectable.Input + '[value=' + collectable + ']:' + property.Checked,
				
				checked = $( checkedSelector ),
				
				// Get quantity of checked Collectables	 
				quantity = checked.length, 
				
				// Get Game ID from page input element
				game_id = $( '#' + ids.Game ).val(),
				
				// Get currently known amount of collected Collectables 
				collectableCurrent = player_game_collected.filter( function( obj ) { return obj.Collectable == collectable; } ),
				
				// Define AJAX actions
				ajaxActions = { update: actions.Update.Collected, insert: actions.Insert.Collected }
			;
			
			// If update command is currently active
			if ( typeof state.Interval.Update[ collectable ] !== 'undefined' && state.Interval.Update[ collectable ] !== false )
				// Clear the existing update command 
				clearInterval( state.Interval.Update[ collectable ] ); 
			
			// If not found in previously collected list
			if ( ! collectableCurrent.length ) 
			
				// Insert this collectable into the player game collected table
				var action = 'insert';
				
			// Otherwise
			else {
				// Update this collectable in the player game collected table
				var action = 'update';
				
				// ABORT if no change from the known quantity
				if ( collectableCurrent[ 0 ].Collected == quantity ) {
					
					log( 'No change! Cancelling update.' );
					
					log( collectableCurrent[ 0 ].Collected, false );
					
					// Deactivate Updating flag
					_deactivateUpdatingFlag();
					
					// And we're done
					return false;
				}
			}
			
			// Gather update data
			var data = { 
				
				_ajax_collected_nonce: $( '#' + ids.Collected.Nonce ).val(), 
				
				// Set AJAX action
				action: ajaxActions[ action ],
				
				// Collectable ID 
				collectable: collectable,
				
				// Quantity of Collected Collectable 
				quantity: quantity, 
				
				// Game ID
				game_id: game_id, 
			};
			
			log(data, false);				
			
			// Start new update command timer
			state.Interval.Update[ collectable ] = setTimeout( function() {
				
				log( ( action == 'update') ? content.Label.Updating : content.Label.Inserting );
	
				// Start new AJAX call
				$.ajax( {
		        	
					url: ajaxurl,
					type: 'POST',
					data: data,
		            
		            // Successful response
					success: function( response ) { 
						
						log( response, false );
						
						// Updated Locally is starts as false
						var updatedLocally = false;
						
						// For each known Player Game Collected Collectable
						$.each( player_game_collected, function() {
							
							// If matching Collectable found
							if ( this.Collectable == data.collectable ) {
								
								// Update Collectable Collected quantity 
								this.Collected = data.quantity;
								
								// Updated Locally is now true
								updatedLocally = true;
								
								log( this, false );
							}
						});
						
						// If Updated Locally still false
						if ( updatedLocally == false ) {
							
							log( "Updated Locally is still false...");
							
							// Add Collectable and quantity to Player Game Collected
							player_game_collected.push( {
								Collectable: data.collectable,
								Collected: data.quantity
							} );
							
							updatedLocally = true;
						}
						
						if ( updatedLocally == false )
							log( "Updated Locally is STILL false...");
						
						// Deactivate Updating flag
						_deactivateUpdatingFlag();
						
						log( ( action == 'update' ) ? content.Label.Updated : content.Label.Inserted );
		            },
					
					
					// If error in response
					error: function( response ) {
						
						// Deactivate Updating flag
						_deactivateUpdatingFlag();
						
						// Log error
						error( 'Error ' + ( ( action == 'update' ) ? 'updating!' : 'inserting!' ) );
						
						// Log response as error
						error( response, false );
					}
				});
			
			// Update delay
			}, plugin.settings.delay );
		};
		
		
		
		
		
		
		
		
		
		
		
		
		
		/////////////////////////////////////////////////////////////////////////
		// Update Filter
		
		var _updateFilter = function ( data ) {
			
			log( "Updating filters... ");
			
			// ABORT if no filter passed 
			if ( typeof data.filter == 'undefined' ) {
				
				error( "No filter value passed!" );
				
				// Deactivate Updating flag
				_deactivateUpdatingFlag();
				
				// RETURN false
				return false;
			}
			
			var 
				// Identify filter
				filter = ( ! data.filter ) ? '0' : data.filter,
				
				player_id = $( '#' + ids.Player ).val(),
				
				// Define AJAX actions
				ajaxActions = { update: actions.Update.Collected, insert: actions.Insert.Collected }
			;
			
			// If update command is currently active
			if ( typeof state.Interval.Update[ player_id ] !== 'undefined' && state.Interval.Update[ player_id ] !== false )
			
				// Clear the existing update command 
				clearInterval( state.Interval.Update[ player_id ] ); 
			
			
			// Gather update data
			var data = { 
				
				_ajax_filters_nonce : $( '#' + ids.Filters.Nonce ).val(), 
				
				// Set AJAX action
				action : actions.Update.Filter,
				
				// Game ID
				player_id : player_id, 
				
				// Filter 
				filter : filter // 1 or 0
			};
			
			log( data, false );				
			
			// Start new update command timer
			state.Interval.Update[ player_id ] = setTimeout( function() {
				
				log( content.Label.Updating );
	
				// Start new AJAX call
				$.ajax( {
		        	
					url: ajaxurl,
					type: 'POST',
					data: data,
		            
		            // Successful response
					success: function( response ) { 
						
						log( response, false );
						
						// Deactivate Updating flag
						_deactivateUpdatingFlag();
						
						log( content.Label.Updated );
		            },
					
					
					// If error in response
					error: function( response ) {
						
						// Deactivate Updating flag
						_deactivateUpdatingFlag();
						
						// Log error
						error( 'Error ' + content.Label.Updating );
						
						// Log response as error
						error( response, false );
					}
				});
			
			// Update delay
			}, plugin.settings.delay );
		};
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		// Get Distinct
		
		var getDistinct = function( array, key, value ) {
			// ABORT if any of the values are not passed
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
			// ABORT if no element passed
			if ( typeof element == "undefined" || ! element )
				return false; 
			
			// CHECK the element and trigger a change event
			element
				.prop( property.Checked, property.Checked )
				.trigger( events.Change );
		};
		
		
		
		// Uncheck checkbox
		
		var unCheck = function( element ) {
			// ABORT if no element passed
			if ( typeof element == "undefined" || ! element )
				return false; 
	
			// UNCHECK the element and trigger a change event
			element
				.prop( property.Checked, '' )
				.trigger( events.Change );
		};
		
		
		
		// Sum Column
		
		var sumColumn = function sumColumn( array, col ) {
			
			// ABORT if crudely no vars passed
			if ( ! array || ! col ) 
				return false;
			
			// Sum begins at zero
		    var sum = 0;
		    
		    // For each element of the array, sum the values in the passed column
		    array.forEach( function ( value, index, array ) {
		        sum += parseInt( value[ col ] );
		    });
		    
		    // Return the final sum of the array column values
		    return sum;
		};
		
		
		
		// Read Query String	
		// Get query string from address bar
		var query = function( query, variable ) {
			var vars = query.split( '&' );
			
			for ( var i = 0; i < vars.length; i++ ) {
			    var pair = vars[ i ].split( '=' );
			    
			    if ( pair[ 0 ] == variable )
			        return pair[ 1 ];
			}
			
			return false;
		};
		
		
		
		
		// Toggle Updating Flag
		
		var toggleUpdatingFlag = function() {
			
			elements.Flag.Updating.toggleClass( classes.Flag.Top );
			
			elements.Flag.Updating.toggleClass( classes.Flag.Show );
			
		};
		
		
		
		// Activate Updating Flag
		
		var _activateUpdatingFlag = function() {
			
			//log( elements.Flag.Updating, false );
			
			elements.Flag.Updating.addClass( classes.Flag.Top );
			
			elements.Flag.Updating.addClass( classes.Flag.Show );
			
		};
		
		
		
		// Deactivate Updating Flag
		
		var _deactivateUpdatingFlag = function() {
			
			elements.Flag.Updating.removeClass( classes.Flag.Show );
			
			setTimeout( function() {
				
				elements.Flag.Updating.removeClass( classes.Flag.Top );
				
			}, 300 );
			
		};
	
	
	
		// Setup Tabs
		
		var setupTabs = function( tabElement ) {
			
			// ABORT if no Tab element passed
			if ( ! tabElement.length ) 
				return;
			
			var $element = tabElement;
			
			if ( ! $(" > ul > li:first-child", $element ).length ) {
				error( 'REALLY Cannot find the appropriate children for tabs!');
				
				error( $element, false ); 
			}				
			
			// Setup EasyTabs jQuery plugin on passed Tab element
			$element.easytabs( $.extend( {}, plugin.settings.EasyTabs, {} ) );
			
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
			
			// ABORT if no Details elements found in the DOM
			if ( ! $( 'details' ).length )
				return false;
			
			// Add Details jQuery plugin to the Details elements in the DOM 	
			$( 'details' ).details();
			
			// Conditionally add a classname to the `<html>` element, based on native support
			$( 'html' ).addClass( $.fn.details.support ? 'details' : 'no-details' );
			
			// Trigger events on Details elements
			$( document ).on( events.Key.Up + ' ' + events.Click, 'summary', function( e ) {
				$( window ).trigger( events.Resize ).trigger( 'scroll' );
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
		
	};
	

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
