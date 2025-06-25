<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $poll->name }} - Admin View</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>
          

@include('layouts.navigation')
<body>
<div class="container mt-5">
    <h1>{{ $poll->name }}</h1>
    <p>{{ $poll->description }}</p>
    <p><strong>Ends At:</strong> {{ $poll->ends_at ?? 'No limit' }}</p>
    <p><strong>Active:</strong> {{ $poll->active ? 'Yes' : 'No' }}</p>

<h3>Results</h3>
<ul class="list-group mb-3">
    @foreach ($poll->answerAlternatives as $alt)
        <li class="list-group-item d-flex justify-content-between align-items-center">
            {{ $alt->answer }}
            <span class="badge bg-primary rounded-pill">{{ $alt->votes_count ?? 0 }} votes</span>
        </li>
    @endforeach
</ul>


    <h3>Voters</h3>
    <ul class="list-group">
        @foreach ($poll->voters as $voter)
            <li class="list-group-item">
                {{ $voter->email }} - {{ $voter->pivot->created_at }}
            </li>
        @endforeach
    </ul>

    <a href="{{ route('admin.polls.index') }}" class="btn btn-secondary mt-3">Back</a>
</div>
</body>
</html>
