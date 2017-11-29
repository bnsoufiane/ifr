<?php

class School extends Eloquent
{
    protected $fillable = array('name');
    protected static $rules = array(
        'name' => 'required|unique:schools,name'
    );

    /**
     * @param $input array
     * @return \Validator
     */
    public static function validate($input, $id = null)
    {
        $rules = self::$rules;

        if ($id !== null) {
            // Do not check for uniqueness when editing.
            $rules['name'] .= ',' . $id;
        }

        return Validator::make($input, $rules);
    }

    public function series()
    {
        return $this->belongsToMany('Series');
    }

    public function classes()
    {
        return $this->hasMany('SchoolClass');
    }

    public function school_district()
    {
        return $this->belongsTo('SchoolDistrict');
    }

    public function users()
    {
        return $this->hasMany('User');
    }

    /**
     * Returns a list of the school's administrators.
     */
    public function admins()
    {
        $schoolAdmins = \Sentry::findGroupByName('School Administrator');
        return $this->users()->ofGroup($schoolAdmins);
    }

    /**
     * Returns a list of the school's teachers.
     */
    public function teachers()
    {
        $teacher = \Sentry::findGroupByName('Teacher');
        return $this->users()->ofGroup($teacher);
    }
}
