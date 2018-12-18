<?php

$controller = <<<'CREATEDATA'
<?php
namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Validator;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function useValidator($request, $type = [])
    {
        $data = $this->assemble($type);
        $field = $data['field'];
        $explain = $data['explain'];
        return $this->checkVali($request, $field, $explain);
    }

    public function assemble($type)
    {
        foreach ($type as $k => $v) {
            //数据存在性验证0-99
            if (in_array(0, $v)) {
                $field[$k][] = 'bail';
                $explain[$k . '.bail'] = $k . '值为空或不存在';
            }
            if (in_array(1, $v)) {
                $field[$k][] = 'required';
                $explain[$k . '.required'] = $k . '值为空或不存在';
            }
            if (in_array(2, $v)) {
                $field[$k][] = 'sometimes';
            }
            if (in_array(3, $v)) {
                $field[$k][] = 'nullable';
            }
            if (in_array(4, $v)) {
                $field[$k][] = 'confirmed';
                $explain[$k . '.confirmed'] = $k . '两次输入不一直';
            }
            //数据类型100-199
            if (in_array(100, $v)) {
                $field[$k][] = 'boolean';
                $explain[$k . '.boolean'] = $k . '只能是布尔值';
            }
            if (in_array(101, $v)) {
                $field[$k][] = 'string';
                $explain[$k . '.string'] = $k . '必须为字符串';
            }
            if (in_array(102, $v)) {
                $field[$k][] = 'integer';
                $explain[$k . '.integer'] = $k . '必须为整数型';
            }
            if (in_array(103, $v)) {
                $field[$k][] = 'numeric';
                $explain[$k . '.numeric'] = $k . '必须是数字';
            }
            if (in_array(104, $v)) {
                $field[$k][] = 'array';
                $explain[$k . '.array'] = $k . '必须是数组格式';
            }
            if (in_array(105, $v)) {
                $field[$k][] = 'json';
                $explain[$k . '.json'] = $k . '必须是Json格式';
            }
            if (in_array(106, $v)) {
                $field[$k][] = 'image';
                $explain[$k . '.image'] = $k . '必须以jpeg、png、bmp、gif、或 svg 结尾';
            }
            if (in_array(107, $v)) {
                $field[$k][] = 'date';
                $explain[$k . '.date'] = $k . '必须是日期格式';
            }
            if (in_array(108, $v)) {
                $field[$k][] = 'email';
                $explain[$k . '.email'] = $k . '格式不正确';
            }
            if (in_array(109, $v)) {
                $field[$k][] = 'url';
                $explain[$k . '.url'] = $k . '格式不正确';
            }
            if (in_array(110, $v)) {
                $field[$k][] = 'timezone';
                $explain[$k . '.timezone'] = $k . '必须是时间格式';
            }
            if (in_array(111, $v)) {
                $field[$k][] = 'file';
                $explain[$k . '.file'] = $k . '必须是文件格式';
            }

            //数据长度验证200-299
            if (in_array(200, $v)) {
                $field[$k][] = 'digits_between:0,4294967296';
                $explain[$k . '.digits_between'] = $k . '超出表设置';
            }
            if (in_array(201, $v)) {
                $field[$k][] = 'min:1';
                $explain[$k . '.min'] = $k . '不能小于1';
            }
            if (in_array(202, $v)) {
                $field[$k][] = 'max:100';
                $explain[$k . '.max'] = $k . '超出字数限制';
            }
            if (in_array(203, $v)) {
                $field[$k][] = 'max:3';
                $explain[$k . '.max'] = $k . '不能大于3';
            }
            if (in_array(204, $v)) {
                $field[$k][] = 'max:4';
                $explain[$k . '.max'] = $k . '不能大于4';
            }
            if (in_array(205, $v)) {
                $field[$k][] = 'max:5';
                $explain[$k . '.max'] = $k . '不能大于5';
            }
            if (in_array(206, $v)) {
                $field[$k][] = 'min:6';
                $explain[$k . '.min'] = $k . '不能小于6';
            }
            if (in_array(208, $v)) {
                $field[$k][] = 'max:8';
                $explain[$k . '.max'] = $k . '超出名称字数限制';
            }
            if (in_array(210, $v)) {
                $field[$k][] = 'max:10';
                $explain[$k . '.max'] = $k . '超出字数限制';
            }
            if (in_array(212, $v)) {
                $field[$k][] = 'min:100';
                $explain[$k . '.min'] = $k . '小于100';
            }
            if (in_array(215, $v)) {
                $field[$k][] = 'max:15';
                $explain[$k . '.max'] = $k . '超出字数限制';
            }
            if (in_array(218, $v)) {
                $field[$k][] = 'max:18';
                $explain[$k . '.max'] = $k . '超出字数限制';
            }
            if (in_array(220, $v)) {
                $field[$k][] = 'max:20';
                $explain[$k . '.max'] = $k . '超出字数限制';
            }
            if (in_array(225, $v)) {
                $field[$k][] = 'max:25';
                $explain[$k . '.max'] = $k . '超出字数限制';
            }
            if (in_array(230, $v)) {
                $field[$k][] = 'max:30';
                $explain[$k . '.max'] = $k . '超出字数限制';
            }
            if (in_array(235, $v)) {
                $field[$k][] = 'max:5000';
                $explain[$k . '.max'] = $k . '超出字数限制';
            }
            if (in_array(239, $v)) {
                $field[$k][] = 'max:999';
                $explain[$k . '.max'] = $k . '超出字数限制';
            }
            if (in_array(240, $v)) {
                $field[$k][] = 'max:40';
                $explain[$k . '.max'] = $k . '超出字数限制';
            }
            if (in_array(249, $v)) {
                $field[$k][] = 'max:9999';
                $explain[$k . '.max'] = $k . '超出字数限制';
            }
            if (in_array(250, $v)) {
                $field[$k][] = 'max:50';
                $explain[$k . '.max'] = $k . '超出字数限制';
            }
            if (in_array(255, $v)) {
                $field[$k][] = 'max:255';
                $explain[$k . '.max'] = $k . '超出字数限制';
            }
            if (in_array(259, $v)) {
                $field[$k][] = 'max:99999';
                $explain[$k . '.max'] = $k . '超出字数限制';
            }

            //正则类300-399
            //密码验证
            if (in_array(300, $v)) {
                $field[$k][] = 'regex:/^(?=.*[a-z])(?=.*\d)(?=.*[#@!~%^&*])[a-z\d#@!~%^&*]{6,12}/i';
                $explain[$k . '.regex'] = $k . '最少含有一个大写字母,一个小写字母,一个数字和#@!~^&*中的任意一个字符';
            }
            //手机号码验证
            if (in_array(301, $v)) {
                $field[$k][] = 'regex:/^1[123456789]\d{9}$/';
                $explain[$k . '.regex'] = $k . '手机号码格式不正确';
            }

            //在数组中400-499
            if (in_array(400, $v)) {
                $field[$k][] = 'in:0,1,2,3,4,5,6,7,8,9,10';
                $explain[$k . '.in'] = $k . '必须在0-10之间';
            }
            if (in_array(401, $v)) {
                $field[$k][] = 'in:0,1';
                $explain[$k . '.in'] = $k . '必须是0、1';
            }
            if (in_array(402, $v)) {
                $field[$k][] = 'in:0,1,2';
                $explain[$k . '.in'] = $k . '必须在0-2之间';
            }
            if (in_array(403, $v)) {
                $field[$k][] = 'in:0,1,2,3';
                $explain[$k . '.in'] = $k . '必须在0-3之间';
            }
            if (in_array(412, $v)) {
                $field[$k][] = 'in:1,2';
                $explain[$k . '.in'] = $k . '必须在1-2之间';
            }
            if (in_array(413, $v)) {
                $field[$k][] = 'in:1,2,3';
                $explain[$k . '.in'] = $k . '必须在1-3之间';
            }
            if (in_array(414, $v)) {
                $field[$k][] = 'in:1,2,3,4';
                $explain[$k . '.in'] = $k . '必须在1-3之间';
            }
            if (in_array(415, $v)) {
                $field[$k][] = 'in:1,2,3,4,5';
                $explain[$k . '.in'] = $k . '必须在1-5之间';
            }
            if (in_array(416, $v)) {
                $field[$k][] = 'in:1,2,3,4,5,6';
                $explain[$k . '.in'] = $k . '必须在1-6之间';
            }
        }
        return ['field' => $field, 'explain' => $explain];
    }

    public function checkVali($request, $field, $explain)
    {
        $validator = Validator::make($request->all(), $field, $explain);
        if ($validator->fails()) {
            header('Content-type: application/json');
            exit(json_encode([
                'error_code'=>1,
                'error_msg'=>implode(',', $validator->errors()->all()),
                'data'=>(object)[]
            ]));
        }
    }

    public function returnJson($msg, $data = [], $code = 9999, $lang = 'zh-CN')
    {
        return response()->json([
            'error_code' => config('lang.'.$msg.'.code') ?? $code,
            'error_msg' => ('zh-CN' == $lang) ? config('lang.'.$msg.'.msg') ?? $msg : $msg,
            'data' => empty($data) ? (object)$data : $data
        ]);
    }
}
CREATEDATA;
