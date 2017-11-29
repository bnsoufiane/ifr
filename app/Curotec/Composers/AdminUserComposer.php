<?php

namespace Curotec\Composers;

use Curotec\Presenters\AdminUserPresenter;
use Sentry;

// Adds a user's data to all admin views.
class AdminUserComposer {
    public function compose($view) {
        $user = Sentry::getUser();

        if ($user) {
            $userPresenter = new AdminUserPresenter($user);

            $view->with('user', $userPresenter);
        } else {
            $view->with('user', false);
        }
    }
}
