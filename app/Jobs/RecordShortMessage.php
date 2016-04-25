<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Repositories\ShortMessageRepository;

class RecordShortMessage extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    private $from;

    private $to;

    private $message;

    /**
     * RecordShortMessage constructor.
     * @param $from
     * @param $to
     * @param $message
     */
    public function __construct($from, $to, $message)
    {
        $this->from = $from;
        $this->to = $to;
        $this->message = $message;
    }


    /**
     * @param ShortMessageRepository $shortMessageRepository
     */
    public function handle(ShortMessageRepository $shortMessageRepository)
    {
        $from = $this->from;
        $to = $this->to;
        $message = $this->message;

        $shortMessageRepository->create(compact('from', 'to', 'message'));
    }
}
