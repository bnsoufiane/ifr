<fieldset>
    @if(isset($print_lesson_blogs))
        <h2>{{$student_name}}</h2>
    @elseif(isset($series_title))
        <h2>{{ "$series_title / $lesson_title"}}</h2>
    @endif

    @if ($hasAnswer){{{ str_replace("\r\n",'<br/>',$answerData->answer) }}}@endif
</fieldset>
