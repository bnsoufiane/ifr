<?php

namespace Admin;

use Illuminate\Support\Facades\Input;
use yajra\Datatables\Facades\Datatables;

class DatatablesController extends BaseController
{
    public function getUsers()
    {
        $users = \DB::table('users')
            ->leftJoin('schools', 'users.school_id', '=', 'schools.id')
            ->join('users_groups', 'users.id', '=', 'users_groups.user_id')
            ->join('groups', 'users_groups.group_id', '=', 'groups.id')
            ->whereIn('users_groups.group_id', [1, 2, 3])
            ->select('users.id', 'users.last_name', 'users.first_name',
                \DB::raw("(GROUP_CONCAT(groups.name)) as `group`")
                , 'schools.id as school_id', 'schools.name as school')
            ->groupBy('users.id');

        return Datatables::of($users)
            ->editColumn('last_name', function ($user) {
                return '<a href="' . \URL::to('admin/users/' . $user->id . '/edit') . '">' . $user->last_name . ', ' . $user->first_name . '</a>';
            })->editColumn('school', function ($user) {
                return '<a href="' . \URL::to('admin/schools/' . $user->school_id . '/edit') . '">' . $user->school . '</a>';
            })->addColumn('actions', function ($user) {
                return '<div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-primary dropdown-toggle"
                                            data-toggle="dropdown">
                                        Action <span class="caret"></span>
                                    </button>
                                    <ul role="menu" class="dropdown-menu pull-right">
                                        <li>
                                            <a href="' . \URL::to('admin/users/' . $user->id . '/edit') . '">Edit Users</a>
                                        </li>
                                        <li><a href="' . \URL::to('admin/users/' . $user->id) . '"
                                               data-action="remove">Delete Users</a></li>
                                    </ul>
                                </div>';
            })->make(true);
    }

    public function getTeachers()
    {
        if (\Sentry::getUser()->isSchoolAdmin()) {
            $users = \DB::table('users')
                ->leftJoin('schools', 'users.school_id', '=', 'schools.id')
                ->join('users_groups', 'users.id', '=', 'users_groups.user_id')
                ->where('users_groups.group_id', 3)
                ->where('schools.id', \Sentry::getUser()->school_id)
                ->select('users.id', 'users.last_name', 'users.first_name', 'users.last_login',
                    'schools.id as school_id', 'schools.name as school')
                ->groupBy('users.id');
        } else {
            $users = \DB::table('users')
                ->leftJoin('schools', 'users.school_id', '=', 'schools.id')
                ->join('users_groups', 'users.id', '=', 'users_groups.user_id')
                ->where('users_groups.group_id', 3)
                ->select('users.id', 'users.last_name', 'users.first_name', 'users.last_login',
                    'schools.id as school_id', 'schools.name as school')
                ->groupBy('users.id');
        }

        return Datatables::of($users)
            ->editColumn('last_name', function ($user) {
                return '<a href="' . \URL::to('admin/teachers/' . $user->id . '/edit') . '">' . $user->last_name . ', ' . $user->first_name . '</a>';
            })->editColumn('school', function ($user) {
                return '<a href="' . \URL::to('admin/schools/' . $user->school_id . '/edit') . '">' . $user->school . '</a>';
            })->addColumn('count', function ($user) {
                return \DB::table('school_classes')
                    ->join('school_class_student', 'school_classes.id', '=', 'school_class_student.school_class_id')
                    ->whereNull('school_classes.deleted_at')
                    ->where('created_by', $user->id)
                    ->where('school_id', $user->school_id)->count();
            })->addColumn('actions', function ($user) {
                return '<div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-primary dropdown-toggle"
                                            data-toggle="dropdown">
                                        Action <span class="caret"></span>
                                    </button>
                                    <ul role="menu" class="dropdown-menu pull-right">
                                        <li>
                                            <a href="' . \URL::to('admin/teachers/' . $user->id . '/edit') . '">Edit Teacher</a>
                                        </li>
                                        <li><a href="' . \URL::to('admin/teachers/' . $user->id) . '"
                                               data-action="remove">Delete Teacher</a></li>
                                    </ul>
                                </div>';
            })->make(true);
    }

    public function getTeachersReport()
    {
        if (\Sentry::getUser()->isSchoolAdmin()) {
            $users = \DB::table('users')
                ->leftJoin('schools', 'users.school_id', '=', 'schools.id')
                ->join('users_groups', 'users.id', '=', 'users_groups.user_id')
                ->where('users_groups.group_id', 3)
                ->where('schools.id', \Sentry::getUser()->school_id)
                ->select('users.id', 'users.last_name', 'users.first_name',
                    'schools.id as school_id', 'schools.name as school')
                ->groupBy('users.id');
        } else {
            $users = \DB::table('users')
                ->leftJoin('schools', 'users.school_id', '=', 'schools.id')
                ->join('users_groups', 'users.id', '=', 'users_groups.user_id')
                ->where('users_groups.group_id', 3)
                ->select('users.id', 'users.last_name', 'users.first_name',
                    'schools.id as school_id', 'schools.name as school')
                ->groupBy('users.id');
        }

        return Datatables::of($users)
            ->editColumn('last_name', function ($user) {
                return '<a href="' . \URL::route('admin.classes.grades.teacher_classes', array($user->id, 'teacher' => $user->id)) . '">' . $user->last_name . ', ' . $user->first_name . '</a>';
            })->editColumn('school', function ($user) {
                return $user->school;
//                return '<a href="'.\URL::to('admin/schools/'.$user->school_id.'/edit').'">'.$user->school.'</a>';
            })->addColumn('actions', function ($user) {
                return '<div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-primary dropdown-toggle"
                                            data-toggle="dropdown">
                                        Action <span class="caret"></span>
                                    </button>
                                    <ul role="menu" class="dropdown-menu pull-right">
                                        <li>
                                            <a href="' . \URL::to('admin/teachers/' . $user->id . '/edit') . '">Edit Teacher</a>
                                        </li>
                                        <li><a href="' . \URL::to('admin/teachers/' . $user->id) . '"
                                               data-action="remove">Delete Teacher</a></li>
                                    </ul>
                                </div>';
            })->make(true);
    }

    public function getTeacherClassesReport()
    {
        $teacher = \User::find(Input::get('teacher_id'));
        $classes = \DB::table('school_classes')
            ->where('school_classes.created_by', $teacher->id)
            ->where('school_classes.school_id', $teacher->school_id)
            ->whereNull('school_classes.deleted_at')
            ->select('school_classes.id', 'school_classes.name')
            ->groupBy('school_classes.id');

        return Datatables::of($classes)
            ->addColumn('class_reports', function ($class) {
                return '<div class="btn-group">
                                    <a href="' . \URL::route('admin.reports.classes.scores_by_series', $class->id) . '" class="btn btn-sm btn-primary">
                                        by Series
                                    </a>
                                </div>
                                <div class="btn-group">
                                    <a href="' . \URL::route('admin.reports.classes.scores_by_lesson_landing', $class->id) . '" class="btn btn-sm btn-primary">
                                        by Lesson
                                    </a>
                                </div>';
            })->addColumn('student_reports', function ($class) {
                return ' <div class="btn-group">
                                    <a href="' . \URL::route('admin.reports.classes.student_scores_by_series_landing', $class->id) . '" class="btn btn-sm btn-primary">
                                        by Series
                                    </a>
                                </div>
                                <div class="btn-group">
                                    <a href="' . \URL::route('admin.reports.classes.student_scores_by_lesson_landing', $class->id) . '" class="btn btn-sm btn-primary">
                                        by Lesson
                                    </a>
                                </div>';
            })->addColumn('test_reports', function ($class) {
                return ' <div class="btn-group">
                                    <a href="' . \URL::route('admin.reports.classes.pre_post_tests_scores', $class->id) . '" class="btn btn-sm btn-primary">
                                        Pre/Post Test
                                    </a>
                                </div>
                                <div class="btn-group">
                                    <a href="' . \URL::route('admin.reports.classes.final_grade', $class->id) . '" class="btn btn-sm btn-primary">
                                        Final Grade
                                    </a>
                                </div>';
            })->make(true);
    }

    public function getClasses()
    {
        if (\Sentry::getUser()->isSchoolAdmin()) {
            $classes = \DB::table('school_classes')
                ->where('school_classes.school_id', \Sentry::getUser()->school_id)
                ->whereNull('school_classes.deleted_at')
                ->leftJoin('users', 'users.id', '=', 'school_classes.created_by')
                ->join('users_groups', 'users.id', '=', 'users_groups.user_id')
                ->where('users_groups.group_id', 3)
                ->join('schools', 'schools.id', '=', 'school_classes.school_id')
                ->select('school_classes.id', 'users.id as teacher_id', 'users.first_name', 'users.last_name', 'school_classes.name', 'schools.id as school_id', 'schools.name as school')
                ->groupBy('school_classes.id');
        } elseif (\Sentry::getUser()->isTeacher()) {
            $classes = \DB::table('school_classes')
                ->where('school_classes.school_id', \Sentry::getUser()->school_id)
                ->where('school_classes.created_by', \Sentry::getUser()->id)
                ->whereNull('school_classes.deleted_at')
                ->leftJoin('users', 'users.id', '=', 'school_classes.created_by')
                ->join('users_groups', 'users.id', '=', 'users_groups.user_id')
                ->where('users_groups.group_id', 3)
                ->join('schools', 'schools.id', '=', 'school_classes.school_id')
                ->select('school_classes.id', 'users.id as teacher_id', 'users.first_name', 'users.last_name', 'school_classes.name', 'schools.id as school_id', 'schools.name as school')
                ->groupBy('school_classes.id');
        } else {
            $classes = \DB::table('school_classes')
                ->whereNull('school_classes.deleted_at')
                ->leftJoin('users', 'users.id', '=', 'school_classes.created_by')
                ->join('users_groups', 'users.id', '=', 'users_groups.user_id')
                ->where('users_groups.group_id', 3)
                ->join('schools', 'schools.id', '=', 'school_classes.school_id')
                ->select('school_classes.id', 'users.id as teacher_id', 'users.first_name', 'users.last_name', 'school_classes.name', 'schools.id as school_id', 'schools.name as school')
                ->groupBy('school_classes.id');
        }

        return Datatables::of($classes)
            ->editColumn('name', function ($class) {
                if (!\Sentry::getUser()->isTeacher()) {
                    return '<a href="' . \URL::to('admin/classes/' . $class->id . '/edit') . '">' . $class->name . '</a>' . ' <a href = "' . \URL::route('admin.classes.destroy', $class->id) . '" style = "color: #f29f8a;" data-action = "remove">
                                <i class="fa fa-minus-square" ></i></a>';
                } else {
                    return $class->name;
                }
            })
            ->editColumn('teacher', function ($class) {
                return '<a href="' . \URL::to('admin/teachers/' . $class->teacher_id . '/edit') . '">' . $class->last_name . ', ' . $class->first_name . '</a>';
            })->editColumn('school', function ($class) {
                return '<a href="' . \URL::to('admin/schools/' . $class->school_id . '/edit') . '">' . $class->school . '</a>';
            })->addColumn('students', function ($class) {
                return '<div class="btn-group">
                                <a href="' . \URL::route('admin.classes.add_students', $class->id) . '" class="btn btn-sm btn-primary">
                                    Add New
                                </a>
                            </div>
                            <div class="btn-group">
                                <a href="' . \URL::route('admin.classes.students', $class->id) . '" class="btn btn-sm btn-primary">
                                    View/Edit
                                </a>
                            </div>';
            })->addColumn('lessons', function ($class) {
                return '<div class="btn-group">
                                <a href="' . \URL::route('admin.classes.optional_lessons_setup', $class->id) . '" class="btn btn-sm btn-primary">
                                    Required
                                </a>
                            </div>
                            <div class="btn-group">
                                <a href="' . \URL::route('admin.tests.setup', $class->id) . '" class="btn btn-sm btn-primary">
                                    Pre/Post Tests
                                </a>
                            </div>
                            <div class="btn-group">
                                <a target="_blank" href="' . \URL::route('admin.classes.preview', $class->id) . '" class="btn btn-sm btn-primary">
                                    Preview
                                </a>
                            </div>';
            })->addColumn('classes', function ($class) {
                return '<div class="btn-group">
                                <a href="' . \URL::route('admin.classes.edit', $class->id) . '" class="btn btn-sm btn-primary">
                                    Edit
                                </a>
                            </div>
                            <div class="btn-group">
                                <a href="' . \URL::route('admin.classes.destroy', $class->id) . '" class="btn btn-sm btn-primary" data-action="remove">
                                    Delete
                                </a>
                            </div>';
            })->make(true);
    }

    public function getStudents()
    {
        $users = \DB::table('users')
            ->join('users_groups', 'users.id', '=', 'users_groups.user_id')
            ->join('school_class_student', 'school_class_student.student_id', '=', 'users.id')
            ->join('school_classes', 'school_classes.id', '=', 'school_class_student.school_class_id')
            ->where('users_groups.group_id', 4)
            ->where('school_classes.school_id', \Sentry::getUser()->school_id)
            ->where('school_classes.created_by', \Sentry::getUser()->id)
            ->whereNull('school_classes.deleted_at')
            ->where('users.school_id', \Sentry::getUser()->school_id)
            ->select('users.id', 'users.last_name', 'users.first_name', 'users.username',
                \DB::raw("(GROUP_CONCAT(school_classes.name)) as `class`"))
            ->groupBy('users.id');

        return Datatables::of($users)
            ->editColumn('last_name', function ($user) {
                return '<a href="' . \URL::to('admin/users/' . $user->id . '/edit') . '">' . $user->last_name . ', ' . $user->first_name . '</a>';
            })->editColumn('username', function ($user) {
                return '<a href="' . \URL::to('admin/users/' . $user->id . '/edit') . '">' . $user->username . '</a>';
            })->addColumn('actions', function ($user) {
                return '<div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-primary dropdown-toggle"
                                            data-toggle="dropdown">
                                        Action <span class="caret"></span>
                                    </button>
                                    <ul role="menu" class="dropdown-menu pull-right">
                                        <li>
                                            <a href="' . \URL::to('admin/users/' . $user->id . '/edit') . '">Edit Users</a>
                                        </li>
                                        <li><a href="' . \URL::to('admin/users/' . $user->id) . '"
                                               data-action="remove">Delete Users</a></li>
                                        <li><a target="_blank"
                                                   href="' . \URL::route('admin.students.show_lessons', $user->id) . '">Reset
                                                    Lesson</a></li>
                                            <li class="series_to_reset">
                                                <a class="add-new" href="javascript:void(0);"
                                                   student_id="' . $user->id . '">Reset Series</a>
                                            </li>
                                            <li><a href="' . \URL::route('admin.students.reset_posttest', $user->id) . '">Reset
                                                    Post-test</a></li>
                                    </ul>
                                </div>';
            })->make(true);
    }


    public function getSchools()
    {

        $schools = \DB::table('schools')
            ->leftJoin('school_districts', 'schools.school_district_id', '=', 'school_districts.id')
            ->select('schools.id as school_id', 'schools.name as school', 'school_districts.name as district')
            ->groupBy('schools.id');


        return Datatables::of($schools)
            ->editColumn('school', function ($school) {
                return '<a href="' . \URL::to('admin/schools/' . $school->school_id . '/edit') . '">' . $school->school . '</a>';
            })->addColumn('admins', function ($school) {
                $school_admins = \DB::table('users')
                    ->join('users_groups', 'users.id', '=', 'users_groups.user_id')
                    ->where('users_groups.group_id', 2)
                    ->where('users.school_id', $school->school_id)->get();
                $admins = '';

                foreach ($school_admins as $admin) {
                    $admins .= '<a href="' . \URL::to('admin/users/' . $admin->id . '/edit') . '">' . $admin->last_name . ', ' . $admin->first_name . '</a> | ';
                }
                return $admins;
            })->addColumn('actions', function ($school) {
                return '<div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-primary dropdown-toggle"
                                            data-toggle="dropdown">
                                        Action <span class="caret"></span>
                                    </button>
                                    <ul role="menu" class="dropdown-menu pull-right">
                                        <li>
                                            <a href="' . \URL::to('admin/schools/' . $school->school_id . '/edit') . '">Edit School</a>
                                        </li>
                                        <li><a href="' . \URL::route('admin.schools.destroy', $school->school_id) . '"
                                              id="delete_school_btn" data-action="remove">Delete School</a></li>
                                    </ul>
                                </div>';
            })->make(true);
    }
}