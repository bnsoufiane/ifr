<div class="page-head">
    <h2 class="orange_color">{{ $class->name }}: Required and Optional Lessons</h2>

    <div class='help-block'>
        <strong>Step 3:</strong> By selecting <strong>Required</strong> or <strong>Optional</strong> lessons below, you
        identify lessons students must complete for a score and others they may complete for extra credit. In the
        Student Version of <em>It’s for Real Workplace Ethics</em>, Required Lessons are identified with an “R” in the
        list of lessons.<br/><br/>
        All lessons default to required and will change to optional <em>only</em> if you click <strong>Optional</strong>
        beside the lesson title. You can change the required lessons in the future. Required and optional lessons scores
        are reported separately in <strong>Scores & Reports.</strong><br/><br/>
        Click the <strong>Save Required Lessons</strong> button to save your lessons or click <strong>Reset</strong> to start over, then return to the main Classes page and advance to
        Step 4 where you will create Pre- and Post-Tests.

    </div>
    <ol class="breadcrumb">
        <li><a href="{{ URL::route('admin.index') }}">Home</a></li>
        <li><a href="{{ URL::route('admin.classes.index') }}">{{ $class->name }}</a></li>
        <li class="active">Required and Optional Lessons</li>
    </ol>
</div>

<div class="cl-mcont">
    <form action="{{ URL::route('admin.classes.save_optional_lessons_setup') }}" method="POST">
        <input type="hidden" name="class" value="{{ $class->id }}"/>

        <div class="block-flat no-padding">
            <div class="content">
                <table class="no-border blue modules-table">
                    <tbody class="no-border-x no-border-y">
                        @foreach ($series as $serie)
                            <tr>
                                <td>
                                    {{ $serie->module->title }} - {{$serie->title}}
                                </td>
                            <td class="text-left">
                                Lesson Topic
                            </td>
                                <td class="text-right">
                                    Required
                                </td>
                                <td class="text-right">
                                    Optional
                                </td>
                            </tr>
                            @foreach ($serie->lessons as $lesson)
                                <tr class="series">
                                    <td>
                                        {{ $lesson->title }}
                                    </td>
                                <td class="text-left">
                                    {{ $lesson->topic }}
                                </td>
                                    <td class="text-right">
                                    <input type="radio" name="optional_{{$lesson->id}}"
                                           value="required" <?php $str = (empty($opt_configuration["optional_" . $lesson->id]) ? 'checked' : ''); echo $str; ?> />
                                    </td>
                                    <td class="text-right">
                                    <input type="radio" name="optional_{{$lesson->id}}"
                                           value="optional" <?php $str = (empty($opt_configuration["optional_" . $lesson->id]) ? '' : 'checked'); echo $str; ?> />
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="wrap ta-c clearfix">
            <button type="submit" class="btn btn-primary">
                    <span>Save Required Lessons</span>
            </button>
            <button type="reset" value="Reset" class="btn btn-default">Reset</button>

        </div>

    </form>
</div>

@section('scripts')
    {{{ javascript_include_tag('admin/modules/index') }}}


    <script type="text/javascript">
        $(function () {
        $('body').on('click', 'button[type="reset"]', function (event) {
                $('input[checked]').each(function () {
                $(this).removeAttr('checked');
            });
        });

    });
    </script>
@stop
