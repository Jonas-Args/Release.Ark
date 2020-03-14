<section class="mb-4">
    <div class="container">
        <div class="px-2 py-4 p-md-4 bg-white shadow-sm">
            <div class="section-title-1 clearfix">
                <h3 class="heading-5 strong-700 mb-0 float-left">
                    <span class="mr-4">{{__('Coming Soon Products')}}</span>
                </h3>
            </div>
            <div class="caorusel-box">
                <div class="slick-carousel" data-slick-items="6" data-slick-xl-items="5" data-slick-lg-items="4"  data-slick-md-items="3" data-slick-sm-items="2" data-slick-xs-items="2">
                    @foreach (\App\Product::where('category_id', \App\Category::where('name','COMING SOON')->first()->id)->limit(12)->get() as $key => $product)
                    <div class="product-card-2 card card-product m-2 shop-cards shop-tech">
                        <div class="card-body p-0">

                            <div class="card-image">
                                <a href="{{ route('product', $product->slug) }}" class="d-block">
                                    <img class="img-fit lazyload mx-auto" src="{{ asset('frontend/images/placeholder.jpg') }}" data-src="{{ asset($product->featured_img) }}" alt="{{ __($product->name) }}">
                                </a>
                            </div>

                            <div class="p-md-3 p-2">
                                <div class="price-box">
                                    @if(home_base_price($product->id) != home_discounted_base_price($product->id))
                                    @endif
                                </div>
                                <div class="star-rating star-rating-sm mt-1">
                                    {{ renderStarRating($product->rating) }}
                                </div>
                                <h2 class="product-title p-0 text-truncate-2">
                                    <a href="{{ route('product', $product->slug) }}">{{ __($product->name) }}</a>
                                </h2>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
