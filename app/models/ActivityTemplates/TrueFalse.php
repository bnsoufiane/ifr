<?php

namespace ActivityTemplates;

class TrueFalse extends YesNo implements \ActivityTemplate, \ActivityWithInput
{

    protected $table = 'activity_template_true_false';
    protected $fillable = array('description');

    public static function getMetaData()
    {
        return array(
            'name' => 'True/False',
            'admin_template' => 'admin/activity_templates/truefalse',
            'user_template' => 'activities/truefalse',
            'answer_template' => 'admin/answer_templates/truefalse'
        );
    }

    public function sections()
    {
        return $this->hasMany('ActivityTemplates\\TrueFalseSection');
    }

    public function options()
    {
        return $this->hasManyThrough(
            'ActivityTemplates\\TrueFalseOption', 'ActivityTemplates\\TrueFalseSection'
        );
    }

    public function delete_activity()
    {

    }

    public function saveFromArray($data)
    {

        $this->description = isset($data['template']['description']) ? $data['template']['description'] : '';
        $this->save();

        // Synchronizing sections.
        $updatedIds = array();

        foreach ($data['template']['sections'] as $sectionData) {
            if (isset($sectionData['id'])) {
                $section = $this->sections()->find($sectionData['id']);

                if ($section != null) {
                    $section->update($sectionData);
                    $section->updateOptionsFromArray($sectionData['options']);
                } else {

                    $_section = new TrueFalseSection($sectionData);
                    $_section->id = $sectionData['id'];
                    try {
                        $this->sections()->save($_section);

                        $options = array_map(function ($data) use ($_section) {
                            return $_section->options()->create($data);
                        }, $sectionData['options']);
                    } catch (\Exception $e) {
                        //$section = $this->sections()->find($sectionData['id']);
                        $section = $this->sections()->create($sectionData);

                        $options = array_map(function ($data) use ($section) {
                            return $section->options()->create($data);
                        }, $sectionData['options']);

                        $updatedIds[] = $section->id;
                    }

                }

                $updatedIds[] = $sectionData['id'];
            } else {
                $section = $this->sections()->create($sectionData);

                $options = array_map(function ($data) use ($section) {
                    return $section->options()->create($data);
                }, $sectionData['options']);

                $updatedIds[] = $section->id;
            }
        }

        $this->sections()->whereNotIn('id', $updatedIds)->delete();
    }

}
