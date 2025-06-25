<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Polls</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>
          

@include('layouts.navigation')

<body>
<div class="container mt-5">
    <h1>Polls</h1>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Name</th>
                <th>Description</th>
                <th>Ends At</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        @forelse ($polls as $poll)
            <tr>
                <td>{{ $poll->name }}</td>
                <td>{{ $poll->description }}</td>
                <td>{{ $poll->ends_at ? $poll->ends_at->format('Y-m-d H:i') : 'No limit' }}</td>
                <td>
                    <a href="{{ route('website.polls.show', $poll) }}" class="btn btn-sm btn-primary">Vote</a>
                    <a href="{{ route('website.polls.show', $poll) }}" class="btn btn-sm btn-primary">View</a>

                </td>
            </tr>
        @empty
            <tr>
                <td colspan="4" class="text-center">No polls available</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>
</body>
</html>
