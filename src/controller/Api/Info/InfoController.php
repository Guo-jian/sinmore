<?php

namespace App\Http\Controllers\Api\Info;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Info;
use DB;

class InfoController extends Controller
{
    use \App\Traits\CategoryTrait;

    /**
     * showdoc
     * @catalog 基础内容/资讯展示
     * @title 列表
     * @description 资讯展示列表的接口
     * @method post
     * @url api/info/list
     * @param token 必选 string 标识
     * @param page 必选 int 页数
     * @param pagesize 必选 int 每页条数
     * @param category_id 非必选 int 分类id
     * @return {"error_code":0,"error_msg":"\u6210\u529f","data":{"data":[{"id":1,"created_at":"2018-11-29 14:19:57","name":"\u8d44\u8baf1","category_id":6,"top":1,"status":1,"hot":"0","sort":0,"view":0,"get_category":{"id":6,"pid":5,"name":"\u4e09\u7ea7\u5206\u7c7b2","get_parent":{"id":5,"pid":1,"name":"\u4e8c\u7ea7\u5206\u7c7b2","get_parent":{"id":1,"pid":0,"name":"\u4e00\u7ea7\u5206\u7c7b1"}}}}],"current_page":1,"total_page":2,"count":2}}
     * @return_param error_code int 错误码
     * @return_param error_msg string 错误提示
     * @return_param -data object 返回数据
     * @return_param --data object 返回信息
     * @return_param id int 资讯id
     * @return_param thumb string 缩略图
     * @return_param name string 资讯名称
     * @return_param created_at string 创建时间
     * @return_param view int 点击量
     * @return_param current_page int 当前页
     * @return_param total_page int 总页数
     * @return_param count int 总条数
     * @remark
     * @number 1
     */
    public function list(Request $req)
    {
        $this->useValidator($req, [
            'page'=>[0,1,102],
            'pagesize'=>[0,1,102],
            'category_id'=>[0,3,102]
        ]);
        $data = Info::when($req->category_id, function ($query) use ($req) {
            $category = \App\Models\Category::where('id', $req->category_id)->first(['id','level']);
            if (false == $category) {
                return $this->returnJson('success', ['data'=>[],'current_page'=>(int)$req->page,'total_page'=>ceil($count/$req->pagesize),'count'=>0]);
            }
            $category = static::getAllCategory($category);
            return $query->whereIn('category_id', $category);
        })->where('status', 1);
        $count = $data->count();
        $data = $data->select('id', 'thumb', 'name', 'created_at', DB::raw('view + click as view'))
            ->orderBy('top', 'desc')
            ->orderBy('sort', 'desc')
            ->orderBy('id', 'desc')
            ->offset(($req->page-1)*$req->pagesize)
            ->limit($req->pagesize)
            ->get();
        return $this->returnJson('success', ['data'=>$data,'current_page'=>(int)$req->page,'total_page'=>ceil($count/$req->pagesize),'count'=>$count]);
    }

    /**
     * showdoc
     * @catalog 基础内容/资讯展示
     * @title 详情
     * @description 资讯展示详情的接口
     * @method post
     * @url api/info/detail
     * @param token 必选 string 标识
     * @param info_id 必选 int 资讯id
     * @return {"error_code":0,"error_msg":"\u6210\u529f","data":{"id":1,"name":"\u8d44\u8baf1","author":"\u65b0\u58a8","desc":"","title":"title","description":"description","keywords":"keywords","thumb":"http:\/\/pm.sinmore.vip\/xinmo1\/data\/logo.jpg","pic":"http:\/\/pm.sinmore.vip\/xinmo1\/data\/logo.jpg","content":"\u65b0\u58a8\u65b0\u58a8\u65b0\u58a8\u65b0\u58a8\u65b0\u58a8\u65b0\u58a8\u65b0\u58a8\u65b0\u58a8\u65b0\u58a8\u65b0\u58a8\u65b0\u58a8\u65b0\u58a8\u65b0\u58a8\u65b0\u58a8\u65b0\u58a8\u65b0\u58a8\u65b0\u58a8\u65b0\u58a8\u65b0\u58a8\u65b0\u58a8\u65b0\u58a8\u65b0\u58a8\u65b0\u58a8\u65b0\u58a8\u65b0\u58a8\u65b0\u58a8\u65b0\u58a8\u65b0\u58a8\u65b0\u58a8\u65b0\u58a8\u65b0\u58a8\u65b0\u58a8","created_at":"2018-11-29 14:19:57"}}
     * @return_param error_code int 错误码
     * @return_param error_msg string 错误提示
     * @return_param -data object 返回数据
     * @return_param id int 资讯id
     * @return_param name string 资讯名称
     * @return_param author string 作者
     * @return_param desc string 描述
     * @return_param title string title
     * @return_param description string description
     * @return_param keywords string keywords
     * @return_param thumb string 缩略图
     * @return_param pic string 推荐图
     * @return_param content string 内容
     * @return_param created_at string 创建时间
     * @remark
     * @number 2
     */
    public function detail(Request $req)
    {
        $this->useValidator($req, [
            'info_id'=>[0,1,102]
        ]);
        $data = Info::where('id', $req->info_id)->where('status', 1);
        $data->increment('view', 1);
        $data = $data->select('id', 'name', 'author', 'desc', 'title', 'description', 'keywords', 'thumb', 'pic', 'content', 'created_at')->first();
        return $data ? $this->returnJson('success', $data) : $this->returnJson('data does not exist');
    }
}
