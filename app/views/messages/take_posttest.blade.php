@extends('messages.base_layout')

@section('content')
<fieldset>
    <h2>Now it's time to take the post-test.</h2>
</fieldset>

<fieldset>
    <div class="userAnswer">
        <fieldset>

        </fieldset>

        <fieldset>
            <div class="wrap ta-c clearfix">
                <a href="{{ URL::route('index') }}">  
                    <button type="submit" class="continueBtn" tabindex="-1">
                        <span>Take Post-test</span>
                    </button>
                </a>
            </div>
        </fieldset>
    </div>
</fieldset>
@stop
