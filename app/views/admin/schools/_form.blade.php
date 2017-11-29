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

    <div class="form-group list-component" id="school-districts">
        {{{ Form::label('districts', 'School Districts', array('class' => 'control-label')) }}}
        <a target="_blank" href="{{ URL::route('admin.school_districts.create') }}" >Create a new School District</a>
        {{{ Form::select('school_districts_id', $school_districts, isset($school_district)?$school_district->id:null, array('class' => 'form-control')) }}}
        <a class="refresh_school_districts_list" href="javascript:void(0);" >Refresh list</a>
    </div>

    <div class="form-group list-component" id="school-administrators">
        {{{ Form::label('administrators', 'School Administrators', array('class' => 'control-label')) }}}

        <ul></ul>

        <a class="add-new" href="javascript:void(0);">Add Existing Administrator</a>
        <a target="_blank" href="{{ URL::route('admin.users.create') }}" style="padding-left: 30px;">Create New Administrator</a>
    </div>

    <div class="form-group list-component" id="school-modules">
        {{{ Form::label('modules', 'Available Products', array('class' => 'control-label')) }}}

        <ul></ul>

        <a class="add-new" href="javascript:void(0);">Add Product</a>
    </div>

    <button class="btn btn-primary" type="submit">Submit</button>
    <button class="btn btn-default" name="cancel" value="cancel">Cancel</button>
</form>
{{{ Form::close() }}}

@section('scripts')
{{{ javascript_include_tag('admin/schools_include') }}}

<script type="text/javascript">
    $(function() {

        $('.refresh_school_districts_list').on('click', function(e) {

            $.ajax({
                url: '../schools/school_districts_list',
                type: 'get',
                dataType: 'json'
            }).success(function(result) {
                $('select[name="school_districts_id"] option').remove();

                for (var key in result) {
                    if (!result.hasOwnProperty(key)) continue;
                    console.log(key, result[key]);

                    $('<option value="'+key+'">'+result[key]+'</option>').prependTo($('select[name="school_districts_id"]'));

                 }
            }).error(function(err) {
            });

        });


    });
</script>

@stop
