<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Repositories\ShortMessageRepository;
use App\Entities\ShortMessage;
use libphonenumber\PhoneNumberFormat;
use App\Criteria\IncomingShortMessageCriterion;

class ShortMessageTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function short_message_has_from_to_message()
    {
        $short_message = App::make(ShortMessageRepository::class)->skipPresenter()->create([
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

        App::make(ShortMessageRepository::class)->create([
            'from' => '09173011987',
            'to'   => '09189362340',
        ]);
    }

    /** @test */
    function short_message_message_field_is_not_blank()
    {
        $this->setExpectedException(Prettus\Validator\Exceptions\ValidatorException::class);

        App::make(ShortMessageRepository::class)->create([
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

        App::make(ShortMessageRepository::class)->create([
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

        App::make(ShortMessageRepository::class)->create([
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
                'from' => 'phone:PH',
                'to'   => 'phone:PH',
            ]
        );

        $this->assertTrue($validator->passes());
    }

    /** @test */
    function short_message_has_a_presenter()
    {
        $sms = App::make(ShortMessageRepository::class)->create([
            'from'      => '09173011987',
            'to'        => '09189362340',
            'message'   => "The quick brown fox...",
            'direction' => INCOMING,
        ]);

        $this->assertEquals(
            phone_format('09173011987',        'PH', PhoneNumberFormat::E164),
            phone_format($sms['data']['from'], 'PH', PhoneNumberFormat::E164)
        );
    }

    /** @test */
    function short_message_has_an_incoming_criterion()
    {
        App::make(ShortMessageRepository::class)->create([
            'from'      => '09189362340',
            'to'        => '09173011987',
            'message'   => "Outgoing message",
            'direction' => OUTGOING,
        ]);

        App::make(ShortMessageRepository::class)->create([
            'from'      => '09173011987',
            'to'        => '09189362340',
            'message'   => "Incoming message",
            'direction' => INCOMING,
        ]);

        $short_messages = App::make(ShortMessageRepository::class)->skipPresenter()->getByCriteria(new IncomingShortMessageCriterion());

        $this->assertEquals('Incoming message', $short_messages->first()->message);
    }
}
