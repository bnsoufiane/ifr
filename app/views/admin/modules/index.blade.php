<div class="page-head">
    <h2 class="orange_color">Products & Series</h2>
    <div class='help-block'>
        You may add a product from this screen.  An example product is "It's For Real."  Within each product, a number of Series may be added.  These are usually numbered with Roman Numerals.<br/><br/>

        Within each series, you are permitted to add a number of lessons.  Within each lesson, you can add many activities.  <br/><br/>

        A "+" sign indicates that you may add another level (example: a Series under a product, or a lesson under a Series).   Use the ACTION button to perform various tasks such as editing or removing an item.

    </div>
    <ol class="breadcrumb">
        <li><a href="{{ URL::route('admin.index') }}">Home</a></li>
        <li class="active">Products & Series</li>
    </ol>
</div>

<div class="cl-mcont">
    <nav class="toolbar-nav">
        <a class="btn btn-lg btn-rad" href="{{ URL::route('admin.modules.create') }}"><i class="fa fa-plus-square"></i>&nbsp;Add a New Product</a>
    </nav>

    <div class="block-flat no-padding">
        <div class="content">
            <table class="no-border blue modules-table">
                <tbody class="no-border-x no-border-y">
                    @foreach ($modules as $module)
                    <tr>
                        <td>
                            <a href="{{ URL::route('admin.modules.edit', $module->id) }}">{{ $module->title }}</a>
                            <a href="{{ URL::route('admin.modules.series.create', $module->id) }}"><i class="fa fa-plus-square"></i></a>
                        </td>
                        <td class="text-right">
                            <div class="btn-group">
                                <button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown">
                                    Action <span class="caret"></span>
                                </button>
                                <ul role="menu" class="dropdown-menu pull-right">
                                    <li><a href="{{ URL::route('admin.modules.series.create', $module->id) }}">Add Series</a></li>
                                    <li><a href="{{ URL::route('admin.modules.edit', $module->id) }}">Edit Product</a></li>
                                    <li><a href="{{ URL::route('admin.modules.destroy', $module->id) }}" data-action="remove">Remove</a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    @foreach ($module->series as $series)
                    <tr class="series">
                        <td>
                            <a href="{{ URL::route('admin.series.edit', $series->id) }}">{{ $series->title }}</a>
                            <a href="{{ URL::route('admin.series.lessons.create', $series->id) }}"><i class="fa fa-plus-square"></i></a>
                        </td>
                        <td class="text-right">
                            <div class="btn-group">
                                <button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown">
                                    Action <span class="caret"></span>
                                </button>
                                <ul role="menu" class="dropdown-menu pull-right">
                                    <li><a href="{{ URL::route('admin.series.lessons.create', $series->id) }}">Add Lesson</a></li>
                                    <li><a href="{{ URL::route('admin.series.edit', $series->id) }}">Edit Series</a></li>
                                    <li><a href="{{ URL::route('admin.series.destroy', $series->id) }}" data-action="remove">Remove</a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    @foreach ($series->lessons as $lesson)
                    <tr class="lesson">
                        <td><a href="{{ URL::route('admin.lessons.edit', $lesson->id) }}">{{ $lesson->title }}</a>
                            <?php 
                               //$optional_class= ($lesson->optional)?"lesson_optional_off":"lesson_optional_on";
                            ?>
                            <!--<a lesson_id='//$lesson->id}}' class="lesson_optional //$optional_class}}" title="" data-placement="bottom" data-toggle="tooltip" href="javascript:void(0);" data-original-title='"Yes" is optional'>
                                <i class="fa fa-check-circle"></i>
                            </a>-->
                        </td>
                        <td class="text-right">
                            <div class="btn-group">
                                <button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown">
                                    Action <span class="caret"></span>
                                </button>
                                <ul role="menu" class="dropdown-menu pull-right">
                                    <li><a href="{{ URL::route('admin.lessons.edit', $lesson->id) }}">Edit Lesson</a></li>
                                    <li><a href="{{ URL::route('admin.lessons.destroy', $lesson->id) }}" data-action="remove">Remove</a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                    @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@section('scripts')
{{{ javascript_include_tag('admin/modules/index') }}}
@stop
