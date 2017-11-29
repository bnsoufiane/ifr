<?php


namespace ActivityTemplates;


class AssessmentOption extends YesNoOption {
	protected $table = 'activity_template_assessment_sections_options';

	public function section() {
		return $this->belongsTo('ActivityTemplates\\AssessmentSection');
	}
}
