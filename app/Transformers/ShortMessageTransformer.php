<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Entities\ShortMessage;
use libphonenumber\PhoneNumberFormat;
/**
 * Class ShortMessageTransformer
 * @package namespace App\Transformers;
 */
class ShortMessageTransformer extends TransformerAbstract
{

    /**
     * Transform the \ShortMessage entity
     * @param \ShortMessage $model
     *
     * @return array
     */
    public function transform(ShortMessage $model)
    {
        return [
            'id'         => (int) $model->id,

            'to'         => phone_format($model->to,   'PH', PhoneNumberFormat::E164),
            'from'       => phone_format($model->from, 'PH', PhoneNumberFormat::E164),
            'message'    => $model->message,
            /* place your other model properties here */

            'created_at' => $model->created_at,
            'updated_at' => $model->updated_at
        ];
    }
}
