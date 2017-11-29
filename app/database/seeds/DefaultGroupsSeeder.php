<?php

class DefaultGroupsSeeder extends Seeder {

    public function run() {
        // System administrators
        Sentry::createGroup(array(
            'name' => 'System Administrator',
            'permissions' => array(
                'admin.*' => 1,
            )
        ));

        // School administrators
        Sentry::createGroup(array(
            'name' => 'School Administrator',
            'permissions' => array(
                'admin.index' => 1,
                'admin.classes.*' => 1,
                'admin.teachers.*' => 1,
                'admin.students.*' => 1,
                'admin.schools.preview' => 1,
                'admin.schools.preview_activity' => 1,
                'admin.help' => 1
            )
        ));

        // Teachers
        Sentry::createGroup(array(
            'name' => 'Teacher',
            'permissions' => array(
                'admin.index' => 1,
                'admin.classes.*' => 1,
                'admin.students.*' => 1,
                'admin.reports.*' => 1,
                'admin.tests.*' => 1,
                'admin.help' => 1,
                'admin.schools.preview' => 1,
                'admin.schools.preview_activity' => 1,
                'tests' => 1
            )
        ));

        // Students
        Sentry::createGroup(array(
            'name' => 'Student',
            'permissions' => array(
            )
        ));
    }

}
