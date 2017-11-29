<?php

// Entry point controller
class MainController extends BaseController
{

    public function index()
    {
        $user = Sentry::getUser();

        if ($user) {
            $isStudent = $user->inGroup(Sentry::findGroupByName('Student'));

            if ($isStudent) {

                $student = \Student::find($user->id);
                $class = $student->schoolClasses()->first();

                //$class_student = DB::table('school_class_student')->where('student_id', '=', $user->id)->first();
                if ($class === null) {
                    return Redirect::route('sign-in')
                        ->with('error', "You don't belong to any class");
                }

                //$started_learning = $class_student->started_learning;
                //DB::table('school_class_student')->where('student_id', $user->id)->where('school_class_id', $class->school_class_id)->update(array('started_learning' => 0));

                //choose optional lessons
                /*if (!$started_learning) {
                    $count = DB::table('lessons')->where('optional', '=', 1)->count();
                    if ($count > 0) {
                        return Redirect::route('user.choose_optional_lessons');
                    }
                }*/

                if (\Hash::check($class->default_password, $user->password)) {
                    return Redirect::route('user.change_password');
                }

                $test = ($class == null) ? null : (\Tests::getBySchoolClass($class->id));

                if ($test != null) {

                    $test_students = \TestStudent::getByTestAndStudent($test->id, $user->id);

                    if (count($test_students) == 0) {
                        // create an entry in the test_students db and show the test to the student
                        $test_student = new \TestStudent();
                        $test_student->test()->associate($test);
                        $test_student->student()->associate($user);
                        $test_student->learning_level = \TestStudent::PRE_TEST;
                        $test_student->status = \Tests::OPEN;

                        $test_conf = \TestConfiguration::getByTestId($test->id);

                        $sections = [];
                        foreach ($test_conf as $key => $value) {
                            if ($value['test_type'] == \Tests::PRE) {
                                array_push($sections, \Activity::find(intval($value['section_id'])));
                            }
                        }

                        //$test_student->current_activity = $activity_id;
                        $test_student->save();

                        if (count($sections) == 0) {
                            \TestStudent::whereRaw('test_id = ? and student_id = ?', array($test->id, \Sentry::getUser()->id))
                                ->update(array('status' => \Tests::CLOSED, 'current_activity' => 0));

                            return Redirect::route('activities.show', $this->get_first_learning_activity($student));
                        }

                        return Redirect::route('user.take_pretest');

                    } else {

                        //($test_student->status == \TestStudent::PRE_TEST || $test_student->status == \TestStudent::POST_TEST) {
                        $last_test_student = (count($test_students) == 1) ? $test_students[0] : $test_students[1];
                        if ($last_test_student->learning_level == \TestStudent::PRE_TEST && $last_test_student->status == \Tests::CLOSED) {

                            $latestActivity = $this->get_learning_activity($student);

                            if ($latestActivity < 0) {
                                return \Redirect::route('user.go_to_next_lesson', (0 - $latestActivity) . "_");
                            }
                            // pretest closed : now show activities
                            return Redirect::route('activities.show', $latestActivity);
                        } else if ($last_test_student->learning_level == \TestStudent::POST_TEST && $last_test_student->status == \Tests::CLOSED) {
                            // post test closed
                            //return Redirect::route('user.posttest_finished');
                            return Redirect::route('activities.show', $this->get_first_learning_activity($student));
                        }

                        //$test_student = \TestStudent::getByTestAndStudent($test->id, $user->id);

                        $test_conf = \TestConfiguration::getByTestId($test->id);
                        $sections = [];
                        foreach ($test_conf as $key => $value) {
                            if ($last_test_student->learning_level == \TestStudent::PRE_TEST && $value['test_type'] == \Tests::PRE) {

                                array_push($sections, \ActivityTemplates\AssessmentSection::find(intval($value['section_id'])));
                            } else if ($last_test_student->learning_level == \TestStudent::POST_TEST && $value['test_type'] == \Tests::POST) {
                                array_push($sections, \ActivityTemplates\AssessmentSection::find(intval($value['activity_id'])));
                            }
                        }

                        if (count($sections)) {
                            if ($last_test_student->learning_level == \TestStudent::PRE_TEST) {
                                return Redirect::route('user.pretest')
                                    ->with('sections', $sections)
                                    ->with('test', $test->id);
                            } else if ($last_test_student->learning_level == \TestStudent::PRE_TEST) {
                                return Redirect::route('user.posttest')
                                    ->with('sections', $sections)
                                    ->with('test', $test->id);
                            }
                        }
                    }
                }

                // Redirect to the latest activity

                return Redirect::route('activities.show', $this->get_first_learning_activity($student));
            } else {
                // Redirect to the admin's UI
                return Redirect::route('admin.index');
            }
        } else {
            return Redirect::route('sign-in');
        }
    }

    public function print_activity()
    {
        $input = Input::get();
        $student = \Student::find($input['student_id']);
        $activity = \Activity::find($input['activity_id']);


        if ($input === null) {
            return \Response::make('You haven\'t provided activity data.', 400);
        }

        if ($activity == null) {
            return 'No activity to show';
        }

        $lesson = $activity->lesson;
        $series = $lesson->series;
        $class = $student->schoolClasses()->first();

        $this->layout = \View::make('layouts.wkhtml2pdf_base');

        $answers = $student->answers()->byActivity($activity->id)->get();
        $answer = null;

        foreach ($answers as $item) {
            $answer = $item;
        }

        $templateType = $activity->template_type;
        $meta = $templateType::getMetaData();

        $is_printable = false;
        if (isset($meta['answer_template'])) {
            $is_printable = true;
        }

        if (isset($meta['answer_template'])) {
            $content = \View::make($meta['answer_template'])
                ->with('current_activity', $activity)
                ->with('data', $activity->template)
                ->with('hasAnswer', $answer != null)
                ->with('answer', $answer)
                ->with('answerData', $answer ? $answer->answerType : null)
                ->with('test', null)
                ->with('preview', true);

            $answers_views[] = $content->render();
        }

        foreach ($activity->getSubAcitivities() as $sub_activity) {
            $templateType = $sub_activity->template_type;
            $meta = $templateType::getMetaData();

            $answers = $student->answers()->byActivity($sub_activity->id)->get();
            $answer = null;

            foreach ($answers as $item) {
                $answer = $item;
            }

            if (isset($meta['answer_template'])) {
                $is_printable = true;
            } else {
                continue;
            }

            $content = \View::make($meta['answer_template'])
                ->with('current_activity', $sub_activity)
                ->with('data', $sub_activity->template)
                ->with('hasAnswer', $answer != null)
                ->with('answer', $answer)
                ->with('answerData', $answer ? $answer->answerType : null)
                ->with('test', null)
                ->with('preview', true);

            $answers_views[] = $content->render();

        }

        if (!$is_printable) {
            return "<html><script>alert('This activity can\'t be printed');history.go(-1);</script></html>";
        }

        $page_title = $student->last_name . " " . $student->first_name . " $class->name - $series->title - $lesson->title - $activity->title";

        $this->layout->with('activityId', $activity->id)
            ->with('template_type', $activity->template_type)
            ->with('activiy_bg_img', $activity->background_image)
            ->with('class_name', $class->name)
            ->with('series_title', $series->title)
            ->with('lesson_title', $lesson->title)
            ->with('activity_title', $activity->title)
            ->with('page_title', $page_title)
            ->with('print', true)
            ->with('answers_views', $answers_views)
            ->with('student_name', $student->last_name . " " . $student->first_name);

        $h = $this->layout->render();
        return \PDF::html('layouts.wkhtml2pdf', array('content' => $h), $page_title);

        /*$contents = \PDF::load($this->layout, 'A4', 'portrait')->download($page_title);

        $response = Response::make($contents, 200);

        $response->header('Content-Type', 'application/pdf');

        return $response;*/

        //return \PDF::load($this->layout, 'A4', 'portrait')->download($page_title);

        //$this->layout->render();

    }

    private function get_learning_activity($student)
    {
        $answers = \DB::table('student_answers')->where('student_id', '=', $student->id)
            ->orderBy('updated_at', 'desc')->get();

        if ((count($answers) == 0) || $answers[0]->time_needed == 999999999) {
            return $this->get_first_learning_activity($student);
        }

        for ($i = 0; $i < count($answers); $i++) {
            $activity = \Activity::find($answers[$i]->activity_id);

            if ($activity->lesson_id != null) {
        $lesson = \Lesson::find($activity->lesson_id);
                break;
            }
        }

        if (!isset($lesson)) {
            return $this->get_first_learning_activity($student);
        }

        $activities = $lesson->activities()->get();

        $user = \Sentry::getUser();
        $student_lesson = \DB::table('student_lesson')->where('student_id', '=', $user->id)
            ->where('lesson_id', '=', $lesson->id)->first();
        if ($student_lesson != null) {
            if ($student_lesson->closed) {
                return 0 - $lesson->id;
            }
            if ($student_lesson->attempts > 3) {
                return $activities[0]->id;
            }
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

        if ($assessment_activity->id == $activity->id) {
            return 0 - $lesson->id;
        }

        $i = 0;
        foreach ($activities as $activity_item) {
            if ($activity_item->id == $activity->id) {
                return isset($activities[$i + 1]) ? $activities[$i + 1]->id : $activities[$i + 2]->id;
            }
            $i++;
        }

        return 1;
    }

    private function get_first_learning_activity($student)
    {
        try {
            $lessons = $student->schoolClasses()->first()->assignedLessons();
            foreach ($lessons as $lesson) {
                if (!$lesson->optional) {
                    $activities = $lesson->activities()->orderBy("order")->get();

                    $i=0;
                    foreach ($activities as &$activity_item) {
                        if ($activity_item->template_type == 'ActivityTemplates\Assessment') {
                            $assessment_activity = $activities[$i];
                            unset($activities[$i]);
                        }else{
                            return $activity_item->id;
                        }
                        $i++;
                    }
                } else {
                    $optional = \DB::table('student_optional_lesson')->where('student_id', '=', $student->id)
                        ->where('lesson_id', '=', $lesson->id)->first();
                    $optional = isset($optional);
                    if ($optional) {
                        return $lesson->activities()->orderBy("order")->first()->id;
                    }
                }
            }
        } catch (\Exception $e) {
            return \Redirect::route('user.no_activities_to_display');
        }

    }


    public function clean_unused_uploaded_pictures()
    {
        $activites = DB::table('activities')
            ->select('illustration_image', 'background_image')
            ->where('illustration_image', '<>', '')
            ->orWhere(function($query)
            {
                $query->where('background_image', '<>', '');
            })
            ->get();

        $cartoons = DB::table('activity_template_cartoon_pictures')
            ->select('file')
            ->where('file', '<>', '')
            ->get();

        $story_characters = DB::table('story_characters')
            ->select('picture')
            ->where('picture', '<>', '')
            ->get();

        $files_array = [];

        foreach($activites as $file){
            if($file->illustration_image)
                $files_array[] = 'uploads/'.$file->illustration_image;

            if($file->background_image)
                $files_array[] = 'uploads/'.$file->background_image;
        }

        foreach($cartoons as $file){
            if($file->file)
                $files_array[] = 'uploads/'.$file->file;
        }

        foreach($story_characters as $file){
            if($file->picture)
                $files_array[] = 'uploads/'.$file->picture;
        }





        echo "Total files in the DB :  ".count($files_array).'<br/><br/>';

        foreach($files_array as $filename){
            if(is_file($filename)){
                //echo $filename.'<br/>';
            }
        }

        $i = 0;
        foreach(glob('uploads/*') as $filename){

if (!in_array($filename, $files_array) && is_file($filename) && $filename!="uploads/csp_logo.png" && $filename!="uploads/lightbulbs.jpg") {
                echo $filename.'<br/>';
                unlink($filename);
                $i++;
            }
        }

        echo "<br/><br/>Total deleted files :  ".$i.'<br/>';

        var_dump('clean_unused_uploaded_pictures');
        die();

    }


    public function background_images_report()
    {
        $activites = DB::table('activities')
            ->where('illustration_image', '<>', '')
            ->orWhere('background_image', '<>', '')
            ->get();

        $files_array = [];

        foreach($activites as $file){
            $activity = $file->title;
            $lesson = \Lesson::find($file->lesson_id);
            $lesson_title = $lesson->title;
            $series = \Series::find($lesson->series_id)->title;
            $val = "$series - $lesson_title - $activity";

            if($file->illustration_image)
                $files_array['uploads/'.$file->illustration_image] = $val;

            if($file->background_image)
                $files_array['uploads/'.$file->background_image] = $val;
        }

        $files_sizes = [];
        foreach($files_array as $key => $filename){
            if(is_file($key)){
                $files_sizes[$key]= filesize($key);
            }else{
                $files_sizes[$key]= 0;
            }
        }

        arsort($files_sizes);

        foreach($files_sizes as $key => $value){
            echo str_replace('uploads/','',$key) .",".$this->FileSizeConvert($value).",".$files_array[$key]."<br/>";
        }


        die();

    }

    function FileSizeConvert($bytes)
    {
        if($bytes==0){
            return 0;
        }

        $bytes = floatval($bytes);
        $arBytes = array(
            0 => array(
                "UNIT" => "TB",
                "VALUE" => pow(1024, 4)
            ),
            1 => array(
                "UNIT" => "GB",
                "VALUE" => pow(1024, 3)
            ),
            2 => array(
                "UNIT" => "MB",
                "VALUE" => pow(1024, 2)
            ),
            3 => array(
                "UNIT" => "KB",
                "VALUE" => 1024
            ),
            4 => array(
                "UNIT" => "B",
                "VALUE" => 1
            ),
        );

        foreach($arBytes as $arItem)
        {
            if($bytes >= $arItem["VALUE"])
            {
                $result = $bytes / $arItem["VALUE"];
                $result = str_replace(".", "," , strval(round($result, 2)))." ".$arItem["UNIT"];
                break;
            }
        }

        return $result;
    }

}
