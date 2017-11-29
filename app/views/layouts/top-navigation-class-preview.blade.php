<div class="mainMenuWrapper">
    <a href="javascript:;" class="menuToggle">
        <span>Lessons List</span>
    </a>
    <?php
    echo '<ul class="mainMenu" style="top: 58px;">';

    $user = Sentry::getUser();

    $serie_index = $current_activity->lesson->series->id;
    ?>

    @foreach ($series as $serie_item)
        <li class="accordion">
        <a href="javascript:;" class="show">{{$serie_item->title}}</a>

        <?php
        $lessons = $serie_item->lessons()->get();
        $class = ($serie_index == $serie_item->id) ? 'active' : '';

            echo '<ul class="' . $class . '">';
            $i = 1;
            ?>
            @foreach ($lessons as $lesson_item)
                @if($lesson_item->activities()->count() > 0)
                    <?php

                     $is_optional_lesson = \DB::table('school_class_optional_lessons')
                       ->where('optional_lesson_id', $lesson_item->id)
                       ->where('school_class_id',$class_id)->first();

                       $required_image = ($is_optional_lesson)?'':'<img src="'.asset("/assets/required.png").'" height="16" width="16" style="margin-left:5px;">';

                        if ($lesson_item->activities()->orderBy("order")->get()[0]->template_type == 'ActivityTemplates\Assessment') {
                            $next_activity = $lesson_item->activities()->orderBy("order")->get()[1]->id;
                        } else {
                            $next_activity = $lesson_item->activities()->orderBy("order")->get()[0]->id;
                        }
                    ?>
                    <li>
                        @if($is_school_preview)
                            <a href="{{URL::route('admin.schools.preview_activity', array($next_activity))}}">{{$i}}. {{$lesson_item->title}} {{{$required_image}}}</a>
                        @else
                            <a href="{{URL::route('admin.classes.preview_activity', array($class_id, $next_activity))}}">{{$i}}. {{$lesson_item->title}}  {{{$required_image}}}</a>
                        @endif
                    </li>

                    <?php $i++; ?>
                @endif
            @endforeach
            </ul>
        </li>
    @endforeach

    @if(!$is_school_preview)
        <li class="lesson_completed">
            <a href="javascript:;" class="show">Take Post-test</a>
        </li>
    @endif

    </ul>

</div>
