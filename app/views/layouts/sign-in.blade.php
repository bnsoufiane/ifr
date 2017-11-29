<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="<?php echo asset('/admin-static/images/favicon.png'); ?>">

    <title>{{isset($page_title)?$page_title:'IFR'}}</title>
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,400italic,700,800' rel='stylesheet'
          type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Raleway:100' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Open+Sans+Condensed:300,700' rel='stylesheet' type='text/css'>

    {{ stylesheet_link_tag('admin') }}

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <![endif]-->
</head>
<body>

<!-- Fixed navbar -->
<div id="head-nav" class="navbar navbar-default navbar-fixed-top login_page">
    <div class="container-fluid">
    </div>
</div>

<div id="header_logo" class="clearfix login_page">
    <a href="#" class="logo">
        <img src="<?php echo asset('/assets/logo.png'); ?>" alt="">
    </a>
</div>

<div id="cl-wrapper" class="fixed-menu login_page">
    <div class="cl-sidebar" data-position="right" data-step="1"
         data-intro="<strong>Fixed Sidebar</strong> <br/> It adjust to your needs.">
        <div class="cl-toggle"><i class="fa fa-bars"></i></div>
        <div class="cl-navblock">
            <div class="menu-space">
                <div class="content">
                    <div class="side-user">
                        <div class="info">
                            Welcome to <strong><em>It's for Real Workplace Ethics</em></strong> that will start you on your way to a rewarding career.<br/><br/>

                            Employers say professional ethics and behavior make the difference between success and failure in a job.<br/><br/>

                            You will learn what employers want you to know about professional ethics before you begin work.<br/><br/>

                            Good luck as you match your workplace ethics to job requirements.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid" id="pcont">&nbsp;</div>

</div>


<div id="cl-wrapper" class="login-container login_page">
    {{{ $content }}}
</div>

{{ javascript_include_tag('admin') }}

@yield('scripts')
</body>
</html>
