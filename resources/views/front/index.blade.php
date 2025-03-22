@extends('front.layout.master')

@section('title','Home')
@section('body')
    <!---->
    <!--Hero section begin-->
    <section class="hero-section ">
        <div class="hero-items owl-carousel " style="padding-top: 235px;">
            <div class="single-hero-items set-bg" data-setbg="front/img/hero-1.jpg">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-5">
                            <span>quần áo người lớn , trẻ em</span>
                            <h1> Black Friday</h1>
                            <p>
                                Sale-off
                            </p>
                            <a href="#" class="primary-btn">Mua ngay</a>
                        </div>
                    </div>
                    <div class="off-card">
                        <h2>  Giảm giá <span>50%</span></h2>
                    </div>
                </div>

            </div>
            <div class="single-hero-items set-bg" data-setbg="front/img/hero-2.jpg">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-5">
                            <span>quần áo người lớn , trẻ em</span>
                            <h1> Black Friday</h1>
                            <p>
                                Sale-off
                            </p>
                            <a href="#" class="primary-btn">Mua ngay</a>
                        </div>
                    </div>
                    <div class="off-card">
                        <h2>  Giảm giá<span>50%</span></h2>
                    </div>
                </div>

            </div>
            <div class="single-hero-items set-bg" data-setbg="front/img/hero-3.jpg">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-7">
                            <span style="color: white;">quần áo người lớn , trẻ em</span>
                            <h1> Black Friday</h1>
                            <p style="color: white;">
                                Sale-off
                            </p>
                            <a href="#" class="primary-btn" >Mua ngay</a>
                        </div>
                    </div>
                    <div class="off-card">
                        <h2>  Giảm giá<span>50%</span></h2>
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
                        </div>
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
                        <a href="#">Discover more</a>
                    </div>
                </div>
                <div class="col-lg-8 offset-lg-1">
                    <div class="filter-control">
                        <ul>
                            <li class="item active" data-tag="*" data-category="women">Tất cả</li>
                            <li class="item" data-tag=".Clothing" data-category="women">Áo</li>
                            <li class="item" data-tag=".HandBag" data-category="women">Quần</li>
                            <li class="item" data-tag=".Shoes" data-category="women">Váy</li>
                            <li class="item" data-tag=".Accessories" data-category="women">Đồ bộ</li>
                        </ul>
                    </div>
                    <div class="product-slider owl-carousel women">
                        @foreach($womenProducts as $womenProduct)
                            @include('front.components.product-item', ['product' => $womenProduct])
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--Women banner section end-->
    <!--Deal of the week section begin-->
    <section class="deal-of-week set-bg spad" data-setbg="front/img/time-bg.jpg">
        <div class="container">
            <div class="col-lg-6 text-center">
                <div class="section title">
                    <h2>Ưu đãi trong tuần</h2>
                    <p>Giảm giá</p>
                    <div class="product-price">
                        100.000đ
                        <span> Túi xách</span>
                    </div>
                </div>
                <div class="countdown-tiner" id="countdown">
                    <div class="cd-item">
                        <span>56</span>
                        <p>Ngày</p>
                    </div>
                    <div class="cd-item">
                        <span>12</span>
                        <p>Giờ</p>
                    </div>
                    <div class="cd-item">
                        <span>48</span>
                        <p>Phút</p>
                    </div>
                    <div class="cd-item">
                        <span>56</span>
                        <p>Giây</p>
                    </div>
                </div>
                <a href="" class="primary-btn">Mua ngay</a>
            </div>
        </div>
    </section>
    <!--Deal of the week section end-->
    <!--Man Banner begin-->
    <section class="man-banner spad">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-8 offset-lg-1">
                    <div class="filter-control">
                        <ul>
                            <li class="item active" data-tag="*" data-category="men">Tất cả</li>
                            <li class="item" data-tag=".Clothing" data-category="men">Áo</li>
                            <li class="item" data-tag=".HandBag" data-category="men">Quần</li>
                            <li class="item" data-tag=".Shoes" data-category="men">Vest-Comple</li>
                            <li class="item" data-tag=".Accessories" data-category="men">Đồ bộ</li>
                        </ul>
                    </div>
                    <div class="product-slider owl-carousel men">
                        @foreach($menProducts as $menProduct)
                            @include('front.components.product-item', ['product' => $menProduct])
                        @endforeach

                    </div>
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
{{--                @foreach($blogs as $blog)--}}
                <div class="col-lg-4 cot-md-6">
                    <div class="single-latest-blog">
{{--                        <img src="front/img/blog/{{$blog->image}}" alt="">--}}
                        <div class="latest-text">
                            <div class="tag-list">
                                <div class="tag-item">
                                    <i class="fa fa-calender-o">
{{--                                       {{ date('M d, Y', strtotime($blog->created_at)) }}--}}
                                    </i>
                                </div>
                                <div class="tag-item">
                                    <i class="fa fa-comment-o">
{{--                                        {{count($blog->blogcomment)}}--}}
                                    </i>
                                </div>
                            </div>
                            <a href="">
{{--                                <h4>{{{$blog->title}}}</h4>--}}
                            </a>
{{--                            <p>{{$blog->content}}</p>--}}
                        </div>
                    </div>
                </div>
{{--                @endforeach--}}
            </div>
        </div>

    </section>
    <!--Latest blog section end-->
@endsection
