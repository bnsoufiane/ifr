(function () {
	var Curotec = window.Curotec || (window.Curotec = {});

	// Models
	Curotec.YesNoOption = Curotec.Model.extend({
		defaults: {
			graded: 0
		},

		// Changes the current option grade status - cycle between the valid statuses.
		toggleGraded: function () {
			var availableStatuses = _.keys(this.constructor.GRADE_STATUSES);

			var nextStatus = availableStatuses.indexOf(this.get('graded').toString()) + 1;
			var newStatus = availableStatuses[nextStatus];

			if (typeof newStatus == 'undefined') {
				newStatus = availableStatuses[0];
			}

			this.set('graded', newStatus);
		}
	}, {
		GRADE_STATUSES: {
			0: {
				// Do not grade the question
				'class': 'off',
				icon: 'fa-circle-o',
				hint: 'Without a correct answer'
			},

			1: {
				// "No" is a correct answer
				'class': 'no',
				icon: 'fa-dot-circle-o',
				hint: '"No" is correct'
			},

			2: {
				// "Yes" is a correct answer
				'class': 'yes',
				icon: 'fa-check-circle',
				hint: '"Yes" is correct'
			}
		}
	});

	Curotec.YesNoOptions = Backbone.Collection.extend({
		model: Curotec.YesNoOption
	});

	// Section model & collection
	Curotec.YesNoSection = Curotec.Model.extend({
		optionsCollection: Curotec.YesNoOptions,

		parse: function (data) {
			var attrs = _.extend({}, data);
			if (attrs.options) {
				attrs.options = new this.optionsCollection(attrs.options);
			}
			return attrs;
		}
	});

	Curotec.YesNoSections = Backbone.Collection.extend({ model: Curotec.YesNoSection });
})();
