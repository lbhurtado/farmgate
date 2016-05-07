<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Repositories\ShortMessageRepository;
use App\Jobs\CreateContactFromShortMessage;
use App\Events\ShortMessageWasRecorded;
use App\Repositories\ContactRepository;
use App\Repositories\ClusterRepository;
use App\Repositories\TokenRepository;
use App\Repositories\GroupRepository;
use App\Entities\Cluster;
use App\Jobs\ClaimToken;
use App\Entities\Group;
use App\Mobile;

use App\Repositories\TownRepository;

class ClaimTokenTest extends TestCase
{
    use DatabaseMigrations, DispatchesJobs;

    /** @test */
    function short_message_with_group_token_creation_expects_a_job()
    {
        $this->expectsJobs(ClaimToken::class);

        $group = $this->app->make(GroupRepository::class)->skipPresenter()->create([
            'name' => "Test Group"
        ]);
        $claim_code = 'ABC1234';
        $tokens = $this->app->make(TokenRepository::class)->skipPresenter();
        $tokens->create([
            'code'       => $claim_code,
            'class'      => Group::class,
            'reference'  => $group->id
        ]);
        $this->app->make(ShortMessageRepository::class)->skipPresenter()->create([
            'from'      => '09189362340',
            'to'        => '09173011987',
            'message'   => $claim_code,
            'direction' => INCOMING
        ]);
    }

    /** @test */
    function short_message_with_cluster_token_creation_expects_a_job()
    {
        $this->expectsJobs(ClaimToken::class);

        $cluster = factory(Cluster::class)->create(['name' => "Test Cluster"]);
        $claim_code = 'ABC1234';
        $tokens = $this->app->make(TokenRepository::class)->skipPresenter();
        $tokens->create([
            'code'       => $claim_code,
            'class'      => Cluster::class,
            'reference'  => $cluster->id
        ]);
        $this->app->make(ShortMessageRepository::class)->skipPresenter()->create([
            'from'      => '09189362340',
            'to'        => '09173011987',
            'message'   => $claim_code,
            'direction' => INCOMING
        ]);
    }

    /** @test */
    function claim_token_does_the_job_for_valid_group_token()
    {
        $group = $this->app->make(GroupRepository::class)->skipPresenter()->create([
            'name' => "Test Group"
        ]);
        $claim_code = 'ABC1234';
        $tokens = $this->app->make(TokenRepository::class)->skipPresenter();
        $tokens->create([
            'code'       => $claim_code,
            'class'      => Group::class,
            'reference'  => $group->id
        ]);
        $this->assertCount(1, $tokens->all());
        $this->expectsEvents(ShortMessageWasRecorded::class);
        $short_message = $this->app->make(ShortMessageRepository::class)->skipPresenter()->create([
            'from'      => '09189362340',
            'to'        => '09173011987',
            'message'   => $claim_code,
            'direction' => INCOMING
        ]);
        $job = new CreateContactFromShortMessage($short_message);
        $this->dispatch($job);
        $job = new ClaimToken($short_message);
        $this->dispatch($job);

        $this->assertCount(1, $group->contacts);
        $this->assertEquals(Mobile::number('09189362340'), $group->contacts->first()->mobile);

        $contacts = $this->app->make(ContactRepository::class)->skipPresenter();
        $contact = $contacts->all()->first();
        $this->assertEquals("Test Group", $contact->groups->first()->name);
    }

    /** @test */
    function claim_token_does_the_job_for_valid_cluster_token()
    {
        $clusters = $this->app->make(ClusterRepository::class)->skipPresenter();
        $cluster = $clusters->create([
            'name' => "Test Cluster",
            'precincts' => '1A, 2A, 3A, 4K',
            'registered_voters' => 800
        ]);
        $claim_code = 'ABC1234';
        $tokens = $this->app->make(TokenRepository::class)->skipPresenter();
        $tokens->create([
            'code'       => $claim_code,
            'class'      => Cluster::class,
            'reference'  => $cluster->id
        ]);
        $this->assertCount(1, $tokens->all());
        $this->expectsEvents(ShortMessageWasRecorded::class);
        $short_message = $this->app->make(ShortMessageRepository::class)->skipPresenter()->create([
            'from'      => '09189362340',
            'to'        => '09173011987',
            'message'   => $claim_code,
            'direction' => INCOMING
        ]);
        $job = new CreateContactFromShortMessage($short_message);
        $this->dispatch($job);
        $job = new ClaimToken($short_message);
        $this->dispatch($job);

        $cluster = $clusters->findByField('name','Test Cluster')->first();

        $this->assertNotNull(1, $cluster->contacts);
        $this->assertEquals(Mobile::number('09189362340'), $cluster->contacts->mobile);

        $contacts = $this->app->make(ContactRepository::class)->skipPresenter();
        $contact = $contacts->all()->first();

        $this->assertEquals("Test Cluster", $contact->cluster->name);
    }

    /** @test */
    function claim_token_does_the_job_for_invalid_token()
    {
        $group = $this->app->make(GroupRepository::class)->skipPresenter()->create([
            'name' => "Test Group"
        ]);
        $claim_code = 'ABC1234';
        $tokens = $this->app->make(TokenRepository::class)->skipPresenter();
        $tokens->create([
            'code'       => $claim_code,
            'class'      => Group::class,
            'reference'  => $group->id
        ]);
        $this->assertCount(1, $tokens->all());
        $this->expectsEvents(ShortMessageWasRecorded::class);
        $short_message = $this->app->make(ShortMessageRepository::class)->skipPresenter()->create([
            'from'      => '09189362340',
            'to'        => '09173011987',
            'message'   => "adsakdjalskdsaldla alksdlas dsaslkd",
            'direction' => INCOMING
        ]);
        $job = new CreateContactFromShortMessage($short_message);
        $this->dispatch($job);
        $job = new ClaimToken($short_message);
        $this->dispatch($job);

        $this->assertCount(0, $group->contacts);

        $contacts = $this->app->make(ContactRepository::class)->skipPresenter();
        $contact = $contacts->all()->first();

        $this->assertCount(0, $contact->groups);
    }

    /** test */
    function claim_token_debug()
    {

        $short_message = $this->app->make(ShortMessageRepository::class)->skipPresenter()->create([
            'from'      => '09189362340',
            'to'        => '09173011987',
            'message'   => 'TERNATE 1 Lester',
            'direction' => INCOMING
        ]);

        $towns = \App::make(TownRepository::class)->skipPresenter();
        $clusters = \App::make(ClusterRepository::class)->skipPresenter();

        dd($this->getTownNumberName($towns, $short_message->message));
//        list($town, $number, $name) = $this->getTownNumberName($towns, $short_message->message);

    }

    protected function getTownNumberName($towns, $input_line)
    {
        $this->artisan('db:seed');

        $town_regex = implode('|', $towns->all()->pluck('name')->toArray()); //get alias instead

        if (preg_match("/\b(?<town>$town_regex)\b[^\d]*(?<number>(?:\d+\w?|\w?\d+)).+?(?=\w)(?<name>.*?[\w\s]*)/i", $input_line, $output_array))
        {
            return array_only($output_array, ['town', 'number', 'name']);
        }
        elseif (preg_match("/(?<number>(?:\d+\w?|\w?\d+)).+?(?=\w)\b(?<town>$town_regex)\b.+?(?=\w)(?<name>.*?[\w\s]*)/i", $input_line, $output_array))
        {
            return array_only($output_array, ['town', 'number', 'name']);
        }

        return array(null, null, null);
    }
}
