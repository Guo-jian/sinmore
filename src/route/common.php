<?php

use Illuminate\Http\Request;

//获取所有权限
Route::post('getAllRule', 'Common\CommonController@getAllRule');

//获取所有管理组
Route::post('getAllGroup', 'Common\CommonController@getAllGroup');

//获取所有管理员
Route::post('getAllAdmin', 'Common\CommonController@getAllAdmin');

//发送验证码
Route::post('code','Common\CommonController@code');

//多图上传
Route::post('upload','Common\CommonController@upload');

//单图上传
Route::post('uploadOnce','Common\CommonController@uploadOnce');
