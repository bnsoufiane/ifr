<?php

// Adds a default system user "curotec"
class DefaultUserSeeder extends Seeder {
	public function run() {
		// System administrator
		$user = Sentry::createUser(array(
			'username' => 'curotec',
			'password' => 'curotec',
            'first_name' => 'System',
            'last_name' => 'Administrator',
			'activated' => true
		));

        $user->addGroup(Sentry::findGroupByName('System Administrator'));

		// School administrator
		$user = Sentry::createUser(array(
			'username' => 'school_admin',
			'password' => 'curotec',
            'first_name' => 'School',
            'last_name' => 'Admin',
			'activated' => true
		));

        $user->addGroup(Sentry::findGroupByName('School Administrator'));

		$user = Sentry::createUser(array(
			'username' => 'school_admin2',
			'password' => 'curotec',
            'first_name' => 'Godric',
            'last_name' => 'Gryffindor',
			'activated' => true
		));

        $user->addGroup(Sentry::findGroupByName('School Administrator'));

		// Teachers
		$user = Sentry::createUser(array(
			'username' => 'teacher',
			'password' => 'curotec',
            'first_name' => 'Walter',
            'last_name' => 'White',
			'activated' => true
		));

        $user->addGroup(Sentry::findGroupByName('Teacher'));

		$user = Sentry::createUser(array(
			'username' => 'teacher2',
			'password' => 'curotec',
            'first_name' => 'Gilderoy',
            'last_name' => 'Lockhart',
			'activated' => true
		));

        $user->addGroup(Sentry::findGroupByName('Teacher'));

		$user = Sentry::createUser(array(
			'username' => 'teacher3',
			'password' => 'curotec',
            'first_name' => 'Quirinus',
            'last_name' => 'Quirrell',
			'activated' => true
		));

        $user->addGroup(Sentry::findGroupByName('Teacher'));

		// Students
		$user = Sentry::createUser(array(
			'username' => 'student',
			'password' => 'curotec',
            'first_name' => 'Jesse',
            'last_name' => 'Pinkman',
			'activated' => true
		));

        $user->addGroup(Sentry::findGroupByName('Student'));

		$user = Sentry::createUser(array(
			'username' => 'student2',
			'password' => 'curotec',
            'first_name' => 'Harry',
            'last_name' => 'Potter',
			'activated' => true
		));

        $user->addGroup(Sentry::findGroupByName('Student'));

		$user = Sentry::createUser(array(
			'username' => 'student3',
			'password' => 'curotec',
            'first_name' => 'Hermione',
            'last_name' => 'Granger',
			'activated' => true
		));

        $user->addGroup(Sentry::findGroupByName('Student'));
	}
}
