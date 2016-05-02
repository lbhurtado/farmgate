<?php

namespace App\Presenters;

use App\Transformers\TownTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class TownPresenter
 *
 * @package namespace App\Presenters;
 */
class TownPresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new TownTransformer();
    }
}
