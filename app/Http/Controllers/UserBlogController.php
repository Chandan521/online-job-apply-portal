<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\BlogComment;
use Illuminate\Http\Request;

class UserBlogController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');

        $blogs = Blog::query()
            ->with('user') // Load author
            ->withCount(['comments']) // Only valid relationship
            ->when($search, fn($q) =>
            $q->where('title', 'like', "%$search%")
                ->orWhere('slug', 'like', "%$search%"))
            ->latest()
            ->paginate(9);

        $featuredBlogs = Blog::orderBy('views', 'desc')->take(5)->get();
        $latestBlogs = Blog::latest()->take(5)->get();

        return view('blogs.index', compact('blogs', 'search', 'featuredBlogs', 'latestBlogs'));
    }



    public function show($slug)
    {
        $blog = Blog::where('slug', $slug)->firstOrFail();

        // Prevent repeated count in same session
        $sessionKey = 'blog_viewed_' . $blog->id;
        if (!session()->has($sessionKey)) {
            $blog->increment('views');
            session()->put($sessionKey, true);
        }

        // 🔽 Get latest 5 blogs except current
        $latestBlogs = Blog::where('id', '!=', $blog->id)
            ->latest()
            ->take(5)
            ->get();

        return view('blogs.blog_show', compact('blog', 'latestBlogs'));
    }

    public function like($id)
    {
        $blog = Blog::findOrFail($id);
        $likeKey = 'blog_liked_' . $blog->id;
        $dislikeKey = 'blog_disliked_' . $blog->id;

        if (!session()->has($likeKey)) {
            $blog->increment('likes');
            session()->put($likeKey, true);

            // Remove dislike if previously disliked
            if (session()->has($dislikeKey)) {
                $blog->decrement('dislikes');
                session()->forget($dislikeKey);
            }
        }

        return response()->json(['likes' => $blog->likes, 'dislikes' => $blog->dislikes]);
    }

    public function dislike($id)
    {
        $blog = Blog::findOrFail($id);
        $likeKey = 'blog_liked_' . $blog->id;
        $dislikeKey = 'blog_disliked_' . $blog->id;

        if (!session()->has($dislikeKey)) {
            $blog->increment('dislikes');
            session()->put($dislikeKey, true);

            // Remove like if previously liked
            if (session()->has($likeKey)) {
                $blog->decrement('likes');
                session()->forget($likeKey);
            }
        }

        return response()->json(['likes' => $blog->likes, 'dislikes' => $blog->dislikes]);
    }
    public function storeComment(Request $request, $slug)
    {
        $request->validate([
            'comment' => 'required|string|max:1000',
        ]);

        $blog = Blog::where('slug', $slug)->firstOrFail();

        $comment = new BlogComment();
        $comment->blog_id = $blog->id;
        $comment->user_id = auth()->id();
        $comment->comment = $request->comment;
        $comment->save();

        return response()->json([
            'success' => true,
            'comment' => view('blogs._comment_item', ['comment' => $comment])->render(),
        ]);
    }
    public function loadMoreComments(Request $request, $slug)
    {
        $offset = (int) $request->get('offset', 0);
        $blog = Blog::where('slug', $slug)->firstOrFail();

        $comments = $blog->comments()
            ->latest()
            ->skip($offset)
            ->take(5)
            ->get();

        $htmlComments = $comments->map(function ($comment) {
            return view('blogs._comment_item', compact('comment'))->render();
        });

        return response()->json([
            'comments' => $htmlComments,
            'hasMore' => $blog->comments()->count() > $offset + $comments->count()
        ]);
    }
}
