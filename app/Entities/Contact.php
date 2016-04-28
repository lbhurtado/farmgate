<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use Prettus\Repository\Contracts\Presentable;
use Prettus\Repository\Traits\PresentableTrait;
use App\Mobile;
use App\Repositories\TokenRepository;

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

	public function short_messages()
	{
		return $this->hasMany(ShortMessage::class, 'from', 'mobile');
	}

	public function claimToken($code)
	{
		$tokens = \App::make(TokenRepository::class)->skipPresenter();
		$related = $tokens->claim($this, $code);
		$related->members()->attach($this);

		return $related;
	}
}
