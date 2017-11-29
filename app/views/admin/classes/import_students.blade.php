<div class="page-head">
    <h2 class="orange_color">Classes: Import Student</h2>
</div>

<div class="cl-mcont" style="padding: 6px 0 0 0;">

    <div class="block-flat no-padding">
        <div class="content">
            <form role="form">

                <div class="dropzone" data-upload-extensions="csv,xls,xlsx" style="padding: 15px 8px 5px 20px">
                    <div class="btn btn-md btn-success btn-rad">Choose file</div>
                    <input type="hidden" name="imported_file" rv-value="model:imported_file"/>
                </div>

            </form>


            <div class="form-group list-component" id="imported-students" style="display: none">

                <table class="add_new_student_table" style="width:100%; ">
                    <tr>
                        <th>Last Name</th>
                        <th>First Name</th>
                        <th>Username</th>
                        <th>Email</th>
                    </tr>

                </table>

                <br/><br/>
                <button class="btn btn-primary add_imported_students" class_id="{{$class->id}}" type="submit">Add Students</button>
                <a class="btn btn-default cancel_importing_students" name="cancel" value="cancel">Cancel Import</a>
            </div>

        </div>
    </div>
</div>


@section('scripts')
{{{ javascript_include_tag('admin/classes_include') }}}

<script>
    $('#classes').dataTable({
        paging: true,
        columns: [
            null,
        ],
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
</script>
@stop
