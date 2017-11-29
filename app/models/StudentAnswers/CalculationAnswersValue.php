<?php

namespace StudentAnswers;

class CalculationAnswersValue extends \Eloquent {
	public $timestamps = false;
	protected $fillable = array('text');
	protected $table = 'student_answers_calculation_values';

	public function multipleAnswers() {
		return $this->belongsTo('StudentAnswers\\CalculationAnswers');
	}
}
