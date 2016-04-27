<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use Prettus\Repository\Contracts\Presentable;
use Prettus\Repository\Traits\PresentableTrait;
use App\Entities\Contact;

define('INCOMING', -1);
define('OUTGOING',  1);

class ShortMessage extends Model implements Transformable, Presentable
{
    use TransformableTrait, PresentableTrait;

	protected $attributes = [
		'direction' => INCOMING,
	];

    protected $fillable = [
		'from',
		'to',
		'message',
		'direction'
	];

	protected $appends = ['mobile'];

	public static function getSignificantMobile(Array $attributes)
	{
		return $attributes['direction'] == INCOMING ? $attributes['from'] : $attributes['to'];
	}

	public function getMobileAttribute()
	{
		return self::getSignificantMobile($this->attributes);
	}

	public function contact()
	{
		return $this->belongsTo(Contact::class, 'from', 'mobile');
	}
}
