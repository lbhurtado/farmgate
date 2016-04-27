<?php

namespace App;

use App\Entities\Contact;
use App\Entities\ShortMessage;
use App\Repositories\ContactRepository;
use App\Repositories\ShortMessageRepository;

class TextCommander
{
    private $attributes;

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

    public function isShortMessageByNewContact()
    {
        $mobile = ShortMessage::getSignificantMobile($this->attributes);

        $contacts = \App::make(ContactRepository::class)->skipPresenter();

        return count($contacts->findByField('mobile', $mobile)) == 0;
    }

    public function recordShortMessage()
    {
        return \App::make(ShortMessageRepository::class)->skipPresenter()->create($this->attributes);
    }

    public static function ShortMessage(Array $attributes)
    {
        return (new static($attributes))->recordShortMessage();
    }

}
