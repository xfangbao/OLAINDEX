<?php

namespace App\Transformers;

use App\Models\Authorization;
use League\Fractal\TransformerAbstract;

class AuthorizationTransformer extends TransformerAbstract
{
    /**
     * @param Authorization $authorization
     * @return array
     * @throws \Exception
     */
    public function transform(Authorization $authorization)
    {
        return $authorization->toArray();
    }
}
