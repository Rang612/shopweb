<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $query = Blog::where('is_approved', true);

        if ($request->filled('search')) {
            $keyword = $request->search;
            $query->where(function ($q) use ($keyword) {
                $q->where('title', 'LIKE', "%$keyword%");
            });
        }
        if ($request->has('category') && $request->category != '') {
            $query->where('category', $request->category);
        }
        $blogs = $query->latest()->paginate(6);
        $categories = Category::pluck('name');
        $recentBlogs = Blog::where('is_approved', true)->latest()->take(5)->get();

        return view('front.blog.blogs', compact('blogs', 'categories', 'recentBlogs'));
    }


    public function create()
    {
        $categories = Category::pluck('name');
        return view('front.blog.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'quote' => 'nullable|string|max:500',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $blog = new Blog();
        $blog->user_id = Auth::id();
        $blog->title = $request->title;
        $blog->category = $request->category;
        $blog->quote = $request->quote;
        $blog->content = $request->input('content');
        $blog->is_approved= '0';

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $fileName = time() . '_' . $image->getClientOriginalName(); // để tránh trùng tên
            $image->storeAs('blogs', $fileName, 'public'); // vẫn lưu vào thư mục blogs
            $blog->image = $fileName; // chỉ lưu tên file vào DB
        }
        $blog->save();

        return redirect()->route('front.blog.index')->with('success', 'Your post has been submitted and is pending admin approval.');
    }
}
