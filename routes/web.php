<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/', function () {
    return view('home');
});


Route::get('/index', '\App\Tool\WeiXin\wechatCallbackapiTest@valid');
// Route::group(['namespace' => '\App\Tool\WeiXin'], function () {
//     // 测试
//     Route::get('/test', 'WXTool@getUserInfo');
//     Route::get('/login', 'WXTool@wxLogin');
// });
Route::get('/test', '\App\Tool\WeiXin\WXTool@getUserInfo');
Route::get('/test/login', '\App\Tool\WeiXin\WXTool@wxLogin');


Route::group(['middleware' => 'auth', 'namespace' => 'Admin', 'prefix' => 'admin'], function () {
	Route::get('/', 'HomeController@index');

	Route::resource('cate', 'CateController');
    Route::post('cate/toggleDisplay', 'CateController@toggleDisplay');

    Route::resource('food', 'FoodController');
    Route::post('food/toggleDisplay', 'FoodController@toggleDisplay');

    Route::resource('shop', 'ShopController');

    Route::resource('table', 'TableController');

    Route::resource('order', 'OrderController');

    Route::resource('user', 'UserController');
    Route::post('user/toggleDisplay', 'UserController@toggleDisplay');

    Route::resource('admin', 'AdminController', ['except' => ['store']]);
    Route::post('admin/toggleDisplay', 'AdminController@toggleDisplay');
});


Auth::routes();

Route::get('/table/{tid}', 'HomeController@index');


/**
 * 接口
 */
$api = app('Dingo\Api\Routing\Router');

$api->version('v1', function ($api) {

    $api->group(['namespace' => 'App\Http\Controllers\Api\V1'/*, 'domain' => 'dcb.ngrok.cc'*/], function ($api) {
        $api->get('/shop/{sid?}', 'ApiController@shop');
        $api->get('/foods', 'ApiController@getFoods');
        $api->get('/f', 'ApiController@getfs');
        $api->get('/foods/cates', 'ApiController@getCates');
    });


    $api->group(['namespace' => '\App\Tool\WeiXin'], function ($api) {
        // 测试
        $api->get('/index', 'wechatCallbackapiTest@valid');
        // 测试
        $api->get('/home', function () {
            return view('home/index');
        });
        // 测试
        $api->get('/test', 'WXTool@getUserInfo');
    });
});
