<?php

$api = <<<'CREATEDATA'
<?php

use Illuminate\Http\Request;

//前置广告图
Route::prefix('ad')->group(function () {

    //列表
    Route::post('list', 'Api\Ad\AdController@list');
});

//单页内容
Route::prefix('content')->group(function () {

    //详情
    Route::post('detail', 'Api\Content\ContentController@detail');
});

//资讯
Route::prefix('info')->group(function () {

    //列表
    Route::post('list', 'Api\Info\InfoController@list');

    //详情
    Route::post('detail', 'Api\Info\InfoController@detail');
});

//版本号
Route::prefix('version')->group(function () {

    //ios
    Route::post('ios', 'Api\Version\VersionController@ios');

    //安卓
    Route::post('andriod', 'Api\Version\VersionController@andriod');
});

Route::prefix('wechat')->group(function () {

    //小程序登录
    Route::post('miniLogin', 'Api\User\LoginController@miniLogin');

    //微信授权登录
    Route::post('oAuthLogin', 'Api\User\LoginController@oAuthLogin');
});

Route::prefix('mobile')->group(function () {

    //验证码登录
    Route::post('codeLogin', 'Api\User\LoginController@codeLogin');

    //密码登录
    Route::post('passwordLogin', 'Api\User\LoginController@passwordLogin');

    //
    Route::post('resetPassword', 'Api\User\LoginController@resetPassword');
});

Route::group(['middleware' => ['checkUser']], function () {

    //用户
    Route::prefix('user')->group(function () {

        //基本资料
        Route::post('detail', 'Api\User\UserController@detail');

        //更换头像
        Route::post('avatar', 'Api\User\UserController@avatar');

        //更换昵称
        Route::post('name', 'Api\User\UserController@name');

        //更换描述
        Route::post('desc', 'Api\User\UserController@desc');

        //更换地址
        Route::post('address', 'Api\User\UserController@address');

        //修改性别
        Route::post('sex', 'Api\User\UserController@sex');

        //修改生日
        Route::post('birthday', 'Api\User\UserController@birthday');

        //修改密码
        Route::post('password', 'Api\User\UserController@password');

        //绑定手机
        Route::post('mobile', 'Api\User\UserController@mobile');
    });

    //反馈
    Route::prefix('contact')->group(function () {

        //添加
        Route::post('add', 'Api\Contact\ContactController@add');
    });
});
CREATEDATA;
