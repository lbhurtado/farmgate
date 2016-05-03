<?php

namespace App\Validators;

use \Prettus\Validator\Contracts\ValidatorInterface;
use \Prettus\Validator\LaravelValidator;

class ElectivePositionValidator extends LaravelValidator {

    protected $rules = [
        ValidatorInterface::RULE_CREATE => [
            'name'	=> 'required',
            'tag'   => 'required|integer|min:0'
	],
        ValidatorInterface::RULE_UPDATE => [
		    'name'	=> 'required',
            'tag'   => 'required|integer|min:0'
	],
   ];

}