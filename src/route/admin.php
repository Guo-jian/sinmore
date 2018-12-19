<?php

$admin = <<<'CREATEDATA'
<?php

use Illuminate\Http\Request;

//添加
Route::post('add', 'Admin\Admin\AdminController@add');

//修改
Route::post('update', 'Admin\Admin\AdminController@update');

//列表
Route::post('list', 'Admin\Admin\AdminController@list');

//详情
Route::post('detail', 'Admin\Admin\AdminController@detail');

//冻结
Route::post('freeze', 'Admin\Admin\AdminController@freeze');

//解冻
Route::post('unfreeze', 'Admin\Admin\AdminController@unfreeze');

//删除
Route::post('delete', 'Admin\Admin\AdminController@del');

//登录
Route::post('login', 'Admin\Admin\LoginController@login');

//登出
Route::post('logout', 'Admin\Admin\LoginController@logout');

//修改密码
Route::post('password','Admin\Admin\LoginController@password');

//管理组
Route::prefix('group')->group(function () {

    //添加
    Route::post('add', 'Admin\Group\GroupController@add');

    //修改
    Route::post('update', 'Admin\Group\GroupController@update');

    //列表
    Route::post('list', 'Admin\Group\GroupController@list');

    //详情
    Route::post('detail', 'Admin\Group\GroupController@detail');

    //删除
    Route::post('delete', 'Admin\Group\GroupController@del');
});

//权限
Route::prefix('rule')->group(function () {

    //详情
    Route::post('detail', 'Admin\Rule\RuleController@detail');

    //修改
    Route::post('update', 'Admin\Rule\RuleController@update');
});

//单页
Route::prefix('content')->group(function () {

    //添加
    Route::post('add', 'Admin\Content\ContentController@add');

    //修改
    Route::post('update', 'Admin\Content\ContentController@update');

    //列表
    Route::post('list', 'Admin\Content\ContentController@list');

    //搜索
    Route::post('keyword', 'Admin\Content\ContentController@keyword');

    //详情
    Route::post('detail', 'Admin\Content\ContentController@detail');

    //删除
    Route::post('delete', 'Admin\Content\ContentController@del');
});

//标签
Route::prefix('label')->group(function () {

    //添加
    Route::post('add', 'Admin\Label\LabelController@add');

    //修改
    Route::post('update', 'Admin\Label\LabelController@update');

    //列表
    Route::post('list', 'Admin\Label\LabelController@list');

    //详情
    Route::post('detail', 'Admin\Label\LabelController@detail');

    //排序
    Route::post('sort', 'Admin\Label\LabelController@sort');

    //删除
    Route::post('delete', 'Admin\Label\LabelController@del');
});

//分类
Route::prefix('category')->group(function () {

    //添加
    Route::post('add', 'Admin\Category\CategoryController@add');

    //修改
    Route::post('update', 'Admin\Category\CategoryController@update');

    //列表
    Route::post('list', 'Admin\Category\CategoryController@list');

    //搜索
    Route::post('search', 'Admin\Category\CategoryController@search');

    //详情
    Route::post('detail', 'Admin\Category\CategoryController@detail');

    //排序
    Route::post('sort', 'Admin\Category\CategoryController@sort');

    //冻结
    Route::post('freeze', 'Admin\Category\CategoryController@freeze');

    //解冻
    Route::post('unfreeze', 'Admin\Category\CategoryController@unfreeze');

    //推荐
    Route::post('hot', 'Admin\Category\CategoryController@hot');

    //删除
    Route::post('delete', 'Admin\Category\CategoryController@del');
});

//资讯
Route::prefix('info')->group(function () {

    //添加
    Route::post('add', 'Admin\Info\InfoController@add');

    //修改
    Route::post('update', 'Admin\Info\InfoController@update');

    //列表
    Route::post('list', 'Admin\Info\InfoController@list');

    //搜索
    Route::post('search', 'Admin\Info\InfoController@search');

    //关键字
    Route::post('keyword', 'Admin\Info\InfoController@keyword');

    //详情
    Route::post('detail', 'Admin\Info\InfoController@detail');

    //排序
    Route::post('sort', 'Admin\Info\InfoController@sort');

    //冻结
    Route::post('freeze', 'Admin\Info\InfoController@freeze');

    //解冻
    Route::post('unfreeze', 'Admin\Info\InfoController@unfreeze');

    //置顶
    Route::post('top', 'Admin\Info\InfoController@top');

    //取消置顶
    Route::post('down', 'Admin\Info\InfoController@down');

    //删除
    Route::post('delete', 'Admin\Info\InfoController@del');
});

//Banner
Route::prefix('banner')->group(function () {

    //添加
    Route::post('add', 'Admin\Banner\BannerController@add');

    //修改
    Route::post('update', 'Admin\Banner\BannerController@update');

    //列表
    Route::post('list', 'Admin\Banner\BannerController@list');

    //检索
    Route::post('search', 'Admin\Banner\BannerController@search');

    //搜索
    Route::post('keyword', 'Admin\Banner\BannerController@keyword');

    //详情
    Route::post('detail', 'Admin\Banner\BannerController@detail');

    //排序
    Route::post('sort', 'Admin\Banner\BannerController@sort');

    //推荐
    Route::post('hot', 'Admin\Banner\BannerController@hot');

    //冻结
    Route::post('freeze', 'Admin\Banner\BannerController@freeze');

    //解冻
    Route::post('unfreeze', 'Admin\Banner\BannerController@unfreeze');

    //删除
    Route::post('delete', 'Admin\Banner\BannerController@del');
});

//广告图
Route::prefix('ad')->group(function () {

    //添加
    Route::post('add', 'Admin\Ad\AdController@add');

    //修改
    Route::post('update', 'Admin\Ad\AdController@update');

    //列表
    Route::post('list', 'Admin\Ad\AdController@list');

    //搜索
    Route::post('keyword', 'Admin\Ad\AdController@keyword');

    //详情
    Route::post('detail', 'Admin\Ad\AdController@detail');

    //排序
    Route::post('sort', 'Admin\Ad\AdController@sort');

    //冻结
    Route::post('freeze', 'Admin\Ad\AdController@freeze');

    //解冻
    Route::post('unfreeze', 'Admin\Ad\AdController@unfreeze');

    //删除
    Route::post('delete', 'Admin\Ad\AdController@del');
});

//版本号
Route::prefix('version')->group(function () {

    //安卓
    Route::prefix('andriod')->group(function () {

        //详情
        Route::post('detail', 'Admin\Version\AndriodController@detail');

        //修改
        Route::post('update', 'Admin\Version\AndriodController@update');
    });

    //ios
    Route::prefix('ios')->group(function () {

        //详情
        Route::post('detail', 'Admin\Version\IosController@detail');

        //修改
        Route::post('update', 'Admin\Version\IosController@update');
    });
});

//用户
Route::prefix('user')->group(function () {

    //列表
    Route::post('list', 'Admin\User\UserController@list');

    //检索
    Route::post('search', 'Admin\User\UserController@search');

    //关键字
    Route::post('keyword', 'Admin\User\UserController@keyword');

    //详情
    Route::post('detail', 'Admin\User\UserController@detail');

    //冻结
    Route::post('freeze', 'Admin\User\FreezeController@freeze');

    //解冻
    Route::post('unfreeze', 'Admin\User\FreezeController@unfreeze');

    Route::prefix('freeze')->group(function () {

        //列表
        Route::post('list', 'Admin\User\FreezeController@list');

        //详情
        Route::post('detail', 'Admin\User\FreezeController@detail');
    });
});

Route::prefix('contact')->group(function () {

    //列表
    Route::post('list', 'Admin\Contact\ContactController@list');

    //修改
    Route::post('update', 'Admin\Contact\ContactController@update');
});
CREATEDATA;
