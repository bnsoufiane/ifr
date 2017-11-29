<?php

class Activity extends Eloquent {
	use \Curotec\Models\SortableTrait;

    protected $fillable = array(
		'background_image', 'title', 'illustration_image', 'audio_version',
		'pdf_version', 'feedback', 'order', 'parent_activity'
	);

    public function lesson() {
        return $this->belongsTo('Lesson');
    }

    public function template() {
        return $this->morphTo();
    }

    public function getSubAcitivities() {
        return \Activity::where('parent_activity', '=', $this->id)->get();
    }

}
