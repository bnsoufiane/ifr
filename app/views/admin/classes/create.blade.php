<div class="page-head">
    <h2 class="orange_color">Classes: Create a New Class</h2>
    <?php 
        $currentUser = \Sentry::getUser();
        $isTeacher = $currentUser->isTeacher();
        if($isTeacher){
    ?>
    <div class='help-block'>
        <strong>Step 1:</strong> To create a Class, enter a <strong>Class Name</strong> in the box. Here are a few suggestions.<br/>
        <ul>
            <li>By time and day of week: 10:00 a.m. Tuesday</li>
            <li>By a course number: WR-235</li>
            <li>By grade and period: 9th Grade, second period</li>
            <li>By a designation of your choice.</li>
        </ul>

        Next, enter the <strong>Minimum Score</strong> students must achieve for each lesson. Then, enter an initial password that allows all students in the class to log in the first time.  They will be prompted to create their own unique password.<br/><br/>
        To identify the <strong><em>It’s for Real Workplace Ethics</em> Series</strong> you want the students in this class to complete, click <strong>Add Series</strong> under the <strong><em>It’s for Real Workplace Ethics</em> Series</strong> heading and then click the box beside your Series Choice(s).<br/><br/>
         Click the <strong>Save Class</strong> button and advance to Step 2, to create a roster for this Class.
    </div>
    <?php 
    }  else {
        ?>
    <div class='help-block'>
        This screen allows you to add a new class.  You may provide the name, minimum score required to pass, and the school the class is associated with.<br/><br/>

        You can also add existing students or create a new student to add to this particular class.
    </div>
    <?php
    }
    ?>
    <ol class="breadcrumb">
        <li><a href="{{ URL::route('admin.index') }}">Home</a></li>
        <li><a href="{{ URL::route('admin.classes.index') }}">Classes</a></li>
        <li class="active">Create a New Class</li>
    </ol>
</div>

<div class="cl-mcont">
    <div class="block-flat">
        <div class="content">
            @include('admin/classes/_form')
        </div>
    </div>
</div>
