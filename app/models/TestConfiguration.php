<?php

class TestConfiguration extends Eloquent {

    const PRE = 0;
    const POST = 1;

    public function test() {
        return $this->belongsTo('Tests');
    }

    public function section() {
        return $this->belongsTo('ActivityTemplates\AssessmentSection');
    }
    
    public static function getByTestId($test) {
        return \TestConfiguration::where('test_id', '=', $test)->get();
    }

    public static function getByTestIdAndTestType($test, $test_type) {
        return \TestConfiguration::where('test_id', '=', $test)->where('test_type', '=', $test_type)->distinct()->get();
    }

}
