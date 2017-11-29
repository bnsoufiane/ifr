(function () {

	var ActivityTemplates = window.ActivityTemplates || (window.ActivityTemplates = {});
	var Curotec = window.Curotec || (window.Curotec = {});

	ActivityTemplates.YesNoView = Backbone.View.extend({
		events: {
			'click .add-section': 'createSection'
		},

		initialize: function () {
			this.initSectionsCollection();
			this.initTemplates();
			this.initEvents();

			this.$sections = this.$('.sections');
		},

		initSectionsCollection: function () {
			this.collection = this.createSectionsCollection(this.model.get('sections'));
			this.model.set('sections', this.collection);
		},

		initTemplates: function () {
			this.sectionTemplate = JST['admin/activity-templates/yesno/section-template'];
			this.optionTemplate = JST['admin/activity-templates/yesno/option-template'];
		},

		initEvents: function () {
			this.listenTo(this.collection, 'add', this.renderSection);
		},

		createSection: function () {
			if (!this.counter) {
				this.counter = 0;
			}

			this.counter += 1;

			this.collection.add(new this.collection.model({
				title: 'Section ' + this.counter
			}));
		},

		createSectionsCollection: function (models) {
			return new Curotec.YesNoSections(models, {
				parse: true
			});
		},

		createSectionView: function (model) {
			return new Curotec.YesNoSectionView({
				model: model,
				template: this.sectionTemplate,
				optionTemplate: this.optionTemplate
			});
		},

		renderSection: function (model) {
			var view = this.createSectionView(model);
			this.$sections.append(view.render().el);
		},

		renderAllSections: function () {
			this.collection.each(_.bind(this.renderSection, this));
		},

		render: function () {
			this.renderAllSections();
			if (!this.bindings) {
				this.bindings = rivets.bind(
					this.$('form:first'),
					{ yesno: this.model }
				);
			}
			return this;
		},

		remove: function () {
			this.bindings.unbind();
			Backbone.View.prototype.remove.call(this);
		}
	});

})();
