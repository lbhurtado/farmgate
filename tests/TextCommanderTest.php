<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Repositories\ShortMessageRepository;
use App\TextCommander;

class TextCommanderTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function text_commander_consumes_a_short_message()
    {
        $short_message = App::make(ShortMessageRepository::class)->skipPresenter()->create([
            'from'      => '09173011987',
            'to'        => '09189362340',
            'message'   => "The quick brown fox...",
            'direction' => INCOMING
        ]);

        $commander = new TextCommander($short_message);

        $this->assertEquals("+639173011987",            $commander->short_message->from);
        $this->assertEquals("+639189362340",            $commander->short_message->to);
        $this->assertEquals("The quick brown fox...",   $commander->short_message->message);
        $this->assertEquals(INCOMING,                   $commander->short_message->direction);
    }
}
