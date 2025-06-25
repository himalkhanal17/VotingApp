<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VoteController extends Controller
{
    // Show poll voting form
    public function show(Poll $poll)
    {
        return view('votes.show', compact('poll'));
    }

    public function vote(Request $request, Poll $poll)
{
    // Your existing vote logic here (store votes)...

    $poll->load('answerAlternatives.votes'); // reload with updated votes

    $resultsHtml = view('website.partials.results', ['poll' => $poll])->render();

    return response()->json(['html' => $resultsHtml]);
}
    // Handle vote submission
    public function store(Request $request, Poll $poll)
    {
        $validated = $request->validate([
            'answers' => 'required|array|min:1',
            'answers.*' => 'exists:answer_alternatives,id',
            'email' => 'nullable|email|required_if:unique_required,true',
        ]);

        $voter = null;

        if ($poll->only_unique) {
            $voter = Voter::firstOrCreate(['email' => $validated['email']]);
            $voter->update(['last_voted_at' => now()]);
        }

        foreach ($validated['answers'] as $answerId) {
            Vote::create([
                'poll_id' => $poll->id,
                'answer_alternative_id' => $answerId,
                'voter_id' => $voter ? $voter->id : null,
            ]);
        }

        // Optional: Dispatch queued email job here to send results later

        return redirect()->route('polls.show', $poll)->with('success', 'Thanks for voting!');
    }
}
