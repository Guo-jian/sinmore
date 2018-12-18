<?php

namespace App\Http\Controllers\Api\Content;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Content;

class ContentController extends Controller
{
    /**
     * showdoc
     * @catalog 基础内容/单页内容
     * @title 详情
     * @description 单页内容详情的接口
     * @method post
     * @url api/content/detail
     * @param content_id 必选 int 内容id
     * @return {"error_code":0,"error_msg":"\u6210\u529f","data":{"id":1,"name":"name","desc":"desc","title":"title","description":"description","keywords":"keywords","content":"content","view":0,"created_at":"2018-11-26 18:43:17","updated_at":"2018-11-26 18:43:17"}}
     * @return_param error_code int 错误码
     * @return_param error_msg string 错误提示
     * @return_param -data object 返回数据
     * @return_param id int 内容id
     * @return_param name string 标题
     * @return_param desc string 描述
     * @return_param title string title
     * @return_param description string description
     * @return_param keywords string keywords
     * @return_param content string 详情
     * @remark
     * @number 1
     */
    public function detail(Request $req)
    {
        $this->useValidator($req, [
            'content_id'=>[0,1,102]
        ]);
        $data = Content::find($req->content_id);
        if (false == $data) {
            return $this->returnJson('data does not exist');
        }
        $data->view += 1;
        return $data->save() ? $this->returnJson('success', $data) : $this->returnJson('data save failed');
    }
}
