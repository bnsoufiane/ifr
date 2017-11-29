<?php

use \Illuminate\Support\Collection;

class Student extends User
{

    public function schoolClasses()
    {
        return $this->belongsToMany('SchoolClass');
    }

    public function answers()
    {
        return $this->hasMany('StudentAnswer', 'student_id');
    }

    public function gradeByLesson($lesson)
    {
        return $this->answersByLesson($lesson)
            ->reduce(function ($grades, $answer) {
                $grade = $answer->getGrade();

                if (!is_array($grade)) {
                    $grade = array($grade);
                }

                return array_merge($grades, $grade);
            }, array());
    }

    public function ScoreByLesson($lesson)
    {
        $assessment = \Activity::where('lesson_id', $lesson->id)->where('template_type', 'ActivityTemplates\Assessment')->first();

        $assessment_answers = $this->answers()->where('activity_id', $assessment->id)->first();
        if (!$assessment_answers) {
            return null;
        }
        try {
            $grades = $assessment_answers->getGrade();
        } catch (\Exception $e) {
            return null;
        }

        $graded_count = 0;
        $correct_count = 0;
        foreach ($grades as $grade) {
            if ($grade != \StudentAnswer::NOT_GRADED)
                $graded_count++;
            if ($grade == \StudentAnswer::CORRECT)
                $correct_count++;
        }

        if ($graded_count == 0) {
            return 0;
        }

        return ($correct_count / $graded_count) * 100;
    }

    public static function StudentScoreByLesson($student, $assessment)
    {
        $assessment_answers = \Student::find($student)->answers()->where('activity_id', $assessment)->first();

        /*
        $sql = "SELECT * FROM `student_answers` WHERE student_id = $student and activity_id = $assessment";
        $assessment_answers = \DB::select(\DB::raw($sql));
        //*/


        if (!$assessment_answers) {
            return null;
        }
        try {

            $grades = $assessment_answers->getGrade();

        } catch (\Exception $e) {
            return null;
        }


        $graded_count = 0;
        $correct_count = 0;
        foreach ($grades as $grade) {
            if ($grade != \StudentAnswer::NOT_GRADED)
                $graded_count++;
            if ($grade == \StudentAnswer::CORRECT)
                $correct_count++;
        }

        if ($graded_count == 0) {
            return 0;
        }

        return array(($correct_count / $graded_count) * 100 , $assessment_answers->created_at->format('m/d/Y  g:i A') );
    }


    public function ScoreByLesson_detailed($lesson)
    {
        $assessment = \Activity::where('lesson_id', $lesson->id)->where('template_type', 'ActivityTemplates\Assessment')->first();

        $assessment_answers = $this->answers()->where('activity_id', $assessment->id)->first();
        if (!$assessment_answers) {
            return null;
        }
        try {
            $grades = $assessment_answers->getGrade();
        } catch (\Exception $e) {
            return null;
        }

        $graded_count = 0;
        $correct_count = 0;
        foreach ($grades as $grade) {
            if ($grade != \StudentAnswer::NOT_GRADED)
                $graded_count++;
            if ($grade == \StudentAnswer::CORRECT)
                $correct_count++;
        }

        if ($graded_count == 0) {
            return 0;
        }

        $result["correct_answers"] = $correct_count;
        $result["total_questions"] = $graded_count;
        $result["score"] = ($correct_count / $graded_count) * 100;

        return $result;
    }

    public function answersByLesson($lesson)
    {
        return $this->answers()
            ->whereHas('activity', function ($q) use ($lesson) {
                $q->whereHas('lesson', function ($q) use ($lesson) {
                    $q->where('id', $lesson->id);
                });
            })
            ->get();
    }

    public function ScoreByTest($data, $test, $test_type)
    {
        if (!$data) {
            $test_student_posttest = \DB::table('test_students')->where('student_id', '=', $this->id)
                ->where('learning_level', '=', $test_type)
                ->where('status', '=', \Tests::CLOSED)->first();

            if ($test_student_posttest) {
                return $test_student_posttest->score;
            } else {
                return 0;
            }

        }

        $answers_ids = array();

        foreach ($data as $key => $value) {
            if ($key != "test") {
                $answers_ids[] = $value;
            }
        }

        $answers = \ActivityTemplates\AssessmentOption::select('graded')->whereIn('id', $answers_ids)->get();

        $grades = array();
        foreach ($answers as $value) {
            $grades[] = $value->graded;
        }

        return isset(array_count_values($grades)[\StudentAnswer::CORRECT]) ? ((array_count_values($grades)[\StudentAnswer::CORRECT] / count($grades)) * 100) : 0;

    }

    public function answeredLessons()
    {
        return $this->answers()
            ->with('activity', 'activity.lesson')
            ->get()
            ->map(function ($a) {
                return $a->activity->lesson;
            })
            ->reduce(function ($lessons, $lesson) {
                if (!isset($lessons[$lesson->id])) {
                    $lessons[$lesson->id] = $lesson;
                }
                return $lessons;
            }, new Collection());
    }

}
