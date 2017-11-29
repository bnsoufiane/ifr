(function () {

	var ActivityTemplates = window.ActivityTemplates || (window.ActivityTemplates = {});

	var FillblankItem = Backbone.Model.extend({
		toJSON: function () {
			var data = _.extend({}, this.attributes);

			return data;
		}
	});

	var FillblankItems = Backbone.Collection.extend({ model: FillblankItem });

	var FillblankItemView = Backbone.View.extend({
		className: 'form-group',

		events: {
			'click .remove-option': 'removeItem'
		},

		initialize: function (options) {
			this.template = options.template;
		},

		render: function () {
			this.$el.html(this.template());

			this.bindings = rivets.bind(this.$el, { item: this.model });

			this.renderMaskedInput();

			return this;
		},

		renderMaskedInput: function () {
			this.$('input[name=employer-cost]')
				.inputmask({
					'alias': 'numeric',
					'groupSeparator': ',',
					'autoGroup': true,
					'digits': 2,
					'digitsOptional': false,
					'prefix': '$',
					'placeholder': '0'
				});
		},

		removeItem: function () {
			this.model.collection.remove(this.model);
			this.remove();
		},

		remove: function () {
			this.bindings.unbind();
			Backbone.View.prototype.remove.call(this);
		}
	});

	ActivityTemplates.FillblankView = Backbone.View.extend({
		events: {
			'click .add-new-item': 'createItem'
		},

		initialize: function () {
			this.itemTemplate = Handlebars.compile(this.$('#fillblank-item-template').html());

			if (this.model.has('items')) {
				this.collection = new FillblankItems(this.model.get('items'));
			} else {
				this.collection = new FillblankItems();
				this.createItem();
			}

			this.model.set('items', this.collection);

			this.listenTo(this.collection, 'add', this.renderItem);

			this.$items = this.$('.items');
		},

		createItem: function () {
			this.collection.add(new FillblankItem());
		},

		renderItem: function (model) {
			var view = new FillblankItemView({
				template: this.itemTemplate,
				model: model
			});

			this.$items.append(view.render().el);
		},

		addAllItems: function () {
			this.collection.each(_.bind(this.renderItem, this));
		},

		render: function () {
			this.addAllItems();

			this.bindings = rivets.bind(this.$('form:first'), { fillblank: this.model });
		},

		remove: function () {
			this.bindings.unbind();
			Backbone.View.prototype.remove.call(this);
		}
	});
})();
