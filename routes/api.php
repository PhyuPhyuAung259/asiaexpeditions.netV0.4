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

// header('Access-Control-Allow-Origin: http://localhost:3000');

Route::middleware('IsLogin:api')->get('/user', function (Request $request) {
    return $request->user();
}); 
Route::get('window/uploaded', 'Admin\UploadController@fileUploaded');


Route::apiResource('tour_popular', 'API\TourPopularController');
Route::apiResource('category', 'API\CategoryController');
Route::apiResource('ournews', 'API\OurNewsController');
Route::apiResource('setting', 'API\SettingController');
Route::apiResource('film', 'API\FilmController');

Route::apiResource('slide', 'API\ControllerSlide');
Route::apiResource('countries', 'API\ControllerCountry');
Route::apiResource('users', 'API\UserController');
Route::apiResource('suppliers', 'API\SupplierController');
Route::apiResource('our-team', 'API\OurTeamController');
Route::apiResource('customers', 'API\CustomersController');

Route::apiResource('contactus', 'API\ControllerContactUs');
Route::apiResource('subscribe', 'API\SubscribeController');

Route::apiResource("footer", 'API\FooterController');

Route::apiResource('tour', 'API\TourController');
Route::apiResource('uploadfile', 'API\APIUploadController');
Route::apiResource('tour_item', 'API\TourItemController');
Route::apiResource('search', 'API\SearchController');
Route::apiResource('users', 'API\CustomersController');