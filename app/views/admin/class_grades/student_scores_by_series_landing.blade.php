<div class="page-head">
    <span>Class: "{{$class->name}}"</span><br/>
    <div class='help-block'>
        Select a Student to view Student Report by Series
    </div>
</div>

<div class="cl-mcont">
    <div class="block-flat no-padding">
        <div class="content">
            <table class="class-grades-table no-border blue">
                <thead class="no-border">
                    <tr>
                        <th style="width: 31%; font-weight: bold; padding-left: 50px;">Student</th>
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
