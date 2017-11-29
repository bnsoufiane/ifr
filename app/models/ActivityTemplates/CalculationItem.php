<?php

namespace ActivityTemplates;

class CalculationItem extends \Eloquent {

    protected $table = 'activity_template_calculation_items';
    public $timestamps = false;
    protected $fillable = array('employer_cost', 'cost_unit', 'name');

    public function calculationTable() {
        return $this->belongsTo('ActivityTemplates\\Calculation');
    }

}
