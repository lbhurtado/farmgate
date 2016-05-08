<?php

use Illuminate\Database\Seeder;
use League\Csv\Reader;

class LGUCandidatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('elective_positions')->delete();

        $reader = Reader::createFromPath(database_path('lgu_candidates.csv'));

        $elective_positions = [];
        foreach ($reader as $index => $row)
        {
            $town_name = $row[1];
            $position = $row[2];
            $candidate_name = $row[3];
            $keyword = $row[4];
            switch ($position)
            {
                case 'Mayor':
                    $elective_position_name = "$position of $town_name";
            }
            $elective_positions [] = array(
                'name' => $row[0],
                'tag' => (int) trim($row[1])
            );
        }

        DB::table('elective_positions')->insert($elective_positions);
    }
}
