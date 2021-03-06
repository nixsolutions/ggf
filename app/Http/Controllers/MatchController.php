<?php

namespace App\Http\Controllers;

use App\Match;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;

/**
 * Class MatchController
 * @package App\Http\Controllers
 */
class MatchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $matches = Match::with(['homeTournamentTeam.team', 'awayTournamentTeam.team'])->get();

        return view('match.index', compact('matches'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        Match::create(Request::all());

        return redirect('match');
    }
}
