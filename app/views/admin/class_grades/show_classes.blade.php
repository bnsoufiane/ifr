<div class="page-head">
    <h2 class="orange_color">Scores and Reports</h2>
    <?php
    $currentUser = \Sentry::getUser();
    $isTeacher = $currentUser->isTeacher();
    $url = URL::route('admin.reports.index');
    ?>
    <div class='help-block'>
        Several reports are available to you in a variety of formats: by Class, by Student, or by Test Score. A list of
        the reports is given below. To view a report, click one of the tabs.<br/>
        Scores displayed in red are below the minimum score set for required lessons in this class.<br/>
        <h4><strong>Class Reports</strong></h4>
         Series: Completed Lessons and scores for all students in a class by Series<br/>
         Lessons: Required and Optional Lessons scores for all students in a class by Series  <br/><br/>
        <h4><strong>Student Reports</strong></h4>
         Series: Required and Optional Lesson Scores for individual students by Series<br/>
         Lessons: Required and Optional Lesson Scores for individual students by Lesson. You may also view or print the
        student's <em>Blog About It</em> answers.<br/><br/>
        <h4><strong>Test Reports</strong></h4>
         Pre/Post-test: Pre- and Post-Test Scores for individual students by Series<br/>
         This report shows students Pre- and Post-Test scores and the number of correct or incorrect answers.<br/><br/>
        <h4><strong>Final Grade</strong></h4>
         This report shows students average score for all series completed.
    </div>
</div>

<div class="cl-mcont">
    <div class="block-flat no-padding">
        <div class="content">
            <table class="class-grades-table no-border blue">
                <thead class="no-border">
                <tr>
                    <th style="width: 31%; font-weight: bold;">Class</th>
                    <th style="width: 23%; font-weight: bold; text-align: center;">Class Reports</th>
                    <th style="width: 23%; font-weight: bold; text-align: center;">Student Reports</th>
                    <th style="width: 23%; font-weight: bold; text-align: center;">Test Reports</th>
                </tr>
                </thead>
                <tbody class="no-border-x">

                </tbody>
            </table>
        </div>
    </div>
</div>

@section('scripts')
    <script type="text/javascript">
        $(function () {
            var data_table = $('table').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": "{{ URL::to('admin/datatables/teacher-classes-report?teacher_id='.$teacher_id) }}",
                "columns": [
                    {data: 'name', name: 'name'},
                    {data: 'class_reports', name: 'class_reports', orderable: false, searchable: false},
                    {data: 'student_reports', name: 'student_reports', orderable: false, searchable: false},
                    {data: 'test_reports', name: 'test_reports', orderable: false, searchable: false}
                ],
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
