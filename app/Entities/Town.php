<?php

namespace App\Entities;

use Prettus\Repository\Traits\TransformableTrait;
use Prettus\Repository\Contracts\Transformable;
use Illuminate\Database\Eloquent\Model;
use App\Entities\District;

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

    public function district()
    {
        return $this->belongsTo(District::class);
    }
}
