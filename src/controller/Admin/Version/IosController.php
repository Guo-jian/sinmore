<?php

namespace App\Http\Controllers\Admin\Version;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Ios;

class IosController extends Controller
{
    /**
     * showdoc
     * @catalog 其他管理/Ios版本号
     * @title 详情
     * @description Ios版本号详情的接口
     * @method post
     * @url admin/version/ios/detail
     * @param token 必选 string 标识
     * @return {"error_code":0,"error_msg":"\u6210\u529f","data":{"version":0}}
     * @return_param error_code int 错误码
     * @return_param error_msg string 错误提示
     * @return_param -data object 返回数据
     * @return_param version int 版本号
     * @remark
     * @number 1
     */
    public function detail(Request $req)
    {
        $data = Ios::select('version')->first();
        return $data ? $this->returnJson('success', $data) : $this->returnJson('success', ['version'=>0]);
    }

    /**
     * showdoc
     * @catalog 其他管理/Ios版本号
     * @title 修改
     * @description Ios版本号修改的接口
     * @method post
     * @url admin/version/ios/update
     * @param token 必选 string 标识
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
            'version'=>[0,1,102,239]
        ]);
        $data = Ios::first();
        if (false == $data) {
            $data = new Ios();
        }
        $data->version = $req->version;
        return $data->save() ? $this->returnJson('success') : $this->returnJson('data save failed');
    }
}
