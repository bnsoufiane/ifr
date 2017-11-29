<?php

namespace ActivityTemplates;

class Blog extends \Eloquent implements \ActivityTemplate, \ActivityWithInput {

    protected $table = 'activity_template_blog';
    public $timestamps = false;
    protected $fillable = array('title', 'explanation');

    public static function getMetaData() {
        return array(
            'name' => 'Blog',
            'admin_template' => 'admin/activity_templates/blog',
            'user_template' => 'activities/blog',
            'answer_template' => 'admin/answer_templates/blog',
        );
    }

    public function saveFromArray($data) {
        $this->title = isset($data['template']['title'])?$data['template']['title']:$data['template']['attributes']['title'];
        $this->explanation = isset($data['template']['explanation'])?$data['template']['explanation']:$data['template']['attributes']['explanation'];
        $this->save();
    }

    public function delete_activity() {
        $this->delete();
    }
    
    public function getStudentAnswer() {
        return 'StudentAnswers\\BlogAnswer';
    }

}
