<?php

namespace App\Http\Controllers\Api\Banner;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Banner;

class BannerController extends Controller
{
    /**
     * showdoc
     * @catalog banner
     * @title 列表
     * @description banner列表的接口
     * @method post
     * @url api/banner/list
     * @param hot 非必选 int 推荐位
     * @param show 必选 int 1安卓,2ios,3小程序,4pc,5h5,6ipad
     * @return {"error_code":0,"error_msg":"\u6210\u529f","data":[{"name":"\u4e3b\u9875","pic":"","url":"","type":0,"view":3},{"name":"\u5176\u4ed6","pic":"","url":"","type":0,"view":3}]}
     * @return_param error_code int 错误码
     * @return_param error_msg string 错误提示
     * @return_param -data object 返回数据
     * @return_param --data object 数据信息
     * @return_param name string banner名称
     * @return_param pic string 图片地址
     * @return_param type string 0不跳转,1跳转
     * @return_param url string 跳转地址
     * @return_param view int 展示次数
     * @remark
     * @number 1
     */
    public function list(Request $req)
    {
        $this->useValidator($req, [
            'hot'=>[0,3,102],
            'show'=>[0,1,102,416]
        ]);
        $data = Banner::where('status', 1)->when($req->hot, function ($query) use ($req) {
                $query->where('hot', $req->hot);
            })->searchhasType($req->show);
        $count = $data->count();
        $data->increment('view', 1);
        $data = $data->select('name', 'pic', 'url', 'type', 'view')->get();
        return $this->returnJson('success', $data);
    }
}
