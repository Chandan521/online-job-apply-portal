@extends('layouts.app')

@section('content')
<div class="container text-center py-5">
    <h1 class="display-1 text-warning">403</h1>
    <p class="lead">Access Denied</p>
    <p class="text-muted">You don't have permission to view this page.</p>
    <a href="{{ url('/') }}" class="btn btn-primary mt-3"><i class="fas fa-home"></i> Back to Home</a>
</div>
@endsection
