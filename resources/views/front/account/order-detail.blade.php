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
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
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
                                        <div class="col-6 col-lg-2">
                                            <!-- Heading -->
                                            <h6 class="heading-xxxs text-muted">Order No:</h6>
                                            <!-- Text -->
                                            <p class="mb-lg-0 fs-sm fw-bold">
                                                {{$order->id}}
                                            </p>
                                        </div>
                                        <div class="col-6 col-lg-2">
                                            <!-- Heading -->
                                            <h6 class="heading-xxxs text-muted">Shipped date:</h6>
                                            <!-- Text -->
                                            <p class="mb-lg-0 fs-sm fw-bold">
                                                <time datetime="2019-10-01">
                                                    {{\Carbon\Carbon::parse($order->shipped_date)->format('d M, Y')}}                                                </time>
                                            </p>
                                        </div>
                                        <div class="col-6 col-lg-2">
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
                                            <h6 class="heading-xxxs text-muted">Payment Status:</h6>
                                            <!-- Text -->
                                            <p class="mb-0 fs-sm fw-bold">
                                                @if($order->payment_status == 'pending')
                                                    <span class="badge bg-warning">Pending</span>
                                                @elseif($order->payment_status == 'paid')
                                                    <span class="badge bg-success">Paid</span>
                                                @elseif($order->payment_status == 'cod')
                                                    <span class="badge bg-info">COD</span>
                                                @elseif($order->payment_status == 'unpaid')
                                                    <span class="badge bg-danger">Unpaid</span>
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
                                            <img class="img-fluid" src="{{ asset('uploads/products/small/' . $productImage->image) }}" alt="Product Image">
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
                                @php
                                    $remainingSeconds = \Carbon\Carbon::now()->diffInSeconds(
                                        \Carbon\Carbon::parse($order->created_at)->addHours(24),
                                        false // ✅ Cho phép trả về số âm nếu đã hết hạn
                                    );
                                @endphp
                            @if(in_array($order->status, ['pending', 'processing']))
                                    <div class="d-flex justify-content-end gap-2 mt-3 flex-wrap">
                                        <style>
                                            .order-timer-btn {
                                                min-width: 220px;
                                                height: 44px;
                                                display: inline-flex;
                                                align-items: center;
                                                justify-content: center;
                                                white-space: nowrap;
                                                padding: 0 16px;
                                                font-weight: 600;
                                                font-size: 16px;
                                            }

                                            .order-timer-countdown {
                                                display: inline-block;
                                                width: 80px;
                                                text-align: center;
                                                font-variant-numeric: tabular-nums;
                                            }
                                        </style>
                                    @if($order->payment_status === 'pending' &&
                                            $order->status === 'pending' &&
                                            $remainingSeconds > 0 &&
                                            $order->payment_method_id === 2)
                                            <a href="{{ route('vnpay.checkout', $order->id) }}" class="btn btn-warning text-white  w-auto order-timer-btn" id="payment-button">
                                                Pay Now (<span class="order-timer-countdown">--:--:--</span>)
                                            </a>
                                        @endif
                                        <!-- Button mở modal huỷ đơn -->
                                        <button type="button" class="btn btn-danger bg-danger w-auto text-white" data-bs-toggle="modal" data-bs-target="#cancelOrderModal">
                                            Cancel Order
                                        </button>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="cancelOrderModal" tabindex="-1" aria-labelledby="cancelOrderModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="cancelOrderModalLabel">Confirm Cancellation</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to cancel this order?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary w-auto text-white px-4" data-bs-dismiss="modal">No</button>

                        <!-- Cancel form -->
                        <form action="{{ route('orders.cancel', $order->id) }}" method="POST" class="d-inline-block">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-danger bg-danger text-white">Yes, Cancel Order</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
        <script>
            document.addEventListener("DOMContentLoaded", function () {
            let remainingTime = {{ $remainingSeconds }};
            const countdownEl = document.querySelector('.order-timer-countdown');
            const payNowBtn = document.getElementById('payment-button');

            function updateCountdown() {
            if (!countdownEl || !payNowBtn) return;

            if (remainingTime <= 0) {
            countdownEl.textContent = 'Expired';
            payNowBtn.classList.add('disabled');
            payNowBtn.href = 'javascript:void(0)';
            return;
        }

            const hours = Math.floor(remainingTime / 3600);
            const minutes = Math.floor((remainingTime % 3600) / 60);
            const seconds = remainingTime % 60;

            countdownEl.textContent =
            `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;

            remainingTime--;
        }
            updateCountdown();
            setInterval(updateCountdown, 1000);
        });
    </script>
@endsection
