@extends('front.layout.master')
@section('title', 'Shop')
@section('body')
    <!--Breadcrumb section begin(giup dinh vi vi tri ban dang o dau trong web)-->
    <div class="breacrumb-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb-text">
                        <a href="{{route('front.home')}}"><i class="fa fa-home"></i>Home</a>
                        <span>Blog</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if(session('success'))
        <div class="alert alert-success mt-3">
            {{ session('success') }}
        </div>
    @endif

    <!--Breadcrumb section end-->
    <!--Blog Section Begin-->
    <section class="blog-section spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-8 order-2 order-lg-1">
                    <div class="blog-sidebar">
                        <div class="search-form">
                            <h4>Search</h4>
                        <form action="{{ route('front.blog.index') }}" method="GET">
                            <input type="text" name="search" placeholder="Search..." value="{{ request('search') }}">
                            <button type="submit"><i class="fa fa-search"></i></button>
                        </form>
                        </div>
                    @auth
                            <div class="write-blog mt-4">
                                <a href="{{route('blogs.create')}}" class="btn btn-success w-100" style="margin-bottom: 30px;">
                                    ✍️ Create Blog
                                </a>
                            </div>
                        @endauth
                        <div class="blog-category">
                            <h4>Categories</h4>
                            <ul>
                                @foreach($categories as $category)
                                    <li>
                                        <a href="{{ route('front.blog.index', ['category' => $category]) }}">{{ $category }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        <div class="recent-post">
                            <h4>Recent Post</h4>
                            <div class="recent-blog">
                                @foreach($recentBlogs as $rblog)
                                <a href="{{route('front.blog.show', $rblog->id)}}" class="rb-item">
                                    <div class="rb-pic">
                                        <img src="{{ asset('storage/blogs/' . $rblog->image) }}" alt="Recent Post">
                                    </div>
                                    <div class="rb-text">
                                        <h6>{{$rblog->title}}</h6>
                                        <p>{{$rblog->category}}<span>{{ $rblog->created_at->format('M d, Y') }}</span></p>
                                    </div>
                                </a>
                                @endforeach
                            </div>
                        </div>
{{--                        <div class="blog-tags">--}}
{{--                            <h4>Product Tags</h4>--}}
{{--                            <div class="tag-item">--}}
{{--                                <a href="#">Fashion</a>--}}
{{--                        </div>--}}
{{--                    </div>--}}
                    </div>
                </div>
                <div class="col-lg-9 order-1 order-lg-2">
                    <div class="row">
                        @foreach($blogs as $blog)
                        <div class="col-lg-6 col-sm-6">
                            <div class="blog-item">
                                <a href="{{route('front.blog.show', $blog->id)}}">
                                <div class="bi-pic">
                                    <img src="{{ asset('storage/blogs/' . $blog->image) }}" alt="Blog Image">
                                </div>
                                <div class="bi-text">
                                    <h4>{{ $blog->title }}</h4>
                                    <p>{{ $blog->category }} <span>{{ $blog->created_at->format('M d, Y') }}</span></p>
                                </div>
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    {{ $blogs->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
@endsection
