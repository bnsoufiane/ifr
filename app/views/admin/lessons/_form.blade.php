<div class="cl-mcont" id="lesson">
    {{{ Form::hidden('series_id', $series->id) }}}

    <div class="block-flat">
        <div class="form-group">
            {{{ Form::model($lesson) }}}
                {{{ Form::label('title', 'Lessons\'s Title', array('class' => 'control-label')) }}}
                {{{ Form::text('title', null, array('class' => 'form-control', 'placeholder' => 'Enter title', 'rv-value' => 'model:title')) }}}
            {{{ Form::close() }}}
        </div>
        <div class="form-group">
            {{{ Form::model($lesson) }}}
                {{{ Form::label('topic', 'Lessons\'s Topic', array('class' => 'control-label')) }}}
                {{{ Form::text('topic', null, array('class' => 'form-control', 'placeholder' => 'Enter topic', 'rv-value' => 'model:topic')) }}}
            {{{ Form::close() }}}
        </div>
    </div>
	
    <nav class="toolbar-nav">
        <ul>
            <li>
                <a class="btn btn-lg btn-rad add-activity" href="javascript:void(0);"><i class="fa fa-plus-square"></i>&nbsp;Add a New Activity</a>
            </li>
            <li>
                <a class="btn btn-lg btn-rad add-assessment" href="{{ URL::route('admin.lessons.add_assessment', array($lesson->id)) }}"><i class="fa fa-plus-square"></i>&nbsp;Add Assessment</a>
            </li>
            <li>
                <a class="btn btn-lg btn-primary btn-rad save-lesson" href="javascript:void(0);"><i class="fa fa-save"></i>&nbsp;Save Lesson</a>
            </li>
        </ul>
    </nav>
    <div class='help-block' style="font-size: 14px;">
        When adding activities, you may choose the title of the activity.  You may also add an image that will appear to the right of the title that appears while the student is taking the course.  A background image can also be added that will appear as the student is working on that particular activity.<br/><br/>

        The Feedback field is used to provide the student with information or advice as they are working on a certain activity.  This can be clicked on by the student at any time during an activity.<br/><br/>

        An audio recording of the activity may be uploaded from this screen.<br/><br/>

        A pdf version may be added so that a student may print the activity at any time.<br/><br/>

        The "Activity Type" drop-down lets you select the type of activity.  Depending on what is selected, you may then fill out the various form fields.  Some activities require you to determine what a correct answer would be from a list of answers that you provide.  Pay close attention to how this is determined.<br/><br/>

        Preview an activity that you have created by clicking the Preview button at the bottom of the screen.  This will present the activity in a form similar to what the student will see while taking the test.<br/><br/>


        You may create an <strong>Assessment</strong> that will test the student on what they learned for this Lesson.  Click the "Add Assessment" button.  The assessment will appear after the final activity of the lesson is taken.  
        This functions similar to adding an activity.  Scores can be reviewed from the Scores & Reports screen.

    </div>

    <div class="panel-group" id="activities">
    </div>

    <br/><br/>
    <a class="btn btn-lg btn-rad show_assessment" href="{{ URL::route('admin.lessons.add_assessment', array($lesson->id)) }}" style="display: none;"><i class="fa fa-plus-square"></i>&nbsp;View Current Assessment</a>
</div>

@section('scripts')
	<script type="text/javascript">
	 var ACTIVITY_TEMPLATES = {{{ json_encode($activityTemplates) }}};
	</script>

	{{ javascript_include_tag('admin/lessons_include') }}
    {{ javascript_include_tag('admin/activity-templates') }}
@stop
