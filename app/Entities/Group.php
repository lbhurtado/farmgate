<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use Prettus\Repository\Contracts\Presentable;
use Prettus\Repository\Traits\PresentableTrait;

class Group extends Model implements Transformable, Presentable
{
    use TransformableTrait, PresentableTrait;

    protected $fillable = [
		'name',
	];

    protected $fieldSearchable = [
        'name',
    ];

    function contacts() {
        return $this->belongsToMany(Contact::class);
    }
}
