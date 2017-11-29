<!DOCTYPE html>
<!--[if lt IE 7 ]> <html lang="en" class="no-js msie ie6 lte9 lte8 lte7"> <![endif]-->
<!--[if IE 7 ]>    <html lang="en" class="no-js msie ie7 lte9 lte8 lte7"> <![endif]-->
<!--[if IE 8 ]>    <html lang="en" class="no-js msie ie8 lte9 lte8"> <![endif]-->
<!--[if IE 9 ]>    <html lang="en" class="no-js msie ie9 lte9"> <![endif]-->
<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
<![endif]-->

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
             $body_class="rightFancybox";
         }else{
             $body_class="";
         }

        if(isset($current_activity)){
            $current_lesson_id =$current_activity->lesson->id;
            $serie_index = 1;
            //$series = $module->series()->get();
            foreach ($series as $serie_item) {
                $lessons = $serie_item->lessons()->get();

                $lesson_index = 1;
                foreach ($lessons as $lesson_item) {
                    if ($lesson_item->id==$current_lesson_id) {
                        break 2;
                    }
                    $lesson_index++;
                }
                $serie_index++;
            }

            $serie_title = $current_activity->lesson->series->title;
            $serie_title = str_replace ( "IV" , "4" , $serie_title );
            $serie_title = str_replace ( "III" , "3" , $serie_title );
            $serie_title = str_replace ( "II" , "2" , $serie_title );
            $serie_title = str_replace ( "I" , "1" , $serie_title );
        }
    ?>
    
    <body class="{{$body_class}}">

        <!-- Top navbar -->
        <div class="pageNavBg"></div>
        <div id="header" class="clearfix">
            <span class="logo class_preview">
                <img src="<?php echo asset('/assets/logo.png'); ?>" alt="">
                <?php
                    if($is_school_preview){
                        $logo_text = "Student Version Preview";
                    }
                    else{
                    $logo_text = "$class_name Preview";
                    }

                ?>
                <div class="class_name_preview">
                    <span>{{{$logo_text}}}</span>
                    @if($is_school_preview)
                        <div class="logo_lesson_title">
                            <span>{{"$serie_title, Lesson $lesson_index"}}</span>
                        </div>
                        <div class="logo_lesson_title">
                            <span>{{$current_activity->lesson->title}}</span>
                        </div>
                    @endif
                </div>
                <span class="back_link">
                    @if(!$is_school_preview)
                        <a href="{{ URL::route('admin.classes.index') }}">Back</a>
                    @endif
                </span>
            </span>


            @if($is_school_preview)
                <div class="school_preview_box wrapper_classes pageWrap newInner clearfix" style="padding-left: 330px; padding-right: 410px;">
                    <div class="wrap clearfix">
                        <div class="mainContentWrapper">
                            <div class="mainContent">
                                <fieldset>
                                    <h2 style="font-size: 15px;">You are viewing the Student Version from The Teacher’s Desktop. You may select an answer to view the feedback, but your answers are not saved.</h2>
                                </fieldset>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @include('layouts/top-navigation-class-preview')
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

        @include('layouts/side-bar-class-preview')

        <form action="#" method="POST">
            {{{$content}}}

            <?php
                for($i=0; $i<count($sub_activities_views); $i++){
                    echo $sub_activities_views[$i];
                }
            ?>

            <div class="wrap ta-c clearfix" style="position: relative;">
                <a class="continueBtn" tabindex="-1" href="
                    @if($next_activity != -1)
                        @if($is_school_preview)
                            {{URL::route('admin.schools.preview_activity', array($next_activity))}}
                        @else
                            {{URL::route('admin.classes.preview_activity', array($class_id, $next_activity))}}
                        @endif
                    @else
                        javascript:alert('No more activities to display');
                    @endif
                ">
                    <span>Save and Continue</span>
                </a>
            </div>
        </form>


        <div id="footer">
            <div class="wrap clearfix">
                <p>
                    &copy; 2016 Career Solutions Publishing. All Rights Reserved
                </p>
            </div>
        </div>

		<!--[if lte IE 7]><script src="js/warning.js"></script><script>window.onload=function(){e("images/warning/")}</script><![endif]-->
		{{{ javascript_include_tag('application') }}}
		{{{ javascript_include_tag('user/base_script') }}}
		{{{ javascript_include_tag('user/yesno') }}}


        @yield('scripts')
    </body>
</html>
