{{{ Form::model($user, array('route' => $route, 'method' => isset($method) ? $method : 'POST', 'autocomplete'=>'off')) }}}
<form role="form">
    {{{ Form::hidden('id') }}}

    <div class="form-group @if ($errors->has('first_name')) has-error @endif">
        {{{ Form::label('first_name', 'First Name', array('class' => 'control-label')) }}}
        {{{ Form::text('first_name', null, array('class' => 'form-control', 'placeholder' => 'Enter first name')) }}}

        @if ($errors->has('first_name'))
        <span class="help-block">{{ $errors->first('first_name') }}</span>
        @endif
    </div>

    <div class="form-group @if ($errors->has('last_name')) has-error @endif">
        {{{ Form::label('last_name', 'Last Name', array('class' => 'control-label')) }}}
        {{{ Form::text('last_name', null, array('class' => 'form-control', 'placeholder' => 'Enter last name')) }}}

        @if ($errors->has('last_name'))
        <span class="help-block">{{ $errors->first('last_name') }}</span>
        @endif
    </div>

    <div class="form-group @if ($errors->has('username')) has-error @endif">
        {{{ Form::label('username', 'Username', array('class' => 'control-label')) }}}
        {{{ Form::text('username', null, array('class' => 'form-control', 'placeholder' => 'Enter username', 'required' => 'required')) }}}

        @if ($errors->has('username'))
        <span class="help-block">{{ $errors->first('username') }}</span>
        @endif
    </div>

    <!-- fake fields are a workaround for chrome autofill getting the wrong fields -->
    <input style="position: absolute; top: -9999px; left: -9999px;" type="text" name="fakeusernameremembered" tabindex="-1"/>
    <input style="position: absolute; top: -9999px; left: -9999px;" type="password" name="fakepasswordremembered" tabindex="-1"/>

    <div class="form-group @if ($errors->has('email')) has-error @endif">
        {{{ Form::label('email', 'Email', array('class' => 'control-label')) }}}
        {{{ Form::text('email', null, array('class' => 'form-control', 'placeholder' => 'Enter email')) }}}

        @if ($errors->has('email'))
            <span class="help-block">{{ $errors->first('email') }}</span>
        @endif
    </div>


@if ($user->id)
    <div class="form-group @if ($errors->has('password')) has-error @endif">
        {{{ Form::label('password', 'Change password', array('class' => 'control-label')) }}}
        {{{ Form::password('password', array('class' => 'form-control', 'placeholder' => 'Enter a new password if you want to change it')) }}}

        @if ($errors->has('password'))
        <span class="help-block">{{ $errors->first('password') }}</span>
        @endif
    </div>
    @else
    <div class="form-group @if ($errors->has('password')) has-error @endif">
        {{{ Form::label('password', 'Password', array('class' => 'control-label')) }}}
        {{{ Form::password('password', array('class' => 'form-control', 'placeholder' => 'Enter password', 'required' => 'required')) }}}

        @if ($errors->has('password'))
        <span class="help-block">{{ $errors->first('password') }}</span>
        @endif
    </div>
    @endif

    @if ($canEditSchool)
    <div class="form-group @if ($errors->has('name')) has-error @endif">
        {{{ Form::label('school_id', 'School', array('class' => 'control-label')) }}}
        <a target="_blank" href="{{ URL::route('admin.schools.create') }}" >Create a new School</a>
        {{{ Form::select('school_id', $schools, null, array('class' => 'form-control')) }}}
        <a class="refresh_school_list" href="javascript:void(0);" >Refresh list</a>

        @if ($errors->has('school_id'))
        <span class="help-block">{{ $errors->first('school_id') }}</span>
        @endif
    </div>
    @endif

    @if ($canEditGroups)
    <div class="form-group">
        {{{ Form::label('groups', 'Groups', array('class' => 'control-label')) }}}

        <div class="groups" id="user-groups-list" style="margin-top: -20px;">

        </div>
    </div>
    @endif

    <button class="btn btn-primary" type="submit">Submit</button>
    <button class="btn btn-default" name="cancel" value="cancel">Cancel</button>
</form>
{{{ Form::close() }}}

@section('scripts')
@if ($canEditGroups)
<!-- Including scripts to handle user's groups -->
<script type="text/javascript">
    var AVAILABLE_GROUPS = {{{ json_encode($groups) }}};
</script>
@endif

{{ javascript_include_tag('admin/users_include') }}


<script type="text/javascript">
    $(function() {

        $('.refresh_school_list').on('click', function(e) {
            /*var data = {
             "students_id": students_id
             }*/

            $.ajax({
                url: '../schools/schools_list',
                type: 'get',
                dataType: 'json'
                        /*data: data*/
            }).success(function(result) {
                $('select#school_id option').remove();

                for (var key in result) {
                    if (!result.hasOwnProperty(key)) continue;
                    console.log(key, result[key]);

                    $('<option value="'+key+'">'+result[key]+'</option>').prependTo($('select#school_id'));

                 }
            }).error(function(err) {
            });

        });


    });
</script>

@stop
