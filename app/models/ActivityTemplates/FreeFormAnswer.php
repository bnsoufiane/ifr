<?php

namespace ActivityTemplates;

class FreeFormAnswer extends \Eloquent implements \ActivityTemplate, \ActivityWithInput {

    protected $table = 'activity_template_freeform';
    public $timestamps = false;
    protected $fillable = array('description', 'explanation');

    public static function getMetaData() {
        return array(
            'name' => 'Free-form answer',
            'admin_template' => 'admin/activity_templates/free_form_answer',
            'user_template' => 'activities/free_form_answer',
            'answer_template' => 'admin/answer_templates/freeform',
        );
    }

    public function activity() {
        return $this->morphOne('Activity', 'template');
    }

    public function saveFromArray($data) {
        $this->description = (isset($data['template']['description']))?$data['template']['description']:(isset($data['template']['attributes']['description'])?$data['template']['attributes']['description']:null);
        $this->explanation = isset($data['template']['explanation'])?$data['template']['explanation']:(isset($data['template']['attributes']['explanation'])?$data['template']['attributes']['explanation']:null);
        $this->save();
    }

    public function delete_activity() {
        $this->delete();
    }

    /**
     * Creates a "student answer" model for this activity template.
     */
    public function getStudentAnswer() {
        return 'StudentAnswers\\FreeFormAnswer';
    }

}
