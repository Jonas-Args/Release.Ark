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
					 $CashtotalAmount += floatval(bcdiv($userIncomeTransactionItem->incomePercentage,1, 5));
				 }
			 }

             if(isset($wallets) && $wallets != null){
				 foreach ($wallets as $item)
				 {
                     $CredittotalAmount += floatval(bcdiv($item->amount,1, 5));
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
                            <div class="col-md-4">
                                <div class="dashboard-widget text-center red-widget text-white mt-4 c-pointer">
                                    <i class="la la-wallet" style="font-size:24px;"></i>
                                    <span class="d-block title heading-3 strong-400"> ₱{{ bcdiv($UserWallet[array_search('ACW', array_column($UserWallet, 'walletCode'))]->balance,1, 3) }}</span>
                                    <span class="d-block sub-title">{{ __('Ark Cash Balance') }}</span>

                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="dashboard-widget text-center green-widget text-white mt-4 c-pointer" style="background-color:#12CBC4!important">
                                    <i class="la la-wallet" style="font-size:24px;"></i>
                                    <span class="d-block title heading-3 strong-400">₱{{ bcdiv(Auth::user()->balance, 1, 3) }}</span>
                                    <span class="d-block sub-title">{{ __('Ark Credit Balance') }}</span>

                                </div>
                            </div>
                            
                        </div>
                        <hr />
                        <div>
                              <h2 class="heading heading-6 text-capitalize strong-600 mb-0">
                                        {{__('Wallet Options')}}
                              </h2>
                             <button type="button" class="btn btn-styled btn-base-3 mt-3" onclick="show_wallet_modal()">{{__('TopUp Wallet')}}</button>
                             <button type="button" class="btn btn-styled btn-base-3 mt-3 ml-2" onclick="show_wallet_withdarw_modal()">{{__('Withdraw Wallet')}}</button>
                             <button type="button" class="btn btn-styled btn-base-3 mt-3 ml-2" onclick="show_wallet_convert_modal()">{{__('Convert Ark Cash')}}</button>
                        </div>
                        <hr />
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
                                            <th>{{__('Source')}}</th>
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
                                                    <td>{{ $wallet->source_details }}</td>
                                                    <td>{{ bcdiv($wallet->amount, 1,5) }}</td>
                                                </tr>
                                            @endforeach
                                            <tr>
													 <td></td>
													 <td></td>
													 <td></td>
													 <td></td>
													 <td><b>₱ {{ bcdiv($CredittotalAmount, 1, 5) }}</b></td>
														
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
														<td>{{ bcdiv($userIncomeTransactionItem->incomePercentage, 1, 5) }}</td>
													</tr>
													@endforeach

                                                    <tr>
													 <td></td>
													 <td></td>
                                                     <td></td>
                                                     <td></td>
													 <td><b>₱ {{ bcdiv($CashtotalAmount, 1, 5) }}</b></td>
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
					<input type="hidden" class="form-control" id="" name="paynamics_action" placeholder="" value="wallet_topup" />
                    <div class="modal-body gry-bg px-3 pt-3">
                        <div class="row">
                            <div class="col-md-2">
                                <label>{{__('Amount')}} <span class="required-star">*</span></label>
                            </div>
                            <div class="col-md-10">
                                <input type="number" class="form-control mb-3" name="amount" step="0.000000001" placeholder="Amount" required>
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
                                        @if (\App\BusinessSetting::where('type', 'paynamics')->first()->value == 1)
                                            <option value="paynamics">{{__('Other Payment Methods')}}</option>
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

     <div class="modal fade" id="wallet_withdraw_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-zoom product-modal" id="modal-size" role="document">
            <div class="modal-content position-relative">
                <div class="modal-header">
                    <h5 class="modal-title strong-600 heading-5">{{__('Withdraw Ark Credits')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form class="" action="{{ route('client.wallet.withdraw.exec') }}" method="post">
                	@csrf
	            	<input type="hidden" class="form-control" id="" name="ID" placeholder="" value="{{ Auth::user()->id }}" />
	            	<input type="hidden" class="form-control" id="" name="target_wallet" placeholder="" value="Ark Cash" />
                    
                    <div class="modal-body gry-bg px-3 pt-3">
                        <div class="row">
                            <div class="col-md-2">
                                <label>{{__('Amount')}} <span class="required-star">*</span></label>
                            </div>
                            <div class="col-md-10">
                                <input type="number" class="form-control mb-3" name="transaction_amount" placeholder="Amount" step="0.000000001" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2">
                                <label>{{__('Withrawal Method')}}</label>
                            </div>
                            <div class="col-md-10">
                                <div class="mb-3">
                                    <select class="form-control selectpicker" name="target_outlet" data-minimum-results-for-search="Infinity">
                                        <option value="Bank Wire Transfer">Bank Wire Transfer</option>
                                        <option value="Cheque">Cheque</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="exampleFormControlTextarea1">Bank / Recepient Details</label>
                            <textarea class="form-control" id="exampleFormControlTextarea1" name="outlet_details"
                                rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-base-1">{{__('Confirm')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="wallet_convert_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-zoom product-modal" id="modal-size" role="document">
            <div class="modal-content position-relative">
                <div class="modal-header">
                    <h5 class="modal-title strong-600 heading-5">{{__('Convert Ark Cash')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form class="" action="{{ route('client.wallet.convert.exec') }}" method="post">
                    @csrf
					<input type="hidden" class="form-control" id="" name="ID" placeholder="" value="{{ Auth::user()->id }}" />
	            	<input type="hidden" class="form-control" id="" name="target_wallet" placeholder="" value="Ark Credits" />
                    
                    <div class="modal-body gry-bg px-3 pt-3">
                        <div class="row">
                            <div class="col-md-2">
                                <label>{{__('Amount')}} <span class="required-star">*</span></label>
                            </div>
                            <div class="col-md-10">
                                <input type="number" class="form-control mb-3" name="transaction_amount" step="0.000000001" placeholder="Amount" required>
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
        function show_wallet_withdarw_modal(){
            $('#wallet_withdraw_modal').modal('show');
        }
        function show_wallet_convert_modal(){
            $('#wallet_convert_modal').modal('show');
        }
    </script>
@endsection
