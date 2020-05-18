<?php

use Illuminate\Http\Request;

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


Route::group(['middleware' => ['api', 'cors']], function () {
    Route::get('shoppinglists', 'ShoppinglistController@index');
    Route::get('shoppinglists/user/{user_id}', 'ShoppinglistController@indexForUser');
    Route::get('shoppinglists/open', 'ShoppinglistController@findOpenShoppinglists');
    Route::get('shoppinglists/{id}', 'ShoppinglistController@findById');
    Route::get('shoppinglists/date/{due_date}', 'ShoppinglistController@findByDueDate');
    Route::get('shoppinglists/{title}', 'ShoppinglistController@findByTitle');
    Route::get('shoppinglists/checkID/{id}', 'ShoppinglistController@checkID');
    Route::get('shoppinglists/search/{searchTerm}', 'ShoppinglistController@findBySearchTerm');
    Route::get('users/{id}', 'UserController@findById');
    Route::post('auth/login', 'Auth\ApiAuthController@login');
    Route::post('auth/register', 'Auth\ApiRegisterController@create');
});
Route::group(['middleware' => ['api', 'cors', 'auth.jwt']], function () {
    Route::post('shoppinglist', 'ShoppinglistController@save');
    Route::put('shoppinglist/{id}', 'ShoppinglistController@update');
    Route::put('shoppinglist/helper/{id}', 'ShoppinglistController@updateHelper');
    Route::delete('shoppinglist/{id}', 'ShoppinglistController@delete');
    Route::post('auth/logout', 'Auth\ApiAuthController@logout');

});
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

