<div class="page-head">
    <h2 class="orange_color">Edit a School</h2>
    <?php 
        $currentUser = \Sentry::getUser();
        $isTeacher = $currentUser->isTeacher();
    ?>
    <div class='help-block'>
        You may edit a school from this tab.  You can also choose to add an existing administrator or create a new one.<br/><br/>

        You can also select which products the new school will be able to access.
    </div>
    <ol class="breadcrumb">
        <li><a href="{{ URL::route('admin.index') }}">Home</a></li>
        <li><a href="{{ URL::route('admin.schools.index') }}">Schools</a></li>
        <li class="active">Edit a School "{{ $school->name }}"</li>
    </ol>
</div>

<script type="text/javascript">
var ADMINISTRATORS = {{{ json_encode($school->admins()->get()) }}};
var MODULES = {{{ json_encode($school->modules()->get()) }}};
</script>

<div class="cl-mcont">
    <div class="block-flat">
        <div class="content">
            @include('...schools._form')
        </div>
    </div>
</div>
