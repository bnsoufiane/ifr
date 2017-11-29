<div class="page-head">
    <span>Class: "{{$class->name}}" Student: {{"$student->last_name, $student->first_name"}}</span><br/>
    <span>Student Report by Series</span>
        <div class='help-block'>
            This student report shows completed Required, Optional, and All Lessons by Series.
        </div>
</div>

<div class="cl-mcont">
    <a class="back_to_reports" href="{{ URL::route('admin.reports.classes.scores_by_series', $class->id) }}">Back to Class Report by Series</a>

    <div class="block-flat no-padding">
        <div class="content">
            <table class="class-grades-table no-border blue">
                <thead class="no-border">
                    <tr>
                        <th style="width: 31%;"></th>
                        <th colspan="2" style="text-align: center">Required Lessons</th>
                        <th colspan="2" style="text-align: center">Optional Lessons</th>
                        <th colspan="2" style="text-align: center">All Lessons</th>
                    </tr>
                    <tr>
                        <th style="width: 31%; text-align: center">Student</th>
                        <th style="text-align: center;">Completed</th>
                        <th style="text-align: center;">Avg Score</th>
                        <th style="text-align: center;">Completed</th>
                        <th style="text-align: center;">Avg Score</th>
                        <th style="text-align: center;">Completed</th>
                        <th style="text-align: center;">Avg Score</th>
                    </tr>
                </thead>
                <tbody class="no-border-x">
                    <?php
                        $series_averages = array();
                        $class_optional_lessons = $class->optional_lessons()->distinct()->get(array('optional_lesson_id'))->toArray();
                        $class_optional_lessons_ids = array();
                        foreach($class_optional_lessons as $optional_lesson){
                            $class_optional_lessons_ids[]=$optional_lesson['optional_lesson_id'];
                        }
                    ?>
                    @foreach ($series as $serie)
                        <tr>
                            <td>
                                <a href="{{ URL::route('admin.reports.classes.student_scores_by_lesson', array($class->id, $student->id, $serie->id)) }}">
                                    {{$serie->title}}
                                </a>
                            </td>
                            <?php $i=0; ?>
                            <?php
                                $all_lessons = $serie->lessons;
                                if(count($class_optional_lessons_ids)){
                                    $required_lessons = $serie->lessons()->whereNotIn('id',$class_optional_lessons_ids)->get();
                                }else{
                                    $required_lessons = $serie->lessons()->get();
                                }
                                if(count($class_optional_lessons_ids)){
                                    $optional_lessons = $serie->lessons()->whereIn('id',$class_optional_lessons_ids)->get();
                                }else{
                                    $optional_lessons = array();
                                }
                                $all_lessons_count = $all_lessons->count();
                                $required_lessons_count = ((gettype($required_lessons)=="array"))?0:$required_lessons->count();
                                $optional_lessons_count = ((gettype($optional_lessons)=="array"))?0:$optional_lessons->count();
                                $required_assessment_ids = array();
                                $optional_assessment_ids = array();

                                $required_lessons_scores = array();
                                $optional_lessons_scores = array();

                                foreach ($required_lessons as $lesson){
                                    $required_assessment_ids[]=$lesson->activities()->where('template_type', '=', 'ActivityTemplates\Assessment')->first()->id;
                                }
                                foreach ($optional_lessons as $lesson){
                                    $optional_assessment_ids[]=$lesson->activities()->where('template_type', '=', 'ActivityTemplates\Assessment')->first()->id;
                                }

                                foreach ($required_lessons as $lesson){
                                    $score = $student->ScoreByLesson($lesson);
                                    if($score){
                                        $required_lessons_scores[] = $score;
                                    }
                                }
                                foreach ($optional_lessons as $lesson){
                                    $score = $student->ScoreByLesson($lesson);
                                    if($score){
                                        $optional_lessons_scores[] = $score;
                                    }
                                }
                             ?>

                            <?php
                                if(count($required_assessment_ids)){
                                    $finished_required_lessons_count = $student->answers()->whereIn('activity_id',$required_assessment_ids)->count();
                                }else{
                                    $finished_required_lessons_count = 0;
                                }

                                if(count($optional_assessment_ids)){
                                    $finished_optional_lessons_count = $student->answers()->whereIn('activity_id',$optional_assessment_ids)->count();
                                }else{
                                    $finished_optional_lessons_count = 0;
                                }

                                $required_avg_score = count($required_lessons_scores)?(array_sum($required_lessons_scores)/count($required_lessons_scores)):0;
                                if(count($required_lessons_scores)){
                                    $series_averages[$i++][]=$required_avg_score;
                                }else{
                                    $i++;
                                }
                                $required_avg_score = number_format((float)$required_avg_score, 2, '.', '');

                                $optional_avg_score = count($optional_lessons_scores)?(array_sum($optional_lessons_scores)/count($optional_lessons_scores)):0;
                                if(count($optional_lessons_scores)){
                                    $series_averages[$i++][]=$optional_avg_score;
                                }else{
                                    $i++;
                                }
                                $optional_avg_score = number_format((float)$optional_avg_score, 2, '.', '');

                                $all_lessons_scores = array_merge ($required_lessons_scores, $optional_lessons_scores);
                                $all_avg_score = count($all_lessons_scores)?(array_sum($all_lessons_scores)/count($all_lessons_scores)):0;
                                if(count($all_lessons_scores)){
                                    $series_averages[$i++][]=$all_avg_score;
                                }else{
                                    $i++;
                                }
                                $all_avg_score = number_format((float)$all_avg_score, 2, '.', '');

                             ?>
                            <td style="text-align: center;">{{$finished_required_lessons_count}} out of {{$required_lessons_count}}</td>
                            <?php $succeded = $finished_required_lessons_count==0 || $required_avg_score>=$class->minimum_score; ?>
                            <td style="text-align: center; {{isset($succeded) && !$succeded?'color:red;':''}}">{{$required_avg_score}}%</td>

                            <td style="text-align: center;">{{$optional_lessons_count?"$finished_optional_lessons_count out of $optional_lessons_count":"n/a"}}</td>
                            <?php $succeded = $finished_optional_lessons_count==0 || $optional_avg_score>=$class->minimum_score; ?>
                            <td style="text-align: center; {{isset($succeded) && !$succeded?'color:red;':''}}">{{$optional_lessons_count?$optional_avg_score:"n/a"}}%</td>
                            <td style="text-align: center;">{{$finished_required_lessons_count+$finished_optional_lessons_count}} out of {{$all_lessons_count}}</td>
                            <?php $succeded = ( $finished_required_lessons_count+$finished_optional_lessons_count == 0) || $all_avg_score>=$class->minimum_score; ?>
                            <td style="text-align: center; {{isset($succeded) && !$succeded?'color:red;':''}}">{{$all_avg_score}}%</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="no-border">
                    <tr>
                        <th style="width: 31%;">Student Total Averages</th>
                        <?php
                            for($i=0; $i<3; $i++){
                                if(isset($series_averages[$i])){
                                    $avg = count($series_averages[$i])?(array_sum($series_averages[$i])/count($series_averages[$i])):0;
                                    $succeded = $avg>=$class->minimum_score;
                                    $avg = number_format((float)$avg, 2, '.', '');
                                }else{
                                    $succeded = true;
                                    $avg=number_format((float)0, 2, '.', '');;
                                }
                            ?>
                            <th></th>
                            <th style="text-align: center; {{isset($succeded) && !$succeded?'color:red;':''}}">{{$avg}}%</th>
                            <?php
                            }
                            ?>
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
