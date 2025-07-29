<?php

namespace App\Http\Controllers\Admin;

use App\Models\Blog;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class BlogController extends Controller
{
    public function index(){
        $blogs = Blog::paginate(10);
        return view('admin.blog.index', compact('blogs'));
    }
    public function create()
    {
        return view('admin.blog.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'featured_image' => 'nullable|image|max:2048',
        ]);

        $slug = $this->generateUniqueSlug($request->title);

        $data = [
            'title' => $request->title,
            'content' => $request->content,
            'slug' => $slug,
            'user_id' => auth()->id(),
            'user_type' => 'admin',
        ];

        if ($request->hasFile('featured_image')) {
            $data['featured_image'] = $request->file('featured_image')->store('blogs', 'public');
        }

        Blog::create($data);

        return redirect()->back()->with('success', 'Blog post created.');
    }

    public function edit(string $id)
    {
        $blog = Blog::findOrFail($id);
        return view('admin.blog.edit', compact('blog'));
    }

    public function update(Request $request, string $id)
    {
        $blog = Blog::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'featured_image' => 'nullable|image|max:2048',
        ]);

        $slug = $this->generateUniqueSlug($request->title, $id);

        $updateData = [
            'title' => $request->title,
            'content' => $request->content,
            'slug' => $slug,
            'user_id' => auth()->id(),
            'user_type' => 'admin',
        ];

        if ($request->hasFile('featured_image')) {
            if ($blog->featured_image && Storage::disk('public')->exists($blog->featured_image)) {
                Storage::disk('public')->delete($blog->featured_image);
            }

            $updateData['featured_image'] = $request->file('featured_image')->store('blogs', 'public');
        }

        $blog->update($updateData);

        return redirect()->back()->with('success', 'Blog updated.');
    }

    public function destroy(string $id)
    {
        $blog = Blog::findOrFail($id);

        if ($blog->featured_image && Storage::disk('public')->exists($blog->featured_image)) {
            Storage::disk('public')->delete($blog->featured_image);
        }

        $blog->delete();

        return redirect()->back()->with('success', 'Blog deleted successfully.');
    }

    private function generateUniqueSlug($title, $ignoreId = null)
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $i = 1;

        while (
            Blog::where('slug', $slug)
                ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $originalSlug . '-' . $i++;
        }

        return $slug;
    }
}
