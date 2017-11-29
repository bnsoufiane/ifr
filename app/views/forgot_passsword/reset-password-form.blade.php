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
            {{{ Form::open(array('url' => URL::route('save_reset_password'))) }}}
                <div class="content">

                    <input type="hidden" name="user_id" id="user_id" value="{{$user_id}}"/>
                    <input type="hidden" name="reset_code" id="reset_code" value="{{$reset_code}}"/>

                    <h4 class="title">Please enter a unique password.<br/><br/>
                        Password must be a minimum of 5 characters and can contain letters, numbers and special
                        characters.
                    </h4>


                    @if ($error)
                        <div class="alert alert-danger alert-white rounded">
                            <button data-dismiss="alert" class="close" type="button">×</button>
                            <div class="icon"><i class="icon-remove-sign"></i></div>
                            <strong>Error!</strong> {{$error}}
                        </div>
                    @endif


                    <div class="form-group">
                        <div class="col-sm-12">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                                <input name="new_password" type="password" placeholder="New Password"
                                       class="form-control">
                            </div>
                            @if ($errors->has('new_password'))
                                <span class="help-block alert alert-danger">{{ $errors->first('new_password') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-12">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                                <input name="new_password_confirmation" type="password"
                                       placeholder="Confirm the new password" class="form-control">
                            </div>

                            @if ($errors->has('new_password_confirmation'))
                                <span class="help-block alert alert-danger">{{ $errors->first('new_password_confirmation') }}</span>
                            @endif

                        </div>
                    </div>


                    <div class="foot row">
                        <div class="col-sm-6">
                            <button class="btn btn-primary" data-dismiss="modal" type="submit">Submit</button>
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
