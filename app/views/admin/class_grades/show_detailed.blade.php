<div class="page-head">
    <h2>{{ $student->fullName() }}'s Answers
        <?php if (isset($lesson)) { ?>
            to "{{ $lesson->title }}" Lesson
            <?php
        }
        ?>
    </h2>
    <ol class="breadcrumb">
        <li><a href="{{ URL::route('admin.index') }}">Home</a></li>
        <?php
        $currentUser = \Sentry::getUser();
        $isTeacher = $currentUser->isTeacher();
        $url = URL::route('admin.reports.index');
        if (!$isTeacher && !$cs) {
            echo '<li><a href="' . $url . '">Teachers</a></li>';
            $classesUrl = URL::route('admin.classes.grades.teacher_classes', array($teacher_id));
        } else {
            $classesUrl = URL::route('admin.reports.index');
        }
        ?>
        <li><a href="{{ ($cs)? (URL::route('admin.classes.index')):($classesUrl) }}">Classes</a></li>
        <li><?php
            if ($cs && $teacher_id) {
                $arr = array($class->id, 'cs' => $cs, 'teacher' => $teacher_id);
            } else if ($cs) {
                $arr = array($class->id, 'cs' => $cs);
            } else if ($teacher_id) {
                $arr = array($class->id, 'teacher' => $teacher_id);
            } else {
                $arr = array($class->id);
            }
            ?>
            <a href="{{ URL::route('admin.classes.grades.modules', $arr) }}">{{ $class->name }}</a></li>
        <li><a href="{{ URL::route('admin.classes.grades.modules', $arr) }}">{{ $module->title }}</a></li>
        <li><?php
            if ($cs && $teacher_id) {
                $arr = array($class->id, $module->id, 'cs' => $cs, 'teacher' => $teacher_id);
            } else if ($cs) {
                $arr = array($class->id, $module->id, 'cs' => $cs);
            } else if ($teacher_id) {
                $arr = array($class->id, $module->id, 'teacher' => $teacher_id);
            } else {
                $arr = array($class->id, $module->id);
            }
            ?>
            <a href="{{ URL::route('admin.classes.grades.series', $arr) }}">{{ $serie->title }}</a></li>
        <li><?php
            if ($cs && $teacher_id) {
                $arr = array($class->id, $module->id, 'cs' => $cs, 'teacher' => $teacher_id);
            } else if ($cs) {
                $arr = array($class->id, $module->id, 'cs' => $cs);
            } else if ($teacher_id) {
                $arr = array($class->id, $module->id, 'teacher' => $teacher_id);
            } else {
                $arr = array($class->id, $module->id, $serie->id);
            }
            ?>
            <a href="{{ URL::route('admin.classes.grades.lessons', $arr) }}">{{ $lesson->title }}</a></li>
        <li><?php
            if ($cs && $teacher_id) {
                $arr = array($class->id, $module->id, 'cs' => $cs, 'teacher' => $teacher_id);
            } else if ($cs) {
                $arr = array($class->id, $module->id, 'cs' => $cs);
            } else if ($teacher_id) {
                $arr = array($class->id, $module->id, 'teacher' => $teacher_id);
            } else {
                $arr = array($class->id, $module->id, $serie->id, $lesson->id);
            }
            ?>
            <a href="{{ URL::route('admin.classes.grades.lesson_grades', $arr) }}">{{ $student->fullName() }}</a></li>
        <li class="active">Student's Answers to Lesson</li>
    </ol>
</div>

<div class="cl-mcont">
    <div class="panel-group accordion" id="answers">
        @foreach ($answers as $answer)
        <?php
        try {
            $answer->activityTitle();
            ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" href="#answer_{{ $answer->id() }}">
                            <i class="fa fa-angle-right"></i> {{ $answer->activityTitle() }} ({{ $answer->tracked_time() }})
                        </a>
                    </h4>
                </div>
                <div id="answer_{{ $answer->id() }}" class="panel-collapse in">
                    <div class="panel-body">
                        {{{ $answer->render() }}}
                    </div>
                </div>
            </div>    

            <?php
        } catch (\Exception $e) {
            
        }
        ?>

        @endforeach
    </div>
</div>
