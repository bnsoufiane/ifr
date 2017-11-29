<?php


namespace ActivityTemplates;


class TrueFalseOption extends YesNoOption
{
    protected $table = 'activity_template_true_false_sections_options';

    public function section()
    {
        return $this->belongsTo('ActivityTemplates\\TrueFalseSection');
    }
}
