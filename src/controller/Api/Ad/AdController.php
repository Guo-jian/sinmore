<?php

namespace App\Http\Controllers\Api\Ad;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Ad;

class AdController extends Controller
{
    /**
     * showdoc
     * @catalog 前置广告图
     * @title 列表
     * @description 前置广告图列表的接口
     * @method post
     * @url api/ad/list
     * @param type 必选 int 1安卓,2ios,3小程序,4pc,5h5,6ipad
     * @return {"error_code":0,"error_msg":"\u6210\u529f","data":[{"id":2,"pic":"https:\/\/publish-pic-cpu.baidu.com\/7e2cb3d8-aae8-49b6-b426-04a3d1fb043f.jpeg@q_90,w_450","type":1,"url":"https:\/\/cpu.baidu.com\/pc\/1022\/1329713\/detail\/25113422177807470\/news?blockId=10624&rts=12&from=block"},{"id":1,"pic":"https:\/\/publish-pic-cpu.baidu.com\/5b6c0b4c-515f-4334-8c6b-465b82e9ca8f.jpeg@q_90,w_450","type":0,"url":""}]}
     * @return_param error_code int 错误码
     * @return_param error_msg string 错误提示
     * @return_param -data object 返回数据
     * @return_param id int 广告图id
     * @return_param pic string 广告图图片
     * @return_param type int 1有跳转,0无跳转
     * @return_param url string 跳转地址
     * @remark
     * @number 1
     */
    public function list(Request $req)
    {
        $this->useValidator($req, [
            'type'=>[0,1,102,416]
        ]);
        $data = Ad::where('status', 1)
            ->hasType($req->type);
        $data->increment('view', 1);
        $data = $data->select('id', 'pic', 'type', 'url')
            ->orderBy('sort', 'desc')
            ->orderBy('id', 'desc')
            ->get();
        return $this->returnJson('success', $data);
    }
}
