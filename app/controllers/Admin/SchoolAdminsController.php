<?php

namespace Admin;

/**
 * A controller to observe the list of school admins and API to get
 * unassigned admins.
 */

class SchoolAdminsController extends BaseController {
	public function index() {
		
	}

	/**
	 * Returns a list of admins that has no assigned schools.
	 */
	public function freeAdmins() {
		$schoolAdminsGroup = \Sentry::findGroupByName('School Administrator');

                return \User::ofGroup($schoolAdminsGroup)->get();
                
		//return \User::withoutSchool()->ofGroup($schoolAdminsGroup)->get();
	}
}
