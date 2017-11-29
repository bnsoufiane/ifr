<?php

class StudentAnswer extends Eloquent implements StudentAnswerGraded {

    const NOT_GRADED = 0;
    const INCORRECT = 1;
    const CORRECT = 2;

    public function student() {
        return $this->belongsTo('User');
    }

    public function activity() {
        return $this->belongsTo('Activity');
    }

    public function scopeByActivity($query, $activityId) {
        return $query->whereHas('activity', function ($q) use ($activityId) {
                    return $q->where('id', $activityId);
                });
    }

    /**
     * Adds grades to answers data.
     */
    public function getGrade() {
        if ($this->answerType instanceof StudentAnswerGraded) {
            return $this->answerType->getGrade();
        } else {
            return self::NOT_GRADED;
        }
    }

    /**
     * Returns a class instance that contains all answer's details.
     */
    public function answerType() {
        return $this->morphTo();
    }

}
