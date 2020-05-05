@extends('frontend.layouts.app')

@section('content')
@php
use Illuminate\Support\Facades\DB;
@endphp
    <section class="home-banner-area mb-4">
        <div class="container">
            <div class="row no-gutters position-relative">
                <div class="col-lg-3 position-static order-2 order-lg-0">
                    <div class="category-sidebar">
                        <div class="all-category d-none d-lg-block">
                            <span >{{__('Categories')}}</span>
                            <a href="#">
                                <span class="d-none d-lg-inline-block">{{__('See All')}} ></span>
                            </a>
                        </div>
                        <ul class="categories no-scrollbar">
                            <li class="d-lg-none">
                                <a href="{{ route('categories.all') }}">
                                    <img class="cat-image lazyload" src="{{ asset('frontend/images/placeholder.jpg') }}" data-src="{{ asset('frontend/images/icons/list.png') }}" width="30" alt="{{ __('All Category') }}">
                                    <span class="cat-name">{{__('All')}} <br> {{__('Categories')}}</span>
                                </a>
                            </li>
                            @foreach (\App\Category::all()->take(11) as $key => $category)
                                @php
                                    $brands = array();
                                @endphp
                                <li>
                                    @if(count($category->subcategories)>0 && $category->featured == 1)
                                    <a href="{{ route('products.category', $category->slug) }}">
                                        <img class="cat-image lazyload" src="{{ asset('frontend/images/placeholder.jpg') }}" data-src="{{ asset($category->icon) }}" width="30" alt="{{ __($category->name) }}">
                                        <span class="cat-name">{{ __($category->name) }}</span>
                                    </a>
                                        <div class="sub-cat-menu c-scrollbar">
                                            <div class="sub-cat-main row no-gutters">
                                                <div class="col-9">
                                                    <div class="sub-cat-content">
                                                        <div class="sub-cat-list">
                                                            <div class="card-columns">
                                                                @foreach ($category->subcategories as $subcategory)
                                                                    <div class="card">
                                                                        <ul class="sub-cat-items">
                                                                            <li class="sub-cat-name"><a href="{{ route('products.subcategory', $subcategory->slug) }}">{{ __($subcategory->name) }}</a></li>
                                                                            @foreach ($subcategory->subsubcategories as $subsubcategory)
                                                                                @php
                                                                                    foreach (json_decode($subsubcategory->brands) as $brand) {
                                                                                        if(!in_array($brand, $brands)){
                                                                                            array_push($brands, $brand);
                                                                                        }
                                                                                    }
                                                                                @endphp
                                                                                <li><a href="{{ route('products.subsubcategory', $subsubcategory->slug) }}">{{ __($subsubcategory->name) }}</a></li>
                                                                            @endforeach
                                                                        </ul>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-3" style="display:none!important">
                                                    <div class="sub-cat-brand">
                                                        <ul class="sub-brand-list">
                                                            @foreach ($brands as $brand_id)
                                                                @if(\App\Brand::find($brand_id) != null)
                                                                    <li class="sub-brand-item">
                                                                        <a href="{{ route('products.brand', \App\Brand::find($brand_id)->slug) }}" ><img src="{{ asset('frontend/images/placeholder.jpg') }}" data-src="{{ asset(\App\Brand::find($brand_id)->logo) }}" class="img-fluid lazyload" alt="{{ \App\Brand::find($brand_id)->name }}"></a>
                                                                    </li>
                                                                @endif
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <div class="col-lg-9 order-1 order-lg-0">
                    <div class="home-slide">
                        <div class="home-slide">
                            <div class="slick-carousel" data-slick-arrows="true" data-slick-dots="false" data-slick-autoplay="true">
                                @foreach (\App\Slider::where('published', 1)->get() as $key => $slider)
                                    <div class="" style="height:345px;">
                                        <a href="{{ $slider->link }}" target="_blank">
                                        <img class="d-block w-100 h-100 lazyload" src="{{ asset('frontend/images/placeholder-rect.jpg') }}" data-src="{{ asset($slider->photo) }}" alt="{{ env('APP_NAME')}} promo">
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="trending-category  d-none d-lg-none">
                        <ul>
                            @foreach (\App\Category::where('featured', 1)->get()->take(7) as $key => $category)
                                <li @if ($key == 0) class="active" @endif>
                                    <div class="trend-category-single">
                                        <a href="{{ route('products.category', $category->slug) }}" class="d-block">
                                            <div class="name">{{ __($category->name) }}</div>
                                            <div class="img">
                                                <img src="{{ asset('frontend/images/placeholder.jpg') }}" data-src="{{ asset($category->banner) }}" alt="{{ __($category->name) }}" class="lazyload img-fit">
                                            </div>
                                        </a>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                @php
                    $flash_deal = \App\FlashDeal::where('status', 1)->first();
                @endphp
                @if($flash_deal != null && strtotime(date('d-m-Y')) >= $flash_deal->start_date && strtotime(date('d-m-Y')) <= $flash_deal->end_date)
                  <!-- <div class="col-lg-2 d-none d-lg-block">
                        <div class="flash-deal-box bg-white h-100">
                            <div class="title text-center p-2 gry-bg">
                                <h3 class="heading-6 mb-0">
                                    {{__('Flash Deal')}}
                                    <span class="badge badge-danger">{{__('Hot')}}</span>
                                </h3>
                                <div class="countdown countdown--style-1 countdown--style-1-v1" data-countdown-date="{{ date('m/d/Y', $flash_deal->end_date) }}" data-countdown-label="show"></div>
                            </div>
                            <div class="flash-content c-scrollbar">
                                @foreach ($flash_deal->flash_deal_products as $key => $flash_deal_product)
                                    @php
                                        $product = \App\Product::find($flash_deal_product->product_id);
                                    @endphp
                                    @if ($product != null)
                                        <a href="{{ route('product', $product->slug) }}" class="d-block flash-deal-item">
                                            <div class="row no-gutters align-items-center">
                                                <div class="col">
                                                    <div class="img">
                                                        <img class="lazyload img-fit" src="{{ asset('frontend/images/placeholder.jpg') }}" data-src="{{ asset($product->flash_deal_img) }}" alt="{{ __($product->name) }}">
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="price">
                                                        <span class="d-block">{{ home_discounted_base_price($product->id) }}</span>
                                                        @if(home_base_price($product->id) != home_discounted_base_price($product->id))
                                                            <del class="d-block">{{ home_base_price($product->id) }}</del>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>--> 
                @else
                   <!-- div class="col-lg-2 d-none d-lg-block">
                        <div class="flash-deal-box bg-white h-100">
                            <div class="title text-center p-2 gry-bg">
                                <h3 class="heading-6 mb-0">
                                    {{ __('Todays Deal') }}
                                    <span class="badge badge-danger">{{__('Hot')}}</span>
                                </h3>
                            </div>
                            <div class="flash-content c-scrollbar c-height">
                                @foreach (filter_products(\App\Product::where('published', 1)->where('todays_deal', '1'))->get() as $key => $product)
                                    @if ($product != null)
                                        <a href="{{ route('product', $product->slug) }}" class="d-block flash-deal-item">
                                            <div class="row no-gutters align-items-center">
                                                <div class="col">
                                                    <div class="img">
                                                        <img class="lazyload img-fit" src="{{ asset('frontend/images/placeholder.jpg') }}" data-src="{{ asset($product->flash_deal_img) }}" alt="{{ __($product->name) }}">
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="price">
                                                        <span class="d-block">{{ home_discounted_base_price($product->id) }}</span>
                                                        @if(home_base_price($product->id) != home_discounted_base_price($product->id))
                                                            <del class="d-block">{{ single_price_dashboard($product->id) }}</del>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>-->
                @endif
            </div>
        </div>
    </section>

<section class="mb-3">
	<div class="container d-lg-none">
		<div class="row gutters-10">
			<div class="col-lg-12">
				<div class="section-title-1 clearfix">
					<h3 class="heading-5 strong-700 mb-0 float-left">
						<span class="mr-4">{{__('Categories')}}</span>
					</h3>
					<ul class="float-right inline-links">
						<li>
							<!--<a href="{{ route('categories.all') }}" class="active">{{__('View All Categories')}}</a>-->
						</li>
					</ul>
				</div>
				<div class="row gutters-5">
					@foreach (\App\Category::get() as $category)
					<div class="mb-3 col-12">
						<img onclick="location.href = '{{ route('products.category', $category->slug) }}'" src="{{ asset($category->banner) }}" style="width:100%" class=" d-block c-base-2 box-2 icon-anim">
					</div>
					@endforeach
				</div>
			</div>
			
		</div>




</section>

    <section class="mb-4">
        <div class="container">
            <div class="row gutters-10">
                @foreach (\App\Banner::where('position', 1)->where('published', 1)->get() as $key => $banner)
                    <div class="col-lg-{{ 12/count(\App\Banner::where('position', 1)->where('published', 1)->get()) }}">
                        <div class="media-banner mb-3 mb-lg-0">
                            <a href="{{ $banner->url }}" target="_blank" class="banner-container">
                                <img src="{{ asset('frontend/images/placeholder-rect.jpg') }}" data-src="{{ asset($banner->photo) }}" alt="{{ env('APP_NAME') }} promo" class="img-fluid lazyload">
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <div id="section_featured">
         <div class="" id="section_featured_real_load" style="display:none" :style="{ display: displayStyle}">
            <section class="mb-4">
                <div class="container">
                    <div class="px-2 py-4 p-md-4 bg-white shadow-sm">
                        <div class="section-title-1 clearfix">
                            <h3 class="heading-5 strong-700 mb-0 float-left">
                                <span class="mr-4">{{__('Featured Products')}}</span>
                            </h3>
                        </div>
                        <div class="row sm-no-gutters gutters-5">
		    		{{-- @foreach (filter_products(\App\Product::orderBy('created_at', 'desc'))->paginate(500) as $key => $product) --}}

		    		{{-- @if ($product->category->featured == 1) --}}
		    		<div class="col-xxl-3 col-xl-4 col-lg-3 col-md-4 col-6" v-for="(rowItem, x) in products" v-bind:key="x">
		    			<div class="product-box-2 bg-white alt-box my-md-2">
		    				<div class="position-relative overflow-hidden">
		    					<a v-bind:href="'/product/'+ rowItem.slug" class="d-block product-image h-100 text-center" tabindex="0">
		    						<img class="img-fit" :src="'/public/' + rowItem.thumbnail_img" :alt="rowItem.name" />
		    					</a>
		    					<div class="product-btns clearfix">
		    						<button class="btn add-wishlist" title="Add to Wishlist" onclick="addToWishList(@{{ rowItem.id }})" tabindex="0">
		    							<i class="la la-heart-o"></i>
		    						</button>
		    						<!-- <button class="btn add-compare" title="Add to Compare" onclick="addToCompare(@{{ rowItem.id }})" tabindex="0">
                                                            <i class="la la-refresh"></i>
                                                        </button>
                                                           -->
		    						<button class="btn quick-view" title="Quick view" onclick="showAddToCartModal(@{{ rowItem.id }})" tabindex="0">
		    							<i class="la la-eye"></i>
		    						</button>
		    					</div>
		    				</div>
		    				<div class="p-2 p-md-3 border-top">
		    					<h2 class="product-title p-0 text-truncate">
		    						<a :href="'/product/' + rowItem.slug" tabindex="0">@{{ rowItem.name }}</a>
		    					</h2>
		    					<div class="star-rating mb-1" :inner-html.prop="rowItem.rating | renderStarRating">
		    					</div>
		    					<div class="clearfix">
		    						<div class="price-box float-left">
		    						 <span class="product-price strong-600">@{{rowItem.price_range | single_price_dashboard}}</span>
		    						</div>
		    					</div>
		    				</div>
		    			</div>
		    		</div>
		    		{{-- @endif --}}
                
		    		{{-- @endforeach --}}
		    	</div>
                    </div>
                </div>
            </section>
        </div>
        <div class="" id="section_featured_preloaded" v-if="displayStyle == 'none'">
        </div>
    </div>
    <div id="section_ark_products" class="mb-3">
        <div class="container" id="section_ark_products_real_load" :style="{ display: displayStyle}">

		    <div class="products-box-bar px-2 py-4 p-md-4 bg-white shadow-sm">
		    	<div class="section-title-1 clearfix">
		    		<h3 class="heading-5 strong-700 mb-0 float-left">
		    			<span class="mr-4">Ark Products</span>
		    		</h3>
		    	</div>

		    	<div class="row sm-no-gutters gutters-5">
		    		{{-- @foreach (filter_products(\App\Product::orderBy('created_at', 'desc'))->paginate(500) as $key => $product) --}}

		    		{{-- @if ($product->category->featured == 1) --}}
		    		<div class="col-xxl-3 col-xl-4 col-lg-3 col-md-4 col-6" v-for="(rowItem, x) in products" v-bind:key="x">
		    			<div class="product-box-2 bg-white alt-box my-md-2">
		    				<div class="position-relative overflow-hidden">
		    					<a v-bind:href="'/product/'+ rowItem.slug" class="d-block product-image h-100 text-center" tabindex="0">
		    						<img class="img-fit" :src="'/public/' + rowItem.thumbnail_img" :alt="rowItem.name" />
		    					</a>
		    					<div class="product-btns clearfix">
		    						<button class="btn add-wishlist" title="Add to Wishlist" onclick="addToWishList(@{{ rowItem.id }})" tabindex="0">
		    							<i class="la la-heart-o"></i>
		    						</button>
		    						<!-- <button class="btn add-compare" title="Add to Compare" onclick="addToCompare(@{{ rowItem.id }})" tabindex="0">
                                                            <i class="la la-refresh"></i>
                                                        </button>
                                                           -->
		    						<button class="btn quick-view" title="Quick view" onclick="showAddToCartModal(@{{ rowItem.id }})" tabindex="0">
		    							<i class="la la-eye"></i>
		    						</button>
		    					</div>
		    				</div>
		    				<div class="p-2 p-md-3 border-top">
		    					<h2 class="product-title p-0 text-truncate">
		    						<a :href="'/product/' + rowItem.slug" tabindex="0">@{{ rowItem.name }}</a>
		    					</h2>
		    					<div class="star-rating mb-1" :inner-html.prop="rowItem.rating | renderStarRating">
		    					</div>
		    					<div class="clearfix">
		    						<div class="price-box float-left">
		    						 <span class="product-price strong-600">@{{rowItem.price_range | single_price_dashboard}}</span>
		    						</div>
		    					</div>
		    				</div>
		    			</div>
		    		</div>
		    		{{-- @endif --}}
                
		    		{{-- @endforeach --}}
		    	</div>
		    </div>

        </div>
        <div class="container" id="section_ark_products_preloaded" v-if="displayStyle == 'none'">
        </div>
    </div>
    <div id="section_best_selling" style="display:none!important"></div>

    <div id="section_home_categories">
          <div class="" id="section_home_categories_real_load" style="display:none" :style="{ display: displayStyle}">
            <section class="mb-4" v-for="(rowItem, x) in homeCategory" v-bind:key="x">
                <div class="container">
                    <div class="px-2 py-4 p-md-4 bg-white shadow-sm">
                        <div class="section-title-1 clearfix">
                            <h3 class="heading-5 strong-700 mb-0 float-lg-left">
                                <span class="mr-4">@{{ rowItem.category.name }}</span>
                            </h3>
                            <ul class="inline-links float-lg-right nav mt-3 mb-2 m-lg-0">
                                <li v-for="(rowSubcatItem, y) in rowItem.category.subcategories" v-bind:key="y" class="px-0" style="display:inline">
                                    <a v-for="(rowSubSubcatItem, a) in rowSubcatItem.subsubcategories" v-bind:key="a" :href="'#subsubcat-' + rowSubSubcatItem.id" data-toggle="tab" :class="{active: y == 0 && a == 0, 'mx-1': true}">@{{ rowSubSubcatItem.name }}</a>
                                </li>
                            </ul>
                        </div>
                         <div class="tab-content">
                                <template v-for="(rowSubcatItem, b) in rowItem.category.subcategories" v-bind:key="b">
                                  <div  v-for="(rowSubSubcatItem, w) in rowSubcatItem.subsubcategories" v-bind:key="w" :class="{active: w == 0 && b == 0, 'show': w == 0, 'fade': true, 'tab-pane': true}" :id="'subsubcat-' + rowSubSubcatItem.id" >
                                    <div class="row gutters-5 sm-no-gutters">
                                            <div class="col-xl-2 col-lg-3 col-md-4 col-6" v-for="(rowSubSubcatProdItem, z) in rowSubSubcatItem.products" v-bind:key="z">
                                                <div class="product-box-2 bg-white alt-box my-2">
                                                    <div class="position-relative overflow-hidden">
                                                        <a :href="'/product/' + rowSubSubcatProdItem.slug" class="d-block product-image h-100 text-center">
                                                            <img class="img-fit" :src="'/public/' + rowSubSubcatProdItem.thumbnail_img" :alt="rowSubSubcatProdItem.name">
                                                        </a>
                                                        <div class="product-btns clearfix">
                                                            <button class="btn add-wishlist" title="Add to Wishlist" onclick="addToWishList(@{{ rowSubSubcatProdItem.id }})" tabindex="0">
                                                                <i class="la la-heart-o"></i>
                                                            </button>
                                                            <button class="btn add-compare" title="Add to Compare" onclick="addToCompare(@{{ rowSubSubcatProdItem.id }})" tabindex="0">
                                                                <i class="la la-refresh"></i>
                                                            </button>
                                                            <button class="btn quick-view" title="Quick view" onclick="showAddToCartModal(@{{ rowSubSubcatProdItem.id }})" tabindex="0">
                                                                <i class="la la-eye"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <div class="p-md-3 p-2 border-top">
                                                        <h2 class="product-title p-0 text-truncate-2">
                                                            <a :href="'/product/' + rowSubSubcatProdItem.slug" tabindex="0">@{{ rowSubSubcatProdItem.name }}</a>
                                                        </h2>
                                                        <div class="star-rating mb-1" :inner-html.prop="rowSubSubcatProdItem.rating | renderStarRating">
		    					                        </div>
                                                        <div class="clearfix">
                                                            <div class="price-box float-left">
                                                                <span class="product-price strong-600">@{{rowSubSubcatProdItem.price_range | single_price_dashboard}}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                       </div>
                                    </div>
                                </template >
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <div class="" id="section_home_categories_preloaded" v-if="displayStyle == 'none'">
        </div>
    </div>
    <div id="section_coming_soon">
         <div class="container" id="section_coming_soon_real_load" style="display:none" :style="{ display: displayStyle}">

		    <div class="products-box-bar px-2 py-4 p-md-4 bg-white shadow-sm">
		    	<div class="section-title-1 clearfix">
		    		<h3 class="heading-5 strong-700 mb-0 float-left">
		    			<span class="mr-4">{{__('Coming Soon Products')}}</span>
		    		</h3>
		    	</div>

		    	<div class="row sm-no-gutters gutters-5">
		    		<div class="col-xxl-3 col-xl-4 col-lg-3 col-md-4 col-6" v-for="(rowItem, x) in products" v-bind:key="x">
		    			<div class="product-box-2 bg-white alt-box my-md-2">
		    				<div class="position-relative overflow-hidden">
		    					<a v-bind:href="'/product/'+ rowItem.slug" class="d-block product-image h-100 text-center" tabindex="0">
		    						<img class="img-fit" :src="'/public/' + rowItem.thumbnail_img" :alt="rowItem.name" />
		    					</a>
		    					<div class="product-btns clearfix">
		    						<button class="btn add-wishlist" title="Add to Wishlist" onclick="addToWishList(@{{ rowItem.id }})" tabindex="0">
		    							<i class="la la-heart-o"></i>
		    						</button>
		    						<!-- <button class="btn add-compare" title="Add to Compare" onclick="addToCompare(@{{ rowItem.id }})" tabindex="0">
                                                            <i class="la la-refresh"></i>
                                                        </button>
                                                           -->
		    						<button class="btn quick-view" title="Quick view" onclick="showAddToCartModal(@{{ rowItem.id }})" tabindex="0">
		    							<i class="la la-eye"></i>
		    						</button>
		    					</div>
		    				</div>
		    				<div class="p-2 p-md-3 border-top">
		    					<h2 class="product-title p-0 text-truncate">
		    						<a :href="'/product/' + rowItem.slug" tabindex="0">@{{ rowItem.name }}</a>
		    					</h2>
		    					<div class="star-rating mb-1" :inner-html.prop="rowItem.rating | renderStarRating">
		    					</div>
		    					<div class="clearfix">
		    						<div class="price-box float-left">
		    						 
		    						</div>
		    					</div>
		    				</div>
		    			</div>
		    		</div>
		    	</div>
		    </div>

        </div>
		 <div class="container" id="section_coming_soon_preloaded" v-if="displayStyle == 'none'">
        </div>
    </div>

    

    <section class="mb-4">
        <div class="container">
            <div class="row gutters-10">
                @foreach (\App\Banner::where('position', 2)->where('published', 1)->get() as $key => $banner)
                    <div class="col-lg-{{ 12/count(\App\Banner::where('position', 2)->where('published', 1)->get()) }}">
                        <div class="media-banner mb-3 mb-lg-0">
                            <a href="{{ $banner->url }}" target="_blank" class="banner-container">
                                <img src="{{ asset('frontend/images/placeholder-rect.jpg') }}" data-src="{{ asset($banner->photo) }}" alt="{{ env('APP_NAME') }} promo" class="img-fluid lazyload">
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

   <!-- <div id="section_best_sellers">

    </div>
-->
<script>
console.log("Starting cache preloading..");
if (window.localStorage.section_ark_products != undefined) {
  document.getElementById("section_ark_products_preloaded").innerHTML =
    window.localStorage.section_ark_products;
}
if (window.localStorage.section_featured != undefined) {
  document.getElementById("section_featured_preloaded").innerHTML =
    window.localStorage.section_featured;
    $('#slick_carousel_section_featured').slick('reinit');
}
if (window.localStorage.section_coming_soon != undefined) {
  document.getElementById("section_coming_soon_preloaded").innerHTML =
    window.localStorage.section_coming_soon;
}
if (window.localStorage.section_home_categories != undefined) {
    document.getElementById("section_home_categories_preloaded").innerHTML =
    window.localStorage.section_home_categories;
}
// if (window.localStorage.section_best_selling != undefined) {
// 	$('#section_best_selling').html(window.localStorage.section_best_selling);
// }

// if (window.localStorage.section_best_sellers != undefined) {
// 	$('#section_best_sellers').html(window.localStorage.section_best_sellers);
// }
</script>    

@endsection

@section('script')
    <script type="text/javascript">
        console.log("Starting executing js..");
      
        var section_ark_products = new Vue({
            el: '#section_ark_products',
            data: {
            	message: 'Hello Vue!',
            	productID: '',
                modalData: [],
                displayStyle: 'none',
                products: []
            },
            methods: {
                DispalyPriceRangeData(url = '{{ route('home.section.data.ark_products') }}') {
                    console.log("Fetching Data..");
            		document.body.style.cursor = "progress";
                    axios
                        .get(url)
                        .then(data => {
                            document.body.style.cursor = "default";
                            this.products = data.data;
                            console.log("Rendering Data..");
                             if (this.products != window.localStorage.section_ark_products_data) {
                                window.localStorage.section_ark_products_data = this.products;
                                setTimeout(() => {
                                    window.localStorage.section_ark_products = document.getElementById('section_ark_products_real_load').innerHTML;
                                    this.displayStyle = 'block';
                                }, 100);
                            }
                        });
            	}
            },
            mounted() {
                this.DispalyPriceRangeData();
            },
             filters: {
               columnHead(value) {
                 return value
                   .split("_")
                   .join(" ")
                   .toUpperCase();
               },
               formatTime(value) {
                 if (value) {
                   moment.defaultFormat = "HH:mm:ss";
                   return moment(String(value), moment.defaultFormat).format("LT");
                 }
               },
               formatWeekDays(value, weekDay) {
                 if (value) {
                   value = value.replace("1", "Mon");
                   value = value.replace("2", "Tue");
                   value = value.replace("3", "Wed");
                   value = value.replace("4", "Thu");
                   value = value.replace("5", "Fri");
                   value = value.replace("6", "Sat");
                   value = value.replace("7", "Sun");
                
                   if (weekDay != "") {
                     var weekday = new Array(7);
                     weekday["Monday"] = "M";
                     weekday["Tuesday"] = "T";
                     weekday["Wednesday"] = "W";
                     weekday["Thursday"] = "TH";
                     weekday["Friday"] = "F";
                     weekday["Saturday"] = "S";
                     weekday["Sunday"] = "U";

                     var qr = weekday[weekDay];
                     if (value.includes(qr)) {
                       value = weekDay;
                       value = "Open on <br> " + value;
                     } else {
                       value = "Also Open on <br> " + value;
                     }
                   } else {
                     value = "Open on <br> " + value;
                   }
               
                   return value;
                 }
            	},
               renderStarRating(rating, maxRating = 5) {
                   var fullStar = "<i class = 'fa fa-star active'></i>";
                   var halfStar = "<i class = 'fa fa-star half'></i>";
                   var emptyStar = "<i class = 'fa fa-star'></i>";
                   rating = rating <= maxRating ? rating : maxRating;
                
                   var fullStarCount = parseFloat(rating);
                   var halfStarCount = Math.ceil(rating) - fullStarCount;
                   var emptyStarCount = maxRating - fullStarCount - halfStarCount;
                
                   var html = fullStar.repeat(fullStarCount);
                   html += halfStar.repeat(halfStarCount);
                   html += emptyStar.repeat(emptyStarCount);
                   return html;
               },
               single_price_dashboard(product_price){
                //  console.log(product_price);
            	   var _price_from = product_price.length > 0 ? "₱" + numeral(product_price[product_price.length - 1].unit_price).format('0,0.00') : "₱0";
                   var _price_to = product_price.length > 0 ? '₱' +  numeral(product_price[0].unit_price).format('0,0.00') : "₱0";
                   var price = _price_from != _price_to ? _price_from + ' - ' + _price_to : _price_from;
                
                   return price;
            	}
             }
        });
        
        var section_featured = new Vue({
            el: '#section_featured',
            data: {
                section: 'section_featured',
            	message: 'Hello Vue!',
            	productID: '',
                modalData: [],
                displayStyle: 'none',
                products: []
            },
            methods: {
                DispalyPriceRangeData(url = '{{ route('home.section.data.featured') }}') {
                    console.log("Fetching Data..");
            		document.body.style.cursor = "progress";
                    axios
                        .get(url)
                        .then(data => {
                            document.body.style.cursor = "default";
                            this.products = data.data;
                            console.log("Rendering Data..");
                            if (this.products != window.localStorage.section_featured_data) {
                                 window.localStorage.section_featured_data = this.products;
                                 setTimeout(() => {
                                    this.displayStyle = 'block';
                                    // $('#slick_carousel_' + this.section).slick('reinit');
                                    setTimeout(() => {window.localStorage.section_featured = document.getElementById('section_featured_real_load').innerHTML; }, 500);
                                }, 100);
                            }                           
                        });
            	}
            },
            mounted() {
                this.DispalyPriceRangeData();
            },
             filters: {
               columnHead(value) {
                 return value
                   .split("_")
                   .join(" ")
                   .toUpperCase();
               },
               formatTime(value) {
                 if (value) {
                   moment.defaultFormat = "HH:mm:ss";
                   return moment(String(value), moment.defaultFormat).format("LT");
                 }
               },
               formatWeekDays(value, weekDay) {
                 if (value) {
                   value = value.replace("1", "Mon");
                   value = value.replace("2", "Tue");
                   value = value.replace("3", "Wed");
                   value = value.replace("4", "Thu");
                   value = value.replace("5", "Fri");
                   value = value.replace("6", "Sat");
                   value = value.replace("7", "Sun");
                
                   if (weekDay != "") {
                     var weekday = new Array(7);
                     weekday["Monday"] = "M";
                     weekday["Tuesday"] = "T";
                     weekday["Wednesday"] = "W";
                     weekday["Thursday"] = "TH";
                     weekday["Friday"] = "F";
                     weekday["Saturday"] = "S";
                     weekday["Sunday"] = "U";

                     var qr = weekday[weekDay];
                     if (value.includes(qr)) {
                       value = weekDay;
                       value = "Open on <br> " + value;
                     } else {
                       value = "Also Open on <br> " + value;
                     }
                   } else {
                     value = "Open on <br> " + value;
                   }
               
                   return value;
                 }
            	},
               renderStarRating(rating, maxRating = 5) {
                   var fullStar = "<i class = 'fa fa-star active'></i>";
                   var halfStar = "<i class = 'fa fa-star half'></i>";
                   var emptyStar = "<i class = 'fa fa-star'></i>";
                   rating = rating <= maxRating ? rating : maxRating;
                
                   var fullStarCount = parseFloat(rating);
                   var halfStarCount = Math.ceil(rating) - fullStarCount;
                   var emptyStarCount = maxRating - fullStarCount - halfStarCount;
                
                   var html = fullStar.repeat(fullStarCount);
                   html += halfStar.repeat(halfStarCount);
                   html += emptyStar.repeat(emptyStarCount);
                   return html;
               },
               single_price_dashboard(product_price){
                //  console.log(product_price);
            	   var _price_from = product_price.length > 0 ? "₱" + numeral(product_price[product_price.length - 1].unit_price).format('0,0.00') : "₱0";
                   var _price_to = product_price.length > 0 ? '₱' +  numeral(product_price[0].unit_price).format('0,0.00') : "₱0";
                   var price = _price_from != _price_to ? _price_from + ' - ' + _price_to : _price_from;
                
                   return price;
            	}
             }
        });
        
        var section_coming_soon = new Vue({
            el: '#section_coming_soon',
            data: {
            	message: 'Hello Vue!',
            	productID: '',
                modalData: [],
                displayStyle: 'none',
                products: []
            },
            methods: {
                DispalyPriceRangeData(url = '{{ route('home.section.data.coming_soon') }}') {
                    console.log("Fetching Data..");
            		document.body.style.cursor = "progress";
                    axios
                        .get(url)
                        .then(data => {
                            document.body.style.cursor = "default";
                            this.products = data.data;
                            console.log("Rendering Data..");
                             if (this.products != window.localStorage.section_coming_soon) {
                                window.localStorage.section_coming_soon = this.products;
                                setTimeout(() => {
                                    window.localStorage.section_coming_soon = document.getElementById('section_coming_soon_real_load').innerHTML;
                                    this.displayStyle = 'block';
                                }, 100);
                            }
                        });
            	}
            },
            mounted() {
                this.DispalyPriceRangeData();
            },
             filters: {
               columnHead(value) {
                 return value
                   .split("_")
                   .join(" ")
                   .toUpperCase();
               },
               formatTime(value) {
                 if (value) {
                   moment.defaultFormat = "HH:mm:ss";
                   return moment(String(value), moment.defaultFormat).format("LT");
                 }
               },
               formatWeekDays(value, weekDay) {
                 if (value) {
                   value = value.replace("1", "Mon");
                   value = value.replace("2", "Tue");
                   value = value.replace("3", "Wed");
                   value = value.replace("4", "Thu");
                   value = value.replace("5", "Fri");
                   value = value.replace("6", "Sat");
                   value = value.replace("7", "Sun");
                
                   if (weekDay != "") {
                     var weekday = new Array(7);
                     weekday["Monday"] = "M";
                     weekday["Tuesday"] = "T";
                     weekday["Wednesday"] = "W";
                     weekday["Thursday"] = "TH";
                     weekday["Friday"] = "F";
                     weekday["Saturday"] = "S";
                     weekday["Sunday"] = "U";

                     var qr = weekday[weekDay];
                     if (value.includes(qr)) {
                       value = weekDay;
                       value = "Open on <br> " + value;
                     } else {
                       value = "Also Open on <br> " + value;
                     }
                   } else {
                     value = "Open on <br> " + value;
                   }
               
                   return value;
                 }
            	},
               renderStarRating(rating, maxRating = 5) {
                   var fullStar = "<i class = 'fa fa-star active'></i>";
                   var halfStar = "<i class = 'fa fa-star half'></i>";
                   var emptyStar = "<i class = 'fa fa-star'></i>";
                   rating = rating <= maxRating ? rating : maxRating;
                
                   var fullStarCount = parseFloat(rating);
                   var halfStarCount = Math.ceil(rating) - fullStarCount;
                   var emptyStarCount = maxRating - fullStarCount - halfStarCount;
                
                   var html = fullStar.repeat(fullStarCount);
                   html += halfStar.repeat(halfStarCount);
                   html += emptyStar.repeat(emptyStarCount);
                   return html;
               },
               single_price_dashboard(product_price){
                //  console.log(product_price);
            	   var _price_from = product_price.length > 0 ? "₱" + numeral(product_price[product_price.length - 1].unit_price).format('0,0.00') : "₱0";
                   var _price_to = product_price.length > 0 ? '₱' +  numeral(product_price[0].unit_price).format('0,0.00') : "₱0";
                   var price = _price_from != _price_to ? _price_from + ' - ' + _price_to : _price_from;
                
                   return price;
            	}
             }
        });
        
        var section_home_categories = new Vue({
            el: '#section_home_categories',
            data: {
                section: 'section_home_categories',
            	message: 'Hello Vue!',
            	productID: '',
                modalData: [],
                displayStyle: 'none',
                homeCategory: []
            },
            methods: {
                DispalyPriceRangeData(url = '{{ route('home.section.data.home_categories') }}') {
                    console.log("Fetching Data..");
            		document.body.style.cursor = "progress";
                    axios
                        .get(url)
                        .then(data => {
                            document.body.style.cursor = "default";
                            this.homeCategory = data.data;
                            console.log("Rendering Data..");
                            // if (this.homeCategory != window.localStorage.section_home_categories_data) {
                                 window.localStorage.section_home_categories_data = this.homeCategory;
                                 setTimeout(() => {
                                    this.displayStyle = 'block';
                                    console.log("Rendering section_home_categories..");
                                    $('#slick_carousel_' + this.section).slick('reinit');
                                    setTimeout(() => {window.localStorage.section_home_categories = document.getElementById('section_home_categories_real_load').innerHTML; }, 500);
                                }, 100);
                            // }                           
                        });
            	}
            },
            mounted() {
                this.DispalyPriceRangeData();
            },
             filters: {
               columnHead(value) {
                 return value
                   .split("_")
                   .join(" ")
                   .toUpperCase();
               },
               formatTime(value) {
                 if (value) {
                   moment.defaultFormat = "HH:mm:ss";
                   return moment(String(value), moment.defaultFormat).format("LT");
                 }
               },
               formatWeekDays(value, weekDay) {
                 if (value) {
                   value = value.replace("1", "Mon");
                   value = value.replace("2", "Tue");
                   value = value.replace("3", "Wed");
                   value = value.replace("4", "Thu");
                   value = value.replace("5", "Fri");
                   value = value.replace("6", "Sat");
                   value = value.replace("7", "Sun");
                
                   if (weekDay != "") {
                     var weekday = new Array(7);
                     weekday["Monday"] = "M";
                     weekday["Tuesday"] = "T";
                     weekday["Wednesday"] = "W";
                     weekday["Thursday"] = "TH";
                     weekday["Friday"] = "F";
                     weekday["Saturday"] = "S";
                     weekday["Sunday"] = "U";

                     var qr = weekday[weekDay];
                     if (value.includes(qr)) {
                       value = weekDay;
                       value = "Open on <br> " + value;
                     } else {
                       value = "Also Open on <br> " + value;
                     }
                   } else {
                     value = "Open on <br> " + value;
                   }
               
                   return value;
                 }
            	},
               renderStarRating(rating, maxRating = 5) {
                   var fullStar = "<i class = 'fa fa-star active'></i>";
                   var halfStar = "<i class = 'fa fa-star half'></i>";
                   var emptyStar = "<i class = 'fa fa-star'></i>";
                   rating = rating <= maxRating ? rating : maxRating;
                
                   var fullStarCount = parseFloat(rating);
                   var halfStarCount = Math.ceil(rating) - fullStarCount;
                   var emptyStarCount = maxRating - fullStarCount - halfStarCount;
                
                   var html = fullStar.repeat(fullStarCount);
                   html += halfStar.repeat(halfStarCount);
                   html += emptyStar.repeat(emptyStarCount);
                   return html;
               },
               single_price_dashboard(product_price){
                //  console.log(product_price);
            	   var _price_from = product_price.length > 0 ? "₱" + numeral(product_price[product_price.length - 1].unit_price).format('0,0.00') : "₱0";
                   var _price_to = product_price.length > 0 ? '₱' +  numeral(product_price[0].unit_price).format('0,0.00') : "₱0";
                   var price = _price_from != _price_to ? _price_from + ' - ' + _price_to : _price_from;
                
                   return price;
            	}
             }
        });

        $(window).on("load", function () {
            console.log("Starting content updating..");

            //$('#maintenance-update').modal('show')

            window.setTimeout( function(){

            // $.post('{{ route('home.section.featured') }}', {_token:'{{ csrf_token() }}'}, function(data){
			// 	window.localStorage.section_featured = data;
			// 	$('#section_featured').html(data);
            //     slickInit();
            // });
		
            // $.post('{{ route('home.section.coming_soon') }}', {_token:'{{ csrf_token() }}'}, function(data){
			// 	window.localStorage.section_coming_soon = data;
			// 	$('#section_coming_soon').html(data);
            //     slickInit();
            // });
		
			// $.post('{{ route('home.section.ark_products') }}', { _token: '{{ csrf_token() }}' }, function (data) {
			// 	window.localStorage.section_ark_products = data;
			// 	$('#section_ark_products').html(data);
            //     slickInit();
            // });
		
            // $.post('{{ route('home.section.best_selling') }}', {_token:'{{ csrf_token() }}'}, function(data){
			// 	window.localStorage.section_best_selling = data;
			// 	$('#section_best_selling').html(data);
            //     slickInit();
            // });
		
            // $.post('{{ route('home.section.home_categories') }}', {_token:'{{ csrf_token() }}'}, function(data){
			// 	window.localStorage.section_home_categories = data;
			// 	$('#section_home_categories').html(data);
            //     slickInit();
            // });
		
            // $.post('{{ route('home.section.best_sellers') }}', {_token:'{{ csrf_token() }}'}, function(data){
			// 	window.localStorage.section_best_sellers = data;
			// 	$('#section_best_sellers').html(data);
            //     slickInit();
            // });
                
            },500);

     
        });
	

</script>
@endsection
