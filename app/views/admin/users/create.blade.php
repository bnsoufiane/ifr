<div class="page-head">
    <h2 class="orange_color">Add a New {{ $singularTitle }}</h2>
    <?php 
        if($singularTitle=="User"){
            ?>
            <div class='help-block'>
                This screen allows you to create a new user.  Add their name and a username and password.  They will use these to log in. Admins will see the Admin Dashboard, Teachers will see the Teacher's Desktop Dashboard, and Students will only see the test itself, with no access to the admin section.<br/><br/>

                Select the school the user will be associated with.<br/><br/>

                "Groups" is an important field as it describes the user's role and level of access.
            </div>
            <?php
        }else if ($singularTitle=="Student"){
            ?>
            <div class='help-block'>
                <?php 
                    $currentUser = \Sentry::getUser();
                    $isTeacher = $currentUser->isTeacher();
                    if($isTeacher){
                ?>
                    You may create new students from this screen.  Pay special attention to their username and password as  it will be used by the student to log in to the test taking area.    
                <?php 
                }else{
                ?>
                    You may create new students from this screen.  Pay special attention to their username and password as  it will be used by the student to log in to the test area.  You can also assign a school to the student from this screen.  <br/><br/>

                    If you have not yet created a particular school, there is a shortcut to that screen by clicking "Create a new School."  You can hit "Refresh list" at any time to update the list of schools in the drop-down to reflect any new schools that you added.
                <?php 
                }
                ?>
                
            </div>
            <?php
        }else if ($singularTitle=="Teacher"){
            ?>
            <div class='help-block'>
                You can create a new teacher from this area.  Pay close attention to their username and password as they will need that to login.  You can also assign a teacher to a certain school or create a new school.
            </div>
            <?php
        }
    
    ?>
    
    <ol class="breadcrumb">
        <li><a href="{{ URL::route('admin.index') }}">Home</a></li>
        <li><a href="{{ URL::route($baseRoute . '.index') }}">{{ $title }}</a></li>
        <li class="active">Add a New {{ $singularTitle }}</li>
    </ol>
</div>

<div class="cl-mcont">
    <div class="block-flat">
        <div class="content">
            @include('admin/users/_form')
        </div>
    </div>
</div>
