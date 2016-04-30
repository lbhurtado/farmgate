<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Entities\ElectivePosition;

/**
 * Class ElectivePositionTransformer
 * @package namespace App\Transformers;
 */
class ElectivePositionTransformer extends TransformerAbstract
{

    /**
     * Transform the \ElectivePosition entity
     * @param \ElectivePosition $model
     *
     * @return array
     */
    public function transform(ElectivePosition $model)
    {
        return [
            'id'         => (int) $model->id,

            /* place your other model properties here */

            'created_at' => $model->created_at,
            'updated_at' => $model->updated_at
        ];
    }
}
