<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends \Cartalyst\Sentry\Users\Eloquent\User
{

    protected static $rules = array(
        'username' => 'required|unique:users,username',
        'password' => ''
    );

    /**
     * @param $input array
     * @return \Validator
     */
    public static function preValidate($input, $id = null)
    {
        $rules = self::$rules;

        if ($id !== null) {
            // Do not check for uniqueness when editing.
            $rules['username'] .= ',' . $id;
        }

        return Validator::make($input, $rules);
    }

    public static function findByUsername($username)
    {
        return User::where('username', $username)->first();
    }

    public static function findByEmail($email)
    {
        return User::where('email', $email)->first();
    }

    /**
     * Returns stringified list of groups for the user.
     * @returns array
     */
    public function getGroupsList()
    {
        $groups = array();

        $this->groups->each(function ($group) use (&$groups) {
            $groups[] = $group->name;
        });

        return $groups;
    }

    public function school()
    {
        return $this->belongsTo('School');
    }

    public function scopeWithoutSchool($query)
    {
        return $query->whereNull('school_id');
    }

    public function scopeOfGroup($query, $group)
    {
        return $query->whereHas('groups', function ($q) use ($group) {
                    $q->where('id', '=', $group->id);
                });
    }

    public function scopeOfSchool($query, $school)
    {
        return $query->where('school_id', '=', $school->id);
    }

    /**
     * Returns users that belong to the same school as the other provided user.
     */
    public function scopeFromUsersSchool($query, $user)
    {
        if ($user->isSysAdmin()) {
            // Return all classes if a user is a system administrator
            return $query;
        }
        return $this->ofSchool($user->school);
    }

    public function isSysAdmin()
    {
        $sysAdminGroup = \Sentry::findGroupByName('System Administrator');

        return $this->inGroup($sysAdminGroup);
    }

    public function isSchoolAdmin()
    {
        $sysAdminGroup = \Sentry::findGroupByName('School Administrator');

        return $this->inGroup($sysAdminGroup);
    }

    public function isTeacher()
    {
        $sysAdminGroup = \Sentry::findGroupByName('Teacher');

        return $this->inGroup($sysAdminGroup);
    }

    public function isStudent()
    {
        $studentGroup = \Sentry::findGroupByName('Student');

        return $this->inGroup($studentGroup);
    }

    public static function getTeachers()
    {
        $users = \User::get();
        $teachers = array();

        foreach ($users as $user) {
            if ($user->isTeacher()) {
                $teachers[$user->id] = $user->first_name . " " . $user->last_name;
            }
        }

        return $teachers;
    }

    public function studentsCount()
    {
        $users = \User::fromUsersSchool($this)->orderBy('last_name');
        $users = $users->get();
        $teacher_classes = \SchoolClass::select("id")->where('created_by', '=', $this->id)->get();
        $teacher_classes_ids = array();
        foreach ($teacher_classes as $teacher_class) {
            $teacher_classes_ids[] = $teacher_class->id;
        }
        $students = array();
        foreach ($users as $usr) {
            $user_classes = \DB::table('school_class_student')->where('student_id', '=', $usr->id)->get();
            $user_classes_ids = array();
            foreach ($user_classes as $user_class) {
                $user_classes_ids[] = $user_class->school_class_id;
            }

            if (count(array_intersect($teacher_classes_ids, $user_classes_ids)) > 0) {
                array_push($students, $usr);
            }
        }

        return count($students);
    }

}
