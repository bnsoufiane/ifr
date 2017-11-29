<div class="page-head">
    <h2 class="orange_color">Edit Product</h2>
    <div class='help-block'>
        This screen allows you to change the name of a product or switch the "skin."  The skin is a color scheme, or look and feel associated with the product.
    </div>
    <ol class="breadcrumb">
        <li><a href="{{ URL::route('admin.index') }}">Home</a></li>
        <li><a href="{{ URL::route('admin.modules.index') }}">Products & Series</a></li>
        <li>{{ $module->title }}</li>
        <li class="active">Edit Product</li>
    </ol>
</div>

<div class="cl-mcont">
    <div class="block-flat">
        <div class="content">
            @include('admin/modules/_form')
        </div>
    </div>
</div>
