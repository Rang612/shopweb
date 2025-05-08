@extends('front.layout.master')

@section('title', 'Reset Password')

@section('body')
    <div class="breacrumb-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb-text">
                        <a href="{{ route('front.home') }}"><i class="fa fa-home"></i> Home</a>
                        <span>Reset Password</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="section-10">
        <div class="login-container">
            @if (session('status'))
                <div class="alert alert-success">{{ session('status') }}</div>
            @endif
            <h2>Reset Password</h2>
            <form action="{{ route('password.update') }}" method="POST">
                @csrf

                <input type="hidden" name="token" value="{{ $token }}">

                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" name="email" value="{{ old('email', request()->email) }}" required class="form-control @error('email') is-invalid @enderror">
                    @error('email')
                    <p class="invalid-feedback text-danger">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label>New Password</label>
                    <input type="password" name="password" required class="form-control @error('password') is-invalid @enderror">
                    @error('password')
                    <p class="invalid-feedback text-danger">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Confirm New Password</label>
                    <input type="password" name="password_confirmation" required class="form-control">
                </div>

                <button type="submit" class="btn">Reset Password</button>
            </form>
        </div>
    </section>
@endsection
