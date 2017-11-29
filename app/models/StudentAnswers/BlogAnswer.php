<?php

namespace StudentAnswers;

class BlogAnswer extends \Eloquent implements \StudentAnswerType
{
    protected $table = 'student_answers_freeform';
    protected $fillable = array('answer');
    public $timestamps = false;

    public function getMetaData()
    {
        return array('admin_template' => 'admin/answer_templates/freeform');
    }

    public function saveFromArray($data, $activityTemplate)
    {
        if (isset($data['parent_activity_id']) && isset($data['blog_text_' . $data['parent_activity_id']])) {
            $this->answer = $data['blog_text_' . $data['parent_activity_id']];
            $this->save();
        }

    }
}

