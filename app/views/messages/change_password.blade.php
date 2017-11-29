@extends('messages.base_layout')

@section('content')
    <fieldset>
        <h2>Welcome to <em>It’s for Real Workplace Ethics</em>. To begin, enter a new password that you will remember,
            then confirm the password and click the Submit button.</h2>
    </fieldset>

    <fieldset>
    <div class="change_password">
        <fieldset>
                Please enter a unique password.<br/>
                Password must be a minimum of 5 characters and can contain letters, numbers and special characters.
            {{{ Form::model(null, array('route' => $route, 'method' => 'POST')) }}}
                <br/><br/>

                <input type="hidden" name="test" value="{{$default_pass}}" id="default_password" />

                <div class="form-group @if ($errors->has('new_password')) has-error @endif">
                    {{{ Form::label('new_password', 'New Password', array('class' => 'control-label')) }}}
                    {{{ Form::password('new_password', null, array('class' => 'form-control', 'placeholder' => 'Enter the new password')) }}}

                    @if ($errors->has('new_password'))
                    <span class="help-block">{{ $errors->first('new_password') }}</span>
                    @endif
                </div>
                <br/>

                <div class="form-group @if ($errors->has('new_password_confirmation')) has-error @endif">
                    {{{ Form::label('new_password_confirmation', 'Confirm Password', array('class' => 'control-label')) }}}
                    {{{ Form::password('new_password_confirmation', null, array('class' => 'form-control', 'placeholder' => 'Confirm the new password')) }}}

                    @if ($errors->has('new_password_confirmation'))
                    <span class="help-block">{{ $errors->first('new_password_confirmation') }}</span>
                    @endif
                </div>

                <button type="submit" class="continueBtn" tabindex="-1">
                    <span>Submit</span>
                </button>
            {{{ Form::close() }}}
        </fieldset>

    </div>
    </fieldset>
@stop
