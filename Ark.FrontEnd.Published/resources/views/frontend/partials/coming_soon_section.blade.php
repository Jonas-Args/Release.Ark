    <div class="container">


		<div class="products-box-bar px-2 py-4 p-md-4 bg-white shadow-sm">
			<div class="section-title-1 clearfix">
				<h3 class="heading-5 strong-700 mb-0 float-left">
					<span class="mr-4">{{__('Coming Soon Products')}}</span>
				</h3>
			</div>

			<div class="row sm-no-gutters gutters-5">
				@foreach (\App\Product::where('category_id', \App\Category::where('name','COMING SOON')->first()->id)->limit(500)->get() as $key => $product)

				<div class="col-xxl-3 col-xl-4 col-lg-3 col-md-4 col-6">
					<div class="product-box-2 bg-white alt-box my-md-2">
						<div class="position-relative overflow-hidden">
							<a href="{{ route('product', $product->slug) }}" class="d-block product-image h-100 text-center" tabindex="0">
								<img class="img-fit lazyload" src="{{ asset('frontend/images/placeholder.jpg') }}" data-src="{{ asset($product->thumbnail_img) }}" alt="{{ __($product->name) }}" />
							</a>
							<div class="product-btns clearfix">
								<button class="btn add-wishlist" title="Add to Wishlist" onclick="addToWishList({{ $product->id }})" tabindex="0">
									<i class="la la-heart-o"></i>
								</button>
								
							</div>
						</div>
						<div class="p-2 p-md-3 border-top">
							<h2 class="product-title p-0 text-truncate">
								<a href="{{ route('product', $product->slug) }}" tabindex="0">{{ __($product->name) }}</a>
							</h2>
							<div class="star-rating mb-1">
								{{ renderStarRating($product->rating) }}
							</div>
							<div class="clearfix" style="display:none!important">
								<div class="price-box float-left">
									@if(home_base_price($product->id) != home_discounted_base_price($product->id))
									<del class="old-product-price strong-400">{{ single_price_dashboard($product->id)  }}</del>
									@endif
									<span class="product-price strong-600">{{single_price_dashboard($product->id)}}</span>
								</div>
							</div>
						</div>
					</div>
				</div>
				@endforeach
			</div>
		</div>



        
    </div>
