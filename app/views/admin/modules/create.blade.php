<div class="page-head">
    <h2 class="orange_color">Add a New Product</h2>
    <div class='help-block'>
        You may add a new product from this screen.  You may also select the "skin" which will change the appearance of the product based on predefined themes.  A product is the overarching course that will package together various Series that contain lessons and activities.
    </div>
    <ol class="breadcrumb">
        <li><a href="{{ URL::route('admin.index') }}">Home</a></li>
        <li><a href="{{ URL::route('admin.modules.index') }}">Products & Series</a></li>
        <li class="active">Add a New Product</li>
    </ol>
</div>

<div class="cl-mcont">
    <div class="block-flat">
        <div class="content">
            @include('admin/modules/_form')
        </div>
    </div>
</div>
