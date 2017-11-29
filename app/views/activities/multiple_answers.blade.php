@extends('activities.base_layout')

@section('wrapper_classes', 'pageWrap newInner lightInner clearfix multiple_answersPage')

@section('content_unwrapped')
<div class="solid_border_fix">
	@if ($data->description)
	<div class="mainContentWrapper">
		<div class="mainContent">
			<p>
				{{{nl2br($data->description)}}}
			</p>
		</div>
	</div>
	@endif

	<div class="mainContentWrapper secondWrapper">
		<div class="mainContent">
			<ol class="excusesList">
				<?php for ($i = 0; $i < $data->number_of_fields; $i++): ?>
				<li>
					<div class="multiple_answers_example_text">{{ $data->getPlaceholder($i) }}</div>
                    <div class="multiple_answers_input_text">
					    <input name="answer_{{$current_activity->id}}[]" type="text" value="{{ ($hasAnswer ? $answerData->getAnswer($i) : '') }}"  @if($hasAnswer || (isset($assessment_done) && $assessment_done)) disabled @endif/>
				    </div>
				</li>
				<?php endfor ?>
			</ol>
		</div>
	</div>
</div>
@stop

@section('scripts')
    {{{ javascript_include_tag('user/multiple_answers') }}}
@stop
