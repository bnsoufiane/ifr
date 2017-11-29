<?php

namespace ActivityTemplates;

class Calculation extends \Eloquent implements \ActivityTemplate, \ActivityWithInput
{

    protected $table = 'activity_template_calculation';
    public $timestamps = false;
    protected $fillable = array('description', 'name', 'column_1', 'column_2', 'column_3');

    public static function getMetaData()
    {
        return array(
            'name' => 'Calculation Table',
            'admin_template' => 'admin/activity_templates/calculation',
            'user_template' => 'activities/calculation',
            'answer_template' => 'admin/answer_templates/calculation'
        );
    }

    public function items()
    {
        return $this->hasMany('ActivityTemplates\\CalculationItem');
    }

    public function footers()
    {
        return $this->hasMany('ActivityTemplates\\CalculationFooter');
    }

    public function toArray()
    {
        return array(
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'column_1' => $this->column_1,
            'column_2' => $this->column_2,
            'column_3' => $this->column_3,
            'items' => $this->items->toArray(),
            'footers' => $this->footers->toArray()
        );
    }

    public function delete_activity()
    {

        $items = $this->items()->get();
        foreach ($items as $item) {
            $item->delete();
        }

        $footers = $this->footers()->get();
        foreach ($footers as $footer) {
            $footer->delete();
        }

        $this->delete();
    }

    public function saveFromArray($data)
    {
        $this->name = empty($data['template']['name']) ? " " : $data['template']['name'];
        $this->description = $data['template']['description'];
        $this->column_1 = $data['template']['column_1'];
        $this->column_2 = $data['template']['column_2'];
        $this->column_3 = $data['template']['column_3'];
        $this->save();

        // Synchronizing select options.
        $updatedItemIds = array();

        foreach ($data['template']['items'] as $itemData) {
            if (isset($itemData['id'])) {
                $_calculation = CalculationItem::find($itemData['id']);

                if ($_calculation != null) {
                    $_calculation = CalculationItem::find($itemData['id']);
                    $_calculation->update($itemData);
                    $this->items()->save($_calculation);

                } else {
                    $_calculation = new CalculationItem($itemData);
                    $_calculation->id = $itemData['id'];
                    $this->items()->save($_calculation);
                }

                $updatedItemIds[] = $itemData['id'];
            } else {
                $item = new CalculationItem($itemData);
                $this->items()->save($item);
                $updatedItemIds[] = $item->id;
            }
        }

        $this->items()->whereNotIn('id', $updatedItemIds)->delete();

        if (isset($data['template']['footers'])) {
            $updatedFooterIds = array();

            foreach ($data['template']['footers'] as $footerData) {
                if (isset($footerData['id'])) {
                    $_calculation = CalculationFooter::find($footerData['id']);

                    if ($_calculation != null) {
                        $_calculation->update($footerData);
                        $this->footers()->save($_calculation);

                    } else {
                        $_calculation = new CalculationFooter($footerData);
                        $_calculation->id = $footerData['id'];
                        $this->footers()->save($_calculation);
                    }

                    $updatedFooterIds[] = $footerData['id'];
                } else {
                    $footer = new CalculationFooter($footerData);
                    $this->footers()->save($footer);
                    $updatedFooterIds[] = $footer->id;
                }
            }

            if (count($updatedFooterIds)) {
                $this->footers()->whereNotIn('id', $updatedFooterIds)->delete();
            } else {
                $this->footers()->delete();
            }
        }
    }

    public function getStudentAnswer() {
        return 'StudentAnswers\\CalculationAnswers';
    }
}
