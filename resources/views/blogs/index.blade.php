@extends('layouts.app')

@section('title', 'Latest Blogs')

@section('content')
    <div class="container py-5">

        {{-- Header --}}
        <div class="text-center mb-5">
            <h1 class="fw-bold display-5">Discover our latest blogs</h1>
            <p class="text-muted">Discover the achievements that set us apart. From groundbreaking projects to industry
                accolades, we take pride in our accomplishments.</p>

            {{-- Search Bar --}}
            <form method="GET" action="{{ route('blogs.index') }}" class="d-flex justify-content-center mt-4">
                <div class="input-group input-group-lg w-75 shadow">
                    <input type="text" name="search" class="form-control border-0"
                        placeholder="Search blog by title or slug..." value="{{ request('search') }}" autocomplete="off">
                    <button class="btn btn-primary px-4" type="submit">Find Now</button>
                </div>
            </form>
        </div>

        {{-- Blog Grid and Sidebar --}}
        <div class="row">
            {{-- Blog Cards --}}
            <div class="col-lg-8">
                <h4 class="fw-bold mb-4">Whiteboards are remarkable.</h4>

                @if ($blogs->isEmpty())
                    <div class="alert alert-info">No blogs found.</div>
                @else
                    <div class="row row-cols-1 row-cols-md-2 g-4">
                        @foreach ($blogs as $blog)
                            <div class="col">
                                <div class="card border-0 shadow h-100">
                                    @if ($blog->featured_image)
                                        <a href="{{ route('blog.show', $blog->slug) }}">
                                            <img src="{{ asset('storage/' . $blog->featured_image) }}" class="card-img-top"
                                                alt="{{ $blog->title }}" style="height: 200px; object-fit: cover;">
                                        </a>
                                    @endif
                                    <div class="card-body d-flex flex-column">
                                        @if ($blog->category)
                                            <span class="badge bg-light text-dark mb-2 small">{{ $blog->category }}</span>
                                        @endif

                                        <h5 class="card-title fw-semibold">
                                            <a href="{{ route('blog.show', $blog->slug) }}"
                                                class="text-dark text-decoration-none">
                                                {{ Str::limit($blog->title, 60) }}
                                            </a>
                                        </h5>

                                        <p class="text-muted small mb-3">
                                            {{ Str::limit(strip_tags($blog->content), 100) }}
                                        </p>

                                        @php $author = $blog->user; @endphp
                                        @if ($author)
                                            <p class="text-muted small mt-auto">
    By <strong>{{ $author->name }}</strong>
    <span class="text-capitalize">· {{ $author->role }}</span><br>

    {{ $blog->created_at->format('M d, Y') }}
    • 👁️ {{ $blog->views }}
    • 💬 {{ $blog->comments()->count() }}
    • 👍 {{ $blog->likes }}
    • 👎 {{ $blog->dislikes }}
</p>


                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-5">
                        {{ $blogs->withQueryString()->links() }}
                    </div>
                @endif
            </div>

            {{-- Sidebar --}}
            <div class="col-lg-4 mt-5 mt-lg-0 ps-lg-4">

                {{-- Featured Blogs --}}
                <h5 class="fw-bold mb-4 border-bottom pb-2">🌟 Featured</h5>
                <ul class="list-unstyled">
                    @forelse ($featuredBlogs as $fBlog)
                        <li class="d-flex align-items-start mb-4">
                            @if ($fBlog->featured_image)
                                <img src="{{ asset('storage/' . $fBlog->featured_image) }}" width="70" height="70"
                                    class="rounded border me-3 shadow-sm" style="object-fit: cover;">
                            @endif
                            <div class="flex-grow-1">
                                <small class="text-muted d-block mb-1">{{ $fBlog->created_at->format('F j, Y') }}</small>
                                <a href="{{ route('blog.show', $fBlog->slug) }}"
                                    class="fw-semibold text-dark text-decoration-none">
                                    {{ Str::limit($fBlog->title, 60) }}
                                </a>
                            </div>
                        </li>
                    @empty
                        <li class="text-muted">No featured blogs available.</li>
                    @endforelse
                </ul>

                {{-- Latest Blogs --}}
                <h5 class="fw-bold mt-5 mb-4 border-bottom pb-2">🕒 Latest</h5>
                <ul class="list-unstyled">
                    @forelse ($latestBlogs as $lBlog)
                        <li class="d-flex align-items-start mb-4">
                            @if ($lBlog->featured_image)
                                <img src="{{ asset('storage/' . $lBlog->featured_image) }}" width="70" height="70"
                                    class="rounded border me-3 shadow-sm" style="object-fit: cover;">
                            @endif
                            <div class="flex-grow-1">
                                <small class="text-muted d-block mb-1">{{ $lBlog->created_at->format('F j, Y') }}</small>
                                <a href="{{ route('blog.show', $lBlog->slug) }}"
                                    class="fw-semibold text-dark text-decoration-none">
                                    {{ Str::limit($lBlog->title, 60) }}
                                </a>
                            </div>
                        </li>
                    @empty
                        <li class="text-muted">No latest blogs available.</li>
                    @endforelse
                </ul>
            </div>

        </div>
    </div>
@endsection

@section('footer')
    <footer class="text-center text-muted py-4 mt-auto border-top small bg-light">
        © {{ date('Y') }} <strong>{{ setting('site_name') ?? 'Job Portal' }}</strong>. All rights reserved.
        <span class="d-block d-md-inline mt-1 mt-md-0">| Built with ❤️ for career growth.</span>
    </footer>
@endsection
