<!DOCTYPE html>
<!--[if !(IE)]><!-->
<html lang="en"> <!--<![endif]-->
<!--[if lt IE 7 ]>
<html lang="en" class="no-js msie ie6 lte9 lte8 lte7"> <![endif]-->
<!--[if IE 7 ]>
<html lang="en" class="no-js msie ie7 lte9 lte8 lte7"> <![endif]-->
<!--[if IE 8 ]>
<html lang="en" class="no-js msie ie8 lte9 lte8"> <![endif]-->
<!--[if IE 9 ]>
<html lang="en" class="no-js msie ie9 lte9"> <![endif]-->

<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->

<html lang="en">

<head>
        <meta charset="utf-8">
        <title>IFR</title>
        <meta http-equiv="Content-type" content="text/html; charset=utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <link rel="shortcut icon" href="<?php echo asset('/admin-static/images/favicon.png'); ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

        <link href='http://fonts.googleapis.com/css?family=Crete+Round:400,400italic' rel='stylesheet' type='text/css'>
        <link href='http://fonts.googleapis.com/css?family=Bitter:400,700,400italic' rel='stylesheet' type='text/css'>
		{{{ stylesheet_link_tag('application') }}}
</head>
<?php
if (isset($template_type) && ($template_type == 'ActivityTemplates\Calculation')) {
    $body_class = "rightFancybox";
} else {
    $body_class = "";
}
?>

<body class="{{$body_class}}">


<?php
if (isset($current_activity)) {
    $current_lesson_id = $current_activity->lesson->id;
            $serie_index = 1;
            //$series = $module->series()->get();
            foreach ($series as $serie_item) {
                $lessons = $serie_item->lessons()->get();

                $lesson_index = 1;
                foreach ($lessons as $lesson_item) {
            if ($lesson_item->id == $current_lesson_id) {
                        break 2;
                    }
                    $lesson_index++;
                }
                $serie_index++;
            }

            $serie_title = $current_activity->lesson->series->title;
    $serie_title = str_replace("IV", "4", $serie_title);
    $serie_title = str_replace("III", "3", $serie_title);
    $serie_title = str_replace("II", "2", $serie_title);
    $serie_title = str_replace("I", "1", $serie_title);
}

?>

<!-- Top navbar -->
<div class="pageNavBg"></div>
<div id="header" class="clearfix">
            <a href="#" class="logo">
                <img src="<?php echo asset('/assets/logo.png'); ?>" alt="">
                <?php
                    $logo_text = "$serie_title, Lesson $lesson_index";
                ?>
                <span>{{$logo_text}}</span>

                <div class="logo_lesson_title">
                    <span>{{$current_activity->lesson->title}}</span>
                </div>
            </a>

            @include('layouts/top-navigation')
</div>


<div class="pageBackground">
            <?php if ($activiy_bg_img) {
                ?>
                <img src="<?php echo asset('/uploads/' . $activiy_bg_img); ?>" alt="">
                <?php
            } else {
                ?>
                <div class="pattern comic"></div>
                <div class="overPattern comic"></div>
                <?php
			}
            ?>


</div>

@include('layouts/side-bar')

<form action="{{ URL::route('activities.store') }}" method="POST">
            {{{$content}}}

            <?php
    for ($i = 0; $i < count($sub_activities_views); $i++) {
                    echo $sub_activities_views[$i];
                }
            ?>

            <?php
    $disabled = isset($students_area) ? '' : 'disabled="disabled"';
            ?>
            <div class="wrap ta-c clearfix" style="position: relative;">
                <button type="submit" class="continueBtn" {{$disabled}} tabindex="-1">
                    <span>Save and Continue</span>
                </button>
            </div>


</form>


<div id="footer">
            <div class="wrap clearfix">
                <p>
            @if($isCurrentActivityAssessment)
                <span class="assessment_alert">The "Save and Continue" button will not appear, and your score will not be recorded,</span>
                <span class="assessment_alert">Unless you have answered all Assessment questions.</span>
            <br/>
            @endif
                    &copy; 2016 Career Solutions Publishing. All Rights Reserved
                </p>
            </div>
</div>

<input type="hidden" id="user_id" value="{{$user->getId()}}"/>
<input type="hidden" id="activity_id" value="{{$activityId}}"/>

<a class="various" href="#session_expired" href="javascript:;" class="disabled"
   style="position: absolute;right: -100px; top: 100px; visibility: hidden;"> click here</a>

<div id="session_expired" style="font-size: 1.3em; text-align: center; display: none;">
        Your session has expired. Please login again.</br></br>
        <a href="{{ URL::route('sign-in') }}">Click here</a>
</div>


<!--[if lte IE 7]>
<script src="js/warning.js"></script>
<script>window.onload = function () {
    e("images/warning/")
}</script><![endif]-->
{{{ javascript_include_tag('application') }}}
{{{ javascript_include_tag('application_controls') }}}

@yield('scripts')

<script>

        $('a[href="#session_expired"]').fancybox({
            maxWidth: 500,
            maxHeight: 800,
            fitToView: true,
            width: '70%',
            height: '70%',
            autoSize: true,
            closeClick: true,
            openEffect: 'none',
            closeEffect: 'none'
        });

        var start = new Date().getTime();

    var interval = setInterval(function () {
            $.ajax({
                url: '/checkSession',
                method: 'GET',
                dataType: 'html'
            }).done(function (response) {
            if (response == "inactive") {
                $('a[href="#session_expired"]').trigger("click");
                }
            });
    }, 60 * 60 * 1000 + 10 * 1000);

</script>

</body>
</html>
