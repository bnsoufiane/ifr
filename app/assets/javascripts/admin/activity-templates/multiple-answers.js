(function () {
	var ActivityTemplates = (window.ActivityTemplates || (window.ActivityTemplates = {}));

	var PlaceholderAnswers = Backbone.Collection.extend({
		toJSON: function () {
			return JSON.stringify(
				this.map(function (model) { return model.get('value'); })
			);
		}
	});

	var PlaceholderAnswerView = Backbone.View.extend({
		className: 'form-group',

		render: function () {
			this.$el.html(JST['admin/activity-templates/multiple-answers-item-template']);
			this.binding = rivets.bind(this.$el, { model: this.model });
			return this;
		},

		remove: function () {
			this.binding.unbind();
			Backbone.View.prototype.remove.call(this);
		}
	});

	ActivityTemplates.MultipleAnswersView = Backbone.View.extend({
		initialize: function () {
			this.fieldsPlaceholders = [];
			this.placeholderAnswers = new PlaceholderAnswers(this.getPlaceholders());

			this.model.set('placeholder_answers', this.placeholderAnswers);

			this.listenTo(this.model, 'change:number_of_fields', this.renderFields);
		},

		getPlaceholders: function () {
			var placeholders = this.model.get('placeholder_answers');

			if (placeholders) {
				try {
					return JSON.parse(placeholders).map(function (val) { return { value: val }; });
				} catch (e) {
					return [];
				}
			} else {
				return [];
			}
		},

		renderFields: function () {
			var numberOfFields = this.model.get('number_of_fields');
			var fields = this.$('.fields-presets');

			this.clearFields();

			for (var i = 0; i < numberOfFields; i++) {
				// Create placeholder fields.
				var model = this.placeholderAnswers.at(i);

				if (model == null) {
					model = new Backbone.Model({ value: '' });
					this.placeholderAnswers.add(model);
				}

				var view = new PlaceholderAnswerView({ model: model });
				fields.append(view.el);

				this.fieldsPlaceholders.push(view.render());
			}
		},

		clearFields: function () {
			_.invoke(this.fieldsPlaceholders, 'remove');
			this.fieldsPlaceholders = [];
		},

		render: function () {
			this.bindings = rivets.bind(this.$el, { model: this.model });
			this.renderFields();
			return this;
		},

		remove: function () {
			this.bindings.unbind();
			Backbone.View.prototype.remove.call(this);
		}
	});
})();
