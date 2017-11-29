(function () {

	var ActivityTemplates = window.ActivityTemplates || (window.ActivityTemplates = {});
	var Curotec = window.Curotec || (window.Curotec = {});

	Curotec.TrueFalseOptionView = Curotec.YesNoOptionView.extend({
		initEvents: function () {
			this.listenTo(this.model, 'change:graded', _.bind(function () {
				// Do not update tooltips for incorrect answers.
				var isIncorrectAnswer = (this.model.get('graded') == 1);
				this.renderGradeStatus({ noUpdateTooltip: isIncorrectAnswer });
			}, this));
		}
	});

	Curotec.TrueFalseSectionView = Curotec.YesNoSectionView.extend({
		events: _.extend({}, Curotec.YesNoSectionView.prototype.events, {
			'click .wrong-answer-collapse': function () {
				this.$('.wrong-answer-desc').collapse('toggle');
			}
		}),

		createOptionsCollection: function () {
			return new Curotec.TrueFalseOptions();
		},

		createOptionView: function (model) {
			return new Curotec.TrueFalseOptionView({
				model: model,
				template: this.optionTemplate
			});
		}
	});

	ActivityTemplates.TrueFalseView = ActivityTemplates.YesNoView.extend({
		initTemplates: function () {
			this.sectionTemplate = JST['admin/activity-templates/truefalse/section-template'];
			this.optionTemplate = JST['admin/activity-templates/truefalse/option-template'];
		},

		createSectionView: function (model) {
			return new Curotec.TrueFalseSectionView({
				model: model,
				template: this.sectionTemplate,
				optionTemplate: this.optionTemplate
			});
		},

		createSectionsCollection: function (models) {
			return new Curotec.TrueFalseSections(models, {
				parse: true
			});
		}
	});
})();
