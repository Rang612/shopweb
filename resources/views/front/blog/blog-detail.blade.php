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
                        <a href="{{route('front.blog.index')}}">Blogs</a>
                        <span>View Blog</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="blog-details">
        <div class="container">
            <div class="row">
                <!-- Bài viết chính -->
                <div class="col-lg-12">
                    <div class="blog-detail-title mt-4 mb-3">
                        <h2>{{ $blog->title }}</h2>
                        <p>{{ $blog->category }} <span>{{ $blog->created_at->format('M d, Y') }}</span></p>
                    </div>

                    <div class="blog-large-pic mb-4">
                        <img src="{{ asset('uploads/blogs/' . $blog->image) }}" alt="{{ $blog->title }}">
                    </div>

                    <div class="blog-detail-desc mb-4">
                        {!! $blog->content !!}
                    </div>
                    @if($blog->quote)
                        <div class="blog-quote mb-5 p-3 bg-light border-start border-warning">
                            <p class="mb-0"><em>{{ $blog->quote }}</em></p>
                        </div>
                    @endif

                    <div class="posted-by mb-5">
                        <h5>Post By: {{ $blog->user->name }}</h5>
                    </div>
                </div>
                <!-- Comment Section -->
                <div class="col-lg-12 mt-5 mb-5">
                    <div class="comment-block p-4 bg-light rounded">
                        <h4 class="mb-4">Comments</h4>
                        {{-- Hiển thị các comment nếu có --}}
                        @if($blog->blogcomment && $blog->blogcomment->isNotEmpty())
                            @foreach($blog->blogcomment as $comment)
                                <div class="mb-3 border-bottom pb-2">
                                    <strong>{{ $comment->name }}</strong>
                                    <p class="mb-1">{{ $comment->meassages }}</p>
                                    <small class="text-muted">{{ $comment->created_at->format('M d, Y H:i') }}</small>
                                </div>
                            @endforeach
                        @else
                            <p class="text-muted">No comments yet.</p>
                        @endif
                        {{-- Form comment nếu đã đăng nhập --}}
                        @auth
                            <form action="{{ route('blogs.comment', $blog->id) }}" method="POST" class="comment-form mt-4">
                                @csrf
                                <textarea name="message" placeholder="Write your comment..." class="form-control mb-3" rows="4" required></textarea>
                                <button type="submit" class="site-btn">Send message</button>
                            </form>
                        @else
                            <div class="alert alert-warning mt-4">
                                You need to <a href="{{ route('account.login') }}" class="text-primary">login</a> to leave a comment.
                            </div>
                        @endauth
                    </div>
                </div>
                <!-- Related posts -->
                <div class="col-lg-12">
                    <div class="blog-more mt-5 pt-3 border-top">
                        <h4 class="mb-4">Related Posts</h4>
                        <div class="row">
                            @forelse($relatedBlogs as $related)
                                <div class="col-sm-4 mb-4">
                                    <a href="{{ route('front.blog.show', $related->id) }}">
                                        <img src="{{ asset('uploads/blogs/' . $related->image) }}" alt="{{ $related->title }}">
                                        <p class="mt-2 fw-bold">{{ Str::limit($related->title, 50) }}</p>
                                    </a>
                                </div>
                            @empty
                                <p class="text-muted px-3">No related posts found.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
