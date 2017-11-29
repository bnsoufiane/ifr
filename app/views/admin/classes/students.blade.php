<div class="page-head">
    <h2 class="orange_color">Classes: View/Edit Students</h2>
    <div class='help-block'>
        To view or edit a student, click on the student’s name.  After you edit your student’s name, click the <strong>Save Student</strong> button.<br/><br/>
        You may also delete a student at this page.  Click the <strong>Delete</strong> red button to remove a student.  Important: deleting a student will remove all student information from your roster!<br/><br/>
        The three boxes below named Copy, Export Excel, and Export PDF shown over the last column allow you to copy your roster to your computer clipboard. You may also export your roster to a spreadsheet or PDF.
        <?php
            $currentUser = \Sentry::getUser();
            $isTeacher = $currentUser->isTeacher();
        ?>
    </div>
    <ol class="breadcrumb">
        <li><a href="{{ URL::route('admin.index') }}">Home</a></li>
        <li><a href="{{ URL::route('admin.classes.index')  }}">{{$class->name}}</a></li>
        <li class="active">View/Edit Students</li>
    </ol>
</div>

<div class="cl-mcont">

    <div class="block-flat no-padding">
        <div class="content">
            <table class="users-table no-border blue" id="classes">
                <thead class="no-border">
                    <tr>
                        <th style="width: 30%;">Last Name</th>
                        <th style="width: 30%;">First Name</th>
                        <th style="width: 30%;">Username</th>
                        <th style="width: 10%;">Delete</th>
                    </tr>
                </thead>
                <tbody class="no-border-x">
                    @foreach ($students as $student)
                    <tr>
                        <td>
                            <a href="{{ URL::route('admin.students.edit', $student->id) }}">
                                {{ trim($student->last_name) }}
                            </a>
                        </td>
                        <td>
                            <a href="{{ URL::route('admin.students.edit', $student->id) }}">
                                {{ trim($student->first_name) }}
                            </a>
                        </td>
                        <td>
                            <a href="{{ URL::route('admin.students.edit', $student->id) }}">
                                {{ $student->username }}
                            </a>
                        </td>
                        <td>
                            <a class="remove_student" href="javascript:void(0);" style="color: #b94a48;">
                                <i class="fa fa-minus-circle"></i>
                            </a>
                            <input type="hidden" name="students_id" value="{{ $student->id }}" />
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@section('scripts')
{{ javascript_include_tag('admin/classes_include') }}

<script>
    mytable = $('#classes').DataTable({
        paging: true,
        columns: [
            null, null, null, null
        ],
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
</script>
@stop
