<div class="page-head">
    <h2 class="orange_color">List of Teachers</h2>
    <div class='help-block'>
        From this screen you can check the Scores & Reports for the various students taking the products. First, select
        a teacher to see a list of classes assigned to that teacher.<br/><br/>

        Next pick the class you would like reports for, followed by the product, series, and lesson. <br/><br/>

        You will then see a list of students, their current progress on that lesson and the score they achieved if they
        have finished.

    </div>
    <ol class="breadcrumb">
        <li><a href="{{ URL::route('admin.index') }}">Home</a></li>
        <li class="active">Teachers</li>
    </ol>
</div>

<div class="cl-mcont">
    <div class="block-flat no-padding">
        <div class="content">
            <table class="class-grades-table no-border blue">
                <thead class="no-border">
                <tr>
                    <th style="width: 40%;">Teachers List<span
                                style="display: inline; padding-left: 10px; font-size: 13px" class='help-inline'>Select a teacher to view classes for that teacher.</span>
                    </th>
                    <th style="width: 60%;">School</th>
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
        $(document).ready(function () {
            var data_table = $('table').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": "{{ URL::to('admin/datatables/teachers-report') }}",
                "columns": [
                    {data: 'last_name', name: 'users.last_name'},
                    {data: 'school', name: 'schools.name'}
                ]
            });
        })
    </script>
@stop
