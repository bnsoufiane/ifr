<?php

namespace ActivityTemplates;

class FillblankItem extends \Eloquent
{

    protected $table = 'activity_template_fillblank_items';
    public $timestamps = false;
    protected $fillable = array('name');

    public function Fillblank()
    {
        return $this->belongsTo('ActivityTemplates\\Fillblank');
    }

}
