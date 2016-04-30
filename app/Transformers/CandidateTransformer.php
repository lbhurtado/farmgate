<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Entities\Candidate;

/**
 * Class CandidateTransformer
 * @package namespace App\Transformers;
 */
class CandidateTransformer extends TransformerAbstract
{

    /**
     * Transform the \Candidate entity
     * @param \Candidate $model
     *
     * @return array
     */
    public function transform(Candidate $model)
    {
        return [
            'id'         => (int) $model->id,
            'name'       => $model->name,
            'alias'      => $model->alias,
            'created_at' => $model->created_at,
            'updated_at' => $model->updated_at
        ];
    }
}
