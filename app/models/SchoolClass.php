<?php

use \Illuminate\Support\Collection;
use \Illuminate\Database\Eloquent\SoftDeletingTrait;

class SchoolClass extends Eloquent
{
    use SoftDeletingTrait;

    public $fillable = array('name', 'minimum_score', 'default_password');
    protected static $rules = array(
        'name' => 'required|unique:school_classes,name',
        'minimum_score' => 'required',
        'default_password' => 'required|min:5|max:50',
        'module_id' => 'required',
        'school_id' => 'exists:schools,id'
    );

    /**
     * @param $input array
     * @return \Validator
     */
    public static function validate($input, $id = null)
    {
        $rules = self::$rules;

        $currentUser = \Sentry::getUser();
        if (!$currentUser->isTeacher()) {
            unset($rules['module_id']);
        }

        if ($id !== null) {
            // Do not check for uniqueness when editing.
            $rules['name'] .= ',' . $id;
        }

        return Validator::make($input, $rules);
    }

    public static function validateStudentsInput($input, $id = null)
    {
        $rules = array();

        foreach ($input as $key => $value) {
            if ($key != "_token" && $key != "id") {
                if (strpos($key, 'username') !== FALSE) {
                    if (isset($input["id_" . str_replace('username_', '', $key)])) {
                        $rules[$key] = 'required|min:5';
                    } else {
                        $rules[$key] = 'required|min:5|unique:users,username';
                    };
                } else if (strpos($key, 'pass') !== FALSE) {
                    if (isset($input["id_" . str_replace('pass_', '', $key)])) {
                        $rules[$key] = 'min:5';
                    } else {
                        $rules[$key] = 'required|min:5';
                    };
                } else if (strpos($key, 'id') === FALSE) {
                    $rules[$key] = 'required|min:2';
                }
            } else {
                unset($input[$key]);
            }
        }

        return Validator::make($input, $rules);
    }

    public static function validateStudentInput($input, $id = null, $username = "")
    {
        $rules = array();

        foreach ($input as $key => $value) {
            if ($key != "_token") {
                if ($key == 'username') {
                    if ($value != $username) {
                        $rules[$key] = 'required|min:5|unique:users,username';
                    } else {
                        $rules[$key] = 'required|min:5';
                    }

                } else if ($key == 'pass') {
                    if (isset($input["id"])) {
                        $rules[$key] = 'min:5';
                    } else {
                        $rules[$key] = 'required|min:5';
                    };
                } else if ($key != 'id') {
                    $rules[$key] = 'required|min:2';
                }
            } else {
                unset($input[$key]);
            }
        }

        return Validator::make($input, $rules);
    }

    public function created_by()
    {
        return $this->belongsTo('User', 'created_by');
    }

    public function school()
    {
        return $this->belongsTo('School');
    }

    public function series()
    {
        return $this->belongsToMany('Series');
    }

    public function students()
    {
        return $this->belongsToMany('Student', 'school_class_student', 'school_class_id', 'student_id');
    }

    public function optional_lessons()
    {
        return $this->belongsToMany('Lesson', 'school_class_optional_lessons', 'school_class_id', 'optional_lesson_id');
    }

    /**
     * Returns classes that belong to the same school as the provided user.
     */
    public function scopeFromUsersSchool($query, $user)
    {
        if ($user->isSysAdmin()) {
            // Return all classes if a user is a system administrator
            return $query;
        }
        return $this->ofSchool($user->school);
    }

    /**
     * Returns classes that belong only to a provided school.
     */
    public function scopeOfSchool($query, $school)
    {
        return $query->where('school_id', '=', $school->id);
    }

    public function assignedLessons()
    {
        return $this->series->map(function ($serie) {
            return $serie->lessons;
        })->reduce(function ($allLessons, $lessons) {
            foreach ($lessons as $lesson) {
                if (!isset($allLessons[$lesson->id])) {
                    $allLessons[$lesson->id] = $lesson;
                }
            }
            return $allLessons;
        }, new Collection());
    }

}
