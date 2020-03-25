<?php $__env->startSection('content'); ?>

<?php
			 

			 $_s = Session::get('apiSession');
			 $totalAmount = 0;

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
				 
				 if(isset($userIncomeTransactions) && $userIncomeTransactions != null){
					 foreach ($userIncomeTransactions as $userIncomeTransactionItem)
					 {
						 $totalAmount += floatval($userIncomeTransactionItem->incomePercentage);
					 }
				 }

			 }
			 catch (Exception $exception)
			 {
				 echo '<script>window.location = "' .  route('logout') . '"</script>';
			 }
			 
  ?>


<section class="gry-bg py-4 profile">
	<div class="container">
		<div class="row cols-xs-space cols-sm-space cols-md-space">
			<div class="col-lg-3 d-none d-lg-block" style="display: <?php if(isset($userLink)): ?> block <?php else: ?> none <?php endif; ?>">
				<?php if(Auth::user()->user_type == 'seller'): ?>
						<?php echo $__env->make('frontend.inc.seller_side_nav', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
					<?php elseif(Auth::user()->user_type == 'customer'): ?>
						<?php echo $__env->make('frontend.inc.customer_side_nav', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
					<?php endif; ?>
			</div>

			<div class=" <?php if(isset($userLink)): ?> col-lg-9 <?php else: ?> col-lg-12 <?php endif; ?>">
				<div class="main-content">
					<!-- Page title -->
					<div class="page-title">
						<div class="row align-items-center">
							<div class="col-md-6 col-12">
								<h2 class="heading heading-6 text-capitalize strong-600 mb-0">
									<?php echo e(__('Your Enterprise')); ?>

								</h2>
							</div>
							<div class="col-md-6 col-12">
								<div class="float-md-right">
									<ul class="breadcrumb">
										<li>
											<a href="<?php echo e(route('home')); ?>"><?php echo e(__('Home')); ?></a>
										</li>
										<li>
											<a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a>
										</li>
										<li class="active">
											<a href="<?php echo e(route('profile')); ?>"><?php echo e(__('My Enterprise')); ?></a>
										</li>
									</ul>
								</div>
							</div>
						</div>
					</div>
					
						<?php echo csrf_field(); ?>

						<div class="">

							

							<?php if($_r->httpStatusCode == "500"): ?>
							<script>window.location.replace('<?php echo e(route('logout')); ?>');</script>

							<?php else: ?>


							<?php if(count($_r->businessPackages) == 0): ?>

								<div class="form-box bg-white mt-4" id="packageBuyForm" style="display:none">
									<div class="form-box-title px-3 py-2">
										<?php echo e(__('Payment Method')); ?>

									</div>
									<div  class="form-box-content p-3">
										<form id="packageForm" method="post" action="<?php echo e(route('payment.checkout.paynamics')); ?>" onsubmit="return SelectPaymentMethod();" >
										<?php echo csrf_field(); ?>
										<label><b>Selected Package</b></label>
										<select class="form-control col-md-4" id="packageBuy_option" name="BusinessPackageID" oninput="UpdateSelectedAmount()">
											<?php $__currentLoopData = $businessPackages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $businessPackage): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
											<option value="<?php echo e($businessPackage->id); ?>"><?php echo e($businessPackage->packageName); ?> (PHP <?php echo e(number_format($businessPackage->valueTo)); ?>)</option>
											<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
										</select>

										<select class="form-control col-md-4" id="packageAmount_option" name="AmountPaid" style="display:none">
											<?php $__currentLoopData = $businessPackages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $businessPackage): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
											<option value="<?php echo e($businessPackage->valueTo); ?>"><?php echo e(number_format($businessPackage->valueTo)); ?>)</option>
											<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
										</select>
										
										<br />

										<input type="hidden" name="Id" value="<?php echo e(Session::get('userAuthId')); ?>" />
										<input type="hidden" name="FromCurrencyIso3" value="PHP" />
										<input type="number" style="display:none" name="DepositStatus" value="0" />

										<label><b>Select Payment Method</b></label>
										<select class="form-control col-md-4" name="Remarks" id="FromWalletCode">
											<option value="CASH VIA ADMIN">CASH VIA ADMIN</option>
											<option value="DEPOSIT VIA BANK">DEPOSIT VIA BANK</option>
										<!--<option value="ACW">ARK CASH WALLET | PHP <?php echo e($UserWallet[9]->balance); ?></option>-->	
											<option value="PAYNAMICS">Other Payment Method (7-Eleven, G-Cash, Paymaya, Etc)</option>
										</select>
										<hr />
										<button type="submit" class="btn btn-styled btn-base-1 col-md-2" style="">Next</button>
									</form>
									</div>
								</div>
							
								<div class="form-box bg-white mt-4" style="width:100%" id="packageSelectForm">
									<div class="form-box-title px-3 py-2">
										<b><?php echo e(__('Enterprise Packages')); ?></b>
									</div>

									<div class="form-box-content p-3">


										<div class="row" style="padding:10px 10px;">
											
											<?php $__currentLoopData = $businessPackages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $businessPackage): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

											<div class="col-md-4">
												<img class="dashboard-widget" src="<?php echo e(asset('uploads/packages/' . $businessPackage->imageFile)); ?>" onclick="SelectPackage('<?php echo e($businessPackage->id); ?>');" alt="Alternate Text" style="width:100%" />
											</div>

											<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

											<div class="col-md-4">
												<img class="dashboard-widget" src="<?php echo e(asset('uploads/packages/DIRECT_REFERRAL_REWARD.png')); ?>"  alt="Alternate Text" style="width:100%" />
											</div>
										</div>

									</div>
									
								</div>

								<div class="form-box bg-white mt-4" id="packageBuy_method_cashAdmin" style="display:none">
									<div class="form-box-title px-3 py-2">
										<?php echo e(__('Payment')); ?>

									</div>
									<div class="form-box-content p-3">
										<h5>Cash Via Admin</h5>

										<p>Please proceed to Ark Philippines' offfice and pay the package amount on the counter.</p>
										<hr />
										<p><b>Office Location:</b></p>
										<p><?php echo e(\App\GeneralSetting::first()->address); ?></p>

										<button type="button" onclick="SendDepositRequest();" class="btn btn-styled btn-base-1 col-md-2">Confirm</button>
									</div>

									
								</div>

								<div class="form-box bg-white mt-4" id="packageBuy_method_depositSlip" style="display:none">
									<div class="form-box-title px-3 py-2">
										<?php echo e(__('Payment')); ?>

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

							<?php else: ?>

							<div class="form-box bg-white mt-4">
								<div class="form-box-title px-3 py-2">
									<?php echo e(__('Summary')); ?>

								</div>
								<div class="form-box-content p-3"></div>
							</div>
							<div class="form-box bg-white mt-4">
								<div class="form-box-title px-3 py-2">
									<?php echo e(__('Source Code Link')); ?>

								</div>
								<div class="form-box-content p-3">
									
									<?php if(isset($userLink)): ?>
									<p>This is your enterprise source code you can share</p>
									<input type="text" id="userLink" class="form-control" name="name" value="<?php echo e('http://'.$_SERVER['HTTP_HOST'].'/users/registration?ulink='.$userLink->directSponsorID); ?>" />
									<?php else: ?>
									<p>Please activate your account first</p>
									<?php endif; ?>
									
									<hr />
									<button type="button" onclick="CopyLink()" class="btn btn-styled btn-base-1 col-md-2" style="">Copy Link</button>
								</div>

								
							</div>
							<div class="form-box bg-white mt-4">
								<div class="form-box-title px-3 py-2">
									<?php echo e(__('Enterprisers Under You')); ?>

								</div>
								<div class="form-box-content p-3">
									 <div id="treeview"></div>
								</div>
							</div>
							<div class="form-box bg-white mt-4" style="display:none!important">
								<div class="form-box-title px-3 py-2">
									<?php echo e(__('First Level Enterprisers')); ?>

								</div>
								<div class="form-box-content p-3">
									<div class="card no-border mt-4" style="margin-top: 6px!important;">
										<div>
											<table class="table table-sm table-hover table-responsive-md">
												<thead>
													<tr>
														<th><?php echo e(__('Date')); ?></th>
														<th><?php echo e(__('Email')); ?></th>
														<th><?php echo e(__('Account Package')); ?></th>
														<th><?php echo e(__('Status')); ?></th>
													</tr>
												</thead>
												<tbody>
													<?php if(isset($unilevelMap) && $unilevelMap != null): ?>
													<?php $__currentLoopData = $unilevelMap; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $unilevelMapItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
													<tr>
														<td><?php echo e(date_format(date_create($unilevelMapItem->userBusinessPackage->createdOn),"Y/m/d H:i:s")); ?></td>
														<td><?php echo e($unilevelMapItem->userAuth->userName); ?></td>
														<td><?php echo e($unilevelMapItem->userBusinessPackage->businessPackage->packageName); ?></td>
														<td><?php echo e($unilevelMapItem->userBusinessPackage->packageStatus == 2 ? 'Activated' : 'Pending Activation'); ?></td>
													</tr>
													<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
												<?php endif; ?>


												</tbody>
											</table>
										</div>
									</div>

									<!--<div class="text-right mt-4">
										<button type="submit" class="btn btn-styled btn-base-1  col-sm-12  col-lg-3"><?php echo e(__('View Genealogy')); ?></button>
									</div>-->
								</div>
							</div>
							<div class="form-box bg-white mt-4">
								<div class="form-box-title px-3 py-2">
									<?php echo e(__('Transactions')); ?>

								</div>
								<div class="form-box-content p-3">
									<div class="card no-border mt-4" style="margin-top: 6px!important;">
										<div>
											<table class="table table-sm table-hover table-responsive-md">
												<thead>
													<tr>
														<th><?php echo e(__('Date')); ?></th>
														<th><?php echo e(__('User')); ?></th>
														<th><?php echo e(__('Reward Name')); ?></th>
														<th><?php echo e(__('Computation')); ?></th>
														<th><?php echo e(__('Amount')); ?></th>
													</tr>
												</thead>
												<tbody>
												
												<?php if(isset($userIncomeTransactions) && $userIncomeTransactions != null): ?>
													<?php $__currentLoopData = $userIncomeTransactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $userIncomeTransactionItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
													
													<tr>
														<td><?php echo e(date_format(date_create($userIncomeTransactionItem->createdOn),"Y/m/d H:i:s")); ?></td>
														<td><?php echo e($userIncomeTransactionItem->userAuth->userInfo->firstName . ' ' .  $userIncomeTransactionItem->userAuth->userInfo->lastName); ?></td>
														<td><?php echo e($userIncomeTransactionItem->incomeType->incomeTypeName); ?></td>
														<td><?php echo e($userIncomeTransactionItem->remarks); ?></td>
														<td><?php echo e(number_format($userIncomeTransactionItem->incomePercentage,3)); ?></td>
													</tr>
													<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

													<tr>
													 <td></td>
													 <td></td>
													 <td></td>
													 <td></td>
													 <td><b><?php echo e($totalAmount); ?></b></td>
														
												 </tr>
												<?php else: ?>
											         <tr>
                                                        <td class="text-center pt-5 h4" colspan="100%">
                                                            <i class="la la-meh-o d-block heading-1 alpha-5"></i>
                                                        <span class="d-block"><?php echo e(__('No history found.')); ?></span>
                                                        </td>
                                                    </tr>

												<?php endif; ?>

												 
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>

							

							<?php endif; ?>
						<?php endif; ?>

							
						</div>


						


				</div>
			</div>
		</div>
	</div>
</section>

<script>

	var datascource = '<?php  if (isset($unilevelMap_raw))
							 {
								 echo $unilevelMap_raw; 
							 }
      ?>'
								  
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
			case 'PAYNAMICS':
				return true;
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

<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Projects\Published\Release.Ark\Ark.FrontEnd.Published\resources\views/frontend/customer/affiliate.blade.php ENDPATH**/ ?>