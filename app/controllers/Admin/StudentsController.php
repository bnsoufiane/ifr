<?php

namespace Admin;

class StudentsController extends GenericUsersController {
	protected $userGroup = 'Student';
	protected $title = 'Students';
	protected $singularTitle = 'Student';
	protected $route = 'admin.students';

	/**
	 * Returns a list of students that belongs to the same school as the current user.
	 */
	public function fromMySchool() {
		return $this->findUsers()->get();
	}
}
