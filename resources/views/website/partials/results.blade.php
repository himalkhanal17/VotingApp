<h4 class="mt-4">Results</h4>
@php
    $totalVotes = $poll->votes->count();
@endphp

@foreach ($poll->answerAlternatives as $alt)
    @php
        $count = $alt->votes->count();
        $percentage = $totalVotes > 0 ? round(($count / $totalVotes) * 100) : 0;
    @endphp
    <div class="mb-2">
        <strong>{{ $alt->answer }}</strong>
        <div class="progress">
            <div class="progress-bar" 
                 role="progressbar" 
                 style="width: {{ $percentage }}%" 
                 aria-valuenow="{{ $percentage }}" 
                 aria-valuemin="0" 
                 aria-valuemax="100">
                {{ $percentage }}%
            </div>
        </div>
        <small>{{ $count }} votes</small>
    </div>
@endforeach
