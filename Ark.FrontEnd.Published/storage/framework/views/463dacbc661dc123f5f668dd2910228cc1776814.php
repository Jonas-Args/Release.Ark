<?php $__env->startSection('content'); ?>

    <div id="page-content">
        <section class="slice-xs sct-color-2 border-bottom">
            <div class="container container-sm">
                <div class="row cols-delimited justify-content-center">
                    <div class="col-4">
                        <div class="icon-block icon-block--style-1-v5 text-center">
                            <div class="block-icon c-gray-light mb-0">
                                <i class="la la-shopping-cart"></i>
                            </div>
                            <div class="block-content d-none d-md-block">
                                <h3 class="heading heading-sm strong-300 c-gray-light text-capitalize">1. <?php echo e(__('My Cart')); ?></h3>
                            </div>
                        </div>
                    </div>

                    <div class="col-4">
                        <div class="icon-block icon-block--style-1-v5 text-center">
                            <div class="block-icon c-gray-light mb-0">
                                <i class="la la-truck"></i>
                            </div>
                            <div class="block-content d-none d-md-block">
                                <h3 class="heading heading-sm strong-300 c-gray-light text-capitalize">2. <?php echo e(__('Shipping info')); ?></h3>
                            </div>
                        </div>
                    </div>

					<div class="col-3" style="">
						<div class="icon-block icon-block--style-1-v5 text-center">
							<div class="block-icon mb-0 c-gray-light">
								<i class="la la-truck"></i>
							</div>
							<div class="block-content d-none d-md-block">
								<h3 class="heading heading-sm strong-300 c-gray-light text-capitalize">3. <?php echo e(__('Delivery info')); ?></h3>
							</div>
						</div>
					</div>

                    <div class="col-4">
                        <div class="icon-block icon-block--style-1-v5 text-center active">
                            <div class="block-icon mb-0">
                                <i class="la la-credit-card"></i>
                            </div>
                            <div class="block-content d-none d-md-block">
                                <h3 class="heading heading-sm strong-300 c-gray-light text-capitalize">3. <?php echo e(__('Payment')); ?></h3>
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
                        <form action="<?php echo e(route('payment.checkout')); ?>" class="form-default" data-toggle="validator" role="form" method="POST" id="checkout-form">
                            <?php echo csrf_field(); ?>
                            <div class="card">
                                <div class="card-title px-4 py-3">
                                    <h3 class="heading heading-5 strong-500">
                                        <?php echo e(__('Select a payment option')); ?>

                                    </h3>
                                </div>
                                <div class="card-body text-center">
                                    <div class="row">
                                        <div class="col-md-6 mx-auto">
                                            <div class="row">
                                                <?php if(\App\BusinessSetting::where('type', 'paypal_payment')->first()->value == 1): ?>
                                                    <div class="col-6">
                                                        <label class="payment_option mb-4" data-toggle="tooltip" data-title="Paypal">
                                                            <input type="radio" id="" name="payment_option" value="paypal" checked>
                                                            <span>
                                                                <img loading="lazy"  src="<?php echo e(asset('frontend/images/icons/cards/paypal.png')); ?>" class="img-fluid">
                                                            </span>
                                                        </label>
                                                    </div>
                                                <?php endif; ?>
                                                <?php if(\App\BusinessSetting::where('type', 'stripe_payment')->first()->value == 1): ?>
                                                    <div class="col-6">
                                                        <label class="payment_option mb-4" data-toggle="tooltip" data-title="Stripe">
                                                            <input type="radio" id="" name="payment_option" value="stripe" checked>
                                                            <span>
                                                                <img loading="lazy"  src="<?php echo e(asset('frontend/images/icons/cards/stripe.png')); ?>" class="img-fluid">
                                                            </span>
                                                        </label>
                                                    </div>
                                                <?php endif; ?>
                                                <?php if(\App\BusinessSetting::where('type', 'sslcommerz_payment')->first()->value == 1): ?>
                                                    <div class="col-6">
                                                        <label class="payment_option mb-4" data-toggle="tooltip" data-title="sslcommerz">
                                                            <input type="radio" id="" name="payment_option" value="sslcommerz" checked>
                                                            <span>
                                                                <img loading="lazy"  src="<?php echo e(asset('frontend/images/icons/cards/sslcommerz.png')); ?>" class="img-fluid">
                                                            </span>
                                                        </label>
                                                    </div>
                                                <?php endif; ?>
                                                <?php if(\App\BusinessSetting::where('type', 'instamojo_payment')->first()->value == 1): ?>
                                                    <div class="col-6">
                                                        <label class="payment_option mb-4" data-toggle="tooltip" data-title="Instamojo">
                                                            <input type="radio" id="" name="payment_option" value="instamojo" checked>
                                                            <span>
                                                                <img loading="lazy"  src="<?php echo e(asset('frontend/images/icons/cards/instamojo.png')); ?>" class="img-fluid">
                                                            </span>
                                                        </label>
                                                    </div>
                                                <?php endif; ?>
                                                <?php if(\App\BusinessSetting::where('type', 'razorpay')->first()->value == 1): ?>
                                                    <div class="col-6">
                                                        <label class="payment_option mb-4" data-toggle="tooltip" data-title="Razorpay">
                                                            <input type="radio" id="" name="payment_option" value="razorpay" checked>
                                                            <span>
                                                                <img loading="lazy"  src="<?php echo e(asset('frontend/images/icons/cards/rozarpay.png')); ?>" class="img-fluid">
                                                            </span>
                                                        </label>
                                                    </div>
                                                <?php endif; ?>
                                                <?php if(\App\BusinessSetting::where('type', 'paystack')->first()->value == 1): ?>
                                                    <div class="col-6">
                                                        <label class="payment_option mb-4" data-toggle="tooltip" data-title="Paystack">
                                                            <input type="radio" id="" name="payment_option" value="paystack" checked>
                                                            <span>
                                                                <img loading="lazy"  src="<?php echo e(asset('frontend/images/icons/cards/paystack.png')); ?>" class="img-fluid">
                                                            </span>
                                                        </label>
                                                    </div>
                                                <?php endif; ?>
                                                <?php if(\App\BusinessSetting::where('type', 'voguepay')->first()->value == 1): ?>
                                                    <div class="col-6">
                                                        <label class="payment_option mb-4" data-toggle="tooltip" data-title="VoguePay">
                                                            <input type="radio" id="" name="payment_option" value="voguepay" checked>
                                                            <span>
                                                                <img loading="lazy"  src="<?php echo e(asset('frontend/images/icons/cards/vogue.png')); ?>" class="img-fluid">
                                                            </span>
                                                        </label>
                                                    </div>
                                                <?php endif; ?>
												<input type="radio" id="" name="payment_option" value="cash_on_delivery" checked style="display:none" />
                                                <?php if(\App\BusinessSetting::where('type', 'cash_payment')->first()->value == 1): ?>
                                                    <div class="col-6">
                                                        <label class="payment_option mb-4" data-toggle="tooltip" data-title="Cash on Delivery">
                                                            <input type="radio" id="" name="payment_option" value="cash_on_delivery" checked>
                                                            <span>
                                                                <img loading="lazy"  src="<?php echo e(asset('frontend/images/icons/cards/cod.png')); ?>" class="img-fluid">
                                                            </span>
                                                        </label>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <?php if(Auth::check() && \App\BusinessSetting::where('type', 'wallet_system')->first()->value == 1): ?>
                                      <!---<div class="or or--1 mt-2">
                                            <span>or</span>
                                        </div>-->  
                                        <div class="row">
                                            <div class="col-xxl-6 col-lg-8 col-md-10 mx-auto">
                                                <div class="text-center bg-gray py-4">
                                                    <i class="fa"></i>
                                                    <div class="h5 mb-4">Your ark credit balance : <strong><?php echo e(single_price(Auth::user()->balance)); ?></strong></div>
                                                    <?php if(Auth::user()->balance < $total): ?>
                                                        <button type="button" class="btn btn-base-2" disabled>Insufficient balance</button>
                                                    <?php else: ?>
                                                        <button  type="button" onclick="use_wallet()" class="btn btn-base-1">Pay with wallet</button>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>

							<?php if(Auth::check() && \App\BusinessSetting::where('type', 'wallet_system')->first()->value == 1): ?>
                            <?php else: ?>
                            <div class="row align-items-center pt-4">
                                <div class="col-6">
                                    <a href="<?php echo e(route('home')); ?>" class="link link--style-3">
                                        <i class="ion-android-arrow-back"></i>
                                        <?php echo e(__('Return to shop')); ?>

                                    </a>
                                </div>
                                <div class="col-6 text-right">
                                     <?php if(Auth::user()->balance < $total): ?>
                                    <button type="submit" class="btn btn-styled btn-base-1"><?php echo e(__('Complete Order')); ?></button>
									<?php endif; ?>
                                </div>
                            </div>
							<?php endif; ?>
                        </form>
                    </div>

                    <div class="col-lg-4 ml-lg-auto">
                        <?php echo $__env->make('frontend.partials.cart_summary', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    </div>
                </div>
            </div>
        </section>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <script type="text/javascript">
        function use_wallet(){
            $('input[name=payment_option]').val('wallet');
            $('#checkout-form').submit();
        }
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Projects\Published\Release.Ark\Ark.FrontEnd.Published\resources\views/frontend/payment_select.blade.php ENDPATH**/ ?>