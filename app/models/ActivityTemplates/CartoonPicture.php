<?php


namespace ActivityTemplates;


class CartoonPicture extends \Eloquent {
	use \Curotec\Models\SortableTrait;

	protected $table = 'activity_template_cartoon_pictures';
    public $timestamps = false;
	protected $fillable = array('file', 'order');

	public function cartoon() {
		$this->belongsTo('ActivityTemplates\\Cartoon');
	}
}
