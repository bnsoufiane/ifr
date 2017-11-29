<?php

namespace ActivityTemplates;

class Cartoon extends \Eloquent implements \ActivityTemplate
{

    protected $table = 'activity_template_cartoon';
    public $timestamps = false;
    protected $fillable = array();

    public function pictures()
    {
        return $this->hasMany('ActivityTemplates\\CartoonPicture');
    }

    public static function getMetaData()
    {
        return array(
            'name' => 'Cartoon',
            'admin_template' => 'admin/activity_templates/cartoon',
            'user_template' => 'activities/cartoon'
        );
    }

    public function toArray()
    {
        return array(
            'id' => $this->id,
            'pictures' => $this->pictures->toArray()
        );
    }

    public function saveFromArray($data)
    {
        $this->save();

        // Synchronizing cartoon pictures.
        $updatedIds = array();

        foreach ($data['template']['pictures'] as $pictureData) {
            $cart = null;
            if (isset($pictureData['id'])) {
                $cart = CartoonPicture::find($pictureData['id']);
            }
            if (isset($pictureData['id']) && $cart != null) {
                $cart->update($pictureData);
                $this->pictures()->save($cart);
                $updatedIds[] = $pictureData['id'];
            } else {
                $picture = new CartoonPicture($pictureData);
                $this->pictures()->save($picture);
                $updatedIds[] = $picture->id;
            }
        }

        $this->pictures()->whereNotIn('id', $updatedIds)->delete();
    }

    public function delete_activity()
    {

        $pictures = $this->pictures()->get();

        foreach ($pictures as $picture) {
            $picture->delete();
        }

        $this->delete();
    }

}
