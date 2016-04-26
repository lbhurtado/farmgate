<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use Prettus\Repository\Contracts\Presentable;
use Prettus\Repository\Traits\PresentableTrait;

define('INCOMING', -1);
define('OUTGOING',  1);

class ShortMessage extends Model implements Transformable, Presentable
{
    use TransformableTrait, PresentableTrait;

//	protected $table = 'short_messages';

	protected $attributes = [
		'direction' => INCOMING
	];

    protected $fillable = [
		'from',
		'to',
		'message',
		'direction'
	];

	public function getMobile()
	{
		return ($this->direction == INCOMING ? $this->from : $this->to);
	}

	public function getHandle()
	{
		return $this->getMobile();
	}
}
