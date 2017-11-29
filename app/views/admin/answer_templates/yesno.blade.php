<fieldset>
    @if(isset($student_name))
        <h2 class="report_student_name">{{$student_name}}</h2><br/>
    @endif
    @if(isset($activity_title))
        <h2 class="report_activity_name">{{$activity_title}}</h2>
    @endif

    @foreach ($data->sections as $section)
        @foreach ($section->options as $option)
            <p class="report_p" style="margin-bottom: 15px;">
               @if ($hasAnswer && $answerData->valueByOption($option) == 1)
                    <span>Yes - </span>
               @else
                    <span>No - </span>
               @endif
               <span>{{$option->option }}</span>
            </p>
        @endforeach
    @endforeach
</fieldset>