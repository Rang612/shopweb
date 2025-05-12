@extends('front.layout.master')
@section('title','Product')
@section('body')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />

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
                        @if(isset($product) && $product->productImages->isNotEmpty())
                            @php $image = $product->productImages->first(); @endphp
                            <div class="col-lg-6">
                                <div class="product-pic-zoom">
                                    <img class="product-big-img" src="{{ asset('uploads/products/large/' . $image->image) }}" alt="Product Image">
                                    <div class="zoom-icon">
                                        <i class="fa fa-search-plus"></i>
                                    </div>
                                </div>
                                <div class="product-thumbs">
                                    <div class="product-thumbs-track ps-slider owl-carousel">
                                        @foreach($product->productImages as $productImage)
                                            @php
                                                $thumbSrc = asset('uploads/products/large/' . $productImage->image);
                                            @endphp
                                            <div class="pt" data-imgbigurl="{{ $thumbSrc }}">
                                                <img src="{{ $thumbSrc }}" alt="Product Thumbnail">
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="col-lg-6">
                                <p>No product images available.</p>
                            </div>
                        @endif
                        <div class="col-lg-6">
                            <div class="product-details">
                                <div class="pd-title">
                                    <span>{{$product->tag}}</span>
                                    <h3 style="display: inline-block; margin-right: 10px;">{{$product->title}}</h3>
                                    @php
                                        $inWishlist = isset($wishlistIds) && in_array($product->id, $wishlistIds);
                                    @endphp

                                    <a href="javascript:void(0);"
                                       class="heart-icon wishlist-icon {{ $inWishlist ? 'in-wishlist' : '' }}"
                                       onclick="addToWishList({{ $product->id }})"
                                       title="{{ $inWishlist ? 'In your wishlist' : 'Add to wishlist' }}">
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
                                        <!-- Submit Form -->
                                        <button type="submit" class="primary-btn pd-card">
                                            Add To Cart
                                        </button>
                                    </div>
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
                                @php
                                    $productUrl = urlencode(url('/shop/product/' . $product->id));
                                @endphp

                                <div class="pd-share">
                                    <div class="p-code"><span>Sku:</span> {{$product->sku}}</div>
                                    <div class="pd-social">
                                        <!-- Facebook Share -->
                                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ $productUrl }}"
                                           target="_blank" title="Share on Facebook">
                                            <i class="ti-facebook"></i>
                                        </a>

                                        <!-- Messenger Share -->
                                        <a href="https://www.facebook.com/dialog/send?link={{ $productUrl }}&app_id={{ env('FB_APP_ID') }}&redirect_uri={{ $productUrl }}"
                                           target="_blank" title="Share on Messenger">
                                            <i class="fab fa-facebook-messenger"></i>
                                        </a>

                                        <!-- Instagram (mở trang IG + copy link tự động) -->
                                        <a href="https://www.instagram.com/" target="_blank" onclick="copyProductLink('{{ url('/shop/product/' . $product->id) }}')" title="Copy link & share on Instagram">
                                            <i class="ti-instagram"></i>
                                        </a>
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
{{--                                    <div class="leave-comment">--}}
{{--                                        <h4>Leave A Comment</h4>--}}
{{--                                        <form action="" method="post" class="comment-form">--}}
{{--                                            @csrf--}}
{{--                                            <input type="hidden" name="product_id" value="{{$product->id}}">--}}
{{--                                            <input type="hidden" name="user_id" value="{{Illuminate\Support\Facades\Auth::user()->id ?? null}}">--}}
{{--                                            <div class="row">--}}
{{--                                                <div class="col-lg-6">--}}
{{--                                                    <input type="text" placeholder="Name" name="name">--}}
{{--                                                </div>--}}
{{--                                                <div class="col-lg-6">--}}
{{--                                                    <input type="text" placeholder="Email" name="email">--}}
{{--                                                </div>--}}
{{--                                                <div class="col-lg-12">--}}
{{--                                                    <textarea placeholder="Messages" name="messages"></textarea>--}}
{{--                                                    <div class="rate">--}}
{{--                                                        <div class="personal-rating">--}}
{{--                                                            <h6>Your Rating</h6>--}}
{{--                                                            <div class="rate">--}}
{{--                                                                <input type="radio" id="star5" name="rating" value="5" />--}}
{{--                                                                <label for="star5" title="text">5 stars</label>--}}
{{--                                                                <input type="radio" id="star4" name="rating" value="4" />--}}
{{--                                                                <label for="star4" title="text">4 stars</label>--}}
{{--                                                                <input type="radio" id="star3" name="rating" value="3" />--}}
{{--                                                                <label for="star3" title="text">3 stars</label>--}}
{{--                                                                <input type="radio" id="star2" name="rating" value="2" />--}}
{{--                                                                <label for="star2" title="text">2 stars</label>--}}
{{--                                                                <input type="radio" id="star1" name="rating" value="1" />--}}
{{--                                                                <label for="star1" title="text">1 star</label>--}}
{{--                                                            </div>--}}
{{--                                                        </div>--}}
{{--                                                    </div>--}}
{{--                                                    <button type="submit" class="site-btn">Send Messages</button>--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                        </form>--}}
{{--                                    </div>--}}
                                            <div class="leave-comment mt-5">
                                                <h4>Leave A Comment</h4>
                                                @if(session('success'))
                                                    <div class="alert alert-success">
                                                        {{ session('success') }}
                                                    </div>
                                                @endif

                                                @auth
                                                    <form action="{{ route('product.comment', $product->id) }}" method="POST" class="comment-form">
                                                        @csrf
                                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                        <input type="hidden" name="user_id" value="{{ Auth::id() }}">

                                                        <div class="row co-item align-items-center mb-3">
                                                            <div class="col-auto">
                                                                <div class="avatar-pic">
                                                                    <img src="{{ asset('front/img/user/' . (Auth::user()->avatar ?? 'default-avatar.png')) }}" alt="User Avatar" style="width: 63px; height: 63px; border-radius: 50%;">
                                                                </div>
                                                            </div>
                                                            <div>
                                                                <h5 class="mt-2">{{ Auth::user()->name }}</h5>
                                                            </div>
                                                        </div>

                                                        <div class="form-group mb-3">
                                                            <label class="mb-2">Your Rating</label>
                                                            <div class="star-rating">
                                                                @for ($i = 5; $i >= 1; $i--)
                                                                    <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}">
                                                                    <label for="star{{ $i }}">&#9733;</label>
                                                                @endfor
                                                            </div>
                                                        </div>

                                                        <div class="mb-3">
                                                            <textarea class="form-control" name="messages" rows="4" placeholder="Write your comment..." required></textarea>
                                                        </div>

                                                        <button type="submit" class="site-btn">Send Comment</button>
                                                    </form>
                                                @else
                                                    <div class="alert alert-warning mt-3">
                                                        Please <a href="{{ route('account.login') }}" class="text-primary">log in</a> to leave a comment.
                                                    </div>
                                                @endauth


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
<script>
    function addToWishList(id){
        $.ajax({
            url: '/wishlist/add-to-wishlist/' + id,
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function (response) {
                if (response.status === true) {
                    let icon = document.querySelector('.wishlist-icon');
                    if(icon) icon.classList.add('in-wishlist');
                } else {
                    window.location.href = "{{ route('account.login') }}";
                }
            },
            error: function () {
                alert('Có lỗi xảy ra khi thêm vào wishlist!');
            }
        });
    }

    function copyProductLink(link) {
        navigator.clipboard.writeText(link)
            .then(() => {
                alert('Link copied! Open Instagram and paste it in your story or bio.');
            })
            .catch(err => {
                alert('Failed to copy link.');
            });
    }


</script>
