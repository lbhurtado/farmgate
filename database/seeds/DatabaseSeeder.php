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

        $this->call(LGUCandidatesSeeder::class);
        $this->call(DistrictsTableSeeder::class);
//        $this->call(GroupsTableSeeder::class);
//        $this->call(TokensTableSeeder::class);
    }
}
