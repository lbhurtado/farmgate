<?php

namespace App\Presenters;

use App\Transformers\ElectionResultTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class ElectionResultPresenter
 *
 * @package namespace App\Presenters;
 */
class ElectionResultPresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new ElectionResultTransformer();
    }
}
