<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Entities\Contact;
use libphonenumber\PhoneNumberFormat;

/**
 * Class ContactTransformer
 * @package namespace App\Transformers;
 */
class ContactTransformer extends TransformerAbstract
{

    /**
     * Transform the Contact entity
     * @param Contact $model
     *
     * @return array
     */
    public function transform(Contact $model)
    {
        return [
            'id'         => (int) $model->id,
            'mobile'     => phone_format($model->mobile, 'PH', PhoneNumberFormat::E164),
            'handle'     => $model->handle,
            'created_at' => $model->created_at,
            'updated_at' => $model->updated_at
        ];
    }
}
