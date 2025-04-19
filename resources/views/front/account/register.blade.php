@extends('front.layout.master')

@section('title','Register')
@section('body')
    <div class="breacrumb-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb-text">
                        <a href="{{route('front.home')}}"><i class="fa fa-home"></i>Home</a>
                        <span>Register</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <section class=" section-10">
        <div class="register-container">
            <h2>Register Now</h2>
            <form action="{{ route('account.processRegister') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required class="form-control @error('name') is-invalid @enderror">
                    @error('name')
                    <p class="invalid-feedback text-danger">{{ $message }}</p>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required class="form-control @error('email') is-invalid @enderror">
                    @error('email')
                    <p class="invalid-feedback text-danger">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="phone">Phone</label>
                    <input type="text" id="phone" name="phone" value="{{ old('phone') }}" required class="form-control @error('phone') is-invalid @enderror">
                    @error('phone')
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
                <div class="form-group">
                    <label for="password_confirmation">Confirm Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required class="form-control">
                </div>
                <button type="submit" class="btn btn-primary">Register</button>
            </form>
            <p class="small-text">Already have an account? <a href="{{route('account.login')}}">Login Now</a></p>
        </div>
    </section>
@endsection
