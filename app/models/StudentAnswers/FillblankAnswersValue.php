<?php

namespace StudentAnswers;

class FillblankAnswersValue extends \Eloquent {
	public $timestamps = false;
	protected $fillable = array('text');
	protected $table = 'student_answers_fillblank_values';

	public function multipleAnswers() {
		return $this->belongsTo('StudentAnswers\\FillblankAnswers');
	}
}
