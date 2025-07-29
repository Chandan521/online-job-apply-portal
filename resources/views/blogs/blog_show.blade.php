@extends('layouts.app')

@section('title', $blog->title)

@push('blog_show_style')
    <style>
        .blog-hero {
            background: linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.4)),
                url('{{ asset('storage/' . $blog->featured_image) }}') center/cover no-repeat;
            color: #fff;
            padding: 6rem 1rem 3rem;
            text-align: center;
        }

        .blog-hero h1 {
            font-size: 2.75rem;
            font-weight: 700;
        }

        .blog-hero .meta {
            font-size: 0.95rem;
            color: #ddd;
            margin-top: 0.5rem;
        }

        .blog-body {
            padding: 2rem 0;
        }

        .blog-content {
            font-size: 1.125rem;
            line-height: 1.8;
            color: #333;
        }

        .sidebar {
            border-left: 1px solid #eee;
            padding-left: 1rem;
        }

        .feedback-buttons button {
            min-width: 140px;
            font-size: 1rem;
            transition: transform 0.2s ease-in-out;
        }

        .feedback-buttons button:hover {
            transform: scale(1.05);
        }

        .related-post img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 6px;
        }

        .related-post {
            font-size: 0.95rem;
            margin-bottom: 1rem;
        }

        .comment.author-comment {
            background-color: #e6f0ff;
            /* light blue */
            border-left: 4px solid #1a73e8;
            padding: 1rem;
            border-radius: 0.5rem;
        }

        #commentForm textarea:focus {
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
            border-color: #86b7fe;
        }

        @media (max-width: 768px) {
            .sidebar {
                border-left: none;
                margin-top: 2rem;
                padding-left: 0;
            }
        }
    </style>
@endpush

@section('content')
    {{-- Hero Banner --}}
    <section class="blog-hero">
        <h1>{{ $blog->title }}</h1>
        <div class="meta">
            Published on {{ $blog->created_at->format('F j, Y') }} |
            {{ number_format($blog->views) }} views
        </div>
    </section>

    {{-- Main Content --}}
    <div class="container blog-body">
        <div class="row">
            {{-- Blog Content --}}
            <div class="col-lg-8">
                <div class="blog-content">
                    {!! $blog->content !!}
                </div>

                {{-- Like & Dislike Section --}}
                <div class="mt-4 mb-5">
                    <div class="d-flex align-items-center gap-4 flex-wrap">
                        {{-- Like Button --}}
                        <button id="likeBtn"
                            class="btn d-flex align-items-center border rounded px-3 py-2 bg-white shadow-sm"
                            style="gap: 8px;">
                            <i class="fas fa-thumbs-up text-success fs-5"></i>
                            <span class="text-dark">Like</span>
                            <span class="badge bg-success text-white" id="likeCount">{{ $blog->likes }}</span>
                        </button>

                        {{-- Dislike Button --}}
                        <button id="dislikeBtn"
                            class="btn d-flex align-items-center border rounded px-3 py-2 bg-white shadow-sm"
                            style="gap: 8px;">
                            <i class="fas fa-thumbs-down text-danger fs-5"></i>
                            <span class="text-dark">Dislike</span>
                            <span class="badge bg-danger text-white" id="dislikeCount">{{ $blog->dislikes }}</span>
                        </button>
                    </div>
                </div>
                {{-- Comment Section --}}
                <div class="mt-5">
                    <h5 class="fw-bold mb-3">Comments</h5>

                    <div id="comment-list">
                        {{-- Only show first 5 --}}
                        @foreach ($blog->comments()->latest()->take(5)->get() as $comment)
                            @include('blogs._comment_item', ['comment' => $comment])
                        @endforeach

                        {{-- Load More Button --}}
                        @if ($blog->comments()->count() > 5)
                            <div class="mt-2 text-center" id="loadMoreWrapper">
                                @if ($blog->comments()->count() > 5)
                                    <button id="load-more-comments" class="btn btn-outline-secondary btn-sm"
                                        data-offset="5">Show More Comments</button>
                                @endif
                            </div>

                        @endif

                    </div>

                    @auth
                        <form id="commentForm" class="mt-4 card shadow-sm border-0 p-4">
                            @csrf
                            <h5 class="mb-3">
                                <i class="fas fa-comment-dots me-2 text-primary"></i>Leave a Comment
                            </h5>

                            <div class="mb-3">
                                <label for="commentText" class="form-label visually-hidden">Your Comment</label>
                                <textarea name="comment" id="commentText" rows="4" class="form-control rounded-3 shadow-sm"
                                    placeholder="Write something thoughtful..." required></textarea>
                            </div>

                            <div class="d-flex align-items-center justify-content-between">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-paper-plane me-1"></i> Submit
                                </button>
                                <div id="commentStatus" class="small text-success d-none">
                                    <i class="fas fa-check-circle me-1"></i> Comment added!
                                </div>
                            </div>
                        </form>
                    @else
                        <div class="alert alert-info mt-4 d-flex align-items-center">
                            <i class="fas fa-sign-in-alt me-2"></i>
                            <span>You must be logged in to comment.</span>
                        </div>
                    @endauth

                </div>

                {{-- Back Button --}}
                <div class="mt-5">
                    <a href="{{ route('blogs.index') }}" class="btn btn-light border shadow-sm rounded-pill px-4 py-2">
                        <i class="fas fa-arrow-left me-2 text-secondary"></i> Back to Blogs
                    </a>
                </div>


            </div>

            {{-- Sidebar --}}
            <div class="col-lg-4 mt-5 mt-lg-0 ps-lg-4">
                <h5 class="fw-bold mb-3">Related Blogs</h5>
                @forelse ($latestBlogs as $relBlog)
                    <div class="d-flex related-post">
                        <a href="{{ route('blog.show', $relBlog->slug) }}" class="me-3">
                            @if ($relBlog->featured_image)
                                <img src="{{ asset('storage/' . $relBlog->featured_image) }}" alt="{{ $relBlog->title }}">
                            @endif
                        </a>
                        <div>
                            <a href="{{ route('blog.show', $relBlog->slug) }}" class="text-dark fw-semibold">
                                {{ Str::limit($relBlog->title, 55) }}
                            </a>
                            <div class="text-muted small">
                                {{ $relBlog->created_at->format('M d, Y') }}
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-muted">No related blogs available.</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection

@section('like_dislike_script')
    <script>
        document.getElementById('likeBtn').addEventListener('click', function() {
            fetch("{{ route('blogs.like', $blog->id) }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                })
                .then(res => res.json())
                .then(data => {
                    document.getElementById('likeCount').innerText = data.likes;
                    document.getElementById('dislikeCount').innerText = data.dislikes;
                });
        });

        document.getElementById('dislikeBtn').addEventListener('click', function() {
            fetch("{{ route('blogs.dislike', $blog->id) }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                })
                .then(res => res.json())
                .then(data => {
                    document.getElementById('likeCount').innerText = data.likes;
                    document.getElementById('dislikeCount').innerText = data.dislikes;
                });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const commentForm = document.getElementById('commentForm');
            const commentInput = document.getElementById('commentText');
            const commentList = document.getElementById('comment-list');
            const statusDiv = document.getElementById('commentStatus');
            const slug = "{{ $blog->slug }}";

            // Used to prevent duplicate comment submission
            let lastComment = '';

            commentForm?.addEventListener('submit', function(e) {
                e.preventDefault();

                const commentText = commentInput.value.trim();
                if (!commentText) return;

                // Prevent same comment as last one
                if (lastComment === commentText) {
                    alert('You already posted this comment.');
                    return;
                }

                fetch(`{{ url('/blogs') }}/${slug}/comment`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({
                            comment: commentText
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success && data.comment) {
                            commentList.insertAdjacentHTML('afterbegin', data.comment);
                            commentInput.value = '';
                            lastComment = commentText;

                            // Show status
                            statusDiv.textContent = 'Comment posted!';
                            statusDiv.classList.remove('d-none', 'text-danger');
                            statusDiv.classList.add('text-success');

                            // Scroll to top of comment list
                            commentList.scrollIntoView({
                                behavior: 'smooth',
                                block: 'start'
                            });

                            // Hide message
                            setTimeout(() => {
                                statusDiv.classList.add('d-none');
                                statusDiv.textContent = '';
                            }, 2500);
                        } else {
                            statusDiv.textContent = 'Failed to post comment.';
                            statusDiv.classList.remove('d-none');
                            statusDiv.classList.add('text-danger');
                        }
                    })
                    .catch(() => {
                        statusDiv.textContent = 'Something went wrong.';
                        statusDiv.classList.remove('d-none');
                        statusDiv.classList.add('text-danger');
                    });
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const loadMoreBtn = document.getElementById('load-more-comments');
            const commentList = document.getElementById('comment-list');

            if (loadMoreBtn) {
                loadMoreBtn.addEventListener('click', function() {
                    const offset = parseInt(this.dataset.offset || 5);
                    const slug = "{{ $blog->slug }}";

                    this.disabled = true;
                    this.innerText = "Loading...";

                    fetch(`{{ url('/blog') }}/${slug}/comments/load?offset=${offset}`)

                        .then(res => res.json())
                        .then(data => {
                            if (data.comments && data.comments.length) {
                                data.comments.forEach(commentHtml => {
                                    commentList.insertAdjacentHTML('beforeend', commentHtml);
                                });

                                // Update offset
                                loadMoreBtn.dataset.offset = offset + data.comments.length;

                                // Hide button if no more
                                if (!data.hasMore) {
                                    document.getElementById('loadMoreWrapper').remove();
                                } else {
                                    loadMoreBtn.disabled = false;
                                    loadMoreBtn.innerText = "Show More Comments";
                                }
                            } else {
                                document.getElementById('loadMoreWrapper').remove();
                            }
                        })
                        .catch(() => {
                            loadMoreBtn.disabled = false;
                            loadMoreBtn.innerText = "Try Again";
                        });
                });
            }
        });
    </script>



@endsection

@section('footer')
    <footer class="text-center text-muted py-4 mt-5 border-top small bg-light">
        © {{ date('Y') }} <strong>{{ setting('site_name') ?? 'Name Not Set' }}</strong>. All rights reserved.
        <span class="d-block d-md-inline mt-1 mt-md-0">
            | Built with ❤️ for job seekers and employers.
        </span>
    </footer>
@endsection
