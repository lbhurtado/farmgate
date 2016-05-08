<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Entities\District;

/**
 * Class DistrictTransformer
 * @package namespace App\Transformers;
 */
class DistrictTransformer extends TransformerAbstract
{

    /**
     * Transform the \District entity
     * @param \District $model
     *
     * @return array
     */
    public function transform(District $model)
    {
        return [
            'id'         => (int) $model->id,
            'name'       => $model->name,
            'created_at' => $model->created_at,
            'updated_at' => $model->updated_at
        ];
    }
}
