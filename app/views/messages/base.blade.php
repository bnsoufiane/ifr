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
        {{{ stylesheet_link_tag('message') }}}
    </head>

    <body class="messages">

        <!-- Top navbar -->
        <div class="pageNavBg"></div>
        <div id="header" class="clearfix">
            <a href="#" class="logo">
                <img src="<?php echo asset('/assets/logo.png'); ?>" alt="">
            </a>

        </div>

        <div class="pageBackground">
            <img src="<?php echo asset('/uploads/lightbulbs.jpg'); ?>" alt="">
        </div>


        {{{$content}}}

        <div id="footer">
            <div class="wrap clearfix">
                <p>
                    &copy; 2016 Career Solutions Publishing. All Rights Reserved
                </p>
            </div>
        </div>

                <!--[if lte IE 7]><script src="js/warning.js"></script><script>window.onload=function(){e("images/warning/")}</script><![endif]-->
        {{ javascript_include_tag('message') }}
    </body>
</html>
