<?php

namespace Curotec\Presenters;

class ClassStudentPresenter extends Presenter {
	private $student = null;

	public function __construct($student) {
		$this->student = $student;
	}

	public function id() {
		return $this->student->id;
	}

	public function fullName() {
		return $this->student->first_name . ' ' . $this->student->last_name;
	}

	public function formattedName() {
		if (!$this->student->last_name) {
			return $this->student->first_name;
		}
		return $this->student->last_name . ', ' . $this->student->first_name;
	}

	public function answersTo($lesson) {
		return new ClassAnsweredLessonPresenter($lesson, array('student' => $this->student));
	}

	public function answeredLessons() {
		return ClassAnsweredLessonPresenter::wrap(
			$this->student->answeredLessons(),
			array('student' => $this->student)
		);
	}
}
