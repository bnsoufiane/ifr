<?php

namespace User;

use Input;
use Session;

class ActivityController extends BaseController
{

    /**
     * Renders an activity template by a provided type.
     * @param string $type
     */
    public function show($activityId)
    {
        $arr = explode("_", $activityId);

        $student = \Student::find(\Sentry::getUser()->id);

        $activity = \Activity::find($activityId);

        $lessons = $activity->lesson->get();
        $lesson = $activity->lesson->first();
        $lesson_id = \DB::table('activities')->select('lesson_id')->where('id', '=', $activity->id)->first();
        foreach ($lessons as &$value) {
            if ($value->id == $lesson_id->lesson_id) {
                $lesson = $value;
                break;
            }
        }

        // Load up data
        $data["lesson_title"] = $lesson->title;
        $data["current_activity"] = $activity;

        $template_type = $activity->template_type;

        $activities = $lesson->activities()->orderBy("order")->get();

        if ($activities == NULL) {
            return \Redirect::route('user.no_activities_to_display');
        }

        $i = 0;
        foreach ($activities as &$value) {
            if ($value->template_type == "ActivityTemplates\Assessment") {
                $activities[count($activities)] = $value;
                unset($activities[$i]);
            }
            $i++;
        }

        $assessment_activity = $lesson->activities()->where("template_type", "=", "ActivityTemplates\\Assessment")->first();
        if ($assessment_activity) {
            $student_assessment_answers = \StudentAnswer::whereRaw('student_id = ? and activity_id = ?', array($student->id, $assessment_activity->id))->first();
        }

        $meta = $template_type::getMetaData();

        $i = 0;
        $sub_activities_views = [];
        foreach ($activity->getSubAcitivities() as $sub_activity) {
            $_template_type = $sub_activity->template_type;
            $_meta = $_template_type::getMetaData();
            $_data["lesson_title"] = $lesson->title;
            $_data["current_activity"] = $sub_activity;

            // Check if we have answer for the current activity
            $answers = $student->answers()->byActivity($sub_activity->id)->get();
            $answer = null;

            foreach ($answers as $item) {
                $answer = $item;
            }

            $sub_activities_views[] = (string)(\View::make($_meta['user_template'], $_data)
                ->with('data', $sub_activity->template)
                ->with('hasAnswer', $answer != null)
                ->with('answer', $answer)
                ->with('test', \Session::get('test'))
                ->with('answerData', $answer ? $answer->answerType : null)
                ->with('assessment_done', ($student_assessment_answers != null)));

            $i++;
        }

        // Check if we have answer for the current activity
        $answers = $student->answers()->byActivity($activityId)->get();
        $answer = null;

        foreach ($answers as $item) {
            $answer = $item;
        }

        $this->layout->content = \View::make($meta['user_template'], $data)
            ->with('data', $activity->template)
            ->with('hasAnswer', $answer != null)
            ->with('answer', $answer)
            ->with('test', \Session::get('test'))
            ->with('answerData', $answer ? $answer->answerType : null)
            ->with('assessment_done', ($student_assessment_answers != null));

        $series = $student->schoolClasses()->first()->series()->get();

        $class = $student->schoolClasses()->first();
        $test = ($class == null) ? null : (\Tests::getBySchoolClass($class->id));

        if (!count($series)) {
            return \Redirect::route('user.no_activities_to_display');
        }

        $isCurrentActivityAssessment = ($assessment_activity && $assessment_activity->id == $activity->id) ? true : false;

        // Build template
        $this->layout->with('activities', $activities)
            ->with('module_title', $lesson->series->module->title)
            ->with('series', $series)
            ->with('activityId', $activityId)
            ->with('current_activity', $activity)
            ->with('template_type', $template_type)
            ->with('test', \Session::get('test'))
            ->with('activiy_bg_img', $activity->background_image)
            ->with('sub_activities_views', $sub_activities_views)
            ->with('students_area', true)
            ->with('assessment_done', ($student_assessment_answers != null))
            ->with('isCurrentActivityAssessment', $isCurrentActivityAssessment)
            ->with('test_setup', $test !== null);

    }

    /**
     * Stores a user's response to the activity and goes to the next activity.
     */
    public function store()
    {
        $activityId = (Input::get('parent_activity_id') !== null) ? Input::get('parent_activity_id') : Input::get('activity_id');
        $activity = \Activity::find($activityId);

        $student = \Student::find(\Sentry::getUser()->id);

        if (!$activity) {
            throw new \Exception('Unknown activity ID: ' . $activityId);
        }
        if ($activity->template instanceof \ActivityWithInput) {

            // Save user's input if the activity implies it
            \DB::transaction(function () use ($activity, $student) {
                //$answer = $student->answers()->byActivity($activity->id)->first();
                // Check if we have answer for the current activity

                $answers_2 = \DB::table('student_answers')->where('student_id', '=', $student->id)
                    ->orderBy('updated_at', 'desc')->get();
                if ((count($answers_2) != 0) && $answers_2[0]->time_needed == 999999999) {
                    \DB::table('student_answers')->where('id', '=', $answers_2[0]->id)
                        ->update(array('time_needed' => 0));
                }

                $answers = $student->answers()->byActivity($activity->id)->get();
                $answer = null;

                foreach ($answers as $item) {
                    $answer = $item;
                }

                $answerType = $activity->template->getStudentAnswer();

                if ($answer === null || !($answer->answerType instanceof $answerType)) {

                    // Create a new answer for the current activity.
                    $answer = new \StudentAnswer();

                    $answerType = new $answerType();
                    $answerType->saveFromArray(Input::get(), $activity->template);

                    $answer->activity()->associate($activity);
                    $answer->student()->associate(\Sentry::getUser());
                    $answer->answerType()->associate($answerType);
                    //$answer->time_needed = Input::get('time_to_complete_activity');

                    try {
                        \Student::find(\Sentry::getUser()->id)
                            ->answers()->save($answer);
                        //\Student::find(\Sentry::getUser()->id)->answers()->touch();
                    } catch (\Exception $e) {
                        //return \Response::make($e->getMessage(), 400);
                    }
                } else {
                    // Update the answer
                    $answer->answerType->saveFromArray(Input::get(), $activity->template);
                }

                foreach ($activity->getSubAcitivities() as $sub_activity) {
                    if ($sub_activity->template instanceof \ActivityWithInput) {
                        $answers = $student->answers()->byActivity($sub_activity->id)->get();
                        $answer = null;

                        foreach ($answers as $item) {
                            $answer = $item;
                        }

                        $answerType = $sub_activity->template->getStudentAnswer();

                        if ($answer === null || !($answer->answerType instanceof $answerType)) {
                            $answer = new \StudentAnswer();

                            $answerType = new $answerType();
                            $data = Input::get();
                            $data['parent_activity_id'] = $sub_activity->id;
                            $answerType->saveFromArray($data, $sub_activity->template);

                            $answer->activity()->associate($sub_activity);
                            $answer->student()->associate(\Sentry::getUser());
                            $answer->answerType()->associate($answerType);
                            //$answer->time_needed = Input::get('time_to_complete_activity');
                            try {
                                \Student::find(\Sentry::getUser()->id)->answers()->save($answer);
                            } catch (\Exception $e) {

                            }
                        } else {
                            $data = Input::get();
                            $data['parent_activity_id'] = $sub_activity->id;
                            $answer->answerType->saveFromArray($data, $sub_activity->template);
                        }
                    }
                }
            });
        } else {

            $answer = new \StudentAnswer();

            $answer->activity()->associate($activity);
            $answer->student()->associate(\Sentry::getUser());
            $answer->answer_type_id = 0;
            $answer->answer_type_type = $activity->template_type;
            //$answer->time_needed = Input::get('time_to_complete_activity');

            \Student::find(\Sentry::getUser()->id)
                ->answers()->save($answer);
        }


        // Switch to a next activity in the lesson
        return $this->goToNextActivity($activity, Input::get('test'));
        //return Input::get('time_to_complete_activity');
    }

    private function goToNextActivity($activity, $test = 0)
    {
        $allActivitiesObj = $activity->lesson->activities()->orderBy("order")->get();
        $flag_lesson_completed = false;

        $i = 0;
        $assessment_activity = null;
        foreach ($allActivitiesObj as &$activity_item) {
            if ($activity_item->template_type == 'ActivityTemplates\Assessment') {
                $assessment_activity = $allActivitiesObj[$i];
                unset($allActivitiesObj[$i]);
            }

            $i++;
        }
        if ($assessment_activity != null) {
            $student_answers = \StudentAnswer::whereRaw('student_id = ? and activity_id = ?', array(\Sentry::getUser()->id, $assessment_activity->id))->first();
            $flag_lesson_completed = ($student_answers != null);

            $allActivitiesObj[$i] = $assessment_activity;
        }

        foreach ($allActivitiesObj as &$activity_item) {
            $allActivities[] = $activity_item->id;
        }

        /* $allActivities = $allActivitiesObj->map(function ($activity) {
          return $activity->id;
          })->all(); */

        $nextActivity = array_search($activity->id, $allActivities) + 1;

        if (!isset($allActivities[$nextActivity])) {
            $nextActivity = count($allActivities);
        }

        if ($assessment_activity != null && isset($allActivities[$nextActivity])) {

            $nextActivity_isAssessment = ($allActivities[$nextActivity] == $assessment_activity->id);
            if (!$nextActivity_isAssessment) {
                $flag_lesson_completed = false;
            }
        }

        //if (isset($allActivities[$nextActivity]) && !$flag_lesson_completed) {
        if (isset($allActivities[$nextActivity])) {
            if ($allActivities[$nextActivity] == $assessment_activity->id) {
                $allActivitiesCompleted = true;
                for ($i = 0; $i < count($allActivities) - 2; $i++) {
                    $student_answers = \StudentAnswer::whereRaw('student_id = ? and activity_id = ?', array(\Sentry::getUser()->id, $allActivities[$i]))->first();
                    if ($student_answers == null) {
                        $allActivitiesCompleted = false;
                    }
                }

                if (!$allActivitiesCompleted) {
                    return \Redirect::route('activities.show', $allActivities[0]);
                }
            }

            return \Redirect::route('activities.show', $allActivities[$nextActivity]);
        } else {
            // Last activity in this lesson
            // We have no next activity.
            //$is_last_activity_in_serie = $this->is_last_activity_in_serie($activity);

            return \Redirect::route('user.lesson_finished', $activity->lesson->id);
        }
    }

    private function is_last_activity_in_serie($activity)
    {
        $lesson_id = \DB::table('activities')->select('lesson_id')->where('id', '=', $activity->id)->first();
        $serie_id = \DB::table('lessons')->select('series_id')->where('id', '=', $lesson_id->lesson_id)->first();

        $current_lesson = \Lesson::find($lesson_id->lesson_id);
        $current_serie = \Series::find($serie_id->series_id);

        $all_lessons = $current_serie->lessons()->get();

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

        if ($current_lesson->id == $last_lesson->id) {
            return true;
        } else {
            return false;
        }
    }

    /*private function show_lesson_results($activity)
    {
        $lesson_id = \DB::table('activities')->select('lesson_id')->where('id', '=', $activity->id)->first();
        $current_lesson = \Lesson::find($lesson_id->lesson_id);
        return \Redirect::route('user.lesson_finished', $current_lesson->id);
    }*/

}
