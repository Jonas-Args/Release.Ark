    <div class="container">


		<div class="products-box-bar px-2 py-4 p-md-4 bg-white shadow-sm">
			<div class="section-title-1 clearfix">
				<h3 class="heading-5 strong-700 mb-0 float-left">
					<span class="mr-4"><?php echo e(__('Coming Soon Products')); ?></span>
				</h3>
			</div>

			<div class="row sm-no-gutters gutters-5">
				<?php $__currentLoopData = \App\Product::where('category_id', \App\Category::where('name','COMING SOON')->first()->id)->limit(500)->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

				<div class="col-xxl-3 col-xl-4 col-lg-3 col-md-4 col-6">
					<div class="product-box-2 bg-white alt-box my-md-2">
						<div class="position-relative overflow-hidden">
							<a href="<?php echo e(route('product', $product->slug)); ?>" class="d-block product-image h-100 text-center" tabindex="0">
								<img class="img-fit lazyload" src="<?php echo e(asset('frontend/images/placeholder.jpg')); ?>" data-src="<?php echo e(asset($product->thumbnail_img)); ?>" alt="<?php echo e(__($product->name)); ?>" />
							</a>
							<div class="product-btns clearfix">
								<button class="btn add-wishlist" title="Add to Wishlist" onclick="addToWishList(<?php echo e($product->id); ?>)" tabindex="0">
									<i class="la la-heart-o"></i>
								</button>
								
							</div>
						</div>
						<div class="p-2 p-md-3 border-top">
							<h2 class="product-title p-0 text-truncate">
								<a href="<?php echo e(route('product', $product->slug)); ?>" tabindex="0"><?php echo e(__($product->name)); ?></a>
							</h2>
							<div class="star-rating mb-1">
								<?php echo e(renderStarRating($product->rating)); ?>

							</div>
							<div class="clearfix" style="display:none!important">
								<div class="price-box float-left">
									<?php if(home_base_price($product->id) != home_discounted_base_price($product->id)): ?>
									<del class="old-product-price strong-400"><?php echo e(single_price_dashboard($product->id)); ?></del>
									<?php endif; ?>
									<span class="product-price strong-600"><?php echo e(single_price_dashboard($product->id)); ?></span>
								</div>
							</div>
						</div>
					</div>
				</div>
				<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
			</div>
		</div>



        
    </div>
<?php /**PATH C:\Projects\Published\Release.Ark\Ark.FrontEnd.Published\resources\views/frontend/partials/coming_soon_section.blade.php ENDPATH**/ ?>