<?php

class Tests extends Eloquent {

    const PRE = 0;
    const POST = 1;
    
    const OPEN = 0;
    const CLOSED = 1;
    
    public function schoolclass() {
        return $this->belongsTo('SchoolClass');
    }

    public static function getBySchoolClass($class) {
        return \Tests::where('schoolclass_id', '=', $class)->first();
    }

}
