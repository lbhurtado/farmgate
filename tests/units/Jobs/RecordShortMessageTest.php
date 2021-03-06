<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Repositories\ShortMessageRepository;
use App\Jobs\RecordShortMessage;
use App\Entities\ShortMessage;

class RecordShortMessageTest extends TestCase
{
    use DatabaseMigrations, DispatchesJobs;

    /** @test */
    function record_short_message_does_the_job()
    {
        $job = new RecordShortMessage('09173011987', '09189362340', "The quick brown fox...", INCOMING);
        $this->dispatch($job);
        $short_message = $this->app->make(ShortMessageRepository::class)->skipPresenter()->findWhere([
            'from'      => '+639173011987',
            'to'        => '+639189362340',
            'message'   => 'The quick brown fox...',
            'direction' => INCOMING
        ])->first();

        $this->assertInstanceOf(ShortMessage::class,  $short_message);
        $this->assertEquals('+639173011987',          $short_message->from);
        $this->assertEquals('+639189362340',          $short_message->to);
        $this->assertEquals('The quick brown fox...', $short_message->message);
        $this->assertEquals(INCOMING,                 $short_message->direction);
        $this->seeInDatabase($short_message->getTable(), [
            'from'      => '+639173011987',
            'to'        => '+639189362340',
            'message'   => "The quick brown fox...",
            'direction' => INCOMING
        ]);
    }
}
