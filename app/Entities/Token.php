<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class Token extends Model implements Transformable
{
    use TransformableTrait, SoftDeletes;

    protected $fillable = [
		'code',
		'class',
		'reference',
	];

	protected $casts = [
		'reference' => 'integer'
	];

	protected $dates = ['deleted_at'];

	public function claimed_by()
	{
		return $this->belongsTo(Contact::class);
	}
}
