<div class="page-head">
    <h2 class="orange_color">"{{ $serie->title }}" Lessons</h2>
    <div class='help-block'>
        This screen presents the available lessons that have activities that you can use for your pre-test or post-test.  Use the check boxes next to each activity to determine whether or not that activity should appear on the pre-test or post-test.  Click "Save Configuration" at the bottom of the screen when you are finished.
    </div>
    <ol class="breadcrumb">
        <li><a href="{{ URL::route('admin.index') }}">Home</a></li>
        <li><a href="{{ URL::route('admin.tests.index') }}">{{ $class->name }}</a></li>
        <li><a href="{{ URL::route('admin.tests.modules', array($class->id)) }}">{{$module->title}}</a></li>
        <li><a href="{{ URL::route('admin.tests.series', array($class->id, $module->id)) }}">{{ $serie->title }}</a></li>
        <li class="active">Tests configuration</li>
    </ol>
</div>

<div class="cl-mcont">
    <form action="{{ URL::route('admin.tests.save_config_tests') }}" method="POST">
        <input type="hidden" name="class" value="{{ $class->id }}"/>
        <input type="hidden" name="module" value="{{ $module->id }}"/>
        <input type="hidden" name="serie" value="{{ $serie->id }}"/>

        <div class="block-flat no-padding">
            <div class="content">
                <table class="no-border blue modules-table">
                    <tbody class="no-border-x no-border-y">
                        @foreach ($lessons as $lesson)
                        <tr>
                            <td class="lesson_title" >
                                {{ $lesson->title }}
                            </td>
                            <td class="text-right">
                                Pre-Test
                            </td>
                            <td class="text-right">
                                Post-Test
                            </td>
                        </tr>
                        @foreach ($lesson->activities as $activity)
                        <tr class="lesson" >
                            <td class="activity_title">
                                {{ $activity->title }}
                            </td>
                            <td class="text-right">
                                <input type="checkbox" name="pretest_{{$lesson->id}}_{{$activity->id}}" <?php $str = (empty($test_configuration["pretest_".$lesson->id."_".$activity->id])?'':'checked'); echo $str; ?> />
                            </td>
                            <td class="text-right">
                                <input type="checkbox" name="posttest_{{$lesson->id}}_{{$activity->id}}" <?php $str = (empty($test_configuration["posttest_".$lesson->id."_".$activity->id])?'':'checked'); echo $str; ?> />
                            </td>
                        </tr>

                        @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="wrap ta-c clearfix">
            <button type="submit" class="continueBtn" tabindex="-1">

                <a href="{{ URL::route('admin.tests.save_config_tests') }}">
                    <span>Save Configuration</span> </a>
            </button>    
        </div>

    </form>
</div>

@section('scripts')
{{{ javascript_include_tag('admin/modules/index') }}}
@stop
