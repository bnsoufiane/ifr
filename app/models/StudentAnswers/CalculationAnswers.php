<?php

namespace StudentAnswers;

/**
 * Model for Calculation free-form (i.e. text based) answers.
 */
class CalculationAnswers extends \Eloquent implements \StudentAnswerType
{
    protected $table = 'student_answers_calculation';
    protected $fillable = array('calculation_answers_id');
    public $timestamps = false;

    public function getMetaData()
    {
        return array('admin_template' => 'admin/answer_templates/calculation');
    }

    public function calculation()
    {
        return $this->belongsTo('ActivityTemplates\\Calculation', 'calculation_answers_id');
    }

    public function values()
    {
        return $this->hasMany('StudentAnswers\\CalculationAnswersValue');
    }

    /**
     * Returns one of the answers, referenced by index.
     * @param int $index
     */
    public function getAnswer($index)
    {
        if (isset($this->values[$index])) {
            return $this->values[$index]->text;
        }
        return '';
    }

    /**
     * @param array $data
     * @param ActivityTemplates\CalculationAnswers $activityTemplate
     */
    public function saveFromArray($data, $activityTemplate)
    {
        if (isset($data['calculation_' . $data['parent_activity_id']])) {

            $this->calculation()->associate($activityTemplate);
            $this->save();

            $values = array();
            
            foreach ($data['calculation_' . $data['parent_activity_id']] as $answer) {
                $value = new CalculationAnswersValue(array(
                    'text' => $answer,
                    'is_footer' => false,
                ));
                $values[] = $value;
            }

            if (isset($data['calculation_footer_' . $data['parent_activity_id']])) {
                foreach ($data['calculation_footer_' . $data['parent_activity_id']] as $answer) {
                    $value = new CalculationAnswersValue(array(
                        'text' => $answer,
                        'is_footer' => true,
                    ));
                    $values[] = $value;
                }
            }

            $this->values()->delete();
            $this->values()->saveMany($values);
        }
    }
}
