<?php $__env->startSection('content'); ?>
<?php
use Illuminate\Support\Facades\DB;
?>
    <section class="home-banner-area mb-4">
        <div class="container">
            <div class="row no-gutters position-relative">
                <div class="col-lg-3 position-static order-2 order-lg-0">
                    <div class="category-sidebar">
                        <div class="all-category d-none d-lg-block">
                            <span ><?php echo e(__('Categories')); ?></span>
                            <a href="#">
                                <span class="d-none d-lg-inline-block"><?php echo e(__('See All')); ?> ></span>
                            </a>
                        </div>
                        <ul class="categories no-scrollbar">
                            <li class="d-lg-none">
                                <a href="<?php echo e(route('categories.all')); ?>">
                                    <img class="cat-image lazyload" src="<?php echo e(asset('frontend/images/placeholder.jpg')); ?>" data-src="<?php echo e(asset('frontend/images/icons/list.png')); ?>" width="30" alt="<?php echo e(__('All Category')); ?>">
                                    <span class="cat-name"><?php echo e(__('All')); ?> <br> <?php echo e(__('Categories')); ?></span>
                                </a>
                            </li>
                            <?php $__currentLoopData = \App\Category::all()->take(11); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $brands = array();
                                ?>
                                <li>
                                    <a href="<?php echo e(route('products.category', $category->slug)); ?>">
                                        <img class="cat-image lazyload" src="<?php echo e(asset('frontend/images/placeholder.jpg')); ?>" data-src="<?php echo e(asset($category->icon)); ?>" width="30" alt="<?php echo e(__($category->name)); ?>">
                                        <span class="cat-name"><?php echo e(__($category->name)); ?></span>
                                    </a>
                                    <?php if(count($category->subcategories)>0): ?>
                                        <div class="sub-cat-menu c-scrollbar">
                                            <div class="sub-cat-main row no-gutters">
                                                <div class="col-9">
                                                    <div class="sub-cat-content">
                                                        <div class="sub-cat-list">
                                                            <div class="card-columns">
                                                                <?php $__currentLoopData = $category->subcategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subcategory): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                    <div class="card">
                                                                        <ul class="sub-cat-items">
                                                                            <li class="sub-cat-name"><a href="<?php echo e(route('products.subcategory', $subcategory->slug)); ?>"><?php echo e(__($subcategory->name)); ?></a></li>
                                                                            <?php $__currentLoopData = $subcategory->subsubcategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subsubcategory): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                                <?php
                                                                                    foreach (json_decode($subsubcategory->brands) as $brand) {
                                                                                        if(!in_array($brand, $brands)){
                                                                                            array_push($brands, $brand);
                                                                                        }
                                                                                    }
                                                                                ?>
                                                                                <li><a href="<?php echo e(route('products.subsubcategory', $subsubcategory->slug)); ?>"><?php echo e(__($subsubcategory->name)); ?></a></li>
                                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                        </ul>
                                                                    </div>
                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-3" style="display:none!important">
                                                    <div class="sub-cat-brand">
                                                        <ul class="sub-brand-list">
                                                            <?php $__currentLoopData = $brands; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $brand_id): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <?php if(\App\Brand::find($brand_id) != null): ?>
                                                                    <li class="sub-brand-item">
                                                                        <a href="<?php echo e(route('products.brand', \App\Brand::find($brand_id)->slug)); ?>" ><img src="<?php echo e(asset('frontend/images/placeholder.jpg')); ?>" data-src="<?php echo e(asset(\App\Brand::find($brand_id)->logo)); ?>" class="img-fluid lazyload" alt="<?php echo e(\App\Brand::find($brand_id)->name); ?>"></a>
                                                                    </li>
                                                                <?php endif; ?>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                </div>

                <div class="col-lg-9 order-1 order-lg-0">
                    <div class="home-slide">
                        <div class="home-slide">
                            <div class="slick-carousel" data-slick-arrows="true" data-slick-dots="false" data-slick-autoplay="true">
                                <?php $__currentLoopData = \App\Slider::where('published', 1)->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $slider): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="" style="height:345px;">
                                        <a href="<?php echo e($slider->link); ?>" target="_blank">
                                        <img class="d-block w-100 h-100 lazyload" src="<?php echo e(asset('frontend/images/placeholder-rect.jpg')); ?>" data-src="<?php echo e(asset($slider->photo)); ?>" alt="<?php echo e(env('APP_NAME')); ?> promo">
                                        </a>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    </div>
                    <div class="trending-category  d-none d-lg-none">
                        <ul>
                            <?php $__currentLoopData = \App\Category::where('featured', 1)->get()->take(7); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li <?php if($key == 0): ?> class="active" <?php endif; ?>>
                                    <div class="trend-category-single">
                                        <a href="<?php echo e(route('products.category', $category->slug)); ?>" class="d-block">
                                            <div class="name"><?php echo e(__($category->name)); ?></div>
                                            <div class="img">
                                                <img src="<?php echo e(asset('frontend/images/placeholder.jpg')); ?>" data-src="<?php echo e(asset($category->banner)); ?>" alt="<?php echo e(__($category->name)); ?>" class="lazyload img-fit">
                                            </div>
                                        </a>
                                    </div>
                                </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                </div>

                <?php
                    $flash_deal = \App\FlashDeal::where('status', 1)->first();
                ?>
                <?php if($flash_deal != null && strtotime(date('d-m-Y')) >= $flash_deal->start_date && strtotime(date('d-m-Y')) <= $flash_deal->end_date): ?>
                  <!-- <div class="col-lg-2 d-none d-lg-block">
                        <div class="flash-deal-box bg-white h-100">
                            <div class="title text-center p-2 gry-bg">
                                <h3 class="heading-6 mb-0">
                                    <?php echo e(__('Flash Deal')); ?>

                                    <span class="badge badge-danger"><?php echo e(__('Hot')); ?></span>
                                </h3>
                                <div class="countdown countdown--style-1 countdown--style-1-v1" data-countdown-date="<?php echo e(date('m/d/Y', $flash_deal->end_date)); ?>" data-countdown-label="show"></div>
                            </div>
                            <div class="flash-content c-scrollbar">
                                <?php $__currentLoopData = $flash_deal->flash_deal_products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $flash_deal_product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        $product = \App\Product::find($flash_deal_product->product_id);
                                    ?>
                                    <?php if($product != null): ?>
                                        <a href="<?php echo e(route('product', $product->slug)); ?>" class="d-block flash-deal-item">
                                            <div class="row no-gutters align-items-center">
                                                <div class="col">
                                                    <div class="img">
                                                        <img class="lazyload img-fit" src="<?php echo e(asset('frontend/images/placeholder.jpg')); ?>" data-src="<?php echo e(asset($product->flash_deal_img)); ?>" alt="<?php echo e(__($product->name)); ?>">
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="price">
                                                        <span class="d-block"><?php echo e(home_discounted_base_price($product->id)); ?></span>
                                                        <?php if(home_base_price($product->id) != home_discounted_base_price($product->id)): ?>
                                                            <del class="d-block"><?php echo e(home_base_price($product->id)); ?></del>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    </div>--> 
                <?php else: ?>
                   <!-- div class="col-lg-2 d-none d-lg-block">
                        <div class="flash-deal-box bg-white h-100">
                            <div class="title text-center p-2 gry-bg">
                                <h3 class="heading-6 mb-0">
                                    <?php echo e(__('Todays Deal')); ?>

                                    <span class="badge badge-danger"><?php echo e(__('Hot')); ?></span>
                                </h3>
                            </div>
                            <div class="flash-content c-scrollbar c-height">
                                <?php $__currentLoopData = filter_products(\App\Product::where('published', 1)->where('todays_deal', '1'))->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php if($product != null): ?>
                                        <a href="<?php echo e(route('product', $product->slug)); ?>" class="d-block flash-deal-item">
                                            <div class="row no-gutters align-items-center">
                                                <div class="col">
                                                    <div class="img">
                                                        <img class="lazyload img-fit" src="<?php echo e(asset('frontend/images/placeholder.jpg')); ?>" data-src="<?php echo e(asset($product->flash_deal_img)); ?>" alt="<?php echo e(__($product->name)); ?>">
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="price">
                                                        <span class="d-block"><?php echo e(home_discounted_base_price($product->id)); ?></span>
                                                        <?php if(home_base_price($product->id) != home_discounted_base_price($product->id)): ?>
                                                            <del class="d-block"><?php echo e(single_price_dashboard($product->id)); ?></del>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    </div>-->
                <?php endif; ?>
            </div>
        </div>
    </section>

<section class="mb-3">
	<div class="container d-lg-none">
		<div class="row gutters-10">
			<div class="col-lg-12">
				<div class="section-title-1 clearfix">
					<h3 class="heading-5 strong-700 mb-0 float-left">
						<span class="mr-4"><?php echo e(__('Categories')); ?></span>
					</h3>
					<ul class="float-right inline-links">
						<li>
							<!--<a href="<?php echo e(route('categories.all')); ?>" class="active"><?php echo e(__('View All Categories')); ?></a>-->
						</li>
					</ul>
				</div>
				<div class="row gutters-5">
					<?php $__currentLoopData = \App\Category::get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
					<div class="mb-3 col-12">
						<img onclick="location.href = '<?php echo e(route('products.category', $category->slug)); ?>'" src="<?php echo e(asset($category->banner)); ?>" style="width:100%" class=" d-block c-base-2 box-2 icon-anim">
					</div>
					<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
				</div>
			</div>
			
		</div>




</section>

    <section class="mb-4">
        <div class="container">
            <div class="row gutters-10">
                <?php $__currentLoopData = \App\Banner::where('position', 1)->where('published', 1)->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $banner): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col-lg-<?php echo e(12/count(\App\Banner::where('position', 1)->where('published', 1)->get())); ?>">
                        <div class="media-banner mb-3 mb-lg-0">
                            <a href="<?php echo e($banner->url); ?>" target="_blank" class="banner-container">
                                <img src="<?php echo e(asset('frontend/images/placeholder-rect.jpg')); ?>" data-src="<?php echo e(asset($banner->photo)); ?>" alt="<?php echo e(env('APP_NAME')); ?> promo" class="img-fluid lazyload">
                            </a>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </section>

    <div id="section_featured"></div>
    <div id="section_ark_products" class="mb-3"></div>

<div id="section_best_selling" style="display:none!important"></div>

    <div id="section_home_categories"></div>

    <div id="section_coming_soon">
		
    </div>

    

    <section class="mb-4">
        <div class="container">
            <div class="row gutters-10">
                <?php $__currentLoopData = \App\Banner::where('position', 2)->where('published', 1)->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $banner): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col-lg-<?php echo e(12/count(\App\Banner::where('position', 2)->where('published', 1)->get())); ?>">
                        <div class="media-banner mb-3 mb-lg-0">
                            <a href="<?php echo e($banner->url); ?>" target="_blank" class="banner-container">
                                <img src="<?php echo e(asset('frontend/images/placeholder-rect.jpg')); ?>" data-src="<?php echo e(asset($banner->photo)); ?>" alt="<?php echo e(env('APP_NAME')); ?> promo" class="img-fluid lazyload">
                            </a>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </section>

   <!-- <div id="section_best_sellers">

    </div>
-->
    
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <script type="text/javascript">
		//$('#maintenance-update').modal('show')

		if (window.localStorage.section_featured != undefined) {
			$('#section_featured').html(window.localStorage.section_featured);
		}
            $.post('<?php echo e(route('home.section.featured')); ?>', {_token:'<?php echo e(csrf_token()); ?>'}, function(data){
				window.localStorage.section_featured = data;
				$('#section_featured').html(data);
                slickInit();
            });
		if (window.localStorage.section_coming_soon != undefined) {
			$('#section_coming_soon').html(window.localStorage.section_coming_soon);
		}
            $.post('<?php echo e(route('home.section.coming_soon')); ?>', {_token:'<?php echo e(csrf_token()); ?>'}, function(data){
				window.localStorage.section_coming_soon = data;
				$('#section_coming_soon').html(data);
                slickInit();
            });
		if (window.localStorage.section_ark_products != undefined) {
			$('#section_ark_products').html(window.localStorage.section_ark_products);
		}
			$.post('<?php echo e(route('home.section.ark_products')); ?>', { _token: '<?php echo e(csrf_token()); ?>' }, function (data) {
				window.localStorage.section_ark_products = data;
				$('#section_ark_products').html(data);
                slickInit();
            });
		if (window.localStorage.section_best_selling != undefined) {
			$('#section_best_selling').html(window.localStorage.section_best_selling);
		}
            $.post('<?php echo e(route('home.section.best_selling')); ?>', {_token:'<?php echo e(csrf_token()); ?>'}, function(data){
				window.localStorage.section_best_selling = data;
				$('#section_best_selling').html(data);
                slickInit();
            });
		if (window.localStorage.section_home_categories != undefined) {
			$('#section_home_categories').html(window.localStorage.section_home_categories);
		}
            $.post('<?php echo e(route('home.section.home_categories')); ?>', {_token:'<?php echo e(csrf_token()); ?>'}, function(data){
				window.localStorage.section_home_categories = data;
				$('#section_home_categories').html(data);
                slickInit();
            });
		if (window.localStorage.section_best_sellers != undefined) {
			$('#section_best_sellers').html(window.localStorage.section_best_sellers);
		}
            $.post('<?php echo e(route('home.section.best_sellers')); ?>', {_token:'<?php echo e(csrf_token()); ?>'}, function(data){
				window.localStorage.section_best_sellers = data;
				$('#section_best_sellers').html(data);
                slickInit();
            });

</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Projects\Published\Release.Ark\Ark.FrontEnd.Published\resources\views/frontend/index.blade.php ENDPATH**/ ?>