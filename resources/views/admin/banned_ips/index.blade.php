@extends('admin.layout.app')

@section('title', 'Banned IPs')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4">Banned IP Addresses</h1>

    <div class="card shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.banned_ips.store') }}" class="row g-3 mb-4">
                @csrf
                <div class="col-md-4">
                    <input type="text" name="ip_address" class="form-control" placeholder="IP Address" required>
                </div>
                <div class="col-md-6">
                    <input type="text" name="reason" class="form-control" placeholder="Reason (optional)">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-danger w-100">Ban IP</button>
                </div>
            </form>

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>IP Address</th>
                        <th>Reason</th>
                        <th>Banned At</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($bannedIps as $ip)
                    <tr>
                        <td>{{ $ip->ip_address }}</td>
                        <td>{{ $ip->reason ?? '-' }}</td>
                        <td>{{ $ip->created_at->format('d M Y, h:i A') }}</td>
                        <td class="text-end">
                            <form action="{{ route('admin.banned_ips.destroy', $ip->id) }}" method="POST" onsubmit="return confirm('Unban this IP?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-success">Unban</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            {{ $bannedIps->links() }}
        </div>
    </div>
</div>
@endsection
