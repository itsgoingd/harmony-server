<?php

Route::group([ 'middleware' => 'web' ], function()
{

	Route::get('/', [ 'as' => 'home', 'uses' => 'SiteController@home' ]);

	Route::get ('/sign-in', [ 'as' => 'sign-in form',    'uses' => 'Web\SignInController@showLoginForm' ]);
	Route::post('/sign-in', [ 'as' => 'sign-in request', 'uses' => 'Web\SignInController@login' ]);

	Route::get ('/sign-up', [ 'as' => 'sign-up form',    'uses' => 'Web\RegistrationController@showRegistrationForm' ]);
	Route::post('/sign-up', [ 'as' => 'sign-up request', 'uses' => 'Web\RegistrationController@register' ]);

	Route::get('/sign-out', [ 'as' => 'sign-out', 'uses' => 'Web\SignInController@logout' ]);

	Route::group([ 'middleware' => 'auth' ], function()
	{

		Route::get('/profile',      [ 'as' => 'profile',        'uses' => 'Web\ProfileController@show' ]);
		Route::get('/profile/edit', [ 'as' => 'profile edit',   'uses' => 'Web\ProfileController@edit' ]);
		Route::put('/profile',      [ 'as' => 'profile update', 'uses' => 'Web\ProfileController@update' ]);

		Route::get   ('/apps/create',                [ 'as' => 'apps create',             'uses' => 'Web\Apps\AppsController@create' ]);
		Route::post  ('/apps',                       [ 'as' => 'apps store',              'uses' => 'Web\Apps\AppsController@store' ]);
		Route::get   ('/apps/{app}',                 [ 'as' => 'apps crashes',            'uses' => 'Web\Apps\CrashesController@index' ]);
		Route::get   ('/apps/{app}/settings',        [ 'as' => 'apps settings',           'uses' => 'Web\Apps\SettingsController@edit' ]);
		Route::put   ('/apps/{app}',                 [ 'as' => 'apps settings update',    'uses' => 'Web\Apps\SettingsController@update' ]);
		Route::put   ('/apps/{app}/my-settings',     [ 'as' => 'apps my-settings update', 'uses' => 'Web\Apps\UserSettingsController@update' ]);
		Route::post  ('/apps/{app}/users',           [ 'as' => 'apps users add',          'uses' => 'Web\Apps\UsersController@store' ]);
		Route::delete('/apps/{app}/users/{user}',    [ 'as' => 'apps users delete',       'uses' => 'Web\Apps\UsersController@delete' ]);

		Route::get ('/apps/{app}/crashes/{crash}',          [ 'as' => 'crashes details',       'uses' => 'Web\Apps\CrashesController@show' ]);
		Route::post('/apps/{app}/crashes/{crash}/comments', [ 'as' => 'crashes comments post', 'uses' => 'Web\Apps\CrashCommentsController@store' ]);
		Route::post('/apps/{app}/crashes/{crash}/archive',  [ 'as' => 'crashes archive',       'uses' => 'Web\Apps\CrashArchiveController@store' ]);

	});

});

Route::group([ 'middleware' => 'api', 'prefix' => 'api', 'namespace' => 'Api' ], function()
{

	Route::post('crashes', [ 'as' => 'api crashes report', 'uses' => 'CrashesController@store' ]);

});
