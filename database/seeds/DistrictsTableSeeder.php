<?php

use Illuminate\Database\Seeder;
use App\Repositories\DistrictRepository;
use App\Repositories\TownRepository;
use League\Csv\Reader;

class DistrictsTableSeeder extends Seeder
{
    private $reader;

    private $districts;

    private $towns;

    public function __construct(DistrictRepository $districts, TownRepository $towns)
    {
        $this->reader = Reader::createFromPath(database_path('districts.csv'));
        $this->districts = $districts->skipPresenter();
        $this->towns = $towns->skipPresenter();
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('districts')->delete();
        foreach ($this->reader as $index => $row)
        {
            if ($row[0] && $row[1])
            {
                $town_name = $row[0];
                $district_name = $row[1];

                $town = $this->towns->updateOrCreate(['name' => $town_name],['name' => $town_name]);
                $district = $this->districts->findByField('name', $district_name)->first();
                if ($district == null)
                {
                    $district = $this->districts->create(['name' => $district_name]);
                }
                if ($town && $district)
                {
                    $town->district()->associate($district)->save();
                }
            }
        }
    }
}
