<div class="middle-login">

    <div class="login_page_logo">
        <img src="{{asset('/uploads/csp_logo.png')}}" alt=""/>
    </div>

    <div class="login_page_image">
        <img src="{{asset('/uploads/72240482bf.jpg')}}" alt=""/>
    </div>

	<div class="block-flat">
        <div class="header">
			<h3 class="text-center">It's For Real</h3>
		</div>
		<div>
			{{{ Form::open(array('url' => URL::route('sign-in'))) }}}
			<form style="margin-bottom: 0px !important;" class="form-horizontal" action="index.html">
				<div class="content">

					<h4 class="title">Login Access</h4>

					@if ($error)
						<div class="alert alert-danger alert-white rounded">
							<button data-dismiss="alert" class="close" type="button">×</button>
							<div class="icon"><i class="icon-remove-sign"></i></div>
							<strong>Error!</strong> {{$error}}
						</div>
                    @elseif($success)
                        <div class="alert alert-success alert-white rounded">
                            <button data-dismiss="alert" class="close" type="button">×</button>
                            <div class="icon"><i class="icon-remove-sign"></i></div>
                            <strong>Success!</strong> {{$success}}
                        </div>
					@endif

					<div class="form-group">
						<div class="col-sm-12">
							<div class="input-group">
								<span class="input-group-addon"><i class="fa fa-user"></i></span>
								{{{ Form::text('username', Input::old('username'), array('placeholder' => 'Enter your username', 'class' => 'form-control')) }}}
							</div>
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-12">
							<div class="input-group">
								<span class="input-group-addon"><i class="fa fa-lock"></i></span>
								<input name="password" type="password" placeholder="Password" class="form-control">
							</div>
						</div>
					</div>

				<div class="foot row">
					<label class="col-sm-6 text-left">
						<input type="checkbox" class="icheck" name="remember">
						Remember me
					</label>
                    </div>

                    <div class="foot row">
                        <a class="col-sm-6 text-left" href="{{ URL::route('forgot_password') }}">Forgot password?</a>

					<div class="col-sm-6">
						<button class="btn btn-primary" data-dismiss="modal" type="submit">Log me in</button>
					</div>
				</div>

				</div>
			{{{ Form::close() }}}
		</div>
	</div>

    <div id="footer">
        <div class="wrap clearfix">
            <p style="font-weight: bold">
                Important: You must click Save and Continue<br/>
                after every activity <span class="underline">to save</span> your work or <span class="underline">to advance</span>
                to the next activity.<br/><br/>

                Look for the <span class="green">green</span> checkmarks on the left of the lesson screens!<br/>
                Each button must contain these marks before you take the Assessment.
            </p>

            <p class="copyright">
                Copyright &copy; 2016 Career Solutions Publishing. All Rights Reserved
            </p>
        </div>
    </div>

</div>
