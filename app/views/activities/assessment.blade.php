@extends('activities.base_layout')

@section('wrapper_classes', 'pageWrap clearfix newInner lightInner assessmentPage')

@section('content')
<ul class="customRadios assessmentRadios">
    <?php $i = 1; ?>
    @foreach ($data->sections as $section)
    <li>
        <p class="section_title" wrong_answer_desc="{{$section->wrong_answer_desc}}"><span class="title_number">{{$i}}.&nbsp;</span><span class="title_text">{{$section->title}}</span></p>

        @foreach ($section->options as $option)

        <fieldset>
            <input type="radio" class="customRadio" name="option_{{ $section->id }}" graded="{{ $option->graded }}" value="{{ $option->id }}"
                   @if ($hasAnswer && $answerData->assessmentIsCorrectOption($section->id, $option->id ))
                   checked="checked"
                   @endif
                   >
                   <div class="labelWrap"><label>{{ $option->option }}</label></div>
        </fieldset>
        @endforeach
    </li>
    <?php $i += 1; ?>
    @endforeach
</ul>
@stop

@section('scripts')
{{{ javascript_include_tag('user/assessment') }}}
@stop
