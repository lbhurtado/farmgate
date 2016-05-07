<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Repositories\ContactRepository;
use App\Repositories\ClusterRepository;
use App\Repositories\TokenRepository;
use App\Repositories\GroupRepository;
use App\Repositories\TownRepository;
use App\Criteria\Town2Criterion;
use App\Entities\Cluster;
use App\Entities\Group;

class TokenTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function token_has_unique_code()
    {
        $this->setExpectedException(Illuminate\Database\QueryException::class);

        App::make(TokenRepository::class)->skipPresenter()->create([
            'code'       => 'ABC1234',
            'class'      => Group::class,
            'reference'  => 1
        ]);

        App::make(TokenRepository::class)->skipPresenter()->create([
            'code'       => 'ABC1234',
            'class'      => Group::class,
            'reference'  => 2
        ]);
    }

    /** @test */
    function token_has_unique_object_and_object_id()
    {
        $this->setExpectedException(Illuminate\Database\QueryException::class);

        App::make(TokenRepository::class)->skipPresenter()->create([
            'code'       => 'ABC1234',
            'class'      => Group::class,
            'reference'  => 1
        ]);

        App::make(TokenRepository::class)->skipPresenter()->create([
            'code'       => 'XYZ4321',
            'class'      => Group::class,
            'reference'  => 1
        ]);
    }

    /** @test */
    function tokens_can_be_claimed_by_a_contact_returning_the_object()
    {
        $group1 = App::make(GroupRepository::class)->skipPresenter()->create([
            'name' => "Test Group 1"
        ]);

        $group2 = App::make(GroupRepository::class)->skipPresenter()->create([
            'name' => "Test Group 2"
        ]);

        $tokens = App::make(TokenRepository::class)->skipPresenter();
        $claim_code = 'ABC1234';
        $unclaimed_code = 'XYZ4321';
        $tokens->create([
            'code'       => $claim_code,
            'class'      => Group::class,
            'reference'  => $group1->id
        ]);
        $tokens->create([
            'code'       => $unclaimed_code,
            'class'     => Group::class,
            'reference'  => $group2->id
        ]);

        $this->assertCount(2, $tokens->all());

        $contact = App::make(ContactRepository::class)->skipPresenter()->create([
            'mobile' => '09173011987',
            'handle' => "lbhurtado"
        ]);

        $object = $tokens->claim($contact, $claim_code);

        $this->assertInstanceOf(Group::class, $object);
        $this->assertEquals($group1->id, $object->id);
        $this->assertCount(1, $tokens->all());

        $token = $tokens->first();
        $this->assertEquals($unclaimed_code, $token->code);
    }

    /** @test */
    function tokens_can_be_generated()
    {
        $tokens = App::make(TokenRepository::class)->skipPresenter();
        $groups = App::make(GroupRepository::class)->skipPresenter();
        $tokens->generate($groups->all());

        $this->assertCount(count($groups->all()), $tokens->all());
    }

    /** @test */
    function tokens_can_auto_associate_contact()
    {
        $tokens = App::make(TokenRepository::class)->skipPresenter();
        $groups = App::make(GroupRepository::class)->skipPresenter();
        $contacts = App::make(ContactRepository::class)->skipPresenter();
        $tokens->generate($groups->all());

        $claim_code = 'ABC1234';

        $group1 = $groups->create([
            'name' => "Test Group 1"
        ]);

        $tokens->create([
            'code'       => $claim_code,
            'class'      => Group::class,
            'reference'  => $group1->id
        ]);

        $contact = $contacts->create([
            'mobile' => '09173011987',
            'handle' => "lbhurtado"
        ]);

        $token = $tokens->find(1);

        $group_object = $contact->claimToken($token->code);

//        $this->assertCount(1, $group_object->contacts);
//
//        $relation = $group_object->getTable();// get table name of relationship
//
//        $this->assertEquals(
//            $group_object->id,
//            $contact->$relation()->find($group_object->id)->id
//        );
//
//        $this->seeInDatabase($contact->groups()->getTable(), ['contact_id' => $contact->id, 'group_id' => $group_object->id]);
    }

    /** test */
    function tokens_can_discern_cluster_gibberish()
    {
        $this->artisan('db:seed');

        $input_line = "ternate 1 Lester Hurtado";

        $towns = $this->app->make(TownRepository::class)->skipPresenter();
        $clusters = $this->app->make(ClusterRepository::class)->skipPresenter();

        $town_regex = implode('|', $towns->all()->pluck('name')->toArray());

        if (preg_match("/\b(?<town>$town_regex)\b[^\d]*(?<number>(?:\d+\w?|\w?\d+)).+?(?=\w)(?<name>.*?[\w\s]*)/i", $input_line, $output_array))
        {
            extract($output_array);
        }
        elseif (preg_match("/(?<number>(?:\d+\w?|\w?\d+)).+?(?=\w)\b(?<town>amadeo|alfonso)\b.+?(?=\w)(?<name>.*?[\w\s]*)/i", $input_line, $output_array))
        {
            extract($output_array);
        }

        if (isset($town) && isset($name) && isset($number))
        {
            if (preg_match("/(?:(?!0[a-z])(?<precinct>(?:(?:\d{1,3}\s*[a-z])|(?:[a-z]\s*\d{1,3})))|(?<cluster>\d{1,3}))/i", $number, $output_array))
            {
                extract($output_array);
            }

            $town = $towns->findByField('name', strtoupper($town))->first();


            if (isset($cluster))
            {
                $cluster_object = $clusters->getByCriteria(new Town2Criterion($town))->where('name', $cluster)->first();

            }
            elseif (isset($precinct))
            {
                $precinct = ltrim($precinct, "0");

                $cluster_object = (new Cluster())->whereRaw("precincts REGEXP '[[:<:]]". $precinct ."[[:>:]]'")
                    ->with('town')->whereHas('town', function($q) use($town) {
                        $q->where('id',$town->id);
                    })->first();
            }

            echo "\n" . $input_line;
            echo "\n" . $cluster_object->town->name . " " . $cluster_object->name;
        }


    }
}
