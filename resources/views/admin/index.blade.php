<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin - Polls</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>
          

@include('layouts.navigation')
<body>
<div class="container mt-5">
    <h1>Admin - Polls</h1>
    <a href="{{ route('admin.polls.create') }}" class="btn btn-primary mb-3">Create New Poll</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Name</th>
                <th>Active</th>
                <th>Ends At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        @forelse ($polls as $poll)
            <tr>
                <td>{{ $poll->name }}</td>
                <td>{{ $poll->active ? 'Yes' : 'No' }}</td>
                <td>{{ $poll->ends_at ? $poll->ends_at->format('Y-m-d H:i') : 'No limit' }}</td>
                <td>
                    <a href="{{ route('admin.polls.show', $poll) }}" class="btn btn-sm btn-info">View</a>
                    <a href="{{ route('admin.polls.edit', $poll) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('admin.polls.destroy', $poll) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger">Delete</button>
                    </form>
                    @if($poll->active)
                        <form action="{{ route('admin.polls.stop', $poll) }}" method="POST" style="display:inline;">
                            @csrf
                            <button class="btn btn-sm btn-secondary">Stop</button>
                        </form>
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="4" class="text-center">No polls created</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>
</body>
</html>
