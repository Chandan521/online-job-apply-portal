<?php

namespace App\Http\Controllers\Admin;

use App\Models\StaticPage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StaticPageController extends Controller
{
    public function index(){
        $pages = StaticPage::paginate(10); // 10 items per page
        return view('admin.static_pages.index',compact('pages'));
    }
    public function show($id)
    {
        $page = StaticPage::findOrFail($id);
        return view('admin.static_pages.show', compact('page'));
    }
    // Delete Page
    public function destroy($id)
    {
        $page = StaticPage::findOrFail($id);
        $page->delete();

        return redirect()->back()->with('success', 'Static page deleted successfully.');
    }
    public function edit($id)
    {
        $page = StaticPage::findOrFail($id);
        return view('admin.static_pages.edit', compact('page'));
    }

    public function update(Request $request, $id)
    {
        $page = StaticPage::findOrFail($id);
        $request->validate([
            'content' => 'required',
        ]);
        $page->update([
            'title' => $request->title,
            'slug' => $request->slug,
            'content' => $request->content,
            'is_visible' => $request->has('is_visible') ? 1 : 0,
        ]);
        return redirect()->back()->with('success', 'Page updated.');
    }
    public function create()
    {
        return view('admin.static_pages.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|unique:static_pages,slug',
            'content' => 'required',
        ]);


        StaticPage::create([
            'title' => $request->title,
            'slug' => $request->slug,
            'content' => $request->content,
            'is_visible' => $request->has('is_visible') ? 1 : 0,
        ]);

        return redirect()->back()->with('success', 'Static page created successfully.');
    }
}
