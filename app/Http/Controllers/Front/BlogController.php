<?php
namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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
        $blog->is_approved = 0;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $fileName = time() . '_' . $image->getClientOriginalName();
            $sharedPath = public_path('uploads/blogs/');
            if (!file_exists($sharedPath)) {
                mkdir($sharedPath, 0775, true);
            }
            $image->move($sharedPath, $fileName);
            $blog->image = $fileName;
        }

        $blog->save();
        return redirect()->route('front.blog.index')->with('success', 'Your post has been submitted and is pending admin approval.');
    }

    public function show($id)
    {
        $blog = Blog::with('user')->where('is_approved', true)->findOrFail($id);
        $prevBlog = Blog::where('is_approved', true)->where('id', '<', $blog->id)->latest()->first();
        $nextBlog = Blog::where('is_approved', true)->where('id', '>', $blog->id)->first();
        $keywords = explode(' ', strtolower($blog->title));
        $relatedBlogsQuery = Blog::where('is_approved', true)->where('id', '!=', $blog->id);
        foreach ($keywords as $word) {
            $relatedBlogsQuery->orWhereRaw('LOWER(title) LIKE ?', ['%' . $word . '%']);
        }
        $relatedBlogs = $relatedBlogsQuery->take(3)->get();
        return view('front.blog.blog-detail', [
            'blog' => $blog,
            'relatedBlogs' => $relatedBlogs,
            'prevBlog' => $prevBlog,
            'nextBlog' => $nextBlog
        ]);
    }

    public function comment(Request $request, Blog $blog)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);
        $user = Auth::user();
        $blog->blogcomment()->create([
            'user_id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'meassages' => $request->message,
        ]);
        return back()->with('success', 'Your comment has been submitted.');
    }

    public function destroy($id)
    {
        $blog = Blog::where('user_id', Auth::id())->findOrFail($id);
        $blog->blogcomment()->delete();
        $imagePath = public_path('uploads/blogs/' . $blog->image);
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }
        $blog->delete();
        return redirect()->back()->with('success', 'Your blog deleted successfully!');
    }
}
