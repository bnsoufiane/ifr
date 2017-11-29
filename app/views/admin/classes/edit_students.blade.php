<div class="page-head">
    <h2 class="orange_color">Students</h2>
    <div class='help-block'>
        Here you can see all of the students that have been assigned to a particular class.  You may also click "Add Student" to add a new student to the class.  Students must be created first by going to the Students screen, if you have not done so already.
        <?php 
            $currentUser = \Sentry::getUser();
            $isTeacher = $currentUser->isTeacher();
            if($isTeacher){
        ?>
            <br/><br/>The "Action" button allows you to edit or delete a class.
        <?php 
        }
        ?>
    </div>
    <ol class="breadcrumb">
        <li><a href="{{ URL::route('admin.index') }}">Home</a></li>
        <li><a href="{{ URL::route('admin.classes.index')  }}">{{$class->name}}</a></li>
        <li class="active">Add Students</li>
    </ol>
</div>

<div class="cl-mcont">

    <div class="block-flat no-padding">
        <div class="content">
            {{{ Form::model($class, array('route' => $route, 'method' => isset($method) ? $method : 'POST')) }}}
            <form role="form">
                {{{ Form::hidden('id') }}}

                <div class="form-group list-component" id="class-students">
                    <!-- <a class="add-new" href="javascript:void(0);">Add Existing Student</a> -->

                    <?php
                        if(count(\Input::old())){
                            $input = \Input::old();
                        }

                        $rows_count = 0;
                        foreach ($input as $key => $value) {
                            if (strpos($key, 'first_name') !== false) {
                                $rows_count++;
                            }
                        }
                    ?>
                    <table class="add_new_student_table" style="width:100%; ">
                       <tr>
                          <th>First Name</th>
                          <th>Last Name</th>
                          <th>Username</th>
                          <th>Password</th>
                          <th>Delete</th>
                      </tr>
                      <?php
                        if(count($input)){
                            for($i=1; $i<=$rows_count; $i++){
                            ?>
                                <tr>
                                    <td><input id="last_name_{{$i}}" class="form-control" type="text" name="last_name_{{$i}}" value="{{empty($input['last_name_'.$i])?'':$input['last_name_'.$i]}}">
                                        @if ($errors->has('last_name_'.$i))
                                          <span class="help-block">{{ $errors->first('last_name_'.$i) }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <input id="id_{{$i}}" class="form-control" name="id_{{$i}}" value="{{empty($input['id_'.$i])?'':$input['id_'.$i]}}"  type="hidden">
                                        <input id="first_name_{{$i}}" class="form-control" type="text" name="first_name_{{$i}}" value="{{empty($input['first_name_'.$i])?'':$input['first_name_'.$i]}}">
                                        @if ($errors->has('first_name_'.$i))
                                          <span class="help-block">{{ $errors->first('first_name_'.$i) }}</span>
                                        @endif
                                    </td>
                                    <td><input id="username_{{$i}}" class="form-control" type="text" name="username_{{$i}}" value="{{empty($input['username_'.$i])?'':$input['username_'.$i]}}">
                                        @if ($errors->has('username_'.$i))
                                          <span class="help-block">{{ $errors->first('username_'.$i) }}</span>
                                        @endif
                                    </td>
                                    <td><input id="pass_{{$i}}" class="form-control" type="text" name="pass_{{$i}}" value="{{empty($input['pass_'.$i])?'':$input['pass_'.$i]}}">
                                        @if ($errors->has('pass_'.$i))
                                          <span class="help-block">{{ $errors->first('pass_'.$i) }}</span>
                                        @endif
                                    </td>
                                      <td style="text-align: center;"><a class="remove_row" href="javascript:void(0);"><i class="fa fa-minus-circle"></i></a></td>
                                  </tr>
                            <?php
                            }
                        }else{
                        ?>
                         <tr>
                            <td><input id="last_name_1" class="form-control" type="text" name="last_name_1" value="{{empty($input['last_name_1'])?'':$input['last_name_1']}}">
                                @if ($errors->has('last_name_1'))
                                  <span class="help-block">{{ $errors->first('last_name_1') }}</span>
                                @endif
                             </td>
                            <td>
                                <input id="first_name_1" class="form-control" type="text" name="first_name_1" value="{{empty($input['first_name_1'])?'':$input['first_name_1']}}">
                                @if ($errors->has('first_name_1'))
                                  <span class="help-block">{{ $errors->first('first_name_1') }}</span>
                                @endif
                            </td>
                            <td><input id="username_1" class="form-control" type="text" name="username_1" value="{{empty($input['username_1'])?'':$input['username_1']}}">
                                @if ($errors->has('username_1'))
                                  <span class="help-block">{{ $errors->first('username_1') }}</span>
                                @endif
                            </td>
                            <td><input id="pass_1" class="form-control" type="text" name="pass_1" value="{{empty($input['pass_1'])?'':$input['pass_1']}}">
                                @if ($errors->has('pass_1'))
                                  <span class="help-block">{{ $errors->first('pass_1') }}</span>
                                @endif
                            </td>
                            <td style="text-align: center;"><a class="remove_row" href="javascript:void(0);"><i class="fa fa-minus-circle"></i></a></td>
                          </tr>
                        <?php
                        }
                      ?>

                    </table>
                    <a class="add_new_student" href="javascript:void(0);" style="padding-left: 10px; margin-bottom: 15px;">Create New Student</a>
                </div>


                <button class="btn btn-primary" type="submit">Save Changes</button>
                <a href="{{ URL::route('admin.classes.index') }}" class="btn btn-default" name="cancel" value="cancel">Cancel</a>
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
