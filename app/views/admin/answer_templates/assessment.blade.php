<fieldset>
    @if(isset($student_name))
        <h2 class="report_student_name">{{$student_name}}</h2><br/>
    @endif
    @if(isset($activity_title))
        <h2 class="report_activity_name">{{$activity_title}}</h2>
    @endif

    <?php $i=1; ?>
    @foreach ($data->sections as $section)
        @foreach ($section->options as $option)
            @if ($hasAnswer && $answerData->assessmentIsCorrectOption($section->id, $option->id ))
                <div class="labelWrap"><label>{{ "$i. $option->option" }}</label></div><br/>
                <?php $i++; ?>
            @endif
        @endforeach
    @endforeach
</fieldset>