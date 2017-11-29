<?php

namespace ActivityTemplates;

use Cache;

class Wysiwyg extends \Eloquent implements \ActivityTemplate {

    protected $table = 'activity_template_wysiwyg';
    public $timestamps = false;
    protected $fillable = array('content');

    public static function getMetaData() {
        return array(
            'name' => 'Visual Page Editor',
            'admin_template' => 'admin/activity_templates/wysiwyg',
            'user_template' => 'activities/wysiwyg'
        );
    }

    public static function boot() {
        parent::boot();

        static::saved(function ($model) {
            $model->cacheDOMInputs();
        });
    }

    private function getCacheKey() {
        return 'wysiwyg_dom_' . $this->id;
    }

    private function cacheDOMInputs() {
        $inputs = array();

        $dom = new \DOMDocument();
        $dom->loadHTML($this->content);

        foreach ($this->enumInputFields($dom) as $input) {
            $inputs[] = $this->getDOMInputName($input);
        }

        Cache::forever($this->getCacheKey(), $inputs);
    }

    /**
     * Formats the content to display it for the user.
     */
    public function displayContent($data = array()) {
        // TODO: cache
        $dom = new \DOMDocument();
        $dom->loadHTML($this->content);

        // Replace all input fields
        $this->replaceInputFields($dom, $data);

        return $dom->saveHTML();
    }

    private function enumInputFields($dom) {
        $result = array();
        $inputs = $dom->getElementsByTagName('input');

        foreach ($inputs as $input) {
            $isInputField = $input->attributes->getNamedItem('data-input-field');

            if ($isInputField !== null) {
                $result[] = $input;
            }
        }

        return $result;
    }

    private function getDOMInputName($input) {
        $fieldName = $input->attributes->getNamedItem('placeholder');

        return ($fieldName !== null) ? $fieldName->value : null;
    }

    /**
     * @param DOMDocument $dom
     * @param array $data Pre-existing data
     */
    private function replaceInputFields($dom, $data = array()) {
        foreach ($this->enumInputFields($dom) as $input) {
            $fieldName = $this->getDOMInputName($input);

            // Replace it with editable version
            $input->removeAttribute('readonly');
            $input->removeAttribute('placeholder');

            $input->setAttribute('class', 'empty-field');
            $input->setAttribute('name', 'response[' . $fieldName . ']');

            // Check if we have stored answer for the input
            if (!empty($data[$fieldName])) {
                $input->setAttribute('value', $data[$fieldName]);
            }
        }
    }

    public function saveFromArray($data) {
        $this->content = $data['template']['content'];
        $this->save();
    }

    public function getAvailableInputs() {
        return Cache::get($this->getCacheKey());
    }

    public function delete_activity() {
        $this->delete();
    }

    /**
     * Creates a "student answer" model for this activity template.
     */
    public function getStudentAnswer() {
        return 'StudentAnswers\\Wysiwyg';
    }

}
