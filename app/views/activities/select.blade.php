@extends('activities.base_layout')

@section('wrapper_classes') pageWrap whatBoss newInner clearfix selectPage @stop

@section('content')
<div class="options">
    <h2 class="label_descr">{{{nl2br($data->description)}}}</h2>
    @if ($data->explanation)
    <fieldset>
        <p style="margin-bottom: 12px;" class="label_expl">{{{nl2br($data->explanation)}}}</p>
    </fieldset>
    @endif

    <?php
    $i = 0;
    $n = (count($data->options->count()) / 2) + 1;

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

    foreach ($data->options()->sorted()->get() as $item) {

        if ($i % $n == 0) {
            echo '<ul class="customRadios">';
        }
        ?>
        <li>

            <input type="radio" class="customRadio select_option_radio" @if ($hasAnswer || (isset($assessment_done) && $assessment_done)) disabled @endif
                   name="select_option_id_{{ $item->id }}" value="{{ $item->id }}"
                   @if ($hasAnswer && in_array( $item->id, $answer_values_ids))
                   checked="checked"
                   @endif>
                   <div class="labelWrap"><label>{{ $item->option }}</label></div>
        </li>
        <?php
        if (($i + 1) % $n == 0) {
            echo '</ul>';
        }
        $i++;
    }
    ?>

</div>
@stop
