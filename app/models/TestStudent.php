<?php

class TestStudent extends Eloquent
{

    const PRE_TEST = 0;
    const LEARNING = 1;
    const POST_TEST = 2;

    public function test()
    {
        return $this->belongsTo('Tests');
    }

    public function student()
    {
        return $this->belongsTo('User');
    }

    public static function getByTestId($test)
    {
        return \TestConfiguration::where('test_id', '=', $test)->get();
    }

    public static function getByTestAndStudent($test, $student)
    {
        return \TestStudent::whereRaw('test_id = ? and student_id = ?', array($test, $student))->get();
    }

    public static function getByTestAndStudentAndLearningLevel($test, $student, $learning_level)
    {
        return \TestStudent::whereRaw('test_id = ? and student_id = ? and learning_level=?', array($test, $student, $learning_level))->first();
    }


}
