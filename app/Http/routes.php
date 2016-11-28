<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

// 默认登录地址
Route::group(['middleware' => ['web']], function () {

    Route::get('/', 'Home\IndexController@index');
    Route::get('/cate/{cate_id}', 'Home\IndexController@cate');
    Route::get('/a/{art_id}', 'Home\IndexController@article');

});
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::group(['middleware' => ['web'],'prefix'=>'admin','namespace'=>'Admin'], function () {
    // 后台验证码
    Route::get('code', 'LoginController@code');
    // 后台登录页面
    Route::any('login', 'LoginController@login');
    // 退出登录
    Route::get('quit', 'LoginController@quit');
});

Route::group(['middleware' => ['web','admin.login'],'prefix'=>'admin','namespace'=>'Admin'], function () {
    // 后台首页
    Route::get('/', 'IndexController@index');
    // 后台欢迎页面
    Route::get('info', 'IndexController@info');
    // 修改密码
    Route::any('pass', 'IndexController@pass');
    // 分类排序
    Route::post('cate/changeorder', 'CategoryController@changeOrder');
    // 文章分类
    Route::resource('category', 'CategoryController');
    // 文章
    Route::resource('article', 'ArticleController');
    // 图片上传验证
    Route::any('upload', 'CommonController@upload');
    // 友情链接
    Route::resource('links', 'LinksController');
    // 友情链接排序
    Route::post('links/changeorder', 'LinksController@changeOrder');
    // 导航
    Route::resource('navs', 'NavsController');
    // 导航排序
    Route::post('navs/changeorder', 'NavsController@changeOrder');
    // 更新配置信息到配置文件中
    Route::get('config/putfile', 'ConfigController@putFile');
    // 修改配置文件中内容
    Route::post('config/changecontent', 'ConfigController@changeContent');
    // 对配置项进行排序
    Route::post('config/changeorder', 'ConfigController@changeOrder');
    // 配置
    Route::resource('config', 'configController');

});
//Application
Route::group(['prefix'=>'api','namespace'=>'Api'], function () {
    // app登录
    Route::any('login', 'ApiController@login');
    // app注册
    Route::any('register', 'ApiController@register');
});