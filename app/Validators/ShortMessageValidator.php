<?php

namespace App\Validators;

use \Prettus\Validator\Contracts\ValidatorInterface;
use \Prettus\Validator\LaravelValidator;

class ShortMessageValidator extends LaravelValidator {

    protected $rules = [
        ValidatorInterface::RULE_CREATE => [
            'from'      => 'phone:PH',
            'to'        => 'phone:PH',
            'message'   => 'required',
            'direction' => 'in:-1,1',
        ],
        ValidatorInterface::RULE_UPDATE => [
            'from'      => 'phone:PH',
            'to'        => 'phone:PH',
            'message'   => 'required',
            'direction' => 'in:-1,1',
        ],
   ];

}