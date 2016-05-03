<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Entities\PollingPlace;

/**
 * Class PollingPlaceTransformer
 * @package namespace App\Transformers;
 */
class PollingPlaceTransformer extends TransformerAbstract
{

    /**
     * Transform the \PollingPlace entity
     * @param \PollingPlace $model
     *
     * @return array
     */
    public function transform(PollingPlace $model)
    {
        return [
            'id'         => (int) $model->id,
            'name'       => $model->name,
            'created_at' => $model->created_at,
            'updated_at' => $model->updated_at
        ];
    }
}
