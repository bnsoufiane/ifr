<div class="page-head">
    <h2 class="orange_color">Products</h2>
    <div class='help-block'>
        Select a product from the list below
    </div>
    <ol class="breadcrumb">
        <li><a href="{{ URL::route('admin.index') }}">Home</a></li>
        <li><a href="{{ URL::route('admin.tests.index') }}">{{$class->name}}</a></li>
        <li class="active">Products</li>
    </ol>
</div>

<div class="cl-mcont">
    <div class="block-flat no-padding">
        <div class="content">
            <table class="class-grades-table no-border blue">
                <thead class="no-border">
                    <tr>
                        <th style="width: 60%;">Products List</th>
                    </tr>
                </thead>
                <tbody class="no-border-x">
                    @foreach ($modules as $module)
                    <tr>
                        <td class="student-name">
                            <a href="{{ URL::route('admin.tests.series', array($class->id, $module->id)) }}">
                                {{ $module->title }}
                            </a>
                        </td>

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
            columns: [null, ],
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
