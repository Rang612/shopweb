@extends('front.layout.master')
@section('title', 'New Blog Post')
@section('body')
    <style>
        .blog-create-container {
            max-width: 700px;
            margin: 50px auto;
            padding: 30px;
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .blog-create-container h2 {
            margin-bottom: 25px;
        }
    </style>
    <!--Breadcrumb section begin(giup dinh vi vi tri ban dang o dau trong web)-->
    <div class="breacrumb-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb-text">
                        <a href="{{route('front.home')}}"><i class="fa fa-home"></i>Home</a>
                        <a href="{{route('front.blog.index')}}">Blogs</a>
                        <span>Create Blog</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="blog-create-container">
        <h2>📝 Create a New Blog Post</h2>
        <form action="{{ route('blogs.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group mb-3">
                <label for="title">Title</label>
                <input type="text" name="title" class="form-control" required>
            </div>
            <div class="form-group mb-3">
                <label for="category">Category</label>
                <select name="category" class="form-control" required>
                    <option value="">-- Select Category --</option>
                    @foreach($categories as $category)
                        <option value="{{ $category }}">{{ ucfirst($category) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group mb-3">
                <label for="quote">Quote (optional)</label>
                <textarea name="quote" class="form-control" rows="2"></textarea>
            </div>
            <div class="form-group mb-3">
                <label for="content">Content</label>
                <textarea name="content" class="form-control summernote" rows="6" required></textarea>
            </div>
            <div class="form-group mb-3">
                <label for="image">Thumbnail Image</label>
                <input type="file" name="image" class="form-control-file">
            </div>
            {{-- Hidden status field --}}
            <input type="hidden" name="status" value="pending">
            <button type="submit" class="btn btn-warning w-auto text-white px-4">Publish</button>
        </form>
    </div>
@endsection

