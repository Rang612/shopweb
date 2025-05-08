@extends('front.layout.master')

@section('title', 'Forgot Password')

@section('body')
    <div class="breacrumb-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb-text">
                        <a href="{{ route('front.home') }}"><i class="fa fa-home"></i> Home</a>
                        <span>Forgot Password</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="section-10">
        <div class="login-container">
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif
            <h2>Forgot Your Password?</h2>
            <p class="small-text">Enter your email address and we'll send you a link to reset your password.</p>
            <form action="{{route('account.processForgotPassword')}}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required class="form-control @error('email') is-invalid @enderror">
                    @error('email')
                    <p class="invalid-feedback text-danger">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="btn">Send Password Reset Link</button>
            </form>
            <p class="small-text"><a href="{{ route('account.login') }}">Back to Login</a></p>
        </div>
    </section>
@endsection
