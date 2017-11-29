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
        <title>
            {{$page_title}}
        </title>
        <meta http-equiv="Content-type" content="text/html; charset=utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <link rel="shortcut icon" href="<?php echo asset('/admin-static/images/favicon.png'); ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

        <link href='http://fonts.googleapis.com/css?family=Crete+Round:400,400italic' rel='stylesheet' type='text/css'>
        <link href='http://fonts.googleapis.com/css?family=Bitter:400,700,400italic' rel='stylesheet' type='text/css'>
		{{{ stylesheet_link_tag('application') }}}

        <style>
            @if($print)
                body, .pageHead .headTitle.print_page_title, .mainContentWrapper, .pageWrap.newInner .mainContent {
                    background-color: #FFF !important;
                }
            @endif
        </style>

    </head>
    <?php 
         if ($template_type == 'ActivityTemplates\Calculation') {
             $body_class="rightFancybox";
         }else{
             $body_class="";
         }
    ?>
    
    <body class="{{$body_class}}">

        <div class="pageWrap newInner blogPage clearfix" style="width: 90% !important; padding-left: 30px;">
            <div class="wrap clearfix" style="max-width: 100% !important;">
                <div class="pageHead">
                    <div class="headTitle print_page_title" style="font-size: 22px; color: black;">
                        {{$page_title}}
                    </div>
                </div>
            </div>
        </div>

        <div class="pageWrap newInner blogPage clearfix" style="width: 90% !important; padding-left: 30px;">
            <div class="wrap clearfix" style="max-width: 100% !important;">
                @section('content_unwrapped')
                <div class="mainContentWrapper">
                    <div class="mainContent">
                        <?php
                            for($i=0; $i<count($answers_views); $i++){
                                echo $answers_views[$i];
                            }

                            for($i=0; $i<777; $i++){
                                echo "&nbsp;";
                            }

                        ?>
                    </div>
                </div>
                @show
            </div>
        </div>

		<!--[if lte IE 7]><script src="js/warning.js"></script><script>window.onload=function(){e("images/warning/")}</script><![endif]-->
		{{{ javascript_include_tag('application') }}}
		{{{ javascript_include_tag('application_controls') }}}
		{{{ javascript_include_tag('user/yesno') }}}
		{{{ javascript_include_tag('user/assessment') }}}
		{{{ javascript_include_tag('user/multiple_answers') }}}

        @yield('scripts')
    </body>
</html>
