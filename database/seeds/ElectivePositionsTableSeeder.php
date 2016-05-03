<?php

use Illuminate\Database\Seeder;

class ElectivePositionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('elective_positions')->delete();

        DB::table('elective_positions')->insert(['name' => 'President',         'tag' => 1]);
        DB::table('elective_positions')->insert(['name' => 'Vice-President',    'tag' => 2]);
        DB::table('elective_positions')->insert(['name' => 'Governor',          'tag' => 3]);
        DB::table('elective_positions')->insert(['name' => 'Vice-Governor',     'tag' => 4]);
        DB::table('elective_positions')->insert(['name' => 'Congressman',       'tag' => 5]);
        DB::table('elective_positions')->insert(['name' => 'Mayor',             'tag' => 6]);
        DB::table('elective_positions')->insert(['name' => 'Vice-Mayor',        'tag' => 7]);
    }
}
