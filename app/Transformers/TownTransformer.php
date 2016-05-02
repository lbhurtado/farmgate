<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Entities\Town;

/**
 * Class TownTransformer
 * @package namespace App\Transformers;
 */
class TownTransformer extends TransformerAbstract
{

    /**
     * Transform the \Town entity
     * @param \Town $model
     *
     * @return array
     */
    public function transform(Town $model)
    {
        return [
            'id'         => (int) $model->id,
            'name'       => $model->name,
            'created_at' => $model->created_at,
            'updated_at' => $model->updated_at
        ];
    }
}
