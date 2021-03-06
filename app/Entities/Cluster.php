<?php

namespace App\Entities;

use Prettus\Repository\Traits\TransformableTrait;
use Prettus\Repository\Contracts\Transformable;
use Illuminate\Database\Eloquent\Model;

class Cluster extends Model implements Transformable
{
    use TransformableTrait;

    protected $fillable = [
		'name',
		'precincts',
		'registered_voters',
	];

	protected $casts = [
		'registered_voters' => 'integer'
	];

	protected $attributes = [
		'registered_voters' => 0,
	];

	public function contacts()
	{
		return $this->belongsTo(Contact::class, 'contact_id');
	}

	public function town()
	{
		return $this->belongsTo(Town::class);
	}

	public function polling_place()
	{
		return $this->belongsTo(PollingPlace::class);
	}
}
