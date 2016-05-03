<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class Barangay extends Model implements Transformable
{
    use TransformableTrait;

    protected $fillable = [
		'name',
	];

    public function town()
    {
        return $this->belongsTo(Town::class);
    }
}
