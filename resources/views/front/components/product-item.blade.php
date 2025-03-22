<div class="product-item item {{$product->tag}}">
    <div class="pi-pic">
        @php
            $image = $product->product_images->first();
        @endphp
        @if(!empty($image->imgur_link))
            @php
                $imageData = base64_encode(file_get_contents($image->imgur_link));
                $src = 'data:image/jpeg;base64,' . $imageData;
            @endphp
            <img src="{{ $src }}" alt="Category Image">
        @endif        @if($product->compare_price != null)
            <div class="sale">Sale</div>
        @endif
        <div class="icon">
            <i class="icon_heart_alt"></i> </div>
        <ul>
            <li class="w-icon active"><a href="./cart/add/{{$product->id}}"><i class="icon_bag_alt"></i></a></li>
            <li class="quick-view"><a href="shop/product/{{$product->id}}">Quick view</a></li>
            <li><a href="w-icon"><i class="fa fa-random"></i></a></li>
        </ul>

    </div>
    <div class="pi-text">
        <div class="category-name">{{$product->tag}}</div>
        <a href="shop/product/{{$product->id}}">
            <h5>{{$product->title}}</h5>
        </a>
        <div class="product-price">
            @if($product->compare_price != null)
                ${{$product->compare_price}}
                <span>${{$product->price}}</span>
            @else
                ${{$product->price}}
            @endif
        </div>
    </div>
</div>
