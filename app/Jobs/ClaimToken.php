<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Entities\ShortMessage;
use App\Repositories\TokenRepository;

class ClaimToken extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    private $message;

    private $contact;

    public function __construct(ShortMessage $short_message)
    {
        $this->message = $short_message->message;
        $this->contact = $short_message->contact;
    }

    /**
     * @param TokenRepository $tokens
     */
    public function handle(TokenRepository $tokens)
    {
        $token = $tokens->findByField('code', $this->message)->first();
        $tokenIsValid = !is_null($token);

        if ($tokenIsValid) $this->contact->claimToken($token->code);
    }
}
