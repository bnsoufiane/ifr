<?php

namespace StudentAnswers;

/**
 * Model for multiple free-form (i.e. text based) answers.
 */
class MultipleAnswers extends \Eloquent implements \StudentAnswerType
{
    protected $table = 'student_answers_multiple';
    protected $fillable = array('multiple_answers_id');
    public $timestamps = false;

    public function getMetaData()
    {
        return array('admin_template' => 'admin/answer_templates/multiple_answers');
    }

    public function multipleAnswers()
    {
        return $this->belongsTo('ActivityTemplates\\MultipleAnswers');
    }

    public function values()
    {
        return $this->hasMany('StudentAnswers\\MultipleAnswersValue');
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
     * @param ActivityTemplates\MultipleAnswers $activityTemplate
     */
    public function saveFromArray($data, $activityTemplate)
    {
        if (isset($data['answer_' . $data['parent_activity_id']])) {
            $this->multipleAnswers()->associate($activityTemplate);
            $this->save();

            $values = array();

            foreach ($data['answer_' . $data['parent_activity_id']] as $answer) {
                $value = new MultipleAnswersValue(array(
                    'text' => $answer
                ));
                $values[] = $value;
            }

            $this->values()->delete();
            $this->values()->saveMany($values);
        }
    }
}
