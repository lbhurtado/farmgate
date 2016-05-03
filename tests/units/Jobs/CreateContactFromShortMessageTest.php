<?php


use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Repositories\ShortMessageRepository;
use App\Jobs\CreateContactFromShortMessage;
use App\Repositories\ContactRepository;
use App\Entities\ShortMessage;
use App\Entities\Contact;
use App\Mobile;


class CreateContactFromShortMessageTest extends TestCase
{
    use DatabaseMigrations, DispatchesJobs;

    /** @test */
    function create_contact_from_incoming_short_message_does_the_job()
    {
        $origin = '09173011987';
        $destination = '09189362340';

        $short_message = App::make(ShortMessageRepository::class)->skipPresenter()->create([
            'from'      => $origin,
            'to'        => $destination,
            'message'   => "The quick brown fox...",
            'direction' => INCOMING
        ]);

        $this->assertInstanceOf(ShortMessage::class,  $short_message);
        $job = new CreateContactFromShortMessage($short_message);
        $this->dispatch($job);
        $contact = $this->app->make(ContactRepository::class)->skipPresenter()->findByField('mobile', Mobile::number($origin))->first();

        $this->assertInstanceOf(Contact::class, $contact);
        $this->assertEquals(Mobile::number($origin), $contact->mobile);
        $this->assertEquals(Mobile::number($origin), $contact->handle);
        $this->seeInDatabase($contact->getTable(), [
            'mobile' => Mobile::number($origin),
            'handle' => Mobile::number($origin),
        ]);
    }

    /** @test */
    function create_contact_from_outgoing_short_message_does_the_job()
    {
        $origin = '09173011987';
        $destination = '09189362340';

        $short_message = App::make(ShortMessageRepository::class)->skipPresenter()->create([
            'from'      => $origin,
            'to'        => $destination,
            'message'   => "The quick brown fox...",
            'direction' => OUTGOING
        ]);

        $this->assertInstanceOf(ShortMessage::class,  $short_message);
        $job = new CreateContactFromShortMessage($short_message);
        $this->dispatch($job);
        $contact = $this->app->make(ContactRepository::class)->skipPresenter()->findByField('mobile', Mobile::number($destination))->first();

        $this->assertInstanceOf(Contact::class, $contact);
        $this->assertEquals(Mobile::number($destination), $contact->mobile);
        $this->assertEquals(Mobile::number($destination), $contact->handle);
        $this->seeInDatabase($contact->getTable(), [
            'mobile' => Mobile::number($destination),
            'handle' => Mobile::number($destination),
        ]);
    }
}
