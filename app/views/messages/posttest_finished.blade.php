@extends('messages.base_layout')

@section('content')
    <fieldset style="text-align: center" ;>
        <h2>You completed the post-test. Your score is {{$score}}%.</h2>
    </fieldset>

    <fieldset>
    <div class="userAnswer">
        <fieldset>

        </fieldset>

        <fieldset>
            <div class="wrap ta-c clearfix">
                    @if ($failed)
                        <span>You have failed the post-test. You can ask your teacher to reset the test so you can take it again.</span>
                    @endif
            </div>
        </fieldset>
    </div>
    </fieldset>
@stop
