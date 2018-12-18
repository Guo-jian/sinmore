<?php

namespace App\Http\Controllers\Admin\Info;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Info;

class InfoController extends Controller
{
    use \App\Traits\CategoryTrait;

    /**
     * showdoc
     * @catalog 内容管理/资讯管理
     * @title 添加
     * @description 添加资讯的接口
     * @method post
     * @url admin/info/add
     * @param token 必选 string 标识
     * @param category_id 必选 int 分类id
     * @param name 必选 string 资讯名称
     * @param author 非必选 string 作者
     * @param desc 非必选 string 描述
     * @param title 非必选 string title
     * @param description 非必选 string description
     * @param keywords 非必选 string keywords
     * @param thumb 非必选 string 缩略图
     * @param pic 非必选 string 推荐图
     * @param hot 必选 int 推荐位
     * @param sort 非必选 int 排序值
     * @param click 非必选 int 附加点击量
     * @param content 必选 string 富文本内容
     * @return {"error_code":0,"error_msg":"\u6210\u529f","data":{"info_id":6}}
     * @return_param error_code int 错误码
     * @return_param error_msg string 错误提示
     * @return_param -data object 返回数据
     * @return_param info_id int 资讯id
     * @remark 不添加推荐位时,hot传0
     * @number 1
     */
    public function add(Request $req)
    {
        $this->useValidator($req, [
            'category_id'=>[0,1,102],
            'name'=>[0,1,101,202],
            'author'=>[0,3,101,220],
            'desc'=>[0,3,101,255],
            'title'=>[0,3,101,255],
            'description'=>[0,3,101,255],
            'keywords'=>[0,3,101,255],
            'thumb'=>[0,3,101,255],
            'pic'=>[0,3,101,255],
            'hot'=>[0,1,102,255],
            'sort'=>[0,3,102,259],
            'click'=>[0,3,102],
            'content'=>[0,1,101]
        ]);
        if (0 == \App\Models\Category::where('id', $req->category_id)->count()) {
            return $this->returnJson('data does not exist');
        }
        if (Info::where('name', $req->name)->count()) {
            return $this->returnJson('duplicate data name');
        }
        $data = new Info();
        $data->category_id = $req->category_id;
        $data->name = $req->name;
        $data->author = $req->author ?? '';
        $data->desc = $req->desc ?? '';
        $data->title = $req->title ?? '';
        $data->description = $req->description ?? '';
        $data->keywords = $req->keywords ?? '';
        $data->thumb = $req->thumb ?? '';
        $data->pic = $req->pic ?? '';
        $data->hot = $req->hot;
        $data->sort = $req->sort ?? 0;
        $data->click = $req->click ?? mt_rand(0, 999);
        $data->content = $req->content;
        return $data->save() ? $this->returnJson('success', ['info_id'=>$data->id]) : $this->returnJson('data save failed');
    }

    /**
     * showdoc
     * @catalog 内容管理/资讯管理
     * @title 修改
     * @description 修改资讯的接口
     * @method post
     * @url admin/info/update
     * @param token 必选 string 标识
     * @param info_id 必选 int 资讯id
     * @param category_id 必选 int 分类id
     * @param name 必选 string 资讯名称
     * @param author 非必选 string 作者
     * @param desc 非必选 string 描述
     * @param title 非必选 string title
     * @param description 非必选 string description
     * @param keywords 非必选 string keywords
     * @param thumb 非必选 string 缩略图
     * @param pic 非必选 string 推荐图
     * @param hot 必选 int 推荐位
     * @param sort 非必选 int 排序值
     * @param click 非必选 int 附加点击量
     * @param content 必选 string 富文本内容
     * @return {"error_code":0,"error_msg":"\u6210\u529f","data":{"info_id":6}}
     * @return_param error_code int 错误码
     * @return_param error_msg string 错误提示
     * @return_param -data object 返回数据
     * @return_param info_id int 资讯id
     * @remark 不添加推荐位时,hot传0
     * @number 2
     */
    public function update(Request $req)
    {
        $this->useValidator($req, [
            'info_id'=>[0,1,102],
            'category_id'=>[0,1,102],
            'name'=>[0,1,101,202],
            'author'=>[0,3,101,220],
            'desc'=>[0,3,101,255],
            'title'=>[0,3,101,255],
            'description'=>[0,3,101,255],
            'keywords'=>[0,3,101,255],
            'thumb'=>[0,3,101,255],
            'pic'=>[0,3,101,255],
            'hot'=>[0,1,102,255],
            'sort'=>[0,3,102,259],
            'click'=>[0,3,102],
            'content'=>[0,1,101]
        ]);
        if (0 == \App\Models\Category::where('id', $req->category_id)->count()) {
            return $this->returnJson('data does not exist');
        }
        if (Info::where('name', $req->name)->where('id', '!=', $req->info_id)->count()) {
            return $this->returnJson('duplicate data name');
        }
        $data = Info::find($req->info_id);
        if (false == $data) {
            return $this->returnJson('data does not exist');
        }
        $data->category_id = $req->category_id;
        $data->name = $req->name;
        $data->author = $req->author ?? '';
        $data->desc = $req->desc ?? '';
        $data->title = $req->title ?? '';
        $data->description = $req->description ?? '';
        $data->keywords = $req->keywords ?? '';
        $data->thumb = $req->thumb ?? '';
        $data->pic = $req->pic ?? '';
        $data->hot = $req->hot;
        $data->sort = $req->sort ?? 0;
        $data->click = $req->click ?? $data->click;
        $data->content = $req->content;
        return $data->save() ? $this->returnJson('success', ['info_id'=>$data->id]) : $this->returnJson('data save failed');
    }

    /**
     * showdoc
     * @catalog 内容管理/资讯管理
     * @title 列表
     * @description 资讯列表的接口
     * @method post
     * @url admin/info/list
     * @param token 必选 string 标识
     * @param page 必选 int 页数
     * @param pagesize 必选 int 每页条数
     * @return {"error_code":0,"error_msg":"\u6210\u529f","data":{"data":[{"id":1,"created_at":"2018-11-29 14:19:57","name":"\u8d44\u8baf1","category_id":6,"top":1,"status":1,"hot":"0","sort":0,"view":0,"get_category":{"id":6,"pid":5,"name":"\u4e09\u7ea7\u5206\u7c7b2","get_parent":{"id":5,"pid":1,"name":"\u4e8c\u7ea7\u5206\u7c7b2","get_parent":{"id":1,"pid":0,"name":"\u4e00\u7ea7\u5206\u7c7b1"}}}}],"current_page":1,"total_page":2,"count":2}}
     * @return_param error_code int 错误码
     * @return_param error_msg string 错误提示
     * @return_param -data object 返回数据
     * @return_param --data object 返回信息
     * @return_param id int 资讯id
     * @return_param created_at string 创建时间
     * @return_param name string 资讯名称
     * @return_param category_id int 分类id
     * @return_param top int 1置顶,0未置顶
     * @return_param status int 1正常,0冻结
     * @return_param hot int 推荐位
     * @return_param sort int 排序值
     * @return_param view int 真实点击量
     * @return_param ---get_category object 分类信息
     * @return_param id int 分类id
     * @return_param pid int 上级id
     * @return_param name int 分类名称
     * @return_param ----get_parent object 分类信息
     * @return_param id int 分类id
     * @return_param pid int 上级id
     * @return_param name int 分类名称
     * @return_param -----get_parent object 分类信息
     * @return_param id int 分类id
     * @return_param pid int 上级id
     * @return_param name int 分类名称
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
        $data = new Info();
        $count = $data->count();
        $data = $data->select('id', 'created_at', 'name', 'category_id', 'top', 'status', 'hot', 'sort', 'view')
            ->searchCategory(3)
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
     * @catalog 内容管理/资讯管理
     * @title 检索
     * @description 资讯检索的接口
     * @method post
     * @url admin/info/search
     * @param token 必选 string 标识
     * @param page 必选 int 页数
     * @param pagesize 必选 int 每页条数
     * @param hot 非必选 int 推荐位
     * @param category_id 非必选 int 分类id
     * @return {"error_code":0,"error_msg":"\u6210\u529f","data":{"data":[{"id":1,"created_at":"2018-11-29 14:19:57","name":"\u8d44\u8baf1","category_id":6,"top":1,"status":1,"hot":"0","sort":0,"view":0,"get_category":{"id":6,"pid":5,"name":"\u4e09\u7ea7\u5206\u7c7b2","get_parent":{"id":5,"pid":1,"name":"\u4e8c\u7ea7\u5206\u7c7b2","get_parent":{"id":1,"pid":0,"name":"\u4e00\u7ea7\u5206\u7c7b1"}}}}],"current_page":1,"total_page":2,"count":2}}
     * @return_param error_code int 错误码
     * @return_param error_msg string 错误提示
     * @return_param -data object 返回数据
     * @return_param --data object 返回信息
     * @return_param id int 资讯id
     * @return_param created_at string 创建时间
     * @return_param name string 资讯名称
     * @return_param category_id int 分类id
     * @return_param top int 1置顶,0未置顶
     * @return_param status int 1正常,0冻结
     * @return_param hot int 推荐位
     * @return_param sort int 排序值
     * @return_param view int 真实点击量
     * @return_param ---get_category object 分类信息
     * @return_param id int 分类id
     * @return_param pid int 上级id
     * @return_param name int 分类名称
     * @return_param ----get_parent object 分类信息
     * @return_param id int 分类id
     * @return_param pid int 上级id
     * @return_param name int 分类名称
     * @return_param -----get_parent object 分类信息
     * @return_param id int 分类id
     * @return_param pid int 上级id
     * @return_param name int 分类名称
     * @return_param current_page int 当前页
     * @return_param total_page int 总页数
     * @return_param count int 总条数
     * @remark
     * @number 4
     */
    public function search(Request $req)
    {
        $this->useValidator($req, [
            'page'=>[0,1,102],
            'pagesize'=>[0,1,102],
            'hot'=>[0,3,102],
            'category_id'=>[0,3,102]
        ]);
        if ($req->has('category_id') && $req->category_id) {
            $category = \App\Models\Category::where('id', $req->category_id)->first(['id','level']);
            if (false == $category) {
                return $this->returnJson('success', ['data'=>[],'current_page'=>(int)$req->page,'total_page'=>ceil($count/$req->pagesize),'count'=>0]);
            }
            $category = static::getAllCategory($category);
        } else {
            $category = [];
        }
        $data = Info::when($req->hot, function ($query) use ($req) {
            return $query->where('hot', $req->hot);
        })->when($category, function ($query) use ($category) {
            return $query->whereIn('category_id', $category);
        });
        $count = $data->count();
        $data = $data->select('id', 'created_at', 'name', 'category_id', 'top', 'status', 'hot', 'sort', 'view')
            ->searchCategory(3)
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
     * @catalog 内容管理/资讯管理
     * @title 搜索
     * @description 资讯搜索的接口
     * @method post
     * @url admin/info/keyword
     * @param token 必选 string 标识
     * @param page 必选 int 页数
     * @param pagesize 必选 int 每页条数
     * @param type 必选 int 1id,2标题
     * @param keyword 必选 string 关键字
     * @return {"error_code":0,"error_msg":"\u6210\u529f","data":{"data":[{"id":1,"created_at":"2018-11-29 14:19:57","name":"\u8d44\u8baf1","category_id":6,"top":1,"status":1,"hot":"0","sort":0,"view":0,"get_category":{"id":6,"pid":5,"name":"\u4e09\u7ea7\u5206\u7c7b2","get_parent":{"id":5,"pid":1,"name":"\u4e8c\u7ea7\u5206\u7c7b2","get_parent":{"id":1,"pid":0,"name":"\u4e00\u7ea7\u5206\u7c7b1"}}}}],"current_page":1,"total_page":2,"count":2}}
     * @return_param error_code int 错误码
     * @return_param error_msg string 错误提示
     * @return_param -data object 返回数据
     * @return_param --data object 返回信息
     * @return_param id int 资讯id
     * @return_param created_at string 创建时间
     * @return_param name string 资讯名称
     * @return_param category_id int 分类id
     * @return_param top int 1置顶,0未置顶
     * @return_param status int 1正常,0冻结
     * @return_param hot int 推荐位
     * @return_param sort int 排序值
     * @return_param view int 真实点击量
     * @return_param ---get_category object 分类信息
     * @return_param id int 分类id
     * @return_param pid int 上级id
     * @return_param name int 分类名称
     * @return_param ----get_parent object 分类信息
     * @return_param id int 分类id
     * @return_param pid int 上级id
     * @return_param name int 分类名称
     * @return_param -----get_parent object 分类信息
     * @return_param id int 分类id
     * @return_param pid int 上级id
     * @return_param name int 分类名称
     * @return_param current_page int 当前页
     * @return_param total_page int 总页数
     * @return_param count int 总条数
     * @remark
     * @number 5
     */
    public function keyword(Request $req)
    {
        $this->useValidator($req, [
            'page'=>[0,1,102],
            'pagesize'=>[0,1,102],
            'type'=>[0,1,102,412],
            'keyword'=>[0,1,101]
        ]);
        $data = Info::where(function ($query) use ($req) {
            if (1 == $req->type) {
                return $query->where('id', 'like', "%$req->keyword%");
            } else {
                return $query->where('name', 'like', "%$req->keyword%");
            }
        });
        $count = $data->count();
        $data = $data->select('id', 'created_at', 'name', 'category_id', 'top', 'status', 'hot', 'sort', 'view')
            ->searchCategory(3)
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
     * @catalog 内容管理/资讯管理
     * @title 详情
     * @description 资讯详情的接口
     * @method post
     * @url admin/info/detail
     * @param token 必选 string 标识
     * @param info_id 必选 int 资讯id
     * @return {"error_code":0,"error_msg":"\u6210\u529f","data":{"id":1,"category_id":6,"name":"\u8d44\u8baf1","author":"\u65b0\u58a8","desc":"","title":"title","description":"description","keywords":"keywords","thumb":"http:\/\/pm.sinmore.vip\/xinmo1\/data\/logo.jpg","pic":"http:\/\/pm.sinmore.vip\/xinmo1\/data\/logo.jpg","hot":"0","sort":0,"click":582,"view":0,"status":1,"top":1,"content":"\u65b0\u58a8\u65b0\u58a8\u65b0\u58a8\u65b0\u58a8\u65b0\u58a8\u65b0\u58a8\u65b0\u58a8\u65b0\u58a8\u65b0\u58a8\u65b0\u58a8\u65b0\u58a8\u65b0\u58a8\u65b0\u58a8\u65b0\u58a8\u65b0\u58a8\u65b0\u58a8\u65b0\u58a8\u65b0\u58a8\u65b0\u58a8\u65b0\u58a8\u65b0\u58a8\u65b0\u58a8\u65b0\u58a8\u65b0\u58a8\u65b0\u58a8\u65b0\u58a8\u65b0\u58a8\u65b0\u58a8\u65b0\u58a8\u65b0\u58a8\u65b0\u58a8\u65b0\u58a8","created_at":"2018-11-29 14:19:57","updated_at":"2018-11-29 14:19:57","get_category":{"id":6,"pid":5,"name":"\u4e09\u7ea7\u5206\u7c7b2","get_parent":{"id":5,"pid":1,"name":"\u4e8c\u7ea7\u5206\u7c7b2","get_parent":{"id":1,"pid":0,"name":"\u4e00\u7ea7\u5206\u7c7b1"}}}}}
     * @return_param error_code int 错误码
     * @return_param error_msg string 错误提示
     * @return_param -data object 返回数据
     * @return_param id int 资讯id
     * @return_param category_id int 分类id
     * @return_param name string 资讯名称
     * @return_param author string 作者
     * @return_param desc string 描述
     * @return_param title string title
     * @return_param description string description
     * @return_param keywords string keywords
     * @return_param thumb string 缩略图
     * @return_param pic string 推荐图
     * @return_param hot int 推荐位
     * @return_param sort int 排序值
     * @return_param click int 附加点击量
     * @return_param view int 真实点击量
     * @return_param status int 1正常,0冻结
     * @return_param top int 1置顶,0未置顶
     * @return_param content string 内容
     * @return_param created_at string 创建时间
     * @return_param updated_at string 修改时间
     * @return_param --get_category object 分类信息
     * @return_param id int 分类id
     * @return_param pid int 上级id
     * @return_param name int 分类名称
     * @return_param ---get_parent object 分类信息
     * @return_param id int 分类id
     * @return_param pid int 上级id
     * @return_param name int 分类名称
     * @return_param ----get_parent object 分类信息
     * @return_param id int 分类id
     * @return_param pid int 上级id
     * @return_param name int 分类名称
     * @remark
     * @number 6
     */
    public function detail(Request $req)
    {
        $this->useValidator($req, [
            'info_id'=>[0,1,102]
        ]);
        $data = Info::searchCategory(3)->find($req->info_id);
        return $data ? $this->returnJson('success', $data) : $this->returnJson('data does not exist');
    }

    /**
     * showdoc
     * @catalog 内容管理/资讯管理
     * @title 排序
     * @description 资讯排序的接口
     * @method post
     * @url admin/info/sort
     * @param token 必选 string 标识
     * @param info_id 必选 int 资讯id
     * @param sort 非必选 int 排序值
     * @return {"error_code":0,"error_msg":"\u6210\u529f","data":{"info_id":6}}
     * @return_param error_code int 错误码
     * @return_param error_msg string 错误提示
     * @return_param -data object 返回数据
     * @return_param info_id int 资讯id
     * @remark
     * @number 7
     */
    public function sort(Request $req)
    {
        $this->useValidator($req, [
            'info_id'=>[0,1,102],
            'sort'=>[0,1,102,259]
        ]);
        $data = Info::select('id', 'sort')->find($req->info_id);
        if (false == $data) {
            return $this->returnJson('data does not exist');
        }
        $data->sort = $req->sort;
        return $data->save() ? $this->returnJson('success', ['info_id'=>$data->id]) : $this->returnJson('data save failed');
    }

    /**
     * showdoc
     * @catalog 内容管理/资讯管理
     * @title 冻结
     * @description 资讯冻结的接口
     * @method post
     * @url admin/info/freeze
     * @param token 必选 string 标识
     * @param info_id 必选 int 资讯id
     * @return {"error_code":0,"error_msg":"\u6210\u529f","data":{"info_id":6}}
     * @return_param error_code int 错误码
     * @return_param error_msg string 错误提示
     * @return_param -data object 返回数据
     * @return_param info_id int 资讯id
     * @remark
     * @number 8
     */
    public function freeze(Request $req)
    {
        $this->useValidator($req, [
            'info_id'=>[0,1,102],
        ]);
        $data = Info::select('id', 'status')->find($req->info_id);
        if (false == $data) {
            return $this->returnJson('data does not exist');
        }
        if (0 == $data->status) {
            return $this->returnJson('data has been frozen');
        }
        $data->status = 0;
        return $data->save() ? $this->returnJson('success', ['info_id'=>$data->id]) : $this->returnJson('data save failed');
    }

    /**
     * showdoc
     * @catalog 内容管理/资讯管理
     * @title 解冻
     * @description 资讯解冻的接口
     * @method post
     * @url admin/info/unfreeze
     * @param token 必选 string 标识
     * @param info_id 必选 int 资讯id
     * @return {"error_code":0,"error_msg":"\u6210\u529f","data":{"info_id":6}}
     * @return_param error_code int 错误码
     * @return_param error_msg string 错误提示
     * @return_param -data object 返回数据
     * @return_param info_id int 资讯id
     * @remark
     * @number 9
     */
    public function unfreeze(Request $req)
    {
        $this->useValidator($req, [
            'info_id'=>[0,1,102],
        ]);
        $data = Info::select('id', 'status')->find($req->info_id);
        if (false == $data) {
            return $this->returnJson('data does not exist');
        }
        if (1 == $data->status) {
            return $this->returnJson('data does not frozen');
        }
        $data->status = 1;
        return $data->save() ? $this->returnJson('success', ['info_id'=>$data->id]) : $this->returnJson('data save failed');
    }

    /**
     * showdoc
     * @catalog 内容管理/资讯管理
     * @title 置顶
     * @description 资讯置顶的接口
     * @method post
     * @url admin/info/top
     * @param token 必选 string 标识
     * @param info_id 必选 int 资讯id
     * @return {"error_code":0,"error_msg":"\u6210\u529f","data":{"info_id":6}}
     * @return_param error_code int 错误码
     * @return_param error_msg string 错误提示
     * @return_param -data object 返回数据
     * @return_param info_id int 资讯id
     * @remark
     * @number 10
     */
    public function top(Request $req)
    {
        $this->useValidator($req, [
            'info_id'=>[0,1,102],
        ]);
        $data = Info::select('id', 'top')->find($req->info_id);
        if (false == $data) {
            return $this->returnJson('data does not exist');
        }
        if (1 == $data->top) {
            return $this->returnJson('data has been top');
        }
        $data->top = 1;
        return $data->save() ? $this->returnJson('success', ['info_id'=>$data->id]) : $this->returnJson('data save failed');
    }

    /**
     * showdoc
     * @catalog 内容管理/资讯管理
     * @title 取消置顶
     * @description 资讯取消置顶的接口
     * @method post
     * @url admin/info/down
     * @param token 必选 string 标识
     * @param info_id 必选 int 资讯id
     * @return {"error_code":0,"error_msg":"\u6210\u529f","data":{"info_id":6}}
     * @return_param error_code int 错误码
     * @return_param error_msg string 错误提示
     * @return_param -data object 返回数据
     * @return_param info_id int 资讯id
     * @remark
     * @number 11
     */
    public function down(Request $req)
    {
        $this->useValidator($req, [
            'info_id'=>[0,1,102],
        ]);
        $data = Info::select('id', 'top')->find($req->info_id);
        if (false == $data) {
            return $this->returnJson('data does not exist');
        }
        if (0 == $data->top) {
            return $this->returnJson('data has been top');
        }
        $data->top = 0;
        return $data->save() ? $this->returnJson('success', ['info_id'=>$data->id]) : $this->returnJson('data save failed');
    }

    /**
     * showdoc
     * @catalog 内容管理/资讯管理
     * @title 删除
     * @description 资讯删除的接口
     * @method post
     * @url admin/info/delete
     * @param token 必选 string 标识
     * @param info_id 必选 int 资讯id
     * @return {"error_code":0,"error_msg":"\u6210\u529f","data":{"info_id":6}}
     * @return_param error_code int 错误码
     * @return_param error_msg string 错误提示
     * @return_param -data object 返回数据
     * @return_param info_id int 资讯id
     * @remark
     * @number 12
     */
    public function del(Request $req)
    {
        $this->useValidator($req, [
            'info_id'=>[0,1,102],
        ]);
        $data = Info::find($req->info_id);
        if (false == $data) {
            return $this->returnJson('data does not exist');
        }
        return $data->delete() ? $this->returnJson('success', ['info_id'=>$data->id]) : $this->returnJson('data save failed');
    }
}
