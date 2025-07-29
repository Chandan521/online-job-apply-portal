@extends('admin.layout.app')

@section('title', 'User Reports')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">User Reports</h1>

    <div class="card shadow-sm">
        <div class="card-body table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Reported User</th>
                        <th>Reporter</th>
                        <th>Reason</th>
                        <th>Details</th>
                        <th>Reported At</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($reports as $report)
                    <tr>
                        <td>
                            <strong>{{ $report->toUser->name }}</strong><br>
                            <small>{{ $report->toUser->email }}</small>
                        </td>
                        <td>
                            <strong>{{ $report->fromUser->name }}</strong><br>
                            <small>{{ $report->fromUser->email }}</small>
                        </td>
                        <td><span class="badge bg-warning">{{ $report->reason }}</span></td>
                        <td>{{ Str::limit($report->details, 100) }}</td>
                        <td>{{ $report->created_at->format('d M Y, h:i A') }}</td>
                        <td class="text-end">
                            <a href="{{ route('admin.users.show', $report->toUser->id) }}" class="btn btn-sm btn-info">View User</a>
                            <a href="{{ route('admin.users.show', $report->fromUser->id) }}" class="btn btn-sm btn-secondary">View Reporter</a>
                            <form action="{{ route('admin.reports.ban', $report->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Ban this user IP?');">
                                @csrf
                                <button class="btn btn-sm btn-danger">Ban User</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">No reports found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
