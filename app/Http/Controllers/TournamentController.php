<?php

namespace App\Http\Controllers;

use App\Tournament;
use Illuminate\Support\Facades\Request;

/**
 * Class TournamentController
 * @package App\Http\Controllers
 */
class TournamentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     */
    public function index()
    {
        $tournaments = Tournament::all();

        return view('tournament.index', compact('tournaments'));
    }

    /**
     * Store a newly created resource in storage.
     *
     */
    public function store()
    {
        Tournament::create(Request::all());

        return redirect('tournament');
    }
}
