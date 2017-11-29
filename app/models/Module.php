<?php

class Module extends Eloquent
{
    use \Illuminate\Database\Eloquent\SoftDeletingTrait;

    protected $fillable = array('title', 'skin');
    protected static $rules = array(
        'title' => 'required|unique:modules,title'
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
            $rules['title'] .= ',' . $id;
        }

        return Validator::make($input, $rules);
    }

    //public function schools() {
    //   return $this->belongsToMany('School');
    //}

    public function series()
    {
        return $this->hasMany('Series');
    }

    public function lessons()
    {
        return $this->hasManyThrough('Lesson', 'Series');
    }
}
