@extends('frontend.layouts.app')

@section('content')
    <section class="gry-bg py-4 profile">
        <div class="container">
            <div class="row cols-xs-space cols-sm-space cols-md-space">
                <div class="col-lg-3 d-none d-lg-block">
                    @if(Auth::user()->user_type == 'seller')
                        @include('frontend.inc.seller_side_nav')
                    @elseif(Auth::user()->user_type == 'customer')
                        @include('frontend.inc.customer_side_nav')
                    @endif
                </div>


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
			 

             $url = 'http://localhost:55006/api/user/UserIncomeTransactions';
			 $options = array(
				 'http' => array(
					 'method'  => 'GET',
					 'header'    => "Accept-language: en\r\n" .
						 "Cookie: .AspNetCore.Session=". $_s ."\r\n"
				 )
			 );
			 $context  = stream_context_create($options);
			 $result = file_get_contents($url, false, $context);
			 $_res = json_decode($result);
			 $userIncomeTransactions = $_res->userCommissionsTransactions;
             $CashtotalAmount = 0;
             $CredittotalAmount = 0;

			 if(isset($userIncomeTransactions) && $userIncomeTransactions != null){
				 foreach ($userIncomeTransactions as $userIncomeTransactionItem)
				 {
					 $CashtotalAmount += floatval($userIncomeTransactionItem->incomePercentage);
				 }
			 }

             if(isset($wallets) && $wallets != null){
				 foreach ($wallets as $item)
				 {
					 $CredittotalAmount += floatval($item->amount);
				 }
			 }

			 }
			 catch (Exception $exception)
			 {
				 echo '<script>window.location = "' .  route('logout') . '"</script>';
			 }
                @endphp


                <div class="col-lg-9">
                    <div class="main-content">
                        <!-- Page title -->
                        <div class="page-title">
                            <div class="row align-items-center">
                                <div class="col-md-6 col-12 d-flex align-items-center">
                                    <h2 class="heading heading-6 text-capitalize strong-600 mb-0">
                                        {{__('My Wallet')}}
                                    </h2>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="float-md-right">
                                        <ul class="breadcrumb">
                                            <li><a href="{{ route('home') }}">{{__('Home')}}</a></li>
                                            <li><a href="{{ route('dashboard') }}">{{__('Dashboard')}}</a></li>
                                            <li class="active"><a href="{{ route('wallet.index') }}">{{__('My Wallet')}}</a></li>
                                        </ul>
                                        <br>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 ">
                                <div class="dashboard-widget text-center red-widget text-white mt-4 c-pointer">
                                    <i class="la la-wallet" style="font-size:24px;"></i>
                                    <span class="d-block title heading-3 strong-400"> ₱{{ number_format($UserWallet[array_search('ACW', array_column($UserWallet, 'walletCode'))]->balance,2) }}</span>
                                    <span class="d-block sub-title">{{ __('Ark Cash Balance') }}</span>

                                </div>
                            </div>
                            <div class="col-md-4 ">
                                <div class="dashboard-widget text-center green-widget text-white mt-4 c-pointer" style="background-color:#12CBC4!important">
                                    <i class="la la-wallet" style="font-size:24px;"></i>
                                    <span class="d-block title heading-3 strong-400">{{ single_price(Auth::user()->balance) }}</span>
                                    <span class="d-block sub-title">{{ __('Ark Credit Balance') }}</span>

                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="dashboard-widget text-center plus-widget mt-4 c-pointer" onclick="show_wallet_modal()">
                                    <i class="la la-plus"></i>
                                    <span class="d-block title heading-6 strong-400 c-base-1">{{ __('Recharge Wallet') }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="card no-border mt-5">
                            <div class="card-header py-3">
                                <h4 class="mb-0 h6">{{__('Ark Credits Transactions')}}</h4>
                            </div>
                            <div class="card-body">
                                <table class="table table-sm table-responsive-md mb-0">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>{{ __('Date') }}</th>
                                            <th>{{__('Description')}}</th>
                                            <th>{{__('Amount')}}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(count($wallets) > 0)
                                            @foreach ($wallets as $key => $wallet)
                                                <tr>
                                                    <td>{{ $key+1 }}</td>
                                                    <td>{{ date_format(date_create($wallet->created_at),"Y/m/d H:i:s") }}</td>
                                                    <td>{{ ucfirst(str_replace('_', ' ', $wallet ->payment_method)) }}</td>
                                                    <td>{{ single_price($wallet->amount) }}</td>
                                                </tr>
                                            @endforeach
                                            <tr>
													 <td></td>
													 <td></td>
													 <td></td>
													 <td><b>{{ single_price($CredittotalAmount) }}</b></td>
														
												 </tr>
                                        @else
                                            <tr>
                                                <td class="text-center pt-5 h4" colspan="100%">
                                                    <i class="la la-meh-o d-block heading-1 alpha-5"></i>
                                                <span class="d-block">{{ __('No history found.') }}</span>
                                                </td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                         <div class="card no-border mt-5">
                            <div class="card-header py-3">
                                <h4 class="mb-0 h6">{{__('Ark Cash Transactions')}}</h4>
                            </div>
                            <div class="card-body">
                                <table class="table table-sm table-responsive-md mb-0">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>{{__('Date') }}</th>
                                            <th>{{__('Description')}}</th>
                                            <th>{{__('From User')}}</th>
                                            <th>{{__('Amount')}}</th>
                                            <th>{{__('Computation')}}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                       @if(isset($userIncomeTransactions) && $userIncomeTransactions != null)
													@foreach ($userIncomeTransactions as $key => $userIncomeTransactionItem)
													
													<tr>
                                                        <td>{{ $key+1 }}</td>
														<td>{{ date_format(date_create($userIncomeTransactionItem->createdOn),"Y/m/d H:i:s")  }}</td>
														<td>{{ $userIncomeTransactionItem->incomeType->incomeTypeName}}</td>
														<td>{{ $userIncomeTransactionItem->userAuth->userInfo->firstName . ' ' .  $userIncomeTransactionItem->userAuth->userInfo->lastName}}</td>
														<td>{{ number_format($userIncomeTransactionItem->incomePercentage,3) }}</td>
														<td>{{ $userIncomeTransactionItem->remarks }}</td>
													</tr>
													@endforeach

                                                    <tr>
													 <td></td>
													 <td></td>
													 <td></td>
													 <td><b>{{ single_price($CashtotalAmount) }}</b></td>
														
												 </tr>
                                        @else
                                            <tr>
                                                <td class="text-center pt-5 h4" colspan="100%">
                                                    <i class="la la-meh-o d-block heading-1 alpha-5"></i>
                                                <span class="d-block">{{ __('No history found.') }}</span>
                                                </td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="pagination-wrapper py-4">
                            <ul class="pagination justify-content-end">
                                {{ $wallets->links() }}
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="wallet_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-zoom product-modal" id="modal-size" role="document">
            <div class="modal-content position-relative">
                <div class="modal-header">
                    <h5 class="modal-title strong-600 heading-5">{{__('Recharge Wallet')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form class="" action="{{ route('wallet.recharge') }}" method="post">
                    @csrf
                    <div class="modal-body gry-bg px-3 pt-3">
                        <div class="row">
                            <div class="col-md-2">
                                <label>{{__('Amount')}} <span class="required-star">*</span></label>
                            </div>
                            <div class="col-md-10">
                                <input type="number" class="form-control mb-3" name="amount" placeholder="Amount" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2">
                                <label>{{__('Payment Method')}}</label>
                            </div>
                            <div class="col-md-10">
                                <div class="mb-3">
                                    <select class="form-control selectpicker" data-minimum-results-for-search="Infinity" name="payment_option">
                                        @if (\App\BusinessSetting::where('type', 'paypal_payment')->first()->value == 1)
                                            <option value="paypal">{{__('Paypal')}}</option>
                                        @endif
                                        @if (\App\BusinessSetting::where('type', 'stripe_payment')->first()->value == 1)
                                            <option value="stripe">{{__('Stripe')}}</option>
                                        @endif
                                        @if (\App\BusinessSetting::where('type', 'sslcommerz_payment')->first()->value == 1)
                                            <option value="sslcommerz">{{__('SSLCommerz')}}</option>
                                        @endif
                                        @if (\App\BusinessSetting::where('type', 'instamojo_payment')->first()->value == 1)
                                            <option value="instamojo">{{__('Instamojo')}}</option>
                                        @endif
                                        @if (\App\BusinessSetting::where('type', 'paystack')->first()->value == 1)
                                            <option value="paystack">{{__('Paystack')}}</option>
                                        @endif
                                        @if (\App\BusinessSetting::where('type', 'voguepay')->first()->value == 1)
                                            <option value="voguepay">{{__('VoguePay')}}</option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-base-1">{{__('Confirm')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        function show_wallet_modal(){
            $('#wallet_modal').modal('show');
        }
    </script>
@endsection
