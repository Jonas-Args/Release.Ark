@extends('frontend.layouts.app')

@section('content')

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

	 if(count($_r->businessPackages) == 0){
		 $url = 'http://localhost:55006/api/BusinessPackage';
		 $options = array(
			 'http' => array(
				 'method'  => 'GET',
				 'header'    => "Accept-language: en\r\n" .
					 "Cookie: .AspNetCore.Session=". $_s ."\r\n"
			 )
		 );
		 $context  = stream_context_create($options);
		 $result = file_get_contents($url, false, $context);
		 $businessPackages = json_decode($result);
		 $businessPackages = $businessPackages->businessPackages;

	 }
	 else{
		 $url = 'http://localhost:55006/api/user/UnilevelMap';
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

		 $unilevelMap_raw = json_encode($_res->userUnilevelMap);
		 $unilevelMap = isset($_res->userUnilevelMap->nodes) == true ? $_res->userUnilevelMap->nodes : [];


		 
		 //var_dump($unilevelMap);

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
		 $userIncomeTransactions = $_res->userIncomeTransactions;
		 //var_dump($userIncomeTransactions);

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
		 //var_dump($userLink);
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
			<div class="col-lg-3 d-none d-lg-block" style="display: @if (isset($userLink)) block @else none @endif">
				@if(Auth::user()->user_type == 'seller')
						@include('frontend.inc.seller_side_nav')
					@elseif(Auth::user()->user_type == 'customer')
						@include('frontend.inc.customer_side_nav')
					@endif
			</div>

			<div class=" @if (isset($userLink)) col-lg-9 @else col-lg-12 @endif">
				<div class="main-content">
					<!-- Page title -->
					<div class="page-title">
						<div class="row align-items-center">
							<div class="col-md-6 col-12">
								<h2 class="heading heading-6 text-capitalize strong-600 mb-0">
									{{__('Your Enterprise')}}
								</h2>
							</div>
							<div class="col-md-6 col-12">
								<div class="float-md-right">
									<ul class="breadcrumb">
										<li>
											<a href="{{ route('home') }}">{{__('Home')}}</a>
										</li>
										<li>
											<a href="{{ route('dashboard') }}">{{__('Dashboard')}}</a>
										</li>
										<li class="active">
											<a href="{{ route('profile') }}">{{__('My Enterprise')}}</a>
										</li>
									</ul>
								</div>
							</div>
						</div>
					</div>
					
						@csrf

						<div class="">

							

							@if($_r->httpStatusCode == "500")
							<script>window.location.replace('{{ route('logout') }}');</script>

							@else


							@if(count($_r->businessPackages) == 0)

								<div class="form-box bg-white mt-4" id="packageBuyForm" style="display:none">
									<div class="form-box-title px-3 py-2">
										{{__('Payment Method')}}
									</div>
									<div  class="form-box-content p-3">
										<form id="packageForm" onsubmit="return SelectPaymentMethod();" >
										
										<label><b>Selected Package</b></label>
										<select class="form-control col-md-4" id="packageBuy_option" name="BusinessPackageID" oninput="UpdateSelectedAmount()">
											@foreach ($businessPackages as $key => $businessPackage)
											<option value="{{ $businessPackage->id }}">{{ $businessPackage->packageName }} (PHP {{ number_format($businessPackage->valueTo) }})</option>
											@endforeach
										</select>

										<select class="form-control col-md-4" id="packageAmount_option" name="AmountPaid" style="display:none">
											@foreach ($businessPackages as $key => $businessPackage)
											<option value="{{ $businessPackage->valueFrom }}">{{ number_format($businessPackage->valueTo) }})</option>
											@endforeach
										</select>
										
										<br />

										<input type="hidden" name="Id" value="{{ Session::get('userAuthId') }}" />
										<input type="hidden" name="FromCurrencyIso3" value="PHP" />
										<input type="number" style="display:none" name="DepositStatus" value="0" />

										<label><b>Select Payment Method</b></label>
										<select class="form-control col-md-4" name="Remarks" id="FromWalletCode">
											<option value="CASH VIA ADMIN">CASH VIA ADMIN</option>
											<option value="DEPOSIT VIA BANK">DEPOSIT VIA BANK</option>
										<!--<option value="ACW">ARK CASH WALLET | PHP {{ $UserWallet[9]->balance }}</option>-->	
											<option value="G Cash" disabled>G-Cash (Coming Soon)</option>
											<option value="Paymaya" disabled>Paymaya (Coming Soon)</option>
											<option value="7 Eleven" disabled>7 - Eleven (Coming Soon)</option>
											<option value="Cebuana" disabled>Cebuana Lhuillier (Coming Soon)</option>
											<option value="Palawan" disabled>Palawan Pawnshop (Coming Soon)</option>
										</select>
										<hr />
										<button type="submit" class="btn btn-styled btn-base-1 col-md-2" style="">Next</button>
									</form>
									</div>
								</div>
							
								<div class="form-box bg-white mt-4" style="width:100%" id="packageSelectForm">
									<div class="form-box-title px-3 py-2">
										<b>{{__('Enterprise Packages')}}</b>
									</div>

									<div class="form-box-content p-3">


										<div class="row" style="padding:10px 10px;">
											
											@foreach ($businessPackages as $key => $businessPackage)

											<div class="col-md-4">

												<img class="dashboard-widget" src="{{asset('uploads/packages/' . $businessPackage->imageFile) }}" onclick="SelectPackage('{{ $businessPackage->id }}');" alt="Alternate Text" style="width:100%" />

												
											</div>

											@endforeach
										</div>

										
										

									</div>
									
								</div>

								<div class="form-box bg-white mt-4" id="packageBuy_method_cashAdmin" style="display:none">
									<div class="form-box-title px-3 py-2">
										{{__('Payment')}}
									</div>
									<div class="form-box-content p-3">
										<h5>Cash Via Admin</h5>

										<p>Please proceed to Ark Philippines' offfice and pay the package amount on the counter.</p>
										<hr />
										<p><b>Office Location:</b></p>
										<p>{{ \App\GeneralSetting::first()->address }}</p>

										<button type="button" onclick="SendDepositRequest();" class="btn btn-styled btn-base-1 col-md-2">Confirm</button>
									</div>

									
								</div>

								<div class="form-box bg-white mt-4" id="packageBuy_method_depositSlip" style="display:none">
									<div class="form-box-title px-3 py-2">
										{{__('Payment')}}
									</div>
									<div class="form-box-content p-3">
										<h5>Deposit Via Bank</h5>

										<p>How to procces payment:</p>
										<ul>
										    <li>You will recieve email for uploading the photo of your deposit slip</li>
											<li>After upload, our staff will review your request</li>
											<li>Upon successful payment confirmation, you will recieve an email confirming your registration</li>
										</ul>

										<hr />
										<p><b>Bank Details:</b></>
										<p style="margin-bottom:0px">Bank Name: <b>EASTWEST</b></p>
										<p style="margin-bottom:0px">Currency: <b>PHP</b></p>
										<p style="margin-bottom:0px">Account Name: <b>ACCESSIBLE REVENUE KIOSK INC</b></p>
										<p style="margin-bottom:0px">Account Number: <b>200039751878</b></p>

										<button type="button" onclick="SendDepositRequest();" class="btn btn-styled btn-base-1 col-md-2" >Confirm</button>
									</div>

									
								</div>

							@else

							<div class="form-box bg-white mt-4">
								<div class="form-box-title px-3 py-2">
									{{__('Summary')}}
								</div>
								<div class="form-box-content p-3"></div>
							</div>
							<div class="form-box bg-white mt-4">
								<div class="form-box-title px-3 py-2">
									{{__('Source Code Link')}}
								</div>
								<div class="form-box-content p-3">
									
									@if (isset($userLink))
									<p>This is your enterprise source code you can share</p>
									<input type="text" id="userLink" class="form-control" name="name" value="{{ 'http://'.$_SERVER['HTTP_HOST'].'/users/registration?ulink='.$userLink->directSponsorID }}" />
									@else
									<p>Please activate your account first</p>
									@endif
									
									<hr />
									<button type="button" onclick="CopyLink()" class="btn btn-styled btn-base-1 col-md-2" style="">Copy Link</button>
								</div>

								
							</div>
							<div class="form-box bg-white mt-4">
								<div class="form-box-title px-3 py-2">
									{{__('Enterprisers Under You')}}
								</div>
								<div class="form-box-content p-3">
									 <div id="treeview"></div>
								</div>
							</div>
							<div class="form-box bg-white mt-4">
								<div class="form-box-title px-3 py-2">
									{{__('First Level Enterprisers')}}
								</div>
								<div class="form-box-content p-3">
									<div class="card no-border mt-4" style="margin-top: 6px!important;">
										<div>
											<table class="table table-sm table-hover table-responsive-md">
												<thead>
													<tr>
														<th>{{__('Date')}}</th>
														<th>{{__('Email')}}</th>
														<th>{{__('Account Package')}}</th>
														<th>{{__('Status')}}</th>
														<th>{{__('Total Commissions')}}</th>
														<th>{{__('Options')}}</th>
													</tr>
												</thead>
												<tbody>
													@if(isset($unilevelMap) && $unilevelMap != null)
													@foreach ($unilevelMap as $key => $unilevelMapItem)
													<tr>
														<td>{{ date_format(date_create($unilevelMapItem->userBusinessPackage->createdOn),"Y/m/d H:i:s")  }}</td>
														<td>{{ $unilevelMapItem->userAuth->userName }}</td>
														<td>{{ $unilevelMapItem->userBusinessPackage->businessPackage->packageName }}</td>
														<td>{{ $unilevelMapItem->userBusinessPackage->packageStatus == 2 ? 'Activated' : 'Pending Activation'}}</td>
														<td>{{ $unilevelMapItem->totalCommission}}</td>
														<td></td>
													</tr>
													@endforeach
												@endif


												</tbody>
											</table>
										</div>
									</div>

									<!--<div class="text-right mt-4">
										<button type="submit" class="btn btn-styled btn-base-1  col-sm-12  col-lg-3">{{__('View Genealogy')}}</button>
									</div>-->
								</div>
							</div>
							<div class="form-box bg-white mt-4">
								<div class="form-box-title px-3 py-2">
									{{__('Rewards Transactions')}}
								</div>
								<div class="form-box-content p-3">
									<div class="card no-border mt-4" style="margin-top: 6px!important;">
										<div>
											<table class="table table-sm table-hover table-responsive-md">
												<thead>
													<tr>
														<th>{{__('Date')}}</th>
														<th>{{__('User')}}</th>
														<th>{{__('Amount')}}</th>
														<th>{{__('Reward Name')}}</th>
														<th>{{__('Options')}}</th>
													</tr>
												</thead>
												<tbody>
												
												@if(isset($userIncomeTransactions) && $userIncomeTransactions != null)
													@foreach ($userIncomeTransactions as $key => $userIncomeTransactionItem)
													<tr>
														<td>{{ date_format(date_create($userIncomeTransactionItem->createdOn),"Y/m/d H:i:s")  }}</td>
														<td>{{ $userIncomeTransactionItem->userAuth->userName }}</td>
														<td>{{ $userIncomeTransactionItem->incomePercentage }}</td>
														<td>{{ $userIncomeTransactionItem->incomeType->incomeTypeName}}</td>
														<td></td>
													</tr>
													@endforeach
												@endif
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>

							

							@endif
						@endif

							
						</div>


						


				</div>
			</div>
		</div>
	</div>
</section>

<script>

	var datascource = '@php  if (isset($unilevelMap_raw))
								 {
								 	echo $unilevelMap_raw; 
								 }
      @endphp'
								  
	datascource = "[" + datascource + "]";
                        var $tree = $('#treeview').treeview({
                            color: "#428bca",
                            expandIcon: "fa fa-chevron-right text-danger",
                            collapseIcon: "fa fa-chevron-down text-danger",
                            nodeIcon: "fa fa-user",
                            showTags: true,
                            showBorder: false,
                            data: datascource
                        });


                        $('#togglePan').on('click', function () {
                            // of course, oc.setOptions({ 'pan': this.checked }); is also OK.
                            oc.setOptions('pan', this.checked);
                        });

                        $('#toggleZoom').on('click', function () {
                            // of course, oc.setOptions({ 'zoom': this.checked }); is also OK.
                            oc.setOptions('zoom', this.checked);
                        });

	function SelectPaymentMethod() {
		document.getElementById('packageBuyForm').style.display = "none";
		switch (document.getElementById('FromWalletCode').value) {
			case 'CASH VIA ADMIN':
				document.getElementById('packageBuy_method_cashAdmin').style.display = "block";
				break;
			case 'DEPOSIT VIA BANK':
				document.getElementById('packageBuy_method_depositSlip').style.display = "block";
				break;
			default:
				break;
		}


		return false;
	}

	function SelectPackage(id) {
		$(window).scrollTop(0);
		document.getElementById('packageBuy_option').value = id;
		document.getElementById('packageSelectForm').style.display = "none";
		document.getElementById('packageBuyForm').style.display = "block";
		UpdateSelectedAmount();
	}

	function UpdateSelectedAmount() {
		document.getElementById('packageAmount_option').selectedIndex = document.getElementById('packageBuy_option').selectedIndex;
	}

	function getFormData(form) {
		 var unindexed_array = $(form).serializeArray();
		 var indexed_array = {};

		$.map(unindexed_array, function (n, i) {
			indexed_array[n['name']] = parseFloat(n['value']) >= 0 ? parseFloat(n['value']) : n['value'];
		 });

    return JSON.stringify(indexed_array);
}

	function sendFormData_V2(url, type, form) {
		console.log(getFormData(form));

    $.ajax({
        url: url,
		type: type,
        data: getFormData(form),
        contentType: 'application/json',
        success: function (data) {
            //console.log(data);
            if (data.Message != undefined && data.HttpStatusCode == "200") {
                alert(data.Message);
            }
            //window.location = data.RedirectUrl;
            //window.location.replace(data.RedirectUrl);
        },
        error: function (data, textStatus, jqXHR) {
            console.log(data.responseJSON);
            //alert(data.responseJSON.Status);
            if (data.responseJSON.message != undefined && data.responseJSON.httpStatusCode == "500") {
                alert(data.responseJSON.message);
            }
            //$('#myModal').modal('show');
            //window.location.href = data.responseJSON.RedirectUrl;
        },
    });

    return false
	}

	function SendDepositRequest() {

		 $.ajax({
		     url: config.ApiURL + 'api/BusinessPackage/Buy',
			 type: "POST",
			 data: getFormData(document.getElementById('packageForm')),
		     contentType: 'application/json',
		     success: function (data) {
		         //console.log(data);
		         if (data.message != undefined && data.httpStatusCode == "200") {
		             alert(data.message);
		         }
		         window.location = data.redirectUrl;
		         //window.location.replace(data.RedirectUrl);
		     },
		     error: function (data, textStatus, jqXHR) {
		         console.log(data.responseJSON);
		         //alert(data.responseJSON.Status);
		         if (data.responseJSON.message != undefined && data.responseJSON.httpStatusCode == "500") {
		             alert(data.responseJSON.message);
		         }
		         //$('#myModal').modal('show');
		         //window.location.href = data.responseJSON.RedirectUrl;
		     },
		 });

    return false
	}
	function CopyLink() {
  /* Get the text field */
  var copyText = document.getElementById("userLink");

  /* Select the text field */
  copyText.select();
  copyText.setSelectionRange(0, 99999); /*For mobile devices*/

  /* Copy the text inside the text field */
  document.execCommand("copy");

  /* Alert the copied text */
  alert("User source code copied!");
}

</script>

@endsection
