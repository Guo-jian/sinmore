<?php

namespace App\Http\Middleware;

use Closure;

class CheckUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ('' == $request->token) {
            return response()->json([
                'error_code' => config('lang.password has expired.code') ?? 9999,
                'error_msg' => ('zh-CN' == config('app.locale')) ? config('lang.password has expired.msg') ?? 'password has expired' : 'password has expired',
                'data' => (object)[]
            ]);
        }
        $data = \App\Models\User::where('token', $request->token)->first();
        if (false == $data) {
            return response()->json([
                'error_code' => config('lang.account is already logged in on another device.code') ?? 9999,
                'error_msg' => ('zh-CN' == config('app.locale')) ? config('lang.account is already logged in on another device.msg') ?? 'account is already logged in on another device' : 'account is already logged in on another device',
                'data' => (object)[]
            ]);
        }
        if (0 == $data->status) {
            if (strtotime($data->froze_at) + ($data->froze_days * 86400) <= time()) {
                $data->status = 1;
                $data->froze_at = null;
                $data->froze_days = 0;
            } else {
                return response()->json([
                    'error_code' => config('lang.account has been frozen.code') ?? 9999,
                    'error_msg' => ('zh-CN' == config('app.locale')) ? config('lang.account has been frozen.msg') ?? 'account has been frozen' : 'account has been frozen',
                    'data' => (object)[]
                ]);
            }
        } elseif (2 == $data->status) {
            return response()->json([
                'error_code' => config('lang.account has been frozen.code') ?? 9999,
                'error_msg' => ('zh-CN' == config('app.locale')) ? config('lang.account has been frozen.msg') ?? 'account has been frozen' : 'account has been frozen',
                'data' => (object)[]
            ]);
        }
        if (time() > strtotime($data->expired_at)) {
            return response()->json([
                'error_code' => config('lang.password has expired.code') ?? 9999,
                'error_msg' => ('zh-CN' == config('app.locale')) ? config('lang.password has expired.msg') ?? 'password has expired' : 'password has expired',
                'data' => (object)[]
            ]);
        }
        $data->expired_at = date('Y-m-d H:i:s', strtotime('+1 week'));
        if (false == $data->save()) {
            return response()->json([
                'error_code' => config('lang.data save failed.code') ?? 9999,
                'error_msg' => ('zh-CN' == config('app.locale')) ? config('lang.data save failed.msg') ?? 'data save failed' : 'data save failed',
                'data' => (object)[]
            ]);
        }
        $request->user = $data;
        return $next($request);
    }
}
