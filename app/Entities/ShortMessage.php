<?php

namespace App\Entities;

use Prettus\Repository\Traits\TransformableTrait;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\PresentableTrait;
use Prettus\Repository\Contracts\Presentable;
use Illuminate\Database\Eloquent\Model;
use App\Entities\Contact;
use App\Instruction;

define('INCOMING', -1);
define('OUTGOING',  1);

class ShortMessage extends Model implements Transformable, Presentable
{
    use TransformableTrait, PresentableTrait;

	protected $instruction;

	public static $keywords = [
		'REGISTER' => 'reg',
		'POLL' => 'poll',
	];

	protected $attributes = [
		'direction' => INCOMING,
	];

    protected $fillable = [
		'from',
		'to',
		'message',
		'direction'
	];

	protected $appends = [
		'mobile',
		'keyword'
	];

	public function __construct($attributes = [])
	{
		parent::__construct($attributes);

		$this->instruction = Instruction::create($this);
	}

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

	public function getInstruction()
	{
		return $this->instruction;
	}

	public function getKeywordAttribute()
	{
		return $this->getInstruction()->getKeyword();
	}
}
