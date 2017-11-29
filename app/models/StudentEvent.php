<?php

class StudentEvent extends Eloquent {

    const VIEWED_LESSON = 1;
    const REQUESTED_FEEDBACK = 2;
    const LISTENED_AUDIO = 3;
    const PRINTED_LESSON = 4;

    protected $fillable = array('event_type');

    public function referTo() {
        return $this->morphTo();
    }

    public function user() {
        return $this->belongsTo('User');
    }

}
