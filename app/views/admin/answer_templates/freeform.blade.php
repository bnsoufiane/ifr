<fieldset>
    @if(isset($student_name))
        <h2 class="report_student_name">{{$student_name}}</h2><br/>
    @endif
    @if(isset($activity_title))
        <h2 class="report_activity_name">{{$activity_title}}</h2>
    @endif
    @if ($hasAnswer)
        <p>
            {{{ str_replace("\r\n",'<br/>',$answerData->answer) }}}
        </p>
    @endif
</fieldset>