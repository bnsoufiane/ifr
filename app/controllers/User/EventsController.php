<?php

namespace User;

use Input;

class EventsController extends BaseController
{

    public function store()
    {
        $user = \Sentry::getUser();

        if ($user && $user->isStudent()) {
            try {
            $activity = \Activity::find(Input::get('activity_id'));

            $event = new \StudentEvent();

            $event->event_type = $this->get_event_id(Input::get('event_type'));

            $event->referTo()->associate($activity);
            $event->user()->associate($user);
            $event->save();

            } catch (\Exception $e) {

            }

            return array('ok' => true);
        }
    }

    private function get_event_id($event_type)
    {

        if ($event_type == "VIEWED_LESSON")
            return \StudentEvent::VIEWED_LESSON;
        if ($event_type == "REQUESTED_FEEDBACK")
            return \StudentEvent::REQUESTED_FEEDBACK;
        if ($event_type == "LISTENED_AUDIO")
            return \StudentEvent::LISTENED_AUDIO;
        if ($event_type == "PRINTED_LESSON")
            return \StudentEvent::PRINTED_LESSON;
    }

}
