<section class="mb-4">
    <div class="container">
        <div class="px-2 py-4 p-md-4 bg-white shadow-sm">
            <div class="section-title-1 clearfix">
                <h3 class="heading-5 strong-700 mb-0 float-left">
                    <span class="mr-4"><?php echo e(__('Featured Products')); ?></span>
                </h3>
            </div>
            <div class="caorusel-box">
                <div class="slick-carousel" data-slick-items="6" data-slick-xl-items="5" data-slick-lg-items="4"  data-slick-md-items="3" data-slick-sm-items="2" data-slick-xs-items="2">
                    <?php $__currentLoopData = filter_products(\App\Product::where('published', 1)->where('featured', '1'))->limit(12)->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="product-card-2 card card-product m-2 shop-cards shop-tech">
                        <div class="card-body p-0">

                            <div class="card-image">
                                <a href="<?php echo e(route('product', $product->slug)); ?>" class="d-block">
                                    <img class="img-fit lazyload mx-auto" src="<?php echo e(asset('frontend/images/placeholder.jpg')); ?>" data-src="<?php echo e(asset($product->featured_img)); ?>" alt="<?php echo e(__($product->name)); ?>">
                                </a>
                            </div>

                            <div class="p-md-3 p-2">
                                <div class="price-box">
                                    <?php if(home_base_price($product->id) != home_discounted_base_price($product->id)): ?>
                                        <del class="old-product-price strong-400"><?php echo e(home_base_price($product->id)); ?></del>
                                    <?php endif; ?>
                                    <span class="product-price strong-600"><?php echo e(home_discounted_base_price($product->id)); ?></span>
                                </div>
                                <div class="star-rating star-rating-sm mt-1">
                                    <?php echo e(renderStarRating($product->rating)); ?>

                                </div>
                                <h2 class="product-title p-0 text-truncate-2">
                                    <a href="<?php echo e(route('product', $product->slug)); ?>"><?php echo e(__($product->name)); ?></a>
                                </h2>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>
    </div>
</section>
<?php /**PATH C:\Projects\PHP\Ark\resources\views/frontend/partials/featured_products_section.blade.php ENDPATH**/ ?>