<?php


namespace ActivityTemplates;


class AssessmentSection extends YesNoSection {
	protected $table = 'activity_template_assessment_sections';
	public $timestamps = false;
	protected $fillable = array('title', 'wrong_answer_desc', 'order');

	public function toArray() {
		return array(
			'id' => $this->id,
			'title' => $this->title,
			'wrong_answer_desc' => $this->wrong_answer_desc,
			'options' => $this->options->toArray()
		);
	}

	public function assessment() {
		return $this->belongsTo('ActivityTemplates\\Assessment');
	}

	public function options() {
		return $this->hasMany('ActivityTemplates\\AssessmentOption');
	}
}
