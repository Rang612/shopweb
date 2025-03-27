<form action="{{request()->segment(2) == 'product' ? 'shop' : '' }}" method="GET">
    <div class="filter-widget">
        <h4 class="fw-title">Categories</h4>
        <ul class="filter-catagories">
            @foreach($categories as $category)
                <li><a href="shop/{{$category->slug}}">{{$category->name}}</a></li>
            @endforeach
        </ul>
    </div>
    <div class="filter-widget">
        <h4 class="fw-title">Brand</h4>
        <div class="fw-brand-check">
            @foreach($brands as $brand)
                <div class="bc-item">
                    <label for="bc-{{$brand->id}}">
                        {{$brand->name}}
                        <input type="checkbox"
                               {{ request()->has("brand.{$brand->id}") ? 'checked' : '' }}
                               id="bc-{{$brand->id}}"
                               name="brand[{{$brand->id}}]">
                        <span class="checkmark"></span>
                    </label>
                </div>
            @endforeach
        </div>
    </div>
    <div class="filter-widget">
        <h4 class="fw-title">Price</h4>
        <div class="filter-range-wrap">
            <div class="range-slider">
                <div class="price-display">
                    <div>
                        <span>Min:</span>
                        <input type="text" id="minamount" name="price_min"
                               value="{{ number_format((float) request('price_min', 50000), 0, ',', '.') }}VND">
                    </div>
                    <div>
                        <span>Max:</span>
                        <input type="text" id="maxamount" name="price_max"
                               value="{{ number_format((float) request('price_max', 50000000), 0, ',', '.') }}VND">
                    </div>
                </div>
            </div>
            <div id="price-slider"
                 class="price-range ui-slider ui-corner-all ui-slider-horizontal ui-widget ui-widget-content"
                 data-min="50000"
                 data-max="5000000"
                 data-min-value="{{ (int) preg_replace('/[^\d]/', '', request('price_min', 50000)) }}"
                 data-max-value="{{ (int) preg_replace('/[^\d]/', '', request('price_max', 5000000)) }}">
            </div>

        </div>
    </div>
    <div class="filter-widget">
        <h4 class="fw-title">Size</h4>
        <div class="fw-size-choose">
            @foreach(['s', 'm', 'l', 'xs'] as $size)
                <div class="sc-item">
                    <input type="checkbox" id="{{ $size }}-size" name="size[]" value="{{ $size }}"
                        {{ in_array($size, (array) request('size')) ? 'checked' : '' }}>
                    <label for="{{ $size }}-size"
                           class="{{ in_array($size, (array) request('size')) ? 'active' : '' }}">
                        {{ strtoupper($size) }}
                    </label>
                </div>
            @endforeach
        </div>
    </div>
    <div class="filter-widget">
        <h4 class="fw-title">Tags</h4>
        <div class="fw-tags">
            @foreach($tags as $tag)
                <input type="checkbox" id="tag-{{ $tag->id }}" name="tags[]" value="{{ $tag->id }}"
                    {{ is_array(request('tags')) && in_array($tag->id, request('tags')) ? 'checked' : '' }}>
                <label for="tag-{{ $tag->id }}">{{ $tag->name }}</label>
            @endforeach
        </div>
    </div>
    <div class="filter-widget">
        <button type="submit" class="filter-btn">Filter</button>
    </div>
</form>
