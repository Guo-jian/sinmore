<?php

/*
 * This file is part of the mquery/sinmore.
 *
 * (c) guojian <n6878088@163.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace App\Http\Controllers\Admin\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * showdoc.
     *
     * @catalog 权限系统/管理员管理
     * @title 添加
     * @description 添加管理员的接口
     *
     * @method post
     * @url admin/add
     *
     * @param token 必选 string 标识
     * @param name 必选 string 姓名
     * @param group_id 必选 int 管理组id
     * @param status 必选 int 状态:1为启用,0为禁用
     * @param mobile 必选 string 手机号
     * @param account 必选 string 帐号
     * @param password 必选 string 密码
     *
     * @return {"error_code":0,"error_msg":"成功","data":{"admin_id":2}}
     * @return_param error_code int 错误码
     * @return_param error_msg string 错误提示
     * @return_param -data object 返回数据
     * @return_param admin_id int 管理员id
     * @remark
     * @number 4
     */
    public function add(Request $req)
    {
        $this->useValidator($req, [
            'name' => [0, 1, 101, 220],
            'group_id' => [0, 1, 102],
            'status' => [0, 1, 100],
            'account' => [0, 1, 101, 220],
            'mobile' => [0, 1, 301],
            'password' => [0, 1, 101, 220],
        ]);
        if (Admin::where('name', $req->name)->orWhere('mobile', $req->mobile)->orWhere('account', $req->account)->count()) {
            return $this->returnJson('account registered');
        }
        if (0 == \App\Models\Group::where('id', $req->group_id)->count()) {
            return $this->returnJson('group does not exist');
        }
        $data = new Admin();
        $data->name = $req->name;
        $data->group_id = $req->group_id;
        $data->status = $req->status;
        $data->mobile = $req->mobile;
        $data->account = $req->account;
        $data->password = md5(md5($req->password).env('APP_ATTACH'));

        return $data->save() ? $this->returnJson('success', ['admin_id' => $data->id]) : $this->returnJson('data save failed');
    }

    /**
     * showdoc.
     *
     * @catalog 权限系统/管理员管理
     * @title 修改
     * @description 修改管理员信息的接口
     *
     * @method post
     * @url admin/update
     *
     * @param token 必选 string 标识
     * @param admin_id 必选 int 管理员id
     * @param name 必选 string 姓名
     * @param group_id 必选 int 管理组id
     * @param status 必选 int 状态:1为启用,0为禁用
     * @param mobile 必选 string 手机号
     * @param account 必选 string 帐号
     * @param password 非必选 string 密码
     *
     * @return {"error_code":0,"error_msg":"成功","data":{"admin_id":2}}
     * @return_param error_code int 错误码
     * @return_param error_msg string 错误提示
     * @return_param -data object 返回数据
     * @return_param admin_id int 管理员id
     * @remark 密码可以为空,若为空则密码不变
     * @number 5
     */
    public function update(Request $req)
    {
        $this->useValidator($req, [
            'admin_id' => [0, 1, 102],
            'name' => [0, 1, 101, 220],
            'group_id' => [0, 1, 102],
            'status' => [0, 1, 100],
            'account' => [0, 1, 101, 220],
            'mobile' => [0, 1, 301],
            'password' => [0, 3, 101, 220],
        ]);
        if (1 == $req->admin_id) {
            return $this->returnJson('account does not exist');
        }
        $data = Admin::find($req->admin_id);
        if (false == $data) {
            return $this->returnJson('account does not exist');
        }
        if (Admin::where(function ($query) use ($req) {
            $query->where('name', $req->name)->orWhere('mobile', $req->mobile)->orWhere('account', $req->account);
        })->where('id', '!=', $req->admin_id)->count()) {
            return $this->returnJson('account registered');
        }
        $data->name = $req->name;
        $data->group_id = $req->group_id;
        $data->status = $req->status;
        $data->mobile = $req->mobile;
        $data->account = $req->account;
        $data->password = $req->password ? md5(md5($req->password).env('APP_ATTACH')) : $data->password;

        return $data->save() ? $this->returnJson('success', ['admin_id' => $data->id]) : $this->returnJson('data save failed');
    }

    /**
     * showdoc.
     *
     * @catalog 权限系统/管理员管理
     * @title 列表
     * @description 管理员列表的接口
     *
     * @method post
     * @url admin/list
     *
     * @param token 必选 string 标识
     * @param page 必选 int 页码
     * @param pagesize 必选 int 每页条数
     *
     * @return {"error_code":0,"error_msg":"\u6210\u529f","data":{"data":[{"id":3,"name":"\u65f6\u632f\u5ddd","mobile":"17600220742","status":1,"group_id":1,"get_group":{"id":1,"name":"\u6d4b\u8bd5\u7ec4"}}],"current_page":1,"total_page":2,"count":2}}
     * @return_param error_code int 错误码
     * @return_param error_msg string 错误提示
     * @return_param -data object 返回数据
     * @return_param --data object 返回信息
     * @return_param id int 管理员id
     * @return_param name string 管理员名称
     * @return_param mobile string 手机号
     * @return_param status int 状态(1正常,0冻结)
     * @return_param group_id int 管理组id
     * @return_param ---get_group object 管理组数据
     * @return_param id int 管理组id
     * @return_param name string 管理组名称
     * @return_param current_page int 当前页
     * @return_param total_page int 总页数
     * @return_param count int 总条数
     * @remark
     * @number 6
     */
    public function list(Request $req)
    {
        $this->useValidator($req, [
            'page' => [0, 1, 102],
            'pagesize' => [0, 1, 102],
        ]);
        $data = Admin::where('id', '>', 1);
        $count = $data->count();
        $data = $data->searchGroup(['id', 'name'])
            ->orderBy('id', 'desc')
            ->offset(($req->page - 1) * $req->pagesize)
            ->limit($req->pagesize)
            ->get(['id', 'name', 'mobile', 'status', 'group_id']);

        return $this->returnJson('success', ['data' => $data, 'current_page' => (int) $req->page, 'total_page' => ceil($count / $req->pagesize), 'count' => $count]);
    }

    /**
     * showdoc.
     *
     * @catalog 权限系统/管理员管理
     * @title 详情
     * @description 管理员详情的接口
     *
     * @method post
     * @url admin/detail
     *
     * @param token 必选 string 标识
     * @param admin_id 必选 int 管理员id
     *
     * @return {"error_code":0,"error_msg":"\u6210\u529f","data":{"id":2,"group_id":1,"status":1,"mobile":"15114580369"}}
     * @return_param error_code int 错误码
     * @return_param error_msg string 错误提示
     * @return_param -data object 返回数据
     * @return_param id int 管理员id
     * @return_param group_id int 管理组id
     * @return_param status int 状态(1正常,0冻结)
     * @return_param mobile string 手机号
     * @return_param account string 帐号
     * @remark
     * @number 7
     */
    public function detail(Request $req)
    {
        $this->useValidator($req, [
            'admin_id' => [0, 1, 102],
        ]);
        if (1 == $req->admin_id) {
            return $this->returnJson('account does not exist');
        }
        $data = Admin::select('id', 'group_id', 'status', 'mobile', 'account')->find($req->admin_id);

        return $data ? $this->returnJson('success', $data) : $this->returnJson('account does not exist');
    }

    /**
     * showdoc.
     *
     * @catalog 权限系统/管理员管理
     * @title 禁用
     * @description 禁用管理员的接口
     *
     * @method post
     * @url admin/freeze
     *
     * @param token 必选 string 标识
     * @param admin_id 必选 int 管理员id
     *
     * @return {"error_code":0,"error_msg":"\u6210\u529f","data":{"admin_id":2}}
     * @return_param error_code int 错误码
     * @return_param error_msg string 错误提示
     * @return_param -data object 返回数据
     * @return_param admin_id int 管理员id
     * @remark
     * @number 8
     */
    public function freeze(Request $req)
    {
        $this->useValidator($req, [
            'admin_id' => [0, 1, 102],
        ]);
        if (1 == $req->admin_id) {
            return $this->returnJson('account does not exist');
        }
        $data = Admin::select('id', 'status')->find($req->admin_id);
        if (false == $data) {
            return $this->returnJson('account does not exist');
        }
        if (0 == $data->status) {
            return $this->returnJson('account has been frozen');
        }
        $data->status = 0;

        return $data->save() ? $this->returnJson('success', ['admin_id' => $data->id]) : $this->returnJson('data save failed');
    }

    /**
     * showdoc.
     *
     * @catalog 权限系统/管理员管理
     * @title 解禁
     * @description 解禁管理员的接口
     *
     * @method post
     * @url admin/unfreeze
     *
     * @param token 必选 string 标识
     * @param admin_id 必选 int 管理员id
     *
     * @return {"error_code":0,"error_msg":"\u6210\u529f","data":{"admin_id":2}}
     * @return_param error_code int 错误码
     * @return_param error_msg string 错误提示
     * @return_param -data object 返回数据
     * @return_param admin_id int 管理员id
     * @remark
     * @number 9
     */
    public function unfreeze(Request $req)
    {
        $this->useValidator($req, [
            'admin_id' => [0, 1, 102],
        ]);
        if (1 == $req->admin_id) {
            return $this->returnJson('account does not exist');
        }
        $data = Admin::select('id', 'status')->find($req->admin_id);
        if (false == $data) {
            return $this->returnJson('account does not exist');
        }
        if (1 == $data->status) {
            return $this->returnJson('account not frozen');
        }
        $data->status = 1;

        return $data->save() ? $this->returnJson('success', ['admin_id' => $data->id]) : $this->returnJson('data save failed');
    }

    /**
     * showdoc.
     *
     * @catalog 权限系统/管理员管理
     * @title 删除
     * @description 删除管理员的接口
     *
     * @method post
     * @url admin/delete
     *
     * @param token 必选 string 标识
     * @param admin_id 必选 int 管理员id
     *
     * @return {"error_code":0,"error_msg":"\u6210\u529f","data":{"admin_id":3}}
     * @return_param error_code int 错误码
     * @return_param error_msg string 错误提示
     * @return_param -data object 返回数据
     * @return_param admin_id int 管理员id
     * @remark
     * @number 10
     */
    public function del(Request $req)
    {
        $this->useValidator($req, [
            'admin_id' => [0, 1, 102],
        ]);
        if (1 == $req->admin_id) {
            return $this->returnJson('account does not exist');
        }
        $data = Admin::find($req->admin_id);
        if (false == $data) {
            return $this->returnJson('account does not exist');
        }

        return $data->delete() ? $this->returnJson('success', ['admin_id' => $data->id]) : $this->returnJson('data save failed');
    }
}
