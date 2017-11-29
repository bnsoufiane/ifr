<div class="mainMenuWrapper">
    <?php $user = Sentry::getUser(); ?>

    <div class="welcome_student">Welcome, {{"$user->first_name $user->last_name"}}</div>
    <a href="javascript:;" class="menuToggle">
        <span>Lessons List</span>
    </a>
    <?php
    echo '<ul class="mainMenu">';

    $student_classes = \DB::table('school_class_student')->select('school_class_id')->where('student_id', $user->id)->get();
    foreach ($student_classes as $student_class) {
        $student_classes_ids[] = $student_class->school_class_id;
    }

    $serie_index = $current_activity->lesson->series->id;
    $flag_all_lessons_completed = true;
    foreach ($series as $serie_item) {
        echo '<li class="accordion">';
        echo '<a href="javascript:;" class="show">' . $serie_item->title . '</a>';

        $lessons = $serie_item->lessons()->get();
        $class = ($serie_index == $serie_item->id) ? 'active' : '';
        echo '<ul class="' . $class . '">';
        $i = 1;
        foreach ($lessons as $lesson_item) {
            //$display = false;
            $is_optional_lesson = \DB::table('school_class_optional_lessons')
                   ->where('optional_lesson_id', $lesson_item->id)
                ->whereIn('school_class_id', $student_classes_ids)->first();

            $assessment_activity = $lesson_item->activities()->where("template_type", "=", "ActivityTemplates\\Assessment")->first();
        if ($assessment_activity) {
                $student_answers = \StudentAnswer::whereRaw('student_id = ? and activity_id = ?', array($user->id, $assessment_activity->id))->first();
            }

            //if ($is_optional_lesson) {
                /*$student_optional_lesson = \DB::table('student_optional_lesson')
                                ->where('lesson_id', $lesson_item->id)
                                ->where('student_id', $user->id)->first();*/
                //if (!$student_optional_lesson) {
                  //  $display = false;
                //}
            //}
        if (($lesson_item->activities()->count() > 0)) {//&& $display) {
            $required_image = ($is_optional_lesson) ? '' : '<img src="../assets/required.png" height="16" width="16" style="margin-left:5px;">';
            $anchor_href = '/lessons/' . $lesson_item->id;
            $lesson_completed_class = ($student_answers == null) ? '' : 'class="lesson_completed"';
            if ($lesson_completed_class == "" && !$is_optional_lesson) {
                    $flag_all_lessons_completed = false;
                }

            echo '<li ' . $lesson_completed_class . '><a href="' . $anchor_href . '">' . $i . ". " . $lesson_item->title . $required_image . '</a></li>';
                $i++;
            }
        }
        echo "</ul>";
        ?>

        <?php
    echo '</li>';
    }
        $student = \Student::find($user->id);

        $class = $student->schoolClasses()->first();
        $test = ($class == null) ? null : (\Tests::getBySchoolClass($class->id));
            if(isset($test)){

        $test_students = \TestStudent::getByTestAndStudent($test->id, $user);

        $posttest = \TestStudent::whereRaw('test_id = ? and student_id = ? and learning_level = ? and status <> ?', array($test->id, $user->id, \TestStudent::POST_TEST, \Tests::OPEN))->count();
        $posttestAlreadyTaken = ($posttest != 0);

        $class_all_lessons_completed = ($flag_all_lessons_completed && $test_setup && !$posttestAlreadyTaken) ? '' : 'class="lesson_completed"';

    ?>
        <li {{{$class_all_lessons_completed}}}>
        @if($flag_all_lessons_completed && $test_setup && !$posttestAlreadyTaken)
            <a href="{{ URL::route('user.posttest') }}" class="show">Take Post-test</a>
            @else
            <a href="javascript:;" class="show">Take Post-test</a>
            @endif
        </li>
        <?php } ?>
    </ul>


</div>
