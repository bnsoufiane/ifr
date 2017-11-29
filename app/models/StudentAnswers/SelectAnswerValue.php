<?php

namespace StudentAnswers;

class SelectAnswerValue extends \Eloquent {

    public $timestamps = false;
    protected $fillable = array('option');
    protected $table = 'student_answers_select_values';

    public function selectOption() {
        return $this->belongsTo('StudentAnswers\\SelectAnswer', 'select_answer_id');
    }

}
