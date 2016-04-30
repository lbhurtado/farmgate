<?php

namespace App;

class Instruction
{
    public static $keywords = [
        'txt reg',
        'txt poll',
    ];

    protected $message;

    protected $keyword;

    protected $arguments;

    /**
     * Instruction constructor.
     * @param $message
     */
    public function __construct($message)
    {
        $this->message = $message;

        if ($message)
        {
            $keywords = implode('|', static::$keywords);

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
}
