<div class="page-head">
    <span>Class: "{{$class->name}}" Student: {{"$student->last_name, $student->first_name"}}</span><br/>
    <span>Student Report by Lesson</span><br/>
    <span>{{$serie->title}}</span>
        <div class='help-block'>
            This student report shows completed Required, Optional, and All Lessons by lesson number.
        </div>
</div>

<div class="cl-mcont">
    <a class="back_to_reports" href="{{ URL::route('admin.reports.classes.student_scores_by_series', array($class->id, $student->id)) }}">Back to Student Report by Series</a>

    <div class="block-flat no-padding">
        <div class="content">
            <input type="hidden" name="page_title" value='{{ "$student->last_name $student->first_name - $serie->title - Grades" }}' />
            <table class="class-grades-table no-border blue">
                <thead class="no-border">
                    <tr>
                        <th colspan="2">
                            <select class="form-control report_students">
                                @foreach($students as $std)
                                    <option href="{{ URL::route('admin.reports.classes.student_scores_by_lesson', array($class->id, $std->id, $serie->id)) }}" {{($student->id == $std->id)?'selected': ''}}>{{"$std->last_name, $std->first_name"}}</option>
                                @endforeach
                            </select>
                        </th>
                        <?php
                            for($i=0; $i<count($series); $i++){
                                $cls = ($series[$i]->id == $serie->id)?'currrent_series':'';
                                echo '<th style="text-align: center"><a class="'.$cls.'" href="'. URL::route("admin.reports.classes.student_scores_by_lesson", array($class->id, $student->id, $series[$i]->id)).'" >'.$series[$i]->title.'</a></th>';
                            }

                            for($i=0; $i<(4-count($series)); $i++){
                                echo '<th></th>';
                            }

                        ?>

                        <th style="text-align: center"><a href="javascript:" class="exp_csv">Export csv</a></th>
                    </tr>
                    <tr>
                        <th></th>
                        <th></th>
                        <th style="text-align: center">Required Lessons</th>
                        <th style="text-align: center">Optional Lessons</th>
                        <th style="text-align: center">All Lessons</th>
                        <th></th>
                        <th></th>
                    </tr>
                    <tr>
                        <th style="width: 22%; text-align: center">Lesson</th>
                        <th style="text-align: center;">Lesson Topic</th>
                        <th style="text-align: center;">Assessment Score</th>
                        <th style="text-align: center;">Assessment Score</th>
                        <th style="text-align: center;">Assessment Score</th>
                        <th style="text-align: center;">Blog</th>
                        <th style="text-align: center;">Lessons</th>
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
                    @foreach ($lessons as $lesson)
                        <tr>
                            <td>{{$lesson->title}}</td>
                            <td style="text-align: center;">{{$lesson->topic}}</td>

                            <?php
                                $score = $student->ScoreByLesson($lesson);
                                $succeded = $score>=$class->minimum_score;
                                if($lesson->required($class->id) && $score){
                                    $series_averages[0][] = $score;
                                    $series_averages[2][] = $score;
                                }else{
                                    if($score){
                                        $series_averages[1][] = $score;
                                        $series_averages[2][] = $score;
                                    }
                                }

                                $score = $avg=number_format((float)$score, 2, '.', '');

                             ?>
                            <td style="text-align: center; {{isset($succeded) && !$succeded?'color:red;':''}}">{{$lesson->required($class->id)?"$score%":''}}</td>
                            <td style="text-align: center; {{isset($succeded) && !$succeded?'color:red;':''}}">{{$lesson->required($class->id)?'':"$score%"}}</td>
                            <td style="text-align: center; {{isset($succeded) && !$succeded?'color:red;':''}}">{{$score}}%</td>
                            <td style="text-align: center">
                                <a href="javascript:" class="view_blog" lesson_id="{{{$lesson->id}}}">View</a>&nbsp;&nbsp;&nbsp;
                                <a href="javascript:" class="print_blog" lesson_id="{{{$lesson->id}}}">Print</a>
                            </td>
                            <td style="text-align: center">
                                <a href="javascript:" class="view_student_lesson" lesson_id="{{{$lesson->id}}}">View</a>&nbsp;&nbsp;&nbsp;
                                <a href="javascript:" class="print_student_lesson" lesson_id="{{{$lesson->id}}}">Print</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="no-border">
                    <tr>
                        <th style="width: 22%;">Student Averages</th>
                        <th></th>
                        <?php
                            for($i=0; $i<3; $i++){
                                if(isset($series_averages[$i])){
                                    $avg = count($series_averages[$i])?(array_sum($series_averages[$i])/count($series_averages[$i])):0;
                                    $succeded = $avg>=$class->minimum_score;
                                    $avg = number_format((float)$avg, 2, '.', '');
                                }else{
                                    $succeded= true;
                                    $avg=number_format((float)0, 2, '.', '');
                                }
                            ?>
                            <th style="text-align: center; {{isset($succeded) && !$succeded?'color:red;':''}}">{{$avg}}%</th>
                            <?php
                            }
                            ?>
                        <th style="text-align: center"><a href="javascript:" class="print_student_blogs">Print Blog Report</a></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

@section('scripts')
<script type="text/javascript">
    $(function() {
        var modalContent = '<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">'+
        '<div class="modal-dialog modal-lg" style="width: 80%;">'+
            '<div class="modal-content">'+
                '<div class="modal-header">'+
                    '<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>'+
                    '<h4 class="modal-title"></h4>'+
                '</div>'+
                '<div class="modal-body" style="height: 800px;">'+
                    '<iframe style="width: 100%; height: 100%; border: none;"'+
                            'id="preview-iframe"></iframe>'+
                '</div>'+
            '</div>'+
        '</div>'+
       '</div>';

        $('body').on('click', '.view_blog', function (event) {

           var data = {
               "student_id": {{{$student->id}}},
               "class_id": {{{$class->id}}},
               "serie_id": {{{$serie->id}}},
               "lesson_id": $(this).attr('lesson_id'),
           }

            $.ajax({
                url: '/admin/reports/view_blog',
                method: 'GET',
                data: data,
                dataType: 'html'
            }).done(function (response) {
                response = response.replace(new RegExp('&lt;br&gt;', 'g'), '<br/>');

                var iframe = $(modalContent)
                    .appendTo(document.body)
                    .modal()
                    .find('iframe')[0];

                iframe.contentWindow.contents = response;
                iframe.src = 'javascript:window["contents"]';
            });
        });

        $('body').on('click', '.print_blog', function (event) {

           var data = {
               "student_id": {{{$student->id}}},
               "class_id": {{{$class->id}}},
               "serie_id": {{{$serie->id}}},
               "print": true,
               "lesson_id": $(this).attr('lesson_id')
           }

            var _newTabUrl =  '/admin/reports/view_blog?'+jQuery.param(data);
            var win = window.open(_newTabUrl, '_blank');
            win.focus();

            /*$.ajax({
                url: '/admin/reports/view_blog',
                method: 'GET',
                data: data,
                dataType: 'html'
            }).done(function (response) {
                response = response.replace(new RegExp('&lt;br&gt;', 'g'), '<br/>');

                var iframe = $(modalContent)
                    .appendTo(document.body)
                    //.modal()
                    .find('iframe')[0];

                iframe.contentWindow.contents = response;
                iframe.src = 'javascript:window["contents"]';
            });*/
        });

        $('body').on('click', '.view_student_lesson', function (event) {

           var data = {
               "student_id": {{{$student->id}}},
               "class_id": {{{$class->id}}},
               "serie_id": {{{$serie->id}}},
               "lesson_id": $(this).attr('lesson_id')
           }

            $.ajax({
                url: '/admin/reports/view_student_lesson',
                method: 'GET',
                data: data,
                dataType: 'html'
            }).done(function (response) {
                response = response.replace(new RegExp('&lt;br&gt;', 'g'), '<br/>');

                var iframe = $(modalContent)
                    .appendTo(document.body)
                    .modal()
                    .find('iframe')[0];

                iframe.contentWindow.contents = response;
                iframe.src = 'javascript:window["contents"]';
            });
        });

        $('body').on('click', '.print_student_lesson', function (event) {

           var data = {
               "student_id": {{{$student->id}}},
               "class_id": {{{$class->id}}},
               "serie_id": {{{$serie->id}}},
               "print": true,
               "lesson_id": $(this).attr('lesson_id')
           }

            var _newTabUrl = '/admin/reports/view_student_lesson?'+jQuery.param(data);
            var win = window.open(_newTabUrl, '_blank');
            win.focus();

            /*$.ajax({
                url: '/admin/reports/view_student_lesson',
                method: 'GET',
                data: data,
                dataType: 'html'
            }).done(function (response) {
                response = response.replace(new RegExp('&lt;br&gt;', 'g'), '<br/>');

                var iframe = $(modalContent)
                    .appendTo(document.body)
                    //.modal()
                    .find('iframe')[0];

                iframe.contentWindow.contents = response;
                iframe.src = 'javascript:window["contents"]';
            });*/
        });

        $('body').on('click', '.print_student_blogs', function (event) {

           var data = {
               "student_id": {{{$student->id}}},
               "class_id": {{{$class->id}}},
               "serie_id": {{{$serie->id}}},
               "print": true
           }

            var _newTabUrl = '/admin/reports/print_student_blogs?'+jQuery.param(data);
            var win = window.open(_newTabUrl, '_blank');
            win.focus();

            /*$.ajax({
                url: '/admin/reports/print_student_blogs',
                method: 'GET',
                data: data,
                dataType: 'html'
            }).done(function (response) {
                response = response.replace(new RegExp('&lt;br&gt;', 'g'), '<br/>');

                var iframe = $(modalContent)
                    .appendTo(document.body)
                    //.modal()
                    .find('iframe')[0];

                iframe.contentWindow.contents = response;
                iframe.src = 'javascript:window["contents"]';
            });*/
        });

        $('table').dataTable({
            paging: false,
            "bSort": false,
            "bFilter": false,
            columns: [
                null, null, null, null, null, null, null
            ],
            dom: 'T<"clear">lfrtip',
            tableTools: {
                "sSwfPath": "{{ asset('copy_csv_xls_pdf.swf') }}",
                "aButtons": [
                    {
                        "sExtends": "csv",
                        "sButtonText": "Export Grades",
                        "mColumns": [0, 1, 2, 3, 4],
                        "sTitle":$('input[name="page_title"]').val()
                    }
                ]
            },
            "fnInitComplete": function(oSettings, json) {
                $('.exp_csv').parent().html($('.DTTT_container'));
                $('.DTTT_container').addClass('exprt_csv');
                $('a.DTTT_button').css({"background": "#fff", "border": "none", "padding": "0", "box-shadow": "none", "margin-right":0, "color":"#99805c"});
                $('a.DTTT_button span').css({"color":"#99805c","font-size":"14px"});
            }
        });

        $('body').on('change', '.report_students', function (event) {
            window.location = $('option:selected', this).attr('href');

        });
    });
</script>
@stop
