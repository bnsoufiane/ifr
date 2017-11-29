(function () {
	window.Curotec || (window.Curotec = {});

	var Row = Backbone.View.extend({
		tagName: 'tr',

		events: {
			'click td': 'selectRow'
		},
		
		initialize: function (options) {
			this.template = options.template;
		},

		selectRow: function () {
			var prevValue = this.model.get('selected');
			var isSelected = !prevValue;

			this.model.set('selected', isSelected);

			this.$el[isSelected ? 'addClass' : 'removeClass']('selected');
			this.$('input[type=checkbox]').prop('checked', isSelected);
		},

		render: function () {
			this.$el.html(this.template({
				model: this.model.toJSON()
			}));
			return this;
		}
	});

	Curotec.SelectorModal = Backbone.View.extend({
		className: 'modal fade',
		
		events: {
			'click .btn-primary': 'choose'
		},
		
		initialize: function (options) {
			this.rows = [];

			this.selectedCollection = options.selectedCollection;

			this.template = options.template;
			this.rowTemplate = options.rowTemplate;

			this.listenTo(this.collection, 'add', this.addOne);
			this.listenTo(this.collection, 'reset', this.addAll);
		},

		addAll: function () {
			this.collection.each(_.bind(this.addOne, this));
		},

		addOne: function (model) {
			var row = new Row({
				model: model,
				template: this.rowTemplate
			});

			this.$('table > tbody')
				.append(row.render().el);

			this.rows.push(row);
		},

		cleanRows: function () {
			_.invoke(this.rows, 'remove');
		},

		choose: function () {
			var chosenModels = this.$('input[type=checkbox]:checked')
					.map(_.bind(function (i, el) {
						return this.collection.get(el.value);
					}, this));

			// Add chosen models to a collection.
			_.each(chosenModels, _.bind(function (model) {
				this.selectedCollection.add(model);
			}, this));

			// Close the modal
			this.$el.modal('hide');
		},

		render: function () {
			this.$el.html(this.template())
				.appendTo($('body'))
				.modal();

			// Fetch a list from the server
			this.addAll();
			this.collection.fetch();
		}
	});
})();
