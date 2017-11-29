<fieldset>
    @if(isset($student_name))
        <h2 class="report_student_name">{{$student_name}}</h2><br/>
    @endif
    @if(isset($activity_title))
        <h2 class="report_activity_name">{{$activity_title}}</h2>
    @endif

    <?php $i=0; ?>
    @foreach($data->items as $item)
        {{ ($hasAnswer ? $answerData->getAnswer($i) : '') }} <br/>
        <?php $i++; ?>
    @endforeach
</fieldset>