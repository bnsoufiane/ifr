<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="<?php echo asset('/admin-static/images/favicon.png'); ?>">

    <title>{{isset($page_title)?$page_title:'IFR'}}</title>
	<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,400italic,700,800' rel='stylesheet' type='text/css'>
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
  <div id="head-nav" class="navbar navbar-default navbar-fixed-top">
    <div class="container-fluid">
    </div>
  </div>

        <div id="header_logo" class="clearfix">
            <a href="#" class="logo">
                <img src="<?php echo asset('/assets/logo.png'); ?>" alt="">
            </a>
        </div>
  
	<div id="cl-wrapper" class="fixed-menu">
            <div class="cl-sidebar" data-position="right" data-step="1" data-intro="<strong>Fixed Sidebar</strong> <br/> It adjust to your needs." >
			<div class="cl-toggle"><i class="fa fa-bars"></i></div>
			<div class="cl-navblock">
                @include('admin/layout/side-bar')
            </div>
	</div>
            
		<div class="container-fluid" id="pcont">
            @if ($messages->success)
            <div class="alert alert-success">
                {{ $messages->success }}
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            </div>
            @endif

            @if ($messages->error)
            <div class="alert alert-danger">
                {{ $messages->error }}
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            </div>
            @endif

            {{{$content}}}
		</div>
		
	</div>

  <a  class="various" href="#session_expired" href="javascript:;" class="disabled" style="position: absolute;right: -100px; top: 100px; visibility: hidden;"> click here</a>

      <div id="session_expired" style="font-size: 1.3em; text-align: center; display: none;">
          Your session has expired. Please login again.</br></br>
          <a href="{{ URL::route('sign-in') }}">Click here</a>
      </div>

	{{ javascript_include_tag('admin') }}
	
    @yield('scripts')

<script>
    
    $(".fancyVideo").click(function() {
      $.fancybox({
        maxWidth	: 800,
        maxHeight	: 455,
        fitToView	: false,
        autoSize	: false,
        closeClick	: false,
        openEffect	: 'none',
        closeEffect	: 'none',
        'title'			: this.title,
        'href'			: this.href.replace(new RegExp("watch\\?v=", "i"), 'v/'),
        'type'			: 'swf',
        'swf'			: {
        'wmode'				: 'transparent',
        'allowfullscreen'	: 'true'
        }
      });
      return false;
    });
    
    $(".fancyPopup").fancybox({
      maxWidth	: 800,
      maxHeight	: 455,
      fitToView	: false,
      autoSize	: false,
      closeClick: false,
      openEffect: 'none',
      closeEffect: 'none'
    });

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

    var interval = setInterval(function(){
        $.ajax({
            url: '/checkSession',
            method: 'GET',
            dataType: 'html'
        }).done(function (response) {
            if(response=="inactive"){
                $('a[href="#session_expired"]').trigger( "click" );
            }
        });
    }, 60*60*1000 + 10*1000);

</script>
  </body>
</html>
