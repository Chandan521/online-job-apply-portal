@extends('admin.layout.app')

@section('content')
<div class="container py-4">
    <h1 class="text-center mb-4">{{ $page->title }}</h1>
    <p><strong>Slug:</strong> {{ $page->slug }}</p>
    <p><strong>Visible:</strong> {{ $page->is_visible ? 'Yes' : 'No' }}</p>
    <hr>
    <div>{!! $page->content !!}</div>
</div>
@endsection
