<?php

namespace StudentAnswers;

class YesNoAnswer extends \Eloquent implements \StudentAnswerType, \StudentAnswerGraded
{

    protected $table = 'student_answers_yesno';
    public $timestamps = false;

    public function getMetaData()
    {
        return array('admin_template' => 'admin/answer_templates/yes_no');
    }

    public function yesNo()
    {
        return $this->belongsTo('ActivityTemplates\\YesNo');
    }

    public function groupBySection()
    {
        $values = $this->values()
            ->with('yesNoOption')
            ->get()
            ->groupBy(function ($item) {
                return $item->yesNoOption->yes_no_section_id;
            });

        $result = $this->yesNo->sections->reduce(
            function ($sections, $section) use ($values) {
                if (isset($values[$section->id])) {
                    $sections[$section->title] = $values[$section->id];
                }

                return $sections;
            }, array()
        );

        return $result;
    }

    public function valueByOption($option)
    {
        $value = $this->values->first(function ($k, $value) use ($option) {
            return $value->yesNoOption == $option;
        });

        return $value ? $value->value : null;
    }

    public function assessmentIsCorrectOption($section, $option)
    {

        foreach ($this->values as $value) {
            if ($value->yes_no_option_id == $section)
                return $value->value == $option;
        }


        return false;
    }

    public function values()
    {
        return $this->hasMany('StudentAnswers\\YesNoAnswerValue');
    }

    public function getGrade()
    {
        return $this->values()
            ->with('yesNoOption')
            ->get()->map(function ($value) {
                return $value->getGrade();
            })
            ->toArray();
    }

    /**
     * @param array $data
     * @param ActivityTemplates\YesNo $yesNo
     */
    public function saveFromArray($data, $yesNo)
    {
        // Associate answer with the corresponding activity template
        $this->yesNo()->associate($yesNo);
        $this->save();

        // Attach new answer options
        $values = array();

        if ($yesNo->getTable() == 'activity_template_assessment') {
            foreach ($yesNo->sections as $section) {
                $key = ('option_' . $section->id);

                if (isset($data[$key])) {

                    $value = new \StudentAnswers\YesNoAnswerValue(array(
                        'value' => (int)$data[$key]
                    ));

                    $value->yesNoOption()->associate($section->options()->find($value->value));

                    $value->yes_no_answer_id = $yesNo->id;
                    $value->yes_no_option_id = $section->id;

                    $values[] = $value;
                }
            }

        } else {
            foreach ($yesNo->options as $option) {
                $key = "";
                if ($yesNo->getTable() == 'activity_template_true_false') {
                    $key = ('option_' . $option->true_false_section_id);
                } else {
                    $key = ('option_' . $option->id);
                }

                if ($yesNo->getTable() == 'activity_template_true_false') {

                    if (isset($data[$key])) {

                        $value = new \StudentAnswers\YesNoAnswerValue(array(
                            'value' => (int)$data[$key]
                        ));

                        $value->yesNoOption()->associate($option);

                        $value->yes_no_answer_id = $yesNo->id;
                        $value->yes_no_option_id = $option->true_false_section_id;

                        $values[] = $value;
                    }
                } else {
                    if (isset($data[$key]) && ($data[$key] == 1 || $data[$key] == 0)) {

                        $value = new \StudentAnswers\YesNoAnswerValue(array(
                            'value' => (int)$data[$key]
                        ));

                        $value->yesNoOption()->associate($option);

                        $values[] = $value;
                    }
                }
            }

        }

        // Remove old answer values
        $this->values()->delete();

        // Attach new answer values
        $this->values()->saveMany($values);
    }

}
