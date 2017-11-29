<?php


namespace Curotec;

/**
 * A skin for a module
 * @package Curotec
 */
abstract class ModuleSkin {
    /**
     * A property to define a list of activity templates for a skin.
     * Should be an array of fully-qualified (i.e. with namespaces) class names.
     * @var
     */
    protected $activityTemplates = null;

    protected static $name = '';

    /**
     * Returns a raw array of the skin's activity templates.
     * @return null
     */
    public function getActivityTemplatesRaw() {
        return $this->activityTemplates;
    }

    /**
     * Returns a list of the skin's activity templates in a "type" => "name" format.
     */
    public function getActivityTemplates() {
        return array_reduce($this->activityTemplates, function ($result, $activityTemplate) {
            $templateType = str_replace('ActivityTemplates\\', '', $activityTemplate);
            $meta = call_user_func_array(array($activityTemplate, 'getMetaData'), array()); // Get the template metadata.

            $result[$templateType] = $meta['name'];

            return $result;
        }, array());
    }

    public static function getName() {
        return static::$name;
    }
}
