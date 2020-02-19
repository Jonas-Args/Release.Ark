<?php $__env->startSection('content'); ?>

    <div id="page-content">
        <section class="slice-xs sct-color-2 border-bottom">
            <div class="container container-sm">
                <div class="row cols-delimited justify-content-center">
                    <div class="col-4">
                        <div class="icon-block icon-block--style-1-v5 text-center ">
                            <div class="block-icon mb-0 c-gray-light">
                                <i class="la la-shopping-cart"></i>
                            </div>
                            <div class="block-content d-none d-md-block">
                                <h3 class="heading heading-sm strong-300 c-gray-light text-capitalize">1. <?php echo e(__('My cart')); ?></h3>
                            </div>
                        </div>
                    </div>

                    <div class="col-4">
                        <div class="icon-block icon-block--style-1-v5 text-center ">
                            <div class="block-icon mb-0 c-gray-light">
                                <i class="la la-map-o"></i>
                            </div>
                            <div class="block-content d-none d-md-block">
                                <h3 class="heading heading-sm strong-300 c-gray-light text-capitalize">2. <?php echo e(__('Shipping address')); ?></h3>
                            </div>
                        </div>
                    </div>

                    <div class="col-3" style="">
                        <div class="icon-block icon-block--style-1-v5 text-center active">
                            <div class="block-icon mb-0">
                                <i class="la la-truck"></i>
                            </div>
                            <div class="block-content d-none d-md-block">
                                <h3 class="heading heading-sm strong-300 c-gray-light text-capitalize">3. <?php echo e(__('Delivery info')); ?></h3>
                            </div>
                        </div>
                    </div>

                    <div class="col-4">
                        <div class="icon-block icon-block--style-1-v5 text-center">
                            <div class="block-icon mb-0 c-gray-light">
                                <i class="la la-credit-card"></i>
                            </div>
                            <div class="block-content d-none d-md-block">
                                <h3 class="heading heading-sm strong-300 c-gray-light text-capitalize">4. <?php echo e(__('Payment selection')); ?></h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-4 gry-bg">
            <div class="container">
                <div class="row cols-xs-space cols-sm-space cols-md-space">
                    <div class="col-xl-8">
                        <form class="form-default" data-toggle="validator" action="<?php echo e(route('checkout.store_delivery_info')); ?>" role="form" method="POST">
                            <?php echo csrf_field(); ?>
                            <?php
                                $admin_products = array();
                                $seller_products = array();
                                foreach (Session::get('cart') as $key => $cartItem){
                                    if(\App\Product::find($cartItem['id'])->added_by == 'admin'){
                                        array_push($admin_products, $cartItem['id']);
                                    }
                                    else{
                                        $product_ids = array();
                                        if(array_key_exists(\App\Product::find($cartItem['id'])->user_id, $seller_products)){
                                            $product_ids = $seller_products[\App\Product::find($cartItem['id'])->user_id];
                                        }
                                        array_push($product_ids, $cartItem['id']);
                                        $seller_products[\App\Product::find($cartItem['id'])->user_id] = $product_ids;
                                    }
                                }
                            ?>

                            <?php if(!empty($admin_products)): ?>
                            <div class="card mb-3">
                                <div class="card-header bg-white py-3">
                                    <h5 class="heading-6 mb-0"><?php echo e(\App\GeneralSetting::first()->site_name); ?> Products</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <table class="table-cart">
                                                <tbody>
                                                    <?php $__currentLoopData = $admin_products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $id): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <tr class="cart-item">
                                                        <td class="product-image" width="25%">
                                                            <a href="<?php echo e(route('product', \App\Product::find($id)->slug)); ?>" target="_blank">
                                                                <img loading="lazy"  src="<?php echo e(asset(\App\Product::find($id)->thumbnail_img)); ?>">
                                                            </a>
                                                        </td>
                                                        <td class="product-name strong-600">
                                                            <a href="<?php echo e(route('product', \App\Product::find($id)->slug)); ?>" target="_blank" class="d-block c-base-2">
                                                                <?php echo e(\App\Product::find($id)->name); ?>

                                                            </a>
                                                        </td>
                                                    </tr>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-6">
                                                    <label class="d-flex align-items-center p-3 border rounded gry-bg c-pointer">
                                                        <input type="radio" name="shipping_type_admin" value="home_delivery" checked class="d-none" onchange="show_pickup_point(this)" data-target=".pickup_point_id_admin">
                                                        <span class="radio-box"></span>
                                                        <span class="d-block ml-2 strong-600">
                                                            <?php echo e(__('Home Delivery')); ?>

                                                        </span>
                                                    </label>
                                                </div>
                                                <?php if(\App\BusinessSetting::where('type', 'pickup_point')->first()->value == 1): ?>
                                                    <div class="col-6">
                                                        <label class="d-flex align-items-center p-3 border rounded gry-bg c-pointer">
                                                            <input type="radio" name="shipping_type_admin" value="pickup_point" class="d-none" onchange="show_pickup_point(this)" data-target=".pickup_point_id_admin">
                                                            <span class="radio-box"></span>
                                                            <span class="d-block ml-2 strong-600">
                                                                <?php echo e(__('Local Pickup')); ?>

                                                            </span>
                                                        </label>
                                                    </div>
                                                <?php endif; ?>
                                            </div>

                                            <?php if(\App\BusinessSetting::where('type', 'pickup_point')->first()->value == 1): ?>
                                                <div class="mt-3 pickup_point_id_admin d-none">
                                                    <select class="pickup-select form-control-lg w-100" name="pickup_point_id_admin" data-placeholder="Select a pickup point">
                                                            <option>Select your nearest pickup point</option>
                                                        <?php $__currentLoopData = \App\PickupPoint::where('pick_up_status',1)->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $pick_up_point): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <option value="<?php echo e($pick_up_point->id); ?>" data-address="<?php echo e($pick_up_point->address); ?>" data-phone="<?php echo e($pick_up_point->phone); ?>">
                                                                <?php echo e($pick_up_point->name); ?>

                                                            </option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </select>
                                                </div>
                                            <?php endif; ?>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>
                            <?php if(!empty($seller_products)): ?>
                                <?php $__currentLoopData = $seller_products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $seller_product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="card mb-3">
                                        <div class="card-header bg-white py-3">
                                            <h5 class="heading-6 mb-0"><?php echo e(\App\Shop::where('user_id', $key)->first()->name); ?> Products</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row no-gutters">
                                                <div class="col-md-6">
                                                    <table class="table-cart">
                                                        <tbody>
                                                            <?php $__currentLoopData = $seller_product; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <tr class="cart-item">
                                                                <td class="product-image" width="25%">
                                                                    <a href="<?php echo e(route('product', \App\Product::find($id)->slug)); ?>" target="_blank">
                                                                        <img loading="lazy"  src="<?php echo e(asset(\App\Product::find($id)->thumbnail_img)); ?>">
                                                                    </a>
                                                                </td>
                                                                <td class="product-name strong-600">
                                                                    <a href="<?php echo e(route('product', \App\Product::find($id)->slug)); ?>" target="_blank" class="d-block c-base-2">
                                                                        <?php echo e(\App\Product::find($id)->name); ?>

                                                                    </a>
                                                                </td>
                                                            </tr>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="row">
                                                        <div class="col-6">
                                                            <label class="d-flex align-items-center p-3 border rounded gry-bg c-pointer">
                                                                <input type="radio" name="shipping_type_<?php echo e($key); ?>" value="home_delivery" checked class="d-none" onchange="show_pickup_point(this)" data-target=".pickup_point_id_<?php echo e($key); ?>">
                                                                <span class="radio-box"></span>
                                                                <span class="d-block ml-2 strong-600">
                                                                    <?php echo e(__('Home Delivery')); ?>

                                                                </span>
                                                            </label>
                                                        </div>
                                                        <?php if(\App\BusinessSetting::where('type', 'pickup_point')->first()->value == 1): ?>
                                                            <?php if(is_array(json_decode(\App\Shop::where('user_id', $key)->first()->pick_up_point_id))): ?>
                                                                <div class="col-6">
                                                                    <label class="d-flex align-items-center p-3 border rounded gry-bg c-pointer">
                                                                        <input type="radio" name="shipping_type_<?php echo e($key); ?>" value="pickup_point" class="d-none" onchange="show_pickup_point(this)" data-target=".pickup_point_id_<?php echo e($key); ?>">
                                                                        <span class="radio-box"></span>
                                                                        <span class="d-block ml-2 strong-600">
                                                                            <?php echo e(__('Local Pickup')); ?>

                                                                        </span>
                                                                    </label>
                                                                </div>
                                                            <?php endif; ?>
                                                        <?php endif; ?>
                                                    </div>

                                                    <?php if(\App\BusinessSetting::where('type', 'pickup_point')->first()->value == 1): ?>
                                                        <?php if(is_array(json_decode(\App\Shop::where('user_id', $key)->first()->pick_up_point_id))): ?>
                                                            <div class="mt-3 pickup_point_id_<?php echo e($key); ?> d-none">
                                                                <select class="pickup-select form-control-lg w-100" name="pickup_point_id_<?php echo e($key); ?>" data-placeholder="Select a pickup point">
                                                                    <option>Select your nearest pickup point</option>
                                                                    <?php $__currentLoopData = json_decode(\App\Shop::where('user_id', $key)->first()->pick_up_point_id); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pick_up_point): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                        <?php if(\App\PickupPoint::find($pick_up_point) != null): ?>
                                                                            <option value="<?php echo e(\App\PickupPoint::find($pick_up_point)->id); ?>" data-address="<?php echo e(\App\PickupPoint::find($pick_up_point)->address); ?>" data-phone="<?php echo e(\App\PickupPoint::find($pick_up_point)->phone); ?>">
                                                                                <?php echo e(\App\PickupPoint::find($pick_up_point)->name); ?>

                                                                            </option>
                                                                        <?php endif; ?>
                                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                </select>
                                                            </div>
                                                        <?php endif; ?>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
                            <div class="row align-items-center pt-4">
                                <div class="col-md-6">
                                    <a href="<?php echo e(route('home')); ?>" class="link link--style-3">
                                        <i class="ion-android-arrow-back"></i>
                                        <?php echo e(__('Return to shop')); ?>

                                    </a>
                                </div>
                                <div class="col-md-6 text-right">
                                    <button type="submit" class="btn btn-styled btn-base-1"><?php echo e(__('Continue to Payment')); ?></a>
                                </div>
                            </div>
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
        function display_option(key){

        }
        function show_pickup_point(el) {
        	var value = $(el).val();
        	var target = $(el).data('target');

            console.log(value);

        	if(value == 'home_delivery'){
                if(!$(target).hasClass('d-none')){
                    $(target).addClass('d-none');
                }
        	}else{
        		$(target).removeClass('d-none');
        	}
        }

    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Projects\PHP\Ark\resources\views/frontend/delivery_info.blade.php ENDPATH**/ ?>