<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Models\Poll;
use App\Models\PollToken;
use App\Models\VoteToken;
use App\Mail\VoteTokenMail;

class PollController extends Controller
{
    public function index()
    {
        $polls = Poll::where('active', true)->latest()->get();
        return view('website.index', compact('polls'));
    }

    public function show(Poll $poll)
    {
        $poll->load('answerAlternatives');
        return view('website.show', compact('poll'));
    }

    public function voteForm(Request $request, $token)
    {
        $pollToken = VoteToken::where('token', $token)->firstOrFail();

        if ($pollToken->isExpired()) {
            abort(403, 'Token expired');
        }

        $pollToken->load('poll.answerAlternatives');

        return view('website.vote', [
            'poll' => $pollToken->poll,
            'pollToken' => $pollToken,
        ]);
    }

  public function vote(Request $request)
{
    $request->validate([
        'answers' => 'required|array',
        'answers.*' => 'exists:answer_alternatives,id',
        'token' => 'required|string',
        'poll_id' => 'required|exists:polls,id',
    ]);

    $poll = Poll::with('answerAlternatives.votes')->findOrFail($request->poll_id);

    $pollToken = VoteToken::where('token', $request->token)->firstOrFail();

    if ($pollToken->isExpired()) {
        return response()->json(['message' => 'Token expired'], 403);
    }

    $voter = \App\Models\Voter::firstOrCreate(['email' => $pollToken->email]);

    foreach ($request->answers as $answerId) {
        $poll->votes()->create([
            'answer_alternative_id' => $answerId,
            'voter_id' => $voter->id,
        ]);
    }

    $poll->load('answerAlternatives.votes');

    $html = view('website.partials.results', compact('poll'))->render();

    return response()->json([
        'message' => 'Vote recorded successfully',
        'html' => $html,
    ]);
}




    public function requestToken(Request $request, Poll $poll)
    {
        $request->validate(['email' => 'required|email']);

        $token = Str::uuid();
        VoteToken::create([
            'email' => $request->email,
            'poll_id' => $poll->id,
            'token' => $token,
            'expires_at' => now()->addMinutes(5),
        ]);

        Mail::to($request->email)->send(new VoteTokenMail($poll, $token));

        return back()->with('message', 'A unique voting link has been sent to your email.');
    }
}
