<div class="card sticky-top">
	<div class="card-title">
		<div class="row align-items-center">
			<div class="col-6">
				<h3 class="heading heading-3 strong-400 mb-0">
					<span><?php echo e(__('Summary')); ?></span>
				</h3>
			</div>

			<div class="col-6 text-right">
				<span class="badge badge-md badge-success"><?php echo e(count(Session::get('cart'))); ?> <?php echo e(__('Items')); ?></span>
			</div>
		</div>
	</div>

	<div class="card-body">
		<table class="table-cart table-cart-review">
			<thead>
				<tr>
					<th class="product-name"><?php echo e(__('Product')); ?></th>
					<th class="product-total text-right"><?php echo e(__('Total')); ?></th>
				</tr>
			</thead>
			<tbody>
				
				<?php
					use Illuminate\Support\Facades\DB;
					$user = Auth::user();
					$subtotal = 0;
					$tax = 0;
					$shipping = 0;
					$total_shipping_points = 0;
					$user_balance = isset($user) ? Auth::user()->balance : 0;
			 ?>

			
		
				<?php $__currentLoopData = Session::get('cart'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $cartItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
					<?php
					

					$product = \App\Product::find($cartItem['id']);
					$product_price = DB::table('product_shipping_points')->where([['product_id', '=', $product->id]])->get();

					$total_shipping_points += $product_price[0]->point_value*$cartItem['quantity'];

					$subtotal += floatval(number_format($cartItem['price'], 2, '.', '')) *$cartItem['quantity'];
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
					?>
					<tr class="cart_item">
						<td class="product-name">
							<?php echo e($product_name_with_choice); ?>

							<strong class="product-quantity">× <?php echo e($cartItem['quantity']); ?></strong>
						</td>
						<td class="product-total text-right">
							<span class="pl-4"><?php echo e(single_price($cartItem['price']*$cartItem['quantity'])); ?></span>
						</td>
					</tr>
				<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

				<?php
			 $si = Session::get('shipping_info');
			 if ($si != null)
			 {
				 //var_dump($total_shipping_points);
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
			 ?>
			</tbody>
		</table>
		<hr />
		<table class="table-cart table-cart-review my-4" style="display:none">
			<thead>
				<tr>
					<th class="product-name"><?php echo e(__('Product Shipping charge')); ?></th>
					<th class="product-total text-right"><?php echo e(__('Amount')); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php $__currentLoopData = Session::get('cart'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $cartItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
					<tr class="cart_item">
						<td class="product-name">
							<?php echo e(\App\Product::find($cartItem['id'])->name); ?>

							<strong class="product-quantity">× <?php echo e($cartItem['quantity']); ?></strong>
						</td>
						<td class="product-total text-right">
							<span class="pl-4"><?php echo e(single_price($cartItem['shipping']*$cartItem['quantity'])); ?></span>
						</td>
					</tr>
				<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
			</tbody>
		</table>

		<table class="table-cart table-cart-review">

			<tfoot>
				<tr class="cart-subtotal">
					<th><?php echo e(__('Total Purchase')); ?></th>
					<td class="text-right">
						<span class="strong-600"><?php echo e(single_price($subtotal)); ?></span>
					</td>
				</tr>

			   

				<tr class="cart-shipping">
					<th><?php echo e(__('Shipping Fee')); ?></th>
					<td class="text-right">
						<span class="text-italic"><?php echo e(single_price($shipping)); ?></span>
					</td>
				</tr>

				<tr class="cart-shipping">
					<th><?php echo e(__('Sub Total')); ?></th>
					<td class="text-right">
						<span class="text-italic"><?php echo e(number_format(floatval(number_format($subtotal,2, '.', '')) + floatval(number_format($shipping, 2, '.', '')), 2)); ?></span>
					</td>
				</tr>

				<tr class="cart-shipping">
					<th></th>
					<td></td>
					<td></td>
					<td></td>
				</tr>

				<tr class="cart-shipping">
					<th><?php echo e(__('Less Available Credit')); ?></th>
					<td class="text-right">
						<span class="text-italic"><?php echo e(floatval($total) < ($user_balance) ? number_format(floatval($total),2) : number_format(floatval($user_balance),2)); ?></span>
					</td>
				</tr>
				
				<?php if(Session::has('coupon_discount')): ?>
					<tr class="cart-shipping">
						<th><?php echo e(__('Coupon Discount')); ?></th>
						<td class="text-right">
							<span class="text-italic"><?php echo e(single_price(Session::get('coupon_discount'))); ?></span>
						</td>
					</tr>
				<?php endif; ?>

			  

				<tr class="cart-total">
					<th><span class="strong-600"><?php echo e(__('Amount To Pay')); ?></span></th>
					<td class="text-right">
						<?php if((floatval($user_balance) - floatval($total)) < 0): ?>

<strong>
	<span><?php echo e(number_format(abs(floatval(number_format($user_balance, 2, '.', '')) - floatval(number_format($total, 2, '.', ''))),2)); ?></span>
</strong>
						<?php else: ?> 
						<strong>
							<span>0</span>
						</strong>
						<?php endif; ?>
					   
					</td>
				</tr>
			</tfoot>
		</table>

		<?php if(Auth::check() && \App\BusinessSetting::where('type', 'coupon_system')->first()->value == 1): ?>
			<?php if(Session::has('coupon_discount')): ?>
				<div class="mt-3">
					<form class="form-inline" action="<?php echo e(route('checkout.remove_coupon_code')); ?>" method="POST" enctype="multipart/form-data">
						<?php echo csrf_field(); ?>
						<div class="form-group flex-grow-1">
							<div class="form-control bg-gray w-100"><?php echo e(\App\Coupon::find(Session::get('coupon_id'))->code); ?></div>
						</div>
						<button type="submit" class="btn btn-base-1"><?php echo e(__('Change Coupon')); ?></button>
					</form>
				</div>
			<?php else: ?>
				<div class="mt-3">
					<form class="form-inline" action="<?php echo e(route('checkout.apply_coupon_code')); ?>" method="POST" enctype="multipart/form-data">
						<?php echo csrf_field(); ?>
						<div class="form-group flex-grow-1">
							<input type="text" class="form-control w-100" name="code" placeholder="Have coupon code? Enter here" required>
						</div>
						<button type="submit" class="btn btn-base-1"><?php echo e(__('Apply')); ?></button>
					</form>
				</div>
			<?php endif; ?>
		<?php endif; ?>

	</div>
</div>
<?php /**PATH C:\Projects\Published\Release.Ark\Ark.FrontEnd.Published\resources\views/frontend/partials/cart_summary.blade.php ENDPATH**/ ?>