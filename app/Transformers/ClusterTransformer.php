<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Entities\Cluster;

/**
 * Class ClusterTransformer
 * @package namespace App\Transformers;
 */
class ClusterTransformer extends TransformerAbstract
{

    /**
     * Transform the \Cluster entity
     * @param \Cluster $model
     *
     * @return array
     */
    public function transform(Cluster $model)
    {
        return [
            'id'                => (int) $model->id,
            'name'              => $model->name,
            'precincts'         => $model->precincts,
            'registered_voters' => (int) $model->registered_voters,
            'created_at'        => $model->created_at,
            'updated_at'        => $model->updated_at
        ];
    }
}
