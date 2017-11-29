<div class="page-head">
    <span>Class: "{{$class->name}}"</span><br/>
    <span>Final Grade</span>
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
                        <th>All series</th>
                    </tr>
                    <tr>
                        <th style="width: 31%; text-align: center;">Student</th>
                        <th>Avg Score</th>
                    </tr>
                </thead>
                <tbody class="no-border-x">
                    <?php
                        $series_averages = array();
                        $optional_lessons = $class->optional_lessons()->distinct()->get(array('optional_lesson_id'))->toArray();
                        $optional_lessons_ids = array();
                        $all_students_score = array();
                        foreach($optional_lessons as $optional_lesson){
                            $optional_lessons_ids[]=$optional_lesson['optional_lesson_id'];
                        }
                    ?>
                    @foreach ($students as $student)
                        <tr>
                            <td>
                                <a href="{{ URL::route('admin.reports.classes.student_scores_by_series', array($class->id, $student->id)) }}">
                                    {{$student->last_name}}, {{$student->first_name}}
                                </a>
                            </td>
                            <?php $i=0;
                                $student_scores = array();?>
                            @foreach ($series as $serie)
                                <?php
                                    $all_lessons = $serie->lessons;
                                    if(count($optional_lessons_ids)){
                                        $required_lessons = $serie->lessons()->whereNotIn('id',$optional_lessons_ids)->get();
                                    }else{
                                        $required_lessons = $serie->lessons()->get();
                                    }
                                    $required_lessons_count = $required_lessons->count();
                                    $assessment_ids = array();
                                    $lessons_scores = array();
                                ?>

                                @foreach ($required_lessons as $lesson)
                                    <?php
                                        $assessment_ids[]=$lesson->activities()->where('template_type', '=', 'ActivityTemplates\Assessment')->first()->id;
                                    ?>
                                @endforeach
                                @foreach ($all_lessons as $lesson)
                                    <?php
                                        $score = $student->ScoreByLesson($lesson);
                                        if($score){
                                            $lessons_scores[] = $score;
                                        }
                                    ?>
                                @endforeach

                                <?php
                                    if(count($assessment_ids)){
                                        $finished_lessons_count = $student->answers()->whereIn('activity_id',$assessment_ids)->count();
                                    }else{
                                        $finished_lessons_count = $student->answers()->count();
                                    }
                                    $lessons_count=count($all_lessons);
                                    $avg_score = count($lessons_scores)?(array_sum($lessons_scores)/count($lessons_scores)):0;
                                    if($avg_score){
                                        $student_scores[]=$avg_score;
                                    }
                                    if(count($lessons_scores)){
                                        $series_averages[$i++][]=$avg_score;
                                    }else{
                                        $i++;
                                    }
                                    $avg_score = number_format((float)$avg_score, 2, '.', '');
                                 ?>
                            @endforeach
                            <?php
                                $student_score = count($student_scores)?(array_sum($student_scores)/count($student_scores)):0;
                                if($student_score){
                                    $all_students_score[]=$student_score;
                                }
                                $succeded = $student_score>=$class->minimum_score;
                                $student_score = number_format((float)$student_score, 2, '.', '');
                             ?>
                            <td style=" {{isset($succeded) && !$succeded?'color:red;':''}}">{{$student_score}}%</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="no-border">
                    <tr>
                        <th style="width: 31%;">Class Average</th>
                        <?php $i=0; ?>
                        @foreach ($series as $serie)
                            <?php
                                if(isset($series_averages[$i])){
                                    $avg = count($series_averages[$i])?(array_sum($series_averages[$i])/count($series_averages[$i])):0;
                                    $avg = number_format((float)$avg, 2, '.', '');
                                }else{
                                    $avg=number_format((float)0, 2, '.', '');;
                                }
                            ?>
                            <?php $i++; ?>
                        @endforeach
                        <?php
                            $scr = count($all_students_score)?(array_sum($all_students_score)/count($all_students_score)):0;
                            $succeded = $scr>=$class->minimum_score;
                            $scr = number_format((float)$scr, 2, '.', '');
                         ?>
                        <th  {{isset($succeded) && !$succeded?'color:red;':''}}>{{$scr}}%</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

@section('scripts')
<script type="text/javascript">
    $(function() {
        $('table').dataTable({
            paging: true,
            columns: [null, ],
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
