<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Jobs\CreateContact;
use App\Repositories\ContactRepository;
use App\Entities\Contact;
use App\Mobile;

class CreateContactTest extends TestCase
{
    use DatabaseMigrations, DispatchesJobs;

    /** @test */
    function create_contact_does_the_job()
    {
        $job = new CreateContact('09173011987', 'lbhurtado');
        $this->dispatch($job);
        $contact = $this->app->make(ContactRepository::class)->skipPresenter()->findByField('mobile', Mobile::number('09173011987'))->first();

        $this->assertInstanceOf(Contact::class, $contact);
        $this->assertEquals('+639173011987', $contact->mobile);
        $this->assertEquals('lbhurtado', $contact->handle);
        $this->seeInDatabase($contact->getTable(), [
            'mobile' => '+639173011987',
            'handle' => 'lbhurtado',
        ]);
    }

    /** @test */
    function create_contact_duplication_will_update()
    {
        $this->dispatch(new CreateContact('09173011987', 'lbhurtado'));
        $this->dispatch(new CreateContact('09173011987', 'lester'));

        $contacts = $this->app->make(ContactRepository::class)->skipPresenter();

        $this->assertCount(1, $contacts->all());
        $this->assertEquals('+639173011987', $contacts->first()->mobile);
        $this->assertEquals('lester', $contacts->first()->handle);

        $this->seeInDatabase($contacts->first()->getTable(), [
            'mobile' => '+639173011987',
            'handle' => 'lester',
        ]);
    }
}
