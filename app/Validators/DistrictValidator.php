<?php

namespace App\Validators;

use \Prettus\Validator\Contracts\ValidatorInterface;
use \Prettus\Validator\LaravelValidator;

class DistrictValidator extends LaravelValidator {

    protected $rules = [
        ValidatorInterface::RULE_CREATE => [
            'name'	=>'	required',
        ],
        ValidatorInterface::RULE_UPDATE => [
            'name'	=>'	required',
        ],
   ];

}