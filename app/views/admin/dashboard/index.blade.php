<div style="padding: 30px; padding-top: 50px">
    <?php
    $currentUser = \Sentry::getUser();
    $isTeacher = $currentUser->isTeacher();
    ?>
    @if (!$isTeacher)

        Welcome to the System Administrator's Dashboard!<br/><br/>

        On the left side you will see the various areas in which you can add or modify Products, Users, Classes,
        Students, Schools, and Teachers.<br/><br/>

        The <strong>Products</strong> tab allows you to change the various courses offered.  You can add or remove a
        series, change the lessons, or modify the activities for the lessons.<br/><br/>

        The <strong>Users</strong> tab is the area in which you can add or remove users that will access the learning
        course, and where you can set restrictions.  Students can only see the actual course material in test format,
        and do not see the admin section.<br/><br/>

        The <strong>Classes</strong> tab allows you to group students together by class.  A class could simply be called
        "Mr. Smith's Class" or something similar.  This allows you to view grade reports by lesson for a particular
        group of students.<br/><br/>

        The <strong>Students</strong> tab allow you to add or modify students.  Students can be assigned to schools.
        You may also modify their user name and password from this screen.<br/><br/>

        The <strong>Schools</strong> tab allows you to add, remove, or edit schools.  School administrators can also be
        assigned.  You can determine what products are available to various schools.<br/><br/>

        The <strong>Teachers</strong> tab grants access to add or remove teachers.  You may modify a teacher's login
        username or password.  Teachers can also be assigned to schools from this view.<br/><br/>

        <strong>Scores & Reports</strong> let's you view the lesson reports and answers for students assigned to the
        various teachers.<br/><br/>

    @else
        <h3 class="orange_color"><strong>Welcome to <em>The Teacher's Desktop</em> for <em>It’s for Real Workplace
                    Ethics.</em></strong></h3>

        We are pleased you are using <em>It’s for Real Workplace Ethics</em> with your students.  If you would like
        assistance at any time, please contact us. We will spend as much time as you need to address your questions. A Reference Manual is also provided when you click Instructor Materials.
        <br/>
        <span style="display: block; text-align: center"><a href="mailto:csp@careersolutionspublishing.com"
                                                            target="_top">csp@careersolutionspublishing.com</a><br/>
        888 299-2784<br/><br/>
        </span>

        To acquaint you with <em>The Teacher’s Desktop</em>, preliminary explanations are given below. Additional
        instructions are given as you use the program.<br/><br/>
          On the left side of the screen, you will see orange navigation buttons that allow you to set up classes, view
        and edit students, see scores and reports and select instructor materials.<br/><br/>
        <br/>

        <h4 class="orange_color"><strong>Navigation Buttons </strong></h4><br/>

        <strong>Student Version</strong> – By clicking this button, you may view the <em>It’s for Real Workplace
            Ethics</em> lessons the students will complete.<br/><br/>

        <strong>Classes</strong> – Here you can group students by class name; add, view, and edit student names; select
        required and optional lessons; and create pre- and post-tests.<br/><br/>

        <strong>Students</strong> – This screen allows you to view or modify students’ names. You can also change a
        student’s password if the student loses or forgets the original password.<br/><br/>

        <strong>  Scores & Reports</strong> – This screen allows you to view students’ scores by series and lessons and
        see their answers to critical-thinking and analysis questions.<br/><br/>

        <strong>Instructor Materials</strong> – Here, you are able to select useful tools such as a Reference Manual,
        a Certificate of Completion, titles and themes of lessons and other items.
        Our contact information is also listed here if you require assistance.  <br/><br/><br/>


        <h4 class="orange_color"><strong>Overview</strong></h4>

        You will follow four steps <em>each time</em> you set up a new class:<br/>
        <div style="padding-left: 10px">
            Step 1: Create a class. <br/>
            Step 2: Add students to create a roster. <br/>
            Step 3: Select the required lessons.<br/>
             Step 4: Create pre- and post-tests.<br/>
        </div>
        <br/><br/>


        <div style=" font-size: 17px; border: 2px solid #e69a52; padding: 10px;">
            <strong>Your first step is to create a new class. Begin by clicking the Classes button in the side
                bar.</strong>
        </div>

    @endif

</div>