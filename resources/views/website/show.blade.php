<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>{{ $poll->name }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

@include('layouts.navigation')

<body>
<div class="container mt-5">
    <h1>{{ $poll->name }}</h1>
    <p>{{ $poll->description }}</p>

    @if (session('message'))
        <div class="alert alert-success">{{ session('message') }}</div>
    @endif

    @if ($poll->ends_at)
        <p><strong>Ends at:</strong> {{ $poll->ends_at->format('Y-m-d H:i') }}</p>
        <div id="countdown" class="mb-3"></div>
    @endif

    @if ($poll->only_unique)
        {{-- Email input for token --}}
        <form method="POST" action="{{ route('polls.request-vote-token', $poll->id) }}" class="mb-4">
            @csrf
            <div class="mb-3">
                <input type="email" name="email" class="form-control" required placeholder="Enter your email to get voting link">
            </div>
            <button type="submit" class="btn btn-outline-primary">Send Voting Link</button>
        </form>
        <p>We’ll send you a secure link to vote. Token is valid for 5 minutes.</p>
    @else
        {{-- Direct vote form --}}
        <form id="voteForm" method="POST" action="{{ route('polls.vote') }}">
            @csrf
            <input type="hidden" name="poll_id" value="{{ $poll->id }}">
            @foreach ($poll->answerAlternatives as $alternative)
                <div class="form-check">
                    <input 
                        class="form-check-input" 
                        type="{{ $poll->multiple_answers ? 'checkbox' : 'radio' }}" 
                        name="answers[]" 
                        value="{{ $alternative->id }}" 
                        id="alt{{ $alternative->id }}"
                        required
                    >
                    <label class="form-check-label" for="alt{{ $alternative->id }}">
                        {{ $alternative->answer }}
                    </label>
                </div>
            @endforeach
            <button type="submit" class="btn btn-primary mt-3">Submit Vote</button>
        </form>
    @endif

    <div id="results" class="mt-4"></div>
    <div id="results" class="mt-5">
    @include('website.partials.results', ['poll' => $poll])
</div>
</div>

@if ($poll->ends_at)
<script>
    let endTime = new Date("{{ $poll->ends_at->toIso8601String() }}").getTime();
    let timer = setInterval(function () {
        let now = new Date().getTime();
        let distance = endTime - now;
        let el = document.getElementById("countdown");

        if (distance < 0) {
            clearInterval(timer);
            el.innerHTML = "Poll ended";
            document.getElementById("voteForm")?.querySelector('button[type="submit"]')?.setAttribute("disabled", true);
        } else {
            let minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            let seconds = Math.floor((distance % (1000 * 60)) / 1000);
            el.innerHTML = `Time left: ${minutes}m ${seconds}s`;
        }
    }, 1000);
</script>
@endif

<script>
$(document).ready(function () {
    $('#voteForm').submit(function (e) {
        e.preventDefault();
        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: $(this).serialize(),
            success: function (response) {
                $('#results').html('<div class="alert alert-success">Vote recorded!</div>');
                $('#voteForm button[type="submit"]').prop('disabled', true);
            },
            error: function (xhr) {
                alert('Error: ' + xhr.responseJSON?.message || xhr.statusText);
            }
        });
    });
});
</script>
</body>
</html>
