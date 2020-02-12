<?php $__env->startSection('content'); ?>

<section class="gry-bg py-4 profile">
	<div class="container">
		<div class="row cols-xs-space cols-sm-space cols-md-space">
			<div class="col-lg-3 d-none d-lg-block">
				<?php echo $__env->make('frontend.inc.customer_side_nav', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
			</div>
			<div class="col-lg-9">
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
					<?php
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
     ?>

					<?php if(count($_r->businessPackages) == 0): ?>

					<div class="form-box bg-white mt-4" style="width:100%">
						<div class="form-box-title px-3 py-2">
							<?php echo e(__('Account Status')); ?>

						</div>

						<div class="form-box-content p-3">

							<h5 style="margin-top:10px;">
								Please activate your account by purchasing a package.
								
							</h5>
							<a class="btn btn-styled btn-base-1 col-md-2" href="<?php echo e(route('affiliate')); ?>">Activate Now</a>
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

					
				</div>

			</div>
		</div>
	</div>
</section>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Projects\PHP\Ark\resources\views/frontend/customer/dashboard.blade.php ENDPATH**/ ?>