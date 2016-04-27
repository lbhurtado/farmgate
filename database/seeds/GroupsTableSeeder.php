<?php

use Illuminate\Database\Seeder;

class GroupsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('groups')->insert(['name' => 'Clustered Precinct 1']);
        DB::table('groups')->insert(['name' => 'Clustered Precinct 2']);
        DB::table('groups')->insert(['name' => 'Clustered Precinct 3']);
        DB::table('groups')->insert(['name' => 'Clustered Precinct 4']);
        DB::table('groups')->insert(['name' => 'Clustered Precinct 5']);
    }
}
