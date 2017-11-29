<?php

namespace ActivityTemplates;

class CalculationFooter extends \Eloquent
{

    protected $table = 'activity_template_calculation_footers';
    public $timestamps = false;
    protected $fillable = array('name');

    public function calculationTable()
    {
        return $this->belongsTo('ActivityTemplates\\Calculation');
    }

}
