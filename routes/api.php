<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResources([
    'trello-webhook' => 'API\TrelloWebHookController',
    'organization' => 'API\OrganizationController',
    'board' => 'API\BoardController',
]);

Route::get('trello-organization', 'API\OrganizationController@trelloOrganization');
Route::get('trello-organization-board/{organization}', 'API\BoardController@trelloBoard');
