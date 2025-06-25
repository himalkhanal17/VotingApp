<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Poll;
use App\Models\PollToken;
use App\Models\AnswerAlternative;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\UniqueVoteTokenMail;

class PollController extends Controller
{
    // List polls (Admin & public list could be separated if needed)
    public function index()
    {
        $polls = Poll::orderBy('created_at', 'desc')->get();
        return view('admin.index', compact('polls'));
    }

    // Show form to create a poll (admin)
    public function create()
    {
        return view('admin.create');
    }

    public function store(Request $request)
{
    $request->merge([
        'multiple_answers' => $request->has('multiple_answers'),
        'only_unique' => $request->has('only_unique'),
        'active' => $request->has('active') ? $request->active : true,
    ]);

    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'multiple_answers' => 'boolean',
        'ends_at' => 'nullable|date',
        'only_unique' => 'boolean',
        'active' => 'boolean',
        'answers' => 'required|array|min:1',
        'answers.*' => 'required|string|max:255',
    ]);

    $poll = Poll::create([
        'name' => $validated['name'],
        'description' => $validated['description'] ?? null,
        'multiple_answers' => $validated['multiple_answers'] ?? false,
        'ends_at' => $validated['ends_at'] ?? null,
        'only_unique' => $validated['only_unique'] ?? false,
        'active' => $validated['active'] ?? true,
    ]);

foreach ($validated['answers'] as $answerText) {
    $poll->answerAlternatives()->create(['answer' => $answerText]);
}


    return redirect()->route('admin.polls.index')->with('success', 'Poll created successfully!');
}

    // Show poll details (admin)
    public function show(Poll $poll)
    {
        $poll->load('answerAlternatives', 'votes');
        return view('admin.show', compact('poll'));
    }

    // Request unique email token (public)
    public function requestToken(Request $request, Poll $poll)
    {
        $request->validate(['email' => 'required|email']);
        $token = Str::random(32);

        PollToken::create([
            'poll_id' => $poll->id,
            'email' => $request->email,
            'token' => $token,
            'expires_at' => now()->addMinutes(5),
        ]);

        Mail::to($request->email)->queue(new UniqueVoteTokenMail($poll, $token));

        return back()->with('status', 'Check your email for the voting link.');
    }

    // Show voting form via token
    public function voteForm(Request $request, $token)
    {
        $pollToken = PollToken::where('token', $token)->firstOrFail();

        if ($pollToken->isExpired()) {
            abort(403, 'Token expired');
        }

        $poll = $pollToken->poll()->with('answerAlternatives')->first();

        return view('website.vote', compact('poll', 'pollToken'));
    }

    // Store vote (public)
    public function storeVote(Request $request, Poll $poll)
    {
        $request->validate([
            'answers' => 'required|array|min:1',
            'answers.*' => 'exists:answer_alternatives,id',
        ]);

        // TODO: Validate token if only_unique
        // TODO: Check if poll ended

        foreach ($request->answers as $answerId) {
            $poll->votes()->create([
                'answer_alternative_id' => $answerId,
                'email' => $request->user_email ?? null,
            ]);
        }

        // Optionally queue email, broadcast update, etc.

        return response()->json(['success' => true, 'message' => 'Vote recorded!']);
    }

    // Stop a poll (admin)
    public function stop(Poll $poll)
    {
        $poll->update(['active' => false]);
        return back()->with('status', 'Poll stopped successfully.');
    }
    public function edit(Poll $poll)
{
    $poll->load('answerAlternatives');

    return view('admin.edit', compact('poll'));
}
public function update(Request $request, Poll $poll)
{
    $request->merge([
        'multiple_answers' => $request->has('multiple_answers'),
        'only_unique' => $request->has('only_unique'),
    ]);

    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'multiple_answers' => 'boolean',
        'ends_at' => 'nullable|date',
        'only_unique' => 'boolean',
        'active' => 'required|in:0,1',
        'answers' => 'required|array|min:1',
        'answers.*' => 'required|string|max:255',
    ]);

    $poll->update([
        'name' => $validated['name'],
        'description' => $validated['description'] ?? null,
        'multiple_answers' => $validated['multiple_answers'] ?? false,
        'ends_at' => $validated['ends_at'] ?? null,
        'only_unique' => $validated['only_unique'] ?? false,
        'active' => $request->input('active') == '1',
    ]);

    $poll->answerAlternatives()->delete();
    foreach ($validated['answers'] as $text) {
        $poll->answerAlternatives()->create(['answer' => $text]);
    }

    return redirect()->route('admin.polls.index')->with('success', 'Poll updated successfully!');
}
public function destroy(Poll $poll)
{
    $poll->delete();

    return redirect()->route('admin.polls.index')->with('success', 'Poll deleted successfully!');
}

}