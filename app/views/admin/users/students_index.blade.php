<div class="page-head">
    <h2 class="orange_color">{{ $title }}</h2>
    <div class='help-block'>
        This tab allows you to view a master list of all of your students and their assigned classes. Clicking the
        <strong>Action</strong> button allows you to <strong>Edit</strong> or <strong>Delete</strong> a student.
        Plus, if a student needs to repeat a portion of the program, you may click <strong>Reset Lesson, Reset Series,
        </strong> or <strong>Reset Post-Test.</strong>
    </div>
    <ol class="breadcrumb">
        <li><a href="{{ URL::route('admin.index') }}">Home</a></li>
        <li class="active">{{ $title }}</li>
    </ol>
</div>

<div class="cl-mcont">

    <input type="hidden" name="page_title" value=''/>

    <div class="block-flat no-padding">
        <div class="content">
            <table class="users-table no-border blue" id="users">
                <thead class="no-border">
                <tr>
                    <th style="width: 30%;">{{ $singularTitle }}</th>
                    <th>Username</th>
                    <th>Classes</th>
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
                "ajax": "{{ URL::to('admin/datatables/students') }}",
                "columns": [
                    {data: 'last_name', name: 'users.last_name'},
                    {data: 'username', name: 'users.username'},
                    {data: 'class', name: 'school_classes.name'},
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