<?php

use App\Repositories\ElectivePositionRepository;
use Illuminate\Database\Seeder;
use League\Csv\Reader;

class CandidatesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('candidates')->delete();

        $reader = Reader::createFromPath(storage_path('app/public/candidate.csv'));
        $reader->setDelimiter(',');
        $reader->setEnclosure('"');
        $reader->setEscape('\\');

        $elective_positions = \App::make(ElectivePositionRepository::class)->skipPresenter();
        $candidates = [];
        foreach ($reader as $index => $row)
        {
            $elective_position =  $elective_positions->findByField('name', trim($row[2]))->first();
            $candidates [] = array(
                'name' => $row[0],
                'alias' => trim($row[1]),
                'elective_position_id' => $elective_position->id
            );
        }

        // Uncomment the below to run the seeder

        DB::table('candidates')->insert($candidates);
    }
}
