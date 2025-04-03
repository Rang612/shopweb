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
                            <h2 class="h5 mb-0 pt-2 pb-2">My Orders</h2>
                        </div>
                        <div class="card-body p-4">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>Orders #</th>
                                        <th>Date Purchased</th>
                                        <th>Status</th>
                                        <th>Total</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if($orders->isNotEmpty())
                                        @foreach($orders as $order)
                                            <tr>
                                                <td>
                                                    <a href="{{route('account.orderDetail',$order->id)}}">{{$order->id}}</a>
                                                </td>
                                                <td>{{\Carbon\Carbon::parse($order->created_at)->format('d M, Y')}}</td>
                                                <td>
                                                    @if($order->status == 'pending')
                                                        <span class="badge badge-warning">Pending</span>
                                                    @elseif($order->status == 'processing')
                                                        <span class="badge badge-info">Processing</span>
                                                    @elseif($order->status == 'completed')
                                                        <span class="badge badge-success">Completed</span>
                                                    @elseif($order->status == 'decline')
                                                        <span class="badge badge-danger">Declined</span>
                                                    @endif
                                                </td>
                                                <td>{{ number_format($order->grand_total, 0, ',', '.') }}VND</td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="3" class="text-center">No Orders Found</td>
                                        </tr>
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
