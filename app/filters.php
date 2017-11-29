<?php

/*
|--------------------------------------------------------------------------
| Application & Route Filters
|--------------------------------------------------------------------------
|
| Below you will find the "before" and "after" events for the application
| which may be used to do any work before or after a request into your
| application. Here you may also register your custom route filters.
|
*/

App::before(function ($request) {
    //
});


App::after(function ($request, $response) {
    //
});

/*
|--------------------------------------------------------------------------
| Authentication Filters
|--------------------------------------------------------------------------
|
| The following filters are used to verify that the user of the current
| session is logged into this application. The "basic" filter easily
| integrates HTTP Basic authentication for quick, simple checking.
|
*/

Route::filter('auth.admin', function () {
    $permission = Route::getCurrentRoute()->getName(); // Get a current route name

    if (!$permission) {
        $permission = Route::getCurrentRoute()->getPath();
    }
//    Log::info($permission);

    $user = Sentry::getUser(); // Get a current user (if she's logged in)

//    Log::info($user);

    if (!$user) {
        return Redirect::route('sign-in')
            ->with('error', 'Access denied.');
    }

//     dd($permission, $user->hasAccess($permission));

    if (!$user->hasAccess($permission)) {
        return Redirect::route('sign-in')
            ->with('error', 'You have no access to this page.');
    }
});

Route::filter('auth.require-user', function () {
    $user = Sentry::getUser(); // Get a current user (if she's logged in)

    if (!$user) {
        return Redirect::route('sign-in')
            ->with('error', 'Please sign in.');
    }
});


/*
|--------------------------------------------------------------------------
| CSRF Protection Filter
|--------------------------------------------------------------------------
|
| The CSRF filter is responsible for protecting your application against
| cross-site request forgery attacks. If this special token in a user
| session does not match the one given in this request, we'll bail.
|
*/

Route::filter('csrf', function () {
    if (Session::token() != Input::get('_token')) {
        throw new Illuminate\Session\TokenMismatchException;
    }
});
