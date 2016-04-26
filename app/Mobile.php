<?php

namespace App;

use libphonenumber\PhoneNumberFormat;

class Mobile
{
    public $number;

    /**
     * Mobile constructor.
     * @param $number
     */
    public function __construct($number)
    {
        $this->number = phone_format($number, 'PH', PhoneNumberFormat::E164);
    }

    public static function number($number)
    {
        return(new static($number))->number;
    }
}
