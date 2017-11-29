(function () {

	var ActivityTemplates = window.ActivityTemplates || (window.ActivityTemplates = {});
	var Curotec = window.Curotec || (window.Curotec = {});

	Curotec.AssessmentOptionView = Curotec.YesNoOptionView.extend({
		initEvents: function () {
			this.listenTo(this.model, 'change:graded', _.bind(function () {
				// Do not update tooltips for incorrect answers.
				var isIncorrectAnswer = (this.model.get('graded') == 1);
				this.renderGradeStatus({ noUpdateTooltip: isIncorrectAnswer });
			}, this));
		}
	});

	Curotec.AssessmentSectionView = Curotec.YesNoSectionView.extend({
		events: _.extend({}, Curotec.YesNoSectionView.prototype.events, {
			'click .wrong-answer-collapse': function () {
				this.$('.wrong-answer-desc').collapse('toggle');
			}
		}),

		createOptionsCollection: function () {
			return new Curotec.AssessmentOptions();
		},

		createOptionView: function (model) {
			return new Curotec.AssessmentOptionView({
				model: model,
				template: this.optionTemplate
			});
		}
	});

	ActivityTemplates.AssessmentView = ActivityTemplates.YesNoView.extend({
		initTemplates: function () {
			this.sectionTemplate = JST['admin/activity-templates/assessment/section-template'];
			this.optionTemplate = JST['admin/activity-templates/assessment/option-template'];
		},

		createSectionView: function (model) {
			return new Curotec.AssessmentSectionView({
				model: model,
				template: this.sectionTemplate,
				optionTemplate: this.optionTemplate
			});
		},

		createSectionsCollection: function (models) {
			return new Curotec.AssessmentSections(models, {
				parse: true
			});
		}
	});
})();
