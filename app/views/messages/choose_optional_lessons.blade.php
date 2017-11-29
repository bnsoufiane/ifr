@extends('messages.base_layout')

@section('content')
<div class="cl-mcont">

    <div class="block-flat no-padding">
        <div class="content">
            <table class="no-border blue modules-table">
                <tbody class="no-border-x no-border-y">
                    @foreach ($modules as $module)
                    <tr>
                        <td>
                            <a href="javascript:void(0);">{{ $module->title }}</a>
                        </td>
                    </tr>
                    @foreach ($module->series as $series)
                    <tr class="series">
                        <td>
                            <a href="javascript:void(0);">{{ $series->title }}</a>
                        </td>
                    </tr>
                    @foreach ($series->lessons as $lesson)
                    @if ($lesson->optional)
                    <tr class="lesson">
                        <td><a href="javascript:void(0);">{{ $lesson->title }}</a>
                            <?php
                            $user = \Sentry::getUser();
                            $student_optional_lesson = \DB::table('student_optional_lesson')
                                            ->where('lesson_id', $lesson->id)
                                            ->where('student_id', $user->id)->first();
                            $optional_class = (!$student_optional_lesson) ? "lesson_optional_off" : "lesson_optional_on";
                            ?>
                            <a lesson_id='{{$lesson->id}}' class="lesson_optional {{$optional_class}}" title="" data-placement="bottom" data-toggle="tooltip" href="javascript:void(0);" data-original-title='"Yes" is optional'>
                                <i class="fa fa-check-circle"></i>
                            </a>
                        </td>
                    </tr>
                    @endif
                    @endforeach
                    @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <a class="btn btn-lg btn-rad" href="{{ URL::route('user.submit_optional_lessons') }}">Submit</a>

</div>
@stop
