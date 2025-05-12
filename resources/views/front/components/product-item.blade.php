<div class="product-item item {{ $product->tags->isNotEmpty() ? $product->tags->first()->name : '' }}">
    <div class="pi-pic">
            <div class="product-item">
                <img src="{{ asset('uploads/products/large/' . $product->productImages->first()->image) }}" alt="Product Image">
            </div>
        @if($product->compare_price != null)
            <div class="sale">Sale</div>
        @endif
        @php
            $inWishlist = in_array($product->id, $wishlistIds ?? []);
        @endphp

        <div class="icon">
            <a onclick="addToWishList({{ $product->id }})"
               class="wishlist-icon {{ $inWishlist ? 'in-wishlist' : '' }}"
               href="javascript:void(0);"
               title="{{ $inWishlist ? 'Remove from Wishlist' : 'Add to Wishlist' }}">
                <i class="icon_heart_alt"></i>
            </a>
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
                    <button type="submit" class="add-to-cart-btn" >
                        <i class="icon_bag_alt"></i> Add To Cart
                    </button>
                </form>
                <a href="shop/product/{{$product->id}}">Quick view</a>
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
                    $("#wishlistModal .modal-body").html(response.message);
                  $("#wishlistModal").modal('show')
                } else {
                    window.location.href = "{{ route('account.login') }}";
                }
            },
            error: function (xhr) {
                alert('Có lỗi xảy ra khi thêm vào wishlist!');
            }
        });
    }
</script>
