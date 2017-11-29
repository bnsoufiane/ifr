(function () {
	var Curotec = window.Curotec || (window.Curotec = {});

	// Options
	Curotec.AssessmentOption = Curotec.Model.extend({
		defaults: {
			graded: 1
		},

		toggleGraded: function () {
			// Mark all other options as incorrect.
			this.collection.each(_.bind(function (option) {
				if (option !== this) {
					option.set('graded', 1);
				}
			}, this));

			// Mark this option as correct
			this.set('graded', 2);
		}
	}, {
		GRADE_STATUSES: {
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

	Curotec.AssessmentOptions = Backbone.Collection.extend({
		model: Curotec.AssessmentOption
	});

	// Sections
	Curotec.AssessmentSection = Curotec.YesNoSection.extend({
		optionsCollection: Curotec.AssessmentOptions
	});

	Curotec.AssessmentSections = Backbone.Collection.extend({
		model: Curotec.AssessmentSection
	});
})();
