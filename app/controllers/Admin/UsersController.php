<?php


namespace Admin;

use Sentry;

class UsersController extends GenericUsersController {
	protected $title = 'Users';
	protected $singularTitle = 'User';
	protected $route = 'admin.users';
	protected $canEditGroups = true;
}
