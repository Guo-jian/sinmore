<?php

namespace App\Http\Controllers\Api\Version;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Ios;
use App\Models\Andriod;

class VersionController extends Controller
{
    /**
     * showdoc
     * @catalog 版本
     * @title Andriod版本号
     * @description Andriod版本号详情的接口
     * @method post
     * @url api/version/andriod
     * @return {"error_code":0,"error_msg":"\u6210\u529f","data":{"version":1,"url":"https:\/\/laravel-china.org\/docs\/laravel\/5.5\/migrations\/1328","apk":"https:\/\/laravel-china.org\/docs\/laravel\/5.5\/migrations\/1328"}}
     * @return_param error_code int 错误码
     * @return_param error_msg string 错误提示
     * @return_param -data object 返回数据
     * @return_param url string 链接地址
     * @return_param apk string 安装包地址
     * @return_param version int 版本号
     * @remark
     * @number 1
     */
    public function andriod(Request $req)
    {
        $data = Andriod::select('version', 'url', 'apk')->first();
        return $this->returnJson('success', $data);
    }

    /**
     * showdoc
     * @catalog 版本
     * @title Ios版本号
     * @description Ios版本号详情的接口
     * @method post
     * @url api/version/ios
     * @return {"error_code":0,"error_msg":"\u6210\u529f","data":{"version":2}}
     * @return_param error_code int 错误码
     * @return_param error_msg string 错误提示
     * @return_param -data object 返回数据
     * @return_param version int 版本号
     * @remark
     * @number 2
     */
    public function ios(Request $req)
    {
        $data = Ios::select('version')->first();
        return $this->returnJson('success', $data);
    }
}
