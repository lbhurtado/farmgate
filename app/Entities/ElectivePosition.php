<?php

namespace App\Entities;

use Prettus\Repository\Traits\TransformableTrait;
use Prettus\Repository\Contracts\Transformable;
use Illuminate\Database\Eloquent\Model;

class ElectivePosition extends Model implements Transformable
{
    use TransformableTrait;

    protected $fillable = [
		'name',
	];

}
