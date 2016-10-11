<?php

namespace App\Http\Controllers;

//use Illuminate\Contracts\Auth\Guard;
use App\Auth\Guard;

/**
 * Class AuthController
 * @package App\Http\Controllers
 */
class AuthController extends Controller
{

    /**
     * @param Guard $auth
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Guard $auth)
    {
        $auth->logout();

        return response()->json(['status' => 200]);
    }
}
