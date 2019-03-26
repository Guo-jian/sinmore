<?php

/*
 * This file is part of the mquery/sinmore.
 *
 * (c) guojian <n6878088@163.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

use Illuminate\Database\Seeder;

class GroupsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $time = date('Y-m-d H:i:s');
        DB::table('groups')->insert([
            [
                'id' => 1,
                'name' => '新墨测试组',
                'desc' => '新墨团队的测试分组',
                'created_at' => $time,
                'updated_at' => $time,
            ],[
                'id' => 2,
                'name' => '客户测试组',
                'desc' => '客户团队的测试分组',
                'created_at' => $time,
                'updated_at' => $time,
            ]
        ]);
    }
}
