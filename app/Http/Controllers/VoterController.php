<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VoterController extends Controller
{
    // List of voters
    public function index()
    {
        $voters = Voter::withCount('votes')->orderBy('last_voted_at', 'desc')->get();
        return view('voters.index', compact('voters'));
    }

    // Show voter details
    public function show(Voter $voter)
    {
        $voter->load('votes.poll');
        return view('voters.show', compact('voter'));
    }
}
