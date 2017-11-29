<?php


namespace ActivityTemplates;

//use Illuminate\Database\Eloquent\SoftDeletingTrait;

class StoryCharacter extends \Eloquent {
  //  use SoftDeletingTrait;
    protected $table = 'story_characters';
    public $timestamps = false;
    public $fillable = array('name', 'picture');
    
    public function story() {
        return $this->belongsTo('ActivityTemplates\\StoryItem', 'character_id');
    }
    
}
