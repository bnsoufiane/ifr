{{{ Form::model($school, array('route' => $route, 'method' => isset($method) ? $method : 'POST')) }}}
<form role="form">
    {{{ Form::hidden('id') }}}

    <div class="form-group @if ($errors->has('name')) has-error @endif">
        {{{ Form::label('name', 'Name', array('class' => 'control-label')) }}}
        {{{ Form::text('name', null, array('class' => 'form-control', 'placeholder' => 'Enter school name')) }}}

        @if ($errors->has('name'))
        <span class="help-block">{{ $errors->first('name') }}</span>
        @endif
    </div>

    <button class="btn btn-primary" type="submit">Submit</button>
    <a href="{{ URL::previous() }}" class="btn btn-default" name="cancel" value="cancel">Cancel</a>
</form>
{{{ Form::close() }}}

@section('scripts')
{{{ javascript_include_tag('admin/schools_include') }}}
@stop
