<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Storage;

class User extends Model
{
    public function scopeRefreshUser($query, $user)
    {
        if (isset($user['openid'])) {
            $user['openId'] = $user['openid'];
        }
        $data = $this::where('openid', $user['openId'])->first(['id','openid','expired_at','token','unionid','mobile','name','status','froze_days','froze_at']);
        if (false == $data) {
            $data = new $this;
            $data->openid = $user['openId'];
            isset($user['avatarUrl']) ? Storage::put('public/avatars/'.$data->openid.'.jpg', file_get_contents($user['avatarUrl'])) : Storage::put('public/avatars/'.$data->openid.'.jpg', file_get_contents($user['headimgurl']));
            $data->avatar = config('app.url').'/storage/avatars/'.$data->openid.'.jpg';
            $data->name = isset($user['nickName']) ? $user['nickName'] : $user['nickname'];
            $data->sex = isset($user['gender']) ? $user['gender'] : $user['sex'];
            $data->prov = $user['province'];
            $data->city = $user['city'];
            if (isset($user['unionid'])) {
                $user['unionId'] = $user['unionid'];
            }
            $data->unionid = isset($user['unionId']) ? $user['unionId'] : '';
            $data->status = 1;
        }
        $data->last_login_ip = $user['ip'];
        if (strtotime($data->expired_at) <= time()) {
            $data->token = md5(encrypt($data->openid.'.'.$data->last_login_ip.'.'.time()));
        }
        $data->expired_at = date("Y-m-d H:i:s", strtotime("+1 week"));
        return $data;
    }

    public function scopeHasCode($query, $where = [])
    {
        return $query->whereHas('getCode', function ($query) use ($where) {
            $query->where($where);
        });
    }

    public function getCode()
    {
        return $this->hasOne('App\Models\Code', 'mobile', 'mobile');
    }
}
