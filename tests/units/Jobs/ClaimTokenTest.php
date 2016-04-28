<?php

use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Repositories\ShortMessageRepository;
use App\Jobs\CreateContactFromShortMessage;
use App\Events\ShortMessageWasRecorded;
use App\Repositories\ContactRepository;
use App\Repositories\TokenRepository;
use App\Repositories\GroupRepository;
use App\Entities\ShortMessage;
use App\Jobs\ClaimToken;
use App\Entities\Group;
use App\Mobile;

class ClaimTokenTest extends TestCase
{
    use DatabaseMigrationsWithSeeding, DispatchesJobs;

    /** @test */
    function short_message_with_token_creation_expects_a_job()
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
    function claim_token_does_the_job_for_valid_token()
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

        $this->assertCount(1, $group->members);
        $this->assertEquals(Mobile::number('09189362340'), $group->members->first()->mobile);

        $contacts = $this->app->make(ContactRepository::class)->skipPresenter();
        $contact = $contacts->all()->first();
        $this->assertEquals("Test Group", $contact->groups->first()->name);
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

        $this->assertCount(0, $group->members);

        $contacts = $this->app->make(ContactRepository::class)->skipPresenter();
        $contact = $contacts->all()->first();

        $this->assertCount(0, $contact->groups);
    }
}
