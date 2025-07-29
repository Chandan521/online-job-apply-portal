@php
    $isAuthorComment = $comment->user_id === $comment->blog->user_id;
@endphp

<div class="comment border-bottom py-3 {{ $isAuthorComment ? 'author-comment' : '' }}">

    <strong>{{ $comment->user->name }}</strong>
    <p class="mb-1">{{ $comment->comment }}</p>
    <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
</div>
