@extends('front.layout.master')
@section('title','Product')
@section('body')
     <!--Breadcrumb section begin(giup dinh vi vi tri ban dang o dau trong web)-->
     <div class="breacrumb-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb-text">
                        <a href="{{ route('front.home') }}"><i class="fa fa-home"></i>Home</a>
                        <a href="{{ route('front.home') }}">Shop</a>
                        <span>Detail</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--Breadcrumb section end-->
    <!--Product Shop section begin -->
    <section class="product-shop spad page-details">
        <div class="container">
            <div class="row">
                <div class="col-lg-3">
                    @include('front.shop.components.products-sidebar-filter')
                </div>
                <div class="col-lg-9">
                    <div class="row">
                        @if(isset($product) && !empty($product->productImages) && $product->productImages->count() > 0)
                            @php
                                $image = $product->productImages->first();
                            @endphp
                            @if(!empty($image->imgur_link))
                                @php
                                    $imageData = base64_encode(file_get_contents($image->imgur_link));
                                    $src = 'data:image/jpeg;base64,' . $imageData;
                                @endphp
                            @endif
                            <div class="col-lg-6">
                                <div class="product-pic-zoom">
                                    <img class="product-big-img" src="{{ $src }}" alt="Product Image">
                                    <div class="zoom-icon">
                                        <i class="fa fa-search-plus"></i>
                                    </div>
                                </div>
                                <div class="product-thumbs">
                                    <div class="product-thumbs-track ps-slider owl-carousel">
                                        @foreach($product->productImages as $productImage)
                                            @php
                                                $thumbSrc = null;
                                                if (!empty($productImage->imgur_link)) {
                                                    try {
                                                        $thumbData = base64_encode(file_get_contents($productImage->imgur_link));
                                                        $thumbSrc = 'data:image/jpeg;base64,' . $thumbData;
                                                    } catch (Exception $e) {
                                                        $thumbSrc = $productImage->imgur_link; // Nếu lỗi, fallback về link gốc
                                                    }
                                                }
                                            @endphp
                                            @if($thumbSrc)
                                                <div class="pt active" data-imgbigurl="{{ $thumbSrc }}">
                                                    <img src="{{ $thumbSrc }}" alt="Product Thumbnail">
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @else
                            <p>No product images available.</p>
                        @endif
                        <div class="col-lg-6">
                            <div class="product-details">
                                <div class="pd-title">
                                    <span>{{$product->tag}}</span>
                                    <h3 style="display: inline-block; margin-right: 10px;">{{$product->title}}</h3>
                                    <a href="#" class="heart-icon">
                                        <i class="icon_heart_alt"></i>
                                    </a>
                                </div>
                                <div class="pd-rating">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= floor($avgRating))
                                            <i class="fa fa-star" style="color: gold;"></i>
                                        @elseif($i == ceil($avgRating) && $avgRating - floor($avgRating) >= 0.5)
                                            <i class="fa fa-star-half-o" style="color: gold;"></i>
                                        @else
                                            <i class="fa fa-star-o" style="color: gray;"></i>
                                        @endif
                                    @endfor
                                    <span>({{ round($avgRating, 1) }})</span>
                                </div>
                                <div class="pd-desc">
                                    <p>{!! $product->description !!}</p> <!--giới thiệu về sản phẩm-->
                                    @if($product->compare_price != null)
                                    <h4>{{ number_format($product->compare_price, 0, ',', '.') }} VND<span>{{ number_format($product->price, 0, ',', '.') }} VND</span></h4>  <!--giá sản phẩm-->
                                    @else
                                    <h4>${{$product->price}}</h4>
                                    @endif
                                </div>
                                <form action="{{ route('cart.add', $product->id) }}" method="POST">
                                    @csrf

                                    <!-- Chọn màu sắc -->
                                    <div class="pd-color">
                                        <h6>Color</h6>
                                        <div class="pd-color-choose">
                                            @if(!empty($product->productDetail) && $product->productDetail->isNotEmpty())
                                                @foreach(array_unique(array_column($product->productDetail->toArray(), 'color')) as $productColor)
                                                    @if(!empty($productColor))
                                                        <div class="cc-item">
                                                            <input type="radio" id="cc-{{$productColor}}" name="color" value="{{$productColor}}" required>
                                                            <label for="cc-{{$productColor}}" style="background-color: {{$productColor}};" class="color-label"></label>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            @else
                                                <p>No colors available</p>
                                            @endif
                                        </div>
                                    </div>
                                    <!-- Chọn kích thước -->
                                    <div class="pd-size">
                                        <h6>Size</h6>
                                        <div class="pd-size-choose">
                                            @foreach(array_unique(array_column($product->productDetail->toArray(), 'size')) as $productSize)
                                                <div class="sc-item">
                                                    <input type="radio" id="{{$productSize}}-size" name="size" value="{{$productSize}}" required>
                                                    <label for="{{$productSize}}-size">{{$productSize}}</label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    <!-- Chọn số lượng -->
                                    <div class="quantity">
                                        <div class="pro-qty">
                                            <input type="number" name="quantity" id="quantity" value="1" min="1" required>
                                        </div>
                                    </div>

                                    <!-- Submit Form -->
                                    <button type="submit" class="primary-btn pd-card">
                                        Add To Cart
                                    </button>
                                </form>
                                <ul class="pd-tags">
                                    <li><span>BRAND</span>: {{$product->brand->name}}</li>
                                    <li><span>CATEGORIES</span>: {{$product->productcategory->name}}</li>
                                    <li>
                                        <span>TAGS:</span>
                                        @if(!empty($product->tags) && $product->tags->isNotEmpty())
                                            @foreach($product->tags as $tag)
                                                <span class="tag-box">{{ $tag->name }}</span>
                                            @endforeach
                                        @else
                                            <span>No tags available</span>
                                        @endif
                                    </li>
                                </ul>
                                <div class="pd-share">
                                    <div class="p-code"><span>Sku:</span> {{$product->sku}}</div>
                                    <div class="pd-social">
                                        <a href="https://www.facebook.com/share/161FYBzort/" target="_blank"><i class="ti-facebook"></i></a>
                                        <a href="http://www.instagram.com/rang_rang0612" target="_blank"><i class="ti-instagram"></i></a>
                                        <a href="https://www.tiktok.com/@rangrang0612?_t=ZS-8ty2P5gjrM8&_r=1" target="_blank"><i class="fa-brands fa-tiktok"></i></a>
                                        <a href="https://www.youtube.com" target="_blank"><i class="fa-brands fa-youtube"></i></a>
                                    </div>
                                </div>
                        </div>
                    </div>
                    </div>
                    <div class="product-tab">
                        <div class="tab-item">
                            <ul class="nav" role="tablist">
                                <li><a class="active" href="#tab-1" data-toggle="tab" role="tab">DESCRIPTION</a></li>
                                <li><a href="#tab-2" data-toggle="tab" role="tab">SPECIFICATIONS</a></li>
                                <li><a href="#tab-3" data-toggle="tab" role="tab">Customer Reviews ({{count($product->productComment)}})</a></li>
                            </ul>
                        </div>
                        <div class="tab-item-content">
                            <div class="tab-content">
                                <div class="tab-pane fade-in active" id="tab-1" role="tabpanel">
                                    <div class="product-content">
                                     {!! $product->description !!}
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="tab-2" role="tabpanel">
                                        <div class="specification-table">
                                            <table>
                                                <tr>
                                                    <td class="p-catagory">Customer Rating</td>
                                                    <td>
                                                        <div class="pd-rating">
                                                            @for($i = 1; $i <= 5; $i++)
                                                                @if($i <= floor($avgRating))
                                                                    <i class="fa fa-star" style="color: gold;"></i>
                                                                @elseif($i == ceil($avgRating) && $avgRating - floor($avgRating) >= 0.5)
                                                                    <i class="fa fa-star-half-o" style="color: gold;"></i>
                                                                @else
                                                                    <i class="fa fa-star-o" style="color: gray;"></i>
                                                                @endif
                                                            @endfor
                                                            <span>({{ round($avgRating, 1) }})</span>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="p-catagory">Price</td>
                                                    <td>
                                                        <div class="p-price">
                                                            {{ number_format($product->price, 0, ',', '.') }} VND
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="p-catagory">Brand</td>
                                                    <td>
                                                        <div class="p-stock"><span>{{$product->brand->name}}</span></div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="p-catagory">Availability</td>
                                                    <td>
                                                        <div class="p-stock">{{$product->qty}}</div> <!-- tồn kho-->
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="p-catagory">Size</td>
                                                    <td>
                                                        <div class="p-size">
                                                            @foreach(array_unique(array_column($product->productdetail->toArray(), 'size')) as $productSize)
                                                          {{$productSize}}
                                                        @endforeach
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="p-catagory">Color</td>
                                                    <td>
                                                        <div class="cs-color">
                                                            @foreach($product->productdetail as $detail)
                                                                @php
                                                                    $color = strtolower(trim($detail->color)); // Chuyển về chữ thường và xóa khoảng trắng
                                                                @endphp
                                                                <span class="cs-item cs-{{ $color }}" title="{{ ucfirst($color) }}"></span>
                                                            @endforeach
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="p-catagory">Sku</td>
                                                    <td>
                                                        <div class="p-code">{{$product->sku}}</div>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                </div>
                                <div class="tab-pane fade" id="tab-3" role="tabpanel">
                                  <div class="customer-review-option">
                                    <h4>{{count($product->productComment)}} Comments</h4>
                                    <div class="comment-option">
                                        @foreach($product->productComment as $productComment)
                                        <div class="co-item">
                                            <div class="avatar-pic">
                                                <img src="front/img/user/{{$productComment->user->avatar ?? 'default-avatar.png'}}" atl="">
                                            </div>
                                            <div class="avatar-text">
                                                <div class="at-rating">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        @if($i <= floor($productComment->rating))
                                                            <i class="fa fa-star" style="color: gold;"></i>
                                                        @elseif($i == ceil($productComment->rating) && $productComment->rating - floor($productComment->rating) >= 0.5)
                                                            <i class="fa fa-star-half-o" style="color: gold;"></i>
                                                        @else
                                                            <i class="fa fa-star-o" style="color: gray;"></i>
                                                        @endif
                                                    @endfor
                                                </div>
                                            </div>
                                            <h5>{{$productComment->name}},<span> {{date('M d, Y', strtotime($productComment->created_at))}}</span></h5>
                                            <div class="at-reply">{{$productComment->messages}}</div>
                                        </div>
                                        @endforeach
                                    <div class="leave-comment">
                                        <h4>Leave A Comment</h4>
                                        <form action="" method="post" class="comment-form">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{$product->id}}">
                                            <input type="hidden" name="user_id" value="{{Illuminate\Support\Facades\Auth::user()->id ?? null}}">
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <input type="text" placeholder="Name" name="name">
                                                </div>
                                                <div class="col-lg-6">
                                                    <input type="text" placeholder="Email" name="email">
                                                </div>
                                                <div class="col-lg-12">
                                                    <textarea placeholder="Messages" name="messages"></textarea>
                                                    <div class="rate">
                                                        <div class="personal-rating">
                                                            <h6>Your Rating</h6>
                                                            <div class="rate">
                                                                <input type="radio" id="star5" name="rating" value="5" />
                                                                <label for="star5" title="text">5 stars</label>
                                                                <input type="radio" id="star4" name="rating" value="4" />
                                                                <label for="star4" title="text">4 stars</label>
                                                                <input type="radio" id="star3" name="rating" value="3" />
                                                                <label for="star3" title="text">3 stars</label>
                                                                <input type="radio" id="star2" name="rating" value="2" />
                                                                <label for="star2" title="text">2 stars</label>
                                                                <input type="radio" id="star1" name="rating" value="1" />
                                                                <label for="star1" title="text">1 star</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <button type="submit" class="site-btn">Send Messages</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                  </div>
                                </div>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--Product Shop section end -->
    <!--Realeted Products section begin-->
    <div class="related-products spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title">
                       <h2>Realeted Products</h2>
                    </div>
                </div>
            </div>
            <div class="row">
                @foreach($relatedProducts as $relatedProduct)
                <div class="col-lg-3 col-sm-6">
                    @include('front.components.product-item', ['product' => $relatedProduct])
                </div>
                @endforeach
            </div>

        </div>
    </div>
    <!-- Realeted Products section end -->
@endsection
