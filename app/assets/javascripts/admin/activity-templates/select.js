/**
 * JavaScript handler for ActivityTemplates\Select.
 */
(function () {

	$('.select_option_radio').on('click', function(e) {
        if($(this).parent().hasClass('checked')){
			$(this).parent().removeClass('checked');
			$(this).removeAttr('checked');
		}
    });

	var ActivityTemplates = window.ActivityTemplates || (window.ActivityTemplates = {});

	var Option = Backbone.Model.extend({});

	var Options = Backbone.Collection.extend({
		model: Option
	});

	var OptionView = Backbone.View.extend({
		template: null,

		className: 'form-group answer-option',

		events: {
			'click .graded-option': 'gradeOption',
			'click .remove-option': 'remove'
		},

		initialize: function (options) {
			this.isFirst = options.isFirst;
			this.template = JST['admin/activity-templates/select-option-template'];
		},

		gradeOption: function () {
			this.model.set('graded', !this.model.get('graded'));
		},

		remove: function () {
			this.bindings.unbind();
			this.model.collection.remove(this.model);
			Backbone.View.prototype.remove.call(this);
		},

		render: function () {
			this.$el.html(this.template());
			this.bindings = rivets.bind(this.$el, { model: this.model });
			return this;
		}
	});

	ActivityTemplates.SelectView = Backbone.View.extend({
		events: {
			'click .add-option': 'createOption'
		},

		initialize: function () {
			this.views = [];

			if (this.model.has('options')) {
				// Use available options
				this.model.set('options', new Options(this.model.get('options')));

				this.collection = this.model.get('options');
			} else {
				this.collection = new Options();
				this.createOption();

				this.model.set('options', this.collection);
			}

			this.listenTo(this.collection, 'add', this.addOption);
			this.listenTo(this.collection, 'remove', this.removeOption);
			this.listenTo(this.collection, 'reset', this.addAllOptions);
		},

		createOption: function () {
			this.collection.add(new Option());
		},

		removeOption: function () {
		},

		removeOptions: function () {
			_.invoke(this.views, 'remove');
			this.views = [];
		},

		addAllOptions: function () {
			this.removeOptions();
			this.collection.forEach(_.bind(this.addOption, this));
		},

		addOption: function (option) {
			var optionView = new OptionView({
				model: option,
				isFirst: this.collection.indexOf(option) == 0
			});

			this.$('.options').append(optionView.render().el);

			this.views.push(optionView);
		},

		render: function () {
			this.addAllOptions();
			this.initSorting();
            this.bindings = rivets.bind(this.$('.select_descr, .select_expl'), {selectmodel: this.model});
		},

		initSorting: function () {
			this.$el.sortable({
				items: '.answer-option',

				update: _.bind(function () {
					this.updateSortingOrder();
				}, this)
			});			
		},

		updateSortingOrder: function () {
			_.each(this.views, function (view) {
				view.model.set('order', view.$el.index());
			});
		}
	});
})();
