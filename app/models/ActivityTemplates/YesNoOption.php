<?php


namespace ActivityTemplates;


class YesNoOption extends \Eloquent {
	use \Curotec\Models\SortableTrait;

    protected $table = 'activity_template_yesno_sections_options';
    public $timestamps = false;
    protected $fillable = array('option', 'graded', 'order');

	public function section() {
		return $this->belongsTo('ActivityTemplates\\YesNoSection');
	}

	public function toArray() {
		return array(
			'id' => $this->id,
			'graded' => $this->graded,
			'option' => $this->option
		);
	}
}
