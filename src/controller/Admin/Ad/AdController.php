<?php

namespace App\Http\Controllers\Admin\Ad;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Ad;
use App\Models\AdType;
use DB;

class AdController extends Controller
{
    /**
     * showdoc
     * @catalog 其他管理/开屏广告图管理
     * @title 添加
     * @description 添加广告图的接口
     * @method post
     * @url admin/ad/add
     * @param token 必选 string 标识
     * @param name 必选 string 广告图名称
     * @param pic 必选 string 图片地址
     * @param show[] 必选 array 展示位置1安卓,2ios,3小程序,4pc,5h5,6ipad
     * @param sort 非必选 int 排序值
     * @param type 必选 int 1跳转外链,0不跳转
     * @param url 必选 string 外链地址
     * @return {"error_code":0,"error_msg":"\u6210\u529f","data":{"ad_id":1}}
     * @return_param error_code int 错误码
     * @return_param error_msg string 错误提示
     * @return_param -data object 返回数据
     * @return_param ad_id int 广告图id
     * @remark
     * @number 1
     */
    public function add(Request $req)
    {
        $this->useValidator($req, [
            'name'=>[0,1,101,202],
            'pic'=>[0,1,101,255],
            'show'=>[0,1,104],
            'show.*'=>[0,1,102,416],
            'sort'=>[0,3,102,259],
            'type'=>[0,1,100],
        ]);
        $data = new Ad();
        if (1 == $req->type) {
            $this->useValidator($req, [
                'url'=>[0,1,101,255]
            ]);
            $data->url = $req->url;
        }
        if (Ad::where('name', $req->name)->count()) {
            return $this->returnJson('duplicate data name');
        }
        $data->name = $req->name;
        $data->pic = $req->pic;
        $data->type = $req->type;
        $data->sort = $req->sort ?? 0;
        try {
            return DB::transaction(function () use ($req,$data) {
                if (false == $data->save()) {
                    throw new \Exception('data save failed');
                }
                $list = [];
                $time = date('Y-m-d H:i:s');
                foreach ($req->show as $key => $value) {
                    array_push($list, ['ad_id'=>$data->id,'type'=>$value,'created_at'=>$time,'updated_at'=>$time]);
                }
                AdType::insert($list);
                return $this->returnJson('success', ['ad_id'=>$data->id]);
            });
        } catch (\Exception $e) {
            return $this->returnJson($e->getMessage());
        }
    }

    /**
     * showdoc
     * @catalog 其他管理/开屏广告图管理
     * @title 修改
     * @description 修改广告图的接口
     * @method post
     * @url admin/ad/update
     * @param token 必选 string 标识
     * @param ad_id 必选 int 广告图id
     * @param name 必选 string 广告图名称
     * @param pic 必选 string 图片地址
     * @param show[] 必选 array 展示位置1安卓,2ios,3小程序,4pc,5h5,6ipad
     * @param sort 非必选 int 排序值
     * @param type 必选 int 1跳转外链,0不跳转
     * @param url 必选 string 外链地址
     * @return {"error_code":0,"error_msg":"\u6210\u529f","data":{"ad_id":1}}
     * @return_param error_code int 错误码
     * @return_param error_msg string 错误提示
     * @return_param -data object 返回数据
     * @return_param ad_id int 广告图id
     * @remark
     * @number 2
     */
    public function update(Request $req)
    {
        $this->useValidator($req, [
            'ad_id'=>[0,1,102],
            'name'=>[0,1,101,202],
            'pic'=>[0,1,101,255],
            'show'=>[0,1,104],
            'show.*'=>[0,1,102,416],
            'sort'=>[0,3,102,259],
            'type'=>[0,1,100],
        ]);
        $data = Ad::find($req->ad_id);
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
        if (Ad::where('name', $req->name)->where('id', '!=', $req->ad_id)->count()) {
            return $this->returnJson('duplicate data name');
        }
        $data->name = $req->name;
        $data->pic = $req->pic;
        $data->type = $req->type;
        $data->sort = $req->sort ?? 0;
        try {
            return DB::transaction(function () use ($req,$data) {
                if (false == $data->save()) {
                    throw new \Exception('data save failed');
                }
                AdType::where('ad_id', $req->ad_id)->delete();
                $list = [];
                $time = date('Y-m-d H:i:s');
                foreach ($req->show as $key => $value) {
                    array_push($list, ['ad_id'=>$data->id,'type'=>$value,'created_at'=>$time,'updated_at'=>$time]);
                }
                AdType::insert($list);
                return $this->returnJson('success', ['ad_id'=>$data->id]);
            });
        } catch (\Exception $e) {
            return $this->returnJson($e->getMessage());
        }
    }

    /**
     * showdoc
     * @catalog 其他管理/开屏广告图管理
     * @title 列表
     * @description 广告图列表的接口
     * @method post
     * @url admin/ad/list
     * @param token 必选 string 标识
     * @param page 必选 int 页数
     * @param pagesize 必选 int 每页条数
     * @return {"error_code":0,"error_msg":"\u6210\u529f","data":{"data":[{"id":3,"name":"\u60ca\u5947\u5e7f\u544a\u56fe","created_at":"2018-12-03 18:18:18","sort":0,"view":0,"status":1}],"current_page":1,"total_page":3,"count":3}}
     * @return_param error_code int 错误码
     * @return_param error_msg string 错误提示
     * @return_param -data object 返回数据
     * @return_param --data object 返回信息
     * @return_param id int 广告图id
     * @return_param name string 广告图名称
     * @return_param created_at string 创建时间
     * @return_param sort int 排序值
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
        $data = new Ad();
        $count = $data->count();
        $data = $data->select('id', 'name', 'created_at', 'sort', 'view', 'status')
            ->orderBy('sort', 'desc')
            ->orderBy('id', 'desc')
            ->offset(($req->page-1)*$req->pagesize)
            ->limit($req->pagesize)
            ->get();
        return $this->returnJson('success', ['data'=>$data,'current_page'=>(int)$req->page,'total_page'=>ceil($count/$req->pagesize),'count'=>$count]);
    }

    /**
     * showdoc
     * @catalog 其他管理/开屏广告图管理
     * @title 列表
     * @description 广告图列表的接口
     * @method post
     * @url admin/ad/keyword
     * @param token 必选 string 标识
     * @param page 必选 int 页数
     * @param pagesize 必选 int 每页条数
     * @param type 必选 int 1id,2名称
     * @return {"error_code":0,"error_msg":"\u6210\u529f","data":{"data":[{"id":1,"name":"\u795e\u5947\u5e7f\u544a\u56fe","created_at":"2018-12-03 18:11:05","sort":0,"view":0,"status":1}],"current_page":1,"total_page":1,"count":1}}
     * @return_param error_code int 错误码
     * @return_param error_msg string 错误提示
     * @return_param -data object 返回数据
     * @return_param --data object 返回信息
     * @return_param id int 广告图id
     * @return_param name string 广告图名称
     * @return_param created_at string 创建时间
     * @return_param sort int 排序值
     * @return_param view int 展示次数
     * @return_param status int 1正常,0冻结
     * @return_param current_page int 当前页
     * @return_param total_page int 总页数
     * @return_param count int 总条数
     * @remark
     * @number 4
     */
    public function keyword(Request $req)
    {
        $this->useValidator($req, [
            'page'=>[0,1,102],
            'pagesize'=>[0,1,102],
            'type'=>[0,1,102,412],
            'keyword'=>[0,1,101]
        ]);
        $data = Ad::when(1 == $req->type, function ($query) use ($req) {
            return $query->where('id', 'like', "%$req->keyword%");
        })->when(2 == $req->type, function ($query) use ($req) {
            return $query->where('name', 'like', "%$req->keyword%");
        });
        $count = $data->count();
        $data = $data->select('id', 'name', 'created_at', 'sort', 'view', 'status')
            ->orderBy('sort', 'desc')
            ->orderBy('id', 'desc')
            ->offset(($req->page-1)*$req->pagesize)
            ->limit($req->pagesize)
            ->get();
        return $this->returnJson('success', ['data'=>$data,'current_page'=>(int)$req->page,'total_page'=>ceil($count/$req->pagesize),'count'=>$count]);
    }

    /**
     * showdoc
     * @catalog 其他管理/开屏广告图管理
     * @title 详情
     * @description 广告图详情的接口
     * @method post
     * @url admin/ad/detail
     * @param token 必选 string 标识
     * @param ad_id 必选 int 广告图id
     * @return {"error_code":0,"error_msg":"\u6210\u529f","data":{"id":1,"name":"\u795e\u5947\u5e7f\u544a\u56fe","pic":"https:\/\/publish-pic-cpu.baidu.com\/5b6c0b4c-515f-4334-8c6b-465b82e9ca8f.jpeg@q_90,w_450","sort":0,"type":0,"url":"","status":1,"view":0,"created_at":"2018-12-03 18:11:05","updated_at":"2018-12-03 18:11:05","get_type":[{"ad_id":1,"type":1},{"ad_id":1,"type":2}]}}
     * @return_param error_code int 错误码
     * @return_param error_msg string 错误提示
     * @return_param -data object 返回数据
     * @return_param --data object 返回信息
     * @return_param id int 广告图id
     * @return_param name string 广告图名称
     * @return_param pic string 广告图地址
     * @return_param sort int 排序值
     * @return_param type int 1跳转外链,0不跳转
     * @return_param url string 外链地址
     * @return_param status int 1正常,0冻结
     * @return_param view int 展示次数
     * @return_param created_at string 创建时间
     * @return_param updated_at string 修改时间
     * @return_param --get_type object 展示类型
     * @return_param ad_id int 广告图id
     * @return_param type int 1安卓,2ios,3小程序,4pc,5h5,6ipad
     * @remark
     * @number 5
     */
    public function detail(Request $req)
    {
        $this->useValidator($req, [
            'ad_id'=>[0,1,102]
        ]);
        $data = Ad::searchType()->find($req->ad_id);
        return $data ? $this->returnJson('success', $data) : $this->returnJson('data does not exist');
    }

    /**
     * showdoc
     * @catalog 其他管理/开屏广告图管理
     * @title 排序
     * @description 广告图排序的接口
     * @method post
     * @url admin/ad/sort
     * @param token 必选 string 标识
     * @param ad_id 必选 int 广告图id
     * @param sort 必选 int 排序值
     * @return {"error_code":0,"error_msg":"\u6210\u529f","data":{"ad_id":1}}
     * @return_param error_code int 错误码
     * @return_param error_msg string 错误提示
     * @return_param -data object 返回数据
     * @return_param ad_id int 广告图id
     * @remark
     * @number 6
     */
    public function sort(Request $req)
    {
        $this->useValidator($req, [
            'ad_id'=>[0,1,102],
            'sort'=>[0,1,102,259]
        ]);
        $data = Ad::select('id', 'sort')->find($req->ad_id);
        if (false == $data) {
            return $this->returnJson('data does not exist');
        }
        $data->sort = $req->sort;
        return $data->save() ? $this->returnJson('success', ['ad_id'=>$data->id]) : $this->returnJson('data save failed');
    }

    /**
     * showdoc
     * @catalog 其他管理/开屏广告图管理
     * @title 冻结
     * @description 广告图冻结的接口
     * @method post
     * @url admin/ad/freeze
     * @param token 必选 string 标识
     * @param ad_id 必选 int 广告图id
     * @return {"error_code":0,"error_msg":"\u6210\u529f","data":{"ad_id":1}}
     * @return_param error_code int 错误码
     * @return_param error_msg string 错误提示
     * @return_param -data object 返回数据
     * @return_param ad_id int 广告图id
     * @remark
     * @number 7
     */
    public function freeze(Request $req)
    {
        $this->useValidator($req, [
            'ad_id'=>[0,1,102],
        ]);
        $data = Ad::select('id', 'status')->find($req->ad_id);
        if (false == $data) {
            return $this->returnJson('data does not exist');
        }
        if (0 == $data->status) {
            return $this->returnJson('data has been frozen');
        }
        $data->status = 0;
        return $data->save() ? $this->returnJson('success', ['ad_id'=>$data->id]) : $this->returnJson('data save failed');
    }

    /**
     * showdoc
     * @catalog 其他管理/开屏广告图管理
     * @title 解冻
     * @description 广告图解冻的接口
     * @method post
     * @url admin/ad/unfreeze
     * @param token 必选 string 标识
     * @param ad_id 必选 int 广告图id
     * @return {"error_code":0,"error_msg":"\u6210\u529f","data":{"ad_id":1}}
     * @return_param error_code int 错误码
     * @return_param error_msg string 错误提示
     * @return_param -data object 返回数据
     * @return_param ad_id int 广告图id
     * @remark
     * @number 8
     */
    public function unfreeze(Request $req)
    {
        $this->useValidator($req, [
            'ad_id'=>[0,1,102],
        ]);
        $data = Ad::select('id', 'status')->find($req->ad_id);
        if (false == $data) {
            return $this->returnJson('data does not exist');
        }
        if (1 == $data->status) {
            return $this->returnJson('data has been frozen');
        }
        $data->status = 1;
        return $data->save() ? $this->returnJson('success', ['ad_id'=>$data->id]) : $this->returnJson('data save failed');
    }

    /**
     * showdoc
     * @catalog 其他管理/开屏广告图管理
     * @title 删除
     * @description 广告图删除的接口
     * @method post
     * @url admin/ad/delete
     * @param token 必选 string 标识
     * @param ad_id 必选 int 广告图id
     * @return {"error_code":0,"error_msg":"\u6210\u529f","data":{"ad_id":1}}
     * @return_param error_code int 错误码
     * @return_param error_msg string 错误提示
     * @return_param -data object 返回数据
     * @return_param ad_id int 广告图id
     * @remark
     * @number 9
     */
    public function del(Request $req)
    {
        $this->useValidator($req, [
            'ad_id'=>[0,1,102],
        ]);
        $data = Ad::find($req->ad_id);
        if (false == $data) {
            return $this->returnJson('data does not exist');
        }
        return $data->delete() ? $this->returnJson('success', ['ad_id'=>$data->id]) : $this->returnJson('data save failed');
    }
}
