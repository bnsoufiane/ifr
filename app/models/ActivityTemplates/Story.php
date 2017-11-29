<?php

namespace ActivityTemplates;

class Story extends \Eloquent implements \ActivityTemplate
{

    protected $table = 'activity_template_story';
    public $timestamps = false;
    protected $fillable = array('title');

    public static function getMetaData()
    {
        return array(
            'name' => 'Story',
            'admin_template' => 'admin/activity_templates/story',
            'user_template' => 'activities/story'
        );
    }

    public function activity()
    {
        return $this->morphOne('Activity', 'template');
    }

    public function items()
    {
        return $this->hasMany('ActivityTemplates\\StoryItem', 'activity_template_story_id');
    }

    public function toArray()
    {
        $data = array(
            'id' => $this->id,
            'title' => $this->title,
            'items' => $this->items->toArray()
        );

        return $data;
    }

    public function delete_activity()
    {

        $items = $this->items()->get();

        foreach ($items as $item) {
            $item->delete();
        }

        $this->delete();
    }

    public function saveFromArray($data)
    {
        $this->title = isset($data['template']['title']) ? $data['template']['title'] : '';
        $this->save();

        // Synchronizing story items.
        $updatedIds = array();

        foreach ($data['template']['items'] as $item) {

            $st_item = null;
            if (isset($item['id'])) {
                $st_item = StoryItem::find($item['id']);
            }

            if ($st_item != null) {
                $st_item->update($item);
                $this->items()->save($st_item);

                $updatedIds[] = $item['id'];
            } else {
                $option = new StoryItem($item);
                $this->items()->save($option);

                $updatedIds[] = $option->id;
            }
        }

        $this->items()->whereNotIn('id', $updatedIds)->delete();
    }

}
