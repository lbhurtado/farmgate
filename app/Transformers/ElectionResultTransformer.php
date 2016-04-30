<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Entities\ElectionResult;

/**
 * Class ElectionResultTransformer
 * @package namespace App\Transformers;
 */
class ElectionResultTransformer extends TransformerAbstract
{

    /**
     * Transform the \ElectionResult entity
     * @param \ElectionResult $model
     *
     * @return array
     */
    public function transform(ElectionResult $model)
    {
        return [
            'id'         => (int) $model->id,
            'votes'      => (int) $model->votes,
            'created_at' => $model->created_at,
            'updated_at' => $model->updated_at,
            'candidate'  => $model->candidate->transform(),
            'cluster'    => $model->cluster->transform(),
        ];
    }
}
