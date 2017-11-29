<?php

namespace Admin;

use Input;
use \Curotec\Presenters\ClassStudentPresenter;
use \Curotec\Presenters\ClassAnswerPresenter;

class ClassGradesController extends BaseController
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

    // directly shoing all lessons */

    public function reports()
    {
        $currentUser = \Sentry::getUser();
        $isTeacher = $currentUser->isTeacher();

        if ($isTeacher) {
            $this->layout->content = \View::make('admin.class_grades.show_classes')->with('teacher_id', $currentUser->id);
        } else {
            $this->layout->content = \View::make('admin.class_grades.show_teachers');
        }
    }

    public function pre_post_tests_scores($class)
    {
        $class = \SchoolClass::find($class);
        $students = $class->students()->distinct()->orderBy('last_name', 'ASC')->get();
        $series = $class->series()->distinct()->get();

        $this->layout->content = \View::make('admin.class_grades.pre_post_tests_scores')
            ->with('students', $students)
            ->with('series', $series)
            ->with('class', $class);
    }

    public function final_grade($class)
    {
        $class = \SchoolClass::find($class);
        $students = $class->students()->distinct()->orderBy('last_name', 'ASC')->get();
        $series = $class->series()->distinct()->get();

        $this->layout->content = \View::make('admin.class_grades.final_grade')
            ->with('students', $students)
            ->with('series', $series)
            ->with('class', $class);
    }

    public function scores_by_series($class)
    {
        $class = \SchoolClass::find($class);
        if (!is_null($class)) {
            $students = $class->students()->distinct()->orderBy('last_name', 'ASC')->get();
            $series = $class->series()->distinct()->with(array('lessons.activities' => function ($query) {
                $query->where('template_type', '=', 'ActivityTemplates\Assessment');
            }))->get();


            $sql = <<<SQL
SELECT
  id
FROM
  `lessons`
JOIN
  school_class_optional_lessons
ON
(
    school_class_optional_lessons.optional_lesson_id = lessons.id AND school_class_id = $class->id
)
SQL;
            $optional_lessons_obj = \DB::select($sql);
            $optional_lessons_ids = [];
            foreach ($optional_lessons_obj as $optional_lesson) {
                $optional_lessons_ids[] = $optional_lesson->id;
            }

            $student_ids = [];
            foreach ($students as $student) {
                $student_ids[] = $student->id;
            }

            $assessment_ids = [];
            foreach ($series as $serie) {
                foreach ($serie->lessons as $lesson) {
                    $assessment_ids[] = $lesson->activities[0]->id;
                }
            }

            $assessments_answers_flags = \DB::table('student_answers')
                ->whereIn('student_id', $student_ids)
                ->whereIn('activity_id', $assessment_ids)
                ->lists('activity_id', 'student_id');

            $this->layout->content = \View::make('admin.class_grades.scores_by_series')
                ->with('students', $students)
                ->with('series', $series)
                ->with('class', $class)
                ->with('optional_lessons_ids', $optional_lessons_ids)
                ->with('assessments_answers_flags', $assessments_answers_flags);
        }
    }

    public function scores_by_lesson_landing($class)
    {
        $class = \SchoolClass::find($class);
        $students = $class->students()->distinct()->orderBy('last_name', 'ASC')->get();
        $series = $class->series()->distinct()->get();

        $this->layout->content = \View::make('admin.class_grades.scores_by_lesson_landing')
            ->with('students', $students)
            ->with('series', $series)
            ->with('class', $class);
    }

    public function student_scores_by_series_landing($class)
    {
        $class = \SchoolClass::find($class);
        $students = $class->students()->distinct()->orderBy('last_name', 'ASC')->get();
        $series = $class->series()->distinct()->get();

        $this->layout->content = \View::make('admin.class_grades.student_scores_by_series_landing')
            ->with('students', $students)
            ->with('series', $series)
            ->with('class', $class);
    }

    public function student_scores_by_lesson_landing($class)
    {
        $class = \SchoolClass::find($class);
        $students = $class->students()->distinct()->orderBy('last_name', 'ASC')->get();
        $series = $class->series()->distinct()->get();

        $this->layout->content = \View::make('admin.class_grades.student_scores_by_lesson_landing')
            ->with('students', $students)
            ->with('series', $series)
            ->with('class', $class);
    }

    public function scores_by_lesson($class, $serie)
    {
        $class = \SchoolClass::find($class);

        $sql = <<<SQL
SELECT
        *
        FROM
          users
        JOIN
          school_class_student
        ON
          users . id = school_class_student . student_id
        WHERE
          school_class_student . school_class_id = $class->id
        ORDER BY
          users . last_name ASC
SQL;
        $students = \DB::select(\DB::raw($sql));

        $series = $class->series()->distinct()->find($serie);

        $sql = <<<SQL
        SELECT
  lessons.*,
  activities.id AS assessment_id,
  IF(
    optional_lesson_id IS NULL,
    1,
    0
  ) AS required
FROM
  `lessons`
JOIN
  activities
ON
  activities.lesson_id = lessons.id
LEFT JOIN
  school_class_optional_lessons
ON
  (
    school_class_optional_lessons.optional_lesson_id = lessons.id AND school_class_id = $class->id
  )
WHERE
  series_id = $serie AND template_type = "ActivityTemplates\\\Assessment"
SQL;
        $lessons = \DB::select(\DB::raw($sql));

        $student_ids = [];
        foreach ($students as $student) {
            $student_ids[] = $student->id;
        }

        $assessment_ids = [];
        foreach ($lessons as $lesson) {
            $assessment_ids[] = $lesson->assessment_id;
        }

        $assessments_answers_flags = \DB::table('student_answers')
            ->whereIn('student_id', $student_ids)
            ->whereIn('activity_id', $assessment_ids)
            ->lists('activity_id', 'student_id');

        $this->layout->content = \View::make('admin.class_grades.class_report_by_lesson')
            ->with('students', $students)
            ->with('series', $series)
            ->with('lessons', $lessons)
            ->with('class', $class)
            ->with('assessments_answers_flags', $assessments_answers_flags);
    }

    public function student_scores_by_series($class, $student)
    {
        $class = \SchoolClass::find($class);
        $student = $class->students()->distinct()->orderBy('last_name', 'ASC')->find($student);
        $series = $class->series()->distinct()->get();

        $this->layout->content = \View::make('admin.class_grades.student_scores_by_series')
            ->with('student', $student)
            ->with('series', $series)
            ->with('class', $class);
    }

    public function student_scores_by_lesson($class, $student, $serie)
    {
        $class = \SchoolClass::find($class);
        $student = $class->students()->distinct()->orderBy('last_name', 'ASC')->find($student);
        $students = $class->students()->distinct()->orderBy('last_name', 'ASC')->get();
        $serie = $class->series()->distinct()->find($serie);
        $lessons = $serie->lessons;
        $series = $class->series()->distinct()->get();

        $this->layout->content = \View::make('admin.class_grades.student_scores_by_lessons')
            ->with('student', $student)
            ->with('students', $students)
            ->with('serie', $serie)
            ->with('series', $series)
            ->with('lessons', $lessons)
            ->with('class', $class);
    }

    public function view_blog()
    {

        $input = Input::get();
        $lesson = \Lesson::find($input['lesson_id']);
        $student = \Student::find($input['student_id']);
        $class = \SchoolClass::find($input['class_id']);
        $activities = $lesson->activities()->orderBy("order")->get();

        $blogs_ids = [];
        foreach ($activities as $activity_item) {
            if ($activity_item->template_type == "ActivityTemplates\\Blog") {
                $blogs_ids[] = $activity_item->id;
            }
            /*
             foreach ($activity_item->getSubAcitivities() as $sub_activity) {
                if($sub_activity->template_type=="ActivityTemplates\\Blog"){
                    $blogs_ids[]=$sub_activity->id;
                }
            }*/
        }

        if ($input === null) {
            return \Response::make('You haven\'t provided activity data.', 400);
        }

        if (count($blogs_ids) == 0) {
            return 'No blog activity to show';
        }

        $activity = \Activity::find($blogs_ids[0]);

        $series = \Series::find($input['serie_id']);
        $templateType = $activity->template_type;
        $meta = $templateType::getMetaData();

        $answers = $student->answers()->byActivity($blogs_ids[0])->get();
        $answer = null;

        foreach ($answers as $item) {
            $answer = $item;
        }
        $blog_views = [];

        $blog_views[] = (string)\View::make('activities/blog_view')
            ->with('current_activity', $activity)
            ->with('data', $activity->template)
            ->with('hasAnswer', $answer != null)
            ->with('answer', $answer)
            ->with('answerData', $answer ? $answer->answerType : null)
            ->with('test', null)
            ->with('preview', true);

        if (isset($input['print'])) {
            $this->layout = \View::make('layouts.wkhtml2pdf_blog_base');
        } else {
            $this->layout = \View::make('layouts.view_blog');
        }

        $this->layout->with('activityId', $activity->id)
            ->with('template_type', $activity->template_type)
            ->with('activiy_bg_img', $activity->background_image)
            ->with('class_name', $class->name)
            ->with('series_title', $series->title)
            ->with('lesson_title', $lesson->title)
            ->with('print', isset($input['print']))
            ->with('blog_views', $blog_views)
            ->with('student_name', $student->last_name . ", " . $student->first_name);

        $h = $this->layout->render();

        if (isset($input['print'])) {
            $student_name = $student->last_name . ", " . $student->first_name;
            $file_name = "Blog Report - $student_name - $class->name" . " - $series->title" . " - $lesson->title";
            $file_name = str_replace(",", "", $file_name);
            return \PDF::html('layouts.wkhtml2pdf', array('content' => $h), $file_name);
        }


    }

    public function print_student_blogs()
    {
        $input = Input::get();
        $series = \Series::find($input['serie_id']);
        $lessons = $series->lessons()->get();
        $class = \SchoolClass::find($input['class_id']);
        $student = \Student::find($input['student_id']);

        $blogs_ids = [];
        foreach ($lessons as $lesson) {
            $activities = $lesson->activities()->orderBy("order")->get();
            foreach ($activities as $activity_item) {
                if ($activity_item->template_type == "ActivityTemplates\\Blog") {
                    $blogs_ids[] = $activity_item->id;
                }
                /*
                 foreach ($activity_item->getSubAcitivities() as $sub_activity) {
                    if($sub_activity->template_type=="ActivityTemplates\\Blog"){
                        $blogs_ids[]=$sub_activity->id;
                    }
                }*/
            }
        }

        if ($input === null) {
            return \Response::make('You haven\'t provided activity data.', 400);
        }

        if (count($blogs_ids) == 0) {
            return 'No blog activity to show';
        }

        $blog_views = [];

        for ($i = 0; $i < count($blogs_ids); $i++) {
            $activity = \Activity::find($blogs_ids[$i]);

            $series = \Series::find($input['serie_id']);
            $templateType = $activity->template_type;
            $meta = $templateType::getMetaData();

            $answers = $student->answers()->byActivity($blogs_ids[$i])->get();
            $answer = null;

            foreach ($answers as $item) {
                $answer = $item;
            }

            $blog_views[] = (string)(\View::make('activities/blog_view')
                ->with('current_activity', $activity)
                ->with('data', $activity->template)
                ->with('hasAnswer', $answer != null)
                ->with('answer', $answer)
                ->with('answerData', $answer ? $answer->answerType : null)
                ->with('test', null)
                ->with('preview', true)
                ->with('lesson_title', $activity->lesson->title)
                ->with('series_title', $series->title));
        }

        if (isset($input['print'])) {
            $this->layout = \View::make('layouts.wkhtml2pdf_blog_base');
        } else {
            $this->layout = \View::make('layouts.view_blog');
        }

        $this->layout->with('activityId', $activity->id)
            ->with('template_type', $activity->template_type)
            ->with('activiy_bg_img', $activity->background_image)
            ->with('class_name', $class->name)
            ->with('blog_views', $blog_views)
            ->with('student_name', $student->last_name . ", " . $student->first_name)
            //->with('print', false);
            ->with('print', isset($input['print']));

        if (isset($input['print'])) {
            $h = $this->layout->render();

            $student_name = $student->last_name . ", " . $student->first_name;
            $file_name = "Blog Report - $student_name - $class->name";
            $file_name = str_replace(",", "", $file_name);
            return \PDF::html('layouts.wkhtml2pdf', array('content' => $h), $file_name);
        }

    }

    public function print_lesson_blogs()
    {
        $input = Input::get();
        $lesson = \Lesson::find($input['lesson_id']);
        $class = \SchoolClass::find($input['class_id']);
        $students = $class->students()->distinct()->orderBy('last_name', 'ASC')->get();

        $blogs_ids = [];
        $activities = $lesson->activities()->orderBy("order")->get();
        foreach ($activities as $activity_item) {
            if ($activity_item->template_type == "ActivityTemplates\\Blog") {
                $blogs_ids[] = $activity_item->id;
            }
            /*
             foreach ($activity_item->getSubAcitivities() as $sub_activity) {
                if($sub_activity->template_type=="ActivityTemplates\\Blog"){
                    $blogs_ids[]=$sub_activity->id;
                }
            }*/
        }

        if ($input === null) {
            return \Response::make('You haven\'t provided activity data.', 400);
        }

        if (count($blogs_ids) == 0) {
            return 'No blog activity to show';
        }

        $blog_views = [];

        foreach ($students as $student) {
            $activity = \Activity::find($blogs_ids[0]);

            $series = \Series::find($input['serie_id']);
            $templateType = $activity->template_type;
            $meta = $templateType::getMetaData();

            $answers = $student->answers()->byActivity($blogs_ids[0])->get();
            $answer = null;

            foreach ($answers as $item) {
                $answer = $item;
            }

            $blog_views[] = (string)(\View::make('activities/blog_view')
                ->with('current_activity', $activity)
                ->with('data', $activity->template)
                ->with('hasAnswer', $answer != null)
                ->with('answer', $answer)
                ->with('answerData', $answer ? $answer->answerType : null)
                ->with('test', null)
                ->with('preview', true)
                ->with('lesson_title', $activity->lesson->title)
                ->with('print_lesson_blogs', true)
                ->with('student_name', $student->last_name . ", " . $student->first_name)
                ->with('series_title', $series->title));
        }

        if (isset($input['print'])) {
            $this->layout = \View::make('layouts.wkhtml2pdf_blog_base');
        } else {
            $this->layout = \View::make('layouts.view_blog');
        }

        $this->layout->with('activityId', $activity->id)
            ->with('template_type', $activity->template_type)
            ->with('activiy_bg_img', $activity->background_image)
            ->with('blog_views', $blog_views)
            ->with('lesson_title', $lesson->title)
            ->with('class_name', $class->name)
            ->with('series_title', $series->title)
            ->with('print_lesson_blogs', true)
            //->with('print', false);
            ->with('print', isset($input['print']));

        if (isset($input['print'])) {
            $h = $this->layout->render();
            $student_name = $student->last_name . ", " . $student->first_name;
            $file_name = "Blog Report - $class->name - $series->title - $lesson->title";
            $file_name = str_replace(",", "", $file_name);
            return \PDF::html('layouts.wkhtml2pdf', array('content' => $h), $file_name);
        }

    }

    public function print_lesson_answers()
    {
        $input = Input::get();
        $lesson = \Lesson::where('id', '=', $input['lesson_id'])->with('series', 'activities')->first();
        $class = \SchoolClass::where('id', '=', $input['class_id'])->with('students.answers.answerType')->first();
        $series = $lesson->series;
        $students = $class->students()->distinct()->orderBy('last_name', 'ASC')->get();
        $activities = $lesson->activities()->orderBy("order")->get();

        foreach ($students as $student) {
            $student_name = null;
            $answers = $student->answers;
            foreach ($activities as $activity_item) {
                $templateType = $activity_item->template_type;
                $meta = $templateType::getMetaData();

                //$answers = $student->answers()->byActivity($activity_item->id)->get();
                $answer = null;
                //$answer = null;
                foreach ($answers as $ans) {
                    if ($ans->activity_id == $activity_item->id) {
                        $answer = $ans;
                        break;
                    }
                }

                $templateType = $activity_item->template_type;
                $meta = $templateType::getMetaData();
                $activity_title = null;

                if (isset($meta['answer_template']) && $meta['answer_template'] != 'admin/answer_templates/blog') {
                    $activity_title = $activity_item->title;
                    $content = \View::make($meta['answer_template'])
                        ->with('current_activity', $activity_item)
                        ->with('data', $activity_item->template)
                        ->with('hasAnswer', $answer != null)
                        ->with('answer', $answer)
                        ->with('answerData', $answer ? $answer->answerType()->first() : null)
                        ->with('test', null)
                        ->with('activity_title', $activity_title)
                        ->with('student_name', $student_name ? null : ($student_name = $student->last_name . ", " . $student->first_name))
                        ->with('preview', true);
                    $student_name = $student_name = $student->last_name . ", " . $student->first_name;

                    $answers_views[] = $content->render();
                }

                foreach ($activity_item->getSubAcitivities() as $sub_activity) {
                    $templateType = $sub_activity->template_type;
                    $meta = $templateType::getMetaData();

                    $answers = $student->answers()->byActivity($sub_activity->id)->get();
                    $answer = null;

                    foreach ($answers as $item) {
                        $answer = $item;
                    }

                    if (isset($meta['answer_template']) && $meta['answer_template'] != 'admin/answer_templates/blog') {
                        $is_printable = true;
                    } else {
                        continue;
                    }

                    $content = \View::make($meta['answer_template'])
                        ->with('current_activity', $sub_activity)
                        ->with('data', $sub_activity->template)
                        ->with('hasAnswer', $answer != null)
                        ->with('answer', $answer)
                        ->with('answerData', $answer ? $answer->answerType()->first() : null)
                        ->with('test', null)
                        ->with('activity_title', $activity_title ? null : $activity_item->title)
                        ->with('student_name', $student_name ? null : ($student_name = $student->last_name . ", " . $student->first_name))
                        ->with('preview', true);
                    $student_name = $student_name = $student->last_name . ", " . $student->first_name;
                    $answers_views[] = $content->render();
                }
            }
        }

        $this->layout = \View::make('layouts.wkhtml2pdf_base');

        $activity = $activities->first();

        $page_title = "$class->name - $series->title - $lesson->title";

        $this->layout->with('activityId', $activity)
            ->with('template_type', $activity->template_type)
            ->with('activiy_bg_img', $activity->background_image)
            ->with('class_name', $class->name)
            ->with('series_title', $series->title)
            ->with('lesson_title', $lesson->title)
            ->with('page_title', $page_title)
            ->with('print', isset($input['print']))
            ->with('answers_views', $answers_views);

        if (isset($input['print'])) {
            $h = $this->layout->render();
            $student_name = $student->last_name . ", " . $student->first_name;
            $file_name = $page_title = str_replace(",", "", $page_title);
            return \PDF::html('layouts.wkhtml2pdf', array('content' => $h), $file_name);
        }

    }

    public function view_student_lesson()
    {
        $input = Input::get();
        $lesson = \Lesson::find($input['lesson_id']);
        $class = \SchoolClass::find($input['class_id']);
        $series = $lesson->series;
        $student = \Student::find($input['student_id']);
        $activities = $lesson->activities()->orderBy("order")->get();

        foreach ($activities as $activity_item) {
            $templateType = $activity_item->template_type;
            $meta = $templateType::getMetaData();

            $answers = $student->answers()->byActivity($activity_item->id)->get();
            $answer = null;

            foreach ($answers as $item) {
                $answer = $item;
            }

            $templateType = $activity_item->template_type;
            $meta = $templateType::getMetaData();
            $activity_title = null;

            if (isset($meta['answer_template'])) {
                $activity_title = $activity_item->title;
                $content = \View::make($meta['answer_template'])
                    ->with('current_activity', $activity_item)
                    ->with('data', $activity_item->template)
                    ->with('hasAnswer', $answer != null)
                    ->with('answer', $answer)
                    ->with('answerData', $answer ? $answer->answerType : null)
                    ->with('test', null)
                    ->with('activity_title', $activity_title)
                    ->with('preview', true);

                $answers_views[] = $content->render();
            }

            foreach ($activity_item->getSubAcitivities() as $sub_activity) {
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
                    ->with('activity_title', $activity_title ? null : $activity_item->title)
                    ->with('preview', true);
                $answers_views[] = $content->render();
            }
        }
        if (isset($input['print'])) {
            $this->layout = \View::make('layouts.wkhtml2pdf_base');
        } else {
            $this->layout = \View::make('layouts.print_activity');
        }

        $activity = $activities->first();

        $page_title = "$student->last_name, $student->first_name - $class->name - $series->title - $lesson->title";

        $this->layout->with('activityId', $activity)
            ->with('template_type', $activity->template_type)
            ->with('activiy_bg_img', $activity->background_image)
            ->with('class_name', $class->name)
            ->with('series_title', $series->title)
            ->with('lesson_title', $lesson->title)
            ->with('page_title', $page_title)
            //->with('print', true)
            ->with('print', isset($input['print']))
            ->with('answers_views', $answers_views);

        $h = $this->layout->render();

        if (isset($input['print'])) {
            //$student_name = $student->last_name . ", " . $student->first_name;
            $page_title = str_replace(",", "", $page_title);
            return \PDF::html('layouts.wkhtml2pdf', array('content' => $h), $page_title);
        }

    }

    public function show_teacher_classes($teacher)
    {
        $teacher = \User::find($teacher);

        $school = $teacher->school()->first();
        $classes = $school->classes()->where('created_by', '=', $teacher['id'])->get();

        $this->layout->content = \View::make('admin.class_grades.show_classes')
            ->with('classes', $classes)
            ->with('teacher_id', $teacher->id);
    }

    public function show_modules($class_id)
    {
        $class = \SchoolClass::find($class_id);
        $school = $class->school()->first();
        $modules = $school->modules()->get();
        $cs = (Input::get('cs')) ? true : false;
        $teacher_id = (Input::get('teacher')) ? Input::get('teacher') : null;

        $this->layout->content = \View::make('admin.class_grades.show_modules')
            ->with('class', $class)
            ->with('modules', $modules)
            ->with('cs', $cs)
            ->with('teacher_id', $teacher_id);
    }

    public function show_series($class, $module)
    {
        $class = \SchoolClass::find($class);
        $module = \Module::find($module);
        $series = $module->series()->get();
        $cs = (Input::get('cs')) ? true : false;
        $teacher_id = (Input::get('teacher')) ? Input::get('teacher') : null;

        $this->layout->content = \View::make('admin.class_grades.show_series')
            ->with('class', $class)
            ->with('module', $module)
            ->with('series', $series)
            ->with('cs', $cs)
            ->with('teacher_id', $teacher_id);
    }

    public function show_lessons($class, $module, $serie)
    {
        $class = \SchoolClass::find($class);
        $module = \Module::find($module);
        $serie = \Series::find($serie);
        $lessons = $serie->lessons()->get();
        $cs = (Input::get('cs')) ? true : false;
        $teacher_id = (Input::get('teacher')) ? Input::get('teacher') : null;

        $this->layout->content = \View::make('admin.class_grades.show_lessons')
            ->with('class', $class)
            ->with('module', $module)
            ->with('serie', $serie)
            ->with('lessons', $lessons)
            ->with('cs', $cs)
            ->with('teacher_id', $teacher_id);
    }

    public function show_lesson_grades($class, $module, $serie, $lesson)
    {
        $class = \SchoolClass::find($class);
        $module = \Module::find($module);
        $serie = \Series::find($serie);
        $lesson = \Lesson::find($lesson);
        $cs = (Input::get('cs')) ? true : false;
        $teacher_id = (Input::get('teacher')) ? Input::get('teacher') : null;

        $students = $class->students()->orderBy('last_name')->get();

        $this->layout->content = \View::make('admin.class_grades.show_lesson_grades')
            ->with('class', $class)
            ->with('module', $module)
            ->with('serie', $serie)
            ->with('lesson', $lesson)
            ->with('students', ClassStudentPresenter::wrap($students))
            ->with('cs', $cs)
            ->with('teacher_id', $teacher_id);
    }

    public function show_lesson_student_grades($class, $module, $serie, $lesson, $student, $teacher_id = 0)
    {
        $class = \SchoolClass::find($class);
        $module = \Module::find($module);
        $serie = \Series::find($serie);
        $lesson = \Lesson::find($lesson);
        $student = \Student::find($student);
        $cs = (Input::get('cs')) ? true : false;
        $teacher_id = (Input::get('teacher')) ? Input::get('teacher') : null;

        $answers = $student->answers;

        $this->layout->content = $this->showDetailedAnswers($class, $module, $serie, $lesson, $student, $answers, $cs, $teacher_id);

        /*
          $this->layout->content = \View::make('admin.class_grades.show_lesson_grades')
          ->with('class', $class)
          ->with('module', $module)
          ->with('serie', $serie)
          ->with('lesson', $lesson)
          ->with('student', $student);
         */
    }

    private function showDetailedAnswers($class, $module, $serie, $lesson, $student, $answers, $cs, $teacher_id)
    {
        $answers = $this->filter_answers($answers);

        return \View::make('admin.class_grades.show_detailed')
            ->with('class', $class)
            ->with('module', $module)
            ->with('serie', $serie)
            ->with('lesson', $lesson)
            ->with('student', new ClassStudentPresenter($student))
            ->with('answers', ClassAnswerPresenter::wrap($answers))
            ->with('cs', $cs)
            ->with('teacher_id', $teacher_id);
    }

    private function showDetailed($class, $student, $answers)
    {
        $answers = $this->filter_answers($answers);

        return \View::make('admin.class_grades.show_detailed')
            ->with('class', $class)
            ->with('student', new ClassStudentPresenter($student))
            ->with('answers', ClassAnswerPresenter::wrap($answers));
    }

    public function showByLesson($classId, $studentId, $lessonId)
    {
        $class = \SchoolClass::find($classId);
        $student = \Student::find($studentId);
        $lesson = \Lesson::find($lessonId);

        $answers = $student->answersByLesson($lesson);

        $this->layout->content = $this->showDetailed($class, $student, $answers)
            ->with('lesson', $lesson);
    }

    public function showByStudent($classId, $studentId)
    {
        $class = \SchoolClass::find($classId);
        $student = \Student::find($studentId);

        $answers = $student->answers;

        $this->layout->content = $this->showDetailed($class, $student, $answers);
    }

    private function filter_answers($answers)
    {

        $answers_new = $answers;
        $i = 0;
        foreach ($answers as $answer) {
            if ($answer->answer_type_id == 0) {
                unset($answers[$i]);
            }
            $i++;
        }

        return $answers;
    }
}
