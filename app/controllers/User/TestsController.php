<?php

namespace User;

class TestsController extends BaseController
{

    public function pretest()
    {
        $user = \Sentry::getUser();
        $student = \Student::find($user->id);
        $class = $student->schoolClasses()->first();

        $test = ($class == null) ? null : (\Tests::getBySchoolClass($class->id));
        $test_students = \TestStudent::getByTestAndStudent($test->id, $user->id);

        $last_test_student = (count($test_students) == 1) ? $test_students[0] : $test_students[1];
        $test_conf = \TestConfiguration::getByTestId($test->id);
        $sections = [];
        foreach ($test_conf as $key => $value) {
            if ($last_test_student->learning_level == \TestStudent::PRE_TEST && $value['test_type'] == \Tests::PRE) {

                array_push($sections, \ActivityTemplates\AssessmentSection::find(intval($value['section_id'])));
            }
        }

        if (count($sections)) {
            if ($last_test_student->learning_level == \TestStudent::PRE_TEST) {
                $this->layout = \View::make('layouts.tests_base');

                $this->layout->content = \View::make('/activities/_pretest')
                    ->with('sections', $sections)
                    ->with('test_title', "Pre-test");
                $this->layout->with('tests', true);
            }
        } else {
            return \Redirect::route('index');
        }

    }

    public function posttest()
    {
        $user = \Sentry::getUser();
        $student = \Student::find($user->id);
        $class = $student->schoolClasses()->first();

        $test = ($class == null) ? null : (\Tests::getBySchoolClass($class->id));
        $test_students = \TestStudent::getByTestAndStudent($test->id, $user->id);

        $last_test_student = (count($test_students) == 1) ? $test_students[0] : $test_students[1];

        if ($last_test_student->learning_level != \TestStudent::POST_TEST) {
            $test_student = new \TestStudent();
            $test_student->test()->associate($test);
            $test_student->student()->associate($user);
            $test_student->learning_level = \TestStudent::POST_TEST;
            $test_student->status = \Tests::OPEN;

            $test_student->save();

            $test_students = \TestStudent::getByTestAndStudent($test->id, $user->id);
            $last_test_student = (count($test_students) == 1) ? $test_students[0] : $test_students[1];
        }


        $test_conf = \TestConfiguration::getByTestId($test->id);
        $sections = [];
        foreach ($test_conf as $key => $value) {
            if ($last_test_student->learning_level == \TestStudent::POST_TEST && $value['test_type'] == \Tests::POST) {
            //if ( $value['test_type'] == \Tests::POST) {

                $section_item = \ActivityTemplates\AssessmentSection::find(intval($value['section_id']));
                if ($section_item !== null) {
                    array_push($sections, $section_item);
                }
            }
        }

        if (count($sections)) {
            if ($last_test_student->learning_level == \TestStudent::POST_TEST) {
                $this->layout = \View::make('layouts.tests_base');

                $this->layout->content = \View::make('/activities/_posttest')
                    ->with('sections', $sections)
                    ->with('test_title', "Post-test");

                $this->layout->with('tests', true);
            }
        } else {
            return \Redirect::route('index');
        }
    }

    public function test_finished()
    {
        $user = \Sentry::getUser();
        $student = \Student::find($user->id);

        $class = $student->schoolClasses()->first();
        $test = ($class == null) ? null : (\Tests::getBySchoolClass($class->id));

        $test_students = \TestStudent::getByTestAndStudent($test->id, $user->id);

        $last_test_student = (count($test_students) == 1) ? $test_students[0] : $test_students[1];

        if ($last_test_student->learning_level == \TestStudent::PRE_TEST) {
            $this->pretest_finished();
        } else if ($last_test_student->learning_level == \TestStudent::POST_TEST) {
            $this->posttest_finished();
        }

    }

    public function pretest_finished()
    {
        $user = \Sentry::getUser();
        $student = \Student::find($user->id);

        $class = $student->schoolClasses()->first();
        $test = ($class == null) ? null : (\Tests::getBySchoolClass($class->id));

        $score = $student->ScoreByTest(\Input::get(), $test, \TestStudent::PRE_TEST);

        \TestStudent::whereRaw('test_id = ? and student_id = ?', array($test->id, \Sentry::getUser()->id))
            ->update(array('status' => \Tests::CLOSED, 'current_activity' => 0, 'score' => $score));

        $this->layout = \View::make('layouts.tests_base');
        $this->layout->content = \View::make('/messages/pretest_finished')
            ->with('student', $student)
            ->with('test', $test)
            ->with('score', number_format((float)$score, 2, '.', ''));
    }

    public function posttest_finished()
    {
        $user = \Sentry::getUser();
        $student = \Student::find($user->id);

        $class = $student->schoolClasses()->first();
        $test = ($class == null) ? null : (\Tests::getBySchoolClass($class->id));

        $score = $student->ScoreByTest(\Input::get(), $test, \TestStudent::POST_TEST);

        $failed = ($class->minimum_score > $score);

        $test_student = \TestStudent::getByTestAndStudentAndLearningLevel($test->id, $user->id, \TestStudent::POST_TEST);
        $attempts = $test_student->attempts;

        \TestStudent::whereRaw('test_id = ? and student_id = ?', array($test->id, \Sentry::getUser()->id))
            ->update(array('status' => \Tests::CLOSED, 'current_activity' => 0, 'score' => $score));

        $this->layout = \View::make('layouts.tests_base');
        $this->layout->content = \View::make('/messages/posttest_finished')
            ->with('student', $student)
            ->with('test', $test)
            ->with('score', number_format((float)$score, 2, '.', ''))
            ->with('failed', $failed)
            ->with('attempts', $attempts);
    }

    public function reset_posttest()
    {
        $user = \Sentry::getUser();
        $student = \Student::find($user->id);

        $class = $student->schoolClasses()->first();
        $test = ($class == null) ? null : (\Tests::getBySchoolClass($class->id));
        $test_students = \TestStudent::getByTestAndStudent($test->id, $user->id);

        $last_test_student = (count($test_students) <= 1) ? $test_students[0] : $test_students[count($test_students) - 1];

        \TestStudent::whereRaw('test_id = ? and student_id = ? and learning_level = ?', array($test->id, $user->id, \TestStudent::POST_TEST))
            ->update(array('status' => \Tests::OPEN, 'attempts' => $last_test_student->attempts + 1));

        return \Redirect::route('index');
    }

    public function reset_lesson($lesson_id)
    {
        $user = \Sentry::getUser();

        $student_lesson = \DB::table('student_lesson')->where('student_id', '=', $user->id)
            ->where('lesson_id', '=', $lesson_id)->first();

        if ($student_lesson->attempts >= 3) {
            \DB::table('student_lesson')->where('student_id', '=', $user->id)
                ->where('lesson_id', '=', $lesson_id)
                ->update(array('closed' => 1, 'attempts' => $student_lesson->attempts + 1));
        } else {
            \DB::table('student_lesson')->where('student_id', '=', $user->id)
                ->where('lesson_id', '=', $lesson_id)
                ->update(array('closed' => 0, 'attempts' => $student_lesson->attempts + 1));
        }


        return \Redirect::route('lessons.show', $lesson_id);
    }

    public function go_to_next_lesson($lesson_id)
    {
        $redirect_to_first_activity = false;
        if (strpos($lesson_id, '_') !== false) {
            $lesson_id = str_replace('_', '', $lesson_id);
            $redirect_to_first_activity = true;
        }

        $user = \Sentry::getUser();
        $lesson = \Lesson::find($lesson_id);

        $all_lessons = $lesson->series->lessons;

        $i = 0;
        $last_lesson = null;
        foreach ($all_lessons as $lesson_item) {
            if ($lesson_item->optional) {
                $user = \Sentry::getUser();
                $student_optional_lesson = \DB::table('student_optional_lesson')
                    ->where('lesson_id', $lesson_item->id)
                    ->where('student_id', $user->id)->first();
                if (!$student_optional_lesson) {
                    unset($all_lessons[$i]);
                } else {
                    $last_lesson = $lesson_item;
                }
            } else {
                $last_lesson = $lesson_item;
            }
            $i++;
        }

        if ($last_lesson->id != $lesson_id) {
            $now_next_lesson = false;
            foreach ($all_lessons as $lesson_item) {
                if ($now_next_lesson) {
                    $next_lesson = $lesson_item;
                    break;
                }
                if ($lesson_item->id == $lesson_id) {
                    $now_next_lesson = true;
                }
            }
            if ($redirect_to_first_activity) {
                return \Redirect::route('lessons.show', $all_lessons[0]->id);
            } else {
            return \Redirect::route('lessons.show', $next_lesson->id);
            }
        } else {
            // create an entry in the test_students db and show the test to the student
            $user = \Sentry::getUser();
            $class = \DB::table('school_class_student')->select('school_class_id')->where('student_id', '=', $user->id)->first();
            $test = ($class == null) ? null : (\Tests::getBySchoolClass($class->school_class_id));

            if ($test === null) {
                // TODO: Redirect to the next lesson if there's no test.
                return \Redirect::route('lessons.show', $all_lessons[0]->id);
                //return \Redirect::route('activities.lesson_finished');
            }

            $test_student = new \TestStudent();
            $test_student->test()->associate($test);
            $test_student->student()->associate($user);
            $test_student->learning_level = \TestStudent::POST_TEST;
            $test_student->status = \Tests::OPEN;
            $test_student->attempts = 1;

            $test_conf = \TestConfiguration::getByTestId($test->id);
            $activity_id = 0;
            $activities = [];
            foreach ($test_conf as $key => $value) {
                if ($value['test_type'] == \Tests::POST) {
                    if (!$activity_id) {
                        $activity_id = intval($value['activity_id']);
                    }

                    array_push($activities, \Activity::find(intval($value['activity_id'])));
                }
            }

            $test_student->current_activity = $activity_id;
            $test_student->save();

            //return \Redirect::route('user.posttest');

            //redirect to first lesson
            return \Redirect::route('lessons.show', $all_lessons[0]->id);

            /* return \Redirect::route('activities.show', $activity_id . "_" . \TestStudent::POST_TEST)
              ->with('activities', $activities)
              ->with('test', $test->id); */
        }
    }

}
