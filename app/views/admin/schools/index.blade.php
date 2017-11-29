<div class="page-head">
    <h2 class="orange_color">Schools</h2>
    <div class='help-block'>
        This screen allows you to add new schools. You can also see a list of school administrators that have been
        assigned to a particular school.<br/><br/>

        To edit a School, click on the school name or click "Action" and then "edit school." Schools can also be removed
        from the "Action" button.<br/><br/>

        When editing a school, pay special attention to the "Available Products" screen. From there you can set the
        school access to the various products available from Career Services Publishing.<br/><br/>

        Clicking on a school administrator's name brings you to the screen where you may edit the user information and
        login credentials. If you change their username and password, be sure to send it to them. You may also change
        their status as a school administrator from that screen.
    </div>
    <ol class="breadcrumb">
        <li><a href="{{ URL::route('admin.index') }}">Home</a></li>
        <li class="active">Schools</li>
    </ol>
</div>

<div class="cl-mcont">
    <nav class="toolbar-nav">
        <a class="btn btn-lg btn-rad" href="{{ URL::route('admin.schools.create') }}">
            <i class="fa fa-plus-square"></i>&nbsp;Add a New School
        </a>
    </nav>

    <div class="block-flat no-padding">
        <div class="content">
            <table class="users-table no-border blue" id="schools">
                <thead class="no-border">
                <tr>
                    <th style="width: 30%;">School</th>
                    <th style="width: 30%;">School District</th>
                    <th style="width: 30%;">Administrators</th>
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
    <script>

        var data_table = $('#schools').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": "{{ URL::to('admin/datatables/schools') }}",
            "columns": [
                {data: 'school', name: 'schools.name'},
                {data: 'district', name: 'school_district.name'},
                {data: 'admins', name: 'admins', orderable: false, searchable: false},
                {data: 'actions', name: 'actions', orderable: false, searchable: false}
            ]
        });

        $('body').on('click', '[data-action="remove"]', function () {
            if (!confirm('Are you sure you want to delete this school?')) {
                return false;
            }

            var row = $(this).parents('tr');
            $.get($(this).attr('href'), {'_method': 'DELETE'}).fail(function () {
                        alert('Whoops something went wrong!!');
                    })
                    .done(function () {
                        data_table.draw();
                    });
            return false;
        });

    </script>
@stop