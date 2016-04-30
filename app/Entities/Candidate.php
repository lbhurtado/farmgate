<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use Prettus\Repository\Traits\PresentableTrait;

class Candidate extends Model implements Transformable
{
    use TransformableTrait;

    protected $fillable = [
		'name',
		'alias',
	];

}
