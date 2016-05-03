<?php

namespace App\Entities;

use Prettus\Repository\Traits\TransformableTrait;
use Prettus\Repository\Contracts\Transformable;
use Illuminate\Database\Eloquent\Model;
use App\Entities\Barangay;

class PollingPlace extends Model implements Transformable
{
    use TransformableTrait;

    protected $fillable = [
		'name',
	];

    public function barangay()
    {
        return $this->belongsTo(Barangay::class);
    }
}
