<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use Prettus\Repository\Contracts\Presentable;
use Prettus\Repository\Traits\PresentableTrait;
use App\Mobile;

class Contact extends Model implements Transformable, Presentable
{
    use TransformableTrait, PresentableTrait;

    protected $fillable = [
		'mobile',
		'handle',
	];

	protected $fieldSearchable = [
		'mobile',
		'handle'
	];

	public function groups() {
		return $this->belongsToMany(Group::class);
	}

	public function getMobileAttribute()
	{
		$mobile = $this->attributes['mobile'];

		return $mobile ? Mobile::number($mobile) : null;
	}
}
