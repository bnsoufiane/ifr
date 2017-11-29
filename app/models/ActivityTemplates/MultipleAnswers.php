<?php

namespace ActivityTemplates;

class MultipleAnswers extends \Eloquent implements \ActivityTemplate, \ActivityWithInput {

    protected $table = 'activity_template_multiple_answers';
    public $timestamps = false;
    protected $fillable = array('number_of_fields', 'placeholder_answers', 'description');
    private $placeholderAnswersCache;

    public static function getMetaData() {
        return array(
            'name' => 'Multiple Answers',
            'admin_template' => 'admin/activity_templates/multiple_answers',
            'user_template' => 'activities/multiple_answers',
            'answer_template' => 'admin/answer_templates/multiple_answers'
        );
    }

    public function delete_activity() {
        $this->delete();
    }
    public function saveFromArray($data) {
        $this->number_of_fields = (int) $data['template']['number_of_fields'];
        $this->placeholder_answers = $data['template']['placeholder_answers'];
        if(isset($data['template']['description'])){
            $this->description = $data['template']['description'];
        }

        $this->save();
    }

    /**
     * Returns a pre-defined sample answer for a provided position
     * @param int $answerNo
     * @return string
     */
    public function getPlaceholder($answerNo) {
        if (!$this->placeholderAnswersCache) {
            $this->placeholderAnswersCache = json_decode($this->placeholder_answers);
        }
        return $this->placeholderAnswersCache[$answerNo];
    }

    /**
     * Creates a "student answer" model for this activity template.
     */
    public function getStudentAnswer() {
        return 'StudentAnswers\\MultipleAnswers';
    }

}
