<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Criteria\IncomingShortMessageCriterion;
use App\Repositories\ShortMessageRepository;
use App\Jobs\CreateContactFromShortMessage;
use App\Events\ShortMessageWasRecorded;
use App\Entities\ShortMessage;
use App\Entities\Contact;
use App\Mobile;

class ShortMessageTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function short_message_has_from_to_message_and_calculated_mobile_fields()
    {
        $short_message = $this->app->make(ShortMessageRepository::class)->skipPresenter()->create([
            'from'      => '09173011987',
            'to'        => '09189362340',
            'message'   => "The quick brown fox...",
            'direction' => INCOMING
        ]);

        $this->assertInstanceOf(ShortMessage::class,  $short_message);
        $this->assertEquals('+639173011987',          $short_message->from);
        $this->assertEquals('+639189362340',          $short_message->to);
        $this->assertEquals('The quick brown fox...', $short_message->message);
        $this->assertEquals(INCOMING,                 $short_message->direction);
        $this->assertEquals('+639173011987',          $short_message->mobile);
        $this->seeInDatabase($short_message->getTable(), [
            'from'      => '+639173011987',
            'to'        => '+639189362340',
            'message'   => "The quick brown fox...",
            'direction' => INCOMING,
        ]);
    }

    /** @test */
    function short_message_message_field_is_required()
    {
        $this->setExpectedException(Prettus\Validator\Exceptions\ValidatorException::class);

        $this->app->make(ShortMessageRepository::class)->create([
            'from' => '09173011987',
            'to'   => '09189362340',
        ]);
    }

    /** @test */
    function short_message_message_field_is_not_blank()
    {
        $this->setExpectedException(Prettus\Validator\Exceptions\ValidatorException::class);

        $this->app->make(ShortMessageRepository::class)->create([
            'from'      => '09173011987',
            'to'        => '09189362340',
            'message'   => '',
            'direction' => INCOMING,
        ]);
    }

    /** @test */
    function short_message_from_field_is_a_mobile_number()
    {
        $this->setExpectedException(Prettus\Validator\Exceptions\ValidatorException::class);

        $this->app->make(ShortMessageRepository::class)->create([
            'from'      => '1234',
            'to'        => '09189362340',
            'message'   => "The quick brown fox...",
            'direction' => INCOMING,
        ]);
    }

    /** @test */
    function short_message_to_field_is_a_mobile_number()
    {
        $this->setExpectedException(Prettus\Validator\Exceptions\ValidatorException::class);

        $this->app->make(ShortMessageRepository::class)->create([
            'from'      => '09173011987',
            'to'        => '4321',
            'message'   => "The quick brown fox...",
            'direction' => INCOMING,
        ]);
    }

    /** @test */
    function short_message_factory_is_valid(){
        $sms = factory(ShortMessage::class)->create();
        $validator = Validator::make(
            $sms->all()->toArray(),
            [
                'from' => array('phone:PH'),
                'to'   => array('phone:PH'),
            ]
        );

        $this->assertTrue($validator->passes());
    }

    /** @test */
    function short_message_has_a_presenter()
    {
        $sms =  $this->app->make(ShortMessageRepository::class)->create([
            'from'      => '09173011987',
            'to'        => '09189362340',
            'message'   => "The quick brown fox...",
            'direction' => INCOMING,
        ]);

        $this->assertEquals(
            Mobile::number('09173011987'),
            Mobile::number($sms['data']['from'])
        );
    }

    /** @test */
    function short_message_has_an_incoming_criterion()
    {
        $this->app->make(ShortMessageRepository::class)->create([
            'from'      => '09189362340',
            'to'        => '09173011987',
            'message'   => "Outgoing message",
            'direction' => OUTGOING,
        ]);

        $this->app->make(ShortMessageRepository::class)->create([
            'from'      => '09173011987',
            'to'        => '09189362340',
            'message'   => "Incoming message",
            'direction' => INCOMING,
        ]);

        $short_messages = App::make(ShortMessageRepository::class)->skipPresenter()->getByCriteria(new IncomingShortMessageCriterion());

        $this->assertEquals('Incoming message', $short_messages->first()->message);
    }

    /** @test */
    function short_message_creation_fires_event()
    {
        $this->expectsEvents(ShortMessageWasRecorded::class);

        $this->app->make(ShortMessageRepository::class)->skipPresenter()->create([
            'from'      => '09173011987',
            'to'        => '09189362340',
            'message'   => "The quick brown fox...",
            'direction' => INCOMING
        ]);
    }

    /** @test */
    function short_message_creation_fires_event_then_dispatches_job()
    {
        $this->expectsJobs(CreateContactFromShortMessage::class);

        $this->app->make(ShortMessageRepository::class)->skipPresenter()->create([
            'from'      => '09173011987',
            'to'        => '09189362340',
            'message'   => "The quick brown fox...",
            'direction' => INCOMING
        ]);
    }

    /** @test */
    function short_message_has_a_contact()
    {
        $short_message =  $this->app->make(ShortMessageRepository::class)->skipPresenter()->create([
            'from'      => '09173011987',
            'to'        => '09189362340',
            'message'   => "POE 123 BINAY 234 DUTERTE 345 ROXAS 456",
            'direction' => INCOMING
        ]);

        $this->assertInstanceOf(Contact::class, $short_message->contact);
        $this->assertEquals(Mobile::number('09173011987'), $short_message->contact->mobile);
        $this->assertEquals(Mobile::number('09173011987'), $short_message->contact->handle);
    }

    /** @test */
    function short_message_has_a_poll_keyword()
    {
        $short_message = $this->app->make(ShortMessageRepository::class)->skipPresenter()->create([
            'from'      => '09173011987',
            'to'        => '09189362340',
            'message'   => "txt poll POE 123 BINAY 234 DUTERTE 345 ROXAS 456",
            'direction' => INCOMING
        ]);

        $this->assertEquals('TXT POLL', $short_message->keyword);
    }
}
