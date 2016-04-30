<?php

namespace App\Entities;

use Prettus\Repository\Traits\TransformableTrait;
use Prettus\Repository\Contracts\Transformable;
use Illuminate\Database\Eloquent\Model;
use App\Entities\Candidate;
use App\Entities\Cluster;

class ElectionResult extends Model implements Transformable
{
    use TransformableTrait;

    protected $fillable = [
		'votes',
	];

    protected $casts = [
        'votes' => 'integer'
    ];

    public function candidate()
    {
        return $this->belongsTo(Candidate::class);
    }

    public function cluster()
    {
        return $this->belongsTo(Cluster::class);
    }
}
