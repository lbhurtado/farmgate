<?php

namespace App;
use App\Entities\ShortMessage;

class Instruction
{
    public static $keywords = [
        'REGISTRATION' => 'txt reg',
        'POLL' => 'txt poll',
    ];

    protected $short_message;

    protected $message;

    protected $keyword;

    protected $arguments;

    /**
     * Instruction constructor.
     * @param $message
     */
    public function __construct(ShortMessage $short_message)
    {
        $this->short_message = $short_message;

        $this->message = $this->short_message->message;

        if ($this->message)
        {
            $keywords = implode('|', array_values(static::$keywords));

            if (preg_match("/^(?<keyword>$keywords)\s(?<arguments>.*)$/i", $this->message, $matches))
            {
                $this->keyword = $matches['keyword'];
                $this->arguments = $matches['arguments'];
            }
        }
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @return mixed
     */
    public function getKeyword()
    {
        return strtoupper($this->keyword);
    }

    /**
     * @return mixed
     */
    public function getArguments()
    {
        return $this->arguments;
    }

    public function isValid()
    {
        return (! is_null($this->keyword));
    }

    public static function create($message)
    {
        return new static($message);
    }

    /**
     * @return ShortMessage
     */
    public function getShortMessage()
    {
        return $this->short_message;
    }


}
