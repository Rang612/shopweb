@extends('front.layout.master')
@section('title','My Wishlist')
@section('body')

    <div class="breacrumb-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb-text">
                        <a href="{{ route('front.home') }}"><i class="fa fa-user"></i> My Account</a>
                        <span>My Wishlist</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="product-shop spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-3">
                    @include('front.account.common.sidebar')
                </div>
                <div class="col-lg-9">
                    <div class="row">
                        @forelse($wishlists as $wishlist)
                            @php $product = $wishlist->product; @endphp
                            <div class="col-lg-4 col-md-6">
                                <div class="product-item item {{ $product->tags->isNotEmpty() ? $product->tags->first()->name : '' }}">
                                    <div class="pi-pic">
                                        <a href="{{ route('front.product.detail', $product->id) }}">
                                            <img src="{{ asset('storage/products/' . $product->productImages->first()->image) }}" alt="Product Image">
                                        </a>
                                        @if($product->compare_price)
                                            <div class="sale">Sale</div>
                                        @endif
                                        <div class="icon">
                                            <form action="{{ route('front.removeToWishlist', $wishlist->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="wishlist" style="background:none;border:none;color:red;">
                                                    <i class="icon_heart_alt"></i>
                                                </button>
                                            </form>
                                        </div>
                                        <ul>
                                            <li class="w-icon active">
                                                <form action="{{ route('cart.add', $product->id) }}" method="POST">
                                                    @csrf
                                                    <select name="size" required>
                                                        <option value="">Choose Size</option>
                                                        @foreach($product->productDetail->pluck('size')->unique() as $size)
                                                            <option value="{{ $size }}">{{ $size }}</option>
                                                        @endforeach
                                                    </select>
                                                    <select name="color" required>
                                                        <option value="">Choose Color</option>
                                                        @foreach($product->productDetail->pluck('color')->unique() as $color)
                                                            <option value="{{ $color }}">{{ $color }}</option>
                                                        @endforeach
                                                    </select>
                                                    <button type="submit" class="add-to-cart-btn">
                                                        <i class="icon_bag_alt"></i> Add To Cart
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="pi-text">
                                        <div class="category-name">{{ $product->tag }}</div>
                                        <a href="{{ url('shop/product/' . $product->id) }}">
                                            <h5>{{ $product->title }}</h5>
                                        </a>
                                        <div class="product-price">
                                            @if($product->compare_price)
                                                {{ number_format($product->compare_price, 0, ',', '.') }} VND
                                                <span>{{ number_format($product->price, 0, ',', '.') }} VND</span>
                                            @else
                                                {{ number_format($product->price, 0, ',', '.') }} VND
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-lg-12 text-center">
                                <h4>Your wishlist is empty!</h4>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
        @if($recommendedProducts->isNotEmpty())
            <div class="recommended-products spad mt-5">
                <div class="container">
                    <h4 class="mb-4">Recommended For You</h4>
                    <div class="row">
                        @foreach($recommendedProducts as $product)
                            <div class="col-lg-3 col-sm-6">
                                @include('front.components.product-item', ['product' => $product])
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

    </section>

@endsection
