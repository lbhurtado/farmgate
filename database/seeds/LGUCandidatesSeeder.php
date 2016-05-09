<?php

use App\Repositories\ElectivePositionRepository;
use Illuminate\Database\Seeder;
use League\Csv\Reader;

class LGUCandidatesSeeder extends Seeder
{
    private $elective_positions;

    /**
     * LGUCandidatesSeeder constructor.
     * @param $elective_positions
     */
    public function __construct(ElectivePositionRepository  $elective_positions)
    {
        $this->elective_positions = $elective_positions->skipPresenter();
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(ElectivePositionsTableSeeder::class);

        $reader = Reader::createFromPath(database_path('lgu_candidates.csv'));

        $this->call(CandidatesTableSeeder::class);

        $candidates = [];
        foreach ($reader as $index => $row)
        {
            $town_name = trim($row[1]);
            $position = trim($row[2]);
            $candidate_name = trim($row[3]);
            $keyword = trim($row[4]);
            $name = "$position of $town_name";
            switch ($position)
            {
                case 'Mayor':
                    $tag = '6';
                    break;
                case 'Vice-Mayor':
                    $tag = '7';
                    break;
            }
            $elective_position = $this->elective_positions->updateOrCreate(compact('name','tag'));

            $candidates [] = array(
                'name' => $candidate_name,
                'alias' => $keyword,
                'elective_position_id' => $elective_position->id
            );
        }
        DB::table('candidates')->insert($candidates);
    }
}
