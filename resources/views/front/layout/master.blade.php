<!DOCTYPE html>
<html lang="zxx">

<head>
    <base href="{{asset('/')}}">

    <meta charset="UTF-8">
    <meta name="description" content="codelean Template">
    <meta name="keywords" content="codelean, unica, creative, html">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title') | iDouGh Shop</title>

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css?family=Muli:300,400,500,600,700,800,900&display=swap" rel="stylesheet">

    <!-- Css Styles -->
    <link href="https://unpkg.com/tailwindcss@^1.0/dist/tailwind.min.css"  rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="front/css/bootstrap.min.css" type="text/css">
    <link rel="stylesheet" href="front/css/font-awesome.min.css" type="text/css">
    <link rel="stylesheet" href="front/css/themify-icons.css" type="text/css">
    <link rel="stylesheet" href="front/css/elegant-icons.css" type="text/css">
    <link rel="stylesheet" href="front/css/owl.carousel.min.css" type="text/css">
    <link rel="stylesheet" href="front/css/nice-select.css" type="text/css">
    <link rel="stylesheet" href="front/css/jquery-ui.min.css" type="text/css">
    <link rel="stylesheet" href="front/css/slicknav.min.css" type="text/css">
    <link rel="stylesheet" href="front/css/style.css" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>

<body>
<!-- Start coding here -->
<!--Page Preloder-->
<div id="preloder">
    <div class="loader">

    </div>
</div>
<!-- Header sectin begin-->
<header class="header-section fixed-top">
    <div class="header-top ">
        <div class="container">
            <div class="ht-left">
                <div class="mail-service">
                    <i class="fa fa-envelope"></i>
                    tranhuonggiang6122003@gmail.com
                </div>
                <div class="phone-service">
                    <i class="fa fa-phone"></i>
                    +84 976122003
                </div>
            </div>
            <div class="ht-right">
                <a href="login.html" class="login-panel"> <i class="fa fa-user"></i>Đăng nhập</a>
                <div class="lan-selector" style="display: flex; align-items: center; gap: 8px;">
                    <img src="front/img/flag-1.png" alt="Vietnam Flag" style="width: 32px; height: 24px;">
                    <span>Tiếng Việt</span>
                </div>
                <div class="top-social">
                    <a href="https://www.facebook.com/share/161FYBzort/" target="_blank"><i class="ti-facebook"></i></a>
                    <a href="http://www.instagram.com/rang_rang0612" target="_blank"><i class="ti-instagram"></i></a>
                    <a href="https://www.tiktok.com/@rangrang0612?_t=ZS-8ty2P5gjrM8&_r=1" target="_blank"><i class="fa-brands fa-tiktok"></i></a>
                    <a href="https://www.youtube.com" target="_blank"><i class="fa-brands fa-youtube"></i></a>
                </div>

            </div>
        </div>
    </div>
    <div class="container">
        <div class="inner-header">
            <div class="row">
                <div class="col-lg-2 col-md-2">
                    <div class="logo">
{{--                        <img src="front/img/logo.png" height="20" alt="">--}}
                    </div>
                </div>
                <div class="col-lg-6 col-md-6">
                    <form action="shop">
                        <div class="advanced-search">
                            <button type="button" class="category-btn">Sản phẩm</button>
                            <div class="input-group">
                                <input name="search" type="text" value="{{ request('search') }}" placeholder="Tìm kiếm">
                                <button type="submit"><i class="ti-search"></i></button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-lg-3 col-md-3 text-right">
                    <ul class="nav-right">
                        <li class="store-icon">
                            <a href="#">
                                <i class="fas fa-store" style="color: rgba(51,49,49,0.96);"></i> <!-- Icon cửa hàng -->
                            </a>
                        </li>
                        <li class="heart-icon">
                            <a href="#">
                                <i class="icon_heart_alt"></i>
                                <span>1</span>
                            </a>
                        </li>
                        <li class="cart-icon">
                            <a href="./cart">
                                <i class="icon_bag_alt"></i>
                                <span>{{Cart::count()}}</span>
                            </a>
                            <div class="cart-hover">
                                <div class="select-items">
                                    <table>
                                        <tbody>
                                        @foreach(Cart::content() as $cart)
                                        <tr>
                                            <td class="si-pic"><img style="height: 70px" src="front/img/products/{{$cart->options->images[0]->path}}"></td>
                                            <td class="si-text">
                                                <div class="product-selected">
                                                    <p>
                                                        ${{number_format($cart->price, 2)}} x {{$cart->qty}}
                                                    </p>
                                                    <h6>{{$cart->name}}</h6>
                                                </div>
                                            </td>
                                            <td class="si-close">
                                            <td class="close-td first-row"><i onclick="window.location='./cart/delete/{{$cart->rowId}}'" class="ti-close"></i></td>
                                            </td>
                                        </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="select-total">
                                    <span>Tổng tiền:</span>
                                    <h5>{{Cart::total()}}đ</h5>

                                </div>
                                <div class="select-button">
                                    <a href="./cart" class="primary-btn view-card">XEM GIỎ HÀNG</a>
                                    <a href="./checkout" class="primary-btn checkout-bin">CHECK OUT</a>

                                </div>
                            </div>
                        </li>
                        <li class="cart-pirce">{{Cart::total()}}đ</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
{{--    <div class="nav-item">--}}
{{--        <div class="container">--}}
{{--            @if(getCategories()->isNotEmpty())--}}
{{--                @foreach(getCategories() as $category)--}}
{{--            <div class="nav-depart">--}}
{{--                <div class="depart-btn">--}}
{{--                    <i class="ti-menu"></i>--}}
{{--                    <span>Category</span>--}}
{{--                    <ul class="depart-hover">--}}
{{--                        <li class="active"><a href="#">Nữ</a></li>--}}
{{--                    </ul>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--                @endforeach--}}
{{--            @endif--}}
{{--            <nav class="nav-menu mobile-menu">--}}
{{--                <ul>--}}
{{--                    <li class="{{(request()->segment(1) == '') ? 'active' : ''}}"><a href="./">Trang chủ</a></li>--}}
{{--                    <li class="{{(request()->segment(1) == 'shop') ? 'active' : ''}}"><a href="./shop">Sản phẩm</a></li>--}}
{{--                    <li class="{{(request()->segment(1) == 'blog') ? 'active' : ''}}"><a href=./">Bài viết</a></li>--}}
{{--                    <li class="{{(request()->segment(1) == 'contact') ? 'active' : ''}}"><a href="./contact">Liên hệ</a></li>--}}

{{--                </ul>--}}
{{--            </nav>--}}

{{--            <div id="mobile-menu-wrap"></div>--}}

{{--        </div>--}}
{{--    </div>--}}
    <div class="nav-item">
        <div class="container">
            <!-- Trang chủ luôn ở đầu -->
{{--            <nav class="nav-menu mobile-menu">--}}
{{--                <ul>--}}
{{--                    <li class="{{ (request()->segment(1) == '') ? 'active' : '' }}">--}}
{{--                        <a href="./">Trang chủ</a>--}}
{{--                    </li>--}}
{{--                </ul>--}}
{{--            </nav>--}}

            <div class="nav-depart">
                <div class="nav-menu mobile-menu">
                    <ul>
                        <li class="{{ (request()->segment(1) == '') ? 'active' : '' }}">
                            <a href="./">Trang chủ</a>
                        </li>
                    </ul>
                </div>
            </div>
            <!-- Hiển thị Category nếu có -->
            @if(getCategories()->isNotEmpty())
                @foreach(getCategories() as $category)
                <div class="nav-depart">
                    <div class="depart-btn">
                        <span>{{ $category->name }}</span>
                        @if($category->sub_category->isNotEmpty())
                            <ul class="depart-hover">
                            @foreach($category->sub_category as $subCategory)
                                    <li class="active"><a href="#">{{ $subCategory->name }}</a></li>
                            @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
                @endforeach
            @endif

            <!-- Các menu còn lại -->
            <nav class="nav-menu mobile-menu">
                <ul>
                    <li class="{{ (request()->segment(1) == 'shop') ? 'active' : '' }}">
                        <a href="./shop">Sản phẩm</a>
                    </li>
                    <li class="{{ (request()->segment(1) == 'blog') ? 'active' : '' }}">
                        <a href="./blog">Bài viết</a>
                    </li>
                    <li class="{{ (request()->segment(1) == 'contact') ? 'active' : '' }}">
                        <a href="./contact">Liên hệ</a>
                    </li>
                </ul>
            </nav>

            <div id="mobile-menu-wrap"></div>
        </div>
    </div>

</header>
<!--Header section end-->

{{--Body here--}}
@yield('body')

<!--Partner Logo section Begin-->
{{--<div class="partner-logo">--}}
{{--    <div class="container">--}}
{{--        <div class="logo-carousel owl-carousel">--}}
{{--            <div class="logo-item">--}}
{{--                <div class="tablecell-inner">--}}
{{--                    <img src="front/img/logo-carousel/logo-1.png">--}}

{{--                </div>--}}
{{--            </div>--}}

{{--            <div class="logo-item">--}}
{{--                <div class="tablecell-inner">--}}
{{--                    <img src="front/img/logo-carousel/logo-2.png">--}}

{{--                </div>--}}
{{--            </div>--}}

{{--            <div class="logo-item">--}}
{{--                <div class="tablecell-inner">--}}
{{--                    <img src="front/img/logo-carousel/logo-3.png">--}}

{{--                </div>--}}
{{--            </div>--}}

{{--            <div class="logo-item">--}}
{{--                <div class="tablecell-inner">--}}
{{--                    <img src="front/img/logo-carousel/logo-4.png">--}}

{{--                </div>--}}
{{--            </div>--}}
{{--            <div class="logo-item">--}}
{{--                <div class="tablecell-inner">--}}
{{--                    <img src="front/img/logo-carousel/logo-5.png">--}}

{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</div>--}}

<!-- Partner Logo section end-->
<!--Footer Section begin-->
<footer class="footer-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-3">
                <div class="footer-left">
                    <div class="footet-logo">
                        <a href="index.html">
{{--                            <img src="front/img/footer-logo.png" height="25" alt="">--}}
                        </a>
                    </div>
                    <ul>
                        <li>So 1 Dai Co Viet</li>
                        <li>Phone:+84 976122003</li>
                        <li>Email:tranhuonggiang612203@gmail.com</li>
                    </ul>
                    <div class="footer-social">
                            <a href="https://www.facebook.com/share/161FYBzort/" target="_blank"><i class="ti-facebook"></i></a>
                            <a href="http://www.instagram.com/rang_rang0612" target="_blank"><i class="ti-instagram"></i></a>
                            <a href="https://www.tiktok.com/@rangrang0612?_t=ZS-8ty2P5gjrM8&_r=1" target="_blank"><i class="fa-brands fa-tiktok"></i></a>
                            <a href="https://www.youtube.com" target="_blank"><i class="fa-brands fa-youtube"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 offset-lg-1">
                <div class="footer-wiget">
                    <h5>Information</h5>
                    <ul>
                        <li><a href="">About us</a></li>
                        <li><a href="">Checkout</a></li>
                        <li><a href="">Contact</a></li>
                        <li><a href="">Serivius</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-2">
                <div class="footer-wiget">
                    <h5>My account</h5>
                    <ul>
                        <li><a href="">My account</a></li>
                        <li><a href="">Contact</a></li>
                        <li><a href="">Shopping cart</a></li>
                        <li><a href="">Shop</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-4"></div>
            <div class="newslatter-item">
                <h5>Join our newsletter now</h5>
                <p> get email updates about new products</p>
                <form action="#" class="subscribe-form">
                    <input type="text" placeholder="Enter your email">
                    <button type="button">Sign up</button>
                </form>
            </div>
        </div>
    </div>
</footer>
<!--Footer section end-->
<!-- Js Plugins -->
<script src="front/js/jquery-3.3.1.min.js"></script>
<script src="front/js/bootstrap.min.js"></script>
<script src="front/js/jquery-ui.min.js"></script>
<script src="front/js/jquery.countdown.min.js"></script>
<script src="front/js/jquery.nice-select.min.js"></script>
<script src="front/js/jquery.zoom.min.js"></script>
<script src="front/js/jquery.dd.min.js"></script>
<script src="front/js/jquery.slicknav.js"></script>
<script src="front/js/owl.carousel.min.js"></script>
<script src="front/js/owlcarousel2-filter.min.js"></script>
<script src="front/js/main.js"></script>
</body>

</html>
