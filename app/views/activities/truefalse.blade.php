@extends('activities.base_layout')

@section('wrapper_classes', 'pageWrap clearfix newInner lightInner assessmentPage truefalsePage')

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
                <ul class="customRadios truefalseRadios">
                    <?php $i = 1; ?>
                    @foreach ($data->sections as $section)
                    <li>
                        <p class="section_title"><span class="title_number">{{$i}}.&nbsp;</span><span class="title_text">{{$section->title}}</span></p>

                        @foreach ($section->options as $option)

                        <fieldset>
                            <input type="radio" class="customRadio" name="option_{{ $section->id }}" graded="{{ $option->graded }}" value="{{ $option->id }}"
                                   @if ($hasAnswer && $answerData->assessmentIsCorrectOption($section->id, $option->id ))
                                   checked="checked"
                                   @endif
                                   @if ($hasAnswer || (isset($assessment_done) && $assessment_done)) disabled @endif
                                   >
                                   <div class="labelWrap"><label>{{ $option->option }}</label></div>
                        </fieldset>
                        @endforeach
                    </li>
                    <?php $i += 1; ?>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
@stop

@section('scripts')
{{{ javascript_include_tag('user/truefalse') }}}
@stop
