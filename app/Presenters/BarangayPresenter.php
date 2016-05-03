<?php

namespace App\Presenters;

use App\Transformers\BarangayTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class BarangayPresenter
 *
 * @package namespace App\Presenters;
 */
class BarangayPresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new BarangayTransformer();
    }
}
