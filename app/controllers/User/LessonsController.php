<?php

namespace User;

use App;
use Input;
use Redirect;

class LessonsController extends BaseController {
	public function show($id) {
		$lesson = \Lesson::find($id);

		if (!$lesson) {
			App::abort(404);
		}

		$activities = $lesson->activities()->orderBy("order")->get();;
                
                $activity = 0;
                foreach ($activities as $value) {
                    if($value->template_type != 'ActivityTemplates\Assessment'){
                        $activity = $value;
                        break;
                    }
                }

		if (!$activity) {
			App::abort(404);
		}

		return Redirect::route('activities.show', $activity->id);
	}
}
