@extends('admin.layout.app')

@section('title', 'User Management')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">User Management</h1>
        <a href="{{ route('users.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i>Add New User
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-header">
            <div class="row">
                <div class="col-md-4">
                    <input type="text" id="search-name" class="form-control" placeholder="Search by name...">
                </div>
                <div class="col-md-3">
                    <select id="filter-role" class="form-select">
                        <option value="">All Roles</option>
                        <option value="admin">Admin</option>
                        <option value="recruiter">Recruiter</option>
                        <option value="recruiter_assistant">Recruiter Assistant</option>
                        <option value="job_seeker">Job Seeker</option>

                    </select>
                </div>
                <div class="col-md-3">
                    <select id="filter-status" class="form-select">
                        <option value="">All Statuses</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                        <option value="banned">Banned</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button id="reset-filters" class="btn btn-secondary w-100">Reset</button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="users-table" class="table table-hover" style="width:100%">
                    <thead class="table-light">
                        <tr>
                            <th>User</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Joined Date</th>
                            <th>Last Login</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection


@push('scripts')
<script>
    $(function () {
        var table = $('#users-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ route('users.index') }}',
                data: function (d) {
                    d.name = $('#search-name').val();
                    d.role = $('#filter-role').val();
                    d.status = $('#filter-status').val();
                }
            },
            columns: [
                {
                    data: 'name',
                    name: 'name',
                    render: function (data, type, row) {
                        const avatar = row.profile_photo
                            ? `<img src="/storage/${row.profile_photo}" alt="${row.name}" class="avatar-img rounded-circle">`
                            : `<img src="https://ui-avatars.com/api/?name=${encodeURIComponent(row.name)}&size=40&background=random" alt="${row.name}" class="avatar-img rounded-circle">`;
                        return `
                            <div class="d-flex align-items-center">
                                <div class="avatar me-3" style="width: 40px; height: 40px;">
                                    ${avatar}
                                </div>
                                <div>
                                    <h6 class="mb-0">${row.name}</h6>
                                    <small>${row.email}</small>
                                </div>
                            </div>
                        `;
                    }
                },
                {
                    data: 'role',
                    name: 'role',
                    render: function (data) {
                        let badgeClass = 'bg-secondary';
                        if (data === 'admin') badgeClass = 'bg-primary';
                        else if (data === 'recruiter') badgeClass = 'bg-success';
                        else if (data === 'recruiter_assistant') badgeClass = 'bg-warning';
                        else if (data === 'job_seeker') badgeClass = 'bg-info';
                        return `<span class="badge ${badgeClass}">${data}</span>`;
                    }
                },
                {
                    data: 'status',
                    name: 'status',
                    render: function (data) {
                        let badgeClass = 'bg-secondary';
                        if (data === 'active') badgeClass = 'bg-success';
                        else if (data === 'inactive') badgeClass = 'bg-warning';
                        else if (data === 'banned') badgeClass = 'bg-danger';
                        return `<span class="badge ${badgeClass}">${data}</span>`;
                    }
                },
                {
                    data: 'created_at',
                    name: 'created_at',
                    render: function (data) {
                        return new Date(data).toLocaleDateString();
                    }
                },
                {
                    data: 'last_login_at',
                    name: 'last_login_at',
                    render: function (data) {
                        return data ? new Date(data).toLocaleString() : 'N/A';
                    }
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    className: 'text-end'
                }
            ],
            order: [[3, 'desc']],
            language: {
                processing: '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>'
            }
        });

        $('#search-name').on('keyup', function () {
            table.draw();
        });

        $('#filter-role, #filter-status').on('change', function () {
            table.draw();
        });

        $('#reset-filters').on('click', function () {
            $('#search-name').val('');
            $('#filter-role').val('');
            $('#filter-status').val('');
            table.draw();
        });
    });
</script>
@endpush