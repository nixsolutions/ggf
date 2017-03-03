<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers;
use Sorskod\Larasponse\Larasponse;

/**
 * Class Controller
 * @package App\Http\Controllers\API
 */
abstract class Controller extends Controllers\Controller
{
    /**
     * @var Larasponse
     */
    protected $response;

    /**
     * Controller constructor.
     * @param Larasponse $response
     */
    public function __construct(Larasponse $response)
    {
        $this->response = $response;
    }
}
