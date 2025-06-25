<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $poll->name }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
<div class="container mt-5">
    <h1>{{ $poll->name }}</h1>
    <p>{{ $poll->description }}</p>

    <form id="voteForm" method="POST" action="{{ route('polls.vote') }}">
        @csrf
        <input type="hidden" name="poll_id" value="{{ $poll->id }}">
        <input type="hidden" name="token" value="{{ $pollToken->token }}">

        @foreach ($poll->answerAlternatives as $alt)
            <div class="form-check">
                <input 
                    class="form-check-input" 
                    type="{{ $poll->multiple_answers ? 'checkbox' : 'radio' }}" 
                    name="answers[]" 
                    value="{{ $alt->id }}" 
                    id="alt{{ $alt->id }}"
                    required>
                <label class="form-check-label" for="alt{{ $alt->id }}">
                    {{ $alt->answer }}
                </label>
            </div>
        @endforeach

        <button type="submit" class="btn btn-primary mt-3">Vote</button>
    </form>

<div id="success-message" class="alert alert-success" style="display:none;"></div>

<div id="results" class="mt-5">
    @include('website.partials.results', ['poll' => $poll])
</div>

</div>

<script>
    $(document).ready(function () {
        $('#voteForm').on('submit', function (e) {
            e.preventDefault();

            $.ajax({
                url: $(this).attr('action'),
                method: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    $('#success-message').text(response.message).show();

                    setTimeout(() => {
                        $('#success-message').fadeOut();
                    }, 3000);

                    $('#results').html(response.html);
                    $('#voteForm button[type="submit"]').prop('disabled', true);
                },

                error: function (xhr) {
                    alert(xhr.responseJSON?.message || 'Vote failed');
                }
            });
        });
    });
</script>
</body>
</html>
