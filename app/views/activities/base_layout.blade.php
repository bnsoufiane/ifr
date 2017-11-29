<input type="hidden" name="test" value="<?php
$var = (!isset($test)) ? '' : $test;
echo $var;
?>" />
<input type="hidden" name="activity_id" value="{{ $current_activity->id }}" />
<!--
<input type="hidden" name="time_to_complete_activity" value="0" />
-->
<div class="@yield('wrapper_classes', 'pageWrap newInner clearfix')">
    <div class="wrap clearfix">
        <div class="pageHead">
            @section('header')
            <div id="feedback">
                <div class="pageWrap newInner">
                    <h2>{{ $current_activity->title }}</h2>
                    <br>
                    <p>{{{ $current_activity->feedback }}}</p>
                </div>
            </div>
            <div id="feedback_msg" style="font-size: 1.3em">
                <br>All the answers must be completed first!
            </div>

            <div id="cant_print_activity" style="display: none; font-size: 1.3em">
                This activity can't be printed
            </div>
            @include('activities._title')

            @if (!empty($current_activity->illustration_image))
            <img src="<?php echo asset('/uploads/' . $current_activity->illustration_image); ?>" alt=""/>
            @endif
            @show
        </div>

        @section('content_unwrapped')
        <div class="mainContentWrapper">
            <div class="mainContent">
                @yield('content')
            </div>
        </div>
        @show
    </div>

</div>

