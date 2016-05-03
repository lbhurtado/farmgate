<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Entities\Barangay;

/**
 * Class BarangayTransformer
 * @package namespace App\Transformers;
 */
class BarangayTransformer extends TransformerAbstract
{

    /**
     * Transform the \Barangay entity
     * @param \Barangay $model
     *
     * @return array
     */
    public function transform(Barangay $model)
    {
        return [
            'id'         => (int) $model->id,
            'name'       => $model->name,
            'created_at' => $model->created_at,
            'updated_at' => $model->updated_at
        ];
    }
}
