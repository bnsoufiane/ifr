@extends('activities.base_layout')

@section('wrapper_classes', 'pageWrap newInner blogPage clearfix')

@section('content')
    <fieldset>
    <h2>{{ $data->title }}</h2>
    </fieldset>

    @if ($data->explanation)
        <fieldset>
    <p>{{{nl2br($data->explanation)}}}</p>
        </fieldset>
    @endif

    <fieldset>
        <textarea @if ($hasAnswer || (isset($assessment_done) && $assessment_done))disabled @endif id="blog_textarea"
                  name="blog_text_{{$current_activity->id}}"
                  placeholder="Start typing here...">@if ($hasAnswer){{ $answerData->answer }}@endif</textarea>
    </fieldset>

    <?php
    if($current_activity->title != "SubActivity"){
 ?>
    <fieldset>
    <!--<button type="submit" class="btn submit" <?php
            //$var = (!isset($preview)) ? '' : 'disabled="disabled"';
            //echo $var;
            ?>><span>Submit</span></button> -->
    </fieldset>
    <?php
    }
    ?>

@stop
