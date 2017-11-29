<div class="page-head">
    <h2 class="orange_color">{{ $title }}</h2>
    <?php
//    $currentUser = \Sentry::getUser();
    $isTeacher = $currentUser->isTeacher();
    if($title == "Users"){
    ?>
    <div class='help-block'>
        As a system administrator, this screen gives you the ability to add or remove users that will access the course
        as either a fellow administrator, teacher, or student. You can also export them as a list in excel or pdf format
        from the buttons on the right.<br/><br/>

        To edit a current user, click "Actions" on the right side.

    </div>
    <?php
    }else if ($title == "Students"){
    ?>
    <div class='help-block'>
        This tab allows you to view a master list of all of your students and their assigned classes. Clicking the
        <strong>Action</strong> button allows you to <strong>Edit</strong> or <strong>Delete</strong> a student.
        Plus, if a student needs to repeat a portion of the program, you may click <strong>Reset Lesson, Reset Series,
        </strong> or <strong>Reset Post-Test.</strong>

        <?php
        $teacher_classes = SchoolClass::select("id")->where('created_by', '=', $currentUser->id)->get();
        $teacher_classes_ids = array();
        foreach ($teacher_classes as $teacher_class) {
            $teacher_classes_ids[] = $teacher_class->id;
        }
        ?>
    </div>
    <?php
    }else if ($title == "Teachers"){
    ?>
    <div class='help-block'>
        This screen allows you to add new Teachers. Click "Add a New Teacher" to create a new user. This will bring you
        to the teacher creation screen. Add the teacher's name and pay special attention to their login username and
        password as this will be needed for them to login to the teacher's admin panel.
        You may also assign a Teacher to certain schools from that screen.<br/><br/>

        Under the Teacher's tab, you can see a list of teachers and the schools to which they are assigned. The "Action"
        button allows you to Edit a teacher or remove a teacher.<br/><br/>

        Clicking on a school will bring you to the screen to edit that particular school.
    </div>
    <?php
    }

    ?>

    <ol class="breadcrumb">
        <li><a href="{{ URL::route('admin.index') }}">Home</a></li>
        <li class="active">{{ $title }}</li>
    </ol>
</div>

<div class="cl-mcont">
    <?php
    if($singularTitle != "Student"){
    ?>
    <nav class="toolbar-nav">
        <a class="btn btn-lg btn-rad" href="{{ URL::route($baseRoute . '.create') }}"><i class="fa fa-plus-square"></i>&nbsp;Add
            a New {{ $singularTitle }}</a>
    </nav>
    <?php
    }
    ?>

    <input type="hidden" name="page_title"
           value='{{ (isset($page_title) && $page_title=='IFR - Teachers' )?$page_title:'' }}'/>

    <div class="block-flat no-padding">
        <div class="content">
            <table class="users-table no-border blue" id="users">
                <thead class="no-border">
                <tr>
                    <th style="width: 30%;">{{ $singularTitle }}</th>
                    @if ($isTeacher)
                        <th>Username</th>
                        <th>Classes</th>
                    @endif
                    @if ($canEditGroups)
                        <th style="width: 20%;">Groups</th>
                    @endif
                    @if ($canEditSchool)
                        <th style="width: 20%;">School</th>
                    @endif
                    @if($title=="Teachers")
                        <th style="width: 20%;">Last Login</th>
                        <th style="width: 20%;">total_students</th>
                    @endif
                    <th style="width: 10%;">Actions</th>
                </tr>
                </thead>
                <tbody class="no-border-x">
                @foreach ($users as $user)
                    @if($isTeacher || !$user->isStudent())
                        <tr>
                            <td class="student_full_name">
                                <a href="{{ URL::route($baseRoute . '.edit', $user->id) }}">{{ $user->last_name }}
                                    , {{ $user->first_name }}</a>
                            </td>
                            @if ($isTeacher)
                                <td>
                                    <a href="{{ URL::route($baseRoute . '.edit', $user->id) }}">
                                        {{ $user->username }}
                                    </a>
                                </td>
                                <td>
                                    <?php
                                    $user_classes = \DB::table('school_class_student')->where('student_id', '=', $user->id)->get();
                                    $user_classes_ids = array();
                                    foreach ($user_classes as $user_class) {
                                        in_array($user_class->school_class_id, $teacher_classes_ids);
                                        $user_classes_ids[] = $user_class->school_class_id;
                                    }

                                    $array_inter = array_intersect($teacher_classes_ids, $user_classes_ids);
                                    if (count($array_inter) > 0) {
                                        $i = 0;
                                        $n = count($array_inter);

                                        foreach ($array_inter as $item) {
                                            $i++;
                                            $cls = SchoolClass::select("name")->where('id', '=', $item)->first();
                                            if ($i < $n) {
                                                echo ($cls->name) . ", ";
                                            } else {
                                                echo($cls->name);
                                            }
                                        }
                                        //var_dump(array_intersect($teacher_classes_ids, $user_classes_ids));
                                    }

                                    ?>
                                </td>
                            @endif
                            @if ($canEditGroups)
                                <td>
                                    {{ implode(', ', $user->getGroupsList()) }}
                                </td>
                            @endif

                            @if ($canEditSchool)
                                <td>
                                    @if ($user->school !== null)
                                        {{ link_to_route('admin.schools.edit', $user->school->name, $user->school->id) }}
                                    @else
                                        <span class="text-muted">No School</span>
                                    @endif
                                </td>
                            @endif
                            @if($title=="Teachers")
                                <td>
                                    {{$user->last_login }}
                                </td>
                                <td>
                                    {{$user->studentsCount() }}
                                </td>
                            @endif

                            <td>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-primary dropdown-toggle"
                                            data-toggle="dropdown">
                                        Action <span class="caret"></span>
                                    </button>
                                    <ul role="menu" class="dropdown-menu pull-right">
                                        <li>
                                            <a href="{{ URL::route($baseRoute . '.edit', $user->id) }}">Edit {{ $singularTitle }}</a>
                                        </li>
                                        <li><a href="{{ URL::route($baseRoute . '.destroy', $user->id) }}"
                                               data-action="remove">Delete {{ $singularTitle }}</a></li>
                                        @if ($canEditSchool == false)
                                            <li><a target="_blank"
                                                   href="{{ URL::route('admin.students.show_lessons', $user->id) }}">Reset
                                                    Lesson</a></li>
                                            <li class="series_to_reset">
                                                <a class="add-new" href="javascript:void(0);"
                                                   student_id="{{$user->id }}">Reset Series</a>
                                            </li>
                                            <li><a href="{{ URL::route('admin.students.reset_posttest', $user->id) }}">Reset
                                                    Post-test</a></li>
                                        @endif
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @endif

                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@section('scripts')

    {{ javascript_include_tag('admin/reset_series_include') }}

    <script type="text/javascript">
        $(function () {
            // Add remove function to table items.
            $('body').on('click', '[data-action="remove"]', function () {
                if (!confirm('Are you sure you want to delete this user?')) {
                    return false;
                }

                var row = $(this).parents('tr');
                $.post($(this).attr('href'), {'_method': 'DELETE'})
                        .done(function () {
                            row.remove();
                        });
                return false;
            });

            $('.users-table').dataTable({
                paging: true,
                columns: [
                    null,
                    @if ($canEditSchool)
                            null,
                    @endif
                            @if ($canEditGroups)
                            null,
                    @endif
                            @if ($title == "Teachers")
                            null, null,
                        @endif
                    {
                        orderable: false
                    }
                ],
                dom: 'T<"clear">lfrtip',
                tableTools: {
                    "sSwfPath": "{{ asset('copy_csv_xls_pdf.swf') }}",
                    "aButtons": [
                        {
                            "sExtends": "copy",
                            "mColumns": ($('.page-head h2').html() == "Teachers") ? [0, 1, 2, 3] : (($('.page-head h2').html() == "Users") ? [0, 1, 2] : [0, 1])
                        },
                        {
                            "sExtends": "csv",
                            "sButtonText": "Export Excel",
                            "sTitle": $('input[name="page_title"]').val(),
                            "mColumns": @if ($canEditSchool)
                            ($('.page-head h2').html() == "Teachers") ? [0, 1, 2, 3] : (($('.page-head h2').html() == "Users") ? [0, 1, 2] : [0, 1])
                                    @else
                                    [0]
                            @endif
                        },
                        {
                            "sExtends": "pdf",
                            "sButtonText": "Export PDF",
                            "mColumns": ($('.page-head h2').html() == "Teachers") ? [0, 1, 2, 3] : (($('.page-head').html() == "Teachers") ? [0, 1, 2] : [0, 1])
                        }
                    ]
                }
            });
        });
    </script>
@stop
