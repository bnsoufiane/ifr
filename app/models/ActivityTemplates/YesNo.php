<?php

namespace ActivityTemplates;

// Activity to select an option

class YesNo extends \Eloquent implements \ActivityTemplate, \ActivityWithInput
{

    protected $table = 'activity_template_yesno';
    public $timestamps = false;
    protected $fillable = array('description', 'yes_letter', 'no_letter');

    public static function getMetaData()
    {
        return array(
            'name' => 'Yes/No Options',
            'admin_template' => 'admin/activity_templates/yesno',
            'user_template' => 'activities/yesno',
            'answer_template' => 'admin/answer_templates/yesno'
        );
    }

    public function sections()
    {
        return $this->hasMany('ActivityTemplates\\YesNoSection');
    }

    public function toArray()
    {
        return array(
            'id' => $this->id,
            'description' => $this->description,
            'yes_letter' => $this->yes_letter,
            'no_letter' => $this->no_letter,
            'sections' => $this->sections->toArray()
        );
    }

    public function options()
    {
        return $this->hasManyThrough(
            'ActivityTemplates\\YesNoOption', 'ActivityTemplates\\YesNoSection'
        );
    }

    public function getStudentAnswer()
    {
        return 'StudentAnswers\\YesNoAnswer';
    }

    public function delete_activity()
    {

        $sections = $this->sections()->get();

        foreach ($sections as $section) {
            $options = $section->options()->get();
            foreach ($options as $option) {
                $option->delete();
            }
            $section->delete();
        }

        $this->delete();
    }

    public function saveFromArray($data)
    {
        $this->description = isset($data['template']['description']) ? $data['template']['description'] : '';
        $this->yes_letter = isset($data['template']['yes_letter']) ? $data['template']['yes_letter'] : 'X';
        $this->no_letter = isset($data['template']['no_letter']) ? $data['template']['no_letter'] : 'O';
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

                    $_section = new YesNoSection($sectionData);
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

        if (count($updatedIds) > 0) {
            $this->sections()->whereNotIn('id', $updatedIds)->delete();
        }
    }

}
