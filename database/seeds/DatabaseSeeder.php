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
        $this->call(ElectivePositionsTableSeeder::class);
        $this->call(CandidatesTableSeeder::class);
//        $this->call(ClustersTableSeeder::class);
        $this->call(GroupsTableSeeder::class);
//        $this->call(TokensTableSeeder::class);

    }
}
