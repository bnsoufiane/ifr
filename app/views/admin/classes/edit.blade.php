<div class="page-head">
    <h2 class="orange_color">Classes: Edit a class</h2>
    <?php 
        $currentUser = \Sentry::getUser();
        $isTeacher = $currentUser->isTeacher();
    ?>

    <div class='help-block'>
        To make changes for a Class, you may edit the information.   For example, you may want to change the Minimum Score.<br/><br/>
        To add another <em>It’s for Real Workplace Ethics</em> Series for your class to complete, click <strong>Add Series</strong> and choose Series I, II, III, or IV.  To remove a series from your class, click the red delete button on the Series name.<br/><br/>
        Click the <strong>Save Class</strong> button, to return to the Classes page.
    </div>

    <ol class="breadcrumb">
        <li><a href="{{ URL::route('admin.index') }}">Home</a></li>
        <li><a href="{{ URL::route('admin.classes.index') }}">Classes</a></li>
        <li class="active">Edit a Class</li>
    </ol>
</div>

<script type="text/javascript">
	var STUDENTS = {{{ $students }}};
	var MODULES = {{{ json_encode($class->series()->get()) }}};
</script>

<div class="cl-mcont">
    <div class="block-flat">
        <div class="content">
            @include('admin/classes/_form')
        </div>
    </div>
</div>
