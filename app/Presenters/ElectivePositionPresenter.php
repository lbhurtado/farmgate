<?php

namespace App\Presenters;

use App\Transformers\ElectivePositionTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class ElectivePositionPresenter
 *
 * @package namespace App\Presenters;
 */
class ElectivePositionPresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new ElectivePositionTransformer();
    }
}
