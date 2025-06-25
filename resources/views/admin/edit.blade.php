<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Poll</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>
          

@include('layouts.navigation')
<body>
<div class="container mt-5">
    <h1>Edit Poll</h1>

    <form action="{{ route('admin.polls.update', $poll) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Poll Name *</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $poll->name) }}" required>
        </div>

        <div class="mb-3">
            <label>Description</label>
            <textarea name="description" class="form-control">{{ old('description', $poll->description) }}</textarea>
        </div>

        <div class="mb-3">
            <label>Answer Alternatives *</label>
            @foreach ($poll->answerAlternatives as $alt)
                <input type="text" name="answers[]" class="form-control mb-2" value="{{ $alt->answer }}" required>
            @endforeach
            <button type="button" onclick="addAlternative()">Add another</button>
            <div id="alternatives"></div>
        </div>

        <div class="form-check mb-3">
            <input type="checkbox" name="multiple_answers" class="form-check-input" id="multi" {{ $poll->multiple_answers ? 'checked' : '' }}>
            <label class="form-check-label" for="multi">Multiple Answers</label>
        </div>

        <div class="mb-3">
            <label>Ends At</label>
            <input type="datetime-local" name="ends_at" class="form-control"
                   value="{{ old('ends_at', $poll->ends_at ? \Carbon\Carbon::parse($poll->ends_at)->format('Y-m-d\TH:i') : '') }}">
        </div>

        <div class="form-check mb-3">
            <input type="checkbox" name="only_unique" class="form-check-input" id="unique" {{ $poll->only_unique ? 'checked' : '' }}>
            <label class="form-check-label" for="unique">Only Unique</label>
        </div>

        <div class="mb-3">
            <label>Status</label>
<select name="active" class="form-select">
<option value="1" {{ $poll->active ? 'selected' : '' }}>Active</option>
<option value="0" {{ !$poll->active ? 'selected' : '' }}>Non-Active</option>

</select>

        </div>

        <button class="btn btn-primary">Update Poll</button>
    </form>
</div>

<script>
function addAlternative() {
    const div = document.createElement('div');
    div.innerHTML = `<input type="text" name="answers[]" class="form-control mb-2" placeholder="Another alternative" required>`;
    document.getElementById('alternatives').appendChild(div);
}
</script>
</body>
</html>
