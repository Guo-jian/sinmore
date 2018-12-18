<?php

use Illuminate\Database\Seeder;

class AdminsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('admins')->insert([
            'id'=>1,
            'name'=>'admin',
            'password'=>md5(md5(1).env('APP_ATTACH')),
            'mobile'=>'admin',
            'token'=>1,
            'expired_at'=>date('Y-m-d H:i:s', strtotime('+15 days')),
            'created_at'=>date('Y-m-d H:i:s'),
            'updated_at'=>date('Y-m-d H:i:s'),
        ]);
    }
}
