@extends('front.layout.master')
@section('title', 'Shopping Cart')
@section('body')
<!-- Breadcrumb Section begin -->
<div class="breacrumb-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb-text">
                    <a href="index.html"><i class="fa fa-home"></i>Home</a>
                    <a href="shop.html">Shop</a>
                    <span>Shopping-Cart</span>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Breadcrumb Section end -->
<!-- Shopping Cart section begin -->
<div class="shopping-cart spad">
    <div class="container">
        <div class="row">
            @if(Cart::count() > 0)
            <div class="col-lg-12">
                <div class="cart-table">
                    <table>
                        <thead>
                        <tr>
                            <th>Image</th>
                            <th class="p-name">Product Name</th>
                            <th>Color</th>
                            <th>Size</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Total</th>
                            <th><i onclick="confirm('Are you sure remove all products ?') === true ? window.location='./cart/destroy' : '' " class="ti-close" style="cursor: pointer"></i>
                            </th>
                        </tr>

                        </thead>

                        <tbody>
                        @foreach($carts as $cart)
                           <tr>
                               <td class="cart-pic first-row">
                                   <img style="height: 170px" src="{{ $cart->options->image ?? asset('front/img/default.jpg') }}" alt="Product Image">
                               </td>
                               <td class="cart-title first-row">
                                <h5>{{$cart->name}}</h5>
                               </td>
                               <td class="cart-color first-row">
                                   <h5>{{$cart->options->color}}</h5>
                               </td>
                               <td class="cart-color first-row">
                                   <h5>{{$cart->options->size}}</h5>
                               </td>
                                <td class="p-price first-row">{{number_format($cart->price)}}VND</td>
                                <td class="qua-col first-row">
                                    <div class="quantity">
                                        <div class="pro-qty">
                                            <input type="text" value="{{$cart->qty}}" data-rowid="{{$cart->rowId}}">
                                        </div>
                                    </div>
                                </td>
                                <td class="total-price first-row">{{number_format($cart->price * $cart->qty)}}VND</td>
                                <td class="close-td first-row"><i onclick="window.location='./cart/delete/{{$cart->rowId}}'" class="ti-close"></i></td>
                           </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="row">
                    <div class="col-lg-4">
                        <div class="cart-buttons">
                            <a href="#" class="primary-btn continue-shop">Continue Shopping</a>
                            <a href="#" class="primary-btn up-cart">Update Cart</a>
                        </div>
                        <div class="discount-coupon">
                            <h6>Discount Code</h6>
                            @if(session('discountMessage'))
                                <div class="alert alert-info">
                                    {{ session('discountMessage') }}
                                </div>
                            @endif
                            <form method="GET" action="{{ route('cart.index') }}" class="coupon-form">
                                <input type="text" name="coupon" placeholder="Enter discount code"
                                       value="{{ request('coupon') }}" class="input-coupon">
                                <button type="submit" class="button-apply">Apply</button>
                            </form>
                        </div>
                    </div>
                    <div class="col-lg-4 offset-lg-4">
                        <div class="proceed-checkout">
                        <ul>
                            <li class="subtotal">Subtotal <span>{{ number_format($subtotal) }}VND</span></li>
                            <li class="discount">Discount
                                <span>
                                    @if($discount > 0)- {{ number_format($discount) }}VND
                                    @else
                                        0VND
                                    @endif
                                </span>
                            </li>
                            <li class="cart-total">Total <span>{{ number_format($totalAfterDiscount) }}VND</span></li>
                        </ul>
                            <a href="./checkout" class="proceed-btn">PROCEED TO CHECK OUT</a>
                        </div>
                    </div>
                </div>
            </div>
            @else
            <div class="col-lg-12">
                <h4>Your cart is empty!</h4>
            </div>
            @endif
        </div>
    </div>
</div>
{{--<script>--}}
    {{--$("#apply-discount").click(function(){--}}
    {{-- $.ajax({--}}
    {{--     url: '{{ route('cart.applyDiscount') }}',--}}
    {{--     type: 'post',--}}
    {{--     data: {code: $("#discount_code").val()} ,--}}
    {{--     success: function (response) {--}}
    {{--         if(response.status){--}}
    {{--             alert(response.message);--}}
    {{--             location.reload();--}}
    {{--         }else{--}}
    {{--             alert(response.message);--}}
    {{--         }--}}
    {{--     }--}}
    {{-- })--}}
    {{--})--}}
    {{--$(document).ready(function() {--}}
    {{--    $("#apply-discount").click(function() {--}}
    {{--        let discountCode = $("#discount_code").val();--}}
    {{--        $.ajax({--}}
    {{--            url: "{{ route('cart.applyDiscount') }}",--}}
    {{--            type: "POST",--}}
    {{--            data: {--}}
    {{--                _token: "{{ csrf_token() }}",--}}
    {{--                code: discountCode--}}
    {{--            },--}}
    {{--            success: function(response) {--}}
    {{--                if (response.status === "success") {--}}
    {{--                    $("#discount-message").text(response.message).css("color", "green");--}}
    {{--                    location.reload(); // Reload trang để cập nhật giỏ hàng--}}
    {{--                } else {--}}
    {{--                    $("#discount-message").text(response.message).css("color", "red");--}}
    {{--                }--}}
    {{--            },--}}
    {{--            error: function() {--}}
    {{--                $("#discount-message").text("Error applying discount.").css("color", "red");--}}
    {{--            }--}}
    {{--        });--}}
    {{--    });--}}
    {{--});--}}
{{--</script>--}}
<!-- Shopping Cart section end -->
@endsection
