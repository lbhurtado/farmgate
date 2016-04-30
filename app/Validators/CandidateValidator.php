<?php

namespace App\Validators;

use \Prettus\Validator\Contracts\ValidatorInterface;
use \Prettus\Validator\LaravelValidator;

class CandidateValidator extends LaravelValidator {

    protected $rules = [
        ValidatorInterface::RULE_CREATE => [
		'name'	=>'	required',
		'alias'	=>'	required',
	],
        ValidatorInterface::RULE_UPDATE => [
		'name'	=>'	required',
		'alias'	=>'	required',
	],
   ];

}