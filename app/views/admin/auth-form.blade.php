<div class="cl-mcont">
  <div class="row">
  <div class="col-sm-12 col-md-12">
    <div class="block-flat">
      <div class="header">							
        <h3>Please log in</h3>
      </div>
      <div class="content">

      @if ($error)
          <div class="alert alert-danger alert-white rounded">
              <button data-dismiss="alert" class="close" type="button">×</button>
              <div class="icon"><i class="icon-remove-sign"></i></div>
              <strong>Error!</strong> {{$error}}
          </div>
      @endif

      {{ Form::open(array('url' => 'admin/login')) }}
        <div class="form-group">
          <label>Username</label>
          {{ Form::text('username', Input::old('username'), array('placeholder' => 'Enter your username', 'class' => 'form-control')) }}
        </div>
        <div class="form-group"> 
          <label>Password</label> <input name="password" type="password" placeholder="Password" class="form-control">
        </div> 
        <div class="checkbox">
          <label> <input type="checkbox" class="icheck" name="remember"> Remember me </label> </div>
          <button class="btn btn-primary" type="submit">Submit</button>
          <button class="btn btn-default">Cancel</button>
      {{ Form::close() }}
      
      </div>
    </div>
  </div>
  </div>
</div>