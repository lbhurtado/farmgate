<?php

namespace App\Presenters;

use App\Transformers\ClusterTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class ClusterPresenter
 *
 * @package namespace App\Presenters;
 */
class ClusterPresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new ClusterTransformer();
    }
}
