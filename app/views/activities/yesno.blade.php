@extends('activities.base_layout')

@section('wrapper_classes', 'pageWrap clearfix newInner lightInner yesno_page')

@section('content_unwrapped')
<div class="solid_border_fix">
    <div class="mainContentWrapper">
        <div class="mainContent">
            <p>
                {{{nl2br($data->description)}}}
            </p>
        </div>
    </div>

    <div class="mainContentWrapper secondWrapper">
        <div class="mainContent">
            <div class="contentSliderWrapper">
                <ul class="contentSlider">
                    <?php
                    $i = 1;
                    foreach ($data->sections as $section) {
                        echo "<li><h2>$section->title</h2><div class='radioQuestions'>";
                        foreach ($section->options as $option) {
                            ?>
                            <div class="radioWrap">
                                <div class="radioOptions" graded ="{{ $option->graded }}">
                                    <div class="option1 letter_{{$data->yes_letter}}">
                                        <input type="radio" class="customRadio" @if($hasAnswer || (isset($assessment_done) && $assessment_done))disabled @endif
                                               value="1"
                                               @if ($hasAnswer && $answerData->valueByOption($option) == 1)
                                               checked="checked"
                                               @endif
                                               name="option_{{ $option->id }}">
                                    </div>

                                    <div class="option2 letter_{{$data->no_letter}}">
                                        <input type="radio" class="customRadio" @if($hasAnswer || (isset($assessment_done) && $assessment_done))disabled @endif
                                               value="0"
                                               @if ($hasAnswer && $answerData->valueByOption($option) == 0)
                                               checked="checked"
                                               @endif
                                               name="option_{{ $option->id }}">
                                    </div>
                                </div>

                                <div class="radioLabel">
                                    <?php
                                        try {
                                            list($number, $text) = explode(".", $option->option, 2);
                                            $number = str_replace ("&nbsp;" , "", $number );

                                        if(strlen($number)>3){
                                            $number='';
                                            $text = $option->option;
                                        }else{
                                            $number .=".&nbsp;";
                                        }
                                        } catch (\Exception $e) {
                                            $text = $option->option;
                                        }
                                    ?>
                                    <p><p class="title_number">{{"$i.&nbsp;"}}</p><p class="title_text" style="float: none !important;">{{$text}}</p></p>
                                </div>
                            </div>
                            <?php
                            $i++;
                        }
                        echo '</div></li>';
                    }
                    ?>

                </ul>

                <!--<div class="nextBtnWrap">
                  <a href="javascript:;" class="nextBtn"><span><span id="nextQ"></span></span></a>
                </div>-->
            </div>
        </div>
    </div>
</div>
@stop

@section('scripts')
{{{ javascript_include_tag('user/yesno') }}}
@stop
