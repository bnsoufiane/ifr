<?php

namespace Admin;

use Input;
use Response;

class TestsController extends BaseController
{

    //* directly showing all lessons 
    public function show($id)
    {
        $class = \SchoolClass::find($id);

        $students = $class->students()->orderBy('last_name')->get();

        $this->layout->content = \View::make('admin.class_grades.show')
            ->with('class', $class)
            ->with('students', ClassStudentPresenter::wrap($students))
            ->with('lessons', $class->assignedLessons());
    }

    public function show_classes()
    {
        $currentUser = \Sentry::getUser();
        $school = $currentUser->school()->first();
        $classes = $school->classes()->get();

        $this->layout->content = \View::make('admin.tests.show_classes')
            ->with('classes', $classes);
    }

    public function show_modules($class)
    {
        $class = \SchoolClass::find($class);
        $currentUser = \Sentry::getUser();
        $school = $currentUser->school()->first();
        $modules = $school->series()->get();

        $this->layout->content = \View::make('admin.tests.show_modules')
            ->with('class', $class)
            ->with('modules', $modules);
    }

    public function show_series($class, $module)
    {
        $class = \SchoolClass::find($class);
        $module = \Module::find($module);
        $series = $module->series()->get();

        $this->layout->content = \View::make('admin.tests.show_series')
            ->with('class', $class)
            ->with('module', $module)
            ->with('series', $series);
    }

    public function config_tests($class, $module, $serie)
    {

        $test = \Tests::getByClassModuleSerie($class, $module, $serie);

        if ($test != null) {
            $test_conf = \TestConfiguration::getByTestId($test->id);

            foreach ($test_conf as $key => $value) {
                $str = ($value['test_type'] == \Tests::POST) ? "posttest" : "pretest";
                $str .= "_" . $value['lesson_id'] . "_" . $value['activity_id'];

                $test_configuration[$str] = true;
            }
        } else {
            $test_configuration = null;
        }

        $class = \SchoolClass::find($class);
        $module = \Module::find($module);
        $serie = \Series::find($serie);
        $lessons = $serie->lessons()->get();

        $this->layout->content = \View::make('admin.tests.config_tests')
            ->with('class', $class)
            ->with('module', $module)
            ->with('serie', $serie)
            ->with('test_configuration', $test_configuration)
            ->with('lessons', $lessons);
    }

    public function tests_setup($class)
    {

        $test = \Tests::getBySchoolClass($class);

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

        $class = \SchoolClass::find($class);
        $series = $class->series()->distinct()->get();

        $this->layout->content = \View::make('admin.tests.tests_setup')
            ->with('class', $class)
            ->with('test_configuration', $test_configuration)
            ->with('series', $series);
    }

    public function show_lesson_grades($class, $module, $serie, $lesson)
    {
        $class = \SchoolClass::find($class);
        $module = \Module::find($module);
        $serie = \Series::find($serie);
        $lesson = \Lesson::find($lesson);

        $students = $class->students()->orderBy('last_name')->get();

        $this->layout->content = \View::make('admin.class_grades.show_lesson_grades')
            ->with('class', $class)
            ->with('module', $module)
            ->with('serie', $serie)
            ->with('lesson', $lesson)
            ->with('students', ClassStudentPresenter::wrap($students));
    }

    public function save_config_tests()
    {
        $class = \SchoolClass::find(Input::get('class'));

        $test = \Tests::getBySchoolClass($class->id);

        if ($test == null) {
            $test = new \Tests();

            $test->schoolclass()->associate($class);

            $test->save();
        } else {

            \TestConfiguration::where('test_id', '=', $test->id)->delete();

            $test->touch();
        }

        $input = Input::get();

        foreach ($input as $key => $value) {

            if ($key != "class" && $key != "module" && $key != "serie") {
                $data = explode("_", $key);

                $type = ($data[0] == "pretest") ? (\Tests::PRE) : (\Tests::POST);
                $section = \ActivityTemplates\AssessmentSection::find($data[1]);

                $test_configuration = new \TestConfiguration();
                $test_configuration->test_type = $type;
                $test_configuration->test()->associate($test);
                if ($section != null) {
                    $test_configuration->section()->associate($section);
                }

                $test_configuration->save();
            }
        }

        $this->tests_setup($class->id);
    }

    public function reset_posttest($user)
    {
        try {
            $student = \Student::find($user);

            $class = $student->schoolClasses()->first();
            $test = ($class == null) ? null : (\Tests::getBySchoolClass($class->id));
            $test_students = \TestStudent::getByTestAndStudent($test->id, $user);
            $last_test_student = (count($test_students) <= 1) ? $test_students[0] : $test_students[count($test_students) - 1];

            \TestStudent::whereRaw('test_id = ? and student_id = ? and learning_level = ?', array($test->id, $user, \TestStudent::POST_TEST))
                ->update(array('status' => \Tests::OPEN, 'score' => null, 'attempts' => $last_test_student->attempts + 1));

            return \Redirect::to('admin/students')->with('success', 'Post-test has been successfully reset.');
        } catch (\Exception $e) {
            return \Redirect::to('admin/students')->with('error', 'An error has occurred.');
        }
    }

    public function reset_serie()
    {
        $student_id = Input::get('student_id');
        $series = Input::get('series');

        try {

            if ($series == null) {
                \Session::flash('error', "You didn't select any series to reset.");
                return \Response::make("You didn't select any series to reset.", 400);
            }

            foreach ($series as $series_id) {
                $activities = array();
                $lessons = \Series::where('id', '=', $series_id)->first()->lessons()->lists('id');

                foreach ($lessons as $lesson) {
                    $activities = array_merge($activities, \Lesson::where('id', '=', $lesson)->first()->activities()->lists('id'));
                }

                $activities = array_merge($activities, \Activity::whereIn('parent_activity', $activities)->lists('id'));

                \DB::table('student_lesson')->where('student_id', '=', $student_id)->whereIn('lesson_id', $lessons)->delete();
                \DB::table('student_answers')->where('student_id', '=', $student_id)->whereIn('activity_id', $activities)->delete();
            }

            \Session::flash('success', 'Series has been successfully reset.');
            return Response::make(array('ok' => true), 201);
        } catch (\Exception $e) {
            \Session::flash('error', 'An error has occurred.');
            return \Response::make('An error has occurred.', 400);
        }
    }

    public function reset_lesson()
    {
        $user = Input::get('student');
        $reset = Input::get('reset');

        try {
            if ($reset == null) {
                return \Redirect::to('admin/students')->with('error', "You didn't select any lesson to reset.");
            }

            foreach ($reset as $lesson_id) {
                $activities = \Lesson::where('id', '=', $lesson_id)->first()->activities()->lists('id');

                $activities = array_merge($activities, \Activity::whereIn('parent_activity', $activities)->lists('id'));

                $answers = \DB::table('student_answers')->where('student_id', '=', $user)->whereIn('activity_id', $activities)->delete();

                \DB::table('student_lesson')->where('student_id', '=', $user)
                    ->where('lesson_id', '=', $lesson_id)->delete();
            }

            return \Redirect::to('admin/students')->with('success', 'Lesson has been successfully reset.');
        } catch (\Exception $e) {
            return \Redirect::to('admin/students')->with('error', 'An error has occurred.');
        }
    }

    public function show_lessons($student)
    {
        $student = \Student::find($student);
        $series = $student->schoolClasses()->first()->series()->get();

        $this->layout->content = \View::make('admin.tests.show_lessons')
            ->with('student', $student)
            ->with('series', $series);
    }

}
