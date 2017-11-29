@if ($user)
<div class="menu-space">
  <div class="content">
    <div class="side-user">
      <div class="info">
        Hello,<br/>
        <a href="#">{{ $user->getFullName() }}</a><br/>
    
		<a href="{{ URL::route('sign-out') }}">Sign Out</a>
      </div>
    </div>
    <ul class="cl-vnavigation">

        @if ($user->hasAccess('admin.index'))
        <li @if (strpos(Route::currentRouteName(), 'admin.index') === 0) class="active" @endif>
             <a href="{{ URL::route('admin.index') }}"><i class="fa fa-home"></i><span>Home</span></a>
        </li>
        @endif

        @if ($user->isTeacher() || $user->isSchoolAdmin())
        <li @if (strpos(Route::currentRouteName(), 'admin.schools.preview') === 0) class="active" @endif>
             <a href="{{ URL::route('admin.schools.preview') }}" target="_blank"><i class="fa fa-home"></i><span>Student Version</span></a>
        </li>
        @endif

        @if ($user->hasAccess('admin.modules.index'))
        <li @if (strpos(Route::currentRouteName(), 'admin.modules') === 0) class="active" @endif>
             <a href="{{ URL::route('admin.modules.index') }}"><i class="fa fa-sitemap"></i><span>Products</span></a>
        </li>
        @endif

        @if ($user->hasAccess('admin.users.index'))
        <li @if (strpos(Route::currentRouteName(), 'admin.users') === 0) class="active" @endif>
             <a href="{{ URL::route('admin.users.index') }}"><i class="fa fa-users"></i><span>Users</span></a>
        </li>
        @endif

        @if ($user->hasAccess('admin.classes.index'))
        <li @if (strpos(Route::currentRouteName(), 'admin.classes') === 0) class="active" @endif>
             <a href="{{ URL::route('admin.classes.index') }}"><i class="fa fa-book"></i><span>Classes</span></a>
        </li>
        @endif

        @if ($user->hasAccess('admin.students.index') && $user->isTeacher() )
        <li @if (strpos(Route::currentRouteName(), 'admin.students') === 0) class="active" @endif>
             <a href="{{ URL::route('admin.students.index') }}"><i class="fa fa-users"></i><span>Students</span></a>
        </li>
        @endif

        @if ($user->hasAccess('admin.schools.index'))
        <li @if (strpos(Route::currentRouteName(), 'admin.schools') === 0) class="active" @endif>
             <a href="{{ URL::route('admin.schools.index') }}"><i class="fa fa-building-o"></i><span>Schools</span></a>
        </li>
        @endif

        @if ($user->hasAccess('admin.teachers.index'))
        <li @if (strpos(Route::currentRouteName(), 'admin.teachers') === 0) class="active" @endif>
             <a href="{{ URL::route('admin.teachers.index') }}"><i class="fa fa-users"></i><span>Teachers</span></a>
        </li>
        @endif

        @if ($user->hasAccess('admin.reports.index'))
        <li @if (strpos(Route::currentRouteName(), 'admin.reports') === 0) class="active" @endif>
             <a href="{{ URL::route('admin.reports.index') }}"><i class="fa fa-book"></i><span>Scores & Reports</span></a>
        </li>
        @endif
        @if ($user->hasAccess('admin.index'))
        <li @if (strpos(Route::currentRouteName(), 'admin.help') === 0) class="active" @endif>
             <a href="{{ URL::route('admin.help') }}"><i class="fa fa-book"></i><span>Instructor Materials</span></a>
        </li>
        @endif
    </ul>
</div>
</div>
@endif
