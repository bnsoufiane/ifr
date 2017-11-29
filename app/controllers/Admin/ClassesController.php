<?php

namespace Admin;

use View;
use Input;
use Redirect;
use Curotec\Presenters\SchoolClassPresenter;

class ClassesController extends BaseController
{

    public function __construct()
    {
        $this->beforeFilter(function () {
            // Check if a user has cancelled editing.
            if (Input::has('cancel')) {
                return Redirect::route('admin.classes.index');
            }
        }, array('only' => array('store', 'update')));

        $this->addAdminComposer();
    }

    /**
     * Allows sys admins to select a school.
     */
    private function addAdminComposer()
    {
        View::composer(array('admin/classes/edit', 'admin/classes/create'), function ($view) {
            $currentUser = \Sentry::getUser();
            $isSysAdmin = $currentUser->isSysAdmin();
            $isSchoolAdminUser = $currentUser->isSchoolAdmin();

            if ($isSysAdmin) {
                $schoolsList = array_reduce(\School::all()->toArray(), function ($schools, $school) {
                    $schools[$school['id']] = $school['name'];
                    return $schools;
                }, array());

                $view->with('schools', $schoolsList);
            }

            $view->with('isSysAdminUser', $isSysAdmin);
            $view->with('isSchoolAdminUser', $isSchoolAdminUser);
        });
    }

    protected function findClasses()
    {
        $currentUser = \Sentry::getUser();
        if ($currentUser->isTeacher()) {
            return \SchoolClass::where('created_by', '=', $currentUser->id);
        } else {
            return \SchoolClass::fromUsersSchool(\Sentry::getUser());
        }

    }

    /**
     * Classes list.
     */
    public function index()
    {
//        $classesList = $this->findClasses()->get();

        $this->layout->content = View::make('admin/classes/index');
//            ->with('classes', SchoolClassPresenter::wrap($classesList));
    }

    public function show_students($id)
    {
        $class = $this->findClasses()->find($id);
        $students = $class->students()->distinct()->get();

        $this->layout->content = View::make('admin/classes/students')
            ->with('class', $class)
            ->with('students', $students);

        $this->layout->with('page_title', "IFR-" . $class->name);
    }

    public function add_students($id)
    {
        $class = $this->findClasses()->find($id);

        $this->layout->content = View::make('admin/classes/add_students')
            ->with('class', $class)
            ->with('route', array('admin.classes.store_students', $id));
    }

    public function edit_students($id)
    {
        $class = $this->findClasses()->find($id);
        $students = $class->students()->distinct()->get();
        $input = array();

        $i = 1;
        foreach ($students as $student) {
            $input['id_' . $i] = $student->id;
            $input['first_name_' . $i] = trim($student->first_name);
            $input['last_name_' . $i] = trim($student->last_name);
            $input['username_' . $i] = trim($student->username);
            //$input['pass_' . $i] = $student->password;

            $i++;
        }

        $this->layout->content = View::make('admin/classes/edit_students')
            ->with('class', $class)
            ->with('route', array('admin.classes.store_updated_students', $id))
            ->with('input', $input);
    }

    public function edit_student($class_id, $student_id)
    {
        $class = $this->findClasses()->find($class_id);
        $student = \Student::find($student_id);

        $input = array();
        $input['id'] = $student->id;
        $input['first_name'] = trim($student->first_name);
        $input['last_name'] = trim($student->last_name);
        $input['username'] = trim($student->username);

        $this->layout->content = View::make('admin/classes/edit_student')
            ->with('route', array('admin.classes.store_updated_student', $class_id, $student_id))
            ->with('input', $input)
            ->with('class', $class)
            ->with('student', $student);
    }

    public function store_updated_student($class_id, $id)
    {
        $input = Input::all();
        $student = \Student::find($id);

        if (!isset($input['username'])) {
            $class = $this->findClasses()->find($class_id);

            \DB::beginTransaction();
            $class->students()->detach($student->id);
            \DB::commit();

            return Redirect::route('admin.classes.index')
                ->with('success', 'Student has been successfully deleted.');
        }
        $validator = \SchoolClass::validateStudentInput($input, $id, $student->username);
        $currentUser = \Sentry::getUser();

        if ($validator->fails()) {
            return Redirect::route('admin.classes.edit_student', array($class_id, $id))
                ->withInput(\Input::old())
                ->withErrors($validator)
                ->with('input', \Input::old())
                ->with('error', "An error has occurred");
        } else {

            \DB::beginTransaction();

            $student->first_name = trim(Input::get('first_name'));
            $student->last_name = trim(Input::get('last_name'));
            $student->username = trim(Input::get('username'));
            if (Input::get('pass') != "") {
                $student->password = Input::get('pass');
            }
            $student->save();

            \DB::commit();

        }

        return Redirect::route('admin.classes.index')
            ->with('success', 'Student has been successfully updated.');

    }

    public function store_students($id)
    {
        $new_students_count = 0;
        $input = Input::all();
        foreach ($input as $key => $value) {
            if (strpos($key, 'first_name') !== false) {
                $new_students_count++;
            }
        }

        $validator = \SchoolClass::validateStudentsInput($input);

        $currentUser = \Sentry::getUser();

        if ($validator->fails()) {

            return Redirect::route('admin.classes.add_students', array($id))
                ->withInput($input)
                ->withErrors($validator)
                ->with('error', "An error has occurred");
        } else {

            \DB::beginTransaction();

            $class = $this->findClasses()->find($id);

            $default_password = $class->default_password;

            for ($i = 1; $i <= $new_students_count; $i++) {
                $usrname = Input::get('username_' . $i);
                if (!empty($usrname)) {
                    $tab = array();
                    $tab['first_name'] = trim(Input::get('first_name_' . $i));
                    $tab['last_name'] = trim(Input::get('last_name_' . $i));
                    $tab['username'] = trim($usrname);
                    $tab['email'] = trim(Input::get('email_' . $i));
                    $tab['password'] = $default_password;
                    $user = \Sentry::createUser($tab);
                    $user->activated = TRUE;
                    $user->school()->associate($class->school);
                    $user->addGroup(\Sentry::findGroupByName("Student"));
                    $user->save();
                    $students[] = $user->id;
                }
            }

            if (isset($students)) {
                $class->students()->attach($students);
                \DB::commit();
            }

        }

        return Redirect::route('admin.classes.index')
            ->with('success', 'Students have been successfully created.');
    }

    public function store_updated_students($id)
    {
        return "store_updated_students";

        $new_students_count = 0;
        $input = Input::all();
        foreach ($input as $key => $value) {
            if (strpos($key, 'first_name') !== false) {
                $new_students_count++;
            }
        }

        $validator = \SchoolClass::validateStudentsInput($input);

        $currentUser = \Sentry::getUser();

        if ($validator->fails()) {
            return Redirect::route('admin.classes.edit_students', array($id))
                ->withInput(\Input::old())
                ->withErrors($validator)
                ->with('input', \Input::old())
                ->with('error', "An error has occurred");
        } else {

            \DB::beginTransaction();

            $class = $this->findClasses()->find($id);

            for ($i = 1; $i <= $new_students_count; $i++) {
                $usrname = Input::get('username_' . $i);
                if (!empty($usrname)) {
                    $tab = array();
                    $tab['first_name'] = trim(Input::get('first_name_' . $i));
                    $tab['last_name'] = trim(Input::get('last_name_' . $i));
                    $tab['username'] = trim($usrname);
                    $tab['password'] = Input::get('pass_' . $i);

                    if (Input::get('id_' . $i) !== null) {
                        $user = \Student::find(Input::get('id_' . $i));
                        $user->first_name = Input::get('first_name_' . $i);
                        $user->last_name = Input::get('last_name_' . $i);
                        $user->username = $usrname;
                        if (Input::get('pass_' . $i) != "") {
                            $user->password = Input::get('pass_' . $i);
                        }
                    } else {
                        $user = \Sentry::createUser($tab);
                        $user->first_name = trim(Input::get('first_name_' . $i));
                        $user->last_name = trim(Input::get('last_name_' . $i));
                        $user->username = trim($usrname);
                        $user->password = Input::get('pass_' . $i);
                        $user->activated = TRUE;
                        $user->school()->associate($class->school);
                        $user->addGroup(\Sentry::findGroupByName("Student"));
                    }
                    $user->save();
                    $students[] = $user->id;
                }
            }

            //$class->students()->attach($students);
            //$class->students()->sync($students);

            \DB::commit();

        }

        return Redirect::route('admin.classes.index')
            ->with('success', 'Students have been successfully updated.');
    }

    public function destroy($id)
    {
        return array('result' => \SchoolClass::destroy($id) > 0);
    }

    public function create()
    {
        $teachers = \User::getTeachers();
        $this->layout->content = View::make('admin/classes/create')
            ->with('class', new \SchoolClass())
            ->with('teachers', $teachers)
            ->with('route', 'admin.classes.store');
    }

    public function edit($id)
    {
        $class = $this->findClasses()->find($id);

        if (!$class) {
            \App::abort(404);
        }

        $teachers = \User::getTeachers();


        $test = \Tests::getBySchoolClass($id);
        if ($test != null) {

            $test_conf = \TestConfiguration::getByTestId($test->id);

            if (count($test_conf) == 0) {
                $test_configuration = null;
            } else {
                foreach ($test_conf as $key => $value) {
                    $str = ($value['test_type'] == \Tests::POST) ? "posttest" : "pretest";
                    $str .= "_" . $value['section_id'];

                    $test_configuration[$str] = true;
                }
            }
        } else {
            $test_configuration = null;
        }

        if ($test_configuration) {
            $can_remove_class = false;
        } else {
            $can_remove_class = true;
        }

        $this->layout->content = View::make('admin/classes/edit')
            ->with('class', $class)
            ->with('route', array('admin.classes.update', $id))
            ->with('students', $class->students()->get()->toJson())
            ->with('teachers', $teachers)
            ->with('default_teacher', $class->created_by)
            ->with('can_remove_class', $can_remove_class)
            ->with('method', 'PUT');
    }

    public function update($id)
    {
        $new_students_count = 0;
        foreach (Input::all() as $key => $value) {
            if (strpos($key, 'first_name') !== false) {
                $new_students_count++;
            }
        }

        $currentUser = \Sentry::getUser();

        $input = Input::all();
        $validator = \SchoolClass::validate($input, $id);

        if ($validator->fails()) {
            return Redirect::route('admin.classes.edit', $id)
                ->withInput()
                ->withErrors($validator);
        } else {
            $students = (isset($input['student_id']) ? $input['student_id'] : array());

            $class = $this->findClasses()->find($id);

            if (!$class) {
                \App::abort(404);
            }

            $oldDefaultPassword = $class->default_password;
            $class->fill($input);
            $newDefaultPassword = $class->default_password;

            if ($currentUser->isTeacher()) {
                $creator = $currentUser;
            } else {
                $creator = \User::find($input['teacher_id']);
            }
            $class->created_by()->associate($creator);

            if ($currentUser->isTeacher()) {
                $class->school()
                    ->associate($currentUser->school);
            } else {
                $class->school()
                    ->associate($creator->school);
            }

            $assignedStudents = $class->students()->get();

            foreach ($assignedStudents as $studentItem) {

                if (\Hash::check($oldDefaultPassword, $studentItem->password)) {
                    $studentItem->password = $newDefaultPassword;
                    $studentItem->save();
                }
            }

            $class->save();

            if (isset($input['module_id'])) {
                $class->series()->sync($input['module_id']);
            } else {
                $class->series()->sync(array());
            }

            for ($i = 1; $i <= $new_students_count; $i++) {
                $usrname = Input::get('username_' . $i);
                if (!empty($usrname)) {
                    $tab = array();
                    $tab['first_name'] = trim(Input::get('first_name_' . $i));
                    $tab['last_name'] = trim(Input::get('last_name_' . $i));
                    $tab['username'] = trim($usrname);
                    $tab['password'] = $class->default_password;

                    $validator = \SchoolClass::validateStudentsInput($tab);
                    if ($validator->fails()) {

                        return Redirect::route('admin.classes.edit', array($class->id))
                            ->withInput($tab)
                            ->withErrors($validator)
                            ->with('error', "An error has occurred");
                    } else {
                        $user = \Sentry::createUser($tab);
                        $user->activated = TRUE;
                        $user->school()->associate($class->school);
                        $user->addGroup(\Sentry::findGroupByName("Student"));
                        $user->save();
                        $students[] = $user->id;
                    }
                }
            }

            if (!$currentUser->isTeacher()) {
                $class->students()->sync($students);
            }

            return Redirect::route('admin.classes.index')
                ->with('success', 'A class has been successfully edited.');
        }
    }

    public function store()
    {
        $new_students_count = 0;
        foreach (Input::all() as $key => $value) {
            if (strpos($key, 'first_name') !== false) {
                $new_students_count++;
            }
        }

        $input = Input::all();
        $validator = \SchoolClass::validate($input);

        if ($validator->fails()) {
            return Redirect::route('admin.classes.create')
                ->withInput()
                ->withErrors($validator);
        } else {
            $students = (isset($input['student_id']) ? $input['student_id'] : array());

            $currentUser = \Sentry::getUser();
            unset($input['id']);

            \DB::beginTransaction();

            $class = new \SchoolClass($input);

            if ($currentUser->isTeacher()) {
                $creator = $currentUser;
            } else {
                $creator = \User::find($input['teacher_id']);
            }
            $class->created_by()->associate($creator);


            if ($currentUser->isTeacher()) {
                $class->school()
                    ->associate($currentUser->school);
            } else {
                $class->school()
                    ->associate($creator->school);
            }

            $class->save();
            //$class->students()->sync($students);

            if (isset($input['module_id'])) {
                $class->series()->sync($input['module_id']);
            }

            for ($i = 1; $i <= $new_students_count; $i++) {
                $usrname = Input::get('username_' . $i);
                if (!empty($usrname)) {
                    $tab = array();
                    $tab['first_name'] = trim(Input::get('first_name_' . $i));
                    $tab['last_name'] = trim(Input::get('last_name_' . $i));
                    $tab['username'] = trim($usrname);
                    $tab['password'] = Input::get('pass_' . $i);
                    $user = \Sentry::createUser($tab);
                    $user->activated = TRUE;
                    $user->school()->associate($currentUser->school);
                    $user->addGroup(\Sentry::findGroupByName("Student"));
                    $user->save();
                    $students[] = $user->id;
                }
            }

            $class->students()->sync($students);
            \DB::commit();

            return Redirect::route('admin.classes.index')
                ->with('success', 'A class has been successfully created.');
        }
    }

    public function save_students($id)
    {
        /*$input = Input::get('students_id');
        $students = explode(',', $input);

        $class = $this->findClasses()->find($id);

        if (!$class) {
            \App::abort(404);
        }

        $class->students()->sync($students);*/

        return "success";
    }

    public function delete_student($id)
    {
        $student_id = Input::get('student_id');
        $class = $this->findClasses()->find($id);

        if (!$class) {
            \App::abort(404);
        }

        $student_class = \DB::table('school_class_student')->where('student_id', '=', $student_id)->delete();

        return "success";
    }


    public function optional_lessons_setup($class)
    {
        $class = \SchoolClass::find($class);
        if ($class->optional_lessons()->distinct()->count() > 0) {
            foreach ($class->optional_lessons()->distinct()->get() as $item) {
                $opt_configuration["optional_" . $item->id] = true;
            }
        } else {
            $opt_configuration = null;
        }

        $series = $class->series()->distinct()->get();

        $this->layout->content = \View::make('admin.classes.optional_lessons_setup')
            ->with('class', $class)
            ->with('opt_configuration', $opt_configuration)
            ->with('series', $series);
    }

    public function save_optional_lessons_setup()
    {

        \DB::beginTransaction();

        $class = \SchoolClass::find(Input::get('class'));

        $input = Input::get();

        $lessons = array();
        foreach ($input as $key => $value) {

            if ($key != "class" && $key != "module" && $key != "serie") {
                if ($value == "optional") {
                    $data = explode("_", $key);

                    $lesson = \Lesson::find($data[1]);

                    $lessons[] = $lesson->id;
                }
            }
        }

        $class->optional_lessons()->sync($lessons);

        \DB::commit();

        return Redirect::route('admin.classes.optional_lessons_setup', $class->id)
            ->with('success', 'Optional lessons have been successfully saved.');
    }


    /**
     * Returns a list of available products from a school in JSON format.
     */
    public function available_from_school()
    {
        $currentUser = \Sentry::getUser();
        $school = $currentUser->school()->first();

        $series = $school->series()->get();

        foreach ($series as $key => $serie) {
            if ($serie->module === null) {
                unset($series[$key]);
            } else {
                $serie->title = $serie->module->title . " - " . $serie->title;
            }
        }

        $series = $series->toArray();

        return array_values($series);
    }

    public function preview($class_id, $activity_id = -1)
    {
        //$activity_id= 3;
        $class = $this->findClasses()->find($class_id);

        $series = $class->series()->distinct()->get();

        if ($activity_id == -1) {
            $lesson = $series->first()->lessons->first();
            $activities = $lesson->activities()->orderBy("order")->get();
            $activity = $activities->first();
            $activity_id = $activity->id;
        } else {
            $activity = \Activity::find($activity_id);
            $lesson = $activity->lesson;
            //$activities = $lesson->activities()->orderBy("order")->get();
            $activities = $lesson->activities()->orderBy("order")->get();
        }

        $i = 0;
        $assessment_activity = null;
        foreach ($activities as &$activity_item) {
            if ($activity_item->template_type == 'ActivityTemplates\Assessment') {
                $assessment_activity = $activities[$i];
                unset($activities[$i]);
            }

            $i++;
        }
        if ($assessment_activity != null) {
            $activities[$i] = $assessment_activity;
        }


        $flag = false;
        $next_activity = -1;
        foreach ($activities as $activity_item) {
            if ($flag) {
                $next_activity = $activity_item->id;
                break;
            }
            if ($activity_item->id == $activity_id) {
                $flag = true;
            }
        }

        $flag = false;
        if ($next_activity == -1) {
            foreach ($series as $serie_item) {
                $lessons = $serie_item->lessons()->get();
                foreach ($lessons as $lesson_item) {
                    if ($flag) {
                        $all_activities = $lesson_item->activities()->orderBy("order");
                        if ($all_activities->get()[0]->template_type == 'ActivityTemplates\Assessment') {
                            $next_activity = $all_activities->get()[1]->id;
                        } else {
                            $next_activity = $all_activities->get()[0]->id;
                        }
                        break 2;
                    }
                    if ($lesson_item->id == $lesson->id) {
                        $flag = true;
                    }
                }
            }
        }

        $data["lesson_title"] = $lesson->title;
        $data["current_activity"] = $activity;
        $template_type = $activity->template_type;

        //var_dump($activity->id);die();

        $meta = $template_type::getMetaData();

        $i = 0;
        $sub_activities_views = [];
        foreach ($activity->getSubAcitivities() as $sub_activity) {
            $_template_type = $sub_activity->template_type;
            $_meta = $_template_type::getMetaData();
            $_data["lesson_title"] = $lesson->title;
            $_data["current_activity"] = $sub_activity;

            $sub_activities_views[] = (string)(\View::make($_meta['user_template'], $_data)
                ->with('data', $sub_activity->template)
                ->with('hasAnswer', false)
                ->with('answer', null)
                ->with('test', \Session::get('test'))
                ->with('answerData', null));

            $i++;
        }

        $this->layout = \View::make('layouts.base_class_preview');
        //$this->layout = \View::make('layouts.preview');

        $content = \View::make($meta['user_template'])
            ->with('current_activity', $activity)
            ->with('data', $activity->template)
            ->with('hasAnswer', false)
            ->with('test', null)
            ->with('preview', true);

        $this->layout->with('activityId', $activity->id)
            ->with('next_activity', $next_activity)
            ->with('activities', $activities)
            ->with('class_id', $class_id)
            ->with('class_name', $class->name)
            ->with('current_activity', $activity)
            ->with('series', $series)
            ->with('template_type', $activity->template_type)
            ->with('activiy_bg_img', $activity->background_image)
            ->with('content', $content->render())
            ->with('sub_activities_views', $sub_activities_views)
            ->with('is_school_preview', false);

        $this->layout->render();
    }

    public function import_students($id)
    {
        $class = $this->findClasses()->find($id);

        $this->layout = \View::make('admin.layout.base_import_students');

        $this->layout->content = View::make('admin/classes/import_students')
            ->with('class', $class)
            ->with('route', array('admin.classes.store_students', $id));
    }

    /**
     * Uploads files for classes
     */
    public function upload()
    {
        if (!\Input::get('upload')) {
            return \Response::make('No file was provided.', 400);
        }

        $file = BaseController::handleUpload('upload');

        return array(
            'url' => '/uploads/' . $file['filename'],
            'filename' => $file['filename']
        );
    }

    public function parse_file()
    {
        $filename = 'uploads/' . (\Input::get('file'));


        $str = file_get_contents($filename);
        $str = str_replace(chr(13), chr(13) . chr(10), $str);
        $str = str_replace(chr(10) . chr(10), chr(10), $str);
        file_put_contents($filename, $str);


        $data = file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        unset($data[0]);
        unset($data[1]);
        unset($data[2]);
        $data = array_values($data);

        return json_encode($data);
    }

    public function store_imported_students()
    {
        $filename = 'uploads/' . (\Input::get('file'));

        $data = file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        unset($data[0]);
        unset($data[1]);
        unset($data[2]);
        $data = array_values($data);

        $class = $this->findClasses()->find(\Input::get('class'));
        $default_password = $class->default_password;

        \DB::beginTransaction();

        foreach ($data as $row) {
            $studentInfo = explode(",", $row);

            if (count($studentInfo) == 4) {
                $username = $studentInfo[2];
                if (!empty($username)) {
                    $tab = array();
                    $tab['last_name'] = trim($studentInfo[0]);
                    $tab['first_name'] = trim($studentInfo[1]);
                    $tab['username'] = trim($studentInfo[2]);
                    $tab['email'] = trim($studentInfo[3]);
                    $tab['password'] = $default_password;
                    try {
                        $user = \Sentry::createUser($tab);
                        $user->activated = TRUE;
                        $user->school()->associate($class->school);
                        $user->addGroup(\Sentry::findGroupByName("Student"));
                        $user->save();
                        $class->students()->attach($user->id);
                        $students[] = $user->id;
                    } catch (\Exception $e) {
                        $duplicatedStudents[] = $tab;
                    }

                }
            }
        }

        \DB::commit();

        if (isset($duplicatedStudents)) {
            return \Response::make(array('ok' => json_encode($duplicatedStudents)), 201);
        } else {
            return \Response::make(array('ok' => true), 201);
        }
    }

}
