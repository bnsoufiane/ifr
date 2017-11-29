<?php

namespace Admin;

use Sentry;
use App;
use Input;
use Redirect;

abstract class GenericUsersController extends BaseController
{

    protected $title = '';
    protected $route = '';
    protected $canEditGroups = false;
    protected $viewsBase = 'admin/users';

    public function __construct()
    {
        $this->beforeFilter(function () {
            // Check if a user has cancelled editing.
            if (Input::has('cancel')) {
                return Redirect::route($this->route . '.index');
            }
        }, array('only' => array('store', 'update')));

        // Adds variables to determine if this view has an option to
        // change users' groups.
        \View::composer(array(
            $this->viewsBase . '/index',
            $this->viewsBase . '/create',
            $this->viewsBase . '/edit'
        ), function ($view) {
            $view->with('canEditGroups', $this->canEditGroups);

            if ($this->canEditGroups) {
                $view->with('groups', Sentry::findAllGroups());
            }
        });

        // Adds variables to determine if the current user can edit
        // users' schools.
        \View::composer(array(
            $this->viewsBase . '/index',
            $this->viewsBase . '/create',
            $this->viewsBase . '/edit'
        ), function ($view) {
            $isSysAdmin = \Sentry::getUser()->isSysAdmin();

            $view->with('canEditSchool', $isSysAdmin);

            if ($isSysAdmin) {
                $schoolsList = array_reduce(\School::all()->toArray(), function ($schools, $school) {
                    $schools[$school['id']] = $school['name'];
                    return $schools;
                }, array("NULL" => ' - No Attached School -'));

                $view->with('schools', $schoolsList);
            }
        });
    }

    protected function findUsers()
    {
        $users = \User::fromUsersSchool(\Sentry::getUser())->orderBy('last_name');
        $currentUser = \Sentry::getUser();

        if ($currentUser->isTeacher()) {
            $teacher_classes = \SchoolClass::select("id")->where('created_by', '=', $currentUser->id)->get();
            $teacher_classes_ids = array();
            foreach ($teacher_classes as $teacher_class) {
                $teacher_classes_ids[] = $teacher_class->id;
            }

            $students = array();
            $users = $users->get();
            foreach ($users as $usr) {
                $user_classes = \DB::table('school_class_student')->where('student_id', '=', $usr->id)->get();
                $user_classes_ids = array();
                foreach ($user_classes as $user_class) {
                    $user_classes_ids[] = $user_class->school_class_id;
                }

                if (count(array_intersect($teacher_classes_ids, $user_classes_ids)) > 0) {
                    array_push($students, $usr);
                }
            }

            return $students;

        } else {
            if (!empty($this->userGroup)) {
                // Return users of a certain group.
                $group = \Sentry::findGroupByName($this->userGroup);
                return $users->ofGroup($group);
            }
        }


        return $users;
    }

    public function index()
    {
        $currentUser = \Sentry::getUser();

//        if ($currentUser->isTeacher()) {
//            $users = $this->findUsers();
//        } else {
//            $users = $this->findUsers()->get();
//        }

        $page_title = 'IFR';
        if ($this->title == 'Teachers') {
            $page_title = 'IFR - Teachers';
            $this->layout->with('page_title', $page_title);

            $this->layout->content = \View::make('admin/users/teachers_index')
                ->with('title', $this->title)
                ->with('singularTitle', $this->singularTitle)
                ->with('baseRoute', $this->route)
                ->with('currentUser', $currentUser)
                ->with('page_title', $page_title);
            return;
        }
        if ($this->title == 'Students') {
            $this->layout->content = \View::make('admin/users/students_index')
                ->with('title', $this->title)
                ->with('singularTitle', $this->singularTitle)
                ->with('baseRoute', $this->route)
                ->with('currentUser', $currentUser)
                ->with('page_title', $page_title);

            return;
        }
        if ($this->title == 'Users') {
            $this->layout->content = \View::make('admin/users/users_index')
                ->with('title', $this->title)
                ->with('singularTitle', $this->singularTitle)
                ->with('baseRoute', $this->route)
                ->with('currentUser', $currentUser)
                ->with('page_title', $page_title);

            return;
        }

//        $this->layout->content = \View::make('admin/users/index')
//            ->with('users', $users)
//            ->with('title', $this->title)
//            ->with('singularTitle', $this->singularTitle)
//            ->with('baseRoute', $this->route)
//            ->with('currentUser', $currentUser)
//            ->with('page_title', $page_title);
//        return;
    }

    public function create()
    {
        $this->layout->content = \View::make('admin/users/create')
            ->with('baseRoute', $this->route)
            ->with('route', $this->route . '.store')
            ->with('title', $this->title)
            ->with('singularTitle', $this->singularTitle)
            ->with('user', new \User());
    }

    public function edit($id)
    {
        $users = $this->findUsers();

        $user = null;
        if (gettype($users) == "array") {
            foreach ($users as $usr) {
                if ($usr->id == $id) {
                    $user = $usr;
                    break;
                }
            }
        } else {
            $user = $this->findUsers()->find($id);
        }

        if (!$user) {
            App::abort(404);
        }

        $this->layout->content = \View::make('admin/users/edit')
            ->with('baseRoute', $this->route)
            ->with('route', array($this->route . '.update', $id))
            ->with('user', $user)
            ->with('title', $this->title)
            ->with('singularTitle', $this->singularTitle)
            ->with('method', 'PUT');
    }

    public function store()
    {
        $input = Input::get();

        if (isset($input['fakeusernameremembered'])) {
            unset($input['fakeusernameremembered']);
        }
        if (isset($input['fakepasswordremembered'])) {
            unset($input['fakepasswordremembered']);
        }

        if (isset($input['school_id']) && ($input['school_id'] == 'NULL')) {
            unset($input['school_id']);
        }
        //die();

        unset($input['id']);

        if (isset($input['group_ids'])) {
            $groups = $input['group_ids'];
            unset($input['group_ids']);
        } else {
            $groups = array();
        }

        \DB::beginTransaction();

        // Validate input
        $validator = \User::preValidate($input);

        if ($validator->fails()) {
            return Redirect::route($this->route . '.create')
                ->withInput()
                ->withErrors($validator);
        }

        // Create a user
        $currentUser = \Sentry::getUser();
        $user = \Sentry::createUser($input);

        // Assign a group(s) to the user
        if ($this->canEditGroups) {
            foreach ($groups as $groupId) {
                $user->addGroup(Sentry::findGroupById($groupId));
            }
        } else if ($this->userGroup) {
            $user->addGroup(Sentry::findGroupByName($this->userGroup));
        }

        // Assign a school
        if ($currentUser->isSysAdmin()) {

            if (!empty($input['school_id'])) {

                $school = \School::find($input['school_id']);

                $user->school()
                    ->associate($school);
            }
        } else if ($currentUser->school) {
            $user->school()
                ->associate($currentUser->school);
        }

        $user->activated = TRUE;
        $user->save();

        \DB::commit();

        return Redirect::route($this->route . '.index')
            ->with('success', $this->singularTitle . ' has been successfully created.');
    }

    public function update()
    {
        $input = Input::get();

        if (isset($input['fakeusernameremembered'])) {
            unset($input['fakeusernameremembered']);
        }
        if (isset($input['fakepasswordremembered'])) {
            unset($input['fakepasswordremembered']);
        }

        if (isset($input['school_id']) && ($input['school_id'] == 'NULL')) {
            unset($input['school_id']);
        }

        if (isset($input['group_ids'])) {
            $groups = $input['group_ids'];
            unset($input['group_ids']);
        } else {
            $groups = array();
        }

        $users = $this->findUsers();

        $user = null;
        if (gettype($users) == "array") {
            foreach ($users as $usr) {
                if ($usr->id == $input['id']) {
                    $user = $usr;
                    break;
                }
            }
        } else {
            $user = $this->findUsers()->find($input['id']);
        }

        if (!$user) {
            App::abort(404);
        }


        // Validate input
        $validator = \User::preValidate($input, $input['id']);

        if ($validator->fails()) {
            return Redirect::route($this->route . '.edit', $input['id'])
                ->withInput()
                ->withErrors($validator);
        }

        // Update user
        \DB::beginTransaction();

        if (empty($input['password'])) {
            unset($input['password']);
        }

        $user->fill($input);

        $user->activated = TRUE;
        $user->save();

        if ($this->canEditGroups) {
            // Reattach groups to a user if we have an option to edit the user's groups.
            $user->groups()->detach();

            foreach ($groups as $groupId) {
                $user->addGroup(Sentry::findGroupById($groupId));
            }
        }

        \DB::commit();

        return Redirect::route($this->route . '.index')
            ->with('success', $this->singularTitle . ' has been successfully updated.');
    }

    public function destroy($id)
    {
        $users = $this->findUsers();

        $user = null;
        if (gettype($users) == "array") {
            foreach ($users as $usr) {
                if ($usr->id == $id) {
                    $user = $usr;
                    break;
                }
            }
        } else {
            $user = $this->findUsers()->find($id);
        }

        if (!$user) {
            App::abort(404);
        }
        $user->delete();

        return array('ok' => 1);
    }

}
