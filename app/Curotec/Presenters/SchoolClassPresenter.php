<?php

namespace Curotec\Presenters;

class SchoolClassPresenter extends Presenter {
	private $class = null;

	public function __construct($class) {
		$this->class = $class;
	}

	public function id() {
		return $this->class->id;
	}

	public function name() {
		return $this->class->name;
	}

    public function school() {
        return $this->class->school->name;
    }

    public function teacher() {
        $teacher = \User::find($this->class->created_by);
        return $teacher->last_name.", ".$teacher->first_name;
    }

	public function students() {
		$students = array();
		
		$this->class->students()->get()->each(function ($student) use (&$students) {
			$students[] = link_to_route(
				'admin.users.edit',
				$student->first_name . ' ' . $student->last_name,
				$student->id
			);
		});

		if (!$students) {
			return 'None';
		} else {
			return join(', ', $students);
		}
	}
}
