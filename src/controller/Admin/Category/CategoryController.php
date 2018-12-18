<?php

namespace App\Http\Controllers\Admin\Category;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Category;
use DB;

class CategoryController extends Controller
{
    /**
     * showdoc
     * @catalog 内容管理/资讯分类
     * @title 添加
     * @description 添加分类的接口
     * @method post
     * @url admin/category/add
     * @param token 必选 string 标识
     * @param pid 必选 int 上级id
     * @param name 必选 string 分类名称
     * @param sort 非必选 int 排序
     * @param thumb 非必选 string 缩略图
     * @param pic 非必选 string 推荐图
     * @param hot 必选 int 推荐位
     * @return {"error_code":0,"error_msg":"\u6210\u529f","data":{"category_id":6}}
     * @return_param error_code int 错误码
     * @return_param error_msg string 错误提示
     * @return_param -data object 返回数据
     * @return_param category_id int 分类id
     * @remark 添加一级分类时,pid传0 不添加推荐位时,hot传0
     * @number 1
     */
    public function add(Request $req)
    {
        $this->useValidator($req, [
            'pid'=>[0,1,102],
            'name'=>[0,1,101,215],
            'sort'=>[0,3,102,259],
            'thumb'=>[0,3,101,255],
            'pic'=>[0,3,101,255],
            'hot'=>[0,1,102,255],
        ]);
        if (Category::where('name', $req->name)->count()) {
            return $this->returnJson('duplicate data name');
        }
        $data = new Category();
        if (0 == $req->pid) {
            $data->pid = $req->pid;
            $data->level = 1;
        } else {
            $pid = Category::where('id', $req->pid)->select('id', 'pid')->first();
            if (false == $pid) {
                return $this->returnJson('data does not exist');
            }
            if (0 == $pid->pid) {
                $data->level = 2;
            } else {
                $gpid = Category::where('id', $pid->pid)->select('id', 'pid')->first();
                if (false == $gpid) {
                    return $this->returnJson('data does not exist');
                }
                if (0 != $gpid->pid) {
                    return $this->returnJson('category up to three levels');
                }
                $data->level = 3;
            }
            $data->pid = $pid->id;
        }
        $data->name = $req->name;
        $data->sort = $req->sort ?? 0;
        $data->thumb = $req->thumb ?? '';
        $data->pic = $req->pic ?? '';
        $data->hot = $req->hot;
        return $data->save() ? $this->returnJson('success', ['category_id'=>$data->id]) : $this->returnJson('data save failed');
    }

    /**
     * showdoc
     * @catalog 内容管理/资讯分类
     * @title 修改
     * @description 修改分类的接口
     * @method post
     * @url admin/category/update
     * @param token 必选 string 标识
     * @param category_id 必选 int 分类id
     * @param pid 必选 int 上级id
     * @param name 必选 string 分类名称
     * @param sort 非必选 int 排序
     * @param thumb 非必选 string 缩略图
     * @param pic 非必选 string 推荐图
     * @param hot 必选 int 推荐位
     * @return {"error_code":0,"error_msg":"\u6210\u529f","data":{"category_id":6}}
     * @return_param error_code int 错误码
     * @return_param error_msg string 错误提示
     * @return_param -data object 返回数据
     * @return_param category_id int 分类id
     * @remark 添加一级分类时,pid传0 不添加推荐位时,hot传0 无法修改分类层级
     * @number 2
     */
    public function update(Request $req)
    {
        $this->useValidator($req, [
            'category_id'=>[0,1,102],
            'pid'=>[0,1,102],
            'name'=>[0,1,101,215],
            'sort'=>[0,3,102,259],
            'thumb'=>[0,3,101,255],
            'pic'=>[0,3,101,255],
            'hot'=>[0,1,102,255],
        ]);
        if (Category::where('name', $req->name)->where('id', '!=', $req->category_id)->count()) {
            return $this->returnJson('duplicate data name');
        }
        $data = Category::find($req->category_id);
        if (false == $data) {
            return $this->returnJson('data does not exist');
        }
        if ($req->pid != $data->pid) {
            $pid = Category::where('id', $req->pid)->select('id', 'pid', 'level')->first();
            if (false == $pid) {
                return $this->returnJson('data does not exist');
            }
            if ($pid->level+1 != $data->level) {
                return $this->returnJson('level can not be modified');
            }
        }
        $data->pid = $req->pid;
        $data->name = $req->name;
        $data->sort = $req->sort ?? 0;
        $data->thumb = $req->thumb ?? '';
        $data->pic = $req->pic ?? '';
        $data->hot = $req->hot;
        return $data->save() ? $this->returnJson('success', ['category_id'=>$data->id]) : $this->returnJson('data save failed');
    }

    /**
     * showdoc
     * @catalog 内容管理/资讯分类
     * @title 列表
     * @description 分类列表的接口
     * @method post
     * @url admin/category/list
     * @param token 必选 string 标识
     * @return {"error_code":0,"error_msg":"\u6210\u529f","data":[{"id":1,"name":"\u4e00\u7ea7\u5206\u7c7b1","sort":0,"hot":0,"status":1,"pid":0,"get_category":[{"id":5,"name":"\u4e8c\u7ea7\u5206\u7c7b2","sort":0,"hot":0,"status":1,"pid":1,"get_category":[{"id":3,"name":"\u4e09\u7ea7\u5206\u7c7b1","sort":0,"hot":0,"status":1,"pid":5}]}]},{"id":4,"name":"\u4e00\u7ea7\u5206\u7c7b2","sort":0,"hot":0,"status":1,"pid":0,"get_category":[{"id":2,"name":"\u4e8c\u7ea7\u5206\u7c7b1","sort":0,"hot":0,"status":1,"pid":4,"get_category":[{"id":6,"name":"\u4e09\u7ea7\u5206\u7c7b2","sort":0,"hot":0,"status":1,"pid":2}]}]}]}
     * @return_param error_code int 错误码
     * @return_param error_msg string 错误提示
     * @return_param -data object 返回数据
     * @return_param id int 分类id
     * @return_param name string 分类名称
     * @return_param sort int 排序
     * @return_param hot int 推荐位
     * @return_param status int 1正常,0冻结
     * @return_param pid int 上级id
     * @return_param --get_category object 二级分类
     * @return_param id int 分类id
     * @return_param name string 分类名称
     * @return_param sort int 排序
     * @return_param hot int 推荐位
     * @return_param status int 1正常,0冻结
     * @return_param pid int 上级id
     * @return_param ---get_category object 三级分类
     * @return_param id int 分类id
     * @return_param name string 分类名称
     * @return_param sort int 排序
     * @return_param hot int 推荐位
     * @return_param status int 1正常,0冻结
     * @return_param pid int 上级id
     * @return_param current_page int 当前页
     * @return_param total_page int 总页数
     * @return_param count int 总条数
     * @remark
     * @number 3
     */
    public function list(Request $req)
    {
        $data = Category::select('id', 'name', 'sort', 'hot', 'status', 'pid')
            ->searchCategory(2)
            ->where('pid', 0)
            ->orderBy('sort', 'desc')
            ->orderBy('id', 'desc')
            ->get();
        return $this->returnJson('success', $data);
    }

    /**
     * showdoc
     * @catalog 内容管理/资讯分类
     * @title 搜索
     * @description 分类搜索的接口
     * @method post
     * @url admin/category/search
     * @param token 必选 string 标识
     * @param category_id 必选 int 分类id
     * @return {"error_code":0,"error_msg":"\u6210\u529f","data":[{"id":4,"name":"\u4e00\u7ea7\u5206\u7c7b2","sort":0,"hot":0,"status":1,"pid":0,"get_category":[{"id":2,"name":"\u4e8c\u7ea7\u5206\u7c7b1","sort":0,"hot":0,"status":1,"pid":4,"get_category":[{"id":6,"name":"\u4e09\u7ea7\u5206\u7c7b2","sort":0,"hot":0,"status":1,"pid":2}]}]}]}
     * @return_param error_code int 错误码
     * @return_param error_msg string 错误提示
     * @return_param -data object 返回数据
     * @return_param id int 分类id
     * @return_param name string 分类名称
     * @return_param sort int 排序
     * @return_param hot int 推荐位
     * @return_param status int 1正常,0冻结
     * @return_param pid int 上级id
     * @return_param --get_category object 二级分类
     * @return_param id int 分类id
     * @return_param name string 分类名称
     * @return_param sort int 排序
     * @return_param hot int 推荐位
     * @return_param status int 1正常,0冻结
     * @return_param pid int 上级id
     * @return_param ---get_category object 三级分类
     * @return_param id int 分类id
     * @return_param name string 分类名称
     * @return_param sort int 排序
     * @return_param hot int 推荐位
     * @return_param status int 1正常,0冻结
     * @return_param pid int 上级id
     * @return_param current_page int 当前页
     * @return_param total_page int 总页数
     * @return_param count int 总条数
     * @remark
     * @number 4
     */
    public function search(Request $req)
    {
        $this->useValidator($req, [
            'category_id'=>[0,1,102]
        ]);
        $data = Category::where('id', $req->category_id)
            ->select('id', 'name', 'sort', 'hot', 'status', 'pid')
            ->searchCategory(2)
            ->orderBy('sort', 'desc')
            ->orderBy('id', 'desc')
            ->get();
        return $this->returnJson('success', $data);
    }

    /**
     * showdoc
     * @catalog 内容管理/资讯分类
     * @title 详情
     * @description 分类详情的接口
     * @method post
     * @url admin/category/detail
     * @param token 必选 string 标识
     * @param category_id 必选 int 分类id
     * @return {"error_code":0,"error_msg":"\u6210\u529f","data":{"id":3,"pid":5,"name":"\u4e09\u7ea7\u5206\u7c7b1","sort":0,"thumb":"","pic":"","status":1,"hot":0,"level":3,"created_at":"2018-11-28 16:18:58","updated_at":"2018-11-28 16:50:49","parent":[{"id":5,"pid":1,"name":"\u4e8c\u7ea7\u5206\u7c7b2","get_parent":{"id":1,"pid":0,"name":"\u4e00\u7ea7\u5206\u7c7b1"}}]}}
     * @return_param error_code int 错误码
     * @return_param error_msg string 错误提示
     * @return_param -data object 返回数据
     * @return_param id int 分类id
     * @return_param pid int 上级id
     * @return_param name string 分类名称
     * @return_param sort int 排序
     * @return_param thumb string 缩略图
     * @return_param pic string 推荐图
     * @return_param status int 1正常,0冻结
     * @return_param hot int 推荐位
     * @return_param level int 层级
     * @return_param created_at string 创建时间
     * @return_param updated_at string 修改时间
     * @return_param --parent object 上级分类
     * @return_param id int 分类id
     * @return_param pid int 上级id
     * @return_param name string 分类名称
     * @return_param ---get_parent 一级分类
     * @return_param id int 分类id
     * @return_param pid int 上级id
     * @return_param name string 分类名称
     * @remark
     * @number 5
     */
    public function detail(Request $req)
    {
        $this->useValidator($req, [
            'category_id'=>[0,1,102]
        ]);
        $data = Category::find($req->category_id);
        if (false == $data) {
            return $this->returnJson('data does not exist');
        }
        if (0 == $data->pid) {
            $data->parent = [];
        } else {
            $data->parent = Category::where('id', $data->pid)->select('id', 'pid', 'name');
            $data->parent = 2 == $data->level ? $data->parent->get() : $data->parent->searchParent(2)->get();
        }
        return $this->returnJson('success', $data);
    }

    /**
     * showdoc
     * @catalog 内容管理/资讯分类
     * @title 排序
     * @description 分类排序的接口
     * @method post
     * @url admin/category/sort
     * @param token 必选 string 标识
     * @param category_id 必选 int 分类id
     * @param sort 必选 int 排序值
     * @return {"error_code":0,"error_msg":"\u6210\u529f","data":{"category_id":1}}
     * @return_param error_code int 错误码
     * @return_param error_msg string 错误提示
     * @return_param -data object 返回数据
     * @return_param category_id int 分类id
     * @remark
     * @number 6
     */
    public function sort(Request $req)
    {
        $this->useValidator($req, [
            'category_id'=>[0,1,102],
            'sort'=>[0,1,102,259]
        ]);
        $data = Category::select('id', 'sort')->find($req->category_id);
        if (false == $data) {
            return $this->returnJson('data does not exist');
        }
        $data->sort = $req->sort;
        return $data->save() ? $this->returnJson('success', ['category_id'=>$data->id]) : $this->returnJson('data save failed');
    }

    /**
     * showdoc
     * @catalog 内容管理/资讯分类
     * @title 冻结
     * @description 分类冻结的接口
     * @method post
     * @url admin/category/freeze
     * @param token 必选 string 标识
     * @param category_id 必选 int 分类id
     * @return {"error_code":0,"error_msg":"\u6210\u529f","data":{"category_id":1}}
     * @return_param error_code int 错误码
     * @return_param error_msg string 错误提示
     * @return_param -data object 返回数据
     * @return_param category_id int 分类id
     * @remark
     * @number 7
     */
    public function freeze(Request $req)
    {
        $this->useValidator($req, [
            'category_id'=>[0,1,102]
        ]);
        $data = Category::select('id', 'status', 'level')->find($req->category_id);
        if (false == $data) {
            return $this->returnJson('data does not exist');
        }
        if (0 == $data->status) {
            return $this->returnJson('data has been frozen');
        }
        try {
            return DB::transaction(function () use ($req,$data) {
                if (2 == $data->level) {
                    Category::where('pid', $data->id)->update(['status'=>0]);
                } elseif (1 == $data->level) {
                    $second = Category::where('pid', $data->id)->get(['id']);
                    if ($second->isNotEmpty()) {
                        $second = array_column($second->toArray(), 'id');
                        $third = Category::whereIn('pid', $second)->get(['id']);
                        $third = $third->isNotEmpty() ? array_column($third->toArray(), 'id') : [];
                        $id = array_merge($second, $third);
                        Category::whereIn('id', $id)->update(['status'=>0]);
                    }
                }
                $data->status = 0;
                return $data->save() ? $this->returnJson('success', ['category_id'=>$data->id]) : $this->returnJson('data save failed');
            });
        } catch (\Exception $e) {
            return $this->returnJson($e->getMessage());
        }
    }

    /**
     * showdoc
     * @catalog 内容管理/资讯分类
     * @title 解冻
     * @description 分类解冻的接口
     * @method post
     * @url admin/category/unfreeze
     * @param token 必选 string 标识
     * @param category_id 必选 int 分类id
     * @return {"error_code":0,"error_msg":"\u6210\u529f","data":{"category_id":1}}
     * @return_param error_code int 错误码
     * @return_param error_msg string 错误提示
     * @return_param -data object 返回数据
     * @return_param category_id int 分类id
     * @remark
     * @number 8
     */
    public function unfreeze(Request $req)
    {
        $this->useValidator($req, [
            'category_id'=>[0,1,102]
        ]);
        $data = Category::select('id', 'status', 'level')->find($req->category_id);
        if (false == $data) {
            return $this->returnJson('data does not exist');
        }
        if (1 == $data->status) {
            return $this->returnJson('data does not frozen');
        }
        try {
            return DB::transaction(function () use ($req,$data) {
                if (2 == $data->level) {
                    Category::where('pid', $data->id)->update(['status'=>1]);
                } elseif (1 == $data->level) {
                    $second = Category::where('pid', $data->id)->get(['id']);
                    if ($second->isNotEmpty()) {
                        $second = array_column($second->toArray(), 'id');
                        $third = Category::whereIn('pid', $second)->get(['id']);
                        $third = $third->isNotEmpty() ? array_column($third->toArray(), 'id') : [];
                        $id = array_merge($second, $third);
                        Category::whereIn('id', $id)->update(['status'=>1]);
                    }
                }
                $data->status = 1;
                return $data->save() ? $this->returnJson('success', ['category_id'=>$data->id]) : $this->returnJson('data save failed');
            });
        } catch (\Exception $e) {
            return $this->returnJson($e->getMessage());
        }
    }

    /**
     * showdoc
     * @catalog 内容管理/资讯分类
     * @title 推荐
     * @description 分类推荐的接口
     * @method post
     * @url admin/category/hot
     * @param token 必选 string 标识
     * @param category_id 必选 int 分类id
     * @return {"error_code":0,"error_msg":"\u6210\u529f","data":{"category_id":1}}
     * @return_param error_code int 错误码
     * @return_param error_msg string 错误提示
     * @return_param -data object 返回数据
     * @return_param category_id int 分类id
     * @remark
     * @number 9
     */
    public function hot(Request $req)
    {
        $this->useValidator($req, [
            'category_id'=>[0,1,102],
            'hot'=>[0,1,102,255],
        ]);
        $data = Category::select('id', 'hot')->find($req->category_id);
        if (false == $data) {
            return $this->returnJson('data does not exist');
        }
        $data->hot = $req->hot;
        return $data->save() ? $this->returnJson('success', ['category_id'=>$data->id]) : $this->returnJson('data save failed');
    }

    /**
     * showdoc
     * @catalog 内容管理/资讯分类
     * @title 删除
     * @description 分类删除的接口
     * @method post
     * @url admin/category/delete
     * @param token 必选 string 标识
     * @param category_id 必选 int 分类id
     * @return {"error_code":0,"error_msg":"\u6210\u529f","data":{"category_id":1}}
     * @return_param error_code int 错误码
     * @return_param error_msg string 错误提示
     * @return_param -data object 返回数据
     * @return_param category_id int 分类id
     * @remark
     * @number 10
     */
    public function del(Request $req)
    {
        $this->useValidator($req, [
            'category_id'=>[0,1,102]
        ]);
        $data = Category::find($req->category_id);
        if (false == $data) {
            return $this->returnJson('data does not exist');
        }
        if (Category::where('pid', $data->id)->count() || \App\Models\Info::where('category_id', $req->category_id)->count()) {
            return $this->returnJson('data cannot be deleted');
        }
        return $data->delete() ? $this->returnJson('success', ['category_id'=>$data->id]) : $this->returnJson('data save failed');
    }
}
