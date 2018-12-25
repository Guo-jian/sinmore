<?php

namespace App\Http\Controllers\Common;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CommonController extends Controller
{
    use \App\Traits\CodeTrait;

    /**
     * showdoc
     * @catalog 通用
     * @title 获取所有管理员
     * @description 获取所有管理员的接口
     * @method post
     * @url common/getAllAdmin
     * @return {"error_code":0,"error_msg":"\u6210\u529f","data":[{"id":1,"name":"admin","mobile":"admin"},{"id":2,"name":"\u90ed\u5efa","mobile":"15114580369"}]}
     * @return_param error_code int 错误码
     * @return_param error_msg string 错误提示
     * @return_param -data object 返回数据
     * @return_param id int 管理员id
     * @return_param name string 管理员名称
     * @return_param mobile string 手机号
     * @remark
     * @number 1
     */
    public function getAllAdmin()
    {
        $data = \App\Models\Admin::select('id', 'name', 'mobile')->get();
        return $this->returnJson('success', $data);
    }

    /**
     * showdoc
     * @catalog 通用
     * @title 获取所有管理组
     * @description 获取所有管理组的接口
     * @method post
     * @url common/getAllGroup
     * @return {"error_code":0,"error_msg":"\u6210\u529f","data":[{"id":1,"name":"\u6d4b\u8bd5\u4e00\u7ec4"}]}
     * @return_param error_code int 错误码
     * @return_param error_msg string 错误提示
     * @return_param -data object 返回数据
     * @return_param id int 管理组id
     * @return_param name string 管理组名称
     * @remark
     * @number 2
     */
    public function getAllGroup()
    {
        $data = \App\Models\Group::select('id', 'name')->get();
        return $this->returnJson('success', $data);
    }

    /**
     * showdoc
     * @catalog 通用
     * @title 获取所有权限
     * @description 获取所有权限的接口
     * @method post
     * @url common/getAllRule
     * @return {"error_code":0,"error_msg":"\u6210\u529f","data":[{"id":1,"pid":0,"title":"\u6743\u9650\u7cfb\u7edf","get_rule":[{"id":2,"pid":1,"title":"\u7ba1\u7406\u7ec4","path":"","get_rule":[{"id":3,"pid":2,"title":"\u6dfb\u52a0\u7ba1\u7406\u7ec4","path":""},{"id":4,"pid":2,"title":"\u4fee\u6539\u7ba1\u7406\u7ec4","path":""},{"id":5,"pid":2,"title":"\u7ba1\u7406\u7ec4\u8be6\u60c5","path":""},{"id":6,"pid":2,"title":"\u5220\u9664\u7ba1\u7406\u7ec4","path":""},{"id":7,"pid":2,"title":"\u8bbe\u7f6e\u6743\u9650","path":""},{"id":8,"pid":2,"title":"\u6743\u9650\u8be6\u60c5","path":""}]},{"id":9,"pid":1,"title":"\u7ba1\u7406\u5458","path":"","get_rule":[{"id":10,"pid":9,"title":"\u6dfb\u52a0\u7ba1\u7406\u5458","path":""}]}]}]}
     * @return_param error_code int 错误码
     * @return_param error_msg string 错误提示
     * @return_param -data object 返回数据
     * @return_param id int 权限id
     * @return_param pid int 上级id
     * @return_param title string 权限标题
     * @return_param --get_rule
     * @return_param id int 权限id
     * @return_param pid int 上级id
     * @return_param title string 权限标题
     * @return_param path string 前台路由
     * @remark
     * @number 3
     */
    public function getAllRule()
    {
        $data = \App\Models\Rule::select('id', 'pid', 'title')
            ->where('status', 1)
            ->where('pid', 0)
            ->searchRule(2)
            ->get();
        return $this->returnJson('success', $data);
    }

    /**
     * showdoc
     * @catalog 通用
     * @title 发送验证码
     * @description 发送验证码的接口
     * @method post
     * @url common/code
     * @param mobile 必选 string 手机号
     * @param type 必选 int 1注册登录,2找回密码,3绑定手机
     * @return {"error_code":0,"error_msg":"\u6210\u529f","data":{"code":"3508"}}
     * @return_param error_code int 错误码
     * @return_param error_msg string 错误提示
     * @return_param -data object 返回数据
     * @return_param code int 验证码
     * @remark
     * @number 4
     */
    public function code(Request $req)
    {
        $this->useValidator($req, [
            'mobile'=>[0,1,101,301],
            'type'=>[0,1,102,414]
        ]);
        $old = \App\Models\Code::where('mobile', $req->mobile)
            ->where('type', $req->type)
            ->where('status', 1)
            ->where('overdued_at', '>=', date('Y-m-d H:i:s', time()))
            ->value('code');
        $app = static::setCodeApp();
        if ($old) {
            $app->send($req->mobile, [
                'content'=>'【新墨科技】您的验证码是'.$old.'。如非本人操作，请忽略本短信',
                'template'=>'',
                'data'=>['code'=>$old],
            ]);
            return $this->returnJson('success', ['code'=>$old]);
        }
        $code = new \App\Models\Code();
        $code->mobile = $req->mobile;
        $code->type = $req->type;
        $code->code = str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_RIGHT);
        $code->overdued_at = date('Y-m-d H:i:s', strtotime("+15 minute"));
        if (false == $code->save()) {
            return $this->returnJson('data save failed');
        }
        $app->send($req->mobile, [
            'content'=>'【新墨科技】您的验证码是'.$code->code.'。如非本人操作，请忽略本短信',
            'template'=>'',
            'data'=>['code'=>$code->code],
        ]);
        return $this->returnJson('success', ['code'=>$code->code]);
    }

    /**
     * showdoc
     * @catalog 通用
     * @title 上传多图
     * @description 上传多图的接口
     * @method post
     * @url common/upload
     * @param file[] 必传 array 图片
     * @param model 必传 string banner,category(分类),content(单页),Info(资讯),user(用户)
     * @return {"error_code": 0,"error_msg": "成功","data": [{"url": "http://xxx.com/storage/video/20180830/bxtt705nrqLmq8IvZPX5WFH1eXFf4NrEUDcKiCH6.zip"},{"url": "http://xxx/video/20180830/2O9t62Fj8BXh3A41ZvddSmbVNCU0nOSzihQDAMOg.png"}]}
     * @return_param error_code number 无
     * @return_param error_msg string 无
     * @return_param -data object 无
     * @return_param url string 图片地址
     * @remark
     * @number 5
     */
    public function upload(Request $req)
    {
        $this->useValidator($req, [
            'file'=>[0,1,104],
            'file.*'=>[0,1,111],
            'model'=>[0,1,101]
        ]);
        $path = [];
        if (!array_key_exists($req->model, config('filesystems.disks'))) {
            return $this->returnJson('model does not exist');
        }
        foreach ($req->file as $k => $v) {
            array_push($path, ['url'=>env('APP_URL').'/storage/'.$req->model.'/'.date('Ymd').'/'.$v->store('', $req->model, 'public')]);
        }
        return $this->returnJson('success', $path);
    }

    /**
     * showdoc
     * @catalog 通用
     * @title 单图上传
     * @description 单图上传的接口
     * @method post
     * @url common/uploadOnce
     * @param file 必传 file 图片
     * @param model 必传 string banner,category(分类),content(单页),Info(资讯),user(用户)
     * @return {"error_code": 0,"error_msg": "成功","data": {"url": "http://xxx.com/storage/video/20180830/bxtt705nrqLmq8IvZPX5WFH1eXFf4NrEUDcKiCH6.zip"}}
     * @return_param error_code number 无
     * @return_param error_msg string 无
     * @return_param -data object 无
     * @return_param url string 图片地址
     * @remark
     * @number 6
     */
    public function uploadOnce(Request $req)
    {
        $this->useValidator($req, [
            'file'=>[0,1,111],
            'model'=>[0,1,101]
        ]);
        if (!array_key_exists($req->model, config('filesystems.disks'))) {
            return $this->returnJson('model does not exist');
        }
        return $this->returnJson('success', ['url'=>env('APP_URL').'/storage/'.$req->model.'/'.date('Ymd').'/'.$req->file->store('', $req->model, 'public')]);
    }

    /**
     * showdoc
     * @catalog 通用
     * @title 资讯分类
     * @description 资讯分类的接口
     * @method post
     * @url common/categroy
     * @param category_id 必传 int 资讯id(获取一级分类传0)
     * @return {"error_code":0,"error_msg":"\u6210\u529f","data":[{"id":3,"name":"\u5927\u724c\u4f01\u4e1a","thumb":""},{"id":1,"name":"app","thumb":""},{"id":2,"name":"\u7269\u8054\u7f51","thumb":""}]}
     * @return_param error_code number 无
     * @return_param error_msg string 无
     * @return_param -data object 无
     * @return_param id int 资讯分类id
     * @return_param name string 资讯分类名称
     * @return_param thumb string 资讯分类缩略图
     * @remark
     * @number 7
     */
    public function categroy(Request $req)
    {
        $this->useValidator($req,[
            'category_id'=>[0,1,102]
        ]);
        $data = \App\Models\Category::select('id','name','thumb')->where('pid',$req->category_id)->where('status',1)->orderBy('sort','desc')->get();
        return $this->returnJson('success',$data);
    }
}
