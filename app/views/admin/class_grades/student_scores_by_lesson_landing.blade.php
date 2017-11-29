<div class="page-head">
    <span>Class: "{{$class->name}}"</span><br/>
    <div class='help-block'>
        Select a Series to view Student Report by Lesson
    </div>
</div>

<div class="cl-mcont">
    <div class="block-flat no-padding">
        <div class="content">
            <table class="class-grades-table no-border blue">
                <thead class="no-border">
                    <tr>
                        <th style="width: 31%; font-weight: bold; text-align: center;">Student</th>
                        <th style="font-weight: bold; text-align: center;">Select Series</th>
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
                                <a href="{{ URL::route('admin.reports.classes.student_scores_by_lesson', array($class->id, $student->id, $series[0]->id)) }}">
                                {{$student->last_name}}, {{$student->first_name}}
                                </a>
                            </td>
                            <td style="text-align: center">
                                @foreach ($series as $serie)
                                    <a class="student_lessons_report_link" href="{{ URL::route('admin.reports.classes.student_scores_by_lesson', array($class->id, $student->id, $serie->id)) }}">
                                        {{$serie->title}}
                                    </a>
                                @endforeach
                            </td>
                        </tr>
                    @endforeach
                </tbody>
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
