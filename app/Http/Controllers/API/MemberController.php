<?php

namespace App\Http\Controllers\API;

use Illuminate\Support\Facades\Auth;

class MemberController extends Controller
{
    public function current()
    {
        return response()->json(Auth::user());
    }
}
