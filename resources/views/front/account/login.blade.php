@extends('front.layout.master')

@section('title','Register')
@section('body')
    <div class="breacrumb-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb-text">
                        <a href="{{route('front.home')}}"><i class="fa fa-home"></i>Home</a>
                        <span>Login</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <section class=" section-10">
        <div class="login-container">
            @if(Session::has('success'))
                    {{Session::get('success')}}
            @endif
            <h2>Login to Your Account</h2>
                <form action="{{ route('account.authenticate') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required class="form-control @error('email') is-invalid @enderror">
                        @error('email')
                        <p class="invalid-feedback text-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" required class="form-control @error('password') is-invalid @enderror">
                        @error('password')
                        <p class="invalid-feedback text-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group small">
                        <a href="#" class="forgot-link">Forgot Password?</a>
                    </div>

                    <button type="submit" class="btn">Login</button>
                </form>
                <p class="small-text">Don't have an account? <a href="{{route('account.register')}}">Sign up</a></p>
        </div>
    </section>

@endsection
