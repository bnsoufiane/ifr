<?php

namespace User;

use View;
use Session;
use \BaseController as BaseAppController;

class MessagesController extends BaseAppController
{

    protected $layout = 'messages.base';

    public function change_password()
    {
        $user = \Sentry::getUser();
        $student = \Student::find($user->id);
        $class = $student->schoolClasses()->first();

        $this->layout->content = \View::make('/messages/change_password')
            ->with('route', 'user.save_password')
            ->with('default_pass', $class->default_password);
    }

    public function save_password()
    {
        $input = \Input::all();

        $rules = array(
            'new_password' => 'required|confirmed|min:5|max:50',
            'new_password_confirmation' => 'required|min:5|max:50',
        );

        $validator = \Validator::make($input, $rules);

        if ($validator->fails()) {
            return \Redirect::route('user.change_password')
                ->withInput()
                ->withErrors($validator);
        }

        $user = \Sentry::getUser();
        $user->password = $input["new_password"];
        $user->save();

        return \Redirect::route('index');
    }

    public function take_pretest()
    {
        $this->layout->content = \View::make('/messages/take_pretest');
    }

    public function take_posttest()
    {
        $this->layout->content = \View::make('/messages/take_posttest');
    }

    public function lesson_finished($lesson_id)
    {
        $lesson = \Lesson::find($lesson_id);

        $user = \Sentry::getUser();
        $student = \Student::find($user->id);

        $result = $student->ScoreByLesson_detailed($lesson);
        $score = $result['score'];

        //$failed = ($lesson->minimum_score > $score);

        $student_lesson = \DB::table('student_lesson')->where('student_id', '=', $user->id)
            ->where('lesson_id', '=', $lesson_id)->first();


        if (count($student_lesson) == 0) {
            \DB::table('student_lesson')->insert(
                array('student_id' => $user->id, 'lesson_id' => $lesson_id, 'closed' => 0, 'attempts' => 1));

            $student_lesson = \DB::table('student_lesson')->where('student_id', '=', $user->id)
                ->where('lesson_id', '=', $lesson_id)->first();
        }

        $attempts = $student_lesson->attempts;

        if ($student_lesson->attempts >= 3) {
            \DB::table('student_lesson')->where('student_id', '=', $user->id)
                ->where('lesson_id', '=', $lesson_id)
                ->update(array('closed' => 1));
        }

        $this->layout->content = \View::make('/messages/lesson_finished')
            ->with('student', $student)
            ->with('lesson', $lesson)
            ->with('correct_answers', $result["correct_answers"])
            ->with('total_questions', $result["total_questions"])
            ->with('score', number_format((float)$score, 2, '.', ''))
            ->with('failed', false)
            ->with('attempts', $attempts);
    }

    public function choose_optional_lessons()
    {

        $modulesList = \Module::with('series', 'series.lessons')->get();

        $view = View::make('/messages/choose_optional_lessons')
            ->with('modules', $modulesList);

        $this->layout->content = $view;

        //$this->layout->content = \View::make('/messages/choose_optional_lessons');
    }

    public function submit_optional_lessons()
    {
        $user = \Sentry::getUser();
        $class = \DB::table('school_class_student')->select('school_class_id')->where('student_id', '=', $user->id)->first();

        //return "submit_optional_lessons";

        \DB::table('school_class_student')
            ->where('student_id', $user->id)
            ->where('school_class_id', $class->school_class_id)
            ->update(array('started_learning' => 1));

        return \Redirect::route('index');
    }

    public function add_optional_lesson($lesson_id)
    {
        $user = \Sentry::getUser();
        $optional = \Input::get('optional');

        if ($optional) {
            \DB::table('student_optional_lesson')->where('student_id', '=', $user->id)
                ->where('lesson_id', '=', $lesson_id)->delete();
        } else {
            \DB::table('student_optional_lesson')->insert(
                array('student_id' => $user->id, 'lesson_id' => $lesson_id)
            );
        }

        return "ok";
    }

    public function no_activities_to_display()
    {
        $this->layout->content = \View::make('/messages/no_activities_to_display');
    }

    public function error404()
    {
        $this->layout->content = \View::make('/messages/errors/404');
    }

    public function error500()
    {
        $this->layout->content = \View::make('/messages/errors/500');
    }

}
