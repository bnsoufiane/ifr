<div class="page-head">
    <h2 class="orange_color">Reset lessons for : {{"$student->last_name $student->first_name"}}</h2>
</div>

<div class="cl-mcont">
    <form action="{{ URL::route('admin.students.reset_lesson') }}" method="POST">
        <input type="hidden" name="student" value="{{ $student->id }}"/>
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
                                    Lessons
                                </td>
                            </tr>
                            @foreach ($serie->lessons as $lesson)
                                <tr class="series">
                                    <td>
                                        {{ $lesson->title }}
                                    </td>
                                    <td class="text-left" style="padding-left:3%;">
                                        <input type="checkbox" name="reset[]" value="{{$lesson->id}}" />
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
                    <span>Reset Selected Lessons</span>
            </button>
            <button type="reset" value="Reset" class="btn btn-default">Reset</button>

        </div>

    </form>
</div>

@section('scripts')

<script type="text/javascript">
    $(function() {
        $('body').on('click', 'button[type="reset"]', function (event) {
             $('input[checked]').each(function() {
                $(this).removeAttr('checked');
            });
        });

    });
</script>
@stop
