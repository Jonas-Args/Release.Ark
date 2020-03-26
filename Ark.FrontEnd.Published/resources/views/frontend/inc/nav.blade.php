@php
     try
	 {
	 $_s = Session::get('apiSession');
	 $url = 'http://localhost:55006/api/user/Wallet';
	 $options = array(
		 'http' => array(
			 'method'  => 'GET',
			 'header'    => "Accept-language: en\r\n" .
				 "Cookie: .AspNetCore.Session=". $_s ."\r\n"
		 )
	 );
	 $context  = stream_context_create($options);
	 $result = file_get_contents($url, false, $context);
	 $UserWallet = json_decode($result);
     
     $UserWallet = $UserWallet->userWallet;
	 }
	 catch (Exception $exception)
	 {
		 echo '<script>window.location = "' .  route('logout') . '"</script>';
	 }
	 function number_format_short( $n, $precision = 2 ) {
				 if ($n < 900) {
					 // 0 - 900
					 $n_format = number_format($n, $precision);
					 $suffix = '';
				 } else if ($n < 900000) {
					 // 0.9k-850k
					 $n_format = number_format($n / 1000, $precision);
					 $suffix = 'K';
				 } else if ($n < 900000000) {
					 // 0.9m-850m
					 $n_format = number_format($n / 1000000, $precision);
					 $suffix = 'M';
				 } else if ($n < 900000000000) {
					 // 0.9b-850b
					 $n_format = number_format($n / 1000000000, $precision);
					 $suffix = 'B';
				 } else {
					 // 0.9t+
					 $n_format = number_format($n / 1000000000000, $precision);
					 $suffix = 'T';
				 }

				 // Remove unecessary zeroes after decimal. "1.0" -> "1"; "1.00" -> "1"
				 // Intentionally does not affect partials, eg "1.50" -> "1.50"
				 if ( $precision > 0 ) {
					 $dotzero = '.' . str_repeat( '0', $precision );
					 $n_format = str_replace( $dotzero, '', $n_format );
				 }

				 return $n_format . $suffix;
			 }


@endphp
<div class="header bg-white">
    <!-- Top Bar -->
    <div class="top-navbar">
        <div class="container">
            <div class="row">
                <div class="col-lg-7 col">
                    <ul class="inline-links d-lg-inline-block d-flex justify-content-between" >
                        @auth
                        <li class="dropdown" id="lang-change" style="float:right">                         
                            <a href="#" class="top-bar-item d-lg-none">
                              <i class="la la-wallet d-inline-block nav-box-icon" style="color:#0acf97"></i>  Ark Credit: <b>₱{{ number_format(floatval(Auth::user()->balance)) }} </b> 
                            </a>
                        </li>

                         <li class="dropdown" id="lang-change" style="float:right">                         
                            <a href="#" class="top-bar-item d-lg-none">
                              <i class="la la-wallet d-inline-block nav-box-icon" style="color:#fa5c7c"></i>  Ark Cash: <b>₱{{ count($UserWallet) > 0 ? number_format($UserWallet[array_search('ACW', array_column($UserWallet, 'walletCode'))]->balance,2) : "0"}}</b> 
                            </a>
                        </li>
                        @endauth
                        <li class="dropdown" id="lang-change" style="display:none!important">
                            @php
                                if(Session::has('locale')){
                                    $locale = Session::get('locale', Config::get('app.locale'));
                                }
                                else{
                                    $locale = 'en';
                                }
                            @endphp
                            <a href="" class="dropdown-toggle top-bar-item" data-toggle="dropdown">
                                <img src="{{ asset('frontend/images/placeholder.jpg') }}" height="11" data-src="{{ asset('frontend/images/icons/flags/'.$locale.'.png') }}" class="flag lazyload" alt="{{ \App\Language::where('code', $locale)->first()->name }}"><span class="language">{{ \App\Language::where('code', $locale)->first()->name }}</span>
                            </a>
                            <ul class="dropdown-menu">
                                @foreach (\App\Language::all() as $key => $language)
                                    <li class="dropdown-item @if($locale == $language) active @endif">
                                        <a href="#" data-flag="{{ $language->code }}"><img src="{{ asset('frontend/images/placeholder.jpg') }}" data-src="{{ asset('frontend/images/icons/flags/'.$language->code.'.png') }}" class="flag lazyload" alt="{{ $language->name }}"><span class="language">{{ $language->name }}</span></a>
                                    </li>
                                @endforeach
                            </ul>
                        </li>

                        <li class="dropdown" id="currency-change" style="display:none!important">
                            @php
                                if(Session::has('currency_code')){
                                    $currency_code = Session::get('currency_code');
                                }
                                else{
                                    $currency_code = \App\Currency::findOrFail(\App\BusinessSetting::where('type', 'system_default_currency')->first()->value)->code;
                                }
                            @endphp
                            <a href="" class="dropdown-toggle top-bar-item" data-toggle="dropdown">
                                {{ \App\Currency::where('code', $currency_code)->first()->name }} {{ (\App\Currency::where('code', $currency_code)->first()->symbol) }}
                            </a>
                            <ul class="dropdown-menu">
                                @foreach (\App\Currency::where('status', 1)->get() as $key => $currency)
                                    <li class="dropdown-item @if($currency_code == $currency->code) active @endif">
                                        <a href="" data-currency="{{ $currency->code }}">{{ $currency->name }} ({{ $currency->symbol }})</a>
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                    </ul>
                </div>

                <div class="col-5 text-right d-none d-lg-block">
                    <ul class="inline-links">
                        <li>
                            <a href="{{ route('orders.track') }}" class="top-bar-item">{{__('Track Order')}}</a>
                        </li>
                        @auth
                        <li>
                            <a href="{{ route('dashboard') }}" class="top-bar-item">{{__('My Account')}}</a>
                        </li>
                        <li>
                            <a href="{{ route('logout') }}" class="top-bar-item">{{__('Logout')}}</a>
                        </li>
                        @else
                        <li>
                            <a href="{{ route('user.login') }}" class="top-bar-item">{{__('Login')}}</a>
                        </li>
                        <li>
                            <a href="{{ route('user.registration') }}" class="top-bar-item">{{__('Registration')}}</a>
                        </li>
                        @endauth
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- END Top Bar -->

    <!-- mobile menu -->
    <div class="mobile-side-menu d-lg-none">
        <div class="side-menu-overlay opacity-0" onclick="sideMenuClose()" style="transition-duration: 0s!important;transition: none!important;"></div>
        <div class="side-menu-wrap opacity-0"  style="transition-duration: 0s!important;transition: none!important;">
            <div class="side-menu closed">
                <div class="side-menu-header"  @auth style="background-image:url('{{ asset("img/DASHBOARD_HEADER_LOGGED_IN.png") }}')"  @else style="background-image:url('{{ asset("img/DASHBOARD_HEADER_LOGGED_OUT.png") }}')" @endauth>
                    <div class="side-menu-close" onclick="sideMenuClose()">
                        <i class="la la-close"></i>
                    </div>

                    @auth
                        <div class="widget-profile-box px-3 py-4 d-flex align-items-center">
                                <div class="image " style="border:none!important;background-image:url('{{ Auth::user()->avatar_original != "" ? asset(Auth::user()->avatar_original) : asset('frontend/images/user.png') }}')"></div>
                                <div class="name">{{ Auth::user()->name }}</div>
                        </div>
                        <div class="side-login px-3 pb-3">
                            <a href="{{ route('logout') }}">{{__('Sign Out')}}</a>
                        </div>
                    @else
                        <div class="widget-profile-box px-3 py-4 d-flex align-items-center">
                                
                        </div>
                        <div class="side-login px-3 pb-3">
                          <!-- <a href="{{ route('user.login') }}">{{__('Sign In')}}</a>
                            <a href="{{ route('user.registration') }}">{{__('Registration')}}</a>--> 
                            <a href="#">{{__('Welcome to ARK PH!')}}</a>
                        </div>
                    @endauth
                </div>
                <div class="side-menu-list px-3">
                    <ul class="side-user-menu">
                        <li>
                            <a href="{{ route('home') }}">
                                <i class="la la-home"></i>
                                <span>{{__('Home')}}</span>
                            </a>
                        </li>

                          @auth
                        <li>
                            <a href="{{ route('dashboard') }}" >
                                <i class="la la-dashboard"></i>
                                <span>{{__('Dashboard')}}</span>
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('purchase_history.index') }}" >
                                <i class="la la-file-text"></i>
                                <span>{{__('Purchase History')}}</span>
                            </a>
                        </li>
                       
                            @php
                                $conversation = \App\Conversation::where('sender_id', Auth::user()->id)->where('sender_viewed', '1')->get();
                            @endphp
                            @if (\App\BusinessSetting::where('type', 'conversation_system')->first()->value == 1)
                                <li>
                                    <a href="{{ route('conversations.index') }}" class="{{ areActiveRoutesHome(['conversations.index', 'conversations.show'])}}">
                                        <i class="la la-comment"></i>
                                        <span class="category-name">
                                            {{__('Conversations')}}
                                            @if (count($conversation) > 0)
                                                <span class="ml-2" style="color:green"><strong>({{ count($conversation) }})</strong></span>
                                            @endif
                                        </span>
                                    </a>
                                </li>
                            @endif
                       
                      
                        <li>
                            <a href="{{ route('cart') }}">
                                <i class="la la-shopping-cart"></i>
                                <span>{{__('Cart')}}</span>
                                @if(Session::has('cart'))
                                    <span class="badge" id="cart_items_sidenav">{{ count(Session::get('cart'))}}</span>
                                @else
                                    <span class="badge" id="cart_items_sidenav">0</span>
                                @endif
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('wishlists.index') }}" >
                                <i class="la la-heart-o"></i>
                                <span>{{__('Wishlist')}}</span>
                            </a>
                        </li>

                        @if (\App\BusinessSetting::where('type', 'wallet_system')->first()->value == 1)
                            <li>
                                <a href="{{ route('wallet.index') }}">
                                    <i class="la la-dollar"></i>
                                    <span>{{__('My Wallet')}}</span>
                                </a>
                            </li>
                        @endif

                        <li>
                            <a href="{{ route('profile') }}" >
                                <i class="la la-user"></i>
                                <span>{{__('Manage Profile')}}</span>
                            </a>
                        </li>

                         <li>
                    <a href="{{ route('affiliate') }}" class="{{ areActiveRoutesHome(['affiliate'])}}">
                        <i class="la la-users"></i>
                        <span class="category-name">
                            {{__('Enterprise')}}
                        </span>
                    </a>
                </li>
                        <!--
                        <li>
                            <a href="#"  class="{{ areActiveRoutesHome(['support_ticket.index', 'support_ticket.show'])}}">
                                <i class="la la-support"></i>
                                <span class="category-name">
                                    {{__('Support Ticket')}}
                                </span>
                            </a>
                        </li>
                            -->

                    </ul>
                    @else
                    <li>
                            <a href="{{ route('user.login') }}">
                                <i class="la la-dashboard"></i>
                                <span>{{__('Sign In')}}</span>
                            </a>
                        </li>
                      <li>
                            <a href="{{ route('user.registration') }}">
                                <i class="la la-dashboard"></i>
                                <span>{{__('Register')}}</span>
                            </a>
                        </li>
                     @endauth
                    @if (Auth::check() && Auth::user()->user_type == 'seller')
                        <div class="sidebar-widget-title py-0">
                            <span>{{__('Shop Options')}}</span>
                        </div>
                        <ul class="side-seller-menu">
                            <li>
                                <a href="{{ route('seller.products') }}">
                                    <i class="la la-diamond"></i>
                                    <span>{{__('Products')}}</span>
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('orders.index') }}">
                                    <i class="la la-file-text"></i>
                                    <span>{{__('Orders')}}</span>
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('shops.index') }}">
                                    <i class="la la-cog"></i>
                                    <span>{{__('Shop Setting')}}</span>
                                </a>
                            </li>

                            @php
                                $conversation = \App\Conversation::where('receiver_id', Auth::user()->id)->where('receiver_viewed', '1')->get();
                            @endphp
                            @if (\App\BusinessSetting::where('type', 'conversation_system')->first()->value == 1)
                                <li>
                                    <a href="{{ route('conversations.index') }}" class="{{ areActiveRoutesHome(['conversations.index', 'conversations.show'])}}">
                                        <i class="la la-comment"></i>
                                        <span class="category-name">
                                            {{__('Conversations')}}
                                            @if (count($conversation) > 0)
                                                <span class="ml-2" style="color:green"><strong>({{ count($conversation) }})</strong></span>
                                            @endif
                                        </span>
                                    </a>
                                </li>
                            @endif

                            <li>
                                <a href="{{ route('payments.index') }}">
                                    <i class="la la-cc-mastercard"></i>
                                    <span>{{__('Payment History')}}</span>
                                </a>
                            </li>
                        </ul>
                        <div class="sidebar-widget-title py-0">
                            <span>{{__('Earnings')}}</span>
                        </div>
                        <div class="widget-balance py-3">
                            <div class="text-center">
                                <div class="heading-4 strong-700 mb-4">
                                    @php
                                        $orderDetails = \App\OrderDetail::where('seller_id', Auth::user()->id)->where('created_at', '>=', date('-30d'))->get();
                                        $total = 0;
                                        foreach ($orderDetails as $key => $orderDetail) {
                                            if($orderDetail->order->payment_status == 'paid'){
                                                $total += $orderDetail->price;
                                            }
                                        }
                                    @endphp
                                    <small class="d-block text-sm alpha-5 mb-2">{{__('Your earnings (current month)')}}</small>
                                    <span class="p-2 bg-base-1 rounded">{{ single_price($total) }}</span>
                                </div>
                                <table class="text-left mb-0 table w-75 m-auto">
                                    <tbody>
                                        <tr>
                                            @php
                                                $orderDetails = \App\OrderDetail::where('seller_id', Auth::user()->id)->get();
                                                $total = 0;
                                                foreach ($orderDetails as $key => $orderDetail) {
                                                    if($orderDetail->order->payment_status == 'paid'){
                                                        $total += $orderDetail->price;
                                                    }
                                                }
                                            @endphp
                                            <td class="p-1 text-sm">
                                                {{__('Total earnings')}}:
                                            </td>
                                            <td class="p-1">
                                                {{ single_price($total) }}
                                            </td>
                                        </tr>
                                        <tr>
                                            @php
                                                $orderDetails = \App\OrderDetail::where('seller_id', Auth::user()->id)->where('created_at', '>=', date('-60d'))->where('created_at', '<=', date('-30d'))->get();
                                                $total = 0;
                                                foreach ($orderDetails as $key => $orderDetail) {
                                                    if($orderDetail->order->payment_status == 'paid'){
                                                        $total += $orderDetail->price;
                                                    }
                                                }
                                            @endphp
                                            <td class="p-1 text-sm">
                                                {{__('Last Month earnings')}}:
                                            </td>
                                            <td class="p-1">
                                                {{ single_price($total) }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif
                    <div class="sidebar-widget-title py-0">
                        <span>Categories</span>
                    </div>
                    <ul class="side-seller-menu">
                        @foreach (\App\Category::all() as $key => $category)
                            <li>
                            <a href="{{ route('products.category', $category->slug) }}" class="text-truncate">
                                <img class="cat-image lazyload" src="{{ asset('frontend/images/placeholder.jpg') }}" data-src="{{ asset($category->icon) }}" width="28" alt="{{ __($category->name) }}">
                                <span>{{ __($category->name) }}</span>
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- end mobile menu -->

    <div class="position-relative logo-bar-area">
        <div class="">
            <div class="container">
                <div class="row no-gutters align-items-center">
                    <div class="col-lg-3 col-8">
                        <div class="d-flex">
                            <div class="d-block d-lg-none mobile-menu-icon-box">
                                <!-- Navbar toggler  -->
                                <a href="" onclick="sideMenuOpen(this)">
                                    <div class="hamburger-icon">
                                        <span></span>
                                        <span></span>
                                        <span></span>
                                        <span></span>
                                    </div>
                                </a>
                            </div>

                            <!-- Brand/Logo -->
                            <a class="navbar-brand w-100" href="{{ route('home') }}">
                                @php
                                    $generalsetting = \App\GeneralSetting::first();
                                @endphp
                                @if($generalsetting->logo != null)
                                  <!--  <img src="{{ asset($generalsetting->logo) }}" alt="{{ env('APP_NAME') }}">-->
                                    <img src="{{ asset('img/ARK_LOGO_TOP.png') }}" alt="{{ env('APP_NAME') }}">
                                @else
                                    <img src="{{ asset('img/ARK_LOGO_206X70.png') }}" alt="{{ env('APP_NAME') }}">
                                @endif
                            </a>

                            @if(Route::currentRouteName() != 'home' && Route::currentRouteName() != 'categories.all')
                                <div class="d-none d-xl-block category-menu-icon-box" style="min-width:120px">
                                    <div class="dropdown-toggle navbar-light category-menu-icon" id="category-menu-icon">
                                        <span class="navbar-toggler-icon"></span>
                                        <span>Categories</span>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="col-lg-9 col-4 position-static">
                        <div class="d-flex w-100">
                            <div class="search-box flex-grow-1 px-4">
                                <form action="{{ route('search') }}" method="GET">
                                    <div class="d-flex position-relative">
                                        <div class="d-lg-none search-box-back">
                                            <button class="" type="button"><i class="la la-long-arrow-left"></i></button>
                                        </div>
                                        <div class="w-100">
                                            <input type="text" aria-label="Search" id="search" name="q" class="w-100" placeholder="I'm shopping for..." autocomplete="off">
                                        </div>
                                        <div class="form-group category-select d-none d-xl-block" style="display:none!important">
                                            <select class="form-control selectpicker" name="category">
                                                <option value="">{{__('All Categories')}}</option>
                                                @foreach (\App\Category::all() as $key => $category)
                                                <option value="{{ $category->slug }}"
                                                    @isset($category_id)
                                                        @if ($category_id == $category->id)
                                                            selected
                                                        @endif
                                                    @endisset
                                                    >{{ __($category->name) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <button class="d-none d-lg-block" type="submit">
                                            <i class="la la-search la-flip-horizontal"></i>
                                        </button>
                                        <div class="typed-search-box d-none">
                                            <div class="search-preloader">
                                                <div class="loader"><div></div><div></div><div></div></div>
                                            </div>
                                            <div class="search-nothing d-none">

                                            </div>
                                            <div id="search-content">

                                            </div>
                                        </div>
                                    </div>
                                </form>

                            </div>

                            <div class="logo-bar-icons d-inline-block ml-auto">
                                <div class="d-inline-block d-lg-none">
                                    <div class="nav-search-box">
                                        <a href="#" class="nav-box-link">
                                            <i class="la la-search la-flip-horizontal d-inline-block nav-box-icon"></i>
                                        </a>
                                    </div>
                                </div>
                                <div class="d-none d-lg-inline-block">
                                    <div class="nav-compare-box" id="compare" style="width:100px">
                                        <a href="{{ route('wallet.index') }}"  class="nav-box-link">
                                            <i class="la la-wallet d-inline-block nav-box-icon"></i>
                                            <span class="nav-box-text d-none d-xl-inline-block">{{__('Ark Credits')}}</span>
                                           <span class="nav-box-number" style="width: max-content;padding: 0px 10px; background-color:#0acf97!important">
                                             @auth
                                               {{ number_format_short(floatval(Auth::user()->balance)) }}
                                            @else
                                               0
                                             @endauth
                                            </span>
                                        </a>
                                    </div>
                                </div>
                                <div class="d-none d-lg-inline-block">
                                    <div class="nav-wishlist-box" id="wishlist">
                                        <a href="#"  class="nav-box-link">
                                            <i class="la la-heart-o d-inline-block nav-box-icon"></i>
                                            <span class="nav-box-text d-none d-xl-inline-block">{{__('Wishlist')}}</span>
                                            @if(Auth::check())
                                                <span class="nav-box-number">{{ count(Auth::user()->wishlists)}}</span>
                                            @else
                                                <span class="nav-box-number">0</span>
                                            @endif
                                        </a>
                                    </div>
                                </div>
                                <div class="d-inline-block" data-hover="dropdown">
                                    <div class="nav-cart-box dropdown" id="cart_items">
                                        <a href="" class="nav-box-link" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="la la-shopping-cart d-inline-block nav-box-icon"></i>
                                            <span class="nav-box-text d-none d-xl-inline-block">{{__('Cart')}}</span>
                                            @if(Session::has('cart'))
                                                <span class="nav-box-number">{{ count(Session::get('cart'))}}</span>
                                            @else
                                                <span class="nav-box-number">0</span>
                                            @endif
                                        </a>
                                        <ul class="dropdown-menu dropdown-menu-right px-0">
                                            <li>
                                                <div class="dropdown-cart px-0">
                                                    @if(Session::has('cart'))
                                                        @if(count($cart = Session::get('cart')) > 0)
                                                            <div class="dc-header">
                                                                <h3 class="heading heading-6 strong-700">{{__('Cart Items')}}</h3>
                                                            </div>
                                                            <div class="dropdown-cart-items c-scrollbar">
                                                                @php
                                                                    $total = 0;
                                                                @endphp
                                                                @foreach($cart as $key => $cartItem)
                                                                    @php
                                                                        $product = \App\Product::find($cartItem['id']);
                                                                        $total = $total + $cartItem['price']*$cartItem['quantity'];
                                                                    @endphp
                                                                    <div class="dc-item">
                                                                        <div class="d-flex align-items-center">
                                                                            <div class="dc-image">
                                                                                <a href="{{ route('product', $product->slug) }}">
                                                                                    <img src="{{ asset('frontend/images/placeholder.jpg') }}" data-src="{{ asset($product->thumbnail_img) }}" class="img-fluid lazyload" alt="{{ __($product->name) }}">
                                                                                </a>
                                                                            </div>
                                                                            <div class="dc-content">
                                                                                <span class="d-block dc-product-name text-capitalize strong-600 mb-1">
                                                                                    <a href="{{ route('product', $product->slug) }}">
                                                                                        {{ __($product->name) }}
                                                                                    </a>
                                                                                </span>

                                                                                <span class="dc-quantity">x{{ $cartItem['quantity'] }}</span>
                                                                                <span class="dc-price">{{ single_price($cartItem['price']*$cartItem['quantity']) }}</span>
                                                                            </div>
                                                                            <div class="dc-actions">
                                                                                <button onclick="removeFromCart({{ $key }})">
                                                                                    <i class="la la-close"></i>
                                                                                </button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                            <div class="dc-item py-3">
                                                                <span class="subtotal-text">{{__('Subtotal')}}</span>
                                                                <span class="subtotal-amount">{{ single_price($total) }}</span>
                                                            </div>
                                                            <div class="py-2 text-center dc-btn">
                                                                <ul class="inline-links inline-links--style-3">
                                                                    <li class="px-1">
                                                                        <a href="{{ route('cart') }}" class="link link--style-1 text-capitalize btn btn-base-1 px-3 py-1">
                                                                            <i class="la la-shopping-cart"></i> {{__('View cart')}}
                                                                        </a>
                                                                    </li>
                                                                    @if (Auth::check())
                                                                    <li class="px-1">
                                                                        <a href="{{ route('checkout.shipping_info') }}" class="link link--style-1 text-capitalize btn btn-base-1 px-3 py-1 light-text">
                                                                            <i class="la la-mail-forward"></i> {{__('Checkout')}}
                                                                        </a>
                                                                    </li>
                                                                    @endif
                                                                </ul>
                                                            </div>
                                                        @else
                                                            <div class="dc-header">
                                                                <h3 class="heading heading-6 strong-700">{{__('Your Cart is empty')}}</h3>
                                                            </div>
                                                        @endif
                                                    @else
                                                        <div class="dc-header">
                                                            <h3 class="heading heading-6 strong-700">{{__('Your Cart is empty')}}</h3>
                                                        </div>
                                                    @endif
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="hover-category-menu" id="hover-category-menu">
            <div class="container">
                <div class="row no-gutters position-relative">
                    <div class="col-lg-3 position-static">
                        <div class="category-sidebar" id="category-sidebar">
                            <div class="all-category">
                                <span>{{__('CATEGORIES')}}</span>
                                <a href="{{ route('categories.all') }}" class="d-inline-block">See All ></a>
                            </div>
                            <ul class="categories">
                                @foreach (\App\Category::all()->take(11) as $key => $category)
                                    @php
                                        $brands = array();
                                    @endphp
                                    <li>
                                        <a href="{{ route('products.category', $category->slug) }}">
                                            <img class="cat-image lazyload" src="{{ asset('frontend/images/placeholder.jpg') }}" data-src="{{ asset($category->icon) }}" width="30" alt="{{ __($category->name) }}">
                                            <span class="cat-name">{{ __($category->name) }}</span>
                                        </a>
                                        @if(count($category->subcategories)>0)
                                            <div class="sub-cat-menu c-scrollbar">
                                                <div class="sub-cat-main row no-gutters">
                                                    <div class="col-9">
                                                        <div class="sub-cat-content">
                                                            <div class="sub-cat-list">
                                                                <div class="card-columns">
                                                                    @foreach ($category->subcategories as $subcategory)
                                                                        <div class="card">
                                                                            <ul class="sub-cat-items">
                                                                                <li class="sub-cat-name"><a href="{{ route('products.subcategory', $subcategory->slug) }}">{{ __($subcategory->name) }}</a></li>
                                                                                @foreach ($subcategory->subsubcategories as $subsubcategory)
                                                                                    @php
                                                                                        foreach (json_decode($subsubcategory->brands) as $brand) {
                                                                                            if(!in_array($brand, $brands)){
                                                                                                array_push($brands, $brand);
                                                                                            }
                                                                                        }
                                                                                    @endphp
                                                                                    <li><a href="{{ route('products.subsubcategory', $subsubcategory->slug) }}">{{ __($subsubcategory->name) }}</a></li>
                                                                                @endforeach
                                                                            </ul>
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                            {{-- <div class="sub-cat-featured">
                                                                <ul class="sub-cat-featured-list inline-links d-flex">
                                                                    <li class="col">
                                                                        <a href="" >
                                                                            <span class="featured-name">New arrival plus size</span>
                                                                            <span class="featured-img">
                                                                                <img loading="lazy"  src="{{ asset('frontend/images/girls/1.png') }}" class="img-fluid">
                                                                            </span>
                                                                        </a>
                                                                    </li>
                                                                    <li class="col">
                                                                        <a href="" >
                                                                            <span class="featured-name">Sweater Collection</span>
                                                                            <span class="featured-img">
                                                                                <img loading="lazy"  src="{{ asset('frontend/images/girls/2.png') }}" class="img-fluid">
                                                                            </span>
                                                                        </a>
                                                                    </li>
                                                                    <li class="col">
                                                                        <a href="" >
                                                                            <span class="featured-name">High Quality Formal Dresses</span>
                                                                            <span class="featured-img">
                                                                                <img loading="lazy"  src="{{ asset('frontend/images/girls/3.png') }}" class="img-fluid">
                                                                            </span>
                                                                        </a>
                                                                    </li>
                                                                </ul>
                                                            </div> --}}
                                                        </div>
                                                    </div>

                                                    <div class="col-3">
                                                        <div class="sub-cat-brand">
                                                            <ul class="sub-brand-list">
                                                                @foreach ($brands as $brand_id)
                                                                    @if(\App\Brand::find($brand_id) != null)
                                                                        <li class="sub-brand-item">
                                                                            <a href="{{ route('products.brand', \App\Brand::find($brand_id)->slug) }}" ><img src="{{ asset('frontend/images/placeholder.jpg') }}" data-src="{{ asset(\App\Brand::find($brand_id)->logo) }}" class="img-fluid lazyload" alt="{{ asset(\App\Brand::find($brand_id)->name) }}"></a>
                                                                        </li>
                                                                    @endif
                                                                @endforeach
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <!-- Navbar -->

    <div class="main-nav-area d-none d-lg-block">
        <nav class="navbar navbar-expand-lg navbar--bold navbar--style-2 navbar-light bg-default">
            <div class="container">
                <div class="collapse navbar-collapse align-items-center justify-content-center" id="navbar_main">
                    <!-- Navbar links -->
                    <ul class="navbar-nav" style="display:none">
                        @foreach (\App\Search::orderBy('count', 'desc')->get()->take(5) as $key => $search)
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('suggestion.search', $search->query) }}">{{ $search->query }}</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </nav>
    </div>
</div>
