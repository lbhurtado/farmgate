<?php

namespace App\Entities;

use Prettus\Repository\Traits\TransformableTrait;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\PresentableTrait;
use Illuminate\Database\Eloquent\Model;
use App\Entities\ElectivePosition;

class Candidate extends Model implements Transformable
{
    use TransformableTrait;

    protected $fillable = [
		'name',
		'alias',
	];

	function setAliasAttribute($value)
	{
		$this->attributes['alias'] = strtoupper($value);
	}

	function elective_position()
	{
		return $this->belongsTo(ElectivePosition::class);
	}
}
