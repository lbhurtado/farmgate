<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class Token extends Model implements Transformable
{
    use TransformableTrait, SoftDeletes;

	private $object;

    protected $fillable = [
		'code',
		'class',
		'reference',
	];

	protected $casts = [
		'reference' => 'integer'
	];

	protected $dates = ['deleted_at'];

	public function claimer()
	{
		return $this->belongsTo(Contact::class);
	}

	/**
	 * Automates the association of contacts
	 *
	 * @param Contact $contact
	 * @return $this
     */
	public function claimed_by(Contact $contact)
	{
		$this->claimer()->associate($contact);

		return $this;
	}

	/**
	 * Instantiate the class
	 * when given the id
	 *
	 * @return $this
	 */
	public function conjureObject()
	{
		$this->object = \App::make($this->class)->find($this->reference);

		return $this;
	}

	public function getObject()
	{
		return $this->object;
	}
}
