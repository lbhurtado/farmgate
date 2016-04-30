<?php

namespace App\Validators;

use \Prettus\Validator\Contracts\ValidatorInterface;
use \Prettus\Validator\LaravelValidator;

class ElectionResultValidator extends LaravelValidator {

    protected $rules = [
        ValidatorInterface::RULE_CREATE => [
            'votes'	=> 'integer|min:1|max:1000',
        ],
        ValidatorInterface::RULE_UPDATE => [
            'votes'	=> 'integer|min:1|max:1000',
        ],
   ];

}