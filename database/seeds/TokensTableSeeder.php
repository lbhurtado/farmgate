<?php

use Illuminate\Database\Seeder;

class TokensTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $groups = DB::table('groups')->get();

        foreach ($groups as $group) {
            DB::table('tokens')->insert([
                'code' => str_random(10),
                'object' => App\Entities\Group::class,
                'object_id' => $group->id
            ]);
        }
    }
}
