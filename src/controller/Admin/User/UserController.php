<?php

namespace App\Http\Controllers\Admin\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;

class UserController extends Controller
{
    /**
     * showdoc
     * @catalog 会员系统
     * @title 列表
     * @description 会员列表的接口
     * @method post
     * @url admin/user/list
     * @param token 必选 string 标识
     * @param page 必选 int 页数
     * @param pagesize 必选 int 每页条数
     * @return {"error_code":0,"error_msg":"\u6210\u529f","data":{"data":[{"id":9,"avatar":"http:\/\/sinmore.com\/storage\/avatars\/default.jpg","name":"151****0369","mobile":"15114580369","prov":"","city":"","area":"","birthday":null,"status":1,"created_at":"2018-12-13 14:39:03"}],"current_page":1,"total_page":2,"count":2}}
     * @return_param error_code int 错误码
     * @return_param error_msg string 错误提示
     * @return_param -data object 返回数据
     * @return_param --data object 返回信息
     * @return_param id int 用户id
     * @return_param avatar string 用户头像
     * @return_param name string 用户昵称
     * @return_param mobile string 手机号
     * @return_param prov string 省
     * @return_param city string 城市
     * @return_param area string 区
     * @return_param birthday string 生日
     * @return_param status int 0暂时冻结,1正常,2永久冻结
     * @return_param created_at string 注册时间
     * @return_param current_page int 当前页
     * @return_param total_page int 总页数
     * @return_param count int 总条数
     * @remark
     * @number 1
     */
    public function list(Request $req)
    {
        $this->useValidator($req, [
            'page'=>[0,1,102],
            'pagesize'=>[0,1,102]
        ]);
        $data = new User();
        $count = $data->count();
        $data = $data->select('id', 'avatar', 'name', 'mobile', 'prov', 'city', 'area', 'birthday', 'status', 'created_at')
            ->orderBy('id', 'desc')
            ->offset(($req->page-1)*$req->pagesize)
            ->limit($req->pagesize)
            ->get();
        return $this->returnJson('success', ['data'=>$data,'current_page'=>(int)$req->page,'total_page'=>ceil($count/$req->pagesize),'count'=>$count]);
    }

    /**
     * showdoc
     * @catalog 会员系统
     * @title 检索
     * @description 会员检索的接口
     * @method post
     * @url admin/user/search
     * @param token 必选 string 标识
     * @param prov 非必选 string 省份
     * @param city 非必选 string 城市
     * @param started_at 非必选 string 开始时间
     * @param ended_at 非必选 string 结束时间
     * @param page 必选 int 页数
     * @param pagesize 必选 int 每页条数
     * @return {"error_code":0,"error_msg":"\u6210\u529f","data":{"data":[{"id":9,"avatar":"http:\/\/sinmore.com\/storage\/avatars\/default.jpg","name":"151****0369","mobile":"15114580369","prov":"","city":"","area":"","birthday":null,"status":1,"created_at":"2018-12-13 14:39:03"}],"current_page":1,"total_page":2,"count":2}}
     * @return_param error_code int 错误码
     * @return_param error_msg string 错误提示
     * @return_param -data object 返回数据
     * @return_param --data object 返回信息
     * @return_param id int 用户id
     * @return_param avatar string 用户头像
     * @return_param name string 用户昵称
     * @return_param mobile string 手机号
     * @return_param prov string 省
     * @return_param city string 城市
     * @return_param area string 区
     * @return_param birthday string 生日
     * @return_param status int 0暂时冻结,1正常,2永久冻结
     * @return_param created_at string 注册时间
     * @return_param current_page int 当前页
     * @return_param total_page int 总页数
     * @return_param count int 总条数
     * @remark
     * @number 2
     */
    public function search(Request $req)
    {
        $this->useValidator($req, [
            'prov'=>[0,3,101],
            'city'=>[0,3,101],
            'started_at'=>[0,3,107],
            'ended_at'=>[0,3,107],
            'page'=>[0,1,102],
            'pagesize'=>[0,1,102],
        ]);
        $data = User::when($req->prov, function ($query) use ($req) {
            return $query->where('prov', $req->prov);
        })->when($req->city, function ($query) use ($req) {
            return $query->where('city', $req->city);
        })->when($req->started_at && $req->ended_at, function ($query) use ($req) {
            return $query->whereBetween('created_at', [$req->started_at,$req->ended_at]);
        });
        $count = $data->count();
        $data = $data->select('id', 'avatar', 'name', 'mobile', 'prov', 'city', 'area', 'birthday', 'status', 'created_at')
            ->orderBy('id', 'desc')
            ->offset(($req->page-1)*$req->pagesize)
            ->limit($req->pagesize)
            ->get();
        return $this->returnJson('success', ['data'=>$data,'current_page'=>(int)$req->page,'total_page'=>ceil($count/$req->pagesize),'count'=>$count]);
    }

    /**
     * showdoc
     * @catalog 会员系统
     * @title 关键字
     * @description 会员关键字的接口
     * @method post
     * @url admin/user/keyword
     * @param token 必选 string 标识
     * @param type 必选 int 1id,2昵称,3手机号
     * @param keyword 必选 string 关键字
     * @param page 必选 int 页数
     * @param pagesize 必选 int 每页条数
     * @return {"error_code":0,"error_msg":"\u6210\u529f","data":{"data":[{"id":9,"avatar":"http:\/\/sinmore.com\/storage\/avatars\/default.jpg","name":"151****0369","mobile":"15114580369","prov":"","city":"","area":"","birthday":null,"status":1,"created_at":"2018-12-13 14:39:03"}],"current_page":1,"total_page":2,"count":2}}
     * @return_param error_code int 错误码
     * @return_param error_msg string 错误提示
     * @return_param -data object 返回数据
     * @return_param --data object 返回信息
     * @return_param id int 用户id
     * @return_param avatar string 用户头像
     * @return_param name string 用户昵称
     * @return_param mobile string 手机号
     * @return_param prov string 省
     * @return_param city string 城市
     * @return_param area string 区
     * @return_param birthday string 生日
     * @return_param status int 0暂时冻结,1正常,2永久冻结
     * @return_param created_at string 注册时间
     * @return_param current_page int 当前页
     * @return_param total_page int 总页数
     * @return_param count int 总条数
     * @remark
     * @number 3
     */
    public function keyword(Request $req)
    {
        $this->useValidator($req, [
            'type'=>[0,1,102,413],
            'keyword'=>[0,1,101],
            'page'=>[0,1,102],
            'pagesize'=>[0,1,102],
        ]);
        $data = User::where(function ($query) use ($req) {
            if (1 == $req->type) {
                $query->where('id', 'like', "%$req->keyword%");
            } elseif (2 == $req->type) {
                $query->where('name', 'like', "%$req->keyword%");
            } else {
                $query->where('mobile', 'like', "%$req->keyword%");
            }
        });
        $count = $data->count();
        $data = $data->select('id', 'avatar', 'name', 'mobile', 'prov', 'city', 'area', 'birthday', 'status', 'created_at')
            ->orderBy('id', 'desc')
            ->offset(($req->page-1)*$req->pagesize)
            ->limit($req->pagesize)
            ->get();
        return $this->returnJson('success', ['data'=>$data,'current_page'=>(int)$req->page,'total_page'=>ceil($count/$req->pagesize),'count'=>$count]);
    }

    /**
     * showdoc
     * @catalog 会员系统
     * @title 详情
     * @description 会员详情的接口
     * @method post
     * @url admin/user/detail
     * @param token 必选 string 标识
     * @param user_id 必选 int 用户id
     * @return {"error_code":0,"error_msg":"\u6210\u529f","data":{"id":7,"name":"1*****8","mobile":"15114580368","sex":1,"avatar":"http:\/\/sinmore.com\/storage\/avatars\/3857794782345686306.jpg","prov":"\u9ed1\u9f99\u6c5f\u7701","city":"\u54c8\u5c14\u6ee8\u5e02","area":"\u9053\u5916\u533a","birthday":"1999-09-09","status":1,"last_login_ip":"127.0.0.1","created_at":"2018-12-12 18:35:20","desc":""}}
     * @return_param error_code int 错误码
     * @return_param error_msg string 错误提示
     * @return_param -data object 返回数据
     * @return_param --data object 返回信息
     * @return_param id int 用户id
     * @return_param name string 用户昵称
     * @return_param mobile string 手机号
     * @return_param sex int 性别1男2女0未知
     * @return_param avatar string 用户头像
     * @return_param prov string 省
     * @return_param city string 城市
     * @return_param area string 区
     * @return_param birthday string 生日
     * @return_param status int 0暂时冻结,1正常,2永久冻结
     * @return_param last_login_ip string 上次登录ip
     * @return_param desc string 描述
     * @return_param created_at string 注册时间
     * @return_param current_page int 当前页
     * @return_param total_page int 总页数
     * @return_param count int 总条数
     * @remark
     * @number 4
     */
    public function detail(Request $req)
    {
        $this->useValidator($req, [
            'user_id'=>[0,1,102]
        ]);
        $data = User::select('id', 'name', 'mobile', 'sex', 'avatar', 'prov', 'city', 'area', 'birthday', 'status', 'last_login_ip', 'created_at', 'desc')
            ->find($req->user_id);
        return $data ? $this->returnJson('success', $data) : $this->returnJson('data does not exist');
    }
}
