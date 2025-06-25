<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Poll</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>
          

@include('layouts.navigation')
<body>
<div class="container mt-5">
    <h1>Create Poll</h1>
    <form action="{{ route('admin.polls.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Poll Name *</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Description</label>
            <textarea name="description" class="form-control"></textarea>
        </div>
        <div class="mb-3">
            <label>Answer Alternatives *</label>
      <input type="text" name="answers[]" class="form-control mb-2" placeholder="Alternative 1" required>
<button type="button" onclick="addAlternative()">Add another</button>
<div id="alternatives"></div>
        </div>
        <div class="mb-3 d-flex align-items-center gap-3">
        <div class="form-check form-check-inline">
            <input type="checkbox" name="multiple_answers" class="form-check-input" id="multi">
            <label class="form-check-label" for="multi">Multiple Answers</label>
        </div>
        
        <div class="form-check form-check-inline">
            <input type="checkbox" name="only_unique" class="form-check-input" id="unique">
            <label class="form-check-label" for="unique">Only Unique</label>
        </div>
            </div>
        <div class="mb-3">
            <label>Ends At</label>
            <input type="datetime-local" name="ends_at" class="form-control">
        </div>
      
        <div class="mb-3">
            <label>Status</label>
            <select name="active" class="form-select">
                <option value="1">Active</option>
                <option value="0">Non-Active</option>
            </select>
        </div>
        <button class="btn btn-primary">Create Poll</button>
    </form>
</div>

<script>
function addAlternative() {
    const div = document.createElement('div');
    div.innerHTML = `<input type="text" name="answers[]" class="form-control mb-2" placeholder="Another alternative" required>`;
    document.getElementById('alternatives').appendChild(div);
}
document.addEventListener('DOMContentLoaded', function() {
  const multipleCheckbox = document.getElementById('multi');
  const uniqueCheckbox = document.getElementById('unique');

  multipleCheckbox.addEventListener('change', function() {
    if (this.checked) {
      uniqueCheckbox.checked = false;
    }
  });

  uniqueCheckbox.addEventListener('change', function() {
    if (this.checked) {
      multipleCheckbox.checked = false;
    }
  });
});
</script>
</body>
</html>
