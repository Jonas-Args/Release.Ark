<?php
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


?>
<div class="header bg-white">
    <!-- Top Bar -->
    <div class="top-navbar">
        <div class="container">
            <div class="row">
                <div class="col-lg-7 col">
                    <ul class="inline-links d-lg-inline-block d-flex justify-content-between" >
                        <?php if(auth()->guard()->check()): ?>
                        <li class="dropdown" id="lang-change" style="float:right">                         
                            <a href="#" class="top-bar-item d-lg-none">
                              <i class="la la-wallet d-inline-block nav-box-icon" style="color:#0acf97"></i>  Ark Credit: <b>₱<?php echo e(number_format(floatval(Auth::user()->balance))); ?> </b> 
                            </a>
                        </li>

                         <li class="dropdown" id="lang-change" style="float:right">                         
                            <a href="#" class="top-bar-item d-lg-none">
                              <i class="la la-wallet d-inline-block nav-box-icon" style="color:#fa5c7c"></i>  Ark Cash: <b>₱<?php echo e(count($UserWallet) > 0 ? number_format($UserWallet[array_search('ACW', array_column($UserWallet, 'walletCode'))]->balance,2) : "0"); ?></b> 
                            </a>
                        </li>
                        <?php endif; ?>
                        <li class="dropdown" id="lang-change" style="display:none!important">
                            <?php
                                if(Session::has('locale')){
                                    $locale = Session::get('locale', Config::get('app.locale'));
                                }
                                else{
                                    $locale = 'en';
                                }
                            ?>
                            <a href="" class="dropdown-toggle top-bar-item" data-toggle="dropdown">
                                <img src="<?php echo e(asset('frontend/images/placeholder.jpg')); ?>" height="11" data-src="<?php echo e(asset('frontend/images/icons/flags/'.$locale.'.png')); ?>" class="flag lazyload" alt="<?php echo e(\App\Language::where('code', $locale)->first()->name); ?>"><span class="language"><?php echo e(\App\Language::where('code', $locale)->first()->name); ?></span>
                            </a>
                            <ul class="dropdown-menu">
                                <?php $__currentLoopData = \App\Language::all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $language): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li class="dropdown-item <?php if($locale == $language): ?> active <?php endif; ?>">
                                        <a href="#" data-flag="<?php echo e($language->code); ?>"><img src="<?php echo e(asset('frontend/images/placeholder.jpg')); ?>" data-src="<?php echo e(asset('frontend/images/icons/flags/'.$language->code.'.png')); ?>" class="flag lazyload" alt="<?php echo e($language->name); ?>"><span class="language"><?php echo e($language->name); ?></span></a>
                                    </li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </li>

                        <li class="dropdown" id="currency-change" style="display:none!important">
                            <?php
                                if(Session::has('currency_code')){
                                    $currency_code = Session::get('currency_code');
                                }
                                else{
                                    $currency_code = \App\Currency::findOrFail(\App\BusinessSetting::where('type', 'system_default_currency')->first()->value)->code;
                                }
                            ?>
                            <a href="" class="dropdown-toggle top-bar-item" data-toggle="dropdown">
                                <?php echo e(\App\Currency::where('code', $currency_code)->first()->name); ?> <?php echo e((\App\Currency::where('code', $currency_code)->first()->symbol)); ?>

                            </a>
                            <ul class="dropdown-menu">
                                <?php $__currentLoopData = \App\Currency::where('status', 1)->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $currency): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li class="dropdown-item <?php if($currency_code == $currency->code): ?> active <?php endif; ?>">
                                        <a href="" data-currency="<?php echo e($currency->code); ?>"><?php echo e($currency->name); ?> (<?php echo e($currency->symbol); ?>)</a>
                                    </li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </li>
                    </ul>
                </div>

                <div class="col-5 text-right d-none d-lg-block">
                    <ul class="inline-links">
                        <li>
                            <a href="<?php echo e(route('orders.track')); ?>" class="top-bar-item"><?php echo e(__('Track Order')); ?></a>
                        </li>
                        <?php if(auth()->guard()->check()): ?>
                        <li>
                            <a href="<?php echo e(route('dashboard')); ?>" class="top-bar-item"><?php echo e(__('My Account')); ?></a>
                        </li>
                        <li>
                            <a href="<?php echo e(route('logout')); ?>" class="top-bar-item"><?php echo e(__('Logout')); ?></a>
                        </li>
                        <?php else: ?>
                        <li>
                            <a href="<?php echo e(route('user.login')); ?>" class="top-bar-item"><?php echo e(__('Login')); ?></a>
                        </li>
                        <li>
                            <a href="<?php echo e(route('user.registration')); ?>" class="top-bar-item"><?php echo e(__('Registration')); ?></a>
                        </li>
                        <?php endif; ?>
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
                <div class="side-menu-header"  <?php if(auth()->guard()->check()): ?> style="background-image:url('<?php echo e(asset("img/DASHBOARD_HEADER_LOGGED_IN.png")); ?>')"  <?php else: ?> style="background-image:url('<?php echo e(asset("img/DASHBOARD_HEADER_LOGGED_OUT.png")); ?>')" <?php endif; ?>>
                    <div class="side-menu-close" onclick="sideMenuClose()">
                        <i class="la la-close"></i>
                    </div>

                    <?php if(auth()->guard()->check()): ?>
                        <div class="widget-profile-box px-3 py-4 d-flex align-items-center">
                                <div class="image " style="border:none!important;background-image:url('<?php echo e(Auth::user()->avatar_original != "" ? asset(Auth::user()->avatar_original) : asset('frontend/images/user.png')); ?>')"></div>
                                <div class="name"><?php echo e(Auth::user()->name); ?></div>
                        </div>
                        <div class="side-login px-3 pb-3">
                            <a href="<?php echo e(route('logout')); ?>"><?php echo e(__('Sign Out')); ?></a>
                        </div>
                    <?php else: ?>
                        <div class="widget-profile-box px-3 py-4 d-flex align-items-center">
                                
                        </div>
                        <div class="side-login px-3 pb-3">
                          <!-- <a href="<?php echo e(route('user.login')); ?>"><?php echo e(__('Sign In')); ?></a>
                            <a href="<?php echo e(route('user.registration')); ?>"><?php echo e(__('Registration')); ?></a>--> 
                            <a href="#"><?php echo e(__('Ark Philippines')); ?></a>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="side-menu-list px-3">
                    <ul class="side-user-menu">
                        <li>
                            <a href="<?php echo e(route('home')); ?>">
                                <i class="la la-home"></i>
                                <span><?php echo e(__('Home')); ?></span>
                            </a>
                        </li>

                          <?php if(auth()->guard()->check()): ?>
                        <li>
                            <a href="<?php echo e(route('dashboard')); ?>" >
                                <i class="la la-dashboard"></i>
                                <span><?php echo e(__('Dashboard')); ?></span>
                            </a>
                        </li>

                        <li>
                            <a href="<?php echo e(route('purchase_history.index')); ?>" >
                                <i class="la la-file-text"></i>
                                <span><?php echo e(__('Purchase History')); ?></span>
                            </a>
                        </li>
                       
                            <?php
                                $conversation = \App\Conversation::where('sender_id', Auth::user()->id)->where('sender_viewed', '1')->get();
                            ?>
                            <?php if(\App\BusinessSetting::where('type', 'conversation_system')->first()->value == 1): ?>
                                <li>
                                    <a href="<?php echo e(route('conversations.index')); ?>" class="<?php echo e(areActiveRoutesHome(['conversations.index', 'conversations.show'])); ?>">
                                        <i class="la la-comment"></i>
                                        <span class="category-name">
                                            <?php echo e(__('Conversations')); ?>

                                            <?php if(count($conversation) > 0): ?>
                                                <span class="ml-2" style="color:green"><strong>(<?php echo e(count($conversation)); ?>)</strong></span>
                                            <?php endif; ?>
                                        </span>
                                    </a>
                                </li>
                            <?php endif; ?>
                       
                      
                        <li>
                            <a href="<?php echo e(route('cart')); ?>">
                                <i class="la la-shopping-cart"></i>
                                <span><?php echo e(__('Cart')); ?></span>
                                <?php if(Session::has('cart')): ?>
                                    <span class="badge" id="cart_items_sidenav"><?php echo e(count(Session::get('cart'))); ?></span>
                                <?php else: ?>
                                    <span class="badge" id="cart_items_sidenav">0</span>
                                <?php endif; ?>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo e(route('wishlists.index')); ?>" >
                                <i class="la la-heart-o"></i>
                                <span><?php echo e(__('Wishlist')); ?></span>
                            </a>
                        </li>

                        <?php if(\App\BusinessSetting::where('type', 'wallet_system')->first()->value == 1): ?>
                            <li>
                                <a href="<?php echo e(route('wallet.index')); ?>">
                                    <i class="la la-dollar"></i>
                                    <span><?php echo e(__('My Wallet')); ?></span>
                                </a>
                            </li>
                        <?php endif; ?>

                        <li>
                            <a href="<?php echo e(route('profile')); ?>" >
                                <i class="la la-user"></i>
                                <span><?php echo e(__('Manage Profile')); ?></span>
                            </a>
                        </li>

                         <li>
                    <a href="<?php echo e(route('affiliate')); ?>" class="<?php echo e(areActiveRoutesHome(['affiliate'])); ?>">
                        <i class="la la-users"></i>
                        <span class="category-name">
                            <?php echo e(__('Enterprise')); ?>

                        </span>
                    </a>
                </li>
                        <!--
                        <li>
                            <a href="#"  class="<?php echo e(areActiveRoutesHome(['support_ticket.index', 'support_ticket.show'])); ?>">
                                <i class="la la-support"></i>
                                <span class="category-name">
                                    <?php echo e(__('Support Ticket')); ?>

                                </span>
                            </a>
                        </li>
                            -->

                    </ul>
                    <?php else: ?>
                    <li>
                            <a href="<?php echo e(route('user.login')); ?>">
                                <i class="la la-dashboard"></i>
                                <span><?php echo e(__('Sign In')); ?></span>
                            </a>
                        </li>
                      <li>
                            <a href="<?php echo e(route('user.registration')); ?>">
                                <i class="la la-dashboard"></i>
                                <span><?php echo e(__('Register')); ?></span>
                            </a>
                        </li>
                     <?php endif; ?>
                    <?php if(Auth::check() && Auth::user()->user_type == 'seller'): ?>
                        <div class="sidebar-widget-title py-0">
                            <span><?php echo e(__('Shop Options')); ?></span>
                        </div>
                        <ul class="side-seller-menu">
                            <li>
                                <a href="<?php echo e(route('seller.products')); ?>">
                                    <i class="la la-diamond"></i>
                                    <span><?php echo e(__('Products')); ?></span>
                                </a>
                            </li>

                            <li>
                                <a href="<?php echo e(route('orders.index')); ?>">
                                    <i class="la la-file-text"></i>
                                    <span><?php echo e(__('Orders')); ?></span>
                                </a>
                            </li>

                            <li>
                                <a href="<?php echo e(route('shops.index')); ?>">
                                    <i class="la la-cog"></i>
                                    <span><?php echo e(__('Shop Setting')); ?></span>
                                </a>
                            </li>

                            <?php
                                $conversation = \App\Conversation::where('receiver_id', Auth::user()->id)->where('receiver_viewed', '1')->get();
                            ?>
                            <?php if(\App\BusinessSetting::where('type', 'conversation_system')->first()->value == 1): ?>
                                <li>
                                    <a href="<?php echo e(route('conversations.index')); ?>" class="<?php echo e(areActiveRoutesHome(['conversations.index', 'conversations.show'])); ?>">
                                        <i class="la la-comment"></i>
                                        <span class="category-name">
                                            <?php echo e(__('Conversations')); ?>

                                            <?php if(count($conversation) > 0): ?>
                                                <span class="ml-2" style="color:green"><strong>(<?php echo e(count($conversation)); ?>)</strong></span>
                                            <?php endif; ?>
                                        </span>
                                    </a>
                                </li>
                            <?php endif; ?>

                            <li>
                                <a href="<?php echo e(route('payments.index')); ?>">
                                    <i class="la la-cc-mastercard"></i>
                                    <span><?php echo e(__('Payment History')); ?></span>
                                </a>
                            </li>
                        </ul>
                        <div class="sidebar-widget-title py-0">
                            <span><?php echo e(__('Earnings')); ?></span>
                        </div>
                        <div class="widget-balance py-3">
                            <div class="text-center">
                                <div class="heading-4 strong-700 mb-4">
                                    <?php
                                        $orderDetails = \App\OrderDetail::where('seller_id', Auth::user()->id)->where('created_at', '>=', date('-30d'))->get();
                                        $total = 0;
                                        foreach ($orderDetails as $key => $orderDetail) {
                                            if($orderDetail->order->payment_status == 'paid'){
                                                $total += $orderDetail->price;
                                            }
                                        }
                                    ?>
                                    <small class="d-block text-sm alpha-5 mb-2"><?php echo e(__('Your earnings (current month)')); ?></small>
                                    <span class="p-2 bg-base-1 rounded"><?php echo e(single_price($total)); ?></span>
                                </div>
                                <table class="text-left mb-0 table w-75 m-auto">
                                    <tbody>
                                        <tr>
                                            <?php
                                                $orderDetails = \App\OrderDetail::where('seller_id', Auth::user()->id)->get();
                                                $total = 0;
                                                foreach ($orderDetails as $key => $orderDetail) {
                                                    if($orderDetail->order->payment_status == 'paid'){
                                                        $total += $orderDetail->price;
                                                    }
                                                }
                                            ?>
                                            <td class="p-1 text-sm">
                                                <?php echo e(__('Total earnings')); ?>:
                                            </td>
                                            <td class="p-1">
                                                <?php echo e(single_price($total)); ?>

                                            </td>
                                        </tr>
                                        <tr>
                                            <?php
                                                $orderDetails = \App\OrderDetail::where('seller_id', Auth::user()->id)->where('created_at', '>=', date('-60d'))->where('created_at', '<=', date('-30d'))->get();
                                                $total = 0;
                                                foreach ($orderDetails as $key => $orderDetail) {
                                                    if($orderDetail->order->payment_status == 'paid'){
                                                        $total += $orderDetail->price;
                                                    }
                                                }
                                            ?>
                                            <td class="p-1 text-sm">
                                                <?php echo e(__('Last Month earnings')); ?>:
                                            </td>
                                            <td class="p-1">
                                                <?php echo e(single_price($total)); ?>

                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="sidebar-widget-title py-0">
                        <span>Categories</span>
                    </div>
                    <ul class="side-seller-menu">
                        <?php $__currentLoopData = \App\Category::all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li>
                            <a href="<?php echo e(route('products.category', $category->slug)); ?>" class="text-truncate">
                                <img class="cat-image lazyload" src="<?php echo e(asset('frontend/images/placeholder.jpg')); ?>" data-src="<?php echo e(asset($category->icon)); ?>" width="28" alt="<?php echo e(__($category->name)); ?>">
                                <span><?php echo e(__($category->name)); ?></span>
                            </a>
                        </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                            <a class="navbar-brand w-100" href="<?php echo e(route('home')); ?>">
                                <?php
                                    $generalsetting = \App\GeneralSetting::first();
                                ?>
                                <?php if($generalsetting->logo != null): ?>
                                    <img src="<?php echo e(asset($generalsetting->logo)); ?>" alt="<?php echo e(env('APP_NAME')); ?>">
                                <?php else: ?>
                                    <img src="<?php echo e(asset('frontend/images/logo/logo.png')); ?>" alt="<?php echo e(env('APP_NAME')); ?>">
                                <?php endif; ?>
                            </a>

                            <?php if(Route::currentRouteName() != 'home' && Route::currentRouteName() != 'categories.all'): ?>
                                <div class="d-none d-xl-block category-menu-icon-box" style="min-width:120px">
                                    <div class="dropdown-toggle navbar-light category-menu-icon" id="category-menu-icon">
                                        <span class="navbar-toggler-icon"></span>
                                        <span>Categories</span>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-lg-9 col-4 position-static">
                        <div class="d-flex w-100">
                            <div class="search-box flex-grow-1 px-4">
                                <form action="<?php echo e(route('search')); ?>" method="GET">
                                    <div class="d-flex position-relative">
                                        <div class="d-lg-none search-box-back">
                                            <button class="" type="button"><i class="la la-long-arrow-left"></i></button>
                                        </div>
                                        <div class="w-100">
                                            <input type="text" aria-label="Search" id="search" name="q" class="w-100" placeholder="I'm shopping for..." autocomplete="off">
                                        </div>
                                        <div class="form-group category-select d-none d-xl-block">
                                            <select class="form-control selectpicker" name="category">
                                                <option value=""><?php echo e(__('All Categories')); ?></option>
                                                <?php $__currentLoopData = \App\Category::all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($category->slug); ?>"
                                                    <?php if(isset($category_id)): ?>
                                                        <?php if($category_id == $category->id): ?>
                                                            selected
                                                        <?php endif; ?>
                                                    <?php endif; ?>
                                                    ><?php echo e(__($category->name)); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                                        <a href="<?php echo e(route('wallet.index')); ?>"  class="nav-box-link">
                                            <i class="la la-wallet d-inline-block nav-box-icon"></i>
                                            <span class="nav-box-text d-none d-xl-inline-block"><?php echo e(__('Ark Credits')); ?></span>
                                           <span class="nav-box-number" style="width: max-content;padding: 0px 10px; background-color:#0acf97!important">
                                             <?php if(auth()->guard()->check()): ?>
                                               <?php echo e(number_format_short(floatval(Auth::user()->balance))); ?>

                                            <?php else: ?>
                                               0
                                             <?php endif; ?>
                                            </span>
                                        </a>
                                    </div>
                                </div>
                                <div class="d-none d-lg-inline-block">
                                    <div class="nav-wishlist-box" id="wishlist">
                                        <a href="#"  class="nav-box-link">
                                            <i class="la la-heart-o d-inline-block nav-box-icon"></i>
                                            <span class="nav-box-text d-none d-xl-inline-block"><?php echo e(__('Wishlist')); ?></span>
                                            <?php if(Auth::check()): ?>
                                                <span class="nav-box-number"><?php echo e(count(Auth::user()->wishlists)); ?></span>
                                            <?php else: ?>
                                                <span class="nav-box-number">0</span>
                                            <?php endif; ?>
                                        </a>
                                    </div>
                                </div>
                                <div class="d-inline-block" data-hover="dropdown">
                                    <div class="nav-cart-box dropdown" id="cart_items">
                                        <a href="" class="nav-box-link" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="la la-shopping-cart d-inline-block nav-box-icon"></i>
                                            <span class="nav-box-text d-none d-xl-inline-block"><?php echo e(__('Cart')); ?></span>
                                            <?php if(Session::has('cart')): ?>
                                                <span class="nav-box-number"><?php echo e(count(Session::get('cart'))); ?></span>
                                            <?php else: ?>
                                                <span class="nav-box-number">0</span>
                                            <?php endif; ?>
                                        </a>
                                        <ul class="dropdown-menu dropdown-menu-right px-0">
                                            <li>
                                                <div class="dropdown-cart px-0">
                                                    <?php if(Session::has('cart')): ?>
                                                        <?php if(count($cart = Session::get('cart')) > 0): ?>
                                                            <div class="dc-header">
                                                                <h3 class="heading heading-6 strong-700"><?php echo e(__('Cart Items')); ?></h3>
                                                            </div>
                                                            <div class="dropdown-cart-items c-scrollbar">
                                                                <?php
                                                                    $total = 0;
                                                                ?>
                                                                <?php $__currentLoopData = $cart; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $cartItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                    <?php
                                                                        $product = \App\Product::find($cartItem['id']);
                                                                        $total = $total + $cartItem['price']*$cartItem['quantity'];
                                                                    ?>
                                                                    <div class="dc-item">
                                                                        <div class="d-flex align-items-center">
                                                                            <div class="dc-image">
                                                                                <a href="<?php echo e(route('product', $product->slug)); ?>">
                                                                                    <img src="<?php echo e(asset('frontend/images/placeholder.jpg')); ?>" data-src="<?php echo e(asset($product->thumbnail_img)); ?>" class="img-fluid lazyload" alt="<?php echo e(__($product->name)); ?>">
                                                                                </a>
                                                                            </div>
                                                                            <div class="dc-content">
                                                                                <span class="d-block dc-product-name text-capitalize strong-600 mb-1">
                                                                                    <a href="<?php echo e(route('product', $product->slug)); ?>">
                                                                                        <?php echo e(__($product->name)); ?>

                                                                                    </a>
                                                                                </span>

                                                                                <span class="dc-quantity">x<?php echo e($cartItem['quantity']); ?></span>
                                                                                <span class="dc-price"><?php echo e(single_price($cartItem['price']*$cartItem['quantity'])); ?></span>
                                                                            </div>
                                                                            <div class="dc-actions">
                                                                                <button onclick="removeFromCart(<?php echo e($key); ?>)">
                                                                                    <i class="la la-close"></i>
                                                                                </button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                            </div>
                                                            <div class="dc-item py-3">
                                                                <span class="subtotal-text"><?php echo e(__('Subtotal')); ?></span>
                                                                <span class="subtotal-amount"><?php echo e(single_price($total)); ?></span>
                                                            </div>
                                                            <div class="py-2 text-center dc-btn">
                                                                <ul class="inline-links inline-links--style-3">
                                                                    <li class="px-1">
                                                                        <a href="<?php echo e(route('cart')); ?>" class="link link--style-1 text-capitalize btn btn-base-1 px-3 py-1">
                                                                            <i class="la la-shopping-cart"></i> <?php echo e(__('View cart')); ?>

                                                                        </a>
                                                                    </li>
                                                                    <?php if(Auth::check()): ?>
                                                                    <li class="px-1">
                                                                        <a href="<?php echo e(route('checkout.shipping_info')); ?>" class="link link--style-1 text-capitalize btn btn-base-1 px-3 py-1 light-text">
                                                                            <i class="la la-mail-forward"></i> <?php echo e(__('Checkout')); ?>

                                                                        </a>
                                                                    </li>
                                                                    <?php endif; ?>
                                                                </ul>
                                                            </div>
                                                        <?php else: ?>
                                                            <div class="dc-header">
                                                                <h3 class="heading heading-6 strong-700"><?php echo e(__('Your Cart is empty')); ?></h3>
                                                            </div>
                                                        <?php endif; ?>
                                                    <?php else: ?>
                                                        <div class="dc-header">
                                                            <h3 class="heading heading-6 strong-700"><?php echo e(__('Your Cart is empty')); ?></h3>
                                                        </div>
                                                    <?php endif; ?>
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
                                <span><?php echo e(__('CATEGORIES')); ?></span>
                                <a href="<?php echo e(route('categories.all')); ?>" class="d-inline-block">See All ></a>
                            </div>
                            <ul class="categories">
                                <?php $__currentLoopData = \App\Category::all()->take(11); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        $brands = array();
                                    ?>
                                    <li>
                                        <a href="<?php echo e(route('products.category', $category->slug)); ?>">
                                            <img class="cat-image lazyload" src="<?php echo e(asset('frontend/images/placeholder.jpg')); ?>" data-src="<?php echo e(asset($category->icon)); ?>" width="30" alt="<?php echo e(__($category->name)); ?>">
                                            <span class="cat-name"><?php echo e(__($category->name)); ?></span>
                                        </a>
                                        <?php if(count($category->subcategories)>0): ?>
                                            <div class="sub-cat-menu c-scrollbar">
                                                <div class="sub-cat-main row no-gutters">
                                                    <div class="col-9">
                                                        <div class="sub-cat-content">
                                                            <div class="sub-cat-list">
                                                                <div class="card-columns">
                                                                    <?php $__currentLoopData = $category->subcategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subcategory): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                        <div class="card">
                                                                            <ul class="sub-cat-items">
                                                                                <li class="sub-cat-name"><a href="<?php echo e(route('products.subcategory', $subcategory->slug)); ?>"><?php echo e(__($subcategory->name)); ?></a></li>
                                                                                <?php $__currentLoopData = $subcategory->subsubcategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subsubcategory): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                                    <?php
                                                                                        foreach (json_decode($subsubcategory->brands) as $brand) {
                                                                                            if(!in_array($brand, $brands)){
                                                                                                array_push($brands, $brand);
                                                                                            }
                                                                                        }
                                                                                    ?>
                                                                                    <li><a href="<?php echo e(route('products.subsubcategory', $subsubcategory->slug)); ?>"><?php echo e(__($subsubcategory->name)); ?></a></li>
                                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                            </ul>
                                                                        </div>
                                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                </div>
                                                            </div>
                                                            
                                                        </div>
                                                    </div>

                                                    <div class="col-3">
                                                        <div class="sub-cat-brand">
                                                            <ul class="sub-brand-list">
                                                                <?php $__currentLoopData = $brands; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $brand_id): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                    <?php if(\App\Brand::find($brand_id) != null): ?>
                                                                        <li class="sub-brand-item">
                                                                            <a href="<?php echo e(route('products.brand', \App\Brand::find($brand_id)->slug)); ?>" ><img src="<?php echo e(asset('frontend/images/placeholder.jpg')); ?>" data-src="<?php echo e(asset(\App\Brand::find($brand_id)->logo)); ?>" class="img-fluid lazyload" alt="<?php echo e(asset(\App\Brand::find($brand_id)->name)); ?>"></a>
                                                                        </li>
                                                                    <?php endif; ?>
                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                    <ul class="navbar-nav">
                        <?php $__currentLoopData = \App\Search::orderBy('count', 'desc')->get()->take(5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $search): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo e(route('suggestion.search', $search->query)); ?>"><?php echo e($search->query); ?></a>
                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            </div>
        </nav>
    </div>
</div>
<?php /**PATH C:\Projects\Published\Release.Ark\Ark.FrontEnd.Published\resources\views/frontend/inc/nav.blade.php ENDPATH**/ ?>