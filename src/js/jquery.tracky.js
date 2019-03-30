
// RDR2 PROGRESS TRACKER 
// jQuery Plugin


( function($) {

$.tracky = function( container, options ) {

	var 
		plugin = this,
		$container = $( container ),
		
		name = "Tracky",
    	title = name + " jQuery Plugin",
		logging = true, 
		logPrefix = name,
		delay = 2500, 
		defaults = {
			delay: delay,
		},
		settings,
			
		timer, 
		staticData, 
		navTabActive, 
		locationID, 
		swiper,
		tabNum = 0,
		navTabs = "",
		tabs = '',
		updateInterval = {},
		
		collection_locations = JSON.parse(database.collection_locations),
		collections = JSON.parse(database.collections),
		collection_groups = JSON.parse(database.collection_groups),
		collection_collectables = JSON.parse(database.collection_collectables),
		collection_items = JSON.parse(database.collection_items),
		collection_item_collectables = JSON.parse(database.collection_item_collectables),
		
		ingredients = JSON.parse(database.ingredients),
		ingredient_types = JSON.parse(database.ingredient_types),
		ingredient_qualities = JSON.parse(database.ingredient_qualities),
		ingredient_parts = JSON.parse(database.ingredient_parts),
	
		player_game_items = JSON.parse(database.player_game_items),
		player_game_collected = JSON.parse(database.player_game_collected),
		player_game_submitted = JSON.parse(database.player_game_submitted),
		
		content = {
			Screens : [
				{ name: "Location" },
				{ name: "IngredientType" },
			],
			UpdatingLabel : "Updating..."
		},
		classes = {
			Swiper : {
				Container : "swiper-container"
			},
			App : {
				Container : "app-container",
				Sections : {
					Container : "app-sections-container",
					List : "app-sections-list",
					Filter : "app-sections-filter",
					Wrapper : "app-sections-wrapper",
				},
				CheckboxList : "app-checkbox-list"
			},
			UpdatingFlag : "updating-flag",
			CheckboxList : "checkbox-list"
		},
		ids = {
			App : "App",
			UpdatingFlag : "UpdatingFlag",
			UpdatingLabel : "updating-label"
		},
		html = {
			App : {
				Container : '<div class="' + classes.App.Container + '" id="' + ids.App + '"></div>',
				List : '<div class="' + classes.App.Sections.List + '"><ul class="horizontal pill"><li><a data-id="0" class="active">Sources</a></li><li><a data-id="1">Collectables</li></ul></div>',
				Filters : '<div class="' + classes.App.Sections.Filter + '"><ul class="horizontal pill"><li><label class=""><input type="checkbox" name="filter" value="1" id="filter-remaining"> Show remaining only</label></li></ul></div>',
				Sections : '<div class="' + classes.App.Sections.Container + '"><div class="' + classes.App.Sections.Wrapper + ' swiper-wrapper"></div></div>',
			},
			UpdatingFlag : "<div class='" + classes.UpdatingFlag + "' id='" + ids.UpdatingFlag + "'><span class='" + classes.UpdatingLabel + "'>" + content.UpdatingLabel + "</span></div>",	
			},
			$app = false,
			$UpdatingFlag = false
		;



		plugin.init = function( ) {
 
			plugin.settings = settings = $.extend({}, defaults, options);
		
			$container.data('tracky', {});
		
		var data = {
			game_id: $("#game_id").val(),
		};
		
		// Add "updating" flag for all screens
		$container.append(html.UpdatingFlag);
		$UpdatingFlag = $("#UpdatingFlag", $container);
		
		
		
		
		// Add Screens
		setupScreens( data );
		
		// Setup Swipers
		//setupSwipers();
		
		// Details and Summary		
		setupDetailSummary();

		
    };	
	
	
	
	// Setup Screens			
	
	var setupScreens = function( data ) {
		// Screens are swiper slides (because of course they are)
		
		// App Container
		$container.append(html.App.Container);
		$app = $("#" + ids.App, $container);
		
		// Section list
		$app.append(html.App.List);
		$list = $("." + classes.App.Sections.List, $app);
		
		// App Filters
		$app.append(html.App.Filters);
		$filters = $("." + classes.App.Sections.Filter, $app);
		
		// Sections
		$app.append(html.App.Sections);
		
		$swiper = $("." + classes.App.Sections.Container, $app);
		$swiper.addClass(classes.Swiper.Container);
		
		$sections = $("." + classes.App.Sections.Wrapper, $swiper);
		
		
		
		
		// Show By Location Screen
		showByLocation( data );
		
		// Show By Ingredient Type Screen
		showByIngredientType( data );
		
		
		// Behavior of category tabs 
		
		// Nav Tab Wrapper
		
		$(".nav-tab a", $container).on("click", function(e) {
			e.stopPropagation();
			log("Clicked item!");
			var wrapper = $(this).closest('.nav-tab-wrapper');
			if (wrapper.hasClass("open"))
				wrapper.removeClass("open");
		});
		
		$(".nav-tab-wrapper", $container).on("click", function(e) {
			log("Clicked container!");
			if (!$(this).hasClass("open"))
				$(this).addClass("open");
		});
		




		
		// Filters
		
		$("#filter-remaining", $container).on("change", function(e) {
			
			if ($(this).prop("checked") == true)
				$app.addClass("filter-remaining");
			else
				$app.removeClass("filter-remaining");
		});
		
		
		
		// Find the swiper and apply appropriate settings...
		
		swiper = new Swiper("." + classes.Swiper.Container, {
			direction : 'horizontal', 
			loop : false, 
			wrapperClass : 'app-sections-wrapper', 
			slideClass : 'app-section', 
			autoHeight : false,
			slidesPerView : 1,
			slidesPerColumn : 1,
			spaceBetween : 20,
			shortSwipes : false,
			resistanceRatio : 0,
			simulateTouch : false,
			
			on: {
				slideChange: function () {
					$("a:eq(" + swiper.activeIndex + ")", $list).click();
				},
			},
		});
		
		
		$("a", $list).on("click", function() {
			$("a", $list).removeClass("active");
			$(this).addClass("active");
			swiper.slideTo($(this).data("id"));
		});
	};
	
	
	
	
	// Setup Swipers
	
	var setupSwipers = function() {
		
		
		// Find the swipers and apply appropriate settings...
		
		swiper = new Swiper("." + classes.Swiper.Container, {
			direction : 'horizontal', 
			loop : false, 
			wrapperClass : 'app-sections-wrapper', 
			slideClass : 'app-section', 
			autoHeight : false,
			slidesPerView : 1,
			slidesPerColumn : 1,
			spaceBetween : 20
		});
		
	};
	
	
	
	
	
	
	
	
	
	// LOCATION/SOURCE
	
	// Prints by Location first
	
	var showByLocation = function( data ) {
		var 
			locationIcons = {
				1: "fas fa-campground",
				2: "fas fa-dollar-sign",
				3: "far fa-envelope",
				4: "fas fa-paw",
			},
			heading = "<h3 class='screen-reader-text'>Sources</h3>",
			navTabs = ""
		;
		
		for ( var l = 0; l < collection_locations.length; l++ ) {
			var tabContent = "";
			
			navTabs += '<li class="nav-tab"><a href="#location-' + collection_locations[l].Name + '" class="" data-locationid="' + collection_locations[l].ID + '" data-tabid="' + l + '"> <i class="' + locationIcons[collection_locations[l].ID] + '"></i> ' + collection_locations[l].Name + '</a></li>';
			
			// Collections
			var locationCollections = collections.filter(function(obj) { return obj.Location == collection_locations[l].ID; });

			if (locationCollections.length) {
				
				for (var c = 0; c < locationCollections.length; c++) {
					var detailsContent = "";
					
					// Groups
					var collectionGroups = collection_groups.filter(function(obj) { return obj.Collection == locationCollections[c].ID; });
					
					if (collectionGroups.length) {
						detailsContent += "<ul class='" + classes.CheckboxList + " " + classes.App.CheckboxList + "'>";
						
						for (var g = 0; g < collectionGroups.length; g++) {
							var itemsContent = "";
							
							// Items
							var groupItems = collection_items.filter(function(obj) { return obj.Group == collectionGroups[g].ID; });
							
							if (groupItems.length) {
								if (collectionGroups[g].Name !== locationCollections[c].Name)
									itemsContent += "<ul>";
								
								for (var i = 0; i < groupItems.length; i++) {
									var itemCollectablesContent = "";
									var totalCollectable = 0;
									var totalCollected = 0;
									var totalSubmitted = 0;
									var totalRemaining = false;
									
									// Item Collectables
									var itemCollectables = collection_item_collectables.filter(function(obj) { return obj.Item == groupItems[i].ID; });
									
									if (itemCollectables.length) {
										itemCollectablesContent += "<ul class='" + classes.CheckboxList + " " + classes.App.CheckboxList + "'>";
										
										for (var s = 0; s < itemCollectables.length; s++) { 
											
											// Collection Collectables
											var 
												collectionCollectable = collection_collectables.filter(function(obj) { return obj.ID == itemCollectables[s].Collectable; })[0],
												ingredient = ingredients.filter(function(obj) { return obj.ID == collectionCollectable.Ingredient; })[0],
												quality = ingredient_qualities.filter(function(obj) { return obj.ID == collectionCollectable.Quality; })[0],
												part = ingredient_parts.filter(function(obj) { return obj.ID == collectionCollectable.Part; })[0],
												quantity = itemCollectables[s].Quantity,
												collectedCollectable = player_game_collected.filter(function(obj) { return obj.Collectable == itemCollectables[s].Collectable; })[0],
												submittedCollectable = player_game_submitted.filter(function(obj) { return obj.ItemCollectable == itemCollectables[s].ID; })[0],
												quantityCollected = (typeof collectedCollectable !== "undefined") ? collectedCollectable.Collected : 0,
												quantitySubmitted = (typeof submittedCollectable !== "undefined") ? submittedCollectable.Quantity : 0,
												collectablesContent = ""
											;
											
											totalCollectable = totalCollectable + parseInt(quantity);
											totalCollected = totalCollected + parseInt(quantityCollected);
											totalSubmitted = totalSubmitted + parseInt(quantitySubmitted);
											
											totalRemaining = totalCollectable - totalSubmitted;
											
											// For each quantity of collectable
											for (var q = 0; q < quantity; q++) {
												var 
													isSubmitted = (q < quantitySubmitted),
													isCollected = (q < quantityCollected), 													 
													availableVal = (isCollected) ? "available " : "",
													checkedVal = (isSubmitted) ? "checked " : "",
													disabledVal = (isSubmitted && !isCollected) ? "disabled " : ""
												;
												
												collectablesContent += "<label class='DeadCheckbox item-collectable-checkbox " + availableVal + disabledVal + "'> <input type='checkbox' name='item-collectables' class='itemcollectable' value='" + itemCollectables[s].ID + "' data-item='" + groupItems[i].ID + "' data-collectable='" + collectionCollectable.ID + "' " + checkedVal + "> </label>";
											}
											
											itemCollectablesContent += "<li><label>" + quality.Name + " " + ingredient.Name + " " + part.Name + " x " + itemCollectables[s].Quantity + "</label> " + collectablesContent + "</li>";
										}
										
										itemCollectablesContent += "</ul>";
									}
									
									var 
										submittable = (totalSubmitted < totalCollectable),
										craftable = !submittable,
										craftedItem = player_game_items.filter(function(obj) { return obj.Item == groupItems[i].ID; })[0],
										isCrafted = (typeof craftedItem !== "undefined" && craftedItem.Acquired == 1),
										abled = (submittable) ? 'disabled ' : '',
										opened = (submittable) ? ' open ' : '',
										available = (craftable) ? 'available ' : '',
										checkedVal = (isCrafted) ? 'checked ' : '',
										
										frank
									;
									
									//log(craftedItem, false);
									
									//log(abled, false);
									var groupItemClasses = (totalRemaining == 0) ? "disabled " : "";
									itemsContent += "<li><details class='" + groupItemClasses + "' " + opened + "><summary class='" + abled + "'><label class='DeadCheckbox collectable-item-checkbox " + abled + available + "'> <span class='dashicons dashicons-yes'></span> <input type='checkbox' class='collectable-item' name='items' value='" + groupItems[i].ID + "' " + abled + checkedVal + " > </label> <label class='collectable-item-label item-label'>" + groupItems[i].Name + "</label></summary> " + itemCollectablesContent + "</details> </li>";
								}
								
								if (collectionGroups[g].Name !== locationCollections[c].Name)
									itemsContent += "</ul>";
							}
							
							if (collectionGroups[g].Name !== locationCollections[c].Name) {
								detailsContent += "<li>";
								
								
								
								detailsContent += "<details><summary><label> <span class='DeadCheckbox disabled'> <span class='dashicons dashicons-yes'></span> <input type='checkbox' class='collectable-group' name='group' value='" + collectionGroups[g].ID + "' " + abled + " > </span> </label> " + collectionGroups[g].Name + " </summary>";
							}
							
							detailsContent += itemsContent;
							
							if (collectionGroups[g].Name !== locationCollections[c].Name) {
								detailsContent += "</details>";
								detailsContent += "</li>";
							}
						}
						
						detailsContent += "</ul>";
					}
					
					tabContent += '<details><summary>' + locationCollections[c].Name + '</summary> ' + detailsContent + '</details>';
				}
			}
			
			tabs += '<div class="location-tab luckytab" id="location-' + collection_locations[l].Name + '"><h3 class="screen-reader-text">' + collection_locations[l].Name + '</h3><div class="tabContent">' + tabContent + '</div></div>';					
		}
		
		var tabsWrapper = '<div id="collectable-items" class="app-section swiper-slide">' + heading + '<div id="location-tabs" class="tabs-container luckytabs toptabs"><ul class="pill location-selector nav-tab-wrapper">' + navTabs + '</ul>' + tabs + '</div></div>';	 				

		$sections.append(tabsWrapper);
		
		$div = $("#location-tabs");
		
		
		
		// Attach event listeners to perform actions
		_setupLocationActions( data , $div );
		
				
		// Tabs
		setupTabs($div);
		
	};



	// LOCATION Behaviours

	var _setupLocationActions = function( data , $div ) {
		

		$(".DeadCheckbox", $div).each(function() { 
			// Initially mark as checked if input is checked
			if ( $("input", this).prop('checked') ) 
				$(this).addClass("checked"); 
		});
		
		
		// Item Collectable Checkbox onClick
		
		$( '.item-collectable-checkbox', $div ).on( 'click', function( e ) {
			// Don't do anything by default
			e.preventDefault();
			
			// Variables
			var 
				$input = $( 'input', $( this ) ),
				item = '',
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
				//log( 'Is available...' );
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
			if ( !confirm( 'This item has not been collected. Do you want to mark as collected and sell?' ) ) 
				return;
				
			// TODO: Mark collectable as collected before selling 
			// Involves interacting with the Ingredients View
			
			

			// Check first not sold (might be self)
			check( $('input', $notSold.first() ) );
			
			// And we're really done
		});
		
		
		// Item Collectable Checkbox onChange
		$("input", $('.item-collectable-checkbox', $div)).on("change", function(e) { 
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
			
			
			if ( $( this ).prop( 'checked' ) ) { 
				$( this ).parent().addClass( 'checked' );
				
				log(unchecked);
				
				if ( unchecked )
					return;
				
				// Make the parent Collectable Item "craftable"						
				$collectableItemSummary
					.removeClass( 'disabled' );
				
				$collectableItemCheckbox
					.removeClass( 'disabled' )
					.addClass( 'available' );
					
				$collectableItemCheckboxInput
					.prop( 'disabled', '');
				
				return;
			}
			
			$( this ).parent().removeClass( 'checked' );
			
			if ( unchecked > 1 )
				return;
			
			// Make the parent Collectable Item "un-craftable"						
			$collectableItemSummary
				.addClass( 'disabled' );
			
			$collectableItemCheckbox
				.addClass( 'disabled' )
				.removeClass( 'available' );
				
			$collectableItemCheckboxInput
				.prop( 'disabled', 'disabled');
			
		});
		
		
		
		// Collectable Item Label onClick
		
		$( '.collectable-item-label', $div).on( 'click', function(e) {
			e.preventDefault();
			
			$(this).parent()
				.click();
		});
		
		
		
		// Collectable Item Checkbox onClick
		
		$('.collectable-item-checkbox', $div).on('click', function(e) {		
			// Don't do anything by default
			e.preventDefault();
			
			// Variables
			var 
				$input = $( 'input', $( this ) ),
				item = '',
				checked = $input.prop( 'checked' ),
				availableThis = $( this ).hasClass( 'available' ),
				
				
				frank
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
			if ( !availableThis ) {
				$(this).parent()
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
		
		
		
		$("input", $('.collectable-item-checkbox', $div)).on("change", function(e) { 
			e.preventDefault();
			
			var checked = $(this).prop( 'checked' );
			
			if ( checked ) 
				$(this).parent().addClass( 'checked' );
			else
				$(this).parent().removeClass( 'checked' );
		
		
			activateUpdatingFlag();
			
			//log(player_game_items, false);
		
			
			clicked = {
				item: $( this ).val(),
				acquired : ((checked == true) ? 1 : 0),
			};
			
			data = $.extend( data, clicked );
			
			updateItem( data ) ;
			
		});

	};



	// SHOW BY INGREDIENT TYPE

	// Prints out list of Collectables by Ingredient type
	
	var showByIngredientType = function( data ) {
		var 
			typeIcons = {
				1 : "fas fa-hippo",
				2 : "fas fa-crow",
				3 : "fas fa-fish",
				4 : "far fa-gem",
				5 : "far fa-snowflake",
			},
			heading = "<h3 class='screen-reader-text'>Collectables</h3>",
			navTabs = "",
			remaining = []
		;
		
		// Ingredient Types
		for (var t = 0; t < ingredient_types.length; t++) {
			var tabContent = "";
			
			navTabs += '<li class="nav-tab"><a href="#type-' + ingredient_types[t].Plural + '" class="" data-typeid="' + ingredient_types[t].ID + '" data-tabid="' + t + '"><i class="' + typeIcons[ingredient_types[t].ID] + '"></i>' + ingredient_types[t].Plural + '</a></li>';
			
			// Type Ingredients
			var typeIngredients = ingredients.filter(function(obj) { return obj.Type == ingredient_types[t].ID; });
			
			if (typeIngredients.length) {
				
				for (var n = 0; n < typeIngredients.length; n++) {
					var 
						collectionCollectables = collection_collectables.filter(function(obj) { return obj.Ingredient == typeIngredients[n].ID; }),
						ingredientsContent = "",
						detailsContent = "",
						totalCollectable = 0,
						totalCollected = 0,
						totalSubmitted = 0
					;
					
					for (var c = 0; c < collectionCollectables.length; c++) {
						var 
							ingredient = ingredients.filter( function(obj) { return obj.ID == collectionCollectables[c].Ingredient; }),
							part = ingredient_parts.filter( function(obj) { return obj.ID == collectionCollectables[c].Part; })[0],
							quality = ingredient_qualities.filter( function(obj) { return obj.ID == collectionCollectables[c].Quality; })[0],
							collectableItems = collection_item_collectables.filter( function(obj) { return obj.Collectable == collectionCollectables[c].ID; }),
							quantity = ( collectionCollectables[c].Quality == 2) ? 1 : sumColumn(collectableItems, "Quantity"),
							collectedCollectable = player_game_collected.filter( function(obj) { return obj.Collectable == collectionCollectables[c].ID; })[0],
							quantityCollected = ( typeof collectedCollectable !== "undefined") ? collectedCollectable.Collected : 0
						;
						
						totalCollectable = totalCollectable + parseInt( quantity );
						totalCollected = totalCollected + parseInt( quantityCollected );
						
						totalRemaining = totalCollectable - totalCollected;
						
						var remainingObject = { "Collectable" : collectionCollectables[c].ID, "Remaining" : totalRemaining };
						
						remaining.push(remainingObject);			
						
						detailsContent += "<ul class='" + classes.CheckboxList + " " + classes.App.CheckboxList + "'><li><label>" + quantity + " x " + quality.Name + " " + ingredient[0].Name + " " + part.Name + "</label> ";
					
						// For each quantity of collectable
						for ( var q = 0; q < quantity; q++ ) {
							var checkedVal = ( q < quantityCollected ) ? "checked " : "";			
							detailsContent += "<label class='DeadCheckbox'> <span class='dashicons dashicons-yes'></span> <input type='checkbox' name='collectables' class='collectable' value='" + collectionCollectables[c].ID + "' " + checkedVal + "> </label>";
						}
						
						detailsContent += "</li></ul>";
					}
					
					var ingredientClasses = (totalRemaining == 0) ? "disabled " : "";
					
					ingredientsContent += '<details class="' + ingredientClasses + '" data-ingredient="' + typeIngredients[n].ID + '" data-remaining="' + totalRemaining + '"><summary>' + ingredient[0].Name + ' <span class="remaining-amount">' + totalRemaining + '</span></summary> ' + detailsContent + '</details>';
					
					tabContent += ingredientsContent;
				}
			}
			
			tabs += '<div class="ingredient-type-tab luckytab" id="type-' + ingredient_types[t].Plural + '"><h3 class="screen-reader-text">' + ingredient_types[t].Plural + '</h3><div class="tabContent">' + tabContent + '</div></div>';
		}
		
		var tabsWrapper = '<div id="ingredients" class="app-section swiper-slide">' + heading + '<div id="collectable-tabs" class="tabs-container luckytabs toptabs"><ul class="pill location-selector nav-tab-wrapper">' + navTabs + '</ul>' + tabs + '</div></div>';	 				
		
		$sections.append(tabsWrapper);
		
		//log(remaining, false);
		
		//log(player_game_collected, false);
		
		$div = $("#collectable-tabs");								
		
		// Setup Ingredient Actions
		_setupIngredientActions( data , $div );
				
		// Tabs
		setupTabs($div);
		
		
		// Setup checkbox actions
		$(".collectable", $div).on("change", function() {
			
			activateUpdatingFlag();
			
			clicked = {
				collectable: $(this).val()
			};
			
			data = $.extend( data, clicked );
			
			updateCollectable( data ) ;
			
			return;
		});
	};
	
	
	
	
	// Setup Ingredient Actions
	
	var _setupIngredientActions = function( data , $div ) { 
		
		
		$(".DeadCheckbox", $div).each(function() { 
			if ($("input", this).prop('checked')) 
				$(this).addClass("checked"); 
			
			
			$(this).on("click", function(e) {
				e.preventDefault();
				
				var 
					input = $("input", $(this)), 
					collectable = "", 
					checked = (input.prop('checked') == true ), 
					collectable = input.val(), 
					$location = $("#location-tabs"), 
					$prevCollectable = false 
				;
				
				// Ingredient quantity clicked is currently checked
				if (checked == true) { 
					
					$("input:checked", $(this).parent()).last() 
						.prop("checked", "") 
						.trigger("change"); 
					
					if (!$location.length)
						return;
					
					$collectable = $("input.itemcollectable[value=" + collectable + "]:not(:checked)", $location);
					
					//log($collectable, false);
					
					if (!$collectable)
						return;
						
					$collectable.last().parent().removeClass("available");
				
					
				// Ingredient not currently checked
				
				} else {
					
					var next = $("input", $(this).parent()).not(":checked").first(); 
					
					if (input == next)
						input
							.prop("checked", "checked")
							.trigger("change");
					else 
						next
							.prop("checked", "checked")
							.trigger("change");
					
					if (!$location.length)
						return;
					
					$collectable = $("input.itemcollectable[value=" + collectable + "]", $location);
					
					if (!$collectable)
						return;
						
					//log($collectable, false);
					
					var curItem = false;
					
					$collectable.each(function() {
						if ($(this).parent().hasClass("available"))
							return;
								
						var item = $(this).data("item");
						
						if (item == curItem) 
							return;
																
						$(this).parent()
							.addClass("available");
												
						curItem = item;
					});							
				}
				
				
				// Get updated number of remaining
				var remainingIngredients = getRemaining( $(this) ); 
				
				//log(remainingIngredients);
				
				updateRemaining( $(this), remainingIngredients);
				
			});
		});
		
		
		$("input", $(".DeadCheckbox", $div)).on("change", function(e) { 
			if ($(this).prop('checked')) 
				$(this).parent().addClass("checked");
			else
				$(this).parent().removeClass("checked");
		});
	};
	
	
	
	
	// Update Remaining
	
	var updateRemaining = function( $el, remaining ) {
		if (!$el || remaining == null) {
			error("No element or remaining");
			return;
		}
		
		var $details = $el.closest('details');
		
		var $remainingAmountSpan = $(".remaining-amount", $details);
		
		if (!$remainingAmountSpan.length) {
			error("Cannot find .remaining-amount span!");
			return;
		}
		
		if (!remaining || remaining < 1) {
			$details
				.addClass("disabled")
				//.removeAttr("open")
			;
						
		}
		else
			$details.removeClass("disabled");
		
		//log(remainingAmountSpan, false);
		
		$remainingAmountSpan.html(remaining);
	};
	
	
	
	// Get Remaining
	var getRemaining = function( $el ) {
		if (!$el)
			return;
			
		var $details = $el.closest('details');
			
		return $("input", $details).not(':checked').length;
	};


	// Get Distinct
	var getDistinct = function( array, key, value ) {
		if (!array || !key || !value)
			return false;
		
		var 
			result = [],
			map = new Map()
		;
		
		for (var i = 0; i < array.length; i++) {
			
		    if (!map.has(array[i].key)) {
		        map.set(array[i].key, true);    // set any value to Map
		        result.push({
		            ID: array[i].key,
		            Ingredient: array[i].value
		        });
		    }
		    
		}
		
		return result;
	};
	
	
	// Check checkbox
	var check = function( element ) {
		element
			.prop('checked', 'checked')
			.trigger('change');
	};
	
	
	// Uncheck checkbox
	var unCheck = function( element ) {
		element
			.prop('checked', '')
			.trigger('change');
	};
	
	
	// Sum Column
	var sumColumn = function sumColumn(array, col) {
	    var sum = 0;
	    array.forEach(function (value, index, array) {
	        sum += parseInt(value[col]);
	    });
	    return sum;
	};
	
	
	// Read Query String	
	var query = function( query, variable ) {
		var vars = query.split("&");
		for ( var i = 0; i <vars.length; i++ ) {
		    var pair = vars[ i ].split("=");
		    if ( pair[0] == variable )
		        return pair[1];
		}
		return false;
	};
	
    
    
    
	// This is where we send the latest player game form data to the database
	
    plugin.update = function( data ) {
		updateItem( data ) ;
		return;
	};





	var updateItem = function ( data ) { 
		if ( typeof data.item == "undefined" || !data.item ) {
			deactivateUpdatingFlag();
			return false;
		}
		
		log(player_game_items, false);
		
		var 
			item = data.item,
			game_id = $("#game_id").val(), 
			itemCurrent = (player_game_items.length) ? player_game_items.filter(function(obj) { return obj.Item == item; }) : false,
			ajaxActions = { update: "_ajax_update_player_game_item", insert: "_ajax_insert_player_game_item" }
		;
		
		if (typeof updateInterval[item] !== "undefined" && updateInterval[item] !== false) 
			clearInterval(updateInterval[item]); 
		
		// If not found in previously collected item list
		// Insert this item into the player game item table
		var action = "insert";
			
		// Otherwise
		// Update this item in the player game item table
		if (itemCurrent.length) 
			var action = "update";
		
		extra = {
			_ajax_items_nonce: $( '#_ajax_update_items_nonce' ).val(), 
			action: ajaxActions[action], 
			game_id: game_id, 
		};
			
		data = $.extend( data, extra );
		
		updateInterval[item] = setTimeout(function() {
			log((action == "update") ? "Updating..." : "Inserting...");
			
			$.ajax({
	        	
				url: ajaxurl,
				type: "POST",
				data: data,
	            
				success: function( response ) {
					deactivateUpdatingFlag();
					log(response, false);
					
					log((action == "update") ? "Updated!" : "Inserted!");
					
					var updatedLocally = false;
					
					$.each(player_game_items, function() { 
						if (this.Item === data.item) {
							this.Acquired == data.acquired;
							updatedLocally = true;
						}
					});

					if (updatedLocally == false && player_game_items !== false)
						player_game_items.push({
							Item: data.item,
							Acquired: data.acquired
						});
					
	            },
				
				error: function( response ) {
					deactivateUpdatingFlag();
					error("Error " + ((action == "update") ? "Updating..." : "Inserting..."));
					error(response, false);
				}
				
			});
			
		}, settings.delay);
	};




	// Update Item Collectable
	
	var _updateItemCollectable = function ( data ) { 
		if ( typeof data.itemcollectable == "undefined" || !data.itemcollectable ) {
			deactivateUpdatingFlag();
			return false;
		}
		
		var 
			itemcollectable = data.itemcollectable, 
			checked = $("input.itemcollectable[value=" + itemcollectable + "]:checked"), 
			quantity = checked.length, 
			game_id = $("#game_id").val(), 
			submittedCurrent = player_game_submitted.filter(function(obj) { return obj.ItemCollectable == itemcollectable; })[0],
			ajaxActions = { update: "_ajax_update_player_game_item_collectable", insert: "_ajax_insert_player_game_item_collectable" }
		;
		
		if (typeof updateInterval[itemcollectable] !== "undefined" && updateInterval[itemcollectable] !== (false || null)) 
			clearInterval(updateInterval[itemcollectable]); 
		
		// If not found in previously collected list
		// Insert this collectable into the player game item collected table
		if (!submittedCurrent) 
			var action = "insert";
			
		// Otherwise
		// Update this collectable in the player game item collected table
		else {
			var action = "update";
			
			log(submittedCurrent, false);
			
			// Find out if this is actually an update
			if (submittedCurrent.Quantity == quantity) {
				
				log("No change to Item Collectable! Cancelling update.");
				
				deactivateUpdatingFlag();
				
				return false;
			}
		}

		var data = { 
			_ajax_collectables_nonce: $( '#_ajax_collectables_nonce' ).val(), 
			action: ajaxActions[action], 
			itemcollectable: itemcollectable, 
			quantity: quantity, 
			game_id: game_id, 
		};
		
		//log(data, false);				
		
		updateInterval[itemcollectable] = setTimeout(function() {
			log((action == "update") ? "Updating..." : "Inserting...");

			$.ajax({
	        	
				url: ajaxurl,
				type: "POST",
				data: data,
	            
				success: function( response ) { 
					//log( response , false );
					var updatedLocally = false;
					var itemcollectable = data.itemcollectable;
					
					$.each(player_game_submitted, function() { 
						if (this.ItemCollectable == itemcollectable) { 
							this.Quantity = data.quantity;
							updatedLocally = true;
						}
					});

					if (updatedLocally == false)
						player_game_submitted.push({
							Collectable: itemcollectable,
							Quantity: data.quantity
						});
					
					deactivateUpdatingFlag();
					
					log( 'Item Collectable ' + itemcollectable + ' ' + ( (action == 'update') ? 'updated!' : 'inserted!' ) );
	            },
				
				error: function(response) {
					deactivateUpdatingFlag();
					error( 'Error ' + ((action == 'update') ? 'updating' : 'inserting') + ' Item Collectable ' + itemcollectable + '!' );
					error( response, false );
				}
			});
			
		}, settings.delay);
	};




	// Update Collectable

	var updateCollectable = function ( data ) { 
		if ( typeof data.collectable == "undefined" || !data.collectable ) {
			deactivateUpdatingFlag();
			return false;
		}
		
		var 
			collectable = data.collectable, 
			checked = $(".collectable[value=" + collectable + "]:checked"), 
			quantity = checked.length, 
			game_id = $("#game_id").val(), 
			collectableCurrent = player_game_collected.filter(function(obj) { return obj.Collectable == collectable; }),
			ajaxActions = { update: "_ajax_update_player_game_collectable", insert: "_ajax_insert_player_game_collectable" }
		;
		
		if (typeof updateInterval[collectable] !== "undefined" && updateInterval[collectable] !== false) 
			clearInterval(updateInterval[collectable]); 
		
		// If not found in previously collected list
		// Insert this collectable into the player game collected table
		if (!collectableCurrent.length) 
			var action = "insert";
			
		// Otherwise
		// Update this collectable in the player game collected table
		else {
			var action = "update";
			
			// Find out if this is actually an update
			if (collectableCurrent[0].Collected == quantity) {
				log("No change! Cancelling update.");
				deactivateUpdatingFlag();
				return false;
			}
		}

		var data = { 
			_ajax_collectables_nonce: $( '#_ajax_collectables_nonce' ).val(), 
			action: ajaxActions[action], 
			collectable: collectable, 
			quantity: quantity, 
			game_id: game_id, 
		};
		
		//log(data, false);				
		
		updateInterval[collectable] = setTimeout(function() {
			log((action == "update") ? "Updating..." : "Inserting...");

			$.ajax({
	        	
				url: ajaxurl,
				type: "POST",
				data: data,
	            
				success: function( response ) { 
					//log( response , false );
					var updatedLocally = false;
					
					$.each(player_game_collected, function() { 
						if (this.Collectable == data.collectable) { 
							this.Collected = data.quantity;
							updatedLocally = true;
						}
					});

					if (updatedLocally == false)
						player_game_collected.push({
							Collectable: data.collectable,
							Collected: data.quantity
						});
					
					deactivateUpdatingFlag();
					
					log((action == "update") ? "Updated!" : "Inserted!");
	            },
				
				error: function(response) {
					deactivateUpdatingFlag();
					error("Error " + ((action == "update") ? "updating!" : "inserting!"));
					error(response, false);
				}
			});
			
		}, settings.delay);
	};
	
	
	
	
	// 
	
	
	
	
	
	
	
	
	// Toggle Updating Flag
	var toggleUpdatingFlag = function() {
		$UpdatingFlag.toggleClass("top");
		$UpdatingFlag.toggleClass("show");
	};
	
	// Activate Updating Flag
	var activateUpdatingFlag = function() {
		$UpdatingFlag.addClass("top");
		$UpdatingFlag.addClass("show");
	};
	
	// Deactivate Updating Flag
	var deactivateUpdatingFlag = function() {
		$UpdatingFlag.removeClass("show");
		
		setTimeout(function() {
			$UpdatingFlag.removeClass("top");
		}, 300);
	};



	// Setup Tabs
	
	var setupTabs = function( tabElement ) {
		if (tabElement.length) {
			tabElement.easytabs({ 
				animate: false, 
				tabActiveClass: "nav-tab-active", 
				updateHash: false 
			});
			
			/*
			$(window).on('hashchange', function() {
				hashslice = location.hash.slice(1);
				
				if (hashslice.length > 0 && $("#" + hashslice, tabElement).length)
					tabElement.easytabs("select", hashslice);
				//else
				//	tabElement.easytabs("select", $("li:first a", tabElement).attr("href"));
			});
			*/
		}
	};
	
	
	
	
	// Setup Details/Summary elements
	
	var setupDetailSummary = function() {
		if (!$("details").length)
			return false;
		 	
		$('details').details();
		
		// Conditionally add a classname to the `<html>` element, based on native support
		$('html').addClass($.fn.details.support ? 'details' : 'no-details');
		
		$(document).on("keyup click", "summary", function(e) {
			$(window).trigger('resize').trigger('scroll');
		});
	};
	
	
	// Get Fields
	var getFields = function( input, field ) {
	    var output = [];
	    
	    for (var i=0; i < input.length ; ++i)
	        output.push( input[i][field] );
	        
	    return output;
	};
	
	
	// Postpone
	var postpone = function( func ) {
		if (typeof func !== "function")
			return false;
		
		window.setTimeout(func, 0);
	};

	
	var log = function(text, showPrefix) {
		if (logging !== true)
			return;
		
		var message = (showPrefix == false) ? text : logPrefix + ": " + text;
			
		console.log(message);
	};
	
	
	var error = function(text, showPrefix) {
		if (logging !== true)
			return;
			
		var message = (showPrefix == false) ? text : logPrefix + ": " + text;
				
			console.error(message);
		};
		
		
 
	    plugin.publicMethods = {

	    };

		plugin.init();

	};
  
  
  
  
  
  
  
  
  
	$.fn.tracky = function(options) {
		var args = arguments;

		return this.each(function() {
			var $this = $(this),
				plugin = $this.data('tracky');

		if (undefined === plugin) {
			plugin = new $.tracky(this, options);
			$this.data('tracky', plugin);
		}

		if ( plugin.publicMethods[options] )
			return plugin.publicMethods[options](Array.prototype.slice.call( args, 1 ));
		
		return;
	});

};


})( jQuery );
