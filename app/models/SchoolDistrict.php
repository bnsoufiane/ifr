<?php

class SchoolDistrict extends Eloquent
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

    public function schools()
    {
        return $this->hasMany('School');
    }

}
