<?php

namespace StudentAnswers;

/**
 * A subclass of 'Json' answer type that imposes more strict
 * input saving rules - it saves only those inputs that are
 * provided by the WYSIWYG page class.
 */
class Wysiwyg extends Json {

    public function saveFromArray($data, $wysiwygActivity) {
        if (!isset($data['response'])) {
            return null;
        }

        $answerStorage = array();

        foreach ($wysiwygActivity->getAvailableInputs() as $input) {
            $answerStorage[$input] = (isset($data['response'][$input])) ? $data['response'][$input] : null;
        }

        $this->json = $answerStorage;
        $this->save();
    }

}
