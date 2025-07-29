@extends('layouts.app')

@section('title', 'All Pages')

@section('content')
<div class="container my-5">
    <h1 class="mb-4">Pages</h1>

    @if($pages->count())
        <ul class="list-group">
            @foreach($pages as $page)
                <li class="list-group-item">
                    <a href="{{ route('pages.show', $page->slug) }}">{{ $page->title }}</a>
                </li>
            @endforeach
        </ul>
    @else
        <div class="alert alert-warning">No pages available.</div>
    @endif
</div>
@endsection
