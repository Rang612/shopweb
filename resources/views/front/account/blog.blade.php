@extends('front.layout.master')

@section('title','Register')
@section('body')
    <div class="breacrumb-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb-text">
                        <a href="#"><i class="fa fa-user"></i>My Account</a>
                        <span>My Blogs</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <section class=" section-11 ">
        <div class="container  mt-5">
            <div class="row">
                <div class="col-md-3">
                    @include('front.account.common.sidebar')
                </div>
                <div class="col-md-9">
                    @if(session('success'))
                        <div class="alert alert-success mt-3">{{ session('success') }}</div>
                    @endif
                    <div class="card">
                        <div class="card-header">
                            <h2 class="h5 mb-0 pt-2 pb-2">My Blogs</h2>
                        </div>
                        <div class="card-body p-4">
                            @foreach($blogs->chunk(2) as $blogRow)
                                <div class="row mb-4">
                                    @foreach($blogRow as $blog)
                                        <div class="col-lg-6 col-sm-6 mb-3">
                                            <div class="blog-item">
                                                <a href="{{ route('front.blog.show', $blog->id) }}">
                                                    <div class="bi-pic">
                                                        <img src="{{ asset('uploads/blogs/' . $blog->image) }}" alt="Blog Image" class="img-fluid">
                                                    </div>
                                                    <div class="bi-text mt-2">
                                                        <h5 class="fw-bold">{{ $blog->title }}</h5>
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <p class="mb-0">{{ $blog->category }} <span>{{ $blog->created_at->format('M d, Y') }}</span></p>

                                                            <form action="{{ route('front.blog.destroy', $blog->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this blog?')" style="display: inline-block;">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-sm btn-outline-danger px-2 py-1">
                                                                    <i class="fas fa-trash-alt"></i>
                                                                </button>
                                                            </form>
                                                        </div>

                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
