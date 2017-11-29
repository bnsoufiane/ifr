{{{ Form::model($series, array('route' => $route, 'method' => isset($method) ? $method : 'POST')) }}}
<form role="form">
    <div class="form-group @if ($errors->has('title')) has-error @endif">
		{{{ Form::label('title', 'Series\' Title', array('class' => 'control-label')) }}}
        {{{ Form::text('title', null, array('class' => 'form-control', 'placeholder' => 'Enter title')) }}}

        @if ($errors->has('title'))
        <span class="help-block">{{ $errors->first('title') }}</span>
        @endif
    </div>

    {{{ Form::hidden('module_id', $module->id) }}}

    <button class="btn btn-primary" type="submit">Submit</button>
    <button class="btn btn-default" name="cancel" value="cancel">Cancel</button>
</form>
{{{ Form::close() }}}
