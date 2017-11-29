<?php

namespace Curotec\Composers;

use Curotec\Presenters\UserPresenter;
use Sentry;

// Adds a user's data to all student-facing views.
class UserComposer {
    public function compose($view) {
        $user = Sentry::getUser();

        if ($user) {
            $userPresenter = new UserPresenter($user);

            $view->with('user', $userPresenter);
        } else {
            $view->with('user', false);
        }
    }
}
