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


	<div id="cl-wrapper" class="fixed-menu">

		<div class="container-fluid" id="pcont" style="padding: 0 50px;">

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
