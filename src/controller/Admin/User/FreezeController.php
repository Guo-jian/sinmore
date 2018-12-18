<?php

namespace App\Http\Controllers\Admin\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserFreeze;
use DB;

class FreezeController extends Controller
{
    /**
     * showdoc
     * @catalog 会员系统
     * @title 冻结
     * @description 会员冻结的接口
     * @method post
     * @url admin/user/freeze
     * @param token 必选 string 标识
     * @param user_id 必选 int 用户id
     * @param type 必选 int 1永久冻结,0暂时冻结
     * @param remark 非必选 string 备注
     * @return {"error_code":0,"error_msg":"\u6210\u529f","data":{}}
     * @return_param error_code int 错误码
     * @return_param error_msg string 错误提示
     * @return_param -data object 返回数据
     * @remark
     * @number 5
     */
    public function freeze(Request $req)
    {
        $this->useValidator($req, [
            'user_id' => [0, 1, 102],
            'type' => [0, 1, 100],
            'remark'=>[0,3,101,250],
        ]);
        $data = User::select('id', 'status', 'froze_at', 'froze_days')->find($req->user_id);
        if (false == $data) {
            return $this->returnJson('data does not exist');
        }
        $freeze = new UserFreeze();
        if ($req->type) {
            $data->status = 2;
            $data->froze_days = 0;
            $freeze->status = 2;
        } else {
            $this->useValidator($req, [
                'days' => [0, 1, 102,201],
            ]);
            if (2 == $data->status) {
                return $this->returnJson('account has been frozen');
            }
            if (0 < $data->froze_days && 0 == $data->status) {
                $data->froze_days = $data->froze_days + $req->days - ceil((time() - strtotime($data->froze_at)) / 86400);
            } else {
                $data->froze_days += $req->days;
            }
            $data->status = 0;
            $freeze->status = 0;
            $freeze->days = $req->days;
        }
        $data->froze_at = date('Y-m-d H:i:s');
        $freeze->user_id = $req->user_id;
        $freeze->remark = $req->remark ?? '';
        $freeze->admin_id = $req->admin->id;
        try {
            return DB::transaction(function () use ($freeze,$data) {
                if (false == $data->save()) {
                    throw new \Exception('data save failed');
                }
                if (false == $freeze->save()) {
                    throw new \Exception('data save failed');
                }
                return $this->returnJson('success');
            });
        } catch (\Exception $e) {
            return $this->returnJson($e->getMessage());
        }
    }

    /**
     * showdoc
     * @catalog 会员系统
     * @title 解冻
     * @description 会员解冻的接口
     * @method post
     * @url admin/user/unfreeze
     * @param token 必选 string 标识
     * @param user_id 必选 int 用户id
     * @return {"error_code":0,"error_msg":"\u6210\u529f","data":{}}
     * @return_param error_code int 错误码
     * @return_param error_msg string 错误提示
     * @return_param -data object 返回数据
     * @remark
     * @number 6
     */
    public function unfreeze(Request $req)
    {
        $this->useValidator($req, [
            'user_id' => [0, 1, 102],
        ]);
        $data = User::select('id', 'status', 'froze_at', 'froze_days')->find($req->user_id);
        if (false == $data) {
            return $this->returnJson('data does not exist');
        }
        if (1 == $data->status) {
            return $this->returnJson('account not frozen');
        }
        $data->status = 1;
        $data->froze_days = 0;
        $data->froze_at = null;
        $freeze = new UserFreeze();
        $freeze->user_id = $req->user_id;
        $freeze->days = 0;
        $freeze->remark = '解冻';
        $freeze->admin_id = $req->admin->id;
        $freeze->status = 1;
        try {
            return DB::transaction(function () use ($freeze,$data) {
                if (false == $data->save()) {
                    throw new \Exception('data save failed');
                }
                if (false == $freeze->save()) {
                    throw new \Exception('data save failed');
                }
                return $this->returnJson('success');
            });
        } catch (\Exception $e) {
            return $this->returnJson($e->getMessage());
        }
    }

    /**
     * showdoc
     * @catalog 会员系统
     * @title 冻结历史
     * @description 会员冻结历史的接口
     * @method post
     * @url admin/user/freeze/list
     * @param token 必选 string 标识
     * @param user_id 必选 int 用户id
     * @return {"error_code":0,"error_msg":"\u6210\u529f","data":{"data":[{"created_at":"2018-12-14 15:49:16","admin_id":1,"days":0,"remark":"\u89e3\u51bb","status":1,"get_admin":{"id":1,"name":"admin"}}],"current_page":1,"total_page":4,"count":4}}
     * @return_param error_code int 错误码
     * @return_param error_msg string 错误提示
     * @return_param -data object 返回数据
     * @return_param --data object 返回信息
     * @return_param created_at string 冻结时间
     * @return_param admin_id int 管理员id
     * @return_param days int 冻结天数
     * @return_param remark string 备注
     * @return_param status int 状态:1正常,0暂时冻结,2永久冻结
     * @return_param ---get_admin object 管理员信息
     * @return_param id int 管理员id
     * @return_param name string 管理员名称
     * @return_param current_page int 当前页
     * @return_param total_page int 总页数
     * @return_param count int 总条数
     * @remark
     * @number 7
     */
    public function list(Request $req)
    {
        $this->useValidator($req, [
            'user_id' => [0, 1, 102, 202],
            'page' => [0, 1, 2, 102],
            'pagesize' => [0, 1, 102],
        ]);
        $data = UserFreeze::where('user_id', $req->user_id);
        $count = $data->count();
        $data = $data->select('created_at', 'admin_id', 'days', 'remark', 'status')
            ->orderBy('created_at', 'desc')
            ->searchAdmin()
            ->offset(($req->page-1)*$req->pagesize)
            ->limit($req->pagesize)
            ->get();
        return $this->returnJson('success', ['data'=>$data,'current_page'=>(int)$req->page,'total_page'=>ceil($count/$req->pagesize),'count'=>$count]);
    }

    /**
     * showdoc
     * @catalog 会员系统
     * @title 会员冻结状态
     * @description 会员冻结状态的接口
     * @method post
     * @url admin/user/freeze/detail
     * @param token 必选 string 标识
     * @param user_id 必选 int 用户id
     * @return {"error_code":0,"error_msg":"\u6210\u529f","data":{"id":7,"status":1,"froze_at":null,"froze_days":0}}
     * @return_param error_code int 错误码
     * @return_param error_msg string 错误提示
     * @return_param -data object 返回数据
     * @return_param id int 用户id
     * @return_param status int 状态:1正常,0暂时冻结,2永久冻结
     * @return_param froze_at string 冻结时间
     * @return_param froze_days int 冻结天数
     * @remark
     * @number 8
     */
    public function detail(Request $req)
    {
        $this->useValidator($req, [
            'user_id' => [0, 1, 102],
        ]);
        $data = User::select('id', 'status', 'froze_at', 'froze_days')->find($req->user_id);
        return $data ? $this->returnJson('success', $data) : $this->returnJson('data does not exist');
    }
}
