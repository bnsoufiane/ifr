@extends('activities.base_layout')

@section('wrapper_classes') pageWrap newInner comicPage clearfix @stop

@section('content')
	<div class="comicsWrapper">
		<?php
		foreach ($data->pictures()->sorted()->get() as $item) {
		?>
        <div class="col">
            <img src="{{asset('/uploads/' . $item->file) }}" alt="">
        </div>  
        <?php
        }
        ?>
    </div>
@stop
