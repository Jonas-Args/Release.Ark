<?php if(\App\BusinessSetting::where('type', 'best_selling')->first()->value == 1): ?>
    <section class="mb-4">
        <div class="container">
            <div class="px-2 py-4 p-md-4 bg-white shadow-sm">
                <div class="section-title-1 clearfix">
                    <h3 class="heading-5 strong-700 mb-0 float-left">
                        <span class="mr-4"><?php echo e(__('Best Selling')); ?></span>
                    </h3>
                    <ul class="inline-links float-right">
                       <!-- <li><a  class="active"><?php echo e(__('Top 20')); ?></a></li>-->
                    </ul>
                </div>
                <div class="caorusel-box">
                    <div class="slick-carousel" data-slick-items="3" data-slick-lg-items="2"  data-slick-md-items="2" data-slick-sm-items="1" data-slick-xs-items="1" data-slick-dots="true" data-slick-rows="2">
                        <?php $__currentLoopData = filter_products(\App\Product::where('published', 1)->orderBy('num_of_sale', 'desc'))->limit(20)->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="p-2">
                                <div class="row no-gutters product-box-2 align-items-center">
                                    <div class="col-4">
                                        <div class="position-relative overflow-hidden h-100">
                                            <a href="<?php echo e(route('product', $product->slug)); ?>" class="d-block product-image h-100">
                                                <img class="img-fit lazyload mx-auto" src="<?php echo e(asset('frontend/images/placeholder.jpg')); ?>" data-src="<?php echo e(asset($product->thumbnail_img)); ?>" alt="<?php echo e(__($product->name)); ?>">
                                            </a>
                                            <div class="product-btns">
                                                <button class="btn add-wishlist" title="Add to Wishlist" onclick="addToWishList(<?php echo e($product->id); ?>)">
                                                    <i class="la la-heart-o"></i>
                                                </button>
                                                <!--<button class="btn add-compare" title="Add to Compare" onclick="addToCompare(<?php echo e($product->id); ?>)">
                                                    <i class="la la-refresh"></i>
                                                </button>
												                                                    -->
                                                <button class="btn quick-view" title="Quick view" onclick="showAddToCartModal(<?php echo e($product->id); ?>)">
                                                    <i class="la la-eye"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-8 border-left">
                                        <div class="p-3">
                                            <h2 class="product-title mb-0 p-0 text-truncate-2">
                                                <a href="<?php echo e(route('product', $product->slug)); ?>"><?php echo e(__($product->name)); ?></a>
                                            </h2>
                                            <div class="star-rating star-rating-sm mb-2">
                                                <?php echo e(renderStarRating($product->rating)); ?>

                                            </div>
                                            <div class="clearfix">
                                                <div class="price-box float-left">
                                                    <?php if(home_base_price($product->id) != home_discounted_base_price($product->id)): ?>
                                                        <del class="old-product-price strong-400"><?php echo e(home_base_price($product->id)); ?></del>
                                                    <?php endif; ?>
                                                    <span class="product-price strong-600"><?php echo e(home_discounted_base_price($product->id)); ?></span>
                                                </div>
                                                <div class="float-right">
                                                    <button class="add-to-cart btn" title="Add to Cart" onclick="showAddToCartModal(<?php echo e($product->id); ?>)">
                                                        <i class="la la-shopping-cart"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>
<?php /**PATH C:\Projects\PHP\Ark\resources\views/frontend/partials/best_selling_section.blade.php ENDPATH**/ ?>