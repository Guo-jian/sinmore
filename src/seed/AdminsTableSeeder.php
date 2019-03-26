<?php

/*
 * This file is part of the mquery/sinmore.
 *
 * (c) guojian <n6878088@163.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

use Illuminate\Database\Seeder;

class AdminsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $time = date('Y-m-d H:i:s');
        DB::table('admins')->insert([
            [
                'id' => 1,
                'account' => 'admin',
                'name' => 'admin',
                'password' => md5(md5(1).env('APP_ATTACH')),
                'mobile' => 'admin',
                'token' => 1,
                'expired_at' => date('Y-m-d H:i:s', strtotime('+15 days')),
                'created_at' => $time,
                'updated_at' => $time,
            ], [
                'id' => 2,
                'account' => 'heshale',
                'name' => '何沙乐',
                'password' => md5(md5('admin001').env('APP_ATTACH')),
                'mobile' => '15810473391',
                'token' => '',
                'group' => 1,
                'expired_at' => date('Y-m-d H:i:s', strtotime('+15 days')),
                'created_at' => $time,
                'updated_at' => $time,
            ], [
                'id' => 3,
                'account' => 'hehangle',
                'name' => '何航乐',
                'password' => md5(md5('admin001').env('APP_ATTACH')),
                'mobile' => '13927499035',
                'token' => '',
                'group' => 1,
                'expired_at' => date('Y-m-d H:i:s', strtotime('+15 days')),
                'created_at' => $time,
                'updated_at' => $time,
            ], [
                'id' => 4,
                'account' => 'gonghaichuan',
                'name' => '巩海川',
                'password' => md5(md5('admin001').env('APP_ATTACH')),
                'mobile' => '13718273417',
                'token' => '',
                'group' => 1,
                'expired_at' => date('Y-m-d H:i:s', strtotime('+15 days')),
                'created_at' => $time,
                'updated_at' => $time,
            ], [
                'id' => 5,
                'account' => 'shizhengchuan',
                'name' => '时振川',
                'password' => md5(md5('admin001').env('APP_ATTACH')),
                'mobile' => '17600220747',
                'token' => '',
                'group' => 1,
                'expired_at' => date('Y-m-d H:i:s', strtotime('+15 days')),
                'created_at' => $time,
                'updated_at' => $time,
            ], [
                'id' => 6,
                'account' => 'wangyuhao',
                'name' => '王宇浩',
                'password' => md5(md5('admin001').env('APP_ATTACH')),
                'mobile' => '15613360720',
                'token' => '',
                'group' => 1,
                'expired_at' => date('Y-m-d H:i:s', strtotime('+15 days')),
                'created_at' => $time,
                'updated_at' => $time,
            ], [
                'id' => 7,
                'account' => 'guojian',
                'name' => '郭建',
                'password' => md5(md5('admin001').env('APP_ATTACH')),
                'mobile' => '15114580369',
                'token' => '',
                'group' => 1,
                'expired_at' => date('Y-m-d H:i:s', strtotime('+15 days')),
                'created_at' => $time,
                'updated_at' => $time,
            ], [
                'id' => 8,
                'account' => 'dujian',
                'name' => '杜建',
                'password' => md5(md5('admin001').env('APP_ATTACH')),
                'mobile' => '18210619665',
                'token' => '',
                'group' => 1,
                'expired_at' => date('Y-m-d H:i:s', strtotime('+15 days')),
                'created_at' => $time,
                'updated_at' => $time,
            ], [
                'id' => 9,
                'account' => 'niuxing',
                'name' => '牛星',
                'password' => md5(md5('admin001').env('APP_ATTACH')),
                'mobile' => '18301466524',
                'token' => '',
                'group' => 1,
                'expired_at' => date('Y-m-d H:i:s', strtotime('+15 days')),
                'created_at' => $time,
                'updated_at' => $time,
            ], [
                'id' => 10,
                'account' => 'lixuebing',
                'name' => '李雪兵',
                'password' => md5(md5('admin001').env('APP_ATTACH')),
                'mobile' => '18519507421',
                'token' => '',
                'group' => 1,
                'expired_at' => date('Y-m-d H:i:s', strtotime('+15 days')),
                'created_at' => $time,
                'updated_at' => $time,
            ], [
                'id' => 11,
                'account' => 'maruize',
                'name' => '马瑞泽',
                'password' => md5(md5('admin001').env('APP_ATTACH')),
                'mobile' => '18800172263',
                'token' => '',
                'group' => 1,
                'expired_at' => date('Y-m-d H:i:s', strtotime('+15 days')),
                'created_at' => $time,
                'updated_at' => $time,
            ], [
                'id' => 12,
                'account' => 'liliqiang',
                'name' => '李利强',
                'password' => md5(md5('admin001').env('APP_ATTACH')),
                'mobile' => '18515630469',
                'token' => '',
                'group' => 1,
                'expired_at' => date('Y-m-d H:i:s', strtotime('+15 days')),
                'created_at' => $time,
                'updated_at' => $time,
            ], [
                'id' => 13,
                'account' => 'datou',
                'name' => '大头',
                'password' => md5(md5('admin001').env('APP_ATTACH')),
                'mobile' => '18824250152',
                'token' => '',
                'group' => 1,
                'expired_at' => date('Y-m-d H:i:s', strtotime('+15 days')),
                'created_at' => $time,
                'updated_at' => $time,
            ], [
                'id' => 14,
                'account' => 'lichengyuan',
                'name' => '李承远',
                'password' => md5(md5('admin001').env('APP_ATTACH')),
                'mobile' => '13923447357',
                'token' => '',
                'group' => 1,
                'expired_at' => date('Y-m-d H:i:s', strtotime('+15 days')),
                'created_at' => $time,
                'updated_at' => $time,
            ],
        ]);
    }
}
