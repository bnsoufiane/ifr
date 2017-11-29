<div class="page-head">
    <h2 class="orange_color">{{ $title }}</h2>
    <div class='help-block'>
        As a system administrator, this screen gives you the ability to add or remove users that will access the course
        as either a fellow administrator, teacher, or student. You can also export them as a list in excel or pdf format
        from the buttons on the right.<br/><br/>

        To edit a current user, click "Actions" on the right side.

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

    <div class="block-flat no-padding">
        <div class="content">
            <table class="users-table no-border blue" id="users">
                <thead class="no-border">
                <tr>
                    <th style="width: 30%;">{{ $singularTitle }}</th>
                    <th style="width: 20%;">Groups</th>
                    <th style="width: 20%;">School</th>
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
                "ajax": "{{ URL::to('admin/datatables/users') }}",
                "columns": [
                    {data: 'last_name', name: 'users.last_name'},
                    {data: 'group', name: 'groups.name'},
                    {data: 'school', name: 'schools.name'},
                    {data: 'actions', name: 'actions', orderable: false, searchable: false}
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
                            "sExtends": "csv",
                            "sButtonText": "Export Excel",
                            "sTitle": '',
                            "mColumns": [0, 1, 2]
                        },
                        {
                            "sExtends": "pdf",
                            "sButtonText": "Export PDF",
                            "mColumns": [0, 1]
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