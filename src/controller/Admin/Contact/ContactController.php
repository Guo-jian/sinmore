<?php

namespace App\Http\Controllers\Admin\Contact;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Contact;

class ContactController extends Controller
{
    /**
     * showdoc
     * @catalog 其他管理/表单提交管理
     * @title 列表
     * @description 表单提交管理列表的接口
     * @method post
     * @url admin/contact/list
     * @param token 必选 string 标识
     * @param page 必选 int 页数
     * @param pagesize 必选 int 每页条数
     * @return {"error_code":0,"error_msg":"\u6210\u529f","data":{"data":[{"id":1,"user_id":7,"admin_id":1,"status":1,"created_at":"2018-12-14 17:10:21","get_user":{"id":7,"name":"1*****8"},"get_admin":{"id":1,"name":"admin"}}],"current_page":1,"total_page":3,"count":3}}
     * @return_param error_code int 错误码
     * @return_param error_msg string 错误提示
     * @return_param -data object 返回数据
     * @return_param --data object 返回信息
     * @return_param id int 表单提交id
     * @return_param user_id int 用户id
     * @return_param admin_id int 管理员id
     * @return_param status int 1已处理,0未处理
     * @return_param created_at string 创建时间
     * @return_param ---get_user object 用户信息
     * @return_param id int 用户id
     * @return_param name string 用户昵称
     * @return_param ---get_admin object 管理员信息
     * @return_param admin_id int 管理员id
     * @return_param name string 管理员昵称
     * @return_param current_page int 当前页
     * @return_param total_page int 总页数
     * @return_param count int 总条数
     * @remark
     * @number 1
     */
    public function list(Request $req)
    {
        $this->useValidator($req,[
            'page'=>[0,1,102],
            'pagesize'=>[0,1,102]
        ]);
        $data = new Contact();
        $count = $data->count();
        $data = $data->select('id','user_id','admin_id','status','created_at')
            ->searchUser()
            ->searchAdmin()
            ->orderBy('status')
            ->orderBy('id','desc')
            ->offset(($req->page-1)*$req->pagesize)
            ->limit($req->pagesize)
            ->get();
        return $this->returnJson('success', ['data'=>$data,'current_page'=>(int)$req->page,'total_page'=>ceil($count/$req->pagesize),'count'=>$count]);
    }

    /**
     * showdoc
     * @catalog 其他管理/表单提交管理
     * @title 处理
     * @description 表单提交管理处理的接口
     * @method post
     * @url admin/contact/update
     * @param token 必选 string 标识
     * @param contact_id 必选 int 表单提交id
     * @param pagesize 必选 int 每页条数
     * @return {"error_code":0,"error_msg":"\u6210\u529f","data":{}}
     * @return_param error_code int 错误码
     * @return_param error_msg string 错误提示
     * @return_param -data object 返回数据
     * @remark
     * @number 2
     */
    public function update(Request $req)
    {
        $this->useValidator($req,[
            'contact_id'=>[0,1,102]
        ]);
        $data = Contact::find($req->contact_id);
        if(false == $data){
            return $this->returnJson('data does not exist');
        }
        if(1 == $data->status){
            return $this->returnJson('unable to update');
        }
        $data->admin_id = $req->admin->id;
        $data->status = 1;
        return $data->save() ? $this->returnJson('success') : $this->returnJson('data save failed');
    }
}
