@extends('activities.base_layout')

@section('wrapper_classes', 'pageWrap lightInner newInner clearfix wysiwyg_page')

@section('content')
	<fieldset>
		{{{ $data->displayContent(empty($answerData) ? null: $answerData->json) }}}
		
	</fieldset>
@stop
