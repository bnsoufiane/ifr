<?php

namespace ActivityTemplates;

// Activity to select an option

class Select extends \Eloquent implements \ActivityTemplate, \ActivityWithInput
{

    protected $table = 'activity_template_select';
    public $timestamps = false;
    protected $fillable = array('description', 'explanation');

    public static function getMetaData()
    {
        return array(
            'name' => 'Select from many options',
            'admin_template' => 'admin/activity_templates/select',
            'user_template' => 'activities/select',
            'answer_template' => 'admin/answer_templates/select'
        );
    }

    public function activity()
    {
        return $this->morphOne('Activity', 'template');
    }

    public function options()
    {
        return $this->hasMany('ActivityTemplates\\SelectOption', 'activity_template_select_id');
    }

    /**
     * Returns grading status of this select - if it has one or more
     * graded options, then it's graded. Otherwise, it's not.
     * @returns bool
     */
    public function isGraded()
    {
        return $this->options->filter(function ($opt) {
            return $opt->graded == 1;
        })->count() > 0;
    }
  
    public function toArray()
    {
        $data = array(
            'id' => $this->id,
            'description' => $this->description,
            'explanation' => $this->explanation,
            'options' => $this->options()->sorted()->get()->toArray()
        );

        return $data;
    }

    public function delete_activity()
    {
        $options = $this->options()->get();

        foreach ($options as $option) {
            $option->delete();
        }

        $this->delete();
    }

    public function saveFromArray($data)
    {
        $this->description = isset($data['template']['description']) ? $data['template']['description'] : '';
        $this->explanation = isset($data['template']['explanation']) ? $data['template']['explanation'] : '';
        $this->save();

        // Synchronizing select options.
        $updatedIds = array();

        if (!isset($data['template']['options'])) {
            $data['template']['options'] = $data['template']['attributes']['options'];
        }

        foreach ($data['template']['options'] as $optionData) {
            if (isset($optionData['id'])) {
                $_option = SelectOption::find($optionData['id']);
                if ($_option != null) {
                    $_option = SelectOption::find($optionData['id']);
                    $_option->update($optionData);
                    $this->options()->save($_option);
                } else {
                    $_option = new SelectOption($optionData);
                    $_option->id = $optionData['id'];
                    $this->options()->save($_option);
                }

                $updatedIds[] = $optionData['id'];
            } else {
                $option = new SelectOption($optionData);
                $this->options()->save($option);

                $updatedIds[] = $option->id;
            }
        }

        if (count($updatedIds)) {
            $this->options()->whereNotIn('id', $updatedIds)->delete();
        }
    }

    /**
     * Creates a "student answer" model for this activity template.
     */
    public function getStudentAnswer()
    {
        return 'StudentAnswers\\SelectAnswer';
    }

}
