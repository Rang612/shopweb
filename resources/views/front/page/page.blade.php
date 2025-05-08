@extends('front.layout.master')
@section('title', 'Contact')
@section('body')
    <!--Breadcrumb section begin(giup dinh vi vi tri ban dang o dau trong web)-->
    <div class="breacrumb-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb-text">
                        <a href="{{route('front.home')}}"><i class="fa fa-home"></i>Home</a>
                        <span>{{$page->name}}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if($page->slug=='contact-us')
        <style>
            .btn-send-custom {
                width: auto !important;
                display: inline-block !important;
                padding: 8px 24px;
            }
        </style>
        <section class="contact-form-area py-5">
            <div class="container">
                <div class="row align-items-start">
                    <!-- Left: Contact Info -->
                    <div class="col-md-6 pe-lg-5 mb-4">
                        @if(session('success'))
                            <div class="alert alert-success" style="background-color: #d4edda; color: #155724; padding: 10px 15px; border-radius: 5px; margin-bottom: 20px;">
                                {{ session('success') }}
                            </div>
                        @endif
                        <h4 class="mb-3 fw-bold">{{$page->name}}</h4>
                        {!! $page->content !!}
                    </div>
                    <!-- Right: Contact Form -->
                    <div class="col-md-6">
                        <form method="POST" action="{{ route('contact.send') }}" id="contactForm">
                            @csrf
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input class="form-control" id="name" type="text" name="name" required>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input class="form-control" id="email" type="email" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="subject" class="form-label">Subject</label>
                                <input class="form-control" id="subject" type="text" name="subject" required>
                            </div>

                            <div class="mb-3">
                                <label for="message" class="form-label">Message</label>
                                <textarea class="form-control" id="message" name="message" rows="4" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-warning text-white btn-send-custom">Send</button>         </form>
                    </div>
                </div>
            </div>
        </section>
        @else
    <section class=" section-10">
        <div class="container">
            <h1 class="my-3">{{$page->name}}</h1>
            {!! $page->content !!}
        </div>
    </section>
    @endif
@endsection
