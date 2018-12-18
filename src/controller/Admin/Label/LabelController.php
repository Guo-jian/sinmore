<?php

namespace App\Http\Controllers\Admin\Label;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Label;

class LabelController extends Controller
{
    /**
     * showdoc
     * @catalog 内容管理/一级标签
     * @title 添加
     * @description 添加标签的接口
     * @method post
     * @url admin/label/add
     * @param token 必选 string 标识
     * @param name 必选 string 名称
     * @param sort 非必选 int 排序值
     * @param pic 非必选 string 图片链接
     * @return {"error_code":0,"error_msg":"\u6210\u529f","data":{"label_id":4}}
     * @return_param error_code int 错误码
     * @return_param error_msg string 错误提示
     * @return_param -data object 返回数据
     * @return_param label_id int 标签id
     * @remark
     * @number 1
     */
    public function add(Request $req)
    {
        $this->useValidator($req, [
            'name'=>[0,1,101,215],
            'sort'=>[0,3,102,259],
            'pic'=>[0,3,101,255]
        ]);
        if (Label::where('name', $req->name)->count()) {
            return $this->returnJson('duplicate data name');
        }
        $data = new Label();
        $data->name = $req->name;
        $data->sort = $req->sort ?? 0;
        $data->pic = $req->pic ?? '';
        return $data->save() ? $this->returnJson('success', ['label_id'=>$data->id]) : $this->returnJson('data save failed');
    }

    /**
     * showdoc
     * @catalog 内容管理/一级标签
     * @title 修改
     * @description 修改标签的接口
     * @method post
     * @url admin/label/update
     * @param token 必选 string 标识
     * @param label_id 必选 int 标签id
     * @param name 必选 string 名称
     * @param sort 非必选 int 排序值
     * @param pic 非必选 string 图片链接
     * @return {"error_code":0,"error_msg":"\u6210\u529f","data":{"label_id":4}}
     * @return_param error_code int 错误码
     * @return_param error_msg string 错误提示
     * @return_param -data object 返回数据
     * @return_param label_id int 标签id
     * @remark
     * @number 2
     */
    public function update(Request $req)
    {
        $this->useValidator($req, [
            'label_id'=>[0,1,102],
            'name'=>[0,1,101,215],
            'sort'=>[0,3,102,259],
            'pic'=>[0,3,101,255]
        ]);
        if (Label::where('name', $req->name)->where('id', '!=', $req->label_id)->count()) {
            return $this->returnJson('duplicate data name');
        }
        $data = Label::find($req->label_id);
        if (false == $data) {
            return $this->returnJson('data does not exist');
        }
        $data->name = $req->name;
        $data->sort = $req->sort ?? 0;
        $data->pic = $req->pic ?? '';
        return $data->save() ? $this->returnJson('success', ['label_id'=>$data->id]) : $this->returnJson('data save failed');
    }

    /**
     * showdoc
     * @catalog 内容管理/一级标签
     * @title 列表
     * @description 标签列表的接口
     * @method post
     * @url admin/label/list
     * @param token 必选 string 标识
     * @param page 必选 int 页数
     * @param pagesize 必选 int 每页条数
     * @return {"error_code":0,"error_msg":"\u6210\u529f","data":{"data":[{"id":3,"name":"\u51ef\u5c14\u7279\u4eba","sort":3}],"current_page":1,"total_page":4,"count":4}}
     * @return_param error_code int 错误码
     * @return_param error_msg string 错误提示
     * @return_param -data object 返回数据
     * @return_param --data object 返回信息
     * @return_param name string 名称
     * @return_param sort int 排序
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
        $data = new Label();
        $count = $data->count();
        $data = $data->select('id', 'name', 'sort')
            ->orderBy('sort', 'desc')
            ->orderBy('id', 'desc')
            ->offset(($req->page-1)*$req->pagesize)
            ->limit($req->pagesize)
            ->get();
        return $this->returnJson('success', ['data'=>$data,'current_page'=>(int)$req->page,'total_page'=>ceil($count/$req->pagesize),'count'=>$count]);
    }

    /**
     * showdoc
     * @catalog 内容管理/一级标签
     * @title 详情
     * @description 标签详情的接口
     * @method post
     * @url admin/label/detail
     * @param token 必选 string 标识
     * @param label_id 必选 int 标签id
     * @return {"error_code":0,"error_msg":"\u6210\u529f","data":{"id":2,"name":"\u516c\u725b","sort":2,"pic":"http:\/\/api.uoosports.com\/storage\/team\/20181106\/g30zDS39JQxHZAVWN9Cm4Mk6iUofGdZ2stYfDOrh.jpeg"}}
     * @return_param error_code int 错误码
     * @return_param error_msg string 错误提示
     * @return_param -data object 返回数据
     * @return_param name string 名称
     * @return_param sort int 排序
     * @return_param pic string 图片
     * @remark
     * @number 4
     */
    public function detail(Request $req)
    {
        $this->useValidator($req, [
            'label_id'=>[0,1,102]
        ]);
        $data = Label::select('id', 'name', 'sort', 'pic')->find($req->label_id);
        return $data ? $this->returnJson('success', $data) : $this->returnJson('data does not exist');
    }

    /**
     * showdoc
     * @catalog 内容管理/一级标签
     * @title 排序
     * @description 标签排序的接口
     * @method post
     * @url admin/label/sort
     * @param token 必选 string 标识
     * @param label_id 必选 int 标签id
     * @param sort 必选 int 排序值
     * @return {"error_code":0,"error_msg":"\u6210\u529f","data":{"label_id":1}}
     * @return_param error_code int 错误码
     * @return_param error_msg string 错误提示
     * @return_param -data object 返回数据
     * @return_param label_id int 标签id
     * @remark
     * @number 5
     */
    public function sort(Request $req)
    {
        $this->useValidator($req, [
            'label_id'=>[0,1,102]
        ]);
        $data = Label::select('id', 'sort')->find($req->label_id);
        if (false == $data) {
            return $this->returnJson('data does not exist');
        }
        $data->sort = $req->sort;
        return $data->save() ? $this->returnJson('success', ['label_id'=>$data->id]) : $this->returnJson('data save failed');
    }

    /**
     * showdoc
     * @catalog 内容管理/一级标签
     * @title 删除
     * @description 标签删除的接口
     * @method post
     * @url admin/label/delete
     * @param token 必选 string 标识
     * @param label_id 必选 int 标签id
     * @return {"error_code":0,"error_msg":"\u6210\u529f","data":{"label_id":1}}
     * @return_param error_code int 错误码
     * @return_param error_msg string 错误提示
     * @return_param -data object 返回数据
     * @return_param label_id int 标签id
     * @remark
     * @number 6
     */
    public function del(Request $req)
    {
        $this->useValidator($req, [
            'label_id'=>[0,1,102]
        ]);
        $data = Label::find($req->label_id);
        if (false == $data) {
            return $this->returnJson('data does not exist');
        }
        return $data->delete() ? $this->returnJson('success', ['label_id'=>$data->id]) : $this->returnJson('data save failed');
    }
}
