<?php

namespace App\Http\Controllers\Admin\Group;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Group;

class GroupController extends Controller
{
    /**
     * showdoc
     * @catalog 权限系统/管理组管理
     * @title 添加
     * @description 添加管理组的接口
     * @method post
     * @url admin/group/add
     * @param token 必选 string 标识
     * @param name 必选 string 管理组名称
     * @param desc 非必选 string 描述
     * @return {"error_code":0,"error_msg":"\u6210\u529f","data":{"group_id":1}}
     * @return_param error_code int 错误码
     * @return_param error_msg string 错误提示
     * @return_param -data object 返回数据
     * @return_param group_id int 管理组id
     * @remark
     * @number 1
     */
    public function add(Request $req)
    {
        $this->useValidator($req, [
            'name'=>[0,1,101,220],
            'desc'=>[0,3,101,202]
        ]);
        if (Group::where('name', $req->name)->count()) {
            return $this->returnJson('group already exists');
        }
        $data = new Group();
        $data->name = $req->name;
        $data->desc = $req->desc ?? '';
        return $data->save() ? $this->returnJson('success', ['group_id'=>$data->id]): $this->returnJson('data save failed');
    }

    /**
     * showdoc
     * @catalog 权限系统/管理组管理
     * @title 修改
     * @description 修改管理组的接口
     * @method post
     * @url admin/group/update
     * @param token 必选 string 标识
     * @param group_id 必选 int 管理组id
     * @param name 必选 string 管理组名称
     * @param desc 非必选 string 描述
     * @return {"error_code":0,"error_msg":"\u6210\u529f","data":{"group_id":1}}
     * @return_param error_code int 错误码
     * @return_param error_msg string 错误提示
     * @return_param -data object 返回数据
     * @return_param group_id int 管理组id
     * @remark
     * @number 2
     */
    public function update(Request $req)
    {
        $this->useValidator($req, [
            'group_id'=>[0,1,102],
            'name'=>[0,1,101,220],
            'desc'=>[0,3,101,202],
        ]);
        $data = Group::find($req->group_id);
        if (false == $data) {
            return $this->returnJson('group does not exist');
        }
        if (Group::where('name', $req->name)->where('id', '!=', $req->group_id)->count()) {
            return $this->returnJson('group already exists');
        }
        $data->name = $req->name;
        $data->desc = $req->desc ?? '';
        return $data->save() ? $this->returnJson('success', ['group_id'=>$data->id]): $this->returnJson('data save failed');
    }

    /**
     * showdoc
     * @catalog 权限系统/管理组管理
     * @title 列表
     * @description 管理组列表的接口
     * @method post
     * @url admin/group/list
     * @param token 必选 string 标识
     * @param page 必选 int 页数
     * @param pagesize 必选 int 每页条数
     * @return {"error_code":0,"error_msg":"\u6210\u529f","data":{"data":[{"id":2,"name":"\u6d4b\u8bd5\u4e8c\u7ec4","desc":"\u6d4b\u8bd5\u4e8c\u7ec4"},{"id":1,"name":"\u6d4b\u8bd5\u4e00\u7ec4","desc":""}],"current_page":1,"total_page":1,"count":2}}
     * @return_param error_code int 错误码
     * @return_param error_msg string 错误提示
     * @return_param -data object 返回数据
     * @return_param --data object 返回信息
     * @return_param id int 管理组id
     * @return_param name string 管理组名称
     * @return_param name string 管理组描述
     * @return_param current_page int 当前页
     * @return_param total_page int 总页数
     * @return_param count int 总条数
     * @remark
     * @number 3
     */
    public function list(Request $req)
    {
        $this->useValidator($req, [
            'page'=>[0,1,102],
            'pagesize'=>[0,1,102]
        ]);
        $data = new Group();
        $count = $data->count();
        $data = $data->select('id', 'name', 'desc')
            ->orderBy('id', 'desc')
            ->offset(($req->page-1)*$req->pagesize)
            ->limit($req->pagesize)
            ->get();
        return $this->returnJson('success', ['data'=>$data,'current_page'=>(int)$req->page,'total_page'=>ceil($count/$req->pagesize),'count'=>$count]);
    }

    /**
     * showdoc
     * @catalog 权限系统/管理组管理
     * @title 详情
     * @description 管理组详情的接口
     * @method post
     * @url admin/group/detail
     * @param token 必选 string 标识
     * @param group_id 必选 int 管理组id
     * @return {"error_code":0,"error_msg":"\u6210\u529f","data":{"id":1,"name":"\u6d4b\u8bd5\u4e00\u7ec4","desc":""}}
     * @return_param error_code int 错误码
     * @return_param error_msg string 错误提示
     * @return_param -data object 返回数据
     * @return_param id int 管理组id
     * @return_param name string 管理组名称
     * @remark
     * @number 4
     */
    public function detail(Request $req)
    {
        $this->useValidator($req, [
            'group_id'=>[0,1,102]
        ]);
        $data = Group::select('id', 'name', 'desc')->find($req->group_id);
        return $data ? $this->returnJson('success', $data) : $this->returnJson('group does not exist');
    }

    /**
     * showdoc
     * @catalog 权限系统/管理组管理
     * @title 删除
     * @description 删除管理组的接口
     * @method post
     * @url admin/group/delete
     * @param token 必选 string 标识
     * @param group_id 必选 int 管理组id
     * @return {"error_code":0,"error_msg":"\u6210\u529f","data":{"group_id":2}}
     * @return_param error_code int 错误码
     * @return_param error_msg string 错误提示
     * @return_param -data object 返回数据
     * @return_param group_id int 管理组id
     * @remark
     * @number 5
     */
    public function del(Request $req)
    {
        $this->useValidator($req, [
            'group_id'=>[0,1,102]
        ]);
        $data = Group::find($req->group_id);
        if (false == $data) {
            return $this->returnJson('group does not exist');
        }
        if (\App\Models\Admin::where('group_id', $data->id)->count()) {
            return $this->returnJson('admin exists in the group');
        }
        return $data->delete() ? $this->returnJson('success', ['group_id'=>$data->id]) : $this->returnJson('data save failed');
    }
}
