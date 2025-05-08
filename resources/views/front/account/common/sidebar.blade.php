<ul id="account-panel" class="nav nav-pills flex-column">
    <li class="nav-item">
        <a href="{{route('account.profile')}}" class="nav-link font-weight-bold">
            <i class="fas fa-user-alt me-2"></i> My Profile
        </a>
    </li>
    <li class="nav-item">
        <a href="{{route('account.orders')}}" class="nav-link font-weight-bold">
            <i class="fas fa-shopping-bag me-2"></i> My Orders
        </a>
    </li>
    <li class="nav-item">
        <a href="{{route('account.wishlist')}}" class="nav-link font-weight-bold">
            <i class="fas fa-heart me-2"></i> Wishlist
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('account.blogs') }}" class="nav-link font-weight-bold">
            <i class="fas fa-blog me-2"></i> My Blogs
        </a>
    </li>
    <li class="nav-item">
        <a href="{{route('password.form')}}" class="nav-link font-weight-bold">
            <i class="fas fa-lock me-2"></i> Change Password
        </a>
    </li>
    <li class="nav-item">
        <form id="logout-form" action="{{ route('account.logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
        <a href="#" class="nav-link font-weight-bold" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="fas fa-sign-out-alt me-2"></i> Logout
        </a>
    </li>


@if(Auth::check())
        <form action="{{ route('account.logout') }}" method="POST" style="display:inline;">
            @csrf
            <i class="fas fa-sign-out-alt me-2"></i> Logout
        </form>
    @endif
</ul>

<style>
    /* Đảm bảo toàn bộ sidebar có nền vàng */
    #account-panel {
        background-color:  #252525; /* Đảm bảo ghi đè mọi thiết lập trước đó */
        padding: 15px;
        border-radius: 10px;
        width: 100%;
    }

    /* Đảm bảo các mục menu có nền vàng */
    #account-panel .nav-item {
        margin-bottom: 10px;
        background-color: #252525; /* Nền vàng */

    }

    #account-panel .nav-link {
        background-color:  #252525; /* Nền vàng */
        color: white !important; /* Chữ trắng */
        padding: 12px;
        border-radius: 5px;
        display: flex;
        align-items: center;
        transition: background 0.3s ease-in-out;
    }

    /* Đảm bảo icon cũng theo màu */
    #account-panel .nav-link i {
        margin-right: 8px;
        color: white !important;
    }

    /* Khi hover, nền chuyển sang đen */
    #account-panel .nav-item .nav-link:hover {
        background-color: #e7ab3c;
        color: white !important;
    }

</style>
