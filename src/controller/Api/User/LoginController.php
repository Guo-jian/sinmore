<?php

namespace App\Http\Controllers\Api\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Code;
use Storage;
use DB;

class LoginController extends Controller
{
    use \App\Traits\Wechat\MiniTrait;

    use \App\Traits\Wechat\OfficialAccountTrait;

    /**
     * showdoc
     * @catalog 登录注册
     * @title 微信小程序注册登录
     * @description 微信小程序注册登录的接口
     * @method post
     * @url api/wechat/miniLogin
     * @param code 必选 string code
     * @param encryptedData 必选 string encryptedData
     * @param iv 必选 string iv
     * @return {"error_code":0,"error_msg":"\u6210\u529f","data":{"token":"31601bca99bd6120395e348e358f4956","mobile":"","name":"mQuery"}}
     * @return_param error_code int 错误码
     * @return_param error_msg string 错误提示
     * @return_param -data object 返回数据
     * @return_param token string 标识
     * @return_param mobile string 手机号
     * @return_param name string 用户昵称
     * @remark 用户手机号为空时,跳转绑定手机号页面
     * @number 1
     */
    public function miniLogin(Request $req)
    {
        $this->useValidator($req, [
            'code'=>[0,1,101],
            'encryptedData'=>[0,1,101],
            'iv'=>[0,1,101]
        ]);
        $app = static::setMiniApp();
        $res = $app->auth->session($req->code);
        if (isset($res['errcode'])) {
            return $this->returnJson($res['errmsg'], [], $res['errcode']);
        }
        $decryptedData = $app->encryptor->decryptData($res['session_key'], $req->iv, $req->encryptedData);
        $decryptedData['ip'] = $req->getClientIp();
        $data = User::refreshUser($decryptedData);
        if (0 == $data->status) {
            if (strtotime($data->froze_at) + ($data->froze_days * 86400) <= time()) {
                $data->status = 1;
                $data->froze_at = null;
                $data->froze_days = 0;
            } else {
                return $this->returnJson('account has been frozen');
            }
        } elseif (2 == $data->status) {
            return $this->returnJson('account has been frozen');
        }
        if (false == $data->save()) {
            return $this->returnJson('data save failed');
        }
        return $this->returnJson('success', ['token'=>$data->token,'mobile'=>$data->mobile ?? '','name'=>$data->name]);
    }

    /**
     * showdoc
     * @catalog 登录注册
     * @title 微信授权注册登录
     * @description 微信授权注册登录的接口
     * @method post
     * @url api/wechat/oAuthLogin
     * @remark 授权成功后自动跳转至前台页面url后携带参数token,例http://xxxx.com/?token=31601bca99bd6120395e348e358f4956
     * @number 2
     */
    public function oAuthLogin()
    {
        $app = static::setOfficialAccountApp();
        return $app->oauth->redirect();
    }

    public function oAuthCallback()
    {
        $app = static::setOfficialAccountApp();
        $user = $app->oauth->user();
        if (false == $user) {
            return redirect()->action('Api\User\LoginController@oAuthLogin');
        }
        $user = $user->getOriginal();
        $user['ip'] = $req->getClientIp();
        $data = User::refreshUser($user);
        if (0 == $data->status) {
            if (strtotime($data->froze_at) + ($data->froze_days * 86400) <= time()) {
                $data->status = 1;
                $data->froze_at = null;
                $data->froze_days = 0;
            } else {
                return header('Location:'.config('app.html').'/frozen/?token='.$data->token);
            }
        } elseif (2 == $data->status) {
            return header('Location:'.config('app.html').'/frozen/?token='.$data->token);
        }
        if (false == $data->save()) {
            return redirect()->action('Api\User\LoginController@oAuthLogin');
        }
        return $data->mobile ? header('Location:'.config('app.html').'/?token='.$data->token) : header('Location:'.config('app.html').'/mobile/?token='.$data->token);
    }

    /**
     * showdoc
     * @catalog 登录注册
     * @title 验证码注册登录
     * @description 验证码注册登录的接口
     * @method post
     * @url api/mobile/codeLogin
     * @param mobile 必选 string 手机号
     * @param code 必选 string 验证码
     * @param pushid 非必选 string 推送id
     * @return {"error_code":0,"error_msg":"\u6210\u529f","data":{"token":"5137d0209278a12205cac7881864fb7c","mobile":"15114580369","name":"151****0369","password":0}}
     * @return_param error_code int 错误码
     * @return_param error_msg string 错误提示
     * @return_param -data object 返回数据
     * @return_param token string 标识
     * @return_param mobile string 手机号
     * @return_param name string 用户昵称
     * @return_param password string 密码
     * @remark 用户密码为0时,跳转设置密码页面
     * @number 3
     */
    public function codeLogin(Request $req)
    {
        $this->useValidator($req, [
            'mobile'=>[0,1,101,301],
            'code'=>[0,1,102,249],
            'pushid'=>[0,3,101,250]
        ]);
        $data = Code::select('id', 'status', 'mobile')
            ->where('mobile', $req->mobile)
            ->where('type', 1)
            ->where('code', $req->code)
            ->where('status', 1)
            ->where('overdued_at', '>=', date('Y-m-d H:i:s'))
            ->searchUser()
            ->first();
        if (false == $data) {
            return $this->returnJson('data does not exist');
        }
        if (false == $data->getUser) {
            $user = new User();
            $user->mobile = $req->mobile;
            $user->name = substr_replace($req->mobile, '****', 3, 4);
            $user->avatar = config('app.url').'/storage/avatars/default.jpg';
        } else {
            $user = $data->getUser;
        }
        if (0 == $user->status) {
            if (strtotime($user->froze_at) + ($user->froze_days * 86400) <= time()) {
                $user->status = 1;
                $user->froze_at = null;
                $user->froze_days = 0;
            } else {
                return $this->returnJson('account has been frozen');
            }
        } elseif (2 == $user->status) {
            return $this->returnJson('account has been frozen');
        }
        $user->pushid = $req->pushid ?? '';
        $user->last_login_ip = $req->getClientIp();
        if (strtotime($user->expired_at) <= time()) {
            $user->token = md5(encrypt($user->mobile.'.'.$user->last_login_ip.'.'.time()));
        }
        $user->expired_at = date("Y-m-d H:i:s", strtotime("+1 week"));
        $data->status = 0;
        try {
            return DB::transaction(function () use ($data,$user) {
                if (false == $data->save()) {
                    return $this->returnJson('data save failed');
                }
                if (false == $user->save()) {
                    return $this->returnJson('data save failed');
                }
                if (false == $user->password) {
                    $user->password = 0;
                }
                $user->password = $user->password ? 1 : 0;
                return $this->returnJson('success', ['token'=>$user->token,'mobile'=>$user->mobile,'name'=>$user->name,'password'=>$user->password]);
            });
        } catch (\Exception $e) {
            return $this->returnJson($e->getMessage());
        }
    }

    /**
     * showdoc
     * @catalog 登录注册
     * @title 密码登录
     * @description 密码登录的接口
     * @method post
     * @url api/mobile/passwordLogin
     * @param mobile 必选 string 手机号
     * @param code 必选 string 验证码
     * @param pushid 非必选 string 推送id
     * @return {"error_code":0,"error_msg":"\u6210\u529f","data":{"token":"5137d0209278a12205cac7881864fb7c","mobile":"15114580369","name":"151****0369"}}
     * @return_param error_code int 错误码
     * @return_param error_msg string 错误提示
     * @return_param -data object 返回数据
     * @return_param token string 标识
     * @return_param mobile string 手机号
     * @return_param name string 用户昵称
     * @remark
     * @number 4
     */
    public function passwordLogin(Request $req)
    {
        $this->useValidator($req, [
            'mobile'=>[0,1,101,301],
            'password'=>[0,1,101],
            'pushid'=>[0,3,101,250]
        ]);
        $data = User::select('id', 'name', 'mobile', 'expired_at', 'status', 'froze_at', 'froze_days', 'token')
            ->where('mobile', $req->mobile)
            ->where('password', md5(md5($req->password).env('APP_ATTACH')))
            ->first();
        if (false == $data) {
            return $this->returnJson('account does not exist');
        }
        if (0 == $data->status) {
            if (strtotime($data->froze_at) + ($data->froze_days * 86400) <= time()) {
                $data->status = 1;
                $data->froze_at = null;
                $data->froze_days = 0;
            } else {
                return $this->returnJson('account has been frozen');
            }
        } elseif (2 == $data->status) {
            return $this->returnJson('account has been frozen');
        }
        $data->pushid = $req->pushid ?? '';
        $data->last_login_ip = $req->getClientIp();
        if (strtotime($data->expired_at) <= time()) {
            $data->token = md5(encrypt($data->mobile.'.'.$data->last_login_ip.'.'.time()));
        }
        $data->expired_at = date("Y-m-d H:i:s", strtotime("+1 week"));
        $data->status = 1;
        if (false == $data->save()) {
            return $this->returnJson('data save failed');
        }
        return $this->returnJson('success', ['token'=>$data->token,'mobile'=>$data->mobile ?? '','name'=>$data->name]);
    }

    /**
     * showdoc
     * @catalog 登录注册
     * @title 找回密码
     * @description 找回密码的接口
     * @method post
     * @url api/mobile/resetPassword
     * @param mobile 必选 string 手机号
     * @param code 必选 string 验证码
     * @param password 非必选 string 密码
     * @return {"error_code":0,"error_msg":"\u6210\u529f","data":{}}
     * @return_param error_code int 错误码
     * @return_param error_msg string 错误提示
     * @return_param -data object 返回数据
     * @remark
     * @number 5
     */
    public function resetPassword(Request $req)
    {
        $this->useValidator($req, [
            'mobile'=>[0,1,101,301],
            'code'=>[0,1,102,249],
            'password'=>[0,1,101]
        ]);
        $data = \App\Models\Code::select('id', 'status', 'mobile')
            ->where('mobile', $req->mobile)
            ->where('type', 2)
            ->where('code', $req->code)
            ->where('status', 1)
            ->where('overdued_at', '>=', date('Y-m-d H:i:s'))
            ->searchUser()
            ->first();
        if (false == $data) {
            return $this->returnJson('data does not exist');
        }
        if (false == $data->getUser) {
            return $this->returnJson('account does not exist');
        } else {
            $user = $data->getUser;
        }
        $user->password = md5(md5($req->password).env('APP_ATTACH'));
        $user->expired_at = date("Y-m-d H:i:s");
        $data->status = 0;
        try {
            return DB::transaction(function () use ($data,$user) {
                if (false == $data->save()) {
                    return $this->returnJson('data save failed');
                }
                if (false == $user->save()) {
                    return $this->returnJson('data save failed');
                }
                return $this->returnJson('success');
            });
        } catch (\Exception $e) {
            return $this->returnJson($e->getMessage());
        }
    }
}
