<?php

namespace App\Http\Controllers\Api\Contact;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Contact;

class ContactController extends Controller
{
    /**
     * showdoc
     * @catalog 留言反馈
     * @title 添加
     * @description 添加留言反馈的接口
     * @method post
     * @url api/contact/add
     * @param token 必选 string 标识
     * @param content 必选 string 反馈内容
     * @return {"error_code":0,"error_msg":"\u6210\u529f","data":{}}
     * @return_param error_code int 错误码
     * @return_param error_msg string 错误提示
     * @return_param -data object 返回数据
     * @remark
     * @number 1
     */
    public function add(Request $req)
    {
        $this->useValidator($req,[
            'content'=>[0,1,101,255]
        ]);
        $data = new Contact();
        $data->user_id = $req->user->id;
        $data->content = $req->content;
        return $data->save() ? $this->returnJson('success') : $this->returnJson('data save failed');
    }
}
