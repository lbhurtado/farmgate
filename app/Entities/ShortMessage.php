<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use Prettus\Repository\Contracts\Presentable;
use Prettus\Repository\Traits\PresentableTrait;

class ShortMessage extends Model implements Transformable, Presentable
{
    use TransformableTrait, PresentableTrait;

	protected $table = 'shortmessages';

    protected $fillable = [
		'from',
		'to',
		'message',
	];

}
