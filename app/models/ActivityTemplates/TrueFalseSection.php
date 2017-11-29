<?php


namespace ActivityTemplates;


class TrueFalseSection extends YesNoSection
{
    protected $table = 'activity_template_true_false_sections';
    public $timestamps = false;
    protected $fillable = array('title', 'order');

    public function toArray()
    {
        return array(
            'id' => $this->id,
            'title' => $this->title,
            'options' => $this->options->toArray()
        );
    }

    public function trueFalse()
    {
        return $this->belongsTo('ActivityTemplates\\TrueFalse');
    }

    public function options()
    {
        return $this->hasMany('ActivityTemplates\\TrueFalseOption');
    }
}
