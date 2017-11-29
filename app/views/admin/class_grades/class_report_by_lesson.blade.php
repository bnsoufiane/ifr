<div class="page-head">
    <span>Class: {{$series->title}} "{{$class->name}}"</span><br/>
    <span>Class Report by Lesson</span>
    <div class='help-block'>
        This report by Class shows Assessment Score for completed Required and Optional Lessons by Student Name, by
        Lesson Number.
    </div>
</div>

<div class="cl-mcont">
    <a class="back_to_reports" href="{{ URL::route('admin.reports.classes.scores_by_series', $class->id) }}">Back to
        Class Report by Series</a>

    <div class="block-flat no-padding">
        <div class="content">
            <table class="class-grades-table no-border blue">
                <thead class="no-border">
                <tr>
                    <th style="min-width: 250px;"></th>
                    @foreach ($lessons as $lesson)
                        <th style="text-align: center; min-width: 130px; font-size: 13px; line-height: 19px;">
                            {{$lesson->title}}
                            {{($lesson->topic)?"($lesson->topic)":''}}<br/><br/>
                            {{$lesson->required?"Required":"Optional"}}
                        </th>
                    @endforeach
                </tr>
                <tr>
                    <th style="width: 31%; text-align: center;">Student</th>
                    @foreach ($lessons as $lesson)
                        <th style="text-align: center;">Score</th>
                    @endforeach
                </tr>
                </thead>
                <tbody class="no-border-x">
                <?php
                $lessons_scores = array();
                ?>
                @foreach ($students as $student)
                    <tr>
                        <td>
                            <a href="{{ URL::route('admin.reports.classes.student_scores_by_series', array($class->id, $student->id)) }}">
                                {{$student->last_name}}, {{$student->first_name}}
                            </a>
                        </td>
                        <?php $i = 0; ?>
                        @foreach ($lessons as $lesson)
                            <?php

                            $assessment_ids = array();
                            $score_date ="";
                            if (isset($assessments_answers_flags[$student->id])) {
                                list($score , $score_date) = \Student::StudentScoreByLesson($student->id, $lesson->assessment_id);
                            } else {
                                $score = null;
                            }
                            if ($score) {
                                $succeded = $score >= $class->minimum_score;
                                $score = number_format((float)$score, 2, '.', '') . "%";
                                $lessons_scores[$i++][] = $score;
                            } else {
                                $succeded = true;
                                $score = "I";
                                $i++;
                            }
                            ?>
                            <td style="text-align: center; {{isset($succeded) && !$succeded?'color:red;':''}}">{{$score}} <br> <small>{{$score_date}}</small> </td>
                        @endforeach
                    </tr>
                @endforeach
                </tbody>
                <tfoot class="no-border">
                <tr>
                    <th style="width: 31%;">Class Averages</th>
                    <?php $i = 0; ?>
                    @foreach ($lessons as $lesson)
                        <?php
                        if (isset($lessons_scores[$i])) {
                            $avg = count($lessons_scores[$i]) ? (array_sum($lessons_scores[$i]) / count($lessons_scores[$i])) : 0;
                            $succeded = $avg >= $class->minimum_score;
                            $avg = number_format((float)$avg, 2, '.', '');
                        } else {
                            $succeded = true;
                            $avg = number_format((float)0, 2, '.', '');
                        }
                        ?>
                        <th style="text-align: center; {{isset($succeded) && !$succeded?'color:red;':''}}">{{$avg}}%
                        </th>
                        <?php $i++; ?>
                    @endforeach
                </tr>
                @if(isset($student))
                    <tr>
                        <th></th>
                        <?php $i = 0; ?>
                        @foreach ($lessons as $lesson)
                            <th class="print_blog">
                                <a href="javascript:" class="print_blogs" lesson_id="{{{$lesson->id}}}">Print Blog
                                    Report</a><br/><br/>
                                <a href="javascript:" class="print_lesson_answers" lesson_id="{{{$lesson->id}}}">Print
                                    Lesson Report</a>
                            </th>
                        @endforeach
                    </tr>
                @endif
                </tfoot>
            </table>
        </div>
    </div>
</div>

@section('scripts')
    <script type="text/javascript">
        @if(isset($student))
        $(function () {
                    var modalContent = '<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">' +
                            '<div class="modal-dialog modal-lg" style="width: 80%;">' +
                            '<div class="modal-content">' +
                            '<div class="modal-header">' +
                            '<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>' +
                            '<h4 class="modal-title"></h4>' +
                            '</div>' +
                            '<div class="modal-body" style="height: 800px;">' +
                            '<iframe style="width: 100%; height: 100%; border: none;"' +
                            'id="preview-iframe"></iframe>' +
                            '</div>' +
                            '</div>' +
                            '</div>' +
                            '</div>';

                    $('body').on('click', '.print_lesson_answers', function (event) {

                                var data = {
                                            "student_id": {{{$student->id}}},
                                        "class_id"
                                : {{{$class->id}}},
                            "serie_id"
                    : {{{$series->id}}},
                "print"
        :
        true,
                "lesson_id"
        :
        $(this).attr('lesson_id')
        }

        var _newTabUrl = '/admin/reports/print_lesson_answers?' + jQuery.param(data);
        var win = window.open(_newTabUrl, '_blank');
        win.focus();

        /*$.ajax({
         url: '/admin/reports/print_lesson_answers',
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
        })
        ;

        $('body').on('click', '.print_blogs', function (event) {

                    var data = {
                                "student_id": {{{$student->id}}},
                            "class_id"
                    : {{{$class->id}}},
                "serie_id"
        : {{{$series->id}}},
        "print"
        :
        true,
                "lesson_id"
        :
        $(this).attr('lesson_id')
        }

        var _newTabUrl = '/admin/reports/print_lesson_blogs?' + jQuery.param(data);
        var win = window.open(_newTabUrl, '_blank');
        win.focus();


        /*$.ajax({
         url: '/admin/reports/print_lesson_blogs',
         method: 'POST',
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
        })
        ;
        @endif

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

        })
        ;
    </script>
@stop
