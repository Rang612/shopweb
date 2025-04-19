@extends('front.layout.master')

@section('title','Register')
@section('body')
    <div class="breacrumb-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb-text">
                        <a href="{{route('front.home')}}"><i class="fa fa-user"></i>My Account</a>
                        <span>Settings</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <section class="section-11">
        <div class="container mt-5">
            <div class="row">
                <div class="col-md-3">
                    @include('front.account.common.sidebar')
                </div>
                <div class="col-md-9">
                    <div class="card">
                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif
                        <div class="card-header">
                            <h2 class="h5 mb-0 pt-2 pb-2">Personal Information</h2>
                        </div>
                        <form method="POST" action="{{ route('account.updateProfile') }}">
                            @csrf
                            <div class="card-body p-4">
                                <div class="row g-3">
                                    <div class="col-md-12">
                                        <label for="name" class="form-label">Name</label>
                                        <input type="text" name="name" id="name" placeholder="Enter Your Name" class="form-control" value="{{ Auth::user()->name }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="text" name="email" id="email" placeholder="Enter Your Email" class="form-control" value="{{ Auth::user()->email }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="phone" class="form-label">Phone</label>
                                        <input type="text" name="phone" id="phone" placeholder="Enter Your Phone" class="form-control" value="{{ Auth::user()->phone }}">
                                    </div>
                                    <div class="col-2 text-end mt-5">
                                        <button class="btn btn-dark px-4">Update</button>
                                    </div>
                                </div>
                            </div>
                        </form>

                            <div class="card mt-4">
                                <div class="card-header">
                                <h2 class="h5 mb-0 pt-2 pb-2">Address</h2>
                                </div>
                            </div>
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form method="POST" action="{{ route('account.updateAddress') }}">
                                @csrf
                                <div class="card-body p-4">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label for="first-name" class="form-label">First Name</label>
                                            <input type="text" name="first_name" placeholder="Enter Your First Name" class="form-control" value="{{ Auth::user()->address->first_name ?? '' }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="last-name" class="form-label">Last Name</label>
                                            <input type="text" name="last_name" placeholder="Enter Your Last Name" class="form-control" value="{{ Auth::user()->address->last_name ?? '' }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="email" class="form-label">Email</label>
                                            <input type="text" name="email" id="email" placeholder="Enter Your Email" class="form-control" readonly value="{{ Auth::user()->email }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="phone" class="form-label">Phone</label>
                                            <input type="text" name="mobile" class="form-control" placeholder="Enter Your Phone Number" readonly value="{{ Auth::user()->phone}}">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="country_name" class="form-label">City</label>
                                            <input type="text" name="country_name" class="form-control" placeholder="Enter Your City" value="{{ Auth::user()->address->country->name ?? '' }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="district" class="form-label">District</label>
                                            <input type="text" name="district" placeholder="Enter Your District" class="form-control" value="{{ Auth::user()->address->district ?? '' }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="ward" class="form-label">Ward</label>
                                            <input type="text" name="ward" placeholder="Enter Your Ward" class="form-control" value="{{ Auth::user()->address->ward ?? '' }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="street" class="form-label">Street</label>
                                            <input type="text" name="street" placeholder="Enter Your Street" class="form-control" value="{{ Auth::user()->address->street ?? '' }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="house-number" class="form-label">House Number</label>
                                            <input type="text" name="house_number" placeholder="Enter Your House Number" class="form-control" value="{{ Auth::user()->address->house_number ?? '' }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="zip" class="form-label">Zip Code</label>
                                            <input type="text" name="zip" placeholder="Enter Your Zip Code" class="form-control" value="{{ Auth::user()->address->zip ?? '' }}">
                                        </div>
                                        <div class="col-2 text-end mt-5">
                                            <button class="btn btn-dark px-4">Update</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
