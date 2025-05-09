@extends('front.layout.master')

@section('title','Home')
@section('body')
    <!---->
    <!--Hero section begin-->
    <section class="hero-section ">
        <div class="hero-items owl-carousel">
            <div class="single-hero-items set-bg" data-setbg="front/img/hero-1.jpg">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-5">
                            <span>Adult and children's clothing</span>
                            <h1> Black Friday</h1>
                            <p>
                                Sale-off
                            </p>
                            <a href="{{route('front.shop.category', 'woman-product')}}" class="primary-btn">Buy Now</a>
                        </div>
                    </div>
                    <div class="off-card">
                        <h2>  Sale<span>50%</span></h2>
                    </div>
                </div>

            </div>
            <div class="single-hero-items set-bg" data-setbg="front/img/hero-2.jpg">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-5">
                            <span>Adult and children's clothing</span>
                            <h1> Black Friday</h1>
                            <p>
                                Sale-off
                            </p>
                            <a href="{{route('front.shop.category', 'woman-product')}}" class="primary-btn">Buy Now</a>
                        </div>
                    </div>
                    <div class="off-card">
                        <h2> Sale<span>50%</span></h2>
                    </div>
                </div>

            </div>
            <div class="single-hero-items set-bg" data-setbg="front/img/hero-3.jpg">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-7">
                            <span style="color: white;">Adult and children's clothing</span>
                            <h1> Black Friday</h1>
                            <p style="color: white;">
                                Sale-off
                            </p>
                            <a href="{{route('front.shop.category', 'woman-product')}}" class="primary-btn" >Buy Now</a>
                        </div>
                    </div>
                    <div class="off-card">
                        <h2>Sale<span>50%</span></h2>
                    </div>
                </div>

            </div>

        </div>
    </section>
    <!--Here sectin end-->
    <!--Banner Section Begin-->
    <div class="banner-section spad">
        <div class="container-fluid">
            <div class="row">
                @if(getCategories()->isNotEmpty())
                    @foreach(getCategories() as $category)
                <div class="col-lg-4">
                    <div class="single-banner">
                            <img src="{{asset('storage/'.$category->image)}}" alt="">
                        @if(!empty($category->imgur_link))
                            @php
                                $imageData = base64_encode(file_get_contents($category->imgur_link));
                                $src = 'data:image/jpeg;base64,' . $imageData;
                            @endphp
                            <img src="{{ $src }}" alt="Category Image">
                        @endif
                        <div class="inner-text">
                            <h4> {{$category->name}}</h4>
                        </div>?
                    </div>
                </div>
                    @endforeach
                @endif
{{--                <div class="col-lg-4">--}}
{{--                    <div class="single-banner">--}}
{{--                        <img src="front/img/banner-2.jpg" alt="">--}}
{{--                        <div class="inner-text">--}}
{{--                            <h4> Nữ</h4>--}}
{{--                        </div>--}}
{{--                    </div>--}}

{{--                </div>--}}

{{--                <div class="col-lg-4">--}}
{{--                    <div class="single-banner">--}}
{{--                        <img src="front/img/banner-3.jpg" alt="">--}}
{{--                        <div class="inner-text">--}}
{{--                            <h4> Trẻ em</h4>--}}
{{--                        </div>--}}
{{--                    </div>--}}

{{--                </div>--}}
            </div>
        </div>
    </div>
    <!-- Banner section end-->

    <!--Women banner section begin-->
    <section class="women-banner spad">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3">
                    <div class="product-large set-bg" data-setbg="front/img/products/women-large.jpg">
                        <h2>Women's</h2>
                        <a href="{{route('front.shop.category', 'woman-product')}}">Discover more</a>
                    </div>
                </div>
                <div class="col-lg-8 offset-lg-1">
                    <div class="filter-control">
                        <ul>
                            <li class="item active" data-tag="all">All</li>
                            @foreach($womenSubCategories as $subCategory)
                                <li class="item" data-tag="{{ $subCategory->slug }}">{{ $subCategory->name }}</li>
                            @endforeach
                        </ul>
                    </div>

                    <!-- Owl Carousel cho "Tất cả" sản phẩm -->
                    <div class="product-slider owl-carousel product-list all">
                        @foreach($womenProductsBySubcategory as $subCategorySlug => $products)
                            @foreach($products as $product)
                                <div class="item">
                                    @include('front.components.product-item', ['product' => $product])
                                </div>
                            @endforeach
                        @endforeach
                    </div>
                    <!-- Owl Carousel cho từng subCategory -->
                    @foreach($womenProductsBySubcategory as $subCategorySlug => $products)
                        <div class="product-slider owl-carousel product-list {{ $subCategorySlug }}" style="display: none;">
                            @foreach($products as $product)
                                <div class="item">
                                    @include('front.components.product-item', ['product' => $product])
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    <!--Women banner section end-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" />
    <style>
        .voucher-carousel {
            position: relative;
        }

        .voucher-carousel .owl-nav {
            position: absolute;
            top: 40%;
            width: 100%;
            display: flex;
            justify-content: space-between;
            pointer-events: none;
            z-index: 10;
        }

        .voucher-carousel .owl-nav button {
            background-color: #007bff;
            border: none;
            border-radius: 50%;
            width: 32px;
            height: 32px;
            font-size: 16px;
            color: #fff;
            pointer-events: auto;
            transition: background 0.3s;
        }

        .voucher-carousel .owl-nav button.owl-prev {
            margin-left: -45px; /* Đẩy nút prev ra ngoài bên trái */
        }

        .voucher-carousel .owl-nav button.owl-next {
            margin-right: -45px; /* Đẩy nút next ra ngoài bên phải */
        }
        .voucher-section {
            padding-bottom: 20px !important; /* Hoặc 10px tùy bạn */
        }
        .voucher-heading {
            font-size: 28px;
            font-weight: 700;
            position: relative;
            display: inline-block;
            padding-bottom: 8px;
            margin-bottom: 40px;
            color: #000;
        }
        .voucher-heading::after {
            content: "";
            position: absolute;
            width: 60px;
            height: 4px;
            background-color: #facc15; /* vàng */
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            border-radius: 2px;
        }
    </style>
    <section class="voucher-section spad " >
        <div class="container">
            <div class="text-center">
                <h4 class="voucher-heading">🎁 Featured Deals</h4>
            </div>
            <div class="voucher-carousel owl-carousel">
                @foreach($vouchers as $voucher)
                    <div class="voucher-card border rounded shadow-sm p-3 bg-white mx-2">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="mb-1"><strong>{{ $voucher->name }}</strong></h6>
                                <p class="mb-1">{{ $voucher->description }}</p>
                                <small>EXD: {{ \Carbon\Carbon::parse($voucher->expires_at)->format('d-m-Y') }}</small>
                            </div>
                            <div class="text-end">
                                <button class="btn btn-warning btn-sm mb-2 take-code-btn"
                                        data-code="{{ $voucher->code }}"
                                        style="font-size: 12px">
                                    Take Code
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- nên đặt trước -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
    <script>
        $(document).ready(function(){
            $('.voucher-carousel').owlCarousel({
                loop: true,
                margin: 20,
                nav: true,
                dots: false,
                autoplay: true,
                autoplayTimeout: 4000,
                responsive: {
                    0: { items: 1 },
                    576: { items: 2 },
                    992: { items: 3 }
                }
            });
        });
    </script>
    <script>
        $(document).ready(function(){
            $('.take-code-btn').click(function(){
                const code = $(this).data('code');
                navigator.clipboard.writeText(code).catch(err => {
                    console.error('Failed to copy code', err);
                });
            });
        });

    </script>
    <!--Voucher section end-->
    <!--Man Banner begin-->
    <section class="man-banner spad">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-8 offset-lg-1">
                    <div class="filter-control">
                        <ul>
                            <li class="item active" data-tag="all">All</li>
                            @foreach($menSubCategories as $subCategoryMen)
                                <li class="item" data-tag="{{ $subCategoryMen->slug }}">{{ $subCategoryMen->name }}</li>
                            @endforeach
                        </ul>
                    </div>
                    <!-- Owl Carousel cho "Tất cả" sản phẩm -->
                    <div class="product-slider owl-carousel product-list all">
                        @foreach($menProductsBySubcategory as $subCategorySlug => $products)
                            @foreach($products as $product)
                                <div class="item">
                                    @include('front.components.product-item', ['product' => $product])
                                </div>
                            @endforeach
                        @endforeach
                    </div>
                    <!-- Owl Carousel cho từng subCategory -->
                    @foreach($menProductsBySubcategory as $subCategorySlug => $products)
                        <div class="product-slider owl-carousel product-list {{ $subCategorySlug }}" style="display: none;">
                            @foreach($products as $product)
                                <div class="item">
                                    @include('front.components.product-item', ['product' => $product])
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
                <div class="col-lg-3">
                    <div class="product-large set-bg" data-setbg="front/img/products/man-large.jpg">
                        <h2>Man's</h2>
                        <a href="#">Discover more</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--Man Banner end-->
    <!--Latest blog section begin-->
    <section class="latest-blog spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title">
                        <h2>From the Blog</h2>
                    </div>
                </div>
            </div>
            <div class="row">
                @foreach($latestBlogs as $blog)
                    <div class="col-lg-4 col-md-6">
                        <div class="single-latest-blog">
                            <a href="{{ route('front.blog.show', $blog->id) }}">
                            <img src="{{ asset('storage/blogs/' . $blog->image) }}" alt="{{ $blog->title }}" class="img-fluid" style="height: 200px; width: 100%; object-fit: cover; border-radius: 5px;">
                            <div class="latest-text">
                                <div class="tag-list">
                                    <div class="tag-item">
                                        <i class="fa fa-calendar-o"></i> {{ $blog->created_at->format('M d, Y') }}
                                    </div>
                                    <div class="tag-item">
                                        <i class="fa fa-comment-o"></i> {{ $blog->blogcomment->count() }}
                                    </div>
                                </div>
                                    <h4>{{ $blog->title }}</h4>

                                <p>{{ \Illuminate\Support\Str::limit(strip_tags($blog->content), 100) }}</p>
                            </div>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    <!--Latest blog section end-->
@endsection



