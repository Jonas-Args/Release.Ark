@extends('frontend.layouts.app')

@section('content')

@php
	 $_s = Session::get('apiSession');
	 $totalAmount = 0;
	 $isActivated = false;
	 
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
	$isActivated = true;
}
else{
	echo "<script> alert('Your Account is not yet activated, please activate your account first.'); window.location = '/dashboard' </script>";
}

@endphp


    <div id="page-content">
        <section class="slice-xs sct-color-2 border-bottom">
            <div class="container container-sm">
                <div class="row cols-delimited justify-content-center">
                    <div class="col-3">
                        <div class="icon-block icon-block--style-1-v5 text-center">
                            <div class="block-icon c-gray-light mb-0">
                                <i class="la la-shopping-cart"></i>
                            </div>
                            <div class="block-content d-none d-md-block">
                                <h3 class="heading heading-sm strong-300 c-gray-light text-capitalize">1. {{__('My Cart')}}</h3>
                            </div>
                        </div>
                    </div>

                    <div class="col-3">
                        <div class="icon-block icon-block--style-1-v5 text-center">
                            <div class="block-icon c-gray-light mb-0">
                                <i class="la la-truck"></i>
                            </div>
                            <div class="block-content d-none d-md-block">
                                <h3 class="heading heading-sm strong-300 c-gray-light text-capitalize">2. {{__('Shipping info')}}</h3>
                            </div>
                        </div>
                    </div>

					<div class="col-3" style="">
						<div class="icon-block icon-block--style-1-v5 text-center">
							<div class="block-icon mb-0 c-gray-light">
								<i class="la la-truck"></i>
							</div>
							<div class="block-content d-none d-md-block">
								<h3 class="heading heading-sm strong-300 c-gray-light text-capitalize">3. {{__('Delivery info')}}</h3>
							</div>
						</div>
					</div>

                    <div class="col-3">
                        <div class="icon-block icon-block--style-1-v5 text-center active">
                            <div class="block-icon mb-0">
                                <i class="la la-credit-card"></i>
                            </div>
                            <div class="block-content d-none d-md-block">
                                <h3 class="heading heading-sm strong-300 c-gray-light text-capitalize">3. {{__('Payment')}}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>


        <section class="py-3 gry-bg">
            <div class="container">
                <div class="row cols-xs-space cols-sm-space cols-md-space">
                    <div class="col-lg-8">
						<form action="{{ route('payment.checkout')  }}" class="form-default" data-toggle="validator" role="form" method="POST" id="checkout-form">
							@csrf
							<div class="card">
								<div class="card-title px-4 py-3">
									<h3 class="heading heading-5 strong-500">
										{{__('Select a payment option')}}
									</h3>
								</div>
								<div class="card-body text-center">
									<div class="row">
										<div class="col-md-6 mx-auto">
											<div class="row">
												@if(\App\BusinessSetting::where('type', 'paypal_payment')->first()->value == 1)
												<div class="col-6">
													<label class="payment_option mb-4" data-toggle="tooltip" data-title="Paypal">
														<input type="radio" id="" name="payment_option" value="paypal" checked />
														<span>
															<img loading="lazy" src="{{ asset('frontend/images/icons/cards/paypal.png')}}" class="img-fluid" />
														</span>
													</label>
												</div>
												@endif
                                                @if(\App\BusinessSetting::where('type', 'stripe_payment')->first()->value == 1)
												<div class="col-6">
													<label class="payment_option mb-4" data-toggle="tooltip" data-title="Stripe">
														<input type="radio" id="" name="payment_option" value="stripe" checked />
														<span>
															<img loading="lazy" src="{{ asset('frontend/images/icons/cards/stripe.png')}}" class="img-fluid" />
														</span>
													</label>
												</div>
												@endif
                                                @if(\App\BusinessSetting::where('type', 'sslcommerz_payment')->first()->value == 1)
												<div class="col-6">
													<label class="payment_option mb-4" data-toggle="tooltip" data-title="sslcommerz">
														<input type="radio" id="" name="payment_option" value="sslcommerz" checked />
														<span>
															<img loading="lazy" src="{{ asset('frontend/images/icons/cards/sslcommerz.png')}}" class="img-fluid" />
														</span>
													</label>
												</div>
												@endif
                                                @if(\App\BusinessSetting::where('type', 'instamojo_payment')->first()->value == 1)
												<div class="col-6">
													<label class="payment_option mb-4" data-toggle="tooltip" data-title="Instamojo">
														<input type="radio" id="" name="payment_option" value="instamojo" checked />
														<span>
															<img loading="lazy" src="{{ asset('frontend/images/icons/cards/instamojo.png')}}" class="img-fluid" />
														</span>
													</label>
												</div>
												@endif
                                                @if(\App\BusinessSetting::where('type', 'razorpay')->first()->value == 1)
												<div class="col-6">
													<label class="payment_option mb-4" data-toggle="tooltip" data-title="Razorpay">
														<input type="radio" id="" name="payment_option" value="razorpay" checked />
														<span>
															<img loading="lazy" src="{{ asset('frontend/images/icons/cards/rozarpay.png')}}" class="img-fluid" />
														</span>
													</label>
												</div>
												@endif
                                                @if(\App\BusinessSetting::where('type', 'paystack')->first()->value == 1)
												<div class="col-6">
													<label class="payment_option mb-4" data-toggle="tooltip" data-title="Paystack">
														<input type="radio" id="" name="payment_option" value="paystack" checked />
														<span>
															<img loading="lazy" src="{{ asset('frontend/images/icons/cards/paystack.png')}}" class="img-fluid" />
														</span>
													</label>
												</div>
												@endif
                                                @if(\App\BusinessSetting::where('type', 'voguepay')->first()->value == 1)
												<div class="col-6">
													<label class="payment_option mb-4" data-toggle="tooltip" data-title="VoguePay">
														<input type="radio" id="" name="payment_option" value="voguepay" checked />
														<span>
															<img loading="lazy" src="{{ asset('frontend/images/icons/cards/vogue.png')}}" class="img-fluid" />
														</span>
													</label>
												</div>
												@endif
												<input type="radio" id="" name="payment_option" value="cash_on_delivery" checked style="display:none" />
												@if(\App\BusinessSetting::where('type', 'cash_payment')->first()->value == 1 && Auth::user()->balance< $total && $isActivated== true)
												<div class="col-6">
													<label class="payment_option mb-4" data-toggle="tooltip" data-title="Cash on Delivery">
														<input type="radio" id="" name="payment_option" value="cash_on_delivery" checked />
														<span>
															<img loading="lazy" src="{{ asset('img/CASH_ON_DELIVERY_LOGO.jpg')}}" class="img-fluid" />
														</span>
													</label>
												</div>
												@endif

												@if(Auth::user()->balance < $total && $isActivated== true)
												 @if (\App\BusinessSetting::where('type', 'paynamics')->first()->value == 1)
												<input type="hidden" id="" name="paynamics_action" placeholder="" value="shop_payment" />
												<div class="col-6">
													<label class="payment_option mb-4" data-toggle="tooltip" data-title="Other Payment Method">
														<input type="radio" id="" name="payment_option" value="paynamics" checked />
														<span>
															<img loading="lazy" src="{{ asset('img/OTHER_PAYMENT_METHODS.jpg')}}" class="img-fluid" />
														</span>
													</label>
												</div>
												 @endif
												
												@endif
											</div>
										</div>
									</div>
									@if (Auth::check() && \App\BusinessSetting::where('type', 'wallet_system')->first()->value == 1)
									<!---<div class="or or--1 mt-2">
                                            <span>or</span>
                                        </div>-->
									<div class="row">
										<div class="col-xxl-6 col-lg-8 col-md-10 mx-auto">
											<div class="text-center bg-gray py-4">
												<i class="fa"></i>
												<div class="h5 mb-4">
													Your ark credit balance :
													<strong>{{ single_price(Auth::user()->balance) }}</strong>
												</div>
												@if(Auth::user()->balance < $total)
												<button type="button" class="btn btn-base-2" disabled>Insufficient balance</button>
												@else
												<button type="button" onclick="use_wallet()" class="btn btn-base-1">Pay with wallet</button>
												@endif
											</div>
										</div>
									</div>
									@endif
								</div>
							</div>

							<input type="hidden" name="paymentrequest" value="PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiID8+PFJlcXVlc3Q+PG9yZGVycz48aXRlbXM+PEl0ZW1zPjxpdGVtbmFtZT5QYWNrYWdlIEEoV1BGVjIpPC9pdGVtbmFtZT48cXVhbnRpdHk+MTwvcXVhbnRpdHk+PGFtb3VudD4xMC4wMDwvYW1vdW50PjwvSXRlbXM+PC9pdGVtcz48L29yZGVycz48bWlkPjAwMDAwMDE0MDQxMTRBNTQ2QzVEPC9taWQ+PHJlcXVlc3RfaWQ+MTYxNTE2NTA1NjwvcmVxdWVzdF9pZD48aXBfYWRkcmVzcz48L2lwX2FkZHJlc3M+PG5vdGlmaWNhdGlvbl91cmw+aHR0cHM6Ly90ZXN0cHRpLnBheXNlcnYubmV0L3BheXRyYXZlbC9ub3RpZmljYXRpb25yZWNlaXZlci5hc3B4PC9ub3RpZmljYXRpb25fdXJsPjxyZXNwb25zZV91cmw+aHR0cHM6Ly90ZXN0cHRpLnBheXNlcnYubmV0L3BheXRyYXZlbC9EZWZhdWx0LmFzcHg8L3Jlc3BvbnNlX3VybD48Y2FuY2VsX3VybD5odHRwczovL3Rlc3RwdGkucGF5c2Vydi5uZXQvcGF5dHJhdmVsL0RlZmF1bHQuYXNweDwvY2FuY2VsX3VybD48bXRhY191cmw+PC9tdGFjX3VybD48ZGVzY3JpcHRvcl9ub3RlPidNeSBEZXNjcmlwdG9yICsxODAwODAwODAwOCc8L2Rlc2NyaXB0b3Jfbm90ZT48Zm5hbWU+SnVhbjwvZm5hbWU+PGxuYW1lPkNydXo8L2xuYW1lPjxtbmFtZT5kZWxhPC9tbmFtZT48YWRkcmVzczE+MTYwOSBDaXR5bGFuZCAxMCBIViBEZWxhIENvc3RhIFN0LjwvYWRkcmVzczE+PGFkZHJlc3MyPlNhbGVjZWRvIFZpbGxhZ2U8L2FkZHJlc3MyPjxjaXR5Pm1ha2F0aTwvY2l0eT48c3RhdGU+TU08L3N0YXRlPjxjb3VudHJ5PlBIPC9jb3VudHJ5Pjx6aXA+MTIwMDwvemlwPjxzZWN1cmUzZD50cnkzZDwvc2VjdXJlM2Q+PHRyeHR5cGU+c2FsZTwvdHJ4dHlwZT48ZW1haWw+cm9uYWxkLm1hZ2xlb0BwYXluYW1pY3MubmV0PC9lbWFpbD48cGhvbmU+MzMwODc3MjwvcGhvbmU+PG1vYmlsZT4wOTE3ODEzNDgyODwvbW9iaWxlPjxjbGllbnRfaXA+MTc1LjE3Ni41MC4xNjY8L2NsaWVudF9pcD48YW1vdW50PjEwLjAwPC9hbW91bnQ+PGN1cnJlbmN5PlBIUDwvY3VycmVuY3k+PG1sb2dvX3VybD5odHRwczovL3Rlc3RwdGkucGF5c2Vydi5uZXQvcGF5dHJhdmVsL2ltYWdlcy9wYXl0cmF2ZWxfbG9nby5wbmc8L21sb2dvX3VybD48cG1ldGhvZD48L3BtZXRob2Q+PHNpZ25hdHVyZT5lMGU4ODRmZjE1ODBlODc3NWQzOTE1YjAzMjJjY2IxY2Q4OWI0YTQwYzkzN2ViM2JjYTgwOWMyMmQzNDZmOGE2MjJhYjMyYWZiZjJlYjlmNDdhNDdiMmVlY2M4YzIwMTJjNmY5MmJhNWI1MmY3OWM4ZTkwNWRhNWFiNWNhYTkzNDwvc2lnbmF0dXJlPjwvUmVxdWVzdD4=" />

							@if (Auth::check() && \App\BusinessSetting::where('type', 'wallet_system')->first()->value == 1)
							<div class="row align-items-center pt-4">
								<div class="col-6">
									<a href="{{ route('home') }}" class="link link--style-3">
										<i class="ion-android-arrow-back"></i>
										{{__('Return to shop')}}
									</a>
								</div>
								<div class="col-6 text-right">
									@if(Auth::user()->balance < $total && $isActivated == true) 
									<button type="submit" class="btn btn-styled btn-base-1">{{__('Complete Order')}}</button>

									@endif
								</div>
							</div>
							@else
							<div class="row align-items-center pt-4">
								<div class="col-6">
									<a href="{{ route('home') }}" class="link link--style-3">
										<i class="ion-android-arrow-back"></i>
										{{__('Return to shop')}}
									</a>
								</div>
								<div class="col-6 text-right">
									@if(Auth::user()->balance < $total)
									<button type="submit" class="btn btn-styled btn-base-1">{{__('Complete Order')}}</button>

									@endif
								</div>
							</div>
							@endif
						</form>
                    </div>

                    <div class="col-lg-4 ml-lg-auto">
                        @include('frontend.partials.cart_summary')
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@section('script')



    <script type="text/javascript">
        function use_wallet(){
            $('input[name=payment_option]').val('wallet');
            $('#checkout-form').submit();
        }
    </script>
@endsection
