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
    return view('welcome');
});



//登录
Route::get('/login', 'View\MemberController@toLogin');
//注册
Route::get('/register', 'View\MemberController@toRegister');
//书籍分类
Route::get('/category', 'View\CategoryController@toCategory');
//分类产品
Route::get('/product/category_id/{category_id}', 'View\CategoryController@toProduct');
// Route::get('/product/category_id/{category_id}',[
//     'as' => 'category', 'uses' => 'View\CategoryController@toProduct'
// ]);

//产品详情页
Route::get('/product/{product_id}', 'View\ProductController@toPdtContent');


//购物车页面
Route::get('/cart', 'View\CartController@toCart');

// Route::get('/cart', ['middleware' => 'check.login', 'uses' => 'View\CartController@toCart']);

// Route::group(['middleware' => 'check.login'], function() {
//  Route::get('/cart', 'View\CartController@toCart');
// });



Route::group(['prefix' => 'service'], function() {
    //生成验证码
    Route::get('validate_code/create', 'Service\ValidateController@create');

    //手机注册发送短信验证
    Route::post('validate_phone/send', 'Service\ValidateController@sendSMS');

    //注册
    Route::post('register', 'Service\MemberController@register');

    //邮箱注册验证
    Route::post('validate_email', 'Service\ValidateController@validateEmail');

    //登录验证
    Route::post('login', 'Service\MemberController@login');

    //添加购物车
    Route::get('cart/add/{product_id}/{qty}', 'Service\CartController@addCart');

    //批量删除购物车产品
    Route::get('cart/deleteall', 'Service\CartController@deleteAllCart');

    //单个删除购物车产品
    Route::get('cart/deleteone/{product_id}', 'Service\CartController@deleteOneCart');
});


//验证是否登录路由组和中间件使用
Route::group(['middleware' => 'check.login'], function() {
    //购物车结算页面
    Route::post('/order_checkout', 'View\OrderController@toOrderCheckout');
    //查看订单历史页面
    Route::get('/order_list', 'View\OrderController@toOrderList');
    //订单提交数据操作
    Route::post('/service/order_commit', 'Service\OrderController@toOrderCommit');
});


//后台登录注册修改密码等
Route::group(['prefix' => 'admin'], function() {
    Auth::routes();
    Route::get('/home', 'HomeController@index');
});

//后台，所有的页面和操作均需要登录
Route::group(['middleware' => 'auth', 'prefix' => 'admin'], function() {

    //登录后首页页面
    Route::get('/index', 'Admin\View\IndexController@index');
    //分类列表
    Route::get('/category', 'Admin\View\CategoryController@toCategory');
    //添加分类页面
    Route::get('/category/add', 'Admin\View\CategoryController@toCategoryAdd');
    //添加分类ajax数据处理
    Route::post('/service/category/add', 'Admin\Service\CategoryController@categoryAdd');
    //删除分类ajax数据处理
    Route::post('/service/category/delete', 'Admin\Service\CategoryController@categoryDelete');
    //编辑分类页面
    Route::get('/category/edit/{id}', 'Admin\View\CategoryController@toCategoryEdit');
    //添加分类ajax数据处理
    Route::post('/service/category/edit', 'Admin\Service\CategoryController@categoryEdit');
    //上传图片
    Route::post('/service/upload/{type}', 'Admin\Service\UploadFileController@uploadFile');
});


