<fieldset>
    @if(isset($student_name))
        <h2 class="report_student_name">{{$student_name}}</h2><br/>
    @endif
    @if(isset($activity_title))
        <h2 class="report_activity_name">{{$activity_title}}</h2>
    @endif

    <?php
    if ($hasAnswer) {
        $answer_values = array();
        if (isset($answerData)) {
            $answer_values = $answerData->values()->get();
        }

        $answer_values_ids = array();

        foreach ($answer_values as $key => $value) {
            $answer_values_ids[] = $value->option;
        }
    }
    ?>
    @foreach ($data->options()->sorted()->get() as $item)
        <p class="report_p">
            @if ($hasAnswer && in_array( $item->id, $answer_values_ids))
                <span>{{ $item->option }}</span>
            @endif
        </p>
    @endforeach

</fieldset>




