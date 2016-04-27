<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class Token extends Model implements Transformable
{
    use TransformableTrait;

    protected $fillable = [
		'code',
		'class',
		'reference',
	];

	protected $casts = [
		'reference' => 'integer'
	];
}
