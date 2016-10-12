<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers;
use Illuminate\Support\Facades\Input;
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

        // The Fractal parseIncludes() is available to use here
//        $this->response->parseIncludes(Input::get('includes'));
    }
}
