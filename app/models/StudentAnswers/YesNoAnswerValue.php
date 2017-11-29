<?php

namespace StudentAnswers;

class YesNoAnswerValue extends \Eloquent
{

    public $timestamps = false;
    protected $fillable = array('value');
    protected $table = 'student_answers_yesno_values';

    public function yesNoOption()
    {
        return $this->belongsTo('ActivityTemplates\\YesNoOption');
    }

    public function getGrade()
    {

        $gradeState = \ActivityTemplates\AssessmentOption::select('graded')->where('id', '=', $this->value)->first();
        return $gradeState->graded;
    }

}
