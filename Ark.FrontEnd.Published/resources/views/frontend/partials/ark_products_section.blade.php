	<div class="container" id="appViewPort_ArkProducts">
		

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
							<a href="/product/@{{product.slug}}" class="d-block product-image h-100 text-center" tabindex="0">
								<img class="img-fit lazyload" src="/public/frontend/images/placeholder.jpg" data-src="/public/uploads/products/thumbnail/@{{ product.thumbnail_img) }}" alt="@{{product.name}}" />
							</a>
							<div class="product-btns clearfix">
								<button class="btn add-wishlist" title="Add to Wishlist" onclick="addToWishList(@{{ product.id }})" tabindex="0">
									<i class="la la-heart-o"></i>
								</button>
								<!-- <button class="btn add-compare" title="Add to Compare" onclick="addToCompare(@{{ product.id }})" tabindex="0">
                                                        <i class="la la-refresh"></i>
                                                    </button>
                                                       -->
								<button class="btn quick-view" title="Quick view" onclick="showAddToCartModal(@{{ product.id }})" tabindex="0">
									<i class="la la-eye"></i>
								</button>
							</div>
						</div>
						<div class="p-2 p-md-3 border-top">
							<h2 class="product-title p-0 text-truncate">
								<a href="/product/@{{product.slug}}" tabindex="0">@{{ product.name }}</a>
							</h2>
							<div class="star-rating mb-1">
								@{{ product.rating | renderStarRating}}
							</div>
							<div class="clearfix">
								<div class="price-box float-left">
								 <span class="product-price strong-600">@{{product.priceRange | single_price_dashboard}}</span>
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

	<script>	
	var appView_products = new Vue({
		    el: '#appViewPort_ArkProducts',
		    data: {
				message: 'Hello Vue!',
				productID: '',
				modalData: [],
		        products: []
		    },
		    methods: {
		        DispalyPriceRangeData(x, url = '{{ route('home.section.data.ark_products') }}') {
					this.productID = x;
					document.body.style.cursor = "progress";
		            axios
		                .get(url)
		                .then(data => {
							document.body.style.cursor = "default";
		                    this.products = data.data;
		                });
				}
		    },
		    created() {
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

        var fullStarCount = (int)rating;
        var halfStarCount = ceil(rating) - fullStarCount;
        var emptyStarCount = maxRating - fullStarCount - halfStarCount;

        var html = fullStar.repeat(fullStarCount);
        html .= halfStar.repeat(halfStarCount);
        html .= emptyStar.repeat(emptyStarCount);
        return html;
	},
	single_price_dashboard(product_price){
		var _price_from = product_price.length > 0 ? "₱" + product_price[product_price.length - 1].unit_price : "₱0";
        var _price_to = product_price.length > 0 ? '₱' + product_price[0].unit_price : "₱0";
        var price = _price_from != _price_to ? _price_from . ' - ' . _price_to : _price_from;

        return price;
	}
  }
		});
	</script>
