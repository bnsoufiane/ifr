<div class="page-head">
    <h2 class="orange_color">{{ $title }}</h2>
    <div class='help-block'>
        This screen allows you to add new Teachers. Click "Add a New Teacher" to create a new user. This will bring you
        to the teacher creation screen. Add the teacher's name and pay special attention to their login username and
        password as this will be needed for them to login to the teacher's admin panel.
        You may also assign a Teacher to certain schools from that screen.<br/><br/>

        Under the Teacher's tab, you can see a list of teachers and the schools to which they are assigned. The "Action"
        button allows you to Edit a teacher or remove a teacher.<br/><br/>

        Clicking on a school will bring you to the screen to edit that particular school.
    </div>
    <ol class="breadcrumb">
        <li><a href="{{ URL::route('admin.index') }}">Home</a></li>
        <li class="active">{{ $title }}</li>
    </ol>
</div>

<div class="cl-mcont">
    <nav class="toolbar-nav">
        <a class="btn btn-lg btn-rad" href="{{ URL::route($baseRoute . '.create') }}"><i class="fa fa-plus-square"></i>&nbsp;Add
            a New {{ $singularTitle }}</a>
    </nav>

    <input type="hidden" name="page_title" value='{{ $page_title }}'/>

    <div class="block-flat no-padding">
        <div class="content">
            <table class="users-table no-border blue" id="users">
                <thead class="no-border">
                <tr>
                    <th style="width: 30%;">{{ $singularTitle }}</th>
                    <th style="width: 20%;">School</th>
                    <th style="width: 20%;">Last Login</th>
                    <th style="width: 20%;">total_students</th>
                    <th style="width: 10%;">Actions</th>
                </tr>
                </thead>
                <tbody class="no-border-x">
                </tbody>
            </table>
        </div>
    </div>
</div>


@section('scripts')

    {{ javascript_include_tag('admin/reset_series_include') }}

    <script type="text/javascript">
        $(document).ready(function () {
            var data_table = $('.users-table').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": "{{ URL::to('admin/datatables/teachers') }}",
                "columns": [
                    {data: 'last_name', name: 'users.last_name'},
                    {data: 'school', name: 'schools.name'},
                    {data: 'last_login', name: 'users.last_login'},
                    {data: 'count', name: 'count', orderable: false, searchable: false},
                    {data: 'actions', name: 'actions', orderable: false, searchable: false}
                ],
                dom: 'T<"clear">lfrtip',
                tableTools: {
                    "sSwfPath": "{{ asset('copy_csv_xls_pdf.swf') }}",
                    "aButtons": [
                        {
                            "sExtends": "copy",
                            "mColumns": [0, 1, 2, 3]
                        },
                        {
                            "sExtends": "csv",
                            "sButtonText": "Export Excel",
                            "sTitle": '',
                            "mColumns": [0, 1, 2, 3]
                        },
                        {
                            "sExtends": "pdf",
                            "sButtonText": "Export PDF",
                            "mColumns": [0, 1, 2, 3]
                        }
                    ]
                }
            });

            $('body').on('click', '[data-action="remove"]', function () {
                if (!confirm('Are you sure you want to delete this user?')) {
                    return false;
                }

                var row = $(this).parents('tr');
                $.post($(this).attr('href'), {'_method': 'DELETE'})
                        .done(function () {
                            data_table.draw();
                        });
                return false;
            });
        })
    </script>
@stop