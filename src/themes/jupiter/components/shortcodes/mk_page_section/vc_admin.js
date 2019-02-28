(function($) {
	'use strict';

	vc.element_start_index = 0, vc.AddElementUIPanelBackendEditor = vc.PanelView.vcExtendUI(vc.HelperPanelViewHeaderFooter).extend({
		el: "#vc_ui-panel-add-element",
		searchSelector: "#vc_elements_name_filter",
		prepend: !1,
		builder: "",
		events: {
			'click [data-vc-ui-element="button-close"]': "hide",
			'click [data-vc-ui-element="panel-tab-control"]': "filterElements",
			"click .vc_shortcode-link": "createElement",
			"keyup #vc_elements_name_filter": "filterElements",
			"search #vc_elements_name_filter": "filterElements",
			"click [data-vc-manage-elements]": "openPresetWindow"
		},
		initialize: function() {
			vc.AddElementUIPanelBackendEditor.__super__.initialize.call(this), vc.events.on("shortcodes:add", this.addCustomCssStyleTag.bind(this)), vc.events.on("vc:savePreset", this.updateAddElementPopUp.bind(this)), vc.events.on("vc:deletePreset", this.removePresetFromAddElementPopUp.bind(this))
		},
		render: function(model, prepend) {
			return _.isUndefined(vc.ShortcodesBuilder) || (this.builder = new vc.ShortcodesBuilder), this.$el.is(":hidden") && vc.closeActivePanel(), vc.active_panel = this, this.prepend = !!_.isBoolean(prepend) && prepend, this.place_after_id = !!_.isString(prepend) && prepend, this.model = !!_.isObject(model) && model, this.$content = this.$el.find('[data-vc-ui-element="panel-add-element-list"]'), this.$buttons = $('[data-vc-ui-element="add-element-button"]', this.$content), this.buildFiltering(), this.$el.find('[data-vc-ui-element="panel-tab-control"]').eq(0).click(), this.show(), this.$el.find('[data-vc-ui-element="panel-tabs-controls"]').vcTabsLine("moveTabs"), vc.is_mobile || $(this.searchSelector).focus(), vc.AddElementUIPanelBackendEditor.__super__.render.call(this)
		},
		buildFiltering: function() {
			var itemSelector, tag, notIn, asParent, parentSelector;
			itemSelector = '[data-vc-ui-element="add-element-button"]', notIn = this._getNotIn(this.model ? this.model.get("shortcode") : ""), $(this.searchSelector).val(""), this.$content.addClass("vc_filter-all"), this.$content.attr("data-vc-ui-filter", "*"), tag = this.model ? this.model.get("shortcode") : "vc_column", asParent = !(!tag || _.isUndefined(vc.getMapped(tag).as_parent)) && vc.getMapped(tag).as_parent, _.isObject(asParent) ? (parentSelector = [], _.isString(asParent.only) && parentSelector.push(_.reduce(asParent.only.replace(/\s/, "").split(","), function(memo, val) {
				return memo + (_.isEmpty(memo) ? "" : ",") + '[data-element="' + val.trim() + '"]'
			}, "")), _.isString(asParent.except) && parentSelector.push(_.reduce(asParent.except.replace(/\s/, "").split(","), function(memo, val) {
				return memo + ':not([data-element="' + val.trim() + '"])'
			}, "")), itemSelector += parentSelector.join(",")) : notIn && (itemSelector = notIn), !1 === tag || _.isUndefined(vc.getMapped(tag).allowed_container_element) || (!1 === vc.getMapped(tag).allowed_container_element ? itemSelector += ":not([data-is-container=true])" : _.isString(vc.getMapped(tag).allowed_container_element) && (itemSelector += ":not([data-is-container=true]), [data-element=" + vc.getMapped(tag).allowed_container_element + "]")), this.$buttons.removeClass("vc_visible").addClass("vc_inappropriate"), $(itemSelector, this.$content).removeClass("vc_inappropriate").addClass("vc_visible"), this.hideEmptyFilters()
		},
		hideEmptyFilters: function() {
			var _this = this;
			this.$el.find('[data-vc-ui-element="panel-add-element-tab"].vc_active').removeClass("vc_active"), this.$el.find('[data-vc-ui-element="panel-add-element-tab"]:first').addClass("vc_active"), this.$el.find("[data-filter]").each(function() {
				$($(this).data("filter") + ".vc_visible:not(.vc_inappropriate)", _this.$content).length ? $(this).parent().show() : $(this).parent().hide()
			})
		},
		_getNotIn: _.memoize(function(tag) {
			return '[data-vc-ui-element="add-element-button"]:not(' + _.reduce(vc.map, function(memo, shortcode) {
				var separator;
				return separator = _.isEmpty(memo) ? "" : ",", _.isObject(shortcode.as_child) ? (_.isString(shortcode.as_child.only) && (_.contains(shortcode.as_child.only.replace(/\s/, "").split(","), tag) || (memo += separator + "[data-element=" + shortcode.base + "]")), _.isString(shortcode.as_child.except) && _.contains(shortcode.as_child.except.replace(/\s/, "").split(","), tag) && (memo += separator + "[data-element=" + shortcode.base + "]")) : !1 === shortcode.as_child && (memo += separator + "[data-element=" + shortcode.base + "]"), memo
			}, "") + ")"
		}),
		filterElements: function(e) {
			_.isObject(e) ? e.preventDefault() && e.stopPropagation() : e = window.event;
			var filterValue, $visibleElements, $control = $(e.currentTarget),
			filter = '[data-vc-ui-element="add-element-button"]',
			nameFilter = $(this.searchSelector).val();
			this.$content.removeClass("vc_filter-all"), $('[data-vc-ui-element="panel-add-element-tab"].vc_active').removeClass("vc_active"), $control.is("[data-filter]") ? ($control.parent().addClass("vc_active"), filterValue = $control.data("filter"), filter += filterValue, "*" === filterValue ? this.$content.addClass("vc_filter-all") : this.$content.removeClass("vc_filter-all"), this.$content.attr("data-vc-ui-filter", filterValue.replace(".js-category-", "")), $(this.searchSelector).val("")) : nameFilter.length ? (filter += ":containsi('" + nameFilter + "'):not('.vc_element-deprecated')", this.$content.attr("data-vc-ui-filter", "name:" + nameFilter)) : nameFilter.length || ($('[data-vc-ui-element="panel-tab-control"][data-filter="*"]').parent().addClass("vc_active"), this.$content.attr("data-vc-ui-filter", "*").addClass("vc_filter-all")), $(".vc_visible", this.$content).removeClass("vc_visible"), $(filter, this.$content).addClass("vc_visible"), nameFilter.length && 13 === (e.keyCode || e.which) && ($visibleElements = $(".vc_visible:not(.vc_inappropriate)", this.$content), 1 === $visibleElements.length && $visibleElements.find("[data-vc-clickable]").click())
		},
		createElement: function(e) {
			e && e.preventDefault && e.preventDefault();
			var model, column, row, showSettings, row_params, inner_row_params, column_params, inner_column_params, tag, $control, preset, presetType, closestPreset;
			if ($control = $(e.currentTarget), tag = $control.data("tag"), row_params = {}, column_params = {
				width: "1/1"
			}, closestPreset = $control.closest("[data-preset]"), closestPreset && (preset = closestPreset.data("preset"), presetType = closestPreset.data("element")), !1 === this.model)
			if (vc.storage.lock(), "vc_section" === tag) {
				var modelOptions = {
					shortcode: tag
				};
				preset && "vc_section" === presetType && (modelOptions.preset = preset), model = vc.shortcodes.create(modelOptions)
			} else {
				var container_tag = ("vc_row" === tag || "mk_page_section" === tag) ? tag : "vc_row";
				var rowOptions = {
					shortcode: container_tag,
					params: row_params
				};
				preset && presetType === tag && (rowOptions.preset = preset), row = vc.shortcodes.create(rowOptions);
				var columnOptions = {
					shortcode: "vc_column",
					params: column_params,
					parent_id: row.id,
					root_id: row.id
				};
				if (preset && "vc_column" === presetType && (columnOptions.preset = preset), column = vc.shortcodes.create(columnOptions), model = row, container_tag !== tag) {
					var options = {
						shortcode: tag,
						parent_id: column.id,
						root_id: row.id
					};
					preset && presetType === tag && (options.preset = preset), model = vc.shortcodes.create(options)
				}
			}
			else if ("vc_row" === tag) "vc_section" === this.model.get("shortcode") ? (vc.storage.lock(), row = vc.shortcodes.create({
				shortcode: "vc_row",
				params: row_params,
				parent_id: this.model.id,
				order: this.prepend ? this.getFirstPositionIndex() : vc.shortcodes.getNextOrder()
			}), column = vc.shortcodes.create({
				shortcode: "vc_column",
				params: column_params,
				parent_id: row.id,
				root_id: row.id
			}), model = row) : (inner_row_params = {}, inner_column_params = {
				width: "1/1"
			}, vc.storage.lock(), row = vc.shortcodes.create({
				shortcode: "vc_row_inner",
				params: inner_row_params,
				parent_id: this.model.id,
				order: this.prepend ? this.getFirstPositionIndex() : vc.shortcodes.getNextOrder()
			}), column = vc.shortcodes.create({
				shortcode: "vc_column_inner",
				params: inner_column_params,
				parent_id: row.id,
				root_id: row.id
			}), model = row);
			else {
				var options = {
					shortcode: tag,
					parent_id: this.model.id,
					order: this.prepend ? this.getFirstPositionIndex() : vc.shortcodes.getNextOrder(),
					root_id: this.model.get("root_id")
				};
				preset && presetType === tag && (options.preset = preset), model = vc.shortcodes.create(options)
			}
			this.model = model, showSettings = !(_.isBoolean(vc.getMapped(tag).show_settings_on_create) && !1 === vc.getMapped(tag).show_settings_on_create), this.model.get("shortcode"), this.hide(), showSettings && this.showEditForm()
		},
		getFirstPositionIndex: function() {
			return vc.element_start_index -= 1, vc.element_start_index
		},
		show: function() {
			this.$el.addClass("vc_active"), this.trigger("show")
		},
		hide: function() {
			this.$el.removeClass("vc_active"), vc.active_panel = !1, this.trigger("hide")
		},
		showEditForm: function() {
			vc.edit_element_block_view.render(this.model)
		},
		addCustomCssStyleTag: function(model) {
			if (model && model.getParam) {
				var customCss;
				customCss = model.getParam("css"), customCss && vc.frame_window && vc.frame_window.vc_iframe.setCustomShortcodeCss(customCss)
			}
		},
		updateAddElementPopUp: function(id, shortcode, title, data) {
			var $presetShortcode = this.$el.find('[data-element="' + shortcode + '"]:first'),
			$newPreset = $presetShortcode.clone(!0);
			vc_all_presets[id] = data, $newPreset.find("[data-vc-shortcode-name]").text(title), $newPreset.find(".vc_element-description").text(""), $newPreset.attr("data-preset", id), $newPreset.addClass("js-category-_my_elements_"), $newPreset.insertAfter(this.$el.find('[data-element="' + shortcode + '"]:last')), this.$el.find('[data-filter="js-category-_my_elements_"]').show();
			var $samplePreset = this.$body.find('[data-vc-ui-element="panel-preset"] [data-vc-presets-list-content] .vc_ui-template:first'),
			$newPreset = $samplePreset.clone(!0);
			$newPreset.find('[data-vc-ui-element="template-title"]').attr("title", title).text(title), $newPreset.find('[data-vc-ui-delete="preset-title"]').attr("data-preset", id).attr("data-preset-parent", shortcode), $newPreset.find("[data-vc-ui-add-preset]").attr("data-preset", id).attr("id", shortcode).attr("data-tag", shortcode), $newPreset.show(), $newPreset.insertAfter(this.$body.find('[data-vc-ui-element="panel-preset"] [data-vc-presets-list-content] .vc_ui-template:last'))
		},
		removePresetFromAddElementPopUp: function(id) {
			this.$el.find('[data-preset="' + id + '"]').remove()
		},
		openPresetWindow: function(e) {
			e && e.preventDefault && e.preventDefault(), vc.preset_panel_view.render().show()
		}
	})
		
})(jQuery);