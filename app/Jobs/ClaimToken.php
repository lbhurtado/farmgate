<?php

namespace App\Jobs;

use App\Repositories\TokenRepository;
use App\Entities\ShortMessage;

class ClaimToken extends Job
{
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
