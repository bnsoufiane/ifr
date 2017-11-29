<div class="page-head">
    <h2 class="orange_color">Edit Series</h2>
    <div class='help-block'>
        You may change the name of a Series from this screen.
    </div>
    <ol class="breadcrumb">
        <li><a href="{{ URL::route('admin.index') }}">Home</a></li>
        <li><a href="{{ URL::route('admin.modules.index') }}">Product & Series</a></li>
        <li>{{ $series->module->title }}</li>
        <li>{{ $series->title }}</li>
        <li class="active">Edit Series</li>
    </ol>
</div>

<div class="cl-mcont">
    <div class="block-flat">
        <div class="content">
            @include('admin/series/_form')
        </div>
    </div>
</div>
