<input type="hidden" name="test" value="<?php
$var = (!isset($test)) ? '' : $test;
echo $var;
?>" />

<div class="@yield('wrapper_classes', 'pageWrap newInner clearfix')">
    <div class="wrap clearfix">
        <div class="pageHead">
            @section('header')
            <div id="feedback">
                <div class="pageWrap newInner">
                    <h2>Pre-test</h2>
                    <br>
                    <p>Pre-test</p>
                </div>
            </div>

            <div class="headTitle"><?php echo $test_title; ?></div>

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

