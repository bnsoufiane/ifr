@extends('messages.base_layout')

@section('content')
<fieldset>
    <h2>You correctly answered {{$correct_answers}} out of {{$total_questions}} questions. Your score is {{$score}}%.</h2>
</fieldset>

<fieldset>
    <div class="userAnswer">
        <fieldset>

        </fieldset>

        <fieldset>
            <div class="wrap ta-c clearfix">
                <?php
                if ($failed && $attempts < 3) {
                    ?>
                    <a href="{{ URL::route('user.reset_lesson', $lesson->id) }}">  
                        <button type="submit" class="continueBtn" tabindex="-1">
                            <span>Reset</span>
                        </button>
                    </a>
                    <?php
                }
                ?>
                <?php
                if (!$failed) {
                    ?>
                    <a href="{{ URL::route('user.go_to_next_lesson', $lesson->id) }}">
                        <button type="submit" class="continueBtn" tabindex="-1">
                            <span>Continue</span>
                        </button>
                    </a>
                    <?php
                }
                ?>

                <?php
                if ($failed && $attempts >= 3) {
                    ?>
                    <span>You failed you this lesson you can ask your teacher to reset the lesson manually.</span>
                    <?php
                }
                ?>

            </div>
        </fieldset>
    </div>
</fieldset>
@stop
