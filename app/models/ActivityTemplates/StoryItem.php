<?php

namespace ActivityTemplates;

class StoryItem extends \Eloquent {
	use \Curotec\Models\SortableTrait;

    protected $table = 'activity_template_story_items';
    public $timestamps = false;
    protected $fillable = array('is_right_side', 'text', 'character_id', 'order');

    public function story() {
        return $this->belongsTo('ActivityTemplates\\Story');
    }

    public function character() {
        return $this->belongsTo('ActivityTemplates\\StoryCharacter');
    }

}
