{{{ Form::model($module, array('route' => $route, 'method' => isset($method) ? $method : 'POST')) }}}
<form role="form">
    <div class="form-group @if ($errors->has('title')) has-error @endif">
        {{{ Form::label('title', 'Product\'s Title', array('class' => 'control-label')) }}}
        {{{ Form::text('title', null, array('class' => 'form-control', 'placeholder' => 'Enter title')) }}}

        @if ($errors->has('title'))
        <span class="help-block">{{ $errors->first('title') }}</span>
        @endif
    </div>

    <div class="form-group">
        {{{ Form::label('skin', 'Skin', array('class' => 'control-label')) }}}
        {{{ Form::select('skin', $skins, null, array('class' => 'form-control')) }}}
    </div>

    <button class="btn btn-primary" type="submit">Submit</button>
    <button class="btn btn-default" name="cancel" value="cancel">Cancel</button>
</form>
{{{ Form::close() }}}
