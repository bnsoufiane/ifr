<?php

namespace Admin;

use ActivityTemplates\BaseActivityTemplate;
use View;
use Input;
use Response;
use Redirect;
use Exception;

class LessonsController extends BaseController
{

    /**
     * Shows an editor to create a new lesson.
     *
     * @param $seriesId
     * @return mixed
     */
    public function create($seriesId)
    {
        $series = \Series::find($seriesId);

        if (!$series) {
            return Redirect::route('admin.modules.index')
                ->with('error', 'Cannot create a lesson in a non-existing series.');
        }

        // Create a product skin instance
        $moduleSkinClass = $series->module->skin;
        $moduleSkin = new $moduleSkinClass();

        $this->layout->content = View::make('admin/lessons/create')
            ->with('activityTemplates', $moduleSkin->getActivityTemplates())
            ->with('series', $series)
            ->with('lesson', new \Lesson());
    }

    /**
     * Shows an editor to update a lesson.
     *
     * @param $lessonId
     * @return mixed
     */
    public function edit($lessonId)
    {
        $lesson = \Lesson::find($lessonId);

        if (!$lesson) {
            return Redirect::route('admin.modules.index')
                ->with('error', 'Cannot edit a non-existing lesson.');
        }

        // Create a product skin instance
        $moduleSkinClass = $lesson->series->module->skin;
        $moduleSkin = new $moduleSkinClass();

        $activityTemplates = $moduleSkin->getActivityTemplates();
        unset($activityTemplates["Assessment"]);

        $i = 0;
        foreach ($lesson->activities as &$value) {
            if (($value->template_type == "ActivityTemplates\Assessment")) {

                unset($lesson->activities[$i]);
            }
            $i++;
        }

        $this->layout->content = View::make('admin/lessons/edit')
            ->with('lesson', $lesson)
            ->with('series', $lesson->series)
            ->with('activityTemplates', $activityTemplates);
    }

    /**
     * Shows an editor to update a lesson.
     *
     * @param $lessonId
     * @return mixed
     */
    public function add_assessment($lessonId)
    {
        $lesson = \Lesson::find($lessonId);

        if (!$lesson) {
            return Redirect::route('admin.modules.index')
                ->with('error', 'Cannot edit a non-existing lesson.');
        }

        // Create a product skin instance
        $moduleSkinClass = $lesson->series->module->skin;
        $moduleSkin = new $moduleSkinClass();

        $activityTemplates = $moduleSkin->getActivityTemplates();

        unset($activityTemplates["Assessment"]);

        $assessment_already_created = false;
        foreach ($lesson->activities as &$value) {
            if (($value->template_type == "ActivityTemplates\Assessment")) {
                $assessment_already_created = true;
            }
        }

        $this->layout->content = View::make('admin/lessons/add_assessment')
            ->with('lesson', $lesson)
            ->with('series', $lesson->series)
            ->with('activityTemplates', $activityTemplates)
            ->with('assessment_already_created', $assessment_already_created);
    }

    /**
     * Returns a lesson serialized to JSON.
     * @param $id
     * @return mixed
     */
    public function show($id)
    {
        $lesson = \Lesson::with('activities', 'activities.template')->find($id);

        if (!$lesson) {
            return Response::make('Lesson not found.', 404);
        }

        foreach ($lesson->activities as &$activity) {
            $sub_activities = $activity->getSubAcitivities();
            if (count($sub_activities) > 0) {
                $activity->sub_activities = $sub_activities;
            }
        }

        foreach ($lesson->activities as &$activity) {
            if ($activity->sub_activities) {
                for ($i = 0; $i < count($activity->sub_activities); $i++) {
                    $activity->template = $activity->sub_activities[$i]->template;

                }
            }
        }

        return $lesson;
    }

    /**
     * Preview a single activity
     */
    public function preview()
    {
        $input = Input::get();

        if ($input === null) {
            return Response::make('You haven\'t provided activity data.', 400);
        }

        \DB::beginTransaction();

        // Creating fake lessons, activities, and series
        $activity = new \Activity($input);

        $activityTemplate = BaseActivityTemplate::createFromArray($input);

        $activity->template()->associate($activityTemplate);

        $series = \Series::find($input['series_id']);

        $lesson = new \Lesson(array('title' => 'Preview', 'order' => 0));
        $lesson->series()->associate($series);
        $lesson->save();

        $activity->lesson()->associate($lesson);
        $activity->save();

        // Build an activity template
        $templateType = $activity->template_type;
        $meta = $templateType::getMetaData();

        $i = 0;
        $sub_activities_views = [];
        if (!empty($input['sub_activities'])) {
            foreach ($input['sub_activities'] as $sub_activity) {

                $_sub_activity = new \Activity($sub_activity);

                $_sub_activity->template()
                    ->associate(BaseActivityTemplate::createFromArray($sub_activity));

                $_template_type = $sub_activity['template_type'];

                if (strpos($_template_type, 'ActivityTemplates\\') === false) {
                    $_template_type = 'ActivityTemplates\\' . $_template_type;
                }

                $_meta = $_template_type::getMetaData();
                $_data["lesson_title"] = $lesson->title;
                $_data["current_activity"] = $_sub_activity;

                $sub_activities_views[] = (string)(\View::make($_meta['user_template'], $_data)
                    ->with('data', $_sub_activity->template)
                    ->with('hasAnswer', false)
                    ->with('answer', null)
                    ->with('test', \Session::get('test'))
                    ->with('answerData', null)
                    ->with('is_subactivity', true)
                    ->with('preview', true));

                $i++;
            }
        }

        $this->layout = \View::make('layouts.preview');

        $content = \View::make($meta['user_template'])
            ->with('current_activity', $activity)
            ->with('data', $activity->template)
            ->with('hasAnswer', false)
            ->with('test', null)
            ->with('preview', true);

        $this->layout->with('activityId', $activity->id)
            ->with('template_type', $activity->template_type)
            ->with('activiy_bg_img', $activity->background_image)
            ->with('content', $content->render())
            ->with('sub_activities_views', $sub_activities_views);

        $this->layout->render();

        \DB::rollback();
    }

    /**
     * Stores a new lesson from JSON-serialized data.
     */
    public function store()
    {

        $input = Input::get();

        if ($input === null) {
            return Response::make('You haven\'t provided activities data.', 400);
        }

        /*$validator = \Lesson::validate($input);

        if ($validator->fails()) {
            return Redirect::route('admin.lessons.create')
                ->withInput()
                ->withErrors($validator);
        }*/

        \DB::beginTransaction();

        $series = \Series::find($input['series_id']);
        if (!$series) {
            return Response::make('Incorrect series ID.', 400);
        }

        $lesson = new \Lesson($input);
        $lesson->series()->associate($series);
        $lesson->save();

        // Adding activities
        try {
            $activities = array_map(function ($activityData) {
                $activity = new \Activity($activityData);

                $activity->template()
                    ->associate(BaseActivityTemplate::createFromArray($activityData));

                return $activity;
            }, $input['activities']);
        } catch (Exception $e) {
            \DB::rollback();
            return Response::make($e->getMessage(), 400);
        }

        // Creating a new lesson.
        $lesson->activities()->saveMany($activities);

        \DB::commit();

        \Session::flash('success', 'Lesson has been succesfully saved.');

        return Response::make(array('ok' => true), 201);
    }

    /**
     * Updates a lesson from JSON-serialized data.
     * @param $id
     */
    public function update($id)
    {
        $input = Input::get();

        /*$validator = \Lesson::validate($input, $id);

        if ($validator->fails()) {
            var_dump($validator);
            die();
            return \Redirect::route('admin.lessons.edit', $id)
                ->withInput()
                ->withErrors($validator);
        }*/

        $isAssessment = false;
        foreach ($input['activities'] as &$value) {
            if (!isset($value['template_type'])) {
                $value['template_type'] = "Assessment";
                $isAssessment = true;
            }
        }

        $lesson = \Lesson::find($input['id']);

        if (!$lesson) {
            return Response::make('Lesson wasn\'t found.', 400);
        }

        // Updating the lesson data.
        \DB::beginTransaction();

        $lesson->title = $input['title'];
        $lesson->topic = $input['topic'];
        //$lesson->minimum_score = $input['minimum_score'];

        // Updating activities data.
        $updatedActivities = array();

        foreach ($input['activities'] as $activityData) {
            if (isset($activityData['id'])) {
                // Update an existing activity.

                if($activityData['order'] != -1) {

                    if (!empty($activityData['sub_activities'])) {
                        $updatedSubActivities = array();

                        foreach ($activityData['sub_activities'] as $subActivity) {
                            if (isset($subActivity["model"])) {
                                $subActivity = $subActivity["model"];
                            }

                            if (empty($subActivity['id'])) {
                                //var_dump($activityData['sub_activities'][0]['model']);
                                $subActivity['parent_activity'] = $activityData['id'];
                                //$updatedSubActivities[] = $activityData['id'];
                                $activity = new \Activity($subActivity);

                                $activity->template()
                                    ->associate(BaseActivityTemplate::createFromArray($subActivity));

                                $activity->save();
                                $updatedSubActivities[] = $activity->id;
                            } else {
                                $subActivity['template_type'] = str_replace('ActivityTemplates\\', '', $subActivity['template_type']);
                                $_sub_activity = \Activity::find($subActivity['id']);

                                if ($_sub_activity->template != null) {
                                    $_sub_activity->template->delete();
                                    $_sub_activity->template()
                                        ->associate(BaseActivityTemplate::createFromArray($subActivity));
                                    $_sub_activity->save();
                                    $updatedSubActivities[] = $_sub_activity->id;
                                }
                            }
                        }
                    }

                    $activity = \Activity::find($activityData['id']);
                    if (!empty($activityData['sub_activities']) && count($updatedSubActivities)) {
                        $sub_activities_to_delete = \Activity::where('parent_activity', '=', $activity->id)->whereNotIn('id', $updatedSubActivities)->delete();
                    } else {
                        $sub_activities_to_delete = \Activity::where('parent_activity', '=', $activity->id)->delete();
                    }

                    $activity->update($activityData);
                    // Update the template data

                    $templateClass = new \ReflectionClass($activity->template);

                    if ($templateClass->getShortName() != $activityData['template_type']) {
                        // Use a new activity template.
                        $activity->template->delete();

                        $activity->template()
                            ->associate(BaseActivityTemplate::createFromArray($activityData));

                        $activity->save();
                    } else {
                        // Update old activity template.
                        if (!$isAssessment || ($activityData['template_type'] == "Assessment")) {
                            if (($activityData['template_type'] != "Calculation") || (isset($activityData['template']['name']))) {
                                $activity->template->saveFromArray($activityData);
                            }
                        }
                    }
                }
                $updatedActivities[] = $activityData['id'];

            } else {
                // Add a new activity
                $activity = new \Activity($activityData);

                $activity->template()
                    ->associate(BaseActivityTemplate::createFromArray($activityData));

                $lesson->activities()->save($activity);

                $updatedActivities[] = $activity->id;

                if (!empty($activityData['sub_activities'])) {
                    foreach ($activityData['sub_activities'] as $subActivity) {
                        $subActivity['parent_activity'] = $activity->id;
                        $sub_activity = new \Activity($subActivity);

                        $sub_activity->template()
                            ->associate(BaseActivityTemplate::createFromArray($subActivity));

                        $sub_activity->save();
                        //$lesson->activities()->save($activity);

                        //$updatedActivities[] = $activity->id;
                    }
                }

            }
        }

        // Deleting removed activities.
        $activities_to_delete = ($updatedActivities == null) ? [] : $lesson->activities()->whereNotIn('id', $updatedActivities)->get();

        foreach ($activities_to_delete as &$value) {
            try {
                $template = $value->template()->first();
                if (isset($template)) {
                    $template->delete_activity();
                }

                \StudentAnswer::where('activity_id', '=', $value->id)->delete();
                $value->delete();
            } catch (Exception $e) {
                var_dump($e->getMessage());
            }
        }

        //$lesson->activities()->whereNotIn('id', $updatedActivities)->delete();

        $lesson->save();

        \DB::commit();
        \Session::flash('success', 'Lesson has been succesfully updated.');
        return Response::make(array('ok' => true), 201);
    }

    /**
     * Uploads files for lessons
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

    public function destroy($id)
    {
        if (\Lesson::destroy($id) > 0) {
            return Redirect::route('admin.modules.index')
                ->with('success', 'Lesson has been successfully deleted.');
        } else {
            return Redirect::route('admin.modules.index')
                ->with('error', 'Cannot delete lesson because it doesn\'t exist or it is already deleted.');
        }
    }

    public function make_optional($lesson_id)
    {
        $lesson = \Lesson::find($lesson_id);
        $lesson->optional = intval(Input::get('optional'));
        $lesson->save();

        return "true";
    }

}
