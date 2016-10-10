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
     * @return \Illuminate\Http\JsonResponse
     */
    public function current()
    {
        return response()->json(Auth::user());
    }
}
