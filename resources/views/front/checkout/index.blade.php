@extends('front.layout.master')

@section('title','Check Out')
@section('body')
 <!--Breadcrumb section begin(giup dinh vi vi tri ban dang o dau trong web)-->
 <div class="breacrumb-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb-text">
                    <a href="{{route('front.home')}}"><i class="fa fa-home"></i>Home</a>
                    <a href="{{route('front.shop.index')}}">Shop</a>
                    <span>Check Out</span>
                </div>
            </div>
        </div>
    </div>
</div>
<!--Breadcrumb section end-->

<!-- Shopping Cart section begin -->
<section class="checkout-section spad">
    <div class="container">
        <form action="{{ route('add.order') }}" method="post" class="checkout-form">
            @csrf
            <input type="hidden" name="shipping_cost" id="shipping_cost" value="0">
            <div class="row">
                @if(Cart::count() > 0)
                <div class="col-lg-6">
{{--                    <div class="checkout-content">--}}
{{--                        <a href="login.html" class="content-btn">Click Here To Login</a>--}}
{{--                    </div>--}}
                    <h4>ADDRESS</h4>
                    <div class="row">
                        <div class="col-lg-6">
                            <label for="fir">First Name <span>*</span></label>
                            <input type="text" id="fir" name="first_name">
                        </div>
                        <div class="col-lg-6">
                            <label for="last">Last Name <span>*</span></label>
                            <input type="text" id="last" name="last_name">
                        </div>
                        <div class="col-lg-6">
                            <label for="phone-number">Phone Number <span>*</span></label>
                            <input type="text" id="phone-number" name="phone-number">
                        </div>
                        <div class="col-lg-6">
                            <label for="email">Email Address <span>*</span></label>
                            <input type="text" id="email" name="email">
                        </div>
                        <div class="form-group col-lg-12">
                            <label for="city">City/Province <span class="text-danger">*</span></label>
                            <select id="city" name="city" class="form-control">
                                <option value="">-- Select City/Province --</option>
                            </select>
                        </div>
                        <div class="form-group col-lg-12">
                            <label for="district">District <span class="text-danger">*</span></label>
                            <select id="district" name="district" class="form-control">
                                <option value="">-- Select District --</option>
                            </select>
                        </div>
                        <div class="form-group col-lg-12">
                            <label for="ward">Ward/Commune <span class="text-danger">*</span></label>
                            <select id="ward" name="ward" class="form-control">
                                <option value="">-- Select Ward/Commune --</option>
                            </select>
                        </div>
                        <div class="form-group col-lg-6">
                            <label for="street">Street <span class="text-danger">*</span></label>
                            <input type="text" id="street" name="street" class="form-control" placeholder="Enter your street name">
                        </div>

                        <div class="col-lg-6">
                            <label for="house">House number<span>*</span></label>
                            <input type="text" id="house" name="house">
                        </div>
                        <div class="col-lg-12">
                            <label for="zip">Postcode/ Zip (optional)</label>
                            <input type="text" id="zip" name="postcode_zip">
                        </div>
                        <div class="col-lg-12">
                            <label for="note">Note</label>
                            <input type="text" id="note" name="note">
                        </div>
                        <div class="col-lg-12">
                            <div class="create-item">
                                <label>
                                    Create an account?
                                    <input type="checkbox" id="acc-create">
                                    <span class="checkmark"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="place-order">
                        <h4>Your Order</h4>
                        <div class="order-total">
                            <ul class="order-table">
                                <li>Product <span>Total</span></li>
                                @foreach($carts as $cart)
                                <li class="fw-normal">{{$cart->name}} ({{$cart->options->color}} , {{$cart->options->size}} ) x {{$cart->qty}}
                                    <span>{{number_format($cart->price * $cart->qty)}}VND</span></li>
                                @endforeach

                                <li class="subtotal">Subtotal <span>{{ number_format($subtotal) }} VND</span></li>
                                @if($discount > 0)
                                    <li class="discount">Discount ({{ $discountData['code'] }}) <span>-{{ number_format($discount) }} VND</span></li>
                                @endif
                                <li class="shipping-cost">Shipping Cost <span>0 VND</span></li>
                                <li class="cart-total">Total <span>{{ number_format($totalAfterDiscount, 0, ',', '.') }} VND</span></li>    </ul>
                            <div class="payment-check">
                                <div class="pc-item">
                                    <label for="pc-check">
                                        Pay later
                                        <input type="radio" id="pc-check" name="payment_type" value="pay_later">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <div class="pc-item">
                                    <label class="pc-paypat">
                                        Pay now
                                        <input type="radio" id="pc-paypal" name="payment_type" value="online_payment">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="order-btn">
                                <button type="submit" class="site-btn place-btn">Place Order</button>
                            </div>

                        </div>
                    </div>
                </div>

                @else
                    <div class="col-lg-12">
                        <h4>Your cart is empty!</h4>
                    </div>
                @endif
            </div>
        </form>
    </div>
</section>
 <script>
     async function loadProvinces() {
         try {
             let response = await fetch("/checkout/proxy/provinces");
             if (!response.ok) throw new Error(`Không thể tải danh sách tỉnh/thành phố! Status: ${response.status}`);
             let data = await response.json();
             let citySelect = document.getElementById("city");
             citySelect.innerHTML = '<option value="">Chọn tỉnh/thành phố</option>';
             data.forEach(province => {
                 let cleanName = province.name.replace(/^(Tỉnh|Thành phố) /, '');
                 citySelect.innerHTML += `<option value="${cleanName}">${cleanName}</option>`;
             });
         } catch (error) {
             console.error("Lỗi khi tải danh sách tỉnh:", error);
         }
     }

     async function loadDistricts(cityName) {
         try {
             // Lấy mã code từ tên tỉnh/thành
             const provinceResponse = await fetch("/checkout/proxy/provinces");
             if (!provinceResponse.ok) throw new Error(`Không thể tải danh sách tỉnh/thành phố! Status: ${provinceResponse.status}`);
             const provinces = await provinceResponse.json();
             const province = provinces.find(p => p.name.replace(/^(Tỉnh|Thành phố) /, '') === cityName);
             if (!province) throw new Error("Không tìm thấy tỉnh/thành phố: " + cityName);

             const response = await fetch(`/checkout/proxy/provinces/${province.code}`);
             if (!response.ok) throw new Error(`Không thể tải danh sách quận/huyện! Status: ${response.status}`);
             const data = await response.json();
             if (!data.districts || !Array.isArray(data.districts)) throw new Error("Dữ liệu quận/huyện không hợp lệ!");

             let districtSelect = document.getElementById("district");
             districtSelect.innerHTML = '<option value="">Select District</option>';
             document.getElementById("ward").innerHTML = '<option value="">Select Ward</option>';

             data.districts.forEach(district => {
                 let option = new Option(district.name, district.name);
                 districtSelect.appendChild(option);
             });
         } catch (error) {
             console.error("Error loading districts:", error);
             let districtSelect = document.getElementById("district");
             districtSelect.innerHTML = '<option value="">Error loading districts</option>';
         }
     }

     async function loadWards(districtName) {
         try {
             // Lấy mã code từ tên quận/huyện
             const cityName = document.getElementById('city').value;
             const provinceResponse = await fetch("/checkout/proxy/provinces");
             if (!provinceResponse.ok) throw new Error(`Không thể tải danh sách tỉnh/thành phố! Status: ${provinceResponse.status}`);
             const provinces = await provinceResponse.json();
             const province = provinces.find(p => p.name.replace(/^(Tỉnh|Thành phố) /, '') === cityName);
             if (!province) throw new Error("Không tìm thấy tỉnh/thành phố: " + cityName);

             const districtResponse = await fetch(`/checkout/proxy/provinces/${province.code}`);
             if (!districtResponse.ok) throw new Error(`Không thể tải danh sách quận/huyện! Status: ${districtResponse.status}`);
             const districtData = await districtResponse.json();
             if (!districtData.districts || !Array.isArray(districtData.districts)) throw new Error("Dữ liệu quận/huyện không hợp lệ!");

             const districts = districtData.districts;
             const district = districts.find(d => d.name === districtName);
             if (!district) throw new Error("Không tìm thấy quận/huyện: " + districtName);

             const response = await fetch(`/checkout/proxy/districts/${district.code}`);
             if (!response.ok) throw new Error(`Không thể tải danh sách phường/xã! Status: ${response.status}`);
             const data = await response.json();
             if (!data.wards || !Array.isArray(data.wards)) throw new Error("Dữ liệu phường/xã không hợp lệ!");

             let wardSelect = document.getElementById("ward");
             wardSelect.innerHTML = '<option value="">Select Ward</option>';

             data.wards.forEach(ward => {
                 let option = new Option(ward.name, ward.name);
                 wardSelect.appendChild(option);
             });
         } catch (error) {
             console.error("Error loading wards:", error);
             let wardSelect = document.getElementById("ward");
             wardSelect.innerHTML = '<option value="">Error: Unable to load wards</option>';
         }
     }

     // Gắn sự kiện khi DOM loaded
     document.addEventListener("DOMContentLoaded", function () {
         loadProvinces();

         // Sự kiện change cho #city
         document.getElementById('city').addEventListener('change', function () {
             let cityName = this.value;
             let token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

             if (cityName) {
                 loadDistricts(cityName);
             }

             if (cityName && token) {
                 fetch('/checkout/checkout/shipping-cost', {
                     method: 'POST',
                     headers: {
                         'Content-Type': 'application/json',
                         'X-CSRF-TOKEN': token
                     },
                     body: JSON.stringify({ city: cityName })
                 })
                     .then(response => response.json())
                     .then(data => {
                         let shippingCost = parseFloat(data.shipping_cost);
                         document.getElementById('shipping_cost').value = shippingCost;
                         let shippingCostElement = document.querySelector('.shipping-cost span');
                         let previousShippingCost = parseFloat(shippingCostElement.textContent.replace(/[^\d]/g, '')) || 0;
                         shippingCostElement.textContent = shippingCost.toLocaleString('vi-VN') + " VND";

                         let totalAfterDiscountElement = document.querySelector('.cart-total span');
                         let totalAfterDiscount = parseFloat(totalAfterDiscountElement.textContent.replace(/[^\d]/g, ''));
                         totalAfterDiscount = totalAfterDiscount - previousShippingCost + shippingCost;
                         totalAfterDiscountElement.textContent = totalAfterDiscount.toLocaleString('vi-VN') + " VND";
                     })
                     .catch(error => console.error("Lỗi khi lấy phí vận chuyển:", error));
             }
         });

         // Sự kiện change cho #district
         document.getElementById('district').addEventListener('change', function () {
             let districtName = this.value;
             if (districtName && districtName !== "Select District") {
                 loadWards(districtName);
             } else {
                 let wardSelect = document.getElementById("ward");
                 wardSelect.innerHTML = '<option value="">Select Ward</option>';
             }
         });
     });
 </script>
<!-- Shopping Cart section end -->
@endsection
