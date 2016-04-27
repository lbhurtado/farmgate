<?php

use App\Repositories\ShortMessageRepository;
use App\TextCommander;
use App\Mobile;
use App\Entities\ShortMessage;

class TextCommanderTest extends TestCase
{
    use DatabaseMigrationsWithSeeding;

//    /** @test */
//    function text_commander_consumes_a_short_message()
//    {
//        $short_message = App::make(ShortMessageRepository::class)->skipPresenter()->create([
//            'from'      => '09173011987',
//            'to'        => '09189362340',
//            'message'   => "The quick brown fox...",
//            'direction' => INCOMING
//        ]);
//
//        $commander = new TextCommander($short_message);
//
//        $this->assertEquals(Mobile::number('+639173011987'), $commander->getShortMessage()->from);
//        $this->assertEquals(Mobile::number('+639189362340'), $commander->getShortMessage()->to);
//        $this->assertEquals("The quick brown fox...",        $commander->getShortMessage()->message);
//        $this->assertEquals(INCOMING,                        $commander->getShortMessage()->direction);
//    }

    /** @test */
    function text_commander_consumes_an_array_of_attributes()
    {
        $attributes = [
            'from'      => '09173011987',
            'to'        => '09189362340',
            'message'   => "The quick brown fox...",
            'direction' => INCOMING
        ];

        $commander = new TextCommander($attributes);

        $this->assertEquals('09173011987',            array_get($commander->getAttributes(), 'from'));
        $this->assertEquals('09189362340',            array_get($commander->getAttributes(), 'to'));
        $this->assertEquals("The quick brown fox...", array_get($commander->getAttributes(), 'message'));
        $this->assertEquals(INCOMING,                 array_get($commander->getAttributes(), 'direction'));;
    }

    /** @test */
    function text_commander_checks_if_short_message_is_from_new_contact()
    {
        $attributes = [
            'from'      => '09173011987',
            'to'        => '09189362340',
            'message'   => "The quick brown fox...",
            'direction' => INCOMING
        ];
        $commander = new TextCommander($attributes);

        $this->assertTrue($commander->isShortMessageByNewContact());
    }

    /** @test */
    function text_commander_can_record_a_short_message()
    {
        $attributes = [
            'from'      => '09173011987',
            'to'        => '09189362340',
            'message'   => "The quick brown fox...",
            'direction' => INCOMING
        ];
        $commander = new TextCommander($attributes);
        $short_message = $commander->recordShortMessage()->getShortMessage();

        $this->assertInstanceOf(ShortMessage::class, $short_message);
    }

    /** @test */
    function text_commander_can_statically_record_a_short_message()
    {
        $attributes = [
            'from'      => '09173011987',
            'to'        => '09189362340',
            'message'   => "The quick brown fox...",
            'direction' => INCOMING
        ];
        $short_message = TextCommander::persistShortMessage($attributes)->getShortMessage();

        $this->assertInstanceOf(ShortMessage::class, $short_message);
    }
}
