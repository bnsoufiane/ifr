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
         if ($template_type == 'ActivityTemplates\Calculation') {
             $body_class="rightFancybox";
         }else{
             $body_class="";
         }
    ?>
    
    <body class="{{$body_class}}">

		<input type="hidden" name="preview" value="true" />
	
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

        <form action="{{ URL::route('activities.store') }}" method="POST">
            {{{$content}}}
            <?php
                for($i=0; $i<count($sub_activities_views); $i++){
                    echo $sub_activities_views[$i];
                }
            ?>

            <div class="wrap ta-c clearfix">
                <button type="submit" class="continueBtn" disabled="disabled" tabindex="-1">
                    <span>Save and Continue</span>
                </button>
            </div>
        </form>


		<!--[if lte IE 7]><script src="js/warning.js"></script><script>window.onload=function(){e("images/warning/")}</script><![endif]-->
		{{{ javascript_include_tag('application') }}}
		{{{ javascript_include_tag('application_controls') }}}
		{{{ javascript_include_tag('user/yesno') }}}
		{{{ javascript_include_tag('user/assessment') }}}
		{{{ javascript_include_tag('user/multiple_answers') }}}

        @yield('scripts')
    </body>
</html>
