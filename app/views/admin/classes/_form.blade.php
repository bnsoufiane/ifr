{{{ Form::model($class, array('route' => $route, 'method' => isset($method) ? $method : 'POST')) }}}
<form role="form">
    {{{ Form::hidden('id') }}}

    <div class="form-group @if ($errors->has('name')) has-error @endif">
        {{{ Form::label('name', 'Name', array('class' => 'control-label')) }}}
        {{{ Form::text('name', null, array('class' => 'form-control', 'placeholder' => 'Enter class name')) }}}

        @if ($errors->has('name'))
        <span class="help-block">{{ $errors->first('name') }}</span>
        @endif
    </div>

    <div class="form-group @if ($errors->has('minimum_score')) has-error @endif">
        {{{ Form::label('minimum_score', 'Minimum Score', array('class' => 'control-label')) }}}
        {{{ Form::text('minimum_score', null, array('class' => 'form-control', 'placeholder' => 'Enter the minimum score for this class')) }}}

        @if ($errors->has('minimum_score'))
        <span class="help-block">{{ $errors->first('minimum_score') }}</span>
        @endif
    </div>

    <div class="form-group @if ($errors->has('default_password')) has-error @endif">
        {{{ Form::label('default_password', 'Default Password', array('class' => 'control-label')) }}}
        {{{ Form::text('default_password', null, array('class' => 'form-control', 'placeholder' => 'Enter the default password for this class')) }}}

        @if ($errors->has('default_password'))
        <span class="help-block">{{ $errors->first('default_password') }}</span>
        @endif
    </div>

    @if ($isSysAdminUser || $isSchoolAdminUser)
    <div class="form-group @if ($errors->has('name')) has-error @endif">
        {{{ Form::label('teacher_id', 'Teacher', array('class' => 'control-label')) }}}
        {{{ Form::select('teacher_id', $teachers, isset($default_teacher)?$default_teacher:null, array('class' => 'form-control')) }}}

        @if ($errors->has('teacher_id'))
        <span class="help-block">{{ $errors->first('teacher_id') }}</span>
        @endif
    </div>
    @endif

    @if ($isSysAdminUser || $isSchoolAdminUser)
        <div class="form-group list-component" id="class-students">
            {{{ Form::label('students', 'Students', array('class' => 'control-label')) }}}

            <ul></ul>

            <!-- <a class="add-new" href="javascript:void(0);">Add Existing Student</a> -->
            <table class="add_new_student_table" style="width:100%; display: none;">
               <tr>
                  <th>First Name</th>
                  <th>Last Name</th>
                  <th>Username</th>

                  <th>Delete</th>
              </tr>
            </table>
            <a class="add_new_student" href="javascript:void(0);" style="padding-left: 10px; margin-bottom: 15px;">Create New Student</a>
            @if($class->id)
                <a class="import_students" href="javascript:void(0);" class_id="{{$class->id}}" style="padding-left: 10px; margin-bottom: 15px;">Import Students</a>
            @endif
        </div>
    @endif

    @if (!$isSysAdminUser)
        <div id="cant_remove" style="display: none; font-size: 1.3em">
            Series cannot be removed from a class after the pre- post-tests have been saved.
        </div>

        <div class="form-group list-component @if ($errors->has('module_id')) has-error @endif" id="school-modules">
            {{{ Form::label('modules', 'It’s for Real Workplace Ethics Series', array('class' => 'control-label')) }}}
            <?php $can_remove_class= (isset($can_remove_class) && !$can_remove_class)?'class="cant_remove"':''; ?>

            <ul {{{$can_remove_class}}}></ul>

            @if ($errors->has('module_id'))
                <span class="help-block">Please select a Series</span>
            @endif

            <a class="add-new" href="javascript:void(0);">Add Series</a>
        </div>
    @endif

    <button class="btn btn-primary" type="submit">Save Class</button>
    <button class="btn btn-default" name="cancel" value="cancel">Cancel</button>
</form>
{{{ Form::close() }}}

@section('scripts')
{{{ javascript_include_tag('admin/classes_include') }}}
@stop
