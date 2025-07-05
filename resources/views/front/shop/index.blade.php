@extends('front.layout.master')
@section('title', 'Shop')
@section('body')
<!--Breadcrumb section begin(giup dinh vi vi tri ban dang o dau trong web)-->
<div class="breacrumb-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb-text">
                    <a href="{{route('front.home')}}"><i class="fa fa-home"></i>Home</a>
                    <span>Shop</span>
                </div>
            </div>
        </div>
    </div>
</div>
<!--Breadcrumb section end-->
<!--Product shop section begin-->
<section class="product-shop spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-8 order-2 order-lg-1 produts-sidebar-filter">
               @include('front.shop.components.products-sidebar-filter')
            </div>
            <div class="col-lg-9 order-1 order-lg-2">
                <div class="product-show-option">
                    <div class="row">
                        <div class="col-lg-7 col-md-7">
                            <form action="" method="GET">
                                <input type="hidden" name="brand" value="{{ implode(',', (array) request('brand')) }}">
                                <input type="hidden" name="price_min" value="{{ implode(',', (array) request('price_min')) }}">
                                <input type="hidden" name="price_max" value="{{ implode(',', (array) request('price_max')) }}">
                                <input type="hidden" name="tags" value="{{ implode(',', (array) request('tags')) }}">
                                <div class="select-option">
                                    <select name="sort_by" class="sorting" onchange="this.form.submit();">
                                        <option {{ request('sort_by') == 'latest' ? 'selected' : '' }} value="latest">Latest</option>
                                        <option {{ request('sort_by') == 'oldest' ? 'selected' : '' }} value="oldest">Oldest</option>
                                        <option {{ request('sort_by') == 'name-ascending' ? 'selected' : '' }} value="name-ascending">Name A-Z</option>
                                        <option {{ request('sort_by') == 'name-descending' ? 'selected' : '' }} value="name-descending">Name Z-A</option>
                                        <option {{ request('sort_by') == 'price-ascending' ? 'selected' : '' }} value="price-ascending">Price Ascending</option>
                                        <option {{ request('sort_by') == 'price-descending' ? 'selected' : '' }} value="price-descending">Price Descending</option>
                                    </select>
                                </div>
                            </form>
                        </div>
                        <div class="col-lg-5 col-md-5 text-right">
                        </div>
                    </div>
                </div>
                <div class="product-list-product">
                    @if($products->count() > 0)
                        <div class="row">
                            @foreach($products as $product)
                                <div class="col-lg-4 col-sm-6">
                                    @include('front.components.product-item', ['product' => $product])
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-center">No products found.</p>
                    @endif
                </div>
                {{ $products->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</section>
<!--Product shop section end-->
@endsection
