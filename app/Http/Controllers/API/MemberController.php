<?php

namespace App\Http\Controllers\API;

use Illuminate\Support\Facades\Auth;

/**
 * Class MemberController
 * @package App\Http\Controllers\API
 */
class MemberController extends Controller
{
    /**
     * @SWG\Get(
     *     path="/api/v1/me",
     *     description="Returns authenticated user",
     *     operationId="current",
     *     produces={"application/json"},
     *     @SWG\Response(
     *     response="200",
     *     description="Successfully get authenticated user"
     *     )
     * )
     */
    public function current()
    {
        return response()->json(Auth::user());
    }
}
