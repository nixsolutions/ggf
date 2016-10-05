<?php

namespace App\Http\Controllers\API;

use App\Member;
use App\TeamMember;
use App\Transformers\MemberSearchTransformer;
use App\Transformers\MemberTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MemberController extends Controller
{
    public function current()
    {
        return response()->json(Auth::user());
    }
}
