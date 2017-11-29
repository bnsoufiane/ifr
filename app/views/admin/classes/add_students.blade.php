<div class="page-head">
    <h2 class="orange_color">Classes: Add Student</h2>
    <div class='help-block'>
        <strong>Step 2:</strong> To enroll students in a Class, enter the last name, first name, and username for each student.
        Usernames require a minimum of five characters. For example, Cassie Barnes at Conestoga High School could have cbarnes.conestogahs
        as a username. Click <strong>Delete</strong> in the last column to remove a student’s name you entered by mistake.<br/><br/>

        You may add several students at a time by clicking <strong>Create a New Student</strong> as many times as you like. To avoid losing student information,
        click the <strong>Save Student Roster</strong> button often.  After your last Save, return to the main Classes page
        to advance to Step 3 and select Required Lessons.

        <?php
            $currentUser = \Sentry::getUser();
            $isTeacher = $currentUser->isTeacher();
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
                        $input = \Input::old();
                        $rows_count = (int)(count($input)/4);
                    ?>
                    <table class="add_new_student_table" style="width:100%; ">
                       <tr>
                          <th>Last Name</th>
                          <th>First Name</th>
                          <th>Username</th>
                           <th>Email</th>
                          <th>Delete</th>
                      </tr>
                      <?php
                        if(count($input)){
                            for($i=1; $i<=$rows_count; $i++){
                            ?>
                                <tr>
                                    <td><input id="last_name_{{$i}}" class="form-control" type="text" name="last_name_{{$i}}" value="{{empty($input['last_name_'.$i])?'':$input['last_name_'.$i]}}">
                                        @if ($errors->has('last_name_'.$i))
                                          <span class="help-block">{{ preg_replace( '/[0-9]/', '', $errors->first('last_name_'.$i), 1 ) }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <input id="first_name_{{$i}}" class="form-control" type="text" name="first_name_{{$i}}" value="{{empty($input['first_name_'.$i])?'':$input['first_name_'.$i]}}">
                                        @if ($errors->has('first_name_'.$i))
                                          <span class="help-block">{{ preg_replace( '/[0-9]/', '', $errors->first('first_name_'.$i), 1 ) }}</span>
                                        @endif
                                    </td>
                                    <td><input id="username_{{$i}}" class="form-control" type="text" name="username_{{$i}}" value="{{empty($input['username_'.$i])?'':$input['username_'.$i]}}">
                                        @if ($errors->has('username_'.$i))
                                          <span class="help-block">{{ preg_replace( '/[0-9]/', '', $errors->first('username_'.$i), 1 ) }}</span>
                                        @endif
                                    </td>
                                    <td><input id="email_{{$i}}" class="form-control" type="text" name="email_{{$i}}" value="{{empty($input['email_'.$i])?'':$input['email_'.$i]}}">
                                        @if ($errors->has('email_'.$i))
                                            <span class="help-block">{{ preg_replace( '/[0-9]/', '', $errors->first('email_'.$i), 1 ) }}</span>
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
                                    <span class="help-block">{{ preg_replace( '/[0-9]/', '', $errors->first('last_name_1'), 1 ) }}</span>
                                @endif
                            </td>
                            <td>
                                <input id="first_name_1" class="form-control" type="text" name="first_name_1" value="{{empty($input['first_name_1'])?'':$input['first_name_1']}}">
                                @if ($errors->has('first_name_1'))
                                  <span class="help-block">{{ preg_replace( '/[0-9]/', '', $errors->first('first_name_1'), 1 ) }}</span>
                                @endif
                            </td>
                            <td><input id="username_1" class="form-control" type="text" name="username_1" value="{{empty($input['username_1'])?'':$input['username_1']}}">
                                @if ($errors->has('username_1'))
                                  <span class="help-block">{{ preg_replace( '/[0-9]/', '', $errors->first('username_1'), 1 ) }}</span>
                                @endif
                            </td>
                             <td><input id="email_1" class="form-control" type="text" name="email_1" value="{{empty($input['email_1'])?'':$input['email_1']}}">
                                 @if ($errors->has('username_1'))
                                     <span class="help-block">{{ preg_replace( '/[0-9]/', '', $errors->first('email_1'), 1 ) }}</span>
                                 @endif
                             </td>
                            <td style="text-align: center;"><a class="remove_row" href="javascript:void(0);"><i class="fa fa-minus-circle"></i></a></td>
                          </tr>
                        <?php
                        }
                      ?>

                    </table>
                    <a class="add_new_student" href="javascript:void(0);" style="padding-left: 10px; margin-bottom: 15px;">Create New Student</a>
                    <a class="import_students" href="javascript:void(0);" class_id="{{$class->id}}" style="padding-left: 10px; margin-bottom: 15px;">Import Students</a>
                </div>


                <button class="btn btn-primary" type="submit">Save Student Roster</button>
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
