<?php

namespace StudentAnswers;

class Json extends \Eloquent implements \StudentAnswerType {
	protected $table = 'student_answers_json';
	protected $fillable = array('json');
	public $timestamps = false;

	private $jsonVal = false;

	public function setJsonAttribute($value) {
		$this->attributes['json'] = \json_encode($value);
	}

	public function getJsonAttribute() {
		if ($this->jsonVal) {
			// Return cached value
			return $this->jsonVal;
		}
		$this->jsonVal = \json_decode($this->attributes['json'], true);
		return $this->jsonVal;
	}

	public function getMetaData() {
		return array('admin_template' => 'admin/answer_templates/json_table');
	}

	public function saveFromArray($data, $activity) {
		$this->json = $data;
		$this->save();
	}
}
