<?php

namespace App\Http\Controllers\Api\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use DB;

class UserController extends Controller
{
    /**
     * showdoc
     * @catalog 会员/基本信息/基本信息
     * @title 详情
     * @description 基本信息详情的接口
     * @method post
     * @url api/user/detail
     * @param token 必选 string 标识
     * @return {"error_code":0,"error_msg":"\u6210\u529f","data":{"avatar":"http:\/\/sinmore.com\/storage\/avatarso3fT_0KUl1uUQ9g1YLVLsfmdJbaI.jpg","name":"mQuery","prov":"Heilongjiang","city":"Harbin","area":"","sex":2,"birthday":null,"mobile":"151****0368","desc":""}}
     * @return_param error_code int 错误码
     * @return_param error_msg string 错误提示
     * @return_param -data object 返回数据
     * @return_param avatar string 头像
     * @return_param name string 昵称
     * @return_param prov string 省份
     * @return_param city string 市
     * @return_param area string 区
     * @return_param sex int 0未知,1男,2女
     * @return_param birthday string 生日
     * @return_param mobile string 电话
     * @return_param desc string 描述
     * @remark
     * @number 1
     */
    public function detail(Request $req)
    {
        return $this->returnJson('success', [
            'avatar'=>$req->user->avatar,
            'name'=>$req->user->name,
            'prov'=>$req->user->prov,
            'city'=>$req->user->city,
            'area'=>$req->user->area,
            'sex'=>$req->user->sex,
            'birthday'=>$req->user->birthday,
            'mobile'=>$req->user->mobile,
            'desc'=>$req->user->desc
        ]);
    }

    /**
     * showdoc
     * @catalog 会员/基本信息/基本信息
     * @title 修改头像
     * @description 修改头像的接口
     * @method post
     * @url api/user/avatar
     * @param token 必选 string 标识
     * @param avatar 必选 string 头像
     * @return {"error_code":0,"error_msg":"\u6210\u529f","data":{}}
     * @return_param error_code int 错误码
     * @return_param error_msg string 错误提示
     * @return_param -data object 返回数据
     * @remark
     * @number 2
     */
    public function avatar(Request $req)
    {
        $this->useValidator($req, [
            'avatar'=>[0,1,101,255]
        ]);
        $req->user->avatar = $req->avatar;
        return $req->user->save() ? $this->returnJson('success') : $this->returnJson('data save failed');
    }

    /**
     * showdoc
     * @catalog 会员/基本信息/基本信息
     * @title 修改昵称
     * @description 修改昵称的接口
     * @method post
     * @url api/user/name
     * @param token 必选 string 标识
     * @param name 必选 string 昵称
     * @return {"error_code":0,"error_msg":"\u6210\u529f","data":{}}
     * @return_param error_code int 错误码
     * @return_param error_msg string 错误提示
     * @return_param -data object 返回数据
     * @remark
     * @number 3
     */
    public function name(Request $req)
    {
        $this->useValidator($req, [
            'name'=>[0,1,101,250]
        ]);
        $req->user->name = $req->name;
        return $req->user->save() ? $this->returnJson('success') : $this->returnJson('data save failed');
    }

    /**
     * showdoc
     * @catalog 会员/基本信息
     * @title 修改地址
     * @description 修改地址的接口
     * @method post
     * @url api/user/address
     * @param token 必选 string 标识
     * @param prov 必选 string 省
     * @param city 必选 string 市
     * @param area 必选 string 区
     * @return {"error_code":0,"error_msg":"\u6210\u529f","data":{}}
     * @return_param error_code int 错误码
     * @return_param error_msg string 错误提示
     * @return_param -data object 返回数据
     * @remark
     * @number 4
     */
    public function address(Request $req)
    {
        $this->useValidator($req, [
            'prov'=>[0,1,101,250],
            'city'=>[0,1,101,250],
            'area'=>[0,1,101,250]
        ]);
        $req->user->prov = $req->prov;
        $req->user->city = $req->city;
        $req->user->area = $req->area;
        return $req->user->save() ? $this->returnJson('success') : $this->returnJson('data save failed');
    }

    /**
     * showdoc
     * @catalog 会员/基本信息
     * @title 修改性别
     * @description 修改性别的接口
     * @method post
     * @url api/user/sex
     * @param token 必选 string 标识
     * @param sex 必选 string 性别1男,2女,3未知
     * @return {"error_code":0,"error_msg":"\u6210\u529f","data":{}}
     * @return_param error_code int 错误码
     * @return_param error_msg string 错误提示
     * @return_param -data object 返回数据
     * @remark
     * @number 5
     */
    public function sex(Request $req)
    {
        $this->useValidator($req, [
            'sex'=>[0,1,102,402]
        ]);
        $req->user->sex = $req->sex;
        return $req->user->save() ? $this->returnJson('success') : $this->returnJson('data save failed');
    }

    /**
     * showdoc
     * @catalog 会员/基本信息
     * @title 修改生日
     * @description 修改生日的接口
     * @method post
     * @url api/user/birthday
     * @param token 必选 string 标识
     * @param birthday 必选 string 生日
     * @return {"error_code":0,"error_msg":"\u6210\u529f","data":{}}
     * @return_param error_code int 错误码
     * @return_param error_msg string 错误提示
     * @return_param -data object 返回数据
     * @remark
     * @number 6
     */
    public function birthday(Request $req)
    {
        $this->useValidator($req, [
            'birthday'=>[0,1,107]
        ]);
        $req->user->birthday = $req->birthday;
        return $req->user->save() ? $this->returnJson('success') : $this->returnJson('data save failed');
    }

    /**
     * showdoc
     * @catalog 会员/基本信息
     * @title 绑定换绑手机
     * @description 绑定换绑手机的接口
     * @method post
     * @url api/user/mobile
     * @param token 必选 string 标识
     * @param mobile 必选 string 手机号
     * @param code 必选 int 验证码
     * @return {"error_code":0,"error_msg":"\u6210\u529f","data":{}}
     * @return_param error_code int 错误码
     * @return_param error_msg string 错误提示
     * @return_param -data object 返回数据
     * @remark
     * @number 7
     */
    public function mobile(Request $req)
    {
        $this->useValidator($req, [
            'mobile'=>[0,1,101,301],
            'code'=>[0,1,102,249]
        ]);
        $code = \App\Models\Code::select('id', 'status')
            ->where('mobile', $req->mobile)
            ->where('type', 3)
            ->where('code', $req->code)
            ->where('status', 1)
            ->where('overdued_at', '>=', date('Y-m-d H:i:s'))
            ->first();
        if (false == $code) {
            return $this->returnJson('data does not exist');
        }
        if (User::where('mobile', $req->mobile)->where('id', '!=', $req->user->id)->count()) {
            return $this->returnJson('account registered');
        }
        $code->status = 0;
        $req->user->mobile = $req->mobile;
        try {
            return DB::transaction(function () use ($code,$req) {
                if (false == $code->save()) {
                    return $this->returnJson('data save failed');
                }
                if (false == $req->user->save()) {
                    return $this->returnJson('data save failed');
                }
                return $this->returnJson('success');
            });
        } catch (\Exception $e) {
            return $this->returnJson($e->getMessage());
        }
    }

    /**
     * showdoc
     * @catalog 会员/基本信息
     * @title 修改描述
     * @description 修改描述的接口
     * @method post
     * @url api/user/desc
     * @param token 必选 string 标识
     * @param desc 非必选 string 描述
     * @return {"error_code":0,"error_msg":"\u6210\u529f","data":{}}
     * @return_param error_code int 错误码
     * @return_param error_msg string 错误提示
     * @return_param -data object 返回数据
     * @remark
     * @number 8
     */
    public function desc(Request $req)
    {
        $this->useValidator($req, [
            'desc'=>[0,3,101,255],
        ]);
        $req->user->desc = $req->desc ?? '';
        return $req->user->save() ? $this->returnJson('success') : $this->returnJson('data save failed');
    }

    /**
     * showdoc
     * @catalog 会员/基本信息
     * @title 初次设置密码
     * @description 初次设置密码的接口
     * @method post
     * @url api/user/password
     * @param token 必选 string 标识
     * @param password 必选 string 密码
     * @return {"error_code":0,"error_msg":"\u6210\u529f","data":{}}
     * @return_param error_code int 错误码
     * @return_param error_msg string 错误提示
     * @return_param -data object 返回数据
     * @remark
     * @number 9
     */
    public function password(Request $req)
    {
        $this->useValidator($req, [
            'password'=>[0,1,101]
        ]);
        if ('' != $req->user->password) {
            return $this->returnJson('unable to update');
        }
        $req->user->password = md5(md5($req->password).env('APP_ATTACH'));
        return $req->user->save() ? $this->returnJson('success') : $this->returnJson('data save failed');
    }
}
