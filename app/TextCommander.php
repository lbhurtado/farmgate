<?php

namespace App;

use App\Entities\Contact;
use App\Entities\ShortMessage;
use App\Repositories\ContactRepository;
use App\Repositories\ShortMessageRepository;

class TextCommander
{
    private $attributes;

    private $short_message;
    /**
     * TextCommander constructor.
     * @param $attributes
     */
    public function __construct(Array $attributes)
    {
        $this->attributes = $attributes;
    }

    public function getAttributes()
    {
        return $this->attributes;
    }

    public function getShortMessage()
    {
        return $this->short_message;
    }

    public function isShortMessageByNewContact()
    {
        $mobile = ShortMessage::getSignificantMobile($this->attributes);
        $contacts = \App::make(ContactRepository::class)->skipPresenter();

        return count($contacts->findByField('mobile', $mobile)) == 0;
    }

    public function recordShortMessage()
    {
        $this->short_message = \App::make(ShortMessageRepository::class)->skipPresenter()->create($this->attributes);

        return $this;
    }

    public static function persistShortMessage(Array $attributes)
    {
        return (new static($attributes))->recordShortMessage();
    }

}
