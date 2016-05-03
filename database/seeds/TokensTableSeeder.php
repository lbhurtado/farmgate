<?php

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

        $clusters = DB::table('clusters')->get();

        foreach ($clusters as $cluster) {
            DB::table('tokens')->insert([
                'code' => str_random(10),
                'class' => App\Entities\Cluster::class,
                'reference' => $cluster->id
            ]);
        }

        $writer = Writer::createFromPath(storage_path('app/public/tokens.csv'));
        $writer->insertAll($this->tokens->all(['code','class','reference'])->toArray());
    }
}
