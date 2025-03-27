<div class="product-item item {{ $product->tags->isNotEmpty() ? $product->tags->first()->name : '' }}">
    <div class="pi-pic">
{{--        @php--}}
{{--            $image = $product->productImages->first();--}}
{{--        @endphp--}}
{{--        @if(!empty($image->imgur_link))--}}
{{--            @php--}}
{{--                $imageData = base64_encode(file_get_contents($image->imgur_link));--}}
{{--                $src = 'data:image/jpeg;base64,' . $imageData;--}}
{{--            @endphp--}}
{{--            <img src="{{ $src }}" alt="Category Image">--}}
{{--        @endif        --}}
            <div class="product-item">
                <img src="{{ asset('storage/products/' . $product->productImages->first()->image) }}" alt="Product Image">
            </div>
        @if($product->compare_price != null)
            <div class="sale">Sale</div>
        @endif
        <div class="icon">
            <i class="icon_heart_alt"></i> </div>
{{--        <ul>--}}
{{--            <li class="w-icon active"><a href="./cart/add/{{$product->id}}"><i class="icon_bag_alt"></i></a></li>--}}
{{--            <li class="w-icon active">--}}
{{--                <form action="{{ route('cart.add', $product->id) }}" method="POST">--}}
{{--                    @csrf--}}
{{--                    <select name="size" required>--}}
{{--                        <option value="">Chọn kích cỡ</option>--}}
{{--                        @foreach($product->productDetail->pluck('size')->unique() as $size)--}}
{{--                            <option value="{{ $size }}">{{ $size }}</option>--}}
{{--                        @endforeach--}}
{{--                    </select>--}}

{{--                    <select name="color" required>--}}
{{--                        <option value="">Chọn màu sắc</option>--}}
{{--                        @foreach($product->productDetail->pluck('color')->unique() as $color)--}}
{{--                            <option value="{{ $color }}">{{ $color }}</option>--}}
{{--                        @endforeach--}}
{{--                    </select>--}}
{{--                    <button type="submit">--}}
{{--                        <i class="icon_bag_alt"></i>--}}
{{--                    </button>--}}
{{--                </form>--}}
{{--            </li>--}}
{{--            <li class="w-icon active">--}}
{{--                <a href="#" class="open-modal" data-id="{{ $product->id }}" data-title="{{ $product->title }}">--}}
{{--                    <i class="icon_bag_alt"></i>--}}
{{--                </a>--}}
{{--            </li>--}}
{{--            <li class="quick-view"><a href="shop/product/{{$product->id}}">Quick view</a></li>--}}
{{--            <li><a href="w-icon"><i class="fa fa-random"></i></a></li>--}}
{{--        </ul>--}}
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

                    <button type="submit" class="add-to-cart-btn" >
                        <i class="icon_bag_alt"></i> Add To Cart
                    </button>
                    <a href="shop/product/{{$product->id}}">Quick view</a>
                </form>
            </li>
        </ul>
    </div>
    <div class="pi-text">
        <div class="category-name">{{$product->tag}}</div>
        <a href="shop/product/{{$product->id}}">
            <h5>{{$product->title}}</h5>
        </a>
        <div class="product-price">
            @if($product->compare_price != null)
                {{ number_format($product->compare_price, 0, ',', '.') }} VND
                <span>{{ number_format($product->price, 0, ',', '.') }} VND</span>
            @else
                {{ number_format($product->price, 0, ',', '.') }} VND
            @endif
        </div>
    </div>
</div>

