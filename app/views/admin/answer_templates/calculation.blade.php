<fieldset>
@if(isset($student_name))
    <h2 class="report_student_name">{{$student_name}}</h2><br/>
@endif
@if(isset($activity_title))
    <h2 class="report_activity_name">{{$activity_title}}</h2>
@endif

@if ($hasAnswer)
    <?php $i=0; ?>
    @foreach ($data->items as $item)
        <p class="report_p">
            {{ ($hasAnswer && $answerData ? $answerData->getAnswer($i) : '') }}
        </p>
        <?php $i++; ?>
    @endforeach
    @foreach ($data->footers as $footer)
        <p class="report_p">
            {{ ($hasAnswer && $answerData ? $answerData->getAnswer($i) : '') }}
        </p>
        <?php $i++; ?>
    @endforeach
@endif
</fieldset>