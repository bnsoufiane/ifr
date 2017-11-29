<?php

namespace ActivityTemplates;

class Fillblank extends \Eloquent implements \ActivityTemplate, \ActivityWithInput
{

    protected $table = 'activity_template_fillblank';
    public $timestamps = false;
    protected $fillable = array('description');

    public static function getMetaData()
    {
        return array(
            'name' => 'Fill in the blank',
            'admin_template' => 'admin/activity_templates/fillblank',
            'user_template' => 'activities/fillblank',
            'answer_template' => 'admin/answer_templates/fillblank',
        );
    }

    public function items()
    {
        return $this->hasMany('ActivityTemplates\\FillblankItem');
    }

    public function toArray()
    {
        return array(
            'id' => $this->id,
            'description' => $this->description,
            'items' => $this->items->toArray()
        );
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
        $this->description = $data['template']['description'];
        $this->save();

        // Synchronizing select options.
        $updatedIds = array();

        foreach ($data['template']['items'] as $itemData) {
            if (isset($itemData['id'])) {
                $_fillblank = FillblankItem::find($itemData['id']);

                if ($_fillblank != null) {
                    $_fillblank = FillblankItem::find($itemData['id']);
                    $_fillblank->update($itemData);
                    $this->items()->save($_fillblank);

                } else {
                    $_fillblank = new FillblankItem($itemData);
                    $_fillblank->id = $itemData['id'];
                    $this->items()->save($_fillblank);
                }

                $updatedIds[] = $itemData['id'];
            } else {
                $item = new FillblankItem($itemData);
                $this->items()->save($item);
                $updatedIds[] = $item->id;
            }
        }

        $this->items()->whereNotIn('id', $updatedIds)->delete();
    }

    /**
     * Creates a "student answer" model for this activity template.
     */
    public function getStudentAnswer() {
        return 'StudentAnswers\\FillblankAnswers';
    }

}
