<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class Town extends Model implements Transformable
{
    use TransformableTrait;

    protected $fillable = [
		'name',
	];

    public function clusters()
    {
        return $this->hasMany(Cluster::class);
    }
}
