<?php

namespace Curotec\Presenters;

class AdminUserPresenter {
    protected $user;

    public function __construct($user) {
        $this->user = $user;
    }

    public function hasAccess($permission) {
        return $this->user->hasAccess($permission);
    }

    public function isTeacher() {
        return $this->user->isTeacher();
    }

    public function isSchoolAdmin() {
        return $this->user->isSchoolAdmin();
    }

    /**
     * Returns a user's full name
     */
    public function getFullName() {
        return $this->user->first_name . ' ' . $this->user->last_name;
    }
}
