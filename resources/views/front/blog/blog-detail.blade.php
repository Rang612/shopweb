@extends('front.layout.master')
@section('title', 'Shop')
@section('body')
    <div class="blog-details">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="blog-detail-title">
                        <h2>The Personality Trait That Makes People Happier</h2>
                        <p>travel<span>May 19, 2925</span></p>
                    </div>
                    <div class="blog-large-pic">
                        <img src="{{ asset('front/img/blog/details.jpg') }}" alt="Blog Detail Image">
                </div>
                    <div class="blog-detail-desc">
                        <p>dday la 1 doan van</p>
                    </div>
                    <div class="blog-quote">
                        <p> dday la 1 doan van</p>
                    </div>
                    <div class="blog-more">
                        <div class="row">
                            <div class="col-sm-4">
                                <img src="{{ asset('front/img/blog/details-1.jpg') }}" alt="Blog More Image">
                            </div>
                            <div class="col-sm-4">
                                <img src="{{ asset('front/img/blog/details-1.jpg') }}" alt="Blog More Image">
                            </div>
                            <div class="col-sm-4">
                                <img src="{{ asset('front/img/blog/details-1.jpg') }}" alt="Blog More Image">
                            </div>
                        </div>
                    </div>
                    <div class="tag-share">
                        <div class="details-tag">
                            <ul>
                                <li><i class="fa fa-tags"></i></li>
                                <li>Travel</li>
                                <li>Fashion</li>
                            </ul>
                        </div>
                        <div class="blog-share">
                            <span>Share:</span>
                            <div class="social-links">
                                <a href="#"><i class="fa fa-facebook"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="blog-post">
                        <div class="row">
                            <div class="col-lg-5 col-md-6">
                                <a href="#" class="prev-blog">
                                    <div class="pb-pic">
                                        <i class="ti-arrow-left"></i>
                                        <img src="{{ asset('front/img/blog/details-1.jpg') }}" alt="Previous Blog Image">
                                    </div>
                                    <div class="pb-text">
                                        <span>Previous Post:</span>
                                        <h5>The Personality Trait That Makes People Happier</h5>
                                    </div>
                                </a>
                            </div>
                            <div class="col-lg-5 col-md-6 offset-lg-2">
                                <a href="#" class="next-blog">
                                    <div class="nb-pic">
                                        <img src="{{ asset('front/img/blog/details-1.jpg') }}" alt="Next Blog Image">
                                        <i class="ti-arrow-right"></i>
                                    </div>
                                    <div class="nb-text">
                                        <span>Next Post:</span>
                                        <h5>The Personality Trait That Makes People Happier</h5>
                                    </div>
                                </a>
                        </div>
                    </div>
                </div>
                    <div class="posted-by">
                        <div class="pb-pic">
                            <img src="{{ asset('front/img/blog/details-1.jpg') }}" alt="Posted By Image">
                        </div>
                        <div class="pb-text">
                            <a href="#">
                                <h5>Shane Lynch</h5>
                                <p>ddoan van</p>
                            </a>
                        </div>
                    </div>
                    <div class="leave-comment">
                        <h4>Leave A Comment</h4>
                        <form action="#" class="comment-form">
                            <div class="row">
                                <div class="col-lg-6">
                                    <input type="text" placeholder="Name">
                                </div>
                                <div class="col-lg-6">
                                    <input type="text" placeholder="Email">
                                </div>
                                <div class="col-lg-12">
                                    <textarea placeholder="Message"></textarea>
                                    <button type="submit" class="site-btn">Send message</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
