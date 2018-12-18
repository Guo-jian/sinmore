<?php

namespace App\Http\Controllers\Admin\Content;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Content;

class ContentController extends Controller
{
    /**
     * showdoc
     * @catalog 基础内容/单页内容
     * @title 添加
     * @description 添加单页内容的接口
     * @method post
     * @url admin/content/add
     * @param token 必选 string 标识
     * @param name 必选 string 标题
     * @param desc 非必选 string 描述
     * @param title 非必选 string title
     * @param description 非必选 string description
     * @param keyword 非必选 string keyword
     * @param content 必选 string 详情
     * @return {"error_code":0,"error_msg":"\u6210\u529f","data":{"content_id":2}}
     * @return_param error_code int 错误码
     * @return_param error_msg string 错误提示
     * @return_param -data object 返回数据
     * @return_param content_id int 内容id
     * @remark
     * @number 1
     */
    public function add(Request $req)
    {
        $this->useValidator($req, [
            'name'=>[0,1,101,202],
            'desc'=>[0,3,101,255],
            'title'=>[0,3,101,255],
            'description'=>[0,3,101,255],
            'keywords'=>[0,3,101,255],
            'content'=>[0,1,101],
        ]);
        $data = new Content();
        $data->name = $req->name;
        $data->desc = $req->desc ?? '';
        $data->title = $req->title ?? '';
        $data->description = $req->description ?? '';
        $data->keywords = $req->keywords ?? '';
        $data->content = $req->content;
        return $data->save() ? $this->returnJson('success', ['content_id'=>$data->id]) : $this->returnJson('data save failed');
    }

    /**
     * showdoc
     * @catalog 内容管理/单页内容
     * @title 修改
     * @description 修改单页内容的接口
     * @method post
     * @url admin/content/update
     * @param token 必选 string 标识
     * @param content_id 必选 int 内容id
     * @param name 必选 string 标题
     * @param desc 非必选 string 描述
     * @param title 非必选 string title
     * @param description 非必选 string description
     * @param keyword 非必选 string keyword
     * @param content 必选 string 详情
     * @return {"error_code":0,"error_msg":"\u6210\u529f","data":{"content_id":2}}
     * @return_param error_code int 错误码
     * @return_param error_msg string 错误提示
     * @return_param -data object 返回数据
     * @return_param content_id int 内容id
     * @remark
     * @number 2
     */
    public function update(Request $req)
    {
        $this->useValidator($req, [
            'content_id'=>[0,1,102],
            'name'=>[0,1,101,202],
            'desc'=>[0,3,101,255],
            'title'=>[0,3,101,255],
            'description'=>[0,3,101,255],
            'keywords'=>[0,3,101,255],
            'content'=>[0,1,101],
        ]);
        $data = Content::find($req->content_id);
        if (false == $data) {
            return $this->returnJson('data does not exist');
        }
        $data->name = $req->name;
        $data->desc = $req->desc ?? '';
        $data->title = $req->title ?? '';
        $data->description = $req->description ?? '';
        $data->keywords = $req->keywords ?? '';
        $data->content = $req->content;
        return $data->save() ? $this->returnJson('success', ['content_id'=>$data->id]) : $this->returnJson('data save failed');
    }

    /**
     * showdoc
     * @catalog 内容管理/单页内容
     * @title 列表
     * @description 单页内容列表的接口
     * @method post
     * @url admin/content/list
     * @param token 必选 string 标识
     * @param page 必选 int 页数
     * @param pagesize 必选 int 每页条数
     * @return {"error_code":0,"error_msg":"\u6210\u529f","data":{"data":[{"id":2,"name":"name2","view":0},{"id":1,"name":"name","view":0}],"current_page":1,"total_page":1,"count":2,"url":"http:\/\/api.sinmore.com.cn\/api\/content\/"}}
     * @return_param error_code int 错误码
     * @return_param error_msg string 错误提示
     * @return_param -data object 返回数据
     * @return_param --data object 返回信息
     * @return_param id int 内容id
     * @return_param name string 内容标题
     * @return_param view int 浏览量
     * @return_param current_page int 当前页
     * @return_param total_page int 总页数
     * @return_param count int 总条数
     * @return_param url string 链接地址
     * @remark 链接地址后拼内容id,例如https://api.sinmore.com.cn/api/content/1
     * @number 3
     */
    public function list(Request $req)
    {
        $this->useValidator($req, [
            'page'=>[0,1,102],
            'pagesize'=>[0,1,102]
        ]);
        $data = new Content();
        $count = $data->count();
        $data = $data->select('id', 'name', 'view')
            ->orderBy('id', 'desc')
            ->offset(($req->page-1)*$req->pagesize)
            ->limit($req->pagesize)
            ->get();
        return $this->returnJson('success', ['data'=>$data,'current_page'=>(int)$req->page,'total_page'=>ceil($count/$req->pagesize),'count'=>$count,'url'=>env('APP_URL').'/api/content/']);
    }

    /**
     * showdoc
     * @catalog 内容管理/单页内容
     * @title 关键字
     * @description 单页内容关键字的接口
     * @method post
     * @url admin/content/keyword
     * @param token 必选 string 标识
     * @param page 必选 int 页数
     * @param pagesize 必选 int 每页条数
     * @param type 必选 int 1id,2标题
     * @param keyword 必选 string 标题
     * @return {"error_code":0,"error_msg":"\u6210\u529f","data":{"data":[{"id":2,"name":"name2","view":0}],"current_page":1,"total_page":1,"count":1,"url":"http:\/\/api.sinmore.com.cn"}}
     * @return_param error_code int 错误码
     * @return_param error_msg string 错误提示
     * @return_param -data object 返回数据
     * @return_param --data object 返回信息
     * @return_param id int 内容id
     * @return_param name string 内容标题
     * @return_param view int 浏览量
     * @return_param current_page int 当前页
     * @return_param total_page int 总页数
     * @return_param count int 总条数
     * @return_param url string 链接地址
     * @remark 链接地址后拼内容id,例如https://api.sinmore.com.cn/api/content/1
     * @number 4
     */
    public function keyword(Request $req)
    {
        $this->useValidator($req, [
            'type'=>[0,1,102,412],
            'keyword'=>[0,1,101],
            'page'=>[0,1,102],
            'pagesize'=>[0,1,102]
        ]);
        $data = Content::when(1 == $req->type, function ($query) use ($req) {
            return $query->where('id', 'like', "%$req->keyword%");
        })->when(2 == $req->type, function ($query) use ($req) {
            return $query->where('name', 'like', "%$req->keyword%");
        });
        $count = $data->count();
        $data = $data->select('id', 'name', 'view')
            ->orderBy('id', 'desc')
            ->offset(($req->page-1)*$req->pagesize)
            ->limit($req->pagesize)
            ->get();
        return $this->returnJson('success', ['data'=>$data,'current_page'=>(int)$req->page,'total_page'=>ceil($count/$req->pagesize),'count'=>$count,'url'=>env('APP_URL')]);
    }

    /**
     * showdoc
     * @catalog 内容管理/单页内容
     * @title 详情
     * @description 单页内容详情的接口
     * @method post
     * @url admin/content/detail
     * @param token 必选 string 标识
     * @param content_id 必选 int 内容id
     * @return {"error_code":0,"error_msg":"\u6210\u529f","data":{"id":1,"name":"name","desc":"desc","title":"title","description":"description","keywords":"keywords","content":"content","view":0,"created_at":"2018-11-26 18:43:17","updated_at":"2018-11-26 18:43:17"}}
     * @return_param error_code int 错误码
     * @return_param error_msg string 错误提示
     * @return_param -data object 返回数据
     * @return_param id int 内容id
     * @return_param name string 标题
     * @return_param desc string 描述
     * @return_param title string title
     * @return_param description string description
     * @return_param keywords string keywords
     * @return_param content string 详情
     * @remark
     * @number 5
     */
    public function detail(Request $req)
    {
        $this->useValidator($req, [
            'content_id'=>[0,1,102]
        ]);
        $data = Content::find($req->content_id);
        return $data ? $this->returnJson('success', $data) : $this->returnJson('data does not exist');
    }

    /**
     * showdoc
     * @catalog 内容管理/单页内容
     * @title 删除
     * @description 删除单页内容的接口
     * @method post
     * @url admin/content/delete
     * @param token 必选 string 标识
     * @param content_id 必选 int 内容id
     * @return {"error_code":0,"error_msg":"\u6210\u529f","data":{"content_id":2}}
     * @return_param error_code int 错误码
     * @return_param error_msg string 错误提示
     * @return_param -data object 返回数据
     * @return_param content_id int 内容id
     * @remark
     * @number 6
     */
    public function del(Request $req)
    {
        $this->useValidator($req, [
            'content_id'=>[0,1,102],
        ]);
        $data = Content::find($req->content_id);
        if (false == $data) {
            return $this->returnJson('data does not exist');
        }
        return $data->delete() ? $this->returnJson('success', ['content_id'=>$data->id]) : $this->returnJson('data save failed');
    }
}
