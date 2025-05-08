@extends('front.layout.master')
@section('title', 'Contact')
@section('body')
    <style>
        .btn-send-custom {
            width: auto !important;
            display: inline-block !important;
            padding: 8px 24px;
        }
    </style>
<!--Breadcrumb section begin(giup dinh vi vi tri ban dang o dau trong web)-->
<div class="breacrumb-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb-text">
                    <a href="{{route('front.home')}}"><i class="fa fa-home"></i>Home</a>
                    <span>Contact Us</span>
                </div>
            </div>
        </div>
    </div>
</div>
<!--Breadcrumb section end-->
{{-- <!--Map Section Begin-->--}}
{{-- <div class="map spad">--}}
{{--    <div class="container">--}}
{{--        <div class="map-inner">--}}
{{--            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d953720.9552066658!2d104.99313826721318!3d20.973689371376185!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3135008e13800a29%3A0x2987e416210b90d!2zSMOgIE7hu5lpLCBWaeG7h3QgTmFt!5e0!3m2!1svi!2s!4v1688876468831!5m2!1svi!2s"--}}
{{--                 height="610" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">--}}
{{--            </iframe>--}}
{{--            <div class="icon">--}}
{{--                <i class="fa fa-map-marker"></i>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</div>--}}
{{--<!--Map Section End-->--}}


<!--Contact Section Begin-->
{{--<section class="contact-section spad">--}}
{{--    <div class="container">--}}
{{--        <div class="row">--}}
{{--            <div class="col-lg-5">--}}
{{--                <div class="contact-title">--}}
{{--                    <h4 Contact Us></h4>--}}
{{--                    <p> Hello</p>--}}
{{--                </div>--}}
{{--                <div class="contact-widget">--}}
{{--                    <!--Address-->--}}
{{--                    <div class="cw-item">--}}
{{--                        <div class="ci-icon">--}}
{{--                            <i class="ti-location-pin"></i>--}}
{{--                        </div>--}}
{{--                        <div class="ci-text">--}}
{{--                            <span>Address:</span>--}}
{{--                            <p>1 Dai Co Viet, Hai Ba Trung, Ha Noi</p>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <!--Phone-->--}}
{{--                    <div class="cw-item">--}}
{{--                        <div class="ci-icon">--}}
{{--                            <i class="ti-mobile"></i>--}}
{{--                        </div>--}}
{{--                        <div class="ci-text">--}}
{{--                            <span>Phone:</span>--}}
{{--                            <p>+84 98.51.88.688</p>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <!--Email-->--}}
{{--                    <div class="cw-item">--}}
{{--                        <div class="ci-icon">--}}
{{--                            <i class="ti-email"></i>--}}
{{--                        </div>--}}
{{--                        <div class="ci-text">--}}
{{--                            <span>Email:</span>--}}
{{--                            <p>hkt123@gmail.com</p>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</section>--}}
<!--Contact Section End-->

{{--<section class=" section-10">--}}
{{--    <div class="container">--}}
{{--        <div class="section-title mt-5 ">--}}
{{--            <h2>Love to Hear From You</h2>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</section>--}}
<section class="contact-form-area py-5">
    <div class="container">
        <div class="row align-items-start">
            <!-- Left: Contact Info -->
            <div class="col-md-6 pe-lg-5 mb-4">
                <h4 class="mb-3 fw-bold">LOVE TO HEAR FROM YOU</h4>
                <p class="mb-4 text-muted" style="line-height: 1.7;">
                    It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.
                    The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using Content.
                </p>
                <address class="text-muted">
                    <strong>Cecilia Chapman</strong><br>
                    711-2880 Nulla St.<br>
                    Mankato Mississippi 96522<br>
                    <a href="tel:+xxxxxxxx">(XXX) 555-2368</a><br>
                    <a href="mailto:jim@rock.com">jim@rock.com</a>
                </address>
            </div>
            <!-- Right: Contact Form -->
            <div class="col-md-6">
                <form method="POST" action="#" id="contactForm">
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
@endsection
