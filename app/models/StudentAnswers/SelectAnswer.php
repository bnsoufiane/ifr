<?php

namespace StudentAnswers;

use \ActivityTemplates\SelectOption;

class SelectAnswer extends \Eloquent implements \StudentAnswerType, \StudentAnswerGraded {

    protected $table = 'student_answers_select';
    protected $fillable = array('select_answer_id');
    public $timestamps = false;

    public function getMetaData() {
        return array('admin_template' => 'admin/answer_templates/select');
    }

    public function values() {
        return $this->hasMany('StudentAnswers\\SelectAnswerValue', 'select_answer_id');
    }

    public function selectOption() {
        return $this->belongsTo('ActivityTemplates\\SelectOption', 'select_answer_id');
    }

    public function getGrade() {
        $isGradedSelect = $this->selectOption->select->isGraded();

        if (!$isGradedSelect) {
            return \StudentAnswer::NOT_GRADED;
        } else {
            if ($this->selectOption->graded == 1) {
                return \StudentAnswer::CORRECT;
            } else {
                return \StudentAnswer::INCORRECT;
            }
        }
    }

    public function saveFromArray($data, $activityTemplate) {
        $option = SelectOption::find($activityTemplate->id);
        //$this->selectOption()->associate($option);
        
        $values = array();
        foreach ($data as $key => $item) {

            if (strpos($key, 'select_option_id_') !== false) {
                $first_input = true;
                $value = new SelectAnswerValue(array(
                    'option' => $item
                ));
                $values[] = $value;
            }
        }

        if(isset($first_input)){
            $this->save();

            $this->values()->delete();
            $this->values()->saveMany($values);
        }
    }

}
