<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Entities\ShortMessage;
use App\Repositories\ShortMessageRepository;

class TextCommander extends Model
{
    public $short_message;

    /**
     * TextCommander constructor.
     * @param $short_message
     */
    public function __construct(ShortMessage $short_message)
    {
        $this->short_message = $short_message;
    }

}
