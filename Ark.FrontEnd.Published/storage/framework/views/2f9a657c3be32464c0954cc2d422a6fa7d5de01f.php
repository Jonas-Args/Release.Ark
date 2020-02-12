<?php if(\App\BusinessSetting::where('type', 'vendor_system_activation')->first()->value == 1): ?>
    <?php
        $array = array();
        foreach (\App\Seller::all() as $key => $seller) {
            if($seller->user != null && $seller->user->shop != null){
                $total_sale = 0;
                foreach ($seller->user->products as $key => $product) {
                    $total_sale += $product->num_of_sale;
                }
                $array[$seller->id] = $total_sale;
            }
        }
        asort($array);
    ?>
    <section class="mb-5">
        <div class="container">
            <div class="px-2 py-4 p-md-4 bg-white shadow-sm">
                <div class="section-title-1 clearfix">
                    <h3 class="heading-5 strong-700 mb-0 float-left">
                        <span class="mr-4"><?php echo e(__('Best Sellers')); ?></span>
                    </h3>
                    <ul class="inline-links float-right">
                        <li><a  class="active"><?php echo e(__('Top 20')); ?></a></li>
                    </ul>
                </div>
                <div class="caorusel-box">
                    <div class="slick-carousel" data-slick-items="3" data-slick-lg-items="3"  data-slick-md-items="2" data-slick-sm-items="2" data-slick-xs-items="1" data-slick-dots="true" data-slick-rows="2">
                        <?php
                            $count = 0;
                        ?>
                        <?php $__currentLoopData = $array; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if($count < 20): ?>
                                <?php
                                    $count ++;
                                    $seller = \App\Seller::find($key);
                                    $total = 0;
                                    $rating = 0;
                                    foreach ($seller->user->products as $key => $seller_product) {
                                        $total += $seller_product->reviews->count();
                                        $rating += $seller_product->reviews->sum('rating');
                                    }
                                ?>
                                <div class="p-2">
                                    <div class="row no-gutters box-3 align-items-center border">
                                        <div class="col-4">
                                            <a href="<?php echo e(route('shop.visit', $seller->user->shop->slug)); ?>" class="d-block product-image p-3">
                                                <img src="<?php echo e(asset('frontend/images/placeholder.jpg')); ?>" data-src="<?php echo e(asset($seller->user->shop->logo)); ?>" alt="<?php echo e($seller->user->shop->name); ?>" class="img-fluid lazyload">
                                            </a>
                                        </div>
                                        <div class="col-8 border-left">
                                            <div class="p-3">
                                                <h2 class="product-title mb-0 p-0 text-truncate">
                                                    <a href="<?php echo e(route('shop.visit', $seller->user->shop->slug)); ?>"><?php echo e(__($seller->user->shop->name)); ?></a>
                                                </h2>
                                                <div class="star-rating star-rating-sm mb-2">
                                                    <?php if($total > 0): ?>
                                                        <?php echo e(renderStarRating($rating/$total)); ?>

                                                    <?php else: ?>
                                                        <?php echo e(renderStarRating(0)); ?>

                                                    <?php endif; ?>
                                                </div>
                                                <div class="">
                                                    <a href="<?php echo e(route('shop.visit', $seller->user->shop->slug)); ?>" class="icon-anim">
                                                        <?php echo e(__('Visit Store')); ?> <i class="la la-angle-right text-sm"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>
<?php /**PATH C:\xampp73\htdocs\shop\resources\views/frontend/partials/best_sellers_section.blade.php ENDPATH**/ ?>