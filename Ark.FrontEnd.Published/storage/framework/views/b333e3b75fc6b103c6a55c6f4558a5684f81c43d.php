<?php $__currentLoopData = \App\HomeCategory::where('status', 1)->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $homeCategory): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <section class="mb-4">
        <div class="container">
            <div class="px-2 py-4 p-md-4 bg-white shadow-sm">
                <div class="section-title-1 clearfix">
                    <h3 class="heading-5 strong-700 mb-0 float-lg-left">
                        <span class="mr-4"><?php echo e($homeCategory->category->name); ?></span>
                    </h3>
                    <ul class="inline-links float-lg-right nav mt-3 mb-2 m-lg-0">
                        <?php $__currentLoopData = json_decode($homeCategory->subsubcategories); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $subsubcategory): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if(\App\SubSubCategory::find($subsubcategory) != null): ?>
                                <li class="<?php if($key == 0) echo 'active'; ?>">
                                    <a href="#subsubcat-<?php echo e($subsubcategory); ?>" data-toggle="tab" class="d-block <?php if($key == 0) echo 'active'; ?>"><?php echo e(\App\SubSubCategory::find($subsubcategory)->name); ?></a>
                                </li>
                            <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
                <div class="tab-content">
                    <?php $__currentLoopData = json_decode($homeCategory->subsubcategories); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $subsubcategory): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if(\App\SubSubCategory::find($subsubcategory) != null): ?>
                        <div class="tab-pane fade <?php if($key == 0) echo 'show active'; ?>" id="subsubcat-<?php echo e($subsubcategory); ?>">
                            <div class="row gutters-5 sm-no-gutters">
                                <?php $__currentLoopData = filter_products(\App\Product::where('published', 1)->where('subsubcategory_id', $subsubcategory))->limit(6)->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="col-xl-2 col-lg-3 col-md-4 col-6">
                                        <div class="product-box-2 bg-white alt-box my-2">
                                            <div class="position-relative overflow-hidden">
                                                <a href="<?php echo e(route('product', $product->slug)); ?>" class="d-block product-image h-100 text-center">
                                                    <img class="img-fit lazyload" src="<?php echo e(asset('frontend/images/placeholder.jpg')); ?>" data-src="<?php echo e(asset($product->thumbnail_img)); ?>" alt="<?php echo e(__($product->name)); ?>">
                                                </a>
                                                <div class="product-btns clearfix">
                                                    <button class="btn add-wishlist" title="Add to Wishlist" onclick="addToWishList(<?php echo e($product->id); ?>)" tabindex="0">
                                                        <i class="la la-heart-o"></i>
                                                    </button>
                                                    <button class="btn add-compare" title="Add to Compare" onclick="addToCompare(<?php echo e($product->id); ?>)" tabindex="0">
                                                        <i class="la la-refresh"></i>
                                                    </button>
                                                    <button class="btn quick-view" title="Quick view" onclick="showAddToCartModal(<?php echo e($product->id); ?>)" tabindex="0">
                                                        <i class="la la-eye"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="p-md-3 p-2 border-top">
                                                <h2 class="product-title p-0 text-truncate-2">
                                                    <a href="<?php echo e(route('product', $product->slug)); ?>" tabindex="0"><?php echo e(__($product->name)); ?></a>
                                                </h2>
                                                <div class="star-rating mb-1">
                                                    <?php echo e(renderStarRating($product->rating)); ?>

                                                </div>
                                                <div class="clearfix">
                                                    <div class="price-box float-left">
                                                        <?php if(home_base_price($product->id) != home_discounted_base_price($product->id)): ?>
                                                            <del class="old-product-price strong-400"><?php echo e(home_base_price($product->id)); ?></del>
                                                        <?php endif; ?>
                                                        <span class="product-price strong-600"><?php echo e(home_discounted_base_price($product->id)); ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>
    </section>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php /**PATH C:\Projects\PHP\Ark\resources\views/frontend/partials/home_categories_section.blade.php ENDPATH**/ ?>