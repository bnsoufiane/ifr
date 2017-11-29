<?php

namespace Admin;

use View;
use Input;
use Redirect;
use Curotec\Presenters\SchoolPresenter;

class SchoolsController extends BaseController
{

    public function __construct()
    {
        $this->beforeFilter(function () {
            // Check if a user has cancelled editing.
            if (Input::has('cancel')) {
                return Redirect::route('admin.schools.index');
            }
        }, array('only' => array('store', 'update')));
    }

    public function index()
    {
//        $schools = \School::all();

        $this->layout->content = View::make('admin/schools/index');
//            ->with('schools', SchoolPresenter::wrap($schools));
    }

    public function schools_list()
    {
        $schoolsList = array_reduce(\School::all()->toArray(), function ($schools, $school) {
            $schools[$school['id']] = $school['name'];
            return $schools;
        }, array("NULL" => ' - No Attached School -'));

        return $schoolsList;
    }

    public function school_districts_list()
    {
        $school_districts_list = array_reduce(\SchoolDistrict::all()->toArray(), function ($school_districts, $school_district) {
            $school_districts[$school_district['id']] = $school_district['name'];
            return $school_districts;
        }, array("NULL" => ' - Select A School District -'));

        return $school_districts_list;
    }

    public function create()
    {
        $this->layout->content = View::make('admin/schools/create')
            ->with('school', new \School())
            ->with('school_districts', $this->school_districts_list())
            ->with('route', 'admin.schools.store');
    }

    public function edit($id)
    {
        $school = \School::find($id);
        $this->layout->content = View::make('admin/schools/edit')
            ->with('school', $school)
            ->with('school_district', \SchoolDistrict::find($school->school_district_id))
            ->with('route', array('admin.schools.update', $id))
            ->with('school_districts', $this->school_districts_list())
            ->with('method', 'PUT');
    }

    public function store()
    {
        $input = Input::all();
        $validator = \School::validate($input);

        if ($validator->fails()) {
            return Redirect::route('admin.schools.create')
                ->withInput()
                ->withErrors($validator);
        } else {
            $schoolAdminsGroup = \Sentry::findGroupByName('School Administrator');

            \DB::beginTransaction();

            unset($input['id']);

            $school = \School::create($input);

            if ($input['school_districts_id'] != "NULL") {
                $school_district = \SchoolDistrict::find($input['school_districts_id']);
                $school->school_district()->associate($school_district);
            }

            // Assign modules to a new school.
            if (isset($input['module_id'])) {
                $school->series()->sync($input['module_id']);
            } else {
                $school->series()->sync(array());
            }

            // Assign users to a new school.
            if (isset($input['school_admin_id'])) {
                $users = \User::whereIn('id', $input['school_admin_id'])
                    ->withoutSchool()->ofGroup($schoolAdminsGroup)
                    ->update(array('school_id' => $school->id));
            }

            $school->save();

            \DB::commit();

            return Redirect::route('admin.schools.index')
                ->with('success', 'A school has been successfully created.');
        }
    }

    public function update($id)
    {
        $input = Input::all();
        $validator = \School::validate($input, $id);

        if ($validator->fails()) {
            return Redirect::route('admin.schools.edit', $id)
                ->withInput()
                ->withErrors($validator);
        } else {
            $schoolAdminsGroup = \Sentry::findGroupByName('School Administrator');

            \DB::beginTransaction();

            $school = \School::find($id);

            $school->fill($input);

            if (isset($input['module_id'])) {
                $school->series()->sync($input['module_id']);
            } else {
                $school->series()->sync(array());
            }

            if ($input['school_districts_id'] != "NULL") {
                $school_district = \SchoolDistrict::find($input['school_districts_id']);
                $school->school_district()->associate($school_district);
            }

            $school->save();

            // Unassign all removed admins.
            if (isset($input['school_admin_id'])) {
                foreach ($school->admins()->get() as $admin) {
                    if (!in_array((string)$admin->id, $input['school_admin_id'])) {
                        \User::find($admin->id)->update(array('school_id' => null));
                    }
                }

                // Reassign admins to the school.
                $user = \User::whereIn('id', $input['school_admin_id'])
                    ->withoutSchool()
                    ->ofGroup($schoolAdminsGroup)
                    ->update(array('school_id' => $school->id));
            }

            \DB::commit();

            return Redirect::route('admin.schools.index')
                ->with('success', 'A school has been successfully edited.');
        }
    }

    public function destroy($id)
    {

        //\DB::beginTransaction();
        //$school = \School::find($id);
        //\DB::commit();

        if (\School::destroy($id) > 0) {
            return Redirect::route('admin.schools.index')
                ->with('success', 'A school has been successfully deleted.');
        } else {
            return Redirect::route('admin.schools.index')
                ->with('error', 'Cannot delete this school because it doesn\'t exist or it is already deleted.');
        }
    }

    public function create_school_districts()
    {
        $this->layout->content = View::make('admin/school_districts/create')
            ->with('school', new \SchoolDistrict())
            ->with('route', 'admin.school_districts.store');
    }

    public function store_school_districts()
    {
        $input = Input::all();
        $validator = \SchoolDistrict::validate($input);

        if ($validator->fails()) {
            return Redirect::route('admin.school_districts.create')
                ->withInput()
                ->withErrors($validator);
        } else {
            \DB::beginTransaction();

            unset($input['id']);

            $school_district = \SchoolDistrict::create($input);

            $school_district->save();

            \DB::commit();

            return Redirect::route('admin.schools.index')
                ->with('success', 'A school district has been successfully created.');
        }

        return "store_school_districts";
    }

    public function preview($activity_id = -1)
    {
        $currentUser = \Sentry::getUser();

        $school = $currentUser->school()->first();

        $series = $school->series()->get();

        //$activity_id= 3;

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
            ->with('class_id', $school->id)
            ->with('class_name', $school->name)
            ->with('current_activity', $activity)
            ->with('series', $series)
            ->with('template_type', $activity->template_type)
            ->with('activiy_bg_img', $activity->background_image)
            ->with('content', $content->render())
            ->with('sub_activities_views', $sub_activities_views)
            ->with('is_school_preview', true);

        $this->layout->render();
    }


}
