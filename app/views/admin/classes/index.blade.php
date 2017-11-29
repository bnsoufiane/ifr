<div class="page-head">
    <h2 class="orange_color">Classes</h2>
    <div class='help-block'>
        <br/>
        The <strong>Classes</strong> screen allows you to group students. You will follow four steps each time you set
        up a class:<br/><br/>
        Instructions for setting up classes are repeated on appropriate screens as you advance through the program. A
        printable Reference Manual
        also is available by clicking the button called <strong>Instructor Materials</strong>.<br/><br/>

        <div style="margin-left: 80px;">

            <div style="text-indent: -50px;"><strong>Step 1:</strong> To create a Class, click <strong>Create a New
                    Class</strong> at the bottom of this screen. Name your class, set a minimum score, and choose a
                common password for all students to use the first time they log in. Next, click the <strong>Add
                    Series</strong> link and select the <em>It’s for Real Workplace Ethics</em> Series number(s) you are
                using. Then click the <strong>Save Class</strong> button. Return to the <strong>Create a New
                    Class</strong> button to set up your next class<br/><br/>
                Under the <strong>Classes</strong> column at the bottom of the screen, you may also click the <strong>Edit</strong>
                tab to change a Class Name or Minimum Score or to add or delete an <em>It’s for Real Workplace
                    Ethics</em> Series for a specific class. You may also <strong>Delete</strong> a Class
                here.<br/><br/></div>
            <div style="text-indent: -50px;"><strong>Step 2:</strong> You are now ready to create a student roster.
                Under the <strong>Students</strong> column at the bottom of the screen, click the <strong>Add
                    New</strong> tab and enter a student’s name. After the roster is complete, click the <strong>Save
                    Student</strong> button. Click the <strong>View/Edit</strong> tab under the Students column to see
                or change the names you entered.<br/><br/></div>
            <div style="text-indent: -50px;"><strong>Step 3:</strong> You are now ready to set up Required and Optional
                Lessons. Under the <strong>Lessons</strong> column at the bottom of the screen, click the <strong>Required</strong>
                tab and make your choices.<br/><br/></div>
            <div style="text-indent: -50px;"><strong>Step 4:</strong> Next, under the <strong>Lessons</strong> column,
                click the <strong>Pre/Post Tests</strong> tab to create pre- and post-tests. Click the
                <strong>Preview</strong> button to view the program with the criteria you established for this class,
                such as the Series you chose, lessons you required, and other useful information.<br/><br/></div>

        </div>
        If you need to leave the program, click Sign Out on the left. When you return, click Classes to continue
        creating a Class.<br/>

        <?php
        $currentUser = \Sentry::getUser();
        $isTeacher = $currentUser->isTeacher();
        ?>
    </div>
    <ol class="breadcrumb">
        <li><a href="{{ URL::route('admin.index') }}">Home</a></li>
        <li class="active">Classes</li>
    </ol>
</div>

<div class="cl-mcont">
    <nav class="toolbar-nav">
        <a class="btn btn-lg btn-rad" href="{{ URL::route('admin.classes.create') }}">
            <i class="fa fa-plus-square"></i>&nbsp;Create a New Class
        </a>
    </nav>

    <div class="block-flat no-padding">
        <div class="content">
            <table class="users-table no-border blue" id="classes">
                <thead class="no-border">
                <tr>
                    <th style="min-width: 300px; width: 15%; font-weight: bold;">Class</th>
                    @if(!$isTeacher)
                        <th style="min-width: 250px; width: 15%; font-weight: bold;">Teacher</th>
                        <th style="min-width: 250px; width: 15%; font-weight: bold;">School</th>
                    @endif
                    <th style="min-width: 300px; width: 15%; font-weight: bold; text-align: center;">Students</th>
                    <th style="min-width: 350px; width: 20%; font-weight: bold; text-align: center;">Lessons</th>
                    <th style="min-width: 250px; width: 15%; font-weight: bold; text-align: center;">Classes</th>
                </tr>
                </thead>
                <tbody class="no-border-x">
                </tbody>
            </table>
        </div>
    </div>
</div>

@section('scripts')
    <script>
        $(document).ready(function () {
            var data_table = $('#classes').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": "{{ URL::to('admin/datatables/classes') }}",
                "columns": [
                    {data: 'name', name: 'school_classes.name'},
                        @if(!$isTeacher)
                    {data: 'teacher', name: 'users.last_name'},
                    {data: 'school', name: 'schools.name'},
                        @endif
                    {data: 'students', name: 'students', orderable: false, searchable: false},
                    {data: 'lessons', name: 'lessons', orderable: false, searchable: false},
                    {data: 'classes', name: 'classes', orderable: false, searchable: false}
                ]
            });
            $('body').on('click', '[data-action="remove"]', function () {
                if (!confirm('Important: Deleting a class will remove all class information from your roster! \r\nAre you sure you want to proceed?')) {
                    return false;
                }

                $.post($(this).attr('href'), {'_method': 'DELETE'})
                        .done(function () {
                            data_table.draw();
                        });
                return false;
            });
        });
    </script>
@stop
