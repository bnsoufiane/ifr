<?php

namespace Curotec\Presenters;

class UserPresenter {

    protected $user;

    public function __construct($user) {
        $this->user = $user;
    }

    public function getFirstName() {
        return $this->user->first_name;
    }

    public function getId() {
        return $this->user->id;
    }

}
