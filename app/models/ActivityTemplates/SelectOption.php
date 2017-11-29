<?php


namespace ActivityTemplates;


class SelectOption extends \Eloquent {
	use \Curotec\Models\SortableTrait;

    protected $table = 'activity_template_select_options';
    protected $fillable = array('option', 'graded', 'order');
    public $timestamps = false;

    public function select() {
        return $this->belongsTo('ActivityTemplates\\Select', 'activity_template_select_id');
    }
}
