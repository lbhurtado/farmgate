<?php

namespace App\Validators;

use \Prettus\Validator\Contracts\ValidatorInterface;
use \Prettus\Validator\LaravelValidator;

class ContactValidator extends LaravelValidator {

    protected $rules = [
        ValidatorInterface::RULE_CREATE => [
            'mobile' => [
                'required',
                'phone:PH'
            ],
        ],
        ValidatorInterface::RULE_UPDATE => [
            'mobile' => [
                'required',
                'phone:PH'
            ],
        ],
   ];

}