<?php

namespace App\Validators;

use \Prettus\Validator\Contracts\ValidatorInterface;
use \Prettus\Validator\LaravelValidator;

class ClusterValidator extends LaravelValidator {

    protected $rules = [
		ValidatorInterface::RULE_CREATE => [
			'name'	=> 'required',
			'precincts'	=> 'required',
			'registered_voters' => 'integer|min:100|max:1000'

	],
        ValidatorInterface::RULE_UPDATE => [
			'name'	=> 'required',
			'precincts'	=> 'required',
			'registered_voters' => 'integer|min:100|max:1000'
	],
   ];

}