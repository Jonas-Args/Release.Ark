@php
	 $_s = Session::get('apiSession');


	 try
	 {
		 $url = 'http://localhost:55006/api/user/BusinessPackages';
		 $options = array(
			 'http' => array(
				 'method'  => 'GET',
				 'header'    => "Accept-language: en\r\n" .
					 "Cookie: .AspNetCore.Session=". $_s ."\r\n"
			 )
		 );
		 $context  = stream_context_create($options);
		 $result = file_get_contents($url, false, $context);
		 $_r = json_decode($result);

		 if(count($_r->businessPackages) != 0 && $_r->businessPackages[0]->packageStatus == "2"){
			 $url = 'http://localhost:55006/api/Affiliate/InvitationLink';
			 $data = array(
				 'DirectSponsorID' => Session::get('userName'),
				 'BinarySponsorID' => Session::get('userName'),
				 'BinaryPosition' => '1'
				 );
			 $options = array(
				 'http' => array(
					 'content' => json_encode($data),
					 'method'  => 'POST',
					 'header'    => "Accept-language: en\r\n" .  "Content-type: application/json\r\n" .
						 "Cookie: .AspNetCore.Session=". $_s ."\r\n"
				 )
			 );
			 $context  = stream_context_create($options);
			 $result = file_get_contents($url, false, $context);
			 $_res = json_decode($result);
			 $userLink = $_res->affiliateMapBO;
			 
		 }
	 }
	 catch (Exception $exception)
	 {
		 echo '<script>window.location = "' .  route('logout') . '"</script>';
	 }
@endphp



<div class="sidebar sidebar--style-3 no-border stickyfill p-0" style="display: @if (isset($userLink)) block @else none @endif">
    <div class="widget mb-0">
        <div class="widget-profile-box text-center p-3">
            @if (Auth::user()->avatar_original != null)
                <div class="image" style="background-image:url('{{ asset(Auth::user()->avatar_original) }}')"></div>
            @else
                <img src="{{ asset('frontend/images/user.png') }}" class="image rounded-circle">
            @endif
            <div class="name">{{ Auth::user()->name }}</div>

            @if (isset($userLink))
	        <p style="margin-bottom:0px">{{ $userLink->directSponsorID }}</p>
            <p style="color:#808080; font-size:9px">Your source code</p>
	        
	        @else
	        <p>Please activate your account first</p>
	        @endif

        </div>
        <div class="sidebar-widget-title py-3">
            <span>{{__('Menu')}}</span>
        </div>
        <div class="widget-profile-menu py-3">
            <ul class="categories categories--style-3">
                <li>
                    <a href="{{ route('dashboard') }}" class="{{ areActiveRoutesHome(['dashboard'])}}">
                        <i class="la la-dashboard"></i>
                        <span class="category-name">
                            {{__('Dashboard')}}
                        </span>
                    </a>
                </li>
                @php
                $delivery_viewed = App\Order::where('user_id', Auth::user()->id)->where('delivery_viewed', 0)->get()->count();
                $payment_status_viewed = App\Order::where('user_id', Auth::user()->id)->where('payment_status_viewed', 0)->get()->count();
                @endphp
                <li>
                    <a href="{{ route('purchase_history.index') }}" class="{{ areActiveRoutesHome(['purchase_history.index'])}}">
                        <i class="la la-file-text"></i>
                        <span class="category-name">
                            {{__('Purchase History')}} @if($delivery_viewed > 0 || $payment_status_viewed > 0)<span class="ml-2" style="color:green"><strong>({{ __('New Notifications') }})</strong></span>@endif
                        </span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('wishlists.index') }}" class="{{ areActiveRoutesHome(['wishlists.index'])}}">
                        <i class="la la-heart-o"></i>
                        <span class="category-name">
                            {{__('Wishlist')}}
                        </span>
                    </a>
                </li>
                @if (\App\BusinessSetting::where('type', 'conversation_system')->first()->value == 1)
                    @php
                        $conversation = \App\Conversation::where('sender_id', Auth::user()->id)->where('sender_viewed', 0)->get();
                    @endphp
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
                    <a href="{{ route('profile') }}" class="{{ areActiveRoutesHome(['profile'])}}">
                        <i class="la la-user"></i>
                        <span class="category-name">
                            {{__('Manage Profile')}}
                        </span>
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
                @if (\App\BusinessSetting::where('type', 'wallet_system')->first()->value == 1)
                    <li>
                        <a href="{{ route('wallet.index') }}" class="{{ areActiveRoutesHome(['wallet.index'])}}">
                            <i class="la la-wallet"></i>
                            <span class="category-name">
                                {{__('My Wallet')}}
                            </span>
                        </a>
                    </li>
                @endif
                @php
                    $support_ticket = DB::table('tickets')
                                ->where('client_viewed', 0)
                                ->where('user_id', Auth::user()->id)
                                ->count();
                @endphp
              <!--<li>
                    <a href="{{ route('support_ticket.index') }}" class="{{ areActiveRoutesHome(['support_ticket.index'])}}">
                        <i class="la la-support"></i>
                        <span class="category-name">
                            {{__('Support Ticket')}} @if($support_ticket > 0)<span class="ml-2" style="color:green"><strong>({{ $support_ticket }} {{ __('New') }})</strong></span></span>@endif
                        </span>
                    </a>
                </li>-->  
            </ul>
        </div>
        @if (\App\BusinessSetting::where('type', 'vendor_system_activation')->first()->value == 1)
            <div class="widget-seller-btn pt-4">
                <a href="{{ route('shops.create') }}" class="btn btn-anim-primary w-100">{{__('Be A Seller')}}</a>
            </div>
        @endif
    </div>
</div>
