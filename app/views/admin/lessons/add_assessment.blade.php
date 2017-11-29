<div class="page-head">
    <h2 class="orange_color">Edit a Lesson</h2>
    <ol class="breadcrumb">
        <li><a href="{{ URL::route('admin.index') }}">Home</a></li>
        <li><a href="{{ URL::route('admin.modules.index') }}">Products & Series</a></li>
        <li><a href="{{ URL::route('admin.modules.index') }}">{{ $series->module->title }}</a></li>
        <li><a href="{{ URL::route('admin.modules.index') }}">{{ $series->title }}</a></li>
        <li class="active">Add Assessment "{{ $lesson->title }}"</li>
    </ol>
</div>

<script>
    var LESSON_ID = {{ $lesson->id }};</script>

<div class="cl-mcont" id="lesson">
    {{{ Form::hidden('series_id', $series->id) }}}

    <nav class="toolbar-nav">
        <ul>
            <li>
                <a class="btn btn-lg btn-primary btn-rad save-lesson" href="javascript:void(0);"><i class="fa fa-save"></i>&nbsp;Save Assessment</a>
            </li>
        </ul>
    </nav>

    <input type="hidden" id="add_assessment_page" />

    <input type="hidden" value="{{$assessment_already_created}}" id="assessment_already_created"/>


    <div class="panel-group" id="activities" class="add_assessment_class">
    </div>
</div>

@section('scripts')
<script type="text/javascript">
            var ACTIVITY_TEMPLATES = {{{ json_encode($activityTemplates) }}};
</script>

{{ javascript_include_tag('admin/lessons_include') }}
{{ javascript_include_tag('admin/activity-templates') }}
@stop
