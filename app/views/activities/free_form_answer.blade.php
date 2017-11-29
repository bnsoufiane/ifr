@extends('activities.base_layout')

@section('wrapper_classes', 'pageWrap newInner freeformPage clearfix')

@section('content')
	<fieldset>
		<h2>{{ $data->description }}</h2>
    </fieldset>
    @if ($data->explanation)
    <fieldset>
        <p>{{{nl2br($data->explanation)}}}</p>
    </fieldset>
    @endif


    <fieldset>
        <!-- <div class="userInfo">
            <div class="userAvatar">
                <div class="userAvatarBg">
                    <img src="{{ asset('/user-static/images/question2.png') }}" alt="">
                </div>
            </div>
            <!-- <div class="userName">Josh</div> -->
        <!--</div>-->

        <div class="userAnswer">
            <fieldset>
                <textarea  @if ($hasAnswer || (isset($assessment_done) && $assessment_done))disabled @endif class="freeform_textarea" name="text_{{$current_activity->id}}" placeholder="Please type your answer in here..."> @if ($hasAnswer){{ $answerData->answer }}@endif</textarea>
            </fieldset>

            <?php
             if($current_activity->title !="SubActivity"){
             ?>
                <fieldset>
                    <!-- <button type="submit" class="btn submit" <?php
                        //$var = (!isset($preview)) ? '' : 'disabled="disabled"';
                        //echo $var;
                        ?> ><span>Submit</span></button> -->
                </fieldset>
            <?php
            }
            ?>
        </div>
    </fieldset>
@stop
