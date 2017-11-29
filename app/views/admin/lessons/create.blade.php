<div class="page-head">
    <h2 class="orange_color">Add a New Lesson</h2>
    <div class='help-block'>
        This screen allows you to add new Lessons to a Series. Title the Lesson from the box at the top of the page.  Below, add <strong>activities</strong> that will be taken in each Lesson.  There are 10 different activity types that you can use to build out the Lesson by asking students to answer questions or fill out forms.<br/><br/>

        <strong>Lessons may be marked as required or optional.  You will be able to view progress reports and scores for Required and Optional lessons separately.</strong>  <br/><br/>

        <strong>All lessons currently marked as Required will change to Optional only if you click "Optional" beside the lesson title.</strong>

    </div>
    <ol class="breadcrumb">
        <li><a href="{{ URL::route('admin.index') }}">Home</a></li>
        <li><a href="{{ URL::route('admin.modules.index') }}">Products & Series</a></li>
        <li><a href="{{ URL::route('admin.modules.index') }}">{{ $series->module->title }}</a></li>
        <li><a href="{{ URL::route('admin.modules.index') }}">{{ $series->title }}</a></li>
        <li class="active">Add a New Lesson</li>
    </ol>
</div>

@include('admin/lessons/_form')
