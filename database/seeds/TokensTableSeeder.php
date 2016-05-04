<?php

use App\Repositories\ClusterRepository;
use App\Repositories\TokenRepository;
use Illuminate\Database\Seeder;
use League\Csv\Writer;

class TokensTableSeeder extends Seeder
{
    private $tokens;

    /**
     * TokensTableSeeder constructor.
     * @param TokenRepository $tokens
     */
    public function __construct(TokenRepository $tokens)
    {
        $this->tokens = $tokens;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tokens')->delete();

        $groups = DB::table('groups')->get();

        foreach ($groups as $group) {
            DB::table('tokens')->insert([
                'code' => str_random(10),
                'class' => App\Entities\Group::class,
                'reference' => $group->id
            ]);
        }

        $clusters = \App::make(ClusterRepository::class)->skipPresenter()->all();

        $clusters->each(function($cluster, $key){
            $code = "";
            switch (strtoupper($cluster->town->name))
            {
                case 'CAVITE CITY':
                    $code = "CAVITE";
                    break;
                case 'CITY OF DASMARIÑAS':
                    $code = "DASMA";
                    break;
                case 'GENERAL EMILIO AGUINALDO':
                    $code = 'AGUINALDO';
                    break;
                case 'GEN. MARIANO ALVAREZ':
                    $code = 'GMA';
                    break;
                case 'GENERAL TRIAS':
                    $code = 'TRIAS';
                    break;
                case 'TAGAYTAY CITY':
                    $code = 'TAGAYTAY';
                    break;
                case 'TRECE MARTIREZ CITY':
                    $code = 'TRECE';
                    break;
                default:
                    $code = strtoupper($cluster->town->name);
            }
            DB::table('tokens')->insert([
                'code' => $code . $cluster->name,
                'class' => App\Entities\Cluster::class,
                'reference' => $cluster->id
            ]);
        });

//        $writer = Writer::createFromPath(storage_path('app/public/tokens.csv'));
//        $writer->insertAll($this->tokens->all(['code','class','reference'])->toArray());
    }
}
