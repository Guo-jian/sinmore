<?php

namespace App\Http\Controllers\Admin\Version;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Andriod;

class AndriodController extends Controller
{
    /**
     * showdoc
     * @catalog 其他管理/Andriod版本号
     * @title 详情
     * @description Andriod版本号详情的接口
     * @method post
     * @url admin/version/andriod/detail
     * @param token 必选 string 标识
     * @return {"error_code":0,"error_msg":"\u6210\u529f","data":{"id":1,"url":"https:\/\/laravel-china.org\/docs\/laravel\/5.5\/migrations\/1329","apk":"https:\/\/laravel-china.org\/docs\/laravel\/5.5\/migrations\/1329","version":1,"created_at":"2018-12-04 11:39:18","updated_at":"2018-12-04 11:39:18"}}
     * @return_param error_code int 错误码
     * @return_param error_msg string 错误提示
     * @return_param -data object 返回数据
     * @return_param url string 链接地址
     * @return_param apk string 安装包地址
     * @return_param version int 版本号
     * @remark
     * @number 1
     */
    public function detail(Request $req)
    {
        $data = Andriod::select('url', 'apk', 'version')->first();
        return $data ? $this->returnJson('success', $data) : $this->returnJson('success', ['url'=>'','apk'=>'','version'=>0]);
    }

    /**
     * showdoc
     * @catalog 其他管理/Andriod版本号
     * @title 修改
     * @description Andriod版本号修改的接口
     * @method post
     * @url admin/version/andriod/update
     * @param token 必选 string 标识
     * @param url 必选 string 链接地址
     * @param apk 必选 string 安装包地址
     * @param version 必选 int 版本号
     * @return {"error_code":0,"error_msg":"\u6210\u529f","data":{}}
     * @return_param error_code int 错误码
     * @return_param error_msg string 错误提示
     * @return_param -data object 返回数据
     * @remark
     * @number 2
     */
    public function update(Request $req)
    {
        $this->useValidator($req, [
            'url'=>[0,1,101,255],
            'apk'=>[0,1,101,255],
            'version'=>[0,1,102,239]
        ]);
        $data = Andriod::first();
        if (false == $data) {
            $data = new Andriod();
        }
        $data->url = $req->url;
        $data->apk = $req->apk;
        $data->version = $req->version;
        return $data->save() ? $this->returnJson('success') : $this->returnJson('data save failed');
    }
}
