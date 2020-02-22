<div class="card sticky-top">
	<div class="card-title">
		<div class="row align-items-center">
			<div class="col-6">
				<h3 class="heading heading-3 strong-400 mb-0">
					<span>{{__('Summary')}}</span>
				</h3>
			</div>

			<div class="col-6 text-right">
				<span class="badge badge-md badge-success">{{ count(Session::get('cart')) }} {{__('Items')}}</span>
			</div>
		</div>
	</div>

	<div class="card-body">
		<table class="table-cart table-cart-review">
			<thead>
				<tr>
					<th class="product-name">{{__('Product')}}</th>
					<th class="product-total text-right">{{__('Total')}}</th>
				</tr>
			</thead>
			<tbody>
				
				@php
					use Illuminate\Support\Facades\DB;
					$user = Auth::user();
					$subtotal = 0;
					$tax = 0;
					$shipping = 0;
					$total_shipping_points = 0;
					$user_balance = isset($user) ? Auth::user()->balance : 0;
			 @endphp

			
		
				@foreach (Session::get('cart') as $key => $cartItem)
					@php
					

					$product = \App\Product::find($cartItem['id']);
					$product_price = DB::table('product_shipping_points')->where([['product_id', '=', $product->id]])->get();

					$total_shipping_points += $product_price[0]->point_value*$cartItem['quantity'];

					$subtotal += $cartItem['price']*$cartItem['quantity'];
					$tax += $cartItem['tax']*$cartItem['quantity'];
					$shipping += $cartItem['shipping']*$cartItem['quantity'];
					$product_name_with_choice = $product->name;
					if(isset($cartItem['color'])){
						$product_name_with_choice .= ' - '.\App\Color::where('code', $cartItem['color'])->first()->name;
					}
					foreach (json_decode($product->choice_options) as $choice){
						$str = $choice->name; // example $str =  choice_0
						$product_name_with_choice .= ' - '.$cartItem[$str];
					}
					@endphp
					<tr class="cart_item">
						<td class="product-name">
							{{ $product_name_with_choice }}
							<strong class="product-quantity">× {{ $cartItem['quantity'] }}</strong>
						</td>
						<td class="product-total text-right">
							<span class="pl-4">{{ single_price($cartItem['price']*$cartItem['quantity']) }}</span>
						</td>
					</tr>
				@endforeach

				@php
			 $si = Session::get('shipping_info');
			 if ($si != null)
			 {
				 var_dump($total_shipping_points);
				 $_psf = DB::table('shipping_fee_type')->where([['range_from', '<=',floatval($total_shipping_points)],['range_to', '>=',floatval($total_shipping_points)],['region', '=',$si['country']]])->get();
				 if ($_psf != null)
				 {
					 $_pt = DB::table('packaging_type')->where([['id', '=',floatval($_psf[0]->packaging_type_id)]])->get();
					 //var_dump($_pt);
					 $shipping = $_pt != null ? $_pt[0]->unit_price : 0;
				 }
				 else{
					 $shipping = 0;
				 }


			 }


			 $total = $subtotal+$tax+$shipping;
			 if(Session::has('coupon_discount')){
				 $total -= Session::get('coupon_discount');
			 }
			 @endphp
			</tbody>
		</table>
		<hr />
		<table class="table-cart table-cart-review my-4" style="display:none">
			<thead>
				<tr>
					<th class="product-name">{{__('Product Shipping charge')}}</th>
					<th class="product-total text-right">{{__('Amount')}}</th>
				</tr>
			</thead>
			<tbody>
				@foreach (Session::get('cart') as $key => $cartItem)
					<tr class="cart_item">
						<td class="product-name">
							{{ \App\Product::find($cartItem['id'])->name }}
							<strong class="product-quantity">× {{ $cartItem['quantity'] }}</strong>
						</td>
						<td class="product-total text-right">
							<span class="pl-4">{{ single_price($cartItem['shipping']*$cartItem['quantity']) }}</span>
						</td>
					</tr>
				@endforeach
			</tbody>
		</table>

		<table class="table-cart table-cart-review">

			<tfoot>
				<tr class="cart-subtotal">
					<th>{{__('Total Purchase')}}</th>
					<td class="text-right">
						<span class="strong-600">{{ single_price($subtotal) }}</span>
					</td>
				</tr>

			   

				<tr class="cart-shipping">
					<th>{{__('Shipping Fee')}}</th>
					<td class="text-right">
						<span class="text-italic">{{ single_price($shipping) }}</span>
					</td>
				</tr>

				<tr class="cart-shipping">
					<th>{{__('Sub Total')}}</th>
					<td class="text-right">
						<span class="text-italic">{{ number_format(floatval($subtotal) + floatval($shipping),2)  }}</span>
					</td>
				</tr>

				<tr class="cart-shipping">
					<th></th>
					<td></td>
					<td></td>
					<td></td>
				</tr>

				<tr class="cart-shipping">
					<th>{{__('Less Available Credit')}}</th>
					<td class="text-right">
						<span class="text-italic">{{ floatval($total) < ($user_balance) ? number_format(floatval($total),2) : number_format(floatval($user_balance),2)}}</span>
					</td>
				</tr>
				
				@if (Session::has('coupon_discount'))
					<tr class="cart-shipping">
						<th>{{__('Coupon Discount')}}</th>
						<td class="text-right">
							<span class="text-italic">{{ single_price(Session::get('coupon_discount')) }}</span>
						</td>
					</tr>
				@endif

			  

				<tr class="cart-total">
					<th><span class="strong-600">{{__('Amount To Pay')}}</span></th>
					<td class="text-right">
						@if ((floatval($user_balance) - floatval($total)) < 0)

<strong>
	<span>{{ number_format(abs(floatval($user_balance) - floatval($total)),2) }}</span>
</strong>
						@else 
						<strong>
							<span>0</span>
						</strong>
						@endif
					   
					</td>
				</tr>
			</tfoot>
		</table>

		@if (Auth::check() && \App\BusinessSetting::where('type', 'coupon_system')->first()->value == 1)
			@if (Session::has('coupon_discount'))
				<div class="mt-3">
					<form class="form-inline" action="{{ route('checkout.remove_coupon_code') }}" method="POST" enctype="multipart/form-data">
						@csrf
						<div class="form-group flex-grow-1">
							<div class="form-control bg-gray w-100">{{ \App\Coupon::find(Session::get('coupon_id'))->code }}</div>
						</div>
						<button type="submit" class="btn btn-base-1">{{__('Change Coupon')}}</button>
					</form>
				</div>
			@else
				<div class="mt-3">
					<form class="form-inline" action="{{ route('checkout.apply_coupon_code') }}" method="POST" enctype="multipart/form-data">
						@csrf
						<div class="form-group flex-grow-1">
							<input type="text" class="form-control w-100" name="code" placeholder="Have coupon code? Enter here" required>
						</div>
						<button type="submit" class="btn btn-base-1">{{__('Apply')}}</button>
					</form>
				</div>
			@endif
		@endif

	</div>
</div>
