<?php $__env->startSection('content'); ?>

<section class="gry-bg py-4 profile">
	<div class="container">
		<div class="row cols-xs-space cols-sm-space cols-md-space">
			<div class="col-lg-3 d-none d-lg-block">
				<?php if(Auth::user()->user_type == 'seller'): ?>
						<?php echo $__env->make('frontend.inc.seller_side_nav', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
					<?php elseif(Auth::user()->user_type == 'customer'): ?>
						<?php echo $__env->make('frontend.inc.customer_side_nav', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
					<?php endif; ?>
			</div>

			<div class="col-lg-9">
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
											<a href="<?php echo e(route('profile')); ?>"><?php echo e(__('Your Affiliates')); ?></a>
										</li>
									</ul>
								</div>
							</div>
						</div>
					</div>
					
						<?php echo csrf_field(); ?>

						<div class="">

							<?php
			

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
					 $unilevelMap = $_res->userUnilevelMap->nodes;
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
			 
       ?>

							<?php if(count($_r->businessPackages) == 0): ?>

								<div class="form-box bg-white mt-4" id="packageBuyForm" style="display:none">
									<div class="form-box-title px-3 py-2">
										<?php echo e(__('Buy Package')); ?>

									</div>
									<div class="form-box-content p-3">
										<form onsubmit="return sendFormData_V2('http://localhost:55006/api/BusinessPackage/Buy', 'POST', this);" >
										
										<label>Package Name</label>
										<select class="form-control col-md-4" id="packageBuy_option" name="BusinessPackageID" oninput="UpdateSelectedAmount()">
											<?php $__currentLoopData = $businessPackages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $businessPackage): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
											<option value="<?php echo e($businessPackage->id); ?>"><?php echo e($businessPackage->packageName); ?> (PHP <?php echo e(number_format($businessPackage->valueFrom)); ?>)</option>
											<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
										</select>

										<select class="form-control col-md-4" id="packageAmount_option" name="AmountPaid" style="display:none">
											<?php $__currentLoopData = $businessPackages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $businessPackage): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
											<option value="<?php echo e($businessPackage->valueFrom); ?>"><?php echo e(number_format($businessPackage->valueFrom)); ?>)</option>
											<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
										</select>
										
										<br />

										<input type="hidden" name="Id" value="<?php echo e(Session::get('userAuthId')); ?>" />
										<input type="hidden" name="FromCurrencyIso3" value="PHP" />

										<label>Payment Method</label>
										<select class="form-control col-md-4" name="FromWalletCode">
											<option value="ACW">ARK CASH WALLET | PHP <?php echo e($UserWallet[9]->balance); ?></option>
											<option value="gcash" disabled>G-Cash (Coming Soon)</option>
											<option value="paymaya" disabled>Paymaya (Coming Soon)</option>
											<option value="7eleven" disabled>7 - Eleven (Coming Soon)</option>
											<option value="cebuana" disabled>Cebuana Lhuillier (Coming Soon)</option>
											<option value="palawan" disabled>Palawan Pawnshop (Coming Soon)</option>
										</select>
										<hr />
										<button type="submit" class="btn btn-styled btn-base-1 col-md-2" style="">Pay Now</button>
									</form>
									</div>
								</div>
							
								<div class="form-box bg-white mt-4" style="width:100%" id="packageSelectForm">
									<div class="form-box-title px-3 py-2">
										<?php echo e(__('Business Packages')); ?>

									</div>

									<div class="form-box-content p-3">

										<h5 style="margin-top:10px; margin-left:10px;">Please activate your account by purchasing a package below:</h5>

										<div class="row" style="padding:10px 10px;">
											
											<?php $__currentLoopData = $businessPackages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $businessPackage): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

											<div class="col-md-4">
												<div class="dashboard-widget text-center blue-widget mt-4 c-pointer">
													<a href="javascript:SelectPackage('<?php echo e($businessPackage->id); ?>');" class="d-block">
														<i class="fa fa-shopping-cart"></i>
														<h3 class="d-block title">PHP <?php echo e(number_format($businessPackage->valueFrom)); ?></h3>
														<span class="d-block title"><?php echo e($businessPackage->packageName); ?></span>
														<span class="d-block sub-title"><?php echo e($businessPackage->packageDescription); ?></span>
													</a>
												</div>
											</div>

											<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
										</div>

										<hr />
										<div class="col-md-12">
											<p>Or use special code provided by your sponsor</p>
											<input type="text" class="form-control mb-3 col-md-9" placeholder="Special Code" name="" style="display:inline-block" />
											<button type="button"  class="btn btn-styled btn-base-1 col-md-2" style="margin-top: -6px;line-height: 14px;display:inline-block;"><?php echo e(__('Apply Code')); ?></button>
										</div>
										

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
									<p>This is your enterprise source code you can share</p>
									<input type="text" class="form-control" name="name" value="<?php echo e('http://'.$_SERVER['HTTP_HOST'].'/users/registration?ulink='.$userLink->directSponsorID); ?>" />
									<hr />
									<button type="button" class="btn btn-styled btn-base-1 col-md-2" style="">Copy Link</button>
								</div>

								
							</div>
							<div class="form-box bg-white mt-4">
								<div class="form-box-title px-3 py-2">
									<?php echo e(__('Direct Affiliates')); ?>

								</div>
								<div class="form-box-content p-3">
									<div class="card no-border mt-4" style="margin-top: 6px!important;">
										<div>
											<table class="table table-sm table-hover table-responsive-md">
												<thead>
													<tr>
														<th><?php echo e(__('Date')); ?></th>
														<th><?php echo e(__('Name')); ?></th>
														<th><?php echo e(__('Account Package')); ?></th>
														<th><?php echo e(__('Status')); ?></th>
														<th><?php echo e(__('Total Commissions')); ?></th>
														<th><?php echo e(__('Options')); ?></th>
													</tr>
												</thead>
												<tbody>
													<?php if(isset($unilevelMap) && $unilevelMap != null): ?>
													<?php $__currentLoopData = $unilevelMap; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $unilevelMapItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
													<tr>
														<td><?php echo e($unilevelMapItem->userBusinessPackage->CreatedOn); ?></td>
														<td><?php echo e($unilevelMapItem->UserAuth->UserName); ?></td>
														<td><?php echo e($unilevelMapItem->userBusinessPackage->BusinessPackage->PackageName); ?></td>
														<td><?php echo e($unilevelMapItem->UserAuth->userBusinessPackage->PackageStatus); ?></td>
														<td><?php echo e($unilevelMapItem->TotalCommission); ?></td>
														<td></td>
													</tr>
													<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
												<?php endif; ?>


												</tbody>
											</table>
										</div>
									</div>

									<div class="text-right mt-4">
										<button type="submit" class="btn btn-styled btn-base-1  col-sm-12  col-lg-3"><?php echo e(__('View Genealogy')); ?></button>
									</div>
								</div>
							</div>
							<div class="form-box bg-white mt-4">
								<div class="form-box-title px-3 py-2">
									<?php echo e(__('Product Commission')); ?>

								</div>
								<div class="form-box-content p-3">
									<div class="card no-border mt-4" style="margin-top: 6px!important;">
										<div>
											<table class="table table-sm table-hover table-responsive-md">
												<thead>
													<tr>
														<th><?php echo e(__('Date')); ?></th>
														<th><?php echo e(__('User')); ?></th>
														<th><?php echo e(__('Amount')); ?></th>
														<th><?php echo e(__('Payment Status')); ?></th>
														<th><?php echo e(__('Options')); ?></th>
													</tr>
												</thead>
												<tbody>
												<?php if(isset($unilevelMap) && $unilevelMap != null): ?>
													<?php $__currentLoopData = $unilevelMap; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $unilevelMapItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
													<tr>
														<td><?php echo e($unilevelMapItem->userBusinessPackage->CreatedOn); ?></td>
														<td><?php echo e($unilevelMapItem->UserAuth->UserName); ?></td>
														<td><?php echo e($unilevelMapItem->userBusinessPackage->IncomePercentage); ?></td>
														<td><?php echo e($unilevelMapItem->UserAuth->userBusinessPackage->IncomeStatus); ?></td>
														<td></td>
													</tr>
													<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
												<?php endif; ?>

												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>

							<?php endif; ?>
							

							
						</div>


						


				</div>
			</div>
		</div>
	</div>
</section>

<script>
	function SelectPackage(id) {
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
			indexed_array[n['name']] = parseFloat(n['value']) > 0 ? parseFloat(n['value']) : n['value'];
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
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Projects\PHP\Ark\resources\views/frontend/customer/affiliate.blade.php ENDPATH**/ ?>