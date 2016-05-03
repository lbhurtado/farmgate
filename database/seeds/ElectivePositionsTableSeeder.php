<?php

use Illuminate\Database\Seeder;
use League\Csv\Reader;

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

        $reader = Reader::createFromPath(storage_path('app/public/elective_position.csv'));

        $elective_positions = [];
        foreach ($reader as $index => $row)
        {
            $elective_positions [] = array(
                'name' => $row[0],
                'tag' => (int) trim($row[1])
            );
        }

        DB::table('elective_positions')->insert($elective_positions);
    }
}
