@extends('recruiter.layout.dashboard_layout')

@section('title', 'Tags')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">All Tags Jobs</h2>

</div>
@endsection

@section('footer')
<footer class="mt-auto w-100 bg-light text-center py-3 text-muted small"
        style="position: fixed; left: 0; right: 0; bottom: 0; z-index: 100; border-top: 1px solid #ddd; transition: left 0.3s;">
    © {{ date('Y') }} <strong>{{ setting('site_name' ?? 'Name Not Set') }}</strong>. All rights reserved.
    <span class="d-block d-md-inline mt-1 mt-md-0">
        | Empowering recruiters to find top talent with ease.
    </span>
</footer>
@endsection
