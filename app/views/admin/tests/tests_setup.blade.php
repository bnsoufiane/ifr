<div class="page-head">
    <h2 class="orange_color">{{ $class->name }}: Create Pre- and Post-Tests</h2>

    <div class='help-block'>
        <strong>Step 4:</strong> The <strong>Pre- and Post-Tests</strong> page allows you to develop questions for <strong>Pre- and Post-Tests</strong>
        from the Assessments in the Required Lessons you designated. Students are prompted to answer Pre-Test questions the first time they log in.
        After they have completed all the required lessons and any optional lessons they chose to complete, the
        Post-Test link will appear at the bottom of the Series Lessons drop-down list.<br/><br/>

        To create a Pre- and Post-Test, you will choose questions from your required lessons.
        Click <strong>Save Pre- and Post-Tests</strong> when completing your questions. IMPORTANT - In order to ensure program scoring
        integrity, you cannot change your questions after clicking <strong>Save Pre- and Post-Tests<strong/>.<br/><br/>

        If you do not want to pre- and post-test your students, you have completed all the steps necessary to set up
        this class. You may direct your students to log in and begin using <em>It’s for Real Workplace Ethics</em>.

    </div>
    <ol class="breadcrumb">
        <li><a href="{{ URL::route('admin.index') }}">Home</a></li>
        <li><a href="{{ URL::route('admin.classes.index') }}">{{ $class->name }}</a></li>
        <li class="active">Create Pre- and Post-Tests</li>
    </ol>
</div>

<div class="cl-mcont">
    <form action="{{ URL::route('admin.tests.save_config_tests') }}" method="POST">
        <input type="hidden" name="class" value="{{ $class->id }}"/>

        <?php $is_posttest_set = false; ?>

        <div class="block-flat no-padding">
            <div class="content">
                <table class="no-border blue modules-table">
                    <tbody class="no-border-x no-border-y">
                        @foreach ($series as $serie)
                            <tr>
                                <td>
                                    {{ $serie->module->title }} - {{$serie->title}}
                                </td>
                            <td class="text-left"></td>
                                <td class="text-right">

                                </td>
                                <td class="text-right">

                                </td>
                            </tr>
                            @foreach ($serie->lessons as $lesson)
                                @if($lesson->required($class->id))
                                    <tr class="series">
                                        <td>
                                            {{ $lesson->title }}
                                        </td>
                                    <td class="text-left">
                                        {{ $lesson->topic }}
                                    </td>
                                        <td class="text-right">
                                            Pre-Test
                                        </td>
                                        <td class="text-right">
                                            Post-Test
                                        </td>
                                    </tr>
                                    @foreach ($lesson->activities as $activity)
                                        @if($activity->template_type == "ActivityTemplates\\Assessment")
                                            <?php $sections = $activity->template->sections; ?>
                                            @foreach ($sections as $section)
                                            <tr class="lesson">
                                                    <td>
                                                        {{ $section->title }}
                                                    </td>
                                                <td class="text-left"></td>
                                                    <td class="text-right">
                                                    <input type="checkbox"
                                                           name="pretest_{{$section->id}}" <?php $str = (empty($test_configuration["pretest_" . $section->id]) ? '' : 'checked'); echo $str; ?> />
                                                    </td>
                                                    <td class="text-right">
                                                        <?php
                                                    if (!empty($test_configuration["posttest_" . $section->id])) {
                                                                $is_posttest_set = true;
                                                            }
                                                        ?>
                                                    <input type="checkbox"
                                                           name="posttest_{{$section->id}}" <?php $str = (empty($test_configuration["posttest_" . $section->id]) ? '' : 'checked'); echo $str; ?> />
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    @endforeach
                                @endif
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="wrap ta-c clearfix">
            @if(!$is_posttest_set)
                <button type="submit" class="btn btn-primary">
                        <span>Save Pre- and Post-Tests</span>
                </button>
                <button type="reset" value="Reset" class="btn btn-default">Reset</button>
            @endif
        </div>

    </form>
</div>

@section('scripts')
    {{{ javascript_include_tag('admin/modules/index') }}}
@stop
