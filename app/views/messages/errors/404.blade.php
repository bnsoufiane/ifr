@extends('messages.base_layout')

@section('content')
    <fieldset>
        <h2>Sorry, the page you are looking for could not be found.</h2>
    </fieldset>

    <fieldset>
        <div class="userAnswer">
            <fieldset>

            </fieldset>

            <fieldset>
                <div class="wrap ta-c clearfix">
                    <a href="{{ URL::route('index') }}">
                    <span  class="continueBtn" tabindex="-1">
                        <span>Continue</span>
                    </span>
                    </a>
                </div>
            </fieldset>
        </div>
    </fieldset>
@stop
