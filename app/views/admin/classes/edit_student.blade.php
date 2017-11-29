<div class="page-head">
    <h2 class="orange_color">Students</h2>
    <div class='help-block'>
        <?php 
            $currentUser = \Sentry::getUser();
            $isTeacher = $currentUser->isTeacher();
        ?>
    </div>
    <ol class="breadcrumb">
        <li><a href="{{ URL::route('admin.index') }}">Home</a></li>
        <li class="active">Edit Student</li>
    </ol>
</div>

<div class="cl-mcont">

    <div class="block-flat no-padding">
        <div class="content">
            {{{ Form::model($student, array('route' => $route, 'method' => isset($method) ? $method : 'POST')) }}}
            <form role="form">
                {{{ Form::hidden('id') }}}

                <div class="form-group list-component" id="class-students">
                    <!-- <a class="add-new" href="javascript:void(0);">Add Existing Student</a> -->

                    <?php
                        if(count(\Input::old())){
                            $input = \Input::old();
                        }

                    ?>
                    <table class="add_new_student_table" style="width:100%; ">
                       <tr>
                          <th>Last Name</th>
                          <th>First Name</th>
                          <th>Username</th>
                          <th>Password</th>
                          <th>Delete</th>
                      </tr>
                         <tr>
                            <td><input id="last_name" class="form-control" type="text" name="last_name" value="{{empty($input['last_name'])?'':$input['last_name']}}">
                                @if ($errors->has('last_name'))
                                  <span class="help-block">{{ $errors->first('last_name') }}</span>
                                @endif
                             </td>
                             <td>
                                <input id="first_name" class="form-control" type="text" name="first_name" value="{{empty($input['first_name'])?'':$input['first_name']}}">
                                @if ($errors->has('first_name'))
                                  <span class="help-block">{{ $errors->first('first_name') }}</span>
                                @endif
                            </td>
                            <td><input id="username" class="form-control" type="text" name="username" value="{{empty($input['username'])?'':$input['username']}}">
                                @if ($errors->has('username'))
                                  <span class="help-block">{{ $errors->first('username') }}</span>
                                @endif
                            </td>
                            <td><input id="pass" class="form-control" type="text" name="pass" value="{{empty($input['pass'])?'':$input['pass']}}">
                                @if ($errors->has('pass'))
                                  <span class="help-block">{{ $errors->first('pass') }}</span>
                                @endif
                            </td>
                            <td style="text-align: center;"><a class="remove_row" href="javascript:void(0);"><i class="fa fa-minus-circle"></i></a></td>
                          </tr>

                    </table>
                </div>


                <button class="btn btn-primary" type="submit">Save Student</button>
                <a class="btn btn-default" name="cancel" value="cancel" href="{{ URL::route('admin.classes.students', $class->id) }}">Cancel</a>
            </form>
            {{{ Form::close() }}}
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
