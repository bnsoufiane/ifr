<?php

namespace Curotec\Presenters;

class ClassAnsweredLessonPresenter extends Presenter {
	private $lesson = null;
	private $student = null;

	public function __construct($lesson, $options) {
		$this->lesson = $lesson;
		$this->student = $options['student'];
	}

	public function id() {
		return $this->lesson->id;
	}

	public function title() {
		return $this->lesson->title;
	}

	public function progress() {
		$allActivities = $this->lesson->activities->map(function ($activity) {
			return $activity->id;
		});
		$answeredActivities = array();

		$this->student->answersByLesson($this->lesson)
					  ->each(function ($answer) use (&$answeredActivities) {
						  $answeredActivities[$answer->activity->id] = true;
					  });

		return count($answeredActivities) . ' out of ' . count($allActivities);
	}

	/**
	 * Returns a percent of correctly answered activities.
	 */
	public function grade() {
		$correctCount = 0; // number of correctly answered activities
		$grades = $this->student->gradeByLesson($this->lesson);

		if (count($grades) == 0) {
			return 0;
		}

		foreach ($grades as $grade) {
			if ($grade == \StudentAnswer::CORRECT || $grade == \StudentAnswer::NOT_GRADED) {
				$correctCount += 1;
			}
		}

		return round(($correctCount / count($grades)) * 100, 1);
	}
}
