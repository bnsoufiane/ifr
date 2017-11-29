<?php

/*
  |--------------------------------------------------------------------------
  | Application Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register all of the routes for an application.
  | It's a breeze. Simply tell Laravel the URIs it should respond to
  | and give it the Closure to execute when that URI is requested.
  |
 */

Route::get('/', array('as' => 'index', 'uses' => 'MainController@index'));

Route::get('print_activity', array('as' => 'print_activity', 'uses' => 'MainController@print_activity'));

Route::get('clean_unused_uploaded_pictures', array('as' => 'clean_unused_uploaded_pictures', 'uses' => 'MainController@clean_unused_uploaded_pictures'));
Route::get('background_images_report', array('as' => 'background_images_report', 'uses' => 'MainController@background_images_report'));

// Common routes
Route::get('sign-in', array('as' => 'sign-in', 'uses' => 'AuthController@showAuthForm'));
Route::post('sign-in', array('uses' => 'AuthController@postAuthForm'));
Route::get('sign-out', array('as' => 'sign-out', 'uses' => 'AuthController@signOut'));
Route::get('/forgot_password', array('as' => 'forgot_password', 'uses' => 'AuthController@forgot_password'));
Route::post('/forgot_password', array('uses' => 'AuthController@postForgotPassword'));
Route::get('/password_email_sent', array('as' => 'password_email_sent', 'uses' => 'AuthController@password_email_sent'));
Route::get('/password/reset/{id}/{token}', array('as' => 'reset_password', 'uses' => 'AuthController@reset_password'));
Route::post('/save_reset_password', array('as' => 'save_reset_password', 'uses' => 'AuthController@save_reset_password'));

Route::get('/signin', function () {
    return Redirect::route('sign-in');
});
Route::get('/signout', function () {
    return Redirect::route('sign-out');
});


Route::get('checkSession', array('uses' => 'AuthController@checkSession', 'as' => 'checkSession'));

// User facing routes
Route::group(array('namespace' => 'User', 'before' => 'auth.require-user'), function () {
    Route::get('/activities', function () {
        return Redirect::route('index');
    });

    // Events
    Route::get('/events/store', array('as' => 'events.store', 'uses' => 'EventsController@store'));
    Route::resource('events', 'EventsController', array(
        'only' => array('store')
    ));

    // Activities
    Route::resource('activities', 'ActivityController', array(
        'only' => array('show', 'store')
    ));

    // Lessons
    Route::resource('lessons', 'LessonsController', array(
        'only' => array('show')
    ));

    Route::get('change_password', array('as' => 'user.change_password', 'uses' => 'MessagesController@change_password'));
    Route::post('save_password', array('as' => 'user.save_password', 'uses' => 'MessagesController@save_password'));
    Route::get('pretest', array('as' => 'user.pretest', 'uses' => 'TestsController@pretest'));
    Route::get('posttest', array('as' => 'user.posttest', 'uses' => 'TestsController@posttest'));
    Route::get('take_pretest', array('as' => 'user.take_pretest', 'uses' => 'MessagesController@take_pretest'));
    Route::post('test_finished', array('as' => 'user.test_finished', 'uses' => 'TestsController@test_finished'));
    Route::get('pretest_finished', array('as' => 'user.pretest_finished', 'uses' => 'TestsController@pretest_finished'));
    Route::get('take_posttest', array('as' => 'user.take_posttest', 'uses' => 'MessagesController@take_posttest'));
    Route::get('posttest_finished', array('as' => 'user.posttest_finished', 'uses' => 'TestsController@posttest_finished'));
    Route::get('lesson_finished/{lesson_id}', array('as' => 'user.lesson_finished', 'uses' => 'MessagesController@lesson_finished'));
    Route::get('choose_optional_lessons', array('as' => 'user.choose_optional_lessons', 'uses' => 'MessagesController@choose_optional_lessons'));
    Route::get('submit_optional_lessons', array('as' => 'user.submit_optional_lessons', 'uses' => 'MessagesController@submit_optional_lessons'));
    Route::post('add_optional_lesson/{lesson_id}', array('as' => 'user.add_optional_lesson', 'uses' => 'MessagesController@add_optional_lesson'));
    Route::get('no_activities_to_display', array('as' => 'user.no_activities_to_display', 'uses' => 'MessagesController@no_activities_to_display'));
    Route::get('reset_posttest', array('as' => 'user.reset_posttest', 'uses' => 'TestsController@reset_posttest'));
    Route::get('reset_lesson/{lesson_id}', array('as' => 'user.reset_lesson', 'uses' => 'TestsController@reset_lesson'));
    Route::get('go_to_next_lesson/{lesson_id}', array('as' => 'user.go_to_next_lesson', 'uses' => 'TestsController@go_to_next_lesson'));

    Route::get('404', array('as' => 'errors.404', 'uses' => 'MessagesController@error404'));
    Route::get('500', array('as' => 'errors.500', 'uses' => 'MessagesController@error500'));
});

// Admin facing routes
Route::group(array('prefix' => 'admin', 'namespace' => 'Admin', 'before' => 'auth.admin'), function () {
    Route::get('', array('as' => 'admin.index', 'uses' => 'DashboardController@showIndex'));

    Route::get('datatables/users', ['as' => 'admin.datatables.users', 'uses' => 'DatatablesController@getUsers']);
    Route::get('datatables/teachers', ['as' => 'admin.datatables.teachers', 'uses' => 'DatatablesController@getTeachers']);
    Route::get('datatables/teachers-report', ['as' => 'admin.datatables.teachers-report', 'uses' => 'DatatablesController@getTeachersReport']);
    Route::get('datatables/teacher-classes-report', ['as' => 'admin.datatables.teacher-classes-report', 'uses' => 'DatatablesController@getTeacherClassesReport']);
    Route::get('datatables/students', ['as' => 'admin.datatables.students', 'uses' => 'DatatablesController@getStudents']);
    Route::get('datatables/schools', ['as' => 'admin.datatables.schools', 'uses' => 'DatatablesController@getSchools']);
    Route::get('datatables/classes', ['as' => 'admin.datatables.classes', 'uses' => 'DatatablesController@getClasses']);

    Route::get('reset_posttest/{student_id}', array('as' => 'admin.students.reset_posttest', 'uses' => 'TestsController@reset_posttest'));
    Route::post('reset_serie', array('as' => 'admin.students.reset_serie', 'uses' => 'TestsController@reset_serie'));
    Route::post('reset_lesson', array('as' => 'admin.students.reset_lesson', 'uses' => 'TestsController@reset_lesson'));
    Route::get('show_lessons/{student_id}', array('as' => 'admin.students.show_lessons', 'uses' => 'TestsController@show_lessons'));

    // Help
    Route::get('help', array('as' => 'admin.help', 'uses' => 'HelpController@showIndex'));

    // Users
    Route::resource('users', 'UsersController', array('except' => array('show')));

    Route::get('schools/schools_list', array(
        'uses' => 'SchoolsController@schools_list',
        'as' => 'admin.schools.list'
    ));

    Route::get('schools/school_districts_list', array(
        'uses' => 'SchoolsController@school_districts_list',
        'as' => 'admin.school_districts_list.list'
    ));

    Route::post('lessons/{lesson_id}/make_optional', array(
        'uses' => 'LessonsController@make_optional',
        'as' => 'admin.lessons.make_optional'
    ));

    // Modules, lessons & activities
    Route::resource('modules', 'ModulesController', array('except' => array('show')));
    Route::resource('modules.series', 'SeriesController', array('only' => array('create')));
    Route::resource('series', 'SeriesController', array('only' => array('edit', 'update', 'destroy', 'store')));
    Route::resource('series.lessons', 'LessonsController', array('only' => array('create')));
    Route::resource('lessons', 'LessonsController', array('only' => array('edit', 'update', 'destroy', 'store', 'show')));

    Route::post('lessons/preview', array(
        'uses' => 'LessonsController@preview',
        'as' => 'admin.lessons.preview'
    ));
    Route::get('lessons/{lesson_id}/add_assessment', array(
        'uses' => 'LessonsController@add_assessment',
        'as' => 'admin.lessons.add_assessment'
    ));

    Route::post('lessons/upload', array(
        'uses' => 'LessonsController@upload',
        'as' => 'admin.lessons.upload'
    ));

    Route::get('modules/available', array(
        'uses' => 'ModulesController@available',
        'as' => 'admin.modules.available'
    ));

    Route::get('classes/available_from_school', array(
        'uses' => 'ClassesController@available_from_school',
        'as' => 'admin.classes.available_from_school'
    ));

    Route::get('activity-templates/render/{type}', array(
        'uses' => 'ActivityTemplatesController@render',
        'as' => 'admin.activity-templates.render'
    ));

    // Activity templates specific routes
    Route::resource('activity-templates/story-characters', 'ActivityTemplates\\StoryCharactersController', array(
        'except' => array('show')
    ));
    Route::post('activity-templates/story-characters/upload', array(
        'uses' => 'ActivityTemplates\\StoryCharactersController@uploadPicture',
        'as' => 'admin.activity-templates.story-characters.upload'
    ));
    Route::post('activity-templates/cartoon/upload', array(
        'uses' => 'ActivityTemplates\\CartoonController@uploadPicture',
        'as' => 'admin.activity-templates.cartoon.upload'
    ));

    // Teachers
    Route::resource('teachers', 'TeachersController');

    // Students & Classes
    Route::get('students/from-my-school', array(
        'uses' => 'StudentsController@fromMySchool',
        'as' => 'admin.students.from-my-school'
    ));

    Route::post('classes/{class_id}/save_students', array(
        'uses' => 'ClassesController@save_students',
        'as' => 'admin.classes.save_students'
    ));

    Route::post('classes/{class_id}/delete_student', array(
        'uses' => 'ClassesController@delete_student',
        'as' => 'admin.classes.delete_student'
    ));

    Route::resource('students', 'StudentsController');

    Route::resource('classes', 'ClassesController');

    Route::get('classes', array(
        'uses' => 'ClassesController@index',
        'as' => 'admin.classes.index'
    ));

    // tests : showing lessons
    Route::get('classes/{classId}/optional_lessons_setup', array(
        'uses' => 'ClassesController@optional_lessons_setup',
        'as' => 'admin.classes.optional_lessons_setup'
    ));

    Route::post('classes/save_optional_lessons_setup', array(
        'uses' => 'ClassesController@save_optional_lessons_setup',
        'as' => 'admin.classes.save_optional_lessons_setup'
    ));

    //showing class students.
    Route::get('classes/{classId}/students', array(
        'uses' => 'ClassesController@show_students',
        'as' => 'admin.classes.students'
    ));

    Route::get('classes/{classId}/students/{studentId}/edit_student', array(
        'uses' => 'ClassesController@edit_student',
        'as' => 'admin.classes.edit_student'
    ));

    Route::post('classes/{classId}/students/{studentId}/store_updated_student', array(
        'uses' => 'ClassesController@store_updated_student',
        'as' => 'admin.classes.store_updated_student'
    ));

    //import students to a class.
    Route::get('classes/{classId}/import_students', array(
        'uses' => 'ClassesController@import_students',
        'as' => 'admin.classes.import_students'
    ));

    Route::post('classes/upload', array(
        'uses' => 'ClassesController@upload',
        'as' => 'admin.classes.upload'
    ));

    Route::post('classes/parse_file', array(
        'uses' => 'ClassesController@parse_file',
        'as' => 'admin.classes.parse_file'
    ));

    Route::post('classes/store_imported_students', array(
        'uses' => 'ClassesController@store_imported_students',
        'as' => 'admin.classes.store_imported_students'
    ));

    //adding students to a class.
    Route::get('classes/{classId}/add_students', array(
        'uses' => 'ClassesController@add_students',
        'as' => 'admin.classes.add_students'
    ));

    //adding students to a class.
    Route::get('classes/{classId}/edit_students', array(
        'uses' => 'ClassesController@edit_students',
        'as' => 'admin.classes.edit_students'
    ));

    Route::post('classes/{class_id}/store_students', array(
        'uses' => 'ClassesController@store_students',
        'as' => 'admin.classes.store_students'
    ));

    Route::post('classes/{class_id}/store_updated_students', array(
        'uses' => 'ClassesController@store_updated_students',
        'as' => 'admin.classes.store_updated_students'
    ));

    Route::get('classes/{class_id}/preview', array(
        'uses' => 'ClassesController@preview',
        'as' => 'admin.classes.preview'
    ));
    Route::get('classes/{class_id}/preview/activity/{activity_id}', array(
        'uses' => 'ClassesController@preview',
        'as' => 'admin.classes.preview_activity'
    ));

    // tests : saving tests configuration
    Route::post('tests/save_config_tests', array(
        'uses' => 'TestsController@save_config_tests',
        'as' => 'admin.tests.save_config_tests'
    ));

    // tests : showing classes
    Route::resource('tests', 'TestsController@show_classes');

    // tests : showing modules
    Route::get('tests/classes/{classId}', array(
        'uses' => 'TestsController@show_modules',
        'as' => 'admin.tests.modules'
    ));

    // tests : showing series
    Route::get('tests/classes/{classId}/module/{moduleId}', array(
        'uses' => 'TestsController@show_series',
        'as' => 'admin.tests.series'
    ));

    // tests : showing lessons
    Route::get('tests/classes/{classId}/module/{moduleId}/serie/{serieId}', array(
        'uses' => 'TestsController@config_tests',
        'as' => 'admin.tests.config_tests'
    ));

    // tests : showing lessons
    Route::get('classes/{classId}/tests_setup', array(
        'uses' => 'TestsController@tests_setup',
        'as' => 'admin.tests.setup'
    ));

    // class report for pre/post tests
    Route::get('reports/classes/{classId}/pre_post_tests_scores', array(
        'uses' => 'ClassGradesController@pre_post_tests_scores',
        'as' => 'admin.reports.classes.pre_post_tests_scores'
    ));
    // final grades report
    Route::get('reports/classes/{classId}/final_grade', array(
        'uses' => 'ClassGradesController@final_grade',
        'as' => 'admin.reports.classes.final_grade'
    ));

    // class report by series
    Route::get('reports/classes/{classId}/scores_by_series', array(
        'uses' => 'ClassGradesController@scores_by_series',
        'as' => 'admin.reports.classes.scores_by_series'
    ));

    // class report by lesson landing
    Route::get('reports/classes/{classId}/series', array(
        'uses' => 'ClassGradesController@scores_by_lesson_landing',
        'as' => 'admin.reports.classes.scores_by_lesson_landing'
    ));

    // class report by lesson
    Route::get('reports/classes/{classId}/series/{seriesId}/scores_by_lesson', array(
        'uses' => 'ClassGradesController@scores_by_lesson',
        'as' => 'admin.reports.classes.scores_by_lesson'
    ));

    // student report by series landing
    Route::get('reports/classes/{classId}/students', array(
        'uses' => 'ClassGradesController@student_scores_by_series_landing',
        'as' => 'admin.reports.classes.student_scores_by_series_landing'
    ));

    // student report by lesson landing
    Route::get('reports/classes/{classId}/students_and_series', array(
        'uses' => 'ClassGradesController@student_scores_by_lesson_landing',
        'as' => 'admin.reports.classes.student_scores_by_lesson_landing'
    ));

    // student report by series
    Route::get('reports/classes/{classId}/students/{studentId}/scores_by_series', array(
        'uses' => 'ClassGradesController@student_scores_by_series',
        'as' => 'admin.reports.classes.student_scores_by_series'
    ));

    // student report by lessons
    Route::get('reports/classes/{classId}/students/{studentId}/series/{seriesId}/scores_by_lesson', array(
        'uses' => 'ClassGradesController@student_scores_by_lesson',
        'as' => 'admin.reports.classes.student_scores_by_lesson'
    ));

    // view blog
    Route::get('reports/view_blog', array('uses' => 'ClassGradesController@view_blog', 'as' => 'admin.reports.view_blog'));

    // print student blogs
    Route::get('reports/print_student_blogs', array('uses' => 'ClassGradesController@print_student_blogs', 'as' => 'admin.reports.print_student_blogs'));
    // print lesson blogs
    Route::get('reports/print_lesson_blogs', array('uses' => 'ClassGradesController@print_lesson_blogs', 'as' => 'admin.reports.print_lesson_blogs'));
    // print lesson answers
    Route::get('reports/print_lesson_answers', array('uses' => 'ClassGradesController@print_lesson_answers', 'as' => 'admin.reports.print_lesson_answers'));

    // view student lesson
    Route::get('reports/view_student_lesson', array('uses' => 'ClassGradesController@view_student_lesson', 'as' => 'admin.reports.view_student_lesson'));

    Route::resource('reports', 'ClassGradesController@reports');

    //showing teacher classes.
    Route::get('teachers/{classId}/classes', array(
        'uses' => 'ClassGradesController@show_teacher_classes',
        'as' => 'admin.classes.grades.teacher_classes'
    ));
    //showing class modules.
    Route::get('classes/{classId}/grades', array(
        'uses' => 'ClassGradesController@show_modules',
        'as' => 'admin.classes.grades.modules'
    ));
    // showing product series
    Route::get('classes/{classId}/grades/module/{moduleId}', array(
        'uses' => 'ClassGradesController@show_series',
        'as' => 'admin.classes.grades.series'
    ));
    // showing serie lessons
    Route::get('classes/{classId}/grades/module/{moduleId}/serie/{serieId}', array(
        'uses' => 'ClassGradesController@show_lessons',
        'as' => 'admin.classes.grades.lessons'
    ));
    // showing lesson grade
    Route::get('classes/{classId}/grades/module/{moduleId}/serie/{serieId}/lesson/{lessonId}', array(
        'uses' => 'ClassGradesController@show_lesson_grades',
        'as' => 'admin.classes.grades.lesson_grades'
    ));
    // show detailed lesson grades for a student
    Route::get('classes/{classId}/grades/module/{moduleId}/serie/{serieId}/lesson/{lessonId}/student/{studentId}', array(
        'uses' => 'ClassGradesController@show_lesson_student_grades',
        'as' => 'admin.classes.show_lesson_student_grades'
    ));

    Route::get('classes/{classId}/grades/{studentId}-{lessonId}', array(
        'uses' => 'ClassGradesController@showByLesson',
        'as' => 'admin.classes.lesson_grades'
    ));
    /*
      Route::get('classes/{classId}/grades/{studentId}', array(
      'uses' => 'ClassGradesController@showByStudent',
      'as' => 'admin.classes.student_grades'
      ));
     */

    // Schools & admins
    Route::resource('schools', 'SchoolsController');
    Route::get('schools/{school_id}/destroy', array(
        'uses' => 'SchoolsController@destroy',
        'as' => 'admin.schools.destroy'
    ));

    Route::get('school_districts/create', array(
        'uses' => 'SchoolsController@create_school_districts',
        'as' => 'admin.school_districts.create'
    ));
    Route::post('school_districts/store', array(
        'uses' => 'SchoolsController@store_school_districts',
        'as' => 'admin.school_districts.store'
    ));

    Route::resource('school-admins', 'SchoolAdminsController', array(
        'only' => array('index')
    ));
    Route::get('school-admins/free', array(
        'uses' => 'SchoolAdminsController@freeAdmins',
        'as' => 'admin.school-admins.free'
    ));

    Route::get('/school_preview', array(
        'uses' => 'SchoolsController@preview',
        'as' => 'admin.schools.preview'
    ));

    Route::get('school_preview/activity/{activity_id}', array(
        'uses' => 'SchoolsController@preview',
        'as' => 'admin.schools.preview_activity'
    ));

});
