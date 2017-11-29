<?php

use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Series extends Eloquent {
    use SoftDeletingTrait;
	use \Curotec\Models\SortableTrait;

    public $fillable = array('title', 'module_id', 'order');
    public static $rules = array(
        'title' => 'required|unique:modules,title',
        'module_id' => 'exists:modules,id'
    );

    public static function validate($input, $id = null) {
		$rules = self::$rules;

		if ($id !== null) {
			// Do not check for uniqueness when editing.
			$rules['title'] .= ',' . $id;
		}

        return Validator::make($input, $rules);
    }

    public function lessons() {
        return $this->hasMany('Lesson');
    }

    public function module() {
        return $this->belongsTo('Module');
    }

    public function schools() {
        return $this->belongsToMany('School');
    }

    public function classes() {
        return $this->belongsToMany('SchoolClass');
    }
}
