<?php


namespace ActivityTemplates;


abstract class BaseActivityTemplate implements \ActivityTemplate
{
    /**
     * Factory method to deserialize an activity template from an array.
     * @param $data
     * @returns ActivityTemplate
     * @throws Exception if wrong data is provided.
     */
    public static function createFromArray($data)
    {
        // Create an instance of the activity template.

        if (!isset($data['template_type'])) {
            $data['template_type'] = "Assessment";
            //throw new Exception('Activity template hasn\'t been provided.');
        }

        $activityTemplate = 'ActivityTemplates\\' . str_replace('ActivityTemplates\\', '', $data['template_type']);

        if (!class_exists($activityTemplate)) {
            throw new \Exception('Non-existing activity template has been provided.');
        }

        $activityTemplate = new $activityTemplate();

        if (!$activityTemplate instanceOf \ActivityTemplate) {
            throw new Exception('Invalid activity template has been provided.');
        }

        $activityTemplate->saveFromArray($data);

        return $activityTemplate;
    }
}
