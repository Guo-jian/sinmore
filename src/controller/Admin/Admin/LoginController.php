<?php

namespace App\Http\Controllers\Admin\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Admin;

class LoginController extends Controller
{
    /**
     * showdoc
     * @catalog 权限系统/管理员管理
     * @title 登录
     * @description 管理员登录的接口
     * @method post
     * @url admin/login
     * @param mobile 必选 string 手机号
     * @param password 必选 string 密码
     * @return {"error_code":0,"error_msg":"\u6210\u529f","data":{"token":"fdfeddc5dd44f2b0382052f78fdef5e1","name":"\u90ed\u5efa","rule":[{"id":1,"pid":0,"title":"\u6743\u9650\u7cfb\u7edf","path":"","get_rule":[{"id":2,"pid":1,"title":"\u7ba1\u7406\u7ec4","path":"","get_rule":[{"id":3,"pid":2,"title":"\u6dfb\u52a0\u7ba1\u7406\u7ec4","path":""},{"id":4,"pid":2,"title":"\u4fee\u6539\u7ba1\u7406\u7ec4","path":""},{"id":5,"pid":2,"title":"\u7ba1\u7406\u7ec4\u8be6\u60c5","path":""},{"id":6,"pid":2,"title":"\u5220\u9664\u7ba1\u7406\u7ec4","path":""},{"id":7,"pid":2,"title":"\u8bbe\u7f6e\u6743\u9650","path":""},{"id":8,"pid":2,"title":"\u6743\u9650\u8be6\u60c5","path":""}]},{"id":9,"pid":1,"title":"\u7ba1\u7406\u5458","path":"","get_rule":[{"id":10,"pid":9,"title":"\u6dfb\u52a0\u7ba1\u7406\u5458","path":""}]}]}]}}
     * @return_param error_code int 错误码
     * @return_param error_msg string 错误提示
     * @return_param -data object 返回数据
     * @return_param token string 标识
     * @return_param name string 用户名
     * @return_param --rule object 权限
     * @return_param id int 权限id
     * @return_param pid int 上级id
     * @return_param title string 权限标题
     * @return_param path string 前台路由
     * @return_param ---get_rule
     * @return_param id int 权限id
     * @return_param pid int 上级id
     * @return_param title string 权限标题
     * @return_param path string 前台路由
     * @remark 标识有效期最少为240分钟,根据使用情况会增长,请保存在本地
     * @number 1
     */
    public function login(Request $req)
    {
        $this->useValidator($req, [
            'mobile'=>[0,1,101,301],
            'password'=>[0,1,101,220]
        ]);
        $data = Admin::where('mobile', $req->mobile)->where('password', md5(md5($req->password).env('APP_ATTACH')))->first();
        if (false == $data) {
            return $this->returnJson('account or password error');
        }
        if (1 == $data->id) {
            return $this->returnJson('success', [
                'token'=>$data->token,
                'name'=>$data->name,
                'rule'=>\App\Models\Rule::where('status', 1)
                    ->where('pid', 0)
                    ->select('id', 'pid', 'title', 'path')
                    ->searchRule(2)
                    ->get()
            ]);
        }
        if (0 == $data->status) {
            return $this->returnJson('account has been frozen');
        }
        $data->last_login_ip = $req->getClientIp();
        $data->token = md5(encrypt($data->id.$data->last_login_ip.time().env('APP_KEY')));
        $data->expired_at = date('Y-m-d H:i:s', strtotime('+4 hours'));
        if ($data->save()) {
            return $this->returnJson('success', [
                'token'=>$data->token,
                'name'=>$data->name,
                'rule'=>\App\Models\Rule::where('pid', 0)
                    ->where('status', 1)
                    ->whereIn('id', explode(",", \App\Models\Group::where('id', $data->group_id)->value('rules')))
                    ->select('id', 'pid', 'title', 'path')
                    ->searchRule(2)
                    ->get()
            ]);
        }
        return $this->returnJson('data save failed');
    }

    /**
     * showdoc
     * @catalog 权限系统/管理员管理
     * @title 登出
     * @description 管理员登出的接口
     * @method post
     * @url admin/logout
     * @param token 必选 string 标识
     * @return {"error_code":0,"error_msg":"\u6210\u529f","data":{}}
     * @return_param error_code int 错误码
     * @return_param error_msg string 错误提示
     * @return_param -data object 返回数据
     * @remark
     * @number 2
     */
    public function logout(Request $req)
    {
        $req->admin->token = '';
        $req->admin->expired_at = date('Y-m-d H:i:s');
        return $req->admin->save() ? $this->returnJson('success') : $this->returnJson('data save failed');
    }

    /**
     * showdoc
     * @catalog 权限系统/管理员管理
     * @title 修改密码
     * @description 修改密码的接口
     * @method post
     * @url admin/password
     * @param mobile 必选 string 手机号
     * @param code 必选 string 验证码
     * @param password 必选 string 密码
     * @return {"error_code":0,"error_msg":"\u6210\u529f","data":{}}
     * @return_param error_code int 错误码
     * @return_param error_msg string 错误提示
     * @return_param -data object 返回数据
     * @remark
     * @number 3
     */
    public function password(Request $req)
    {
        $this->useValidator($req, [
            'password'=>[0,1,101]
        ]);
        $req->admin->password = md5(md5($req->password).env('APP_ATTACH'));
        return $req->admin->save() ? $this->returnJson('success') : $this->returnJson('data save failed');
    }
}
