<?php $__env->startSection('content'); ?>

<?php
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
?>

<section class="gry-bg py-4 profile">
	<div class="container">
		<div class="row cols-xs-space cols-sm-space cols-md-space">
			<div class="col-lg-3 d-none d-lg-block" style="display: <?php if(isset($userLink)): ?> block!important <?php else: ?> none!important <?php endif; ?>">
				<?php echo $__env->make('frontend.inc.customer_side_nav', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
			</div>
			<div class=" <?php if(isset($userLink)): ?> col-lg-9 <?php else: ?> col-lg-12 <?php endif; ?>">
				<!-- Page title -->
				<div class="page-title">
					<div class="row align-items-center">
						<div class="col-md-6 col-12">
							<h2 class="heading heading-6 text-capitalize strong-600 mb-0">
								<?php echo e(__('Dashboard')); ?>

							</h2>
						</div>
						<div class="col-md-6 col-12">
							<div class="float-md-right">
								<ul class="breadcrumb">
									<li>
										<a href="<?php echo e(route('home')); ?>"><?php echo e(__('Home')); ?></a>
									</li>
									<li class="active">
										<a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a>
									</li>
								</ul>
							</div>
						</div>
					</div>
				</div>

				<!-- dashboard content -->
				<div class="">
					
					<?php if($_r->httpStatusCode != "500"): ?>
					

					<?php if(count($_r->businessPackages) == 0): ?>

					<div class="form-box bg-white mt-4" style="width:100%" id="account_activation">
						<div class="form-box-title px-3 py-2">
							<?php echo e(__('Account Activation')); ?>

						</div>

						<div class="form-box-content p-3">

							<h5 style="margin-top:10px;">
								Loading..
							</h5>
						</div>
					</div>
					<script>window.location.replace('<?php echo e(route('affiliate')); ?>');
					</script>


					<?php elseif($_r->businessPackages[0]->packageStatus == "1"): ?>

					<div class="form-box bg-white mt-4" style="width:100%" id="">
						<div class="form-box-title px-3 py-2">
							<?php echo e(__('Account Activation')); ?>

						</div>

						<div class="form-box-content p-3">

							<p>Please pay your enterprise package to activate your account</p>
							<hr />
							<h6><b>Package Details:</b></h6>
							<ul>
							    <li>Package Name: <b><?php echo e($_r->businessPackages[0]->businessPackage->packageName); ?></b></li>
							    <li>Payment Method: <b><?php echo e($_r->businessPackages[0]->userDepositRequest->remarks); ?></b></li>
							    <li>Payment Amount: <b><?php echo e($_r->businessPackages[0]->userDepositRequest->amount); ?></b></li>
							    <li>Package Status: Pending Activation</li>
							</ul>
							
							<hr />
							<?php if($_r->businessPackages[0]->userDepositRequest->remarks == "DEPOSIT VIA BANK"): ?>

							<p><b>Bank Details:</b></>
							<p style="margin-bottom:0px">Bank Name: <b>EASTWEST</b></p>
							<p style="margin-bottom:0px">Currency: <b>PHP</b></p>
							<p style="margin-bottom:0px">Account Name: <b>ACCESSIBLE REVENUE KIOSK INC</b></p>
							<p style="margin-bottom:0px">Account Number: <b>200039751878</b></p>

							<?php elseif($_r->businessPackages[0]->userDepositRequest->remarks == "CASH VIA ADMIN"): ?>
							<h5>Cash Via Admin</h5>

										<p>Please proceed to Ark Philippines' offfice and pay the package amount on the counter.</p>
										<p><b>Office Location:</b></p>
										<p><?php echo e(\App\GeneralSetting::first()->address); ?></p>
							<?php endif; ?>
						</div>
					</div>

					<?php else: ?>
					<div class="row">



						<div class="col-md-4">
							<div class="dashboard-widget text-center green-widget mt-4 c-pointer">
								<a href="javascript:;" class="d-block">
									<i class="fa fa-shopping-cart"></i>
									<?php if(Session::has('cart')): ?>
									<span class="d-block title"><?php echo e(count(Session::get('cart'))); ?> Product(s)</span>
									<?php else: ?>
									<span class="d-block title">0 Product</span>
									<?php endif; ?>
									<span class="d-block sub-title">in your cart</span>
								</a>
							</div>
						</div>
						<div class="col-md-4">
							<div class="dashboard-widget text-center red-widget mt-4 c-pointer">
								<a href="javascript:;" class="d-block">
									<i class="fa fa-heart"></i>
									<span class="d-block title"><?php echo e(count(Auth::user()->wishlists)); ?> Product(s)</span>
									<span class="d-block sub-title">in your wishlist</span>
								</a>
							</div>
						</div>
						<div class="col-md-4">
							<div class="dashboard-widget text-center yellow-widget mt-4 c-pointer">
								<a href="javascript:;" class="d-block">
									<i class="fa fa-building"></i>
									<?php
			 $orders = \App\Order::where('user_id', Auth::user()->id)->get();
			 $total = 0;
			 foreach ($orders as $key => $order) {
				 $total += count($order->orderDetails);
			 }
             ?>
									<span class="d-block title"><?php echo e($total); ?> Product(s)</span>
									<span class="d-block sub-title">you ordered</span>
								</a>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-5">
							<div class="form-box bg-white mt-4">
								<div class="form-box-title px-3 py-2 clearfix ">
									<?php echo e(__('Saved Shipping Info')); ?>

									<div class="float-right">
										<a href="<?php echo e(route('profile')); ?>" class="btn btn-link btn-sm"><?php echo e(__('Edit')); ?></a>
									</div>
								</div>
								<div class="form-box-content p-3">
									<table>
										<tr>
											<td><?php echo e(__('Address')); ?>:</td>
											<td class="p-2"><?php echo e(Auth::user()->address); ?></td>
										</tr>
										<tr>
											<td><?php echo e(__('Country')); ?>:</td>
											<td class="p-2">
												<?php if(Auth::user()->country != null): ?>
                                                        <?php echo e(\App\Country::where('code', Auth::user()->country)->first()->name); ?>

                                                    <?php endif; ?>
											</td>
										</tr>
										<tr>
											<td><?php echo e(__('City')); ?>:</td>
											<td class="p-2"><?php echo e(Auth::user()->city); ?></td>
										</tr>
										<tr>
											<td><?php echo e(__('Postal Code')); ?>:</td>
											<td class="p-2"><?php echo e(Auth::user()->postal_code); ?></td>
										</tr>
										<tr>
											<td><?php echo e(__('Phone')); ?>:</td>
											<td class="p-2"><?php echo e(Auth::user()->phone); ?></td>
										</tr>
									</table>
								</div>
							</div>
						</div>
					</div>
					<?php endif; ?>

				<?php else: ?>
					<script>window.location.replace('<?php echo e(route('logout')); ?>');</script>
				<?php endif; ?>	
				</div>

			</div>
		</div>
	</div>
</section>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Projects\Published\Release.Ark\Ark.FrontEnd.Published\resources\views/frontend/customer/dashboard.blade.php ENDPATH**/ ?>