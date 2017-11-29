(function () {

	var Curotec = window.Curotec || (window.Curotec = {});

	// Views
	var YesNoOptionView = Curotec.YesNoOptionView = Backbone.View.extend({
		tagName: 'li',

		events: {
			'click .remove-option': 'removeOption',

			'click .graded-option': function () {
				this.model.toggleGraded();
			}
		},

		initialize: function (options) {
			this.template = options.template;
			this.initEvents();
		},

		initEvents: function () {
			this.listenTo(this.model, 'change:graded', this.renderGradeStatus);
		},

		render: function () {
			this.$el.html(
				this.template({ option: this.model })
			);
			this.$gradeStatus = this.$('.graded-option');

			this.renderGradeStatus({ noUpdateTooltip: true });

			this.bindings = rivets.bind(this.$el, { option: this.model });

			return this;
		},

		renderGradeStatus: function (options) {
			var className = this.$gradeStatus.attr('class');
			var gradeStatus = this.model.constructor.GRADE_STATUSES[this.model.get('graded')];

			className = className.replace(/graded-status-(\w+)/,
										  'graded-status-' + gradeStatus['class']);

			this.$gradeStatus.attr('class', className)
				.attr('title', gradeStatus.hint).tooltip('fixTitle')
				.find('i:first').attr('class', 'fa ' + gradeStatus.icon);

			if (!options || !options.noUpdateTooltip) {
				this.$gradeStatus.tooltip('show');
			}
		},

		removeOption: function () {
			this.model.collection.remove(this.model);
			this.remove();
		},

		remove: function () {
			this.bindings.unbind();
			Backbone.View.prototype.remove.call(this);
		}
	});

	// Encapsulates section title editor
	var SectionTitleView = Curotec.SectionTitleView = Backbone.View.extend({
		events: {
			'click .edit-title': 'showEditor',
			'click .accept-edit-title': 'acceptEdit',
			'click .cancel-edit-title': 'cancelEdit',

			'keydown input[name=title]': function (e) {
				if (e.keyCode == 13) {
					this.acceptEdit();
					return false;
				} else if (e.keyCode == 27) {
					this.cancelEdit();
				}
				return true;
			}
		},

		initialize: function () {
			this.template = JST['admin/activity-templates/yesno/section-title-template'];
		},

		render: function () {
			this.$el.html(this.template());

			this.$titleHeader = this.$('.title-header');
			this.$titleEditor = this.$('.title-editor');
			this.$input = this.$titleEditor.find('input[name=title]');
		},

		hideEditor: function () {
			this.$titleHeader.show();
			this.$titleEditor.hide();
		},

		showEditor: function () {
			this.$titleHeader.hide();
			this.$titleEditor.show();

			this.$input.focus()
				.val(this.model.get('title'));
		},

		acceptEdit: function () {
			this.model.set('title', this.$input.val());
			this.hideEditor();
		},

		cancelEdit: function () {
			this.hideEditor();
		}
	});

	var YesNoSectionView = Curotec.YesNoSectionView = Backbone.View.extend({
		tagName: 'section',
		className: 'yesno-section',

		events: {
			'click .add-new-item': 'addNewOption',
			'click .remove-section': 'removeSection'
		},

		initialize: function (options) {
			this.template = options.template;
			this.optionTemplate = options.optionTemplate;

			if (this.model.has('options')) {
				this.collection = this.model.get('options');
			} else {
				this.collection = this.createOptionsCollection();
			}

			this.model.set('options', this.collection);

			this.listenTo(this.collection, 'add', this.renderOption);
			this.listenTo(this.collection, 'reset', this.renderOptions);
		},

		createOptionsCollection: function () {
			return new Curotec.YesNoOptions();
		},

		addNewOption: function () {
			this.collection.add(new this.collection.model());
		},

		render: function () {
			this.renderTemplate();
			this.renderTitle();
			this.bindings = rivets.bind(this.$el, { section: this.model });

			this.$options = this.$('.options');

			this.renderOptions();

			return this;
		},

		renderTemplate: function () {
			this.$el.html(
				this.template({ section: this.model })
			);
		},

		renderTitle: function () {
			this.titleView = new SectionTitleView({
				model: this.model,
				el: this.$('.title')
			});
			this.titleView.render();
		},

		createOptionView: function (model) {
			return new YesNoOptionView({
				model: model,
				template: this.optionTemplate
			});
		},

		renderOption: function (model) {
			var view = this.createOptionView(model);
			this.$options.append(view.render().el);
		},

		renderOptions: function () {
			this.collection.each(
				_.bind(this.renderOption, this)
			);
		},

		removeSection: function () {
			if (this.collection.length == 0 ||
				confirm('Are you sure that you want to remove this section?')) {
				this.model.collection.remove(this.model);
				this.remove();
			}
		},

		remove: function () {
			this.bindings.unbind();
			Backbone.View.prototype.remove.call(this);
		}
	});

})();
