<fieldset>
    @if(isset($student_name))
        <h2 class="report_student_name">{{$student_name}}</h2><br/>
    @endif
    @if(isset($activity_title))
        <h2 class="report_activity_name">{{$activity_title}}</h2>
    @endif

    <?php for ($i = 0; $i < $data->number_of_fields; $i++): ?>
        {{{ ($hasAnswer ? $answerData->getAnswer($i).'<br/>' : '') }}}
    <?php endfor ?>
</fieldset>