<?php

namespace App\Entities;

use Prettus\Repository\Traits\TransformableTrait;
use Prettus\Repository\Traits\PresentableTrait;
use Prettus\Repository\Contracts\Transformable;
use App\Events\GroupMembershipsWereProcessed;
use Prettus\Repository\Contracts\Presentable;
use Illuminate\Database\Eloquent\Model;
use App\Repositories\TokenRepository;
use App\Entities\Cluster;
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

	public function groups()
	{
		return $this->belongsToMany(Group::class);
	}

	public function cluster()
	{
		return $this->hasOne(Cluster::class);
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

		if ($related)
		{
			if (get_class($related) == Group::class)
			{
				event(new GroupMembershipsWereProcessed($related));
			}
		}

		return $related;
	}
}
