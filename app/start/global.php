<?php

/*
|--------------------------------------------------------------------------
| Register The Laravel Class Loader
|--------------------------------------------------------------------------
|
| In addition to using Composer, you may use the Laravel class loader to
| load your controllers and models. This is useful for keeping all of
| your classes in the "global" namespace without Composer updating.
|
*/

ClassLoader::addDirectories(array(

    app_path() . '/commands',
    app_path() . '/controllers',
    app_path() . '/models',
    app_path() . '/database/seeds',

));

/*
|--------------------------------------------------------------------------
| Application Error Logger
|--------------------------------------------------------------------------
|
| Here we will configure the error logger setup for the application which
| is built on top of the wonderful Monolog library. By default we will
| build a basic log file setup which creates a single file for logs.
|
*/

Log::useFiles(storage_path() . '/logs/laravel.log');

/*
|--------------------------------------------------------------------------
| Application Error Handler
|--------------------------------------------------------------------------
|
| Here you may handle any errors that occur in your application, including
| logging them or displaying custom views for specific errors. You may
| even register several error handlers to handle different types of
| exceptions. If nothing is returned, the default error view is
| shown, which includes a detailed stack trace during debug.
|
*/

App::error(function (Exception $exception, $code) {
    Log::error(Request::url());
	Log::error($exception);


     /*if ($code != 404 ) {
        Mail::send('emails.error', array('trace' => $exception), function ($message) {
            $message
                ->to('benlamalem.soufiane@gmail.com')
                ->from('info@its-for-real.com', 'Its For Real')
                ->subject('IFR App Error');

        });
    }*/

    switch ($code) {
        case 404:
            $title = 'Sorry, the page you are looking for could not be found.';
            return \Redirect::route('errors.404');
            break;
        case 500:
            $title = 'Whoops, looks like something went wrong.';
            //return \Redirect::route('errors.500');
            break;
        default:
            $title = 'Whoops, looks like something went wrong.';
            return \Redirect::route('errors.500');
    }

});


/*
|--------------------------------------------------------------------------
| Maintenance Mode Handler
|--------------------------------------------------------------------------
|
| The "down" Artisan command gives you the ability to put an application
| into maintenance mode. Here, you will define what is displayed back
| to the user if maintenance mode is in effect for the application.
|
*/

App::down(function () {
	return Response::make("Be right back!", 503);
});

/*
|--------------------------------------------------------------------------
| Require The Filters File
|--------------------------------------------------------------------------
|
| Next we will load the filters file for the application. This gives us
| a nice separate location to store our route and application filter
| definitions instead of putting them all in the main routes file.
|
*/

require app_path() . '/filters.php';

/*
|--------------------------------------------------------------------------
| Require The View Composers
|--------------------------------------------------------------------------
*/

require app_path() . '/composers.php';

/*
|--------------------------------------------------------------------------
| Require Skins for IFR Modules
|--------------------------------------------------------------------------
*/

require app_path() . '/module_skins.php';

/**
 * Configure templates
 */
Blade::setEscapedContentTags('{{', '}}');
Blade::setContentTags('{{{', '}}}');
