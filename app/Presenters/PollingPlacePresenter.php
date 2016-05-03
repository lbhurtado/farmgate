<?php

namespace App\Presenters;

use App\Transformers\PollingPlaceTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class PollingPlacePresenter
 *
 * @package namespace App\Presenters;
 */
class PollingPlacePresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new PollingPlaceTransformer();
    }
}
