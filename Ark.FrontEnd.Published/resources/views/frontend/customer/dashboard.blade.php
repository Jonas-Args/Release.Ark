@extends('frontend.layouts.app')

@section('content')

@php
	try{
	
   $_s = Session::get('apiSession');
  
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

   if ($_r->httpStatusCode == "200")
   {
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
   

   
   }
	catch (Exception $exception)
	{
		echo '<script>window.location = "' .  route('logout') . '"</script>';
	}
@endphp

<section class="gry-bg py-4 profile">
	<div class="container">
		<div class="row cols-xs-space cols-sm-space cols-md-space">
			<div class="col-lg-3 d-none d-lg-block" style="display: @if (isset($userLink)) block!important @else none!important @endif">
				@include('frontend.inc.customer_side_nav')
			</div>
			<div class=" @if (isset($userLink)) col-lg-9 @else col-lg-12 @endif">
				<!-- Page title -->
				<div class="page-title">
					<div class="row align-items-center">
						<div class="col-md-6 col-12">
							<h2 class="heading heading-6 text-capitalize strong-600 mb-0">
								{{__('Dashboard')}}
							</h2>
						</div>
						<div class="col-md-6 col-12">
							<div class="float-md-right">
								<ul class="breadcrumb">
									<li>
										<a href="{{ route('home') }}">{{__('Home')}}</a>
									</li>
									<li class="active">
										<a href="{{ route('dashboard') }}">{{__('Dashboard')}}</a>
									</li>
								</ul>
							</div>
						</div>
					</div>
				</div>

				<!-- dashboard content -->
				<div class="">
					
					@if($_r->httpStatusCode != "500")
					

					@if(count($_r->businessPackages) == 0)

					<div class="form-box bg-white mt-4" style="width:100%" id="account_activation">
						<div class="form-box-title px-3 py-2">
							{{__('Account Activation')}}
						</div>

						<div class="form-box-content p-3">

							<h5 style="margin-top:10px;">
								Loading..
							</h5>
						</div>
					</div>
					<script>window.location.replace('{{ route('affiliate') }}');
					</script>


					@elseif($_r->businessPackages[0]->packageStatus == "1")

					<div class="form-box bg-white mt-4" style="width:100%" id="">
						<div class="form-box-title px-3 py-2">
							{{__('Account Activation')}}
						</div>

						<div class="form-box-content p-3">

							<p>Please pay your enterprise package to activate your account</p>
							<hr />
							<h6><b>Package Details:</b></h6>
							<ul>
								<li>Package Name: <b>{{ $_r->businessPackages[0]->businessPackage->packageName }}</b></li>
								<li>Payment Method: <b>{{ $_r->businessPackages[0]->userDepositRequest->remarks }}</b></li>
								<li>Payment Amount: <b>{{ $_r->businessPackages[0]->userDepositRequest->amount }}</b></li>
								<li>Package Status: Pending Activation</li>
							</ul>
							
							<hr />
							@if($_r->businessPackages[0]->userDepositRequest->remarks == "DEPOSIT VIA BANK")

							<p><b>Bank Details:</b></>
							<p style="margin-bottom:0px">Bank Name: <b>EASTWEST</b></p>
							<p style="margin-bottom:0px">Currency: <b>PHP</b></p>
							<p style="margin-bottom:0px">Account Name: <b>ACCESSIBLE REVENUE KIOSK INC</b></p>
							<p style="margin-bottom:0px">Account Number: <b>200039751878</b></p>

							@elseif($_r->businessPackages[0]->userDepositRequest->remarks == "CASH VIA ADMIN")
							<h5>Cash Via Admin</h5>

										<p>Please proceed to Ark Philippines' offfice and pay the package amount on the counter.</p>
										<p><b>Office Location:</b></p>
										<p>{{ \App\GeneralSetting::first()->address }}</p>
							@endif
						</div>
					</div>

					@else
						@if($_r->businessPackages[0]->businessPackage->packageCode == "EPKG1TRL")
						<div class="mt-3">
						    <div class="alert alert-warning" role="alert">
						        You are using a trial account. Your free membership will expire on {{ date_format(date_create($_r->businessPackages[0]->expiryDate),"Y/m/d H:i:s") }}
						    </div>
						</div>
						@endif
					<div class="row">
						<div class="col-md-4">
							<div class="dashboard-widget text-center blue-widget mt-4 c-pointer" style="background-color:#12CBC4!important">
								<a href="javascript:;" class="d-block">
									<i class="fa fa-shopping-cart"></i>
									@if(Session::has('cart'))
									<span class="d-block title">{{ count(Session::get('cart'))}} Product(s)</span>
									@else
									<span class="d-block title">0 Product</span>
									@endif
									<span class="d-block sub-title">in your cart</span>
								</a>
							</div>
						</div>
						<div class="col-md-4">
							<div class="dashboard-widget text-center red-widget mt-4 c-pointer">
								<a href="javascript:;" class="d-block">
									<i class="fa fa-heart"></i>
									<span class="d-block title">{{ count(Auth::user()->wishlists)}} Product(s)</span>
									<span class="d-block sub-title">in your wishlist</span>
								</a>
							</div>
						</div>
						<div class="col-md-4">
							<div class="dashboard-widget text-center yellow-widget mt-4 c-pointer">
								<a href="javascript:;" class="d-block">
									<i class="fa fa-building"></i>
									@php
			 $orders = \App\Order::where('user_id', Auth::user()->id)->get();
			 $total = 0;
			 foreach ($orders as $key => $order) {
				 $total += count($order->orderDetails);
			 }
			 @endphp
									<span class="d-block title">{{ $total }} Product(s)</span>
									<span class="d-block sub-title">you ordered</span>
								</a>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-5">
							<div class="form-box bg-white mt-4">
								<div class="form-box-title px-3 py-2 clearfix ">
									{{__('Saved Shipping Info')}}
									<div class="float-right">
										<a href="{{ route('profile') }}" class="btn btn-link btn-sm">{{__('Edit')}}</a>
									</div>
								</div>
								<div class="form-box-content p-3">
									<table>
										<tr>
											<td>{{__('Address')}}:</td>
											<td class="p-2">{{ Auth::user()->address }}</td>
										</tr>
										<tr>
											<td>{{__('Region')}}:</td>
											<td class="p-2">
												@if (Auth::user()->country != null)
														{{ \App\Country::where('code', Auth::user()->country)->first()->name }}
													@endif
											</td>
										</tr>
										<tr>
											<td>{{__('City')}}:</td>
											<td class="p-2">{{ Auth::user()->city }}</td>
										</tr>
										<tr>
											<td>{{__('Postal Code')}}:</td>
											<td class="p-2">{{ Auth::user()->postal_code }}</td>
										</tr>
										<tr>
											<td>{{__('Phone')}}:</td>
											<td class="p-2">{{ Auth::user()->phone }}</td>
										</tr>
									</table>
								</div>
							</div>
						</div>
					</div>
					@endif

				@else
					<script>window.location.replace('{{ route('logout') }}');</script>
				@endif	
				</div>

			</div>
		</div>
	</div>
</section>

@endsection
