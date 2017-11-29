(function () {
	var Curotec = window.Curotec || (window.Curotec = {});

	// Options
	Curotec.TrueFalseOption = Curotec.Model.extend({
		defaults: {
			graded: 0
		},

		toggleGraded: function () {

            var availableStatuses = _.keys(this.constructor.GRADE_STATUSES);

            var nextStatus = availableStatuses.indexOf(this.get('graded').toString()) + 1;
            var newStatus = availableStatuses[nextStatus];

            if (typeof newStatus == 'undefined') {
                newStatus = availableStatuses[0];
            }

            this.set('graded', newStatus);

			/* //radio buttons, when one is marked as true the others are marked as false
			// Mark all other options as incorrect.
			this.collection.each(_.bind(function (option) {
				if (option !== this) {
					option.set('graded', 1);
				}
			}, this));

			// Mark this option as correct
			this.set('graded', 2);*/
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
				hint: 'Incorrect answer'
			},

			2: {
				// "Yes" is a correct answer
				'class': 'yes',
				icon: 'fa-check-circle',
				hint: 'Correct answer'
			}
		}
	});

	Curotec.TrueFalseOptions = Backbone.Collection.extend({
		model: Curotec.TrueFalseOption
	});

	// Sections
	Curotec.TrueFalseSection = Curotec.YesNoSection.extend({
		optionsCollection: Curotec.TrueFalseOptions
	});

	Curotec.TrueFalseSections = Backbone.Collection.extend({
		model: Curotec.TrueFalseSection
	});
})();
