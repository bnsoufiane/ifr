<?php


namespace ActivityTemplates;


class YesNoSection extends \Eloquent {
	use \Curotec\Models\SortableTrait;

    protected $table = 'activity_template_yesno_sections';
    public $timestamps = false;
    protected $fillable = array('title', 'order');

	public function yesNo() {
		return $this->belongsTo('ActivityTemplates\\YesNo');
	}

	public function options() {
		return $this->hasMany('ActivityTemplates\\YesNoOption');
	}

	public function toArray() {
		return array(
			'id' => $this->id,
			'title' => $this->title,
			'options' => $this->options->toArray()
		);
	}

	/**
	 * Updates related options.
	 */
	public function updateOptionsFromArray($data) {
		$idSet = array();

		foreach ($data as $optionData) {
			if (isset($optionData['id'])) {
                $this->options()->find($optionData['id'])->update($optionData);
				$idSet[] = $optionData['id'];
			} else {
				$option = $this->options()->create($optionData);
				$idSet[] = $option->id;
			}
		}

        $this->options()->whereNotIn('id', $idSet)->delete();
	}
}
