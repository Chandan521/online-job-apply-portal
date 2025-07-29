@extends('admin.layout.app')

@section('title', 'Admin - Companies')

@section('page-title', 'Companies')

@section('content')
<div class="page-header">
    <h1 class="page-title">Companies</h1>
</div>

<div class="card">
    <div class="card-body">
        @if($companies->count())
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Logo</th>
                            <th>Company Name</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($companies as $company)
                            <tr>
                                <td>
                                    @if($company->company_logo)
                                        <img src="{{ asset('/' . $company->company_logo) }}" alt="Logo" width="60">
                                    @else
                                        <span class="text-muted">No Logo</span>
                                    @endif
                                </td>
                                <td>{{ $company->company }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-muted">No companies found.</p>
        @endif
    </div>
</div>
@endsection
