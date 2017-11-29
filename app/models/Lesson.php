<?php

class Lesson extends Eloquent
{
    use \Illuminate\Database\Eloquent\SoftDeletingTrait;
    use \Curotec\Models\SortableTrait;

    protected $fillable = array('title', 'topic', 'order', 'optional', 'minimum_score');
    protected static $rules = array(
        'title' => 'required|max:255|unique:lessons,title',
        'topic' => 'max:20',
    );

    public static function validate($input, $id = null)
    {
        $rules = self::$rules;

        if ($id !== null) {
            // Do not check for uniqueness when editing.
            $rules['title'] .= ',' . $id;
        }

        return Validator::make($input, $rules);
    }

    public function series()
    {
        return $this->belongsTo('Series');
    }

    public function activities()
    {
        return $this->hasMany('Activity');
    }

    public function required($class)
    {
        $class = \SchoolClass::find($class);
        $is_required = $class->optional_lessons()->distinct()->where('optional_lesson_id', '=', $this->id)->count()==0;
        return $is_required;
    }

}
