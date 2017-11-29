<div class="page-head">
    <span>Class: "{{$class->name}}"</span><br/>
    <span>Class Report by Series</span>
    <div class='help-block'>
        This report by Class shows completed Required lessons by Student Name, by Series.
    </div>
</div>

<div class="cl-mcont">
    <div class="block-flat no-padding">
        <div class="content">
            <table class="class-grades-table no-border blue">
                <thead class="no-border">
                <tr>
                    <th style="width: 31%;"></th>
                    @foreach ($series as $serie)
                        <th colspan="2" style="text-align: center">
                            <a href="{{ URL::route('admin.reports.classes.scores_by_lesson', array($class->id, $serie->id) ) }}">
                                {{$serie->title}}
                            </a>
                        </th>
                    @endforeach
                    <th style="text-align: center">All series</th>
                </tr>
                <tr>
                    <th style="width: 31%; text-align: center;">Student</th>
                    @foreach ($series as $serie)
                        <th style="text-align: center;">Completed</th>
                        <th style="text-align: center;">Avg Score</th>
                    @endforeach
                    <th style="text-align: center;">Avg Score</th>
                </tr>
                </thead>
                <tbody class="no-border-x">
                <?php
                $series_averages = array();
                $all_students_score = array();
                ?>
                @foreach ($students as $student)
                    <tr>
                        <td>
                            <a href="{{ URL::route('admin.reports.classes.student_scores_by_series', array($class->id, $student->id)) }}">
                                {{$student->last_name}}, {{$student->first_name}}
                            </a>
                        </td>
                        <?php $i = 0;
                        $student_scores = array();?>
                        @foreach ($series as $serie)
                            <?php
                            $all_lessons = $serie->lessons;
                            if (count($optional_lessons_ids)) {
                                $required_lessons = $serie->lessons()->whereNotIn('id', $optional_lessons_ids)->get();
                            } else {
                                $required_lessons = $serie->lessons;
                            }
                            $required_lessons_count = $required_lessons->count();
                            $assessment_ids = array();
                            $lessons_scores = array();
                            ?>

                            @foreach ($required_lessons as $lesson)
                                <?php
                                $assessment_ids[] = $lesson->activities;
                                ?>
                            @endforeach
                            @foreach ($all_lessons as $lesson)
                                <?php
                                if (isset($assessments_answers_flags[$student->id])) {
                                    $score = $student->ScoreByLesson($lesson);
                                } else {
                                    $score = null;
                                }
                                if ($score) {
                                    $lessons_scores[] = $score;
                                    $student_scores[] = $score;
                                }
                                ?>
                            @endforeach

                            <?php
                            if (count($assessment_ids)) {
                                $finished_lessons_count = $student->answers()->whereIn('activity_id', $assessment_ids)->count();
                            } else {
                                $finished_lessons_count = $student->answers()->count();
                            }
                            $lessons_count = count($all_lessons);
                            $avg_score = count($lessons_scores) ? (array_sum($lessons_scores) / count($lessons_scores)) : 0;
                            /*if($avg_score){
                                $student_scores[]=$avg_score;
                            }*/
                            if (count($lessons_scores)) {
                                $succeded = $avg_score >= $class->minimum_score;
                                $series_averages[$i++][] = $avg_score;
                            } else {
                                $succeded = true;
                                $i++;
                            }
                            $avg_score = number_format((float)$avg_score, 2, '.', '');
                            ?>
                            <td style="text-align: center;">{{$finished_lessons_count}} out
                                of {{$required_lessons_count}}</td>
                            <td style="text-align: center; {{isset($succeded) && !$succeded?'color:red;':''}}">{{$avg_score}}%
                            </td>
                        @endforeach
                        <?php
                        $student_score = count($student_scores) ? (array_sum($student_scores) / count($student_scores)) : 0;
                        if ($student_score) {
                            $succeded = $student_score >= $class->minimum_score;
                            $all_students_score[] = $student_score;
                        } else {
                            $succeded = true;
                        }


                        $student_score = number_format((float)$student_score, 2, '.', '');
                        ?>
                        <td style="text-align: center; {{isset($succeded) && !$succeded?'color:red;':''}}">{{$student_score}}%
                        </td>
                    </tr>
                @endforeach
                </tbody>
                <tfoot class="no-border">
                <tr>
                    <th style="width: 31%;">Class Averages</th>
                    <?php $i = 0; ?>
                    @foreach ($series as $serie)
                        <?php
                        if (isset($series_averages[$i])) {
                            $avg = count($series_averages[$i]) ? (array_sum($series_averages[$i]) / count($series_averages[$i])) : 0;
                            $succeded = $avg >= $class->minimum_score;
                            $avg = number_format((float)$avg, 2, '.', '');
                        } else {
                            $succeded = true;
                            $avg = number_format((float)0, 2, '.', '');;
                        }
                        ?>
                        <th></th>
                        <th style="text-align: center; {{isset($succeded) && !$succeded?'color:red;':''}}">{{$avg}}%
                        </th>
                        <?php $i++; ?>
                    @endforeach
                    <?php
                    $scr = count($all_students_score) ? (array_sum($all_students_score) / count($all_students_score)) : 0;
                    $succeded = $scr >= $class->minimum_score;
                    $scr = number_format((float)$scr, 2, '.', '');
                    ?>
                    <th style="text-align: center; {{isset($succeded) && !$succeded?'color:red;':''}}">{{$scr}}</th>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

@section('scripts')
    <script type="text/javascript">
        $(function () {
            $('table').dataTable({
                paging: true,
                columns: [null,],
                dom: 'T<"clear">lfrtip',
                tableTools: {
                    "sSwfPath": "{{ asset('copy_csv_xls_pdf.swf') }}",
                    "aButtons": [
                        {
                            "sExtends": "copy",
                            "mColumns": [0]
                        },
                        {
                            "sExtends": "xls",
                            "sButtonText": "Export Excel",
                            "mColumns": [0]
                        },
                        {
                            "sExtends": "pdf",
                            "sButtonText": "Export PDF",
                            "mColumns": [0]
                        }
                    ]
                }
            });

        });
    </script>
@stop
