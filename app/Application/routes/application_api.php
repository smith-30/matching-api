<?php
use Illuminate\Http\Request;

/**
 * api for admin panel
 */
$api = app('Dingo\Api\Routing\Router');

/**
 * authentication
 */
$api->version('v1', ['prefix' => 'api/v1', 'namespace' => 'App\Application\Http\Controllers\Auth'], function ($api) {
    $api->post('/auth/fb_login', 'AuthController@storeFbUserData');
});


$api->version('v1',
    [
        'prefix' => 'api/v1',
        'namespace' => 'App\Application\Http\Controllers\V1',
        'middleware' => ['cors', 'api.auth', 'bindings']
    ],
    function ($api) {
        $api->get('/auth/authenticated_user', 'UserController@getAuthenticatedUser');
        $api->resource('users', 'UserController');
        $api->resource('userProfiles', 'UserProfileController');
        $api->resource('userPermissions', 'UserPermissionController'); //modelの名前に合わせないとbindingsが動かない

        $api->group(['prefix' => 'teams', 'middleware' => ['can:createTeam']], function ($api) {
            $api->post('/', ['as' => 'teams.create', 'uses' => 'TeamController@create']);
        });
});