<div class="page-head">
    <h2 class="orange_color">Add a New School</h2>
    <div class='help-block'>
        You may add a new school from this tab.  You can also choose to add an existing administrator or create a new one.<br/><br/>

        You can also select which products the new school will be able to access.
    </div>
    <ol class="breadcrumb">
        <li><a href="{{ URL::route('admin.index') }}">Home</a></li>
        <li><a href="{{ URL::route('admin.schools.index') }}">Schools</a></li>
        <li class="active">Add a New School</li>
    </ol>
</div>

<div class="cl-mcont">
    <div class="block-flat">
        <div class="content">
            @include('admin/schools/_form')
        </div>
    </div>
</div>
