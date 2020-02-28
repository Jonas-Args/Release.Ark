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


                <?php

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
			 


                ?>


                <div class="col-lg-9">
                    <div class="main-content">
                        <!-- Page title -->
                        <div class="page-title">
                            <div class="row align-items-center">
                                <div class="col-md-6 col-12 d-flex align-items-center">
                                    <h2 class="heading heading-6 text-capitalize strong-600 mb-0">
                                        <?php echo e(__('My Wallet')); ?>

                                    </h2>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="float-md-right">
                                        <ul class="breadcrumb">
                                            <li><a href="<?php echo e(route('home')); ?>"><?php echo e(__('Home')); ?></a></li>
                                            <li><a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a></li>
                                            <li class="active"><a href="<?php echo e(route('wallet.index')); ?>"><?php echo e(__('My Wallet')); ?></a></li>
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
                                    <span class="d-block title heading-3 strong-400"> ₱<?php echo e(number_format($UserWallet[array_search('ACW', array_column($UserWallet, 'walletCode'))]->balance,2)); ?></span>
                                    <span class="d-block sub-title"><?php echo e(__('Ark Cash Balance')); ?></span>

                                </div>
                            </div>
                            <div class="col-md-4 ">
                                <div class="dashboard-widget text-center green-widget text-white mt-4 c-pointer">
                                    <i class="la la-wallet" style="font-size:24px;"></i>
                                    <span class="d-block title heading-3 strong-400"><?php echo e(single_price(Auth::user()->balance)); ?></span>
                                    <span class="d-block sub-title"><?php echo e(__('Ark Credit Balance')); ?></span>

                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="dashboard-widget text-center plus-widget mt-4 c-pointer" onclick="show_wallet_modal()">
                                    <i class="la la-plus"></i>
                                    <span class="d-block title heading-6 strong-400 c-base-1"><?php echo e(__('Recharge Wallet')); ?></span>
                                </div>
                            </div>
                        </div>

                        <div class="card no-border mt-5">
                            <div class="card-header py-3">
                                <h4 class="mb-0 h6"><?php echo e(__('Wallet recharge history')); ?></h4>
                            </div>
                            <div class="card-body">
                                <table class="table table-sm table-responsive-md mb-0">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th><?php echo e(__('Date')); ?></th>
                                            <th><?php echo e(__('Amount')); ?></th>
                                            <th><?php echo e(__('Payment Method')); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if(count($wallets) > 0): ?>
                                            <?php $__currentLoopData = $wallets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $wallet): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr>
                                                    <td><?php echo e($key+1); ?></td>
                                                    <td><?php echo e(date('d-m-Y', strtotime($wallet->created_at))); ?></td>
                                                    <td><?php echo e(single_price($wallet->amount)); ?></td>
                                                    <td><?php echo e(ucfirst(str_replace('_', ' ', $wallet ->payment_method))); ?></td>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                        <div class="pagination-wrapper py-4">
                            <ul class="pagination justify-content-end">
                                <?php echo e($wallets->links()); ?>

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
                    <h5 class="modal-title strong-600 heading-5"><?php echo e(__('Recharge Wallet')); ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form class="" action="<?php echo e(route('wallet.recharge')); ?>" method="post">
                    <?php echo csrf_field(); ?>
                    <div class="modal-body gry-bg px-3 pt-3">
                        <div class="row">
                            <div class="col-md-2">
                                <label><?php echo e(__('Amount')); ?> <span class="required-star">*</span></label>
                            </div>
                            <div class="col-md-10">
                                <input type="number" class="form-control mb-3" name="amount" placeholder="Amount" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2">
                                <label><?php echo e(__('Payment Method')); ?></label>
                            </div>
                            <div class="col-md-10">
                                <div class="mb-3">
                                    <select class="form-control selectpicker" data-minimum-results-for-search="Infinity" name="payment_option">
                                        <?php if(\App\BusinessSetting::where('type', 'paypal_payment')->first()->value == 1): ?>
                                            <option value="paypal"><?php echo e(__('Paypal')); ?></option>
                                        <?php endif; ?>
                                        <?php if(\App\BusinessSetting::where('type', 'stripe_payment')->first()->value == 1): ?>
                                            <option value="stripe"><?php echo e(__('Stripe')); ?></option>
                                        <?php endif; ?>
                                        <?php if(\App\BusinessSetting::where('type', 'sslcommerz_payment')->first()->value == 1): ?>
                                            <option value="sslcommerz"><?php echo e(__('SSLCommerz')); ?></option>
                                        <?php endif; ?>
                                        <?php if(\App\BusinessSetting::where('type', 'instamojo_payment')->first()->value == 1): ?>
                                            <option value="instamojo"><?php echo e(__('Instamojo')); ?></option>
                                        <?php endif; ?>
                                        <?php if(\App\BusinessSetting::where('type', 'paystack')->first()->value == 1): ?>
                                            <option value="paystack"><?php echo e(__('Paystack')); ?></option>
                                        <?php endif; ?>
                                        <?php if(\App\BusinessSetting::where('type', 'voguepay')->first()->value == 1): ?>
                                            <option value="voguepay"><?php echo e(__('VoguePay')); ?></option>
                                        <?php endif; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-base-1"><?php echo e(__('Confirm')); ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <script type="text/javascript">
        function show_wallet_modal(){
            $('#wallet_modal').modal('show');
        }
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Projects\Published\Release.Ark\Ark.FrontEnd.Published\resources\views/frontend/wallet.blade.php ENDPATH**/ ?>