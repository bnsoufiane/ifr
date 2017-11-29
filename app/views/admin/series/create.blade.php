<div class="page-head">
    <h2 class="orange_color">Add a New Series</h2>
    <div class='help-block'>
        You can add a new Series within a product from this screen.  Each Series holds the various Lessons and activities within a product.
    </div>
    <ol class="breadcrumb">
        <li><a href="{{ URL::route('admin.index') }}">Home</a></li>
        <li><a href="{{ URL::route('admin.modules.index') }}">Products & Series</a></li>
        <li>{{ $module->title }}</li>
        <li class="active">Add a New Series</li>
    </ol>
</div>

<div class="cl-mcont">
    <div class="block-flat">
        <div class="content">
            @include('admin/series/_form')
        </div>
    </div>
</div>
