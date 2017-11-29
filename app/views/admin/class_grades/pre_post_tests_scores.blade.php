<div class="page-head">
    <span>Class: "{{$class->name}}"</span><br/>
    <span>Class Pre- and Post-Test Scores</span>
        <div class='help-block'>
            This report shows each student's Pre- and Post-Test scores and the number of correct and incorrect answers.
        </div>
</div>

<div class="cl-mcont">
    <div class="block-flat no-padding">
        <div class="content">
            <?php $test = \Tests::getBySchoolClass($class->id); ?>
            @if(!isset($test))
                <div style="padding: 10px"><h4>The Pre- and Post-Tests questions have not been selected yet.</h4></div>
            @else
                <table class="class-grades-table no-border blue">
                    <thead class="no-border">
                        <tr>
                            <th style="width: 31%;"></th>
                            <th colspan="2" style="text-align: center">Pre-Test</th>
                            <th colspan="2" style="text-align: center;">Post-Test</th>
                        </tr>
                        <tr>
                            <th style="width: 31%; text-align: center;">Student</th>
                            <th style="text-align: center;">Correct Answers</th>
                            <th style="text-align: center;">Score</th>
                            <th style="text-align: center;">Correct Answers</th>
                            <th style="text-align: center;">Score</th>
                        </tr>
                    </thead>
                    <tbody class="no-border-x">
                        <?php
                            $classes_averages = array();
                            $test_conf_pretest = \TestConfiguration::getByTestIdAndTestType($test->id, \TestConfiguration::PRE);
                            $test_conf_posttest = \TestConfiguration::getByTestIdAndTestType($test->id, \TestConfiguration::POST);
                        ?>
                        @foreach ($students as $student)
                            <?php $i=0; ?>
                            <tr>
                                <td>
                                    <a href="{{ URL::route('admin.reports.classes.student_scores_by_series', array($class->id, $student->id)) }}">
                                        {{$student->last_name}}, {{$student->first_name}}
                                    </a>
                                </td>
                                <?php
                                    $test_student_pretest = \DB::table('test_students')->where('student_id', '=', $student->id)
                                        ->where('learning_level', '=', \TestStudent::PRE_TEST)
                                        ->where('status', '=', \Tests::CLOSED)->first();

                                    if($test_student_pretest){
                                        $classes_averages[$i][]=$test_student_pretest->score;
                                    }
                                ?>

                                <td style="text-align: center;">{{$test_student_pretest?(count($test_conf_pretest)*($test_student_pretest->score/100))." out of ".count($test_conf_pretest):null}}</td>
                                <?php $succeded = $test_student_pretest?($test_student_pretest->score>=$class->minimum_score):true; ?>
                                <td style="text-align: center; {{isset($succeded) && !$succeded?'color:red;':''}}">{{$test_student_pretest?"$test_student_pretest->score%":null}}</td>
                                <?php $i++;?>

                                <?php
                                    $test_student_posttest = \DB::table('test_students')->where('student_id', '=', $student->id)
                                        ->where('learning_level', '=', \TestStudent::POST_TEST)
                                        ->where('status', '=', \Tests::CLOSED)->first();

                                    if(isset($test_student_posttest)){
                                        $classes_averages[$i][]=$test_student_posttest->score;
                                    }
                                ?>
                                <td style="text-align: center;">{{$test_student_posttest?(count($test_conf_posttest)*($test_student_posttest->score/100))." out of ".count($test_conf_posttest):null}}</td>
                                <?php $succeded = $test_student_posttest?($test_student_posttest->score>=$class->minimum_score):true; ?>
                                <td style="text-align: center; {{ isset($succeded) && !$succeded?'color:red;':''}}">{{$test_student_posttest?"$test_student_posttest->score%":null}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="no-border">
                        <tr>
                            <th style="width: 31%;">Class Averages</th>
                                <?php
                                for($i=0; $i<2; $i++){
                                    if(isset($classes_averages[$i])){
                                        $avg = count($classes_averages[$i])?(array_sum($classes_averages[$i])/count($classes_averages[$i])):0;
                                        $succeded = $avg>=$class->minimum_score;
                                        $avg = number_format((float)$avg, 2, '.', '');
                                    }else{
                                        $succeded = true;
                                        $avg=number_format((float)0, 2, '.', '');;
                                    }
                                ?>
                                <th></th>
                                <th style="text-align: center; {{isset($succeded) && !$succeded?'color:red;':''}}">{{$avg}}%</th>
                                <?php } ?>

                        </tr>
                    </tfoot>
                </table>
            @endif
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
