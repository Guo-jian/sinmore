<?php

namespace App\Http\Middleware;

use Closure;

class CheckAdmin
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
        if ('admin/login' == $request->path()) {
            return $next($request);
        }
        if ('' == $request->token) {
            return response()->json([
                'error_code' => config('lang.password has expired.code') ?? 9999,
                'error_msg' => ('zh-CN' == config('app.locale')) ? config('lang.password has expired.msg') ?? 'password has expired' : 'password has expired',
                'data' => (object)[]
            ]);
        }
        $data = \App\Models\Admin::where('token', $request->token)->searchGroup(['id','rules'])->first();
        if (false == $data) {
            return response()->json([
                'error_code' => config('lang.account is already logged in on another device.code') ?? 9999,
                'error_msg' => ('zh-CN' == config('app.locale')) ? config('lang.account is already logged in on another device.msg') ?? 'account is already logged in on another device' : 'account is already logged in on another device',
                'data' => (object)[]
            ]);
        }
        if (0 == $data->status) {
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
        $data->expired_at = date('Y-m-d H:i:s', strtotime('+4 hours'));
        if (false == $data->save()) {
            return response()->json([
                'error_code' => config('lang.data save failed.code') ?? 9999,
                'error_msg' => ('zh-CN' == config('app.locale')) ? config('lang.data save failed.msg') ?? 'data save failed' : 'data save failed',
                'data' => (object)[]
            ]);
        }
        if (1 != $data->id) {
            if (0 == \App\Models\Rule::where('rule', $request->path())->whereIn('id', explode(',', $data->getGroup->rules))->count()) {
                return response()->json([
                    'error_code' => config('lang.no such permission.code') ?? 9999,
                    'error_msg' => ('zh-CN' == config('app.locale')) ? config('lang.no such permission.msg') ?? 'no such permission' : 'no such permission',
                    'data' => (object)[]
                ]);
            }
        }
        $request->admin = $data;
        return $next($request);
    }
}
