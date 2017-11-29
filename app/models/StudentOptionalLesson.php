<?php

class StudentOptionalLesson extends Eloquent {

    public function student() {
        return $this->belongsTo('Student');
    }

    public function lesson() {
        return $this->belongsTo('Lesson');
    }

    /*
    public static function getByTestId($test) {
        return \TestConfiguration::where('test_id', '=', $test)->get();
    }

    public static function getStudentClass($student) {
        return \TestConfiguration::where('test_id', '=', $test)->get();
    }

    public static function getByTestAndStudent($test, $student) {
        return \TestStudent::whereRaw('test_id = ? and student_id = ?', array($test, $student))->get();
    }

    public static function getByTestAndStudentAndLearningLevel($test, $student, $learning_level) {
        return \TestStudent::whereRaw('test_id = ? and student_id = ? and learning_level=?', array($test, $student, $learning_level))->first();
    }
    */

}