<?php


namespace ActivityTemplates;


class QnA extends Story {

    public static function getMetaData() {
        return array(
            'name' => 'Questions & Answers',
            'admin_template' => 'admin/activity_templates/story',
            'user_template' => 'activities/q_and_a'
        );
    }

}
