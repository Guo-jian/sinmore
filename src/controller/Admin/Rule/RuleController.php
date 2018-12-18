<?php

namespace App\Http\Controllers\Admin\Rule;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Group;

class RuleController extends Controller
{
    /**
     * showdoc
     * @catalog 权限系统/管理组管理
     * @title 权限详情
     * @description 权限详情的接口
     * @method post
     * @url admin/rule/detail
     * @param token 必选 string 标识
     * @param group_id 必选 int 管理组id
     * @return {"error_code":0,"error_msg":"\u6210\u529f","data":{"rules":["1","2","3","4","5","6","7","8","9","10"]}}
     * @return_param error_code int 错误码
     * @return_param error_msg string 错误提示
     * @return_param -data object 返回数据
     * @return_param --rules array 权限
     * @remark
     * @number 6
     */
    public function detail(Request $req)
    {
        $this->useValidator($req, [
            'group_id'=>[0,1,102]
        ]);
        $data = Group::select('rules')->find($req->group_id);
        return $data ? $this->returnJson('success', ['rules'=>explode(',', $data->rules)]) : $this->returnJson('group does not exist');
    }

    /**
     * showdoc
     * @catalog 权限系统/管理组管理
     * @title 修改权限
     * @description 修改权限的接口
     * @method post
     * @url admin/rule/update
     * @param token 必选 string 标识
     * @param group_id 必选 int 管理组id
     * @param rules[] 必选 array 权限id数组
     * @return {"error_code":0,"error_msg":"\u6210\u529f","data":{"group_id":1}}
     * @return_param error_code int 错误码
     * @return_param error_msg string 错误提示
     * @return_param -data object 返回数据
     * @return_param group_id int 管理组id
     * @remark
     * @number 7
     */
    public function update(Request $req)
    {
        $this->useValidator($req, [
            'rules'=>[0,1,104],
            'rules.*.*'=>[0,1,102,200],
            'group_id'=>[0,1,102,200]
        ]);
        $data = Group::select('id', 'rules')->find($req->group_id);
        $data->rules = implode(',', $req->rules);
        return $data->save() ? $this->returnJson('success', ['group_id'=>$data->id]): $this->returnJson('data save failed');
    }
}
