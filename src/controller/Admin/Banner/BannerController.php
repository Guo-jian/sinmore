<?php

namespace App\Http\Controllers\Admin\Banner;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\BannerType;
use DB;

class BannerController extends Controller
{
    /**
     * showdoc
     * @catalog 内容管理/banner管理
     * @title 添加
     * @description 添加banner的接口
     * @method post
     * @url admin/banner/add
     * @param token 必选 string 标识
     * @param name 必选 string banner名称
     * @param pic 必选 string 图片地址
     * @param hot 必选 int 推荐位
     * @param show[] 必选 array 展示位置1安卓,2ios,3小程序,4pc,5h5,6ipad
     * @param sort 非必选 int 排序值
     * @param type 必选 int 1跳转外链,0不跳转
     * @param url 必选 string 外链地址
     * @return {"error_code":0,"error_msg":"\u6210\u529f","data":{"banner_id":6}}
     * @return_param error_code int 错误码
     * @return_param error_msg string 错误提示
     * @return_param -data object 返回数据
     * @return_param banner_id int banner_id
     * @remark 不添加推荐位时,hot传0
     * @number 1
     */
    public function add(Request $req)
    {
        $this->useValidator($req, [
            'name'=>[0,1,101,202],
            'pic'=>[0,1,101,255],
            'hot'=>[0,3,102,255],
            'show'=>[0,1,104],
            'show.*'=>[0,1,102,416],
            'sort'=>[0,3,102,259],
            'type'=>[0,1,100],
        ]);
        $data = new Banner();
        if (1 == $req->type) {
            $this->useValidator($req, [
                'url'=>[0,1,101,255]
            ]);
            $data->url = $req->url;
        }
        if (Banner::where('name', $req->name)->count()) {
            return $this->returnJson('duplicate data name');
        }
        $data->name = $req->name;
        $data->pic = $req->pic;
        $data->hot = $req->hot ?? '';
        $data->sort = $req->sort ?? 0;
        $data->type = $req->type;
        try {
            return DB::transaction(function () use ($req,$data) {
                if (false == $data->save()) {
                    throw new \Exception('data save failed');
                }
                $list = [];
                $time = date('Y-m-d H:i:s');
                foreach ($req->show as $key => $value) {
                    array_push($list, ['banner_id'=>$data->id,'type'=>$value,'created_at'=>$time,'updated_at'=>$time]);
                }
                BannerType::insert($list);
                return $this->returnJson('success', ['banner_id'=>$data->id]);
            });
        } catch (\Exception $e) {
            return $this->returnJson($e->getMessage());
        }
    }

    /**
     * showdoc
     * @catalog 内容管理/banner管理
     * @title 修改
     * @description 修改banner的接口
     * @method post
     * @url admin/banner/update
     * @param token 必选 string 标识
     * @param banner_id 必选 int banner_id
     * @param name 必选 string banner名称
     * @param pic 必选 string 图片地址
     * @param hot 必选 int 推荐位
     * @param show[] 必选 array 展示位置1安卓,2ios,3小程序,4pc,5h5,6ipad
     * @param sort 非必选 int 排序值
     * @param type 必选 int 1跳转外链,0不跳转
     * @param url 必选 string 外链地址
     * @return {"error_code":0,"error_msg":"\u6210\u529f","data":{"banner_id":6}}
     * @return_param error_code int 错误码
     * @return_param error_msg string 错误提示
     * @return_param -data object 返回数据
     * @return_param banner_id int banner_id
     * @remark 不添加推荐位时,hot传0
     * @number 2
     */
    public function update(Request $req)
    {
        $this->useValidator($req, [
            'banner_id'=>[0,1,102],
            'name'=>[0,1,101,202],
            'pic'=>[0,1,101,255],
            'hot'=>[0,3,102,255],
            'show'=>[0,1,104],
            'show.*'=>[0,1,102,416],
            'sort'=>[0,3,102,259],
            'type'=>[0,1,100],
        ]);
        $data = Banner::find($req->banner_id);
        if (false == $data) {
            return $this->returnJson('data does not exist');
        }
        if (1 == $req->type) {
            $this->useValidator($req, [
                'url'=>[0,1,101,255]
            ]);
            $data->url = $req->url;
        } else {
            $data->url = '';
        }
        if (Banner::where('name', $req->name)->where('id', '!=', $req->banner_id)->count()) {
            return $this->returnJson('duplicate data name');
        }
        $data->name = $req->name;
        $data->pic = $req->pic;
        $data->hot = $req->hot ?? '';
        $data->sort = $req->sort ?? 0;
        $data->type = $req->type;
        try {
            return DB::transaction(function () use ($req,$data) {
                if (false == $data->save()) {
                    throw new \Exception('data save failed');
                }
                BannerType::where('banner_id', $req->banner_id)->delete();
                $list = [];
                $time = date('Y-m-d H:i:s');
                foreach ($req->show as $key => $value) {
                    array_push($list, ['banner_id'=>$data->id,'type'=>$value,'created_at'=>$time,'updated_at'=>$time]);
                }
                BannerType::insert($list);
                return $this->returnJson('success', ['banner_id'=>$data->id]);
            });
        } catch (\Exception $e) {
            return $this->returnJson($e->getMessage());
        }
    }

    /**
     * showdoc
     * @catalog 内容管理/banner管理
     * @title 列表
     * @description banner列表的接口
     * @method post
     * @url admin/banner/list
     * @param token 必选 string 标识
     * @param page 必选 int 页数
     * @param pagesize 必选 int 每页条数
     * @return {"error_code":0,"error_msg":"\u6210\u529f","data":{"data":[{"id":2,"created_at":"2018-11-29 20:06:16","name":"\u7b2c\u4e8c\u4e2a","sort":0,"hot":0,"view":0,"status":1}],"current_page":1,"total_page":2,"count":2}}
     * @return_param error_code int 错误码
     * @return_param error_msg string 错误提示
     * @return_param -data object 返回数据
     * @return_param --data object 返回信息
     * @return_param id int banner_id
     * @return_param created_at string 创建时间
     * @return_param name string banner名称
     * @return_param sort int 排序值
     * @return_param hot int 推荐位
     * @return_param view int 展示次数
     * @return_param status int 1正常,0冻结
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
        $data = new Banner();
        $count = $data->count();
        $data = $data->select('id', 'created_at', 'name', 'sort', 'hot', 'view', 'status')
            ->orderBy('sort', 'desc')
            ->orderBy('id', 'desc')
            ->offset(($req->page-1)*$req->pagesize)
            ->limit($req->pagesize)
            ->get();
        return $this->returnJson('success', ['data'=>$data,'current_page'=>(int)$req->page,'total_page'=>ceil($count/$req->pagesize),'count'=>$count]);
    }

    /**
     * showdoc
     * @catalog 内容管理/banner管理
     * @title 检索
     * @description banner检索的接口
     * @method post
     * @url admin/banner/search
     * @param token 必选 string 标识
     * @param page 必选 int 页数
     * @param pagesize 必选 int 每页条数
     * @return {"error_code":0,"error_msg":"\u6210\u529f","data":{"data":[{"id":2,"created_at":"2018-11-29 20:06:16","name":"\u7b2c\u4e8c\u4e2a","sort":0,"hot":0,"view":0,"status":1}],"current_page":1,"total_page":2,"count":2}}
     * @return_param error_code int 错误码
     * @return_param error_msg string 错误提示
     * @return_param -data object 返回数据
     * @return_param --data object 返回信息
     * @return_param id int banner_id
     * @return_param created_at string 创建时间
     * @return_param name string banner名称
     * @return_param sort int 排序值
     * @return_param hot int 推荐位
     * @return_param view int 展示次数
     * @return_param status int 1正常,0冻结
     * @return_param current_page int 当前页
     * @return_param total_page int 总页数
     * @return_param count int 总条数
     * @remark
     * @number 4
     */
    public function search(Request $req)
    {
        $this->useValidator($req, [
            'hot'=>[0,1,102],
            'page'=>[0,1,102],
            'pagesize'=>[0,1,102]
        ]);
        $data = Banner::where('hot', $req->hot);
        $count = $data->count();
        $data = $data->select('id', 'created_at', 'name', 'sort', 'hot', 'view', 'status')
            ->orderBy('sort', 'desc')
            ->orderBy('id', 'desc')
            ->offset(($req->page-1)*$req->pagesize)
            ->limit($req->pagesize)
            ->get();
        return $this->returnJson('success', ['data'=>$data,'current_page'=>(int)$req->page,'total_page'=>ceil($count/$req->pagesize),'count'=>$count]);
    }

    /**
     * showdoc
     * @catalog 内容管理/banner管理
     * @title 搜索
     * @description banner搜索的接口
     * @method post
     * @url admin/banner/keyword
     * @param token 必选 string 标识
     * @param page 必选 int 页数
     * @param pagesize 必选 int 每页条数
     * @return {"error_code":0,"error_msg":"\u6210\u529f","data":{"data":[{"id":2,"created_at":"2018-11-29 20:06:16","name":"\u7b2c\u4e8c\u4e2a","sort":0,"hot":0,"view":0,"status":1}],"current_page":1,"total_page":2,"count":2}}
     * @return_param error_code int 错误码
     * @return_param error_msg string 错误提示
     * @return_param -data object 返回数据
     * @return_param --data object 返回信息
     * @return_param id int banner_id
     * @return_param created_at string 创建时间
     * @return_param name string banner名称
     * @return_param sort int 排序值
     * @return_param hot int 推荐位
     * @return_param view int 展示次数
     * @return_param status int 1正常,0冻结
     * @return_param current_page int 当前页
     * @return_param total_page int 总页数
     * @return_param count int 总条数
     * @remark
     * @number 5
     */
    public function keyword(Request $req)
    {
        $this->useValidator($req, [
            'type'=>[0,1,102,412],
            'keyword'=>[0,1,101],
            'page'=>[0,1,102],
            'pagesize'=>[0,1,102]
        ]);
        $data = Banner::where(function ($query) use ($req) {
            if (1 == $req->type) {
                return $query->where('id', 'like', "%$req->keyword%");
            } else {
                return $query->where('name', 'like', "%$req->keyword%");
            }
        });
        $count = $data->count();
        $data = $data->select('id', 'created_at', 'name', 'sort', 'hot', 'view', 'status')
            ->orderBy('sort', 'desc')
            ->orderBy('id', 'desc')
            ->offset(($req->page-1)*$req->pagesize)
            ->limit($req->pagesize)
            ->get();
        return $this->returnJson('success', ['data'=>$data,'current_page'=>(int)$req->page,'total_page'=>ceil($count/$req->pagesize),'count'=>$count]);
    }

    /**
     * showdoc
     * @catalog 内容管理/banner管理
     * @title 详情
     * @description banner详情的接口
     * @method post
     * @url admin/banner/detail
     * @param token 必选 string 标识
     * @param banner_id 必选 int banner_id
     * @return {"error_code":0,"error_msg":"\u6210\u529f","data":{"id":1,"name":"7","pic":"https:\/\/iocaffcdn.phphub.org\/uploads\/avatars\/1_1530614766.png!\/both\/200x200","hot":1,"sort":0,"type":0,"url":"","status":1,"view":0,"created_at":"2018-11-29 20:05:11","updated_at":"2018-12-03 10:37:48","get_type":[{"banner_id":1,"type":1},{"banner_id":1,"type":2}]}}
     * @return_param error_code int 错误码
     * @return_param error_msg string 错误提示
     * @return_param -data object 返回数据
     * @return_param id int banner_id
     * @return_param name string banner名称
     * @return_param pic string 图片地址
     * @return_param hot int 推荐位
     * @return_param sort int 排序值
     * @return_param type int 1跳转外链,0不跳转
     * @return_param url string 外链地址
     * @return_param status int 1正常,0冻结
     * @return_param view int 展示次数
     * @return_param created_at string 创建时间
     * @return_param updated_at string 修改时间
     * @return_param --get_type object 展示类型
     * @return_param banner_id int banner_id
     * @return_param type int 1安卓,2ios,3小程序,4pc,5h5,6ipad
     * @remark
     * @number 6
     */
    public function detail(Request $req)
    {
        $this->useValidator($req, [
            'banner_id'=>[0,1,102],
        ]);
        $data = Banner::searchType()->find($req->banner_id);
        return $data ? $this->returnJson('success', $data) : $this->returnJson('data does not exist');
    }

    /**
     * showdoc
     * @catalog 内容管理/banner管理
     * @title 排序
     * @description banner排序的接口
     * @method post
     * @url admin/banner/sort
     * @param token 必选 string 标识
     * @param banner_id 必选 int banner_id
     * @param sort 必选 int 排序值
     * @return {"error_code":0,"error_msg":"\u6210\u529f","data":{"banner_id":1}}
     * @return_param error_code int 错误码
     * @return_param error_msg string 错误提示
     * @return_param -data object 返回数据
     * @return_param banner_id int banner_id
     * @remark
     * @number 7
     */
    public function sort(Request $req)
    {
        $this->useValidator($req, [
            'banner_id'=>[0,1,102],
            'sort'=>[0,1,102,259]
        ]);
        $data = Banner::select('id', 'sort')->find($req->banner_id);
        if (false == $data) {
            return $this->returnJson('data does not exist');
        }
        $data->sort = $req->sort;
        return $data->save() ? $this->returnJson('success', ['banner_id'=>$data->id]) : $this->returnJson('data save failed');
    }

    /**
     * showdoc
     * @catalog 内容管理/banner管理
     * @title 推荐位
     * @description banner推荐位的接口
     * @method post
     * @url admin/banner/hot
     * @param token 必选 string 标识
     * @param banner_id 必选 int banner_id
     * @param hot 必选 int 推荐位
     * @return {"error_code":0,"error_msg":"\u6210\u529f","data":{"banner_id":1}}
     * @return_param error_code int 错误码
     * @return_param error_msg string 错误提示
     * @return_param -data object 返回数据
     * @return_param banner_id int banner_id
     * @remark
     * @number 8
     */
    public function hot(Request $req)
    {
        $this->useValidator($req, [
            'banner_id'=>[0,1,102],
            'hot'=>[0,1,102,255]
        ]);
        $data = Banner::select('id', 'hot')->find($req->banner_id);
        if (false == $data) {
            return $this->returnJson('data does not exist');
        }
        $data->hot = $req->hot;
        return $data->save() ? $this->returnJson('success', ['banner_id'=>$data->id]) : $this->returnJson('data save failed');
    }

    /**
     * showdoc
     * @catalog 内容管理/banner管理
     * @title 冻结
     * @description banner冻结的接口
     * @method post
     * @url admin/banner/freeze
     * @param token 必选 string 标识
     * @param banner_id 必选 int banner_id
     * @return {"error_code":0,"error_msg":"\u6210\u529f","data":{"banner_id":1}}
     * @return_param error_code int 错误码
     * @return_param error_msg string 错误提示
     * @return_param -data object 返回数据
     * @return_param banner_id int banner_id
     * @remark
     * @number 9
     */
    public function freeze(Request $req)
    {
        $this->useValidator($req, [
            'banner_id'=>[0,1,102],
        ]);
        $data = Banner::select('id', 'status')->find($req->banner_id);
        if (false == $data) {
            return $this->returnJson('data does not exist');
        }
        if (0 == $data->status) {
            return $this->returnJson('data has been frozen');
        }
        $data->status = 0;
        return $data->save() ? $this->returnJson('success', ['banner_id'=>$data->id]) : $this->returnJson('data save failed');
    }

    /**
     * showdoc
     * @catalog 内容管理/banner管理
     * @title 解冻
     * @description banner解冻的接口
     * @method post
     * @url admin/banner/unfreeze
     * @param token 必选 string 标识
     * @param banner_id 必选 int banner_id
     * @return {"error_code":0,"error_msg":"\u6210\u529f","data":{"banner_id":1}}
     * @return_param error_code int 错误码
     * @return_param error_msg string 错误提示
     * @return_param -data object 返回数据
     * @return_param banner_id int banner_id
     * @remark
     * @number 10
     */
    public function unfreeze(Request $req)
    {
        $this->useValidator($req, [
            'banner_id'=>[0,1,102],
        ]);
        $data = Banner::select('id', 'status')->find($req->banner_id);
        if (false == $data) {
            return $this->returnJson('data does not exist');
        }
        if (1 == $data->status) {
            return $this->returnJson('data has been frozen');
        }
        $data->status = 1;
        return $data->save() ? $this->returnJson('success', ['banner_id'=>$data->id]) : $this->returnJson('data save failed');
    }

    /**
     * showdoc
     * @catalog 内容管理/banner管理
     * @title 删除
     * @description banner删除的接口
     * @method post
     * @url admin/banner/delete
     * @param token 必选 string 标识
     * @param banner_id 必选 int banner_id
     * @return {"error_code":0,"error_msg":"\u6210\u529f","data":{"banner_id":3}}
     * @return_param error_code int 错误码
     * @return_param error_msg string 错误提示
     * @return_param -data object 返回数据
     * @return_param banner_id int banner_id
     * @remark
     * @number 11
     */
    public function del(Request $req)
    {
        $this->useValidator($req, [
            'banner_id'=>[0,1,102],
        ]);
        $data = Banner::find($req->banner_id);
        if (false == $data) {
            return $this->returnJson('data does not exist');
        }
        return $data->delete() ? $this->returnJson('success', ['banner_id'=>$data->id]) : $this->returnJson('data save failed');
    }
}
