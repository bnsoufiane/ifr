<div class="page-head">
    <h2 class="orange_color">Editing a {{ $singularTitle }}</h2>
    <?php 
        if($singularTitle=="User"){
            ?>
            <div class='help-block'>
                This screen allows you to change the information about each user.  For the most part, this information should not need to change often as once the username and password are set, it should not need to change unless the user loses their password.<br/><br/>

                The Group field is important - it allows you to assign the user as a Student, Teacher, School Administrator, and System Administrator.  This will determine the users level of access.  Students can only see the test portion and can never access any of the administration screens.
            </div>
            <?php
        }else if ($singularTitle=="Student"){
            ?>
            <div class='help-block'>
                You may modify a student's user information from this screen.  Be sure to send them their new username or password if you change it.
            </div>
            <?php
        }
    
    ?>
    
    <ol class="breadcrumb">
        <li><a href="{{ URL::route('admin.index') }}">Home</a></li>
        <li><a href="{{ URL::route($baseRoute . '.index') }}">{{ $title }}</a></li>
        <li class="active">Editing a {{ $singularTitle }} "{{ $user->getLogin() }}"</li>
    </ol>
</div>

<div class="cl-mcont">
    <div class="block-flat">
        <div class="content">
            @include('admin/users/_form')
        </div>
    </div>
</div>

<script type="text/javascript">
    var USER_GROUPS = {{{ json_encode($user->groups) }}}
</script>
