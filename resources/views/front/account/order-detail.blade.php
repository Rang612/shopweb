@extends('front.layout.master')

@section('title','Register')
@section('body')
    <div class="breacrumb-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb-text">
                        <a href="#"><i class="fa fa-user"></i>My Account</a>
                        <span>My Orders</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <section class=" section-11 ">
        <div class="container  mt-5">
            <div class="row">
                <div class="col-md-3">
                        @include('front.account.common.sidebar')
                </div>
                <div class="col-md-9">
                    <div class="card">
                        <div class="card-header">
                            <h2 class="h5 mb-0 pt-2 pb-2">Order: {{$order->id}}</h2>
                        </div>

                        <div class="card-body pb-0">
                            <!-- Info -->
                            <div class="card card-sm">
                                <div class="card-body bg-light mb-3">
                                    <div class="row">
                                        <div class="col-6 col-lg-3">
                                            <!-- Heading -->
                                            <h6 class="heading-xxxs text-muted">Order No:</h6>
                                            <!-- Text -->
                                            <p class="mb-lg-0 fs-sm fw-bold">
                                                {{$order->id}}
                                            </p>
                                        </div>
                                        <div class="col-6 col-lg-3">
                                            <!-- Heading -->
                                            <h6 class="heading-xxxs text-muted">Shipped date:</h6>
                                            <!-- Text -->
                                            <p class="mb-lg-0 fs-sm fw-bold">
                                                <time datetime="2019-10-01">
                                                    {{\Carbon\Carbon::parse($order->shipped_date)->format('d M, Y')}}                                                </time>
                                            </p>
                                        </div>
                                        <div class="col-6 col-lg-3">
                                            <!-- Heading -->
                                            <h6 class="heading-xxxs text-muted">Status:</h6>
                                            <!-- Text -->
                                            <p class="mb-0 fs-sm fw-bold">
                                                @if($order->status == 'pending')
                                                    <span class="badge badge-warning">Pending</span>
                                                @elseif($order->status == 'processing')
                                                    <span class="badge badge-info">Processing</span>
                                                @elseif($order->status == 'completed')
                                                    <span class="badge badge-success">Completed</span>
                                                @elseif($order->status == 'decline')
                                                    <span class="badge badge-danger">Declined</span>
                                                @endif
                                            </p>
                                        </div>
                                        <div class="col-6 col-lg-3">
                                            <!-- Heading -->
                                            <h6 class="heading-xxxs text-muted">Order Amount:</h6>
                                            <!-- Text -->
                                            <p class="mb-0 fs-sm fw-bold">
                                                {{ number_format($order->grand_total, 0, ',', '.') }}VND
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer p-3">

                            <!-- Heading -->
                            <h6 class="mb-7 h5 mt-4">Order Items</h6>

                            <!-- Divider -->
                            <hr class="my-3">

                            <!-- List group -->
                            <ul>
                                @foreach($orderItems as $item)
                                <li class="list-group-item">
                                    <div class="row align-items-center">
                                        <div class="col-4 col-md-3 col-xl-2">
                                            <!-- Image -->
                                        @php
                                          $productImage =  getProductImage($item->product_id);
                                        @endphp
                                            <img class="img-fluid" src="{{ asset('storage/products/' . $productImage->image) }}" alt="Product Image">
                                        </div>
                                        <div class="col">
                                            <!-- Title -->
                                            <p class="mb-4 fs-sm fw-bold">
                                                <a class="text-body" href="product.html">{{$item-> name}} ({{$item->color}} , {{$item->size}} ) x {{$item ->qty}}</a> <br>
                                                <span class="text-muted">{{ number_format($item->total, 0, ',', '.') }}VND</span>
                                            </p>
                                        </div>
                                    </div>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                    <div class="card card-lg mb-5 mt-3">
                        <div class="card-body">
                            <!-- Heading -->
                            <h6 class="mt-0 mb-3 h5">Order Total</h6>
                            <!-- List group -->
{{--                            <ul>--}}
{{--                                <li class="list-group-item d-flex">--}}
{{--                                    <span>Subtotal</span>--}}
{{--                                    <span class="ms-auto">{{ number_format($order->subtotal, 0, ',', '.') }}VND</span>--}}
{{--                                </li>--}}
{{--                                <li class="list-group-item d-flex">--}}
{{--                                    <span>Discount</span>--}}
{{--                                    <span class="ms-auto">{{ number_format($order->discount, 0, ',', '.') }}VND</span>--}}
{{--                                </li>--}}
{{--                                <li class="list-group-item d-flex">--}}
{{--                                    <span>Shipping</span>--}}
{{--                                    <span class="ms-auto">{{ number_format($order->shipping, 0, ',', '.') }}VND</span>--}}
{{--                                </li>--}}
{{--                                <li class="list-group-item d-flex fs-lg fw-bold">--}}
{{--                                    <span>Total</span>--}}
{{--                                    <span class="ms-auto">{{ number_format($order->grand_total, 0, ',', '.') }}VND</span>--}}
{{--                                </li>--}}
{{--                            </ul>--}}
                            <div class="order-total">
                                <h5 class="order-total-title">ORDER TOTAL</h5>
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item d-flex justify-content-between">
                                        <span class="text-capitalize">Subtotal</span>
                                        <span>{{ number_format($order->subtotal, 0, ',', '.') }} VND</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between">
                                        <span class="text-capitalize">Discount {{(!empty($order->coupon_code)) ? '('.$order->coupon_code.')' : ''}}</span>
                                        <span>-{{ number_format($order->discount, 0, ',', '.') }} VND</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between">
                                        <span class="text-capitalize">Shipping</span>
                                        <span>{{ number_format($order->shipping, 0, ',', '.') }} VND</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between fw-bold">
                                        <span class="text-uppercase">Total</span>
                                        <span>{{ number_format($order->grand_total, 0, ',', '.') }} VND</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
