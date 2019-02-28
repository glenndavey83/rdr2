
// Javascript document

// RDR2 Progress Tracker
// Plugin
// Frontend Scripting


(function($) {
	
	$(document).ready(function() {
		
		var 
			GamesPortalContainer = $("#PlayerGamesPortal"),
			ItemsPortalContainer = $("#PlayerItemsPortal"),
			RandomTip = $("#RandomTip"),
			$tabsContainer = $(".luckytabs")
		;
		
		// Tell CSS that JS is present
		$("html").removeClass("no-js");
		
		collection_locations = JSON.parse(database.collection_locations);
		collections = JSON.parse(database.collections);
		collection_groups = JSON.parse(database.collection_groups);
		collection_collectables = JSON.parse(database.collection_collectables);
		collection_items = JSON.parse(database.collection_items);
		collection_item_collectables = JSON.parse(database.collection_item_collectables);
		
		ingredients = JSON.parse(database.ingredients);
		ingredient_types = JSON.parse(database.ingredient_types);
		ingredient_qualities = JSON.parse(database.ingredient_qualities);
		ingredient_parts = JSON.parse(database.ingredient_parts);
		
		player_game_items = JSON.parse(database.player_game_items);
		player_game_collected = JSON.parse(database.player_game_collected);
		
		
		// Items Portal
		if (ItemsPortalContainer.length)
			ItemsPortalContainer.itemizely();

		// Games Portal 
		if (GamesPortalContainer.length)
			GamesPortalContainer.filtery();		
		
		// Random Tips
		if (RandomTip.length)
			RandomTip.tippy();

	});



	// ITEMIZELY Plugin
	
	( function($) {
	
		$.itemizely = function( container, options ) {
	
			var plugin = this,
				$container = $( container );
	        
			var 
				settings, 
				timer, 
				delay, 
				staticData, 
				navTabActive, 
				locationID, 
				tabNum = 0,
				delay = 2500, 
				navTabs = "",
				tabs = '',
				updateInterval = {},
				updatingFlag = "<div class='updating-flag'><span class='updating-label'>Updating...</span></div>"
			;
	
			var defaults = {
				delay: delay,
			};
	    
			plugin.init = function( ) {
	 
				plugin.settings = settings = $.extend({}, defaults, options);
			
				$container.data('itemizely', {});
				
				var data = {
					game_id: $("#game_id").val(),
				};

				var tabsWrapper = showByLocation();

		    };	
			
			
			// Prints by Location first
			
			var showByLocation = function() {
				locationIcons = {
					1: "fas fa-campground",
					2: "fas fa-dollar-sign",
					3: "far fa-envelope",
					4: "fas fa-paw",
				};
				
				for (var l = 0; l < collection_locations.length; l++) {
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
								detailsContent += "<ul>";
								
								for (var g = 0; g < collectionGroups.length; g++) {
									var itemsContent = "";
									
									// Items
									var groupItems = collection_items.filter(function(obj) { return obj.Group == collectionGroups[g].ID; });
									
									if (groupItems.length) {
										itemsContent += "<ul>";
										
										for (var i = 0; i < groupItems.length; i++) {
											var itemCollectablesContent = "";
											
											// Item Collectables
											var itemCollectables = collection_item_collectables.filter(function(obj) { return obj.Item == groupItems[i].ID; });
											
											if (itemCollectables.length) {
												itemCollectablesContent += "<ul>";
												
												for (var s = 0; s < itemCollectables.length; s++) { 
													
													// Collection Collectables
													var collectionCollectable = collection_collectables.filter(function(obj) { return obj.ID == itemCollectables[s].Collectable; })[0];
													var ingredient = ingredients.filter(function(obj) { return obj.ID == collectionCollectable.Ingredient; })[0];
													var quality = ingredient_qualities.filter(function(obj) { return obj.ID == collectionCollectable.Quality; })[0];
													var part = ingredient_parts.filter(function(obj) { return obj.ID == collectionCollectable.Part; })[0];
													itemCollectablesContent += "<li>" + quality.Name + " " + ingredient.Name + " " + part.Name + " x " + itemCollectables[s].Quantity + "</li>";
												}
												
												itemCollectablesContent += "</ul>";
											}
											
											itemsContent += "<li><label><input type='checkbox' class='collectable-item' name='items' value='" + groupItems[i].ID + "' > " + groupItems[i].Name + "</label> " + itemCollectablesContent + "</li>";
										}
										
										itemsContent += "</ul>";
									}
									
									detailsContent += "<li>" + collectionGroups[g].Name + itemsContent + "</li>";
								}
								
								detailsContent += "</ul>";
							}
							
							tabContent += '<details><summary>' + locationCollections[c].Name + '</summary> ' + detailsContent + '</details>';
						}
						
					}
					
					tabs += '<div class="location-tab luckytab" id="location-' + collection_locations[l].Name + '"><h3 class="screen-reader-text">' + collection_locations[l].Name + '</h3><div class="tabContent">' + tabContent + '</div></div>';					
				}
				
				var tabsWrapper = '<div id="location-tabs" class="tabs-container luckytabs toptabs"><ul class="pill location-selector nav-tab-wrapper">' + navTabs + '</ul>' + tabs + '</div>';	 				
				
				$container.html(tabsWrapper);
						
				// Tabs
				var tabElement = $("#location-tabs");
				setupTabs(tabElement);
				
				// Details and Summary		
				setupDetailSummary();
			};
			
				
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
			
			
			
			var getDistinct = function( array, key, value ) {
				if (!array || !key || !value)
					return false;
				
				var result = [];
				var map = new Map();
				
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
			
			
			
			var sumColumn = function sumColumn(array, col) {
			    var sum = 0;
			    array.forEach(function (value, index, array) {
			        sum += parseInt(value[col]);
			    });
			    return sum;
			};
			
			
			var toggleUpdating = function() {
				$(".updating-flag").toggaleClass("show");
			};
			
			var activateUpdatingFlag = function() {
				$(".updating-flag").addClass("show");
			};
			
			var deactivateUpdatingFlag = function() {
				$(".updating-flag").removeClass("show");
			};
			
			
	
			
			var getFields = function(input, field) {
			    var output = [];
			    
			    for (var i=0; i < input.length ; ++i)
			        output.push(input[i][field]);
			        
			    return output;
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
			
		    
		    
		    
			// This is where we send the latest player game form data to the database
			
		    plugin.update = function( data ) {
				
				updateCollectable( data ) ;
				
			};
	
	
			var updateCollectable = function ( data ) { 
				if ( typeof data.collectable == "undefined" || !data.collectable ) {
					deactivateUpdatingFlag();
					return false;
				}
				
				var collectable = data.collectable; 
				var checked = $(".collectable[value=" + collectable + "]:checked"); 
				var quantity = checked.length; 
				var game_id = $("#game_id").val(); 
				var collectableCurrent = player_game_collected.filter(function(obj) { return obj.Collectable == collectable; });
				var ajaxActions = { update: "_ajax_update_player_game_collectable", insert: "_ajax_insert_player_game_collectable" };
				
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
						console.log("No change! Cancelling update.");
						deactivateUpdatingFlag();
						return false;
					}
				}
	
				var data = { 
					_ajax_collectables_nonce: $( '#_ajax_update_collectables_nonce' ).val(), 
					action: ajaxActions[action], 
					collectable: collectable, 
					quantity: quantity, 
					game_id: game_id, 
				};
				
				
				updateInterval[collectable] = setTimeout(function() {
					console.log((action == "update") ? "Updating..." : "Inserting...");
					
					$.ajax({
			        	
						url: ajaxurl,
						type: "POST",
						data: data,
			            
						success: function( response ) {
							deactivateUpdatingFlag();
							console.log((action == "update") ? "Updated!" : "Inserted!");
							
							var updatedLocally = false;
							
							$.each(player_game_collected, function() { 
								if (this.Collectable === data.collectable) { 
									this.Collected = data.quantity;
									updatedLocally = true;
								}
							});
	
							if (updatedLocally == false)
								player_game_collected.push({
									Collectable: data.collectable,
									Collected: data.quantity
								});
			            },
						
						error: function(response) {
							deactivateUpdatingFlag();
							console.error("Error " + ((action == "update") ? "Updating..." : "Inserting..."));
							console.error(response);
						}
					});
					
				}, settings.delay);
			};
			
	 
		    plugin.publicMethods = {
	
		    };
	
			plugin.init();
	
		};
	  
	  
		$.fn.itemizely = function(options) {
			var args = arguments;
	
			return this.each(function() {
				var $this = $(this),
					plugin = $this.data('itemizely');
	
				if (undefined === plugin) {
					plugin = new $.itemizely(this, options);
					$this.data('itemizely', plugin);
				}
		
				if ( plugin.publicMethods[options] )
					return plugin.publicMethods[options](Array.prototype.slice.call( args, 1 ));
	
			});
		
		};
	
	})( jQuery );
	
	
	
	
	
	
	
	
	// FILTERY Plugin
	
	( function($) {
	
		$.filtery = function( container, options ) {
	
			var plugin = this,
				$container = $( container );
	        
			var 
				settings, 
				timer, 
				delay, 
				staticData, 
				navTabActive, 
				locationID, 
				tabNum = 0,
				delay = 1500, 
				navTabs = "",
				tabs = '',
				updateInterval = {},
				updatingFlag = "<div class='updating-flag'><span class='updating-label'>Updating...</span></div>"
			;
	
			var defaults = {
				delay: 2500,
			};
	    
			plugin.init = function( ) {
	 
				plugin.settings = settings = $.extend({}, defaults, options);
			
				$container.data('filtery', {});
				
				var data = {
					game_id: $("#game_id").val(),
				};

				var tabsWrapper = showByIngredientType( data );
	
		    };	
			
			
			// Prints out list of Collectables by Ingredient type
			var showByIngredientType = function( data ) {
				var typeIcons = {
					1: "fas fa-hippo",
					2: "fas fa-crow",
					3: "fas fa-fish",
					4: "far fa-gem",
					5: "far fa-snowflake",
				};
				
				var collectablesContent = "<h3>Collectables</h3>";
				
				// Ingredient Types
				for (var t = 0; t < ingredient_types.length; t++) {
					var tabContent = "";
					
					navTabs += '<li class="nav-tab"><a href="#type-' + ingredient_types[t].Plural + '" class="" data-typeid="' + ingredient_types[t].ID + '" data-tabid="' + t + '"><i class="' + typeIcons[ingredient_types[t].ID] + '"></i>' + ingredient_types[t].Plural + '</a></li>';
					
					// Type Ingredients
					var typeIngredients = ingredients.filter(function(obj) { return obj.Type == ingredient_types[t].ID; });
					
					if (typeIngredients.length) {
						
						for (var n = 0; n < typeIngredients.length; n++) {
							var ingredientsContent = "";
							var detailsContent = "";
							// Collectable Ingredients
							var collectionCollectables = collection_collectables.filter(function(obj) { return obj.Ingredient == typeIngredients[n].ID; });
							
							for (var c = 0; c < collectionCollectables.length; c++) {
								var ingredient = ingredients.filter(function(obj) { return obj.ID == collectionCollectables[c].Ingredient; });
								var part = ingredient_parts.filter(function(obj) { return obj.ID == collectionCollectables[c].Part; })[0];
								var quality = ingredient_qualities.filter(function(obj) { return obj.ID == collectionCollectables[c].Quality; })[0];
								
								// Get total quantity needed (the real value starts here)
								var collectableItems = collection_item_collectables.filter(function(obj) { return obj.Collectable == collectionCollectables[c].ID; });
								var quantity = (collectionCollectables[c].Quality == 2) ? 1 : sumColumn(collectableItems, "Quantity");
								var collectedCollectable = player_game_collected.filter(function(obj) { return obj.Collectable == collectionCollectables[c].ID; })[0];
								var quantityCollected = (typeof collectedCollectable !== "undefined") ? collectedCollectable.Collected : 0;
								
								detailsContent += "<ul><li><label>" + quantity + " x " + quality.Name + " " + ingredient[0].Name + " " + part.Name + "</label> ";
							
								// For each quantity of collectable
								for (var q = 0; q < quantity; q++) {
									var checkedVal = (q < quantityCollected) ? "checked " : "";			
									detailsContent += "<label class='DeadCheckbox'> <span class='dashicons dashicons-yes'></span> <input type='checkbox' name='collectables' class='collectable' value='" + collectionCollectables[c].ID + "' " + checkedVal + "> </label>";
								}
								
								detailsContent += "</li></ul>";
							}
							
							ingredientsContent += '<details><summary>' + ingredient[0].Name + '</summary> ' + detailsContent + '</details>';
							
							tabContent += ingredientsContent;
						}
						
					}
					
					tabs += '<div class="ingredient-type-tab luckytab" id="type-' + ingredient_types[t].Plural + '"><h3 class="screen-reader-text">' + ingredient_types[t].Plural + '</h3><div class="tabContent">' + tabContent + '</div></div>';
				}
				
				var tabsWrapper = '<div id="collectable-tabs" class="tabs-container luckytabs toptabs"><ul class="pill location-selector nav-tab-wrapper">' + navTabs + '</ul>' + tabs + '</div>';	 				
				
				collectablesContent += tabsWrapper;
				
				$container.html(collectablesContent + updatingFlag);
				
				$(".DeadCheckbox", $container).each(function() { 
					if ($("input", this).prop('checked'))
						$(this).addClass("checked");
				});
				
				$("input", $(".DeadCheckbox")).on("change", function(e) { 
					if ($(this).prop('checked'))					
						$(this).parent().addClass("checked");
					else
						$(this).parent().removeClass("checked");
				});
						
				// Tabs
				var tabElement = $("#collectable-tabs");
				setupTabs(tabElement);
				
				// Details and Summary		
				setupDetailSummary();
				
				
				// Setup checkbox actions
				$(".collectable", $container).on("change", function() {
					
					activateUpdatingFlag();
					
					clicked = {
						collectable: $(this).val()
					};
					
					data = $.extend( data, clicked );
					
					plugin.update( data );
				});
			};
			
			
			
			var getDistinct = function( array, key, value ) {
				if (!array || !key || !value)
					return false;
				
				var result = [];
				var map = new Map();
				
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
			
			
			
			var sumColumn = function sumColumn(array, col) {
			    var sum = 0;
			    array.forEach(function (value, index, array) {
			        sum += parseInt(value[col]);
			    });
			    return sum;
			};
			
			
			var toggleUpdating = function() {
				$(".updating-flag").toggaleClass("show");
			};
			
			var activateUpdatingFlag = function() {
				$(".updating-flag").addClass("show");
			};
			
			var deactivateUpdatingFlag = function() {
				$(".updating-flag").removeClass("show");
			};
			
			
	
			
			var getFields = function(input, field) {
			    var output = [];
			    
			    for (var i=0; i < input.length ; ++i)
			        output.push(input[i][field]);
			        
			    return output;
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
			
		    
		    
		    
			// This is where we send the latest player game form data to the database
			
		    plugin.update = function( data ) {
				
				updateCollectable( data ) ;
				
				//updateCollectables( data );
				
			};
	
	
			var updateCollectable = function ( data ) { 
				if ( typeof data.collectable == "undefined" || !data.collectable ) {
					deactivateUpdatingFlag();
					return false;
				}
				
				var collectable = data.collectable; 
				var checked = $(".collectable[value=" + collectable + "]:checked"); 
				var quantity = checked.length; 
				var game_id = $("#game_id").val(); 
				var collectableCurrent = player_game_collected.filter(function(obj) { return obj.Collectable == collectable; });
				var ajaxActions = { update: "_ajax_update_player_game_collectable", insert: "_ajax_insert_player_game_collectable" };
				
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
						console.log("No change! Cancelling update.");
						deactivateUpdatingFlag();
						return false;
					}
				}
	
				var data = { 
					_ajax_collectables_nonce: $( '#_ajax_update_collectables_nonce' ).val(), 
					action: ajaxActions[action], 
					collectable: collectable, 
					quantity: quantity, 
					game_id: game_id, 
				};
				
				
				updateInterval[collectable] = setTimeout(function() {
					console.log((action == "update") ? "Updating..." : "Inserting...");
					
					$.ajax({
			        	
						url: ajaxurl,
						type: "POST",
						data: data,
			            
						success: function( response ) {
							deactivateUpdatingFlag();
							console.log((action == "update") ? "Updated!" : "Inserted!");
							
							var updatedLocally = false;
							
							$.each(player_game_collected, function() { 
								if (this.Collectable === data.collectable) { 
									this.Collected = data.quantity;
									updatedLocally = true;
								}
							});
	
							if (updatedLocally == false)
								player_game_collected.push({
									Collectable: data.collectable,
									Collected: data.quantity
								});
			            },
						
						error: function(response) {
							deactivateUpdatingFlag();
							console.error("Error " + ((action == "update") ? "Updating..." : "Inserting..."));
							console.error(response);
						}
					});
					
				}, settings.delay);
			};
			
			
			
			var updateCollectables = function ( data ) {
				if (updateInterval !== false)
					clearInterval(updateInterval);
				
				var formResults = $("#collectables-filter").serializeArray();
				var game_id = $("#game_id").val();
				
				// Distinct values with counts
				var collectables = formResults.filter(function(obj) { return obj.name == "collectables"; });
				collectables = getFields(collectables, "value");
				
				var data = {
					_ajax_collectables_nonce: $( '#_ajax_update_collectables_nonce' ).val(), 
					action: "_ajax_update_player_game_collectables", 
					collectables: collectables, 
					collected: player_game_collected,
					game_id: game_id, 
				};
				
				updateInterval = setTimeout(function() {
					console.log("Updating...");
					
					$.ajax({
			        	
						url: ajaxurl,
						type: "POST",
						data: data,
			            
						success: function( response ) {
							console.log("Updated!");
							//console.log(response);
							player_game_collected = JSON.parse(response);
							//console.log(player_game_collected);
							deactivateUpdating();
			            },
						
						error: function(response) {
							console.error(response);
							deactivateUpdating();
						}
		            
					});
				}, settings.delay);
			};
			
			
	 
		    plugin.publicMethods = {
	
		    };
	
			plugin.init();
	
		};
	  
	  
		$.fn.filtery = function(options) {
			var args = arguments;
	
			return this.each(function() {
				var $this = $(this),
					plugin = $this.data('filtery');
	
				if (undefined === plugin) {
					plugin = new $.filtery(this, options);
					$this.data('filtery', plugin);
				}
		
				if ( plugin.publicMethods[options] )
					return plugin.publicMethods[options](Array.prototype.slice.call( args, 1 ));
	
			});
		
		};
	
	})( jQuery );
	
	
	
	
	
	
	
	
	
	
	
	
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
			    	$container
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
	
	
	
	function setupTabs( tabElement ) {
		if (tabElement.length) {
			tabElement.easytabs({ 
				animate: true, 
				tabActiveClass: "nav-tab-active", 
				updateHash: true 
			});
		
			$(window).on('hashchange', function() {
				hashslice = location.hash.slice(1);
				
				if (hashslice.length > 0 && $("#" + hashslice, tabElement).length)
					tabElement.easytabs("select", hashslice);
				//else
				//	tabElement.easytabs("select", $("li:first a", tabElement).attr("href"));
			});
		}
	}
	
	function setupDetailSummary() {
		if (!$("details").length)
			return false;
		 	
		$('details').details();
		
		// Conditionally add a classname to the `<html>` element, based on native support
		$('html').addClass($.fn.details.support ? 'details' : 'no-details');
		
		$(document).on("keyup click", "summary", function(e) {
			$(window).trigger('resize').trigger('scroll');
		});
	}
	
	// Postpone
	function postpone(func) {
		if (typeof func !== "function")
			return false;
		
		window.setTimeout(func, 0);
	}
	
	Array.prototype.unique = function() {
		return this.filter(function (value, index, self) { 
			return self.indexOf(value) === index;
		});
	};


} )( jQuery );








