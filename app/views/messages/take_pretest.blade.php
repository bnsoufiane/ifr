@extends('messages.base_layout')

@section('content')
    <fieldset>
        <h2>The pre-test you are about to take will help you identify what you already know about work ethics and what
            you need to learn.</h2>
    </fieldset>

    <fieldset>
    <div class="userAnswer">
        <fieldset>

        </fieldset>

        <fieldset>
            <div class="wrap ta-c clearfix">
                    <a href="{{ URL::route('index') }}">
                    <button type="submit" class="continueBtn" tabindex="-1">
                        <span>Take Pre-test</span>
                    </button>
                </a>
            </div>
        </fieldset>
    </div>
    </fieldset>
@stop
