<?php

namespace Admin;

class TeachersController extends GenericUsersController {
	protected $userGroup = 'Teacher';
	protected $title = 'Teachers';
	protected $singularTitle = 'Teacher';
	protected $route = 'admin.teachers';
}
