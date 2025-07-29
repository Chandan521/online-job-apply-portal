<?php

namespace App\Http\Controllers\Recruiter;

use App\Models\Blog;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class RecruiterBlogController extends Controller
{
    public function index()
    {
        $blogs = Blog::where('user_id', auth()->id())
                     ->where('user_type', 'recruiter')
                     ->latest()
                     ->paginate(10);

        return view('recruiter.dashboard.blogs.index', compact('blogs'));
    }

    public function create()
    {
        return view('recruiter.dashboard.blogs.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'featured_image' => 'nullable|image|max:2048',
        ]);

        $slug = Str::slug($request->title);

        $data = $request->only(['title', 'content']);
        $data['slug'] = $slug;
        $data['user_id'] = auth()->id();
        $data['user_type'] = 'recruiter';

        if ($request->hasFile('featured_image')) {
            $data['featured_image'] = $request->file('featured_image')->store('blogs', 'public');
        }

        Blog::create($data);

        return redirect()->route('blog.index')->with('success', 'Blog post created.');
    }

    public function edit(string $id)
    {
        $blog = Blog::where('id', $id)
                    ->where('user_id', auth()->id())
                    ->where('user_type', 'recruiter')
                    ->firstOrFail();

        return view('recruiter.dashboard.blogs.edit', compact('blog'));
    }

    public function update(Request $request, string $id)
    {
        $blog = Blog::where('id', $id)
                    ->where('user_id', auth()->id())
                    ->where('user_type', 'recruiter')
                    ->firstOrFail();

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'featured_image' => 'nullable|image|max:2048',
        ]);

        $slug = Str::slug($request->title);

        $blog->update([
            'title' => $request->title,
            'content' => $request->content,
            'slug' => $slug,
        ]);

        if ($request->hasFile('featured_image')) {
            // Delete old image if exists
            if ($blog->featured_image && Storage::disk('public')->exists($blog->featured_image)) {
                Storage::disk('public')->delete($blog->featured_image);
            }

            $path = $request->file('featured_image')->store('blogs', 'public');
            $blog->update(['featured_image' => $path]);
        }

        return redirect()->route('blog.index')->with('success', 'Blog updated.');
    }

    public function destroy(string $id)
    {
        $blog = Blog::where('id', $id)
                    ->where('user_id', auth()->id())
                    ->where('user_type', 'recruiter')
                    ->firstOrFail();

        if ($blog->featured_image && Storage::disk('public')->exists($blog->featured_image)) {
            Storage::disk('public')->delete($blog->featured_image);
        }

        $blog->delete();

        return redirect()->route('blog.index')->with('success', 'Blog deleted successfully.');
    }
}
