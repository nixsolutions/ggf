<?php

namespace App\Http\Controllers\API;

use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function ping()
    {
        return response()->json(['isAuthenticated' => Auth::check()]);
    }
}
