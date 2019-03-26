<?php

/*
 * This file is part of the mquery/sinmore.
 *
 * (c) guojian <n6878088@163.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

$databaseSeeder = <<<'CREATEDATA'
<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(AdminsTableSeeder::class);
        $this->call(RulesTableSeeder::class);
        $this->call(GroupsTableSeeder::class);
    }
}
CREATEDATA;
