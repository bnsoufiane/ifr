<div class="page-head">
    <h2 class="orange_color">"{{ $lesson->title }}" Grades</h2>
    <div class='help-block'>
        On this screen you can view a particular student's progress on the selected lesson, and the score that they have achieved.  Use the breadcrumb navigation  to go back and view other products, series, or lessons reports.<br/><br/>

        You can also export this screen as a pdf or excel sheet using the buttons on the right hand side.

    </div>
    <ol class="breadcrumb">
        <li><a href="{{ URL::route('admin.index') }}">Home</a></li>
        <?php
        $currentUser = \Sentry::getUser();
        $isTeacher = $currentUser->isTeacher();
        $url = URL::route('admin.reports.index');
        if (!$isTeacher && !$cs ) {
            echo '<li><a href="' . $url . '">Teachers</a></li>';
            $classesUrl = URL::route('admin.classes.grades.teacher_classes', array($teacher_id));
        } else {
            $classesUrl = URL::route('admin.reports.index');
        }
        ?>
        <li><a href="{{ ($cs)? (URL::route('admin.classes.index')):($classesUrl) }}">Classes</a></li>
        <li><?php
            if ($cs && $teacher_id) {
                $arr = array($class->id, 'cs' => $cs, 'teacher' => $teacher_id);
            } else if ($cs) {
                $arr = array($class->id, 'cs' => $cs);
            } else if ($teacher_id) {
                $arr = array($class->id, 'teacher' => $teacher_id);
            } else {
                $arr = array($class->id);
            }
            ?>
            <a href="{{ URL::route('admin.classes.grades.modules', $arr) }}">{{ $class->name }}</a></li>
        <li><a href="{{ URL::route('admin.classes.grades.modules', $arr) }}">{{ $module->title }}</a></li>
        <li><?php
            if ($cs && $teacher_id) {
                $arr = array($class->id, $module->id, 'cs' => $cs, 'teacher' => $teacher_id);
            } else if ($cs) {
                $arr = array($class->id, $module->id, 'cs' => $cs);
            } else if ($teacher_id) {
                $arr = array($class->id, $module->id, 'teacher' => $teacher_id);
            } else {
                $arr = array($class->id, $module->id);
            }
            ?>
            <a href="{{ URL::route('admin.classes.grades.series', $arr) }}">{{ $serie->title }}</a></li>
        <li><?php
            if ($cs && $teacher_id) {
                $arr = array($class->id, $module->id, 'cs' => $cs, 'teacher' => $teacher_id);
            } else if ($cs) {
                $arr = array($class->id, $module->id, 'cs' => $cs);
            } else if ($teacher_id) {
                $arr = array($class->id, $module->id, 'teacher' => $teacher_id);
            } else {
                $arr = array($class->id, $module->id, $serie->id);
            }
            ?>
            <a href="{{ URL::route('admin.classes.grades.lessons', $arr) }}">{{ $lesson->title }}</a></li>
        <li class="active">Grades</li>
    </ol>
</div>

<div class="cl-mcont">
    <div class="block-flat no-padding">
        <div class="content">
            <table class="class-grades-table no-border blue">
                <thead class="no-border">
                    <tr></tr>
                    <tr>
                        <th style="width: 20%;">Student</th>

                        <th>Progress</th>
                        <th>Score</th>
                    </tr>
                </thead>
                <tbody class="no-border-x">
                    @foreach ($students as $student)
                    <tr>
                        <td class="student-name">
                            <?php
                            if ($cs && $teacher_id) {
                                $arr = array($class->id, $module->id, $serie->id, $lesson->id, $student->id(), 'cs' => $cs, 'teacher' => $teacher_id);
                            } else if ($cs) {
                                $arr = array($class->id, $module->id, $serie->id, $lesson->id, $student->id(), 'cs' => $cs);
                            } else if ($teacher_id) {
                                $arr = array($class->id, $module->id, $serie->id, $lesson->id, $student->id(), 'teacher' => $teacher_id);
                            } else {
                                $arr = array($class->id, $module->id, $serie->id, $lesson->id, $student->id());
                            }
                            ?>
                            <a href="{{ URL::route('admin.classes.show_lesson_student_grades', $arr) }}">
                                {{ $student->formattedName() }}
                            </a>
                        </td>

                        <td>{{ $student->answersTo($lesson)->progress() }}</td>
                        <td>{{ $student->answersTo($lesson)->grade() }}%</td>
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
            columns: [null, null, null],
            dom: 'T<"clear">lfrtip',
            tableTools: {
                "sSwfPath": "{{ asset('copy_csv_xls_pdf.swf') }}",
                "aButtons": [
                    {
                        "sExtends": "copy",
                        "mColumns": [0, 1, 2]
                    },
                    {
                        "sExtends": "xls",
                        "sButtonText": "Export Excel",
                        "mColumns": [0, 1, 2]
                    },
                    {
                        "sExtends": "pdf",
                        "sButtonText": "Export PDF",
                        "mColumns": [0, 1, 2]
                    }
                ]
            }
        });

    });
</script>
@stop
