<?php

namespace Curotec\Presenters;

class ClassAnswerPresenter extends Presenter {

    private $answer = null;

    public function __construct($answer) {
        $this->answer = $answer;
    }

    public function render() {
        if($this->answer->answerType==null){
            return null;
        }
        
        $meta = $this->answer->answerType->getMetaData();

        return \View::make($meta['admin_template'])
                        ->with('answer', $this)
                        ->with('data', $this->answer->answerType)
                        ->render();
    }

    public function id() {
        return $this->answer->id;
    }

    public function activityTitle() {
        return $this->answer->activity->title;
    }

    public function tracked_time() {
        return $this->secondsToTime($this->answer->time_needed);
    }

    private function secondsToTime($inputSeconds) {

        $inputSeconds = $inputSeconds / 1000;

        $secondsInAMinute = 60;
        $secondsInAnHour = 60 * $secondsInAMinute;
        $secondsInADay = 24 * $secondsInAnHour;

        // extract days
        $days = floor($inputSeconds / $secondsInADay);

        // extract hours
        $hourSeconds = $inputSeconds % $secondsInADay;
        $hours = floor($hourSeconds / $secondsInAnHour);

        // extract minutes
        $minuteSeconds = $hourSeconds % $secondsInAnHour;
        $minutes = floor($minuteSeconds / $secondsInAMinute);

        // extract the remaining seconds
        $remainingSeconds = $minuteSeconds % $secondsInAMinute;
        $seconds = ceil($remainingSeconds);

        // return the final array
        /* $obj = array(
          'd' => (int) $days,
          'h' => (int) $hours,
          'm' => (int) $minutes,
          's' => (int) $seconds,
          ); */

        $days = ($days != 0 ) ? "$days days " : '';
        $hours = ($hours != 0 ) ? "$hours hours " : '';
        $minutes = ($minutes != 0 ) ? "$minutes minutes " : '';
        $seconds = "$seconds seconds";

        $duration_string = $days . $hours . $minutes . $seconds;

        return $duration_string;
    }

}
