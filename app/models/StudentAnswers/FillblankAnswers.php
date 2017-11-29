<?php

namespace StudentAnswers;

/**
 * Model for Fillblank free-form (i.e. text based) answers.
 */
class FillblankAnswers extends \Eloquent implements \StudentAnswerType
{
    protected $table = 'student_answers_fillblank';
    protected $fillable = array('fillblank_answers_id');
    public $timestamps = false;

    public function getMetaData()
    {
        return array('admin_template' => 'admin/answer_templates/fillblank');
    }

    public function fillblank()
    {
        return $this->belongsTo('ActivityTemplates\\Fillblank', 'fillblank_answers_id');
    }

    public function values()
    {
        return $this->hasMany('StudentAnswers\\FillblankAnswersValue');
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
     * @param ActivityTemplates\FillblankAnswers $activityTemplate
     */
    public function saveFromArray($data, $activityTemplate)
    {
        if (isset($data['fillblank_' . $data['parent_activity_id']])) {
            $this->fillblank()->associate($activityTemplate);
            $this->save();

            $values = array();

            foreach ($data['fillblank_' . $data['parent_activity_id']] as $answer) {
                $value = new FillblankAnswersValue(array(
                    'text' => $answer
                ));
                $values[] = $value;
            }

            $this->values()->delete();
            $this->values()->saveMany($values);
        }
    }
}
