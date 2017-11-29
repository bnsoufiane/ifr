<?php

namespace StudentAnswers;

class MultipleAnswersValue extends \Eloquent {
	public $timestamps = false;
	protected $fillable = array('text');
	protected $table = 'student_answers_multiple_values';

	public function multipleAnswers() {
		return $this->belongsTo('StudentAnswers\\MultipleAnswers');
	}
}
