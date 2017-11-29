<div class="page-head">
    <h2 class="orange_color">Add a New School District</h2>
    <ol class="breadcrumb">
        <li><a href="{{ URL::route('admin.index') }}">Home</a></li>
        <li><a href="{{ URL::route('admin.schools.index') }}">Schools</a></li>
        <li class="active">Add a New School District</li>
    </ol>
</div>

<div class="cl-mcont">
    <div class="block-flat">
        <div class="content">
            @include('admin/school_districts/_form')
        </div>
    </div>
</div>
