<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use Auth;
use App\Category;
use App\Http\Controllers\PaypalController;
use App\Http\Controllers\InstamojoController;
use App\Http\Controllers\StripePaymentController;
use App\Http\Controllers\PublicSslCommerzPaymentController;
use App\Http\Controllers\OrderController;
use App\Order;
use App\BusinessSetting;
use App\Coupon;
use App\CouponUsage;
use App\Product;
use App\Wallet;
use Session;


class CheckoutController extends Controller
{

    public function __construct()
    {
        //
    }

    //check the selected payment gateway and redirect to that controller accordingly
    public function checkout(Request $request)
    {
        $orderController = new OrderController;
        $orderController->store($request);

        $request->session()->put('payment_type', 'cart_payment');

        if($request->session()->get('order_id') != null){
            if($request->payment_option == 'paypal'){
                $paypal = new PaypalController;
                return $paypal->getCheckout();
            }
            elseif ($request->payment_option == 'stripe') {
                $stripe = new StripePaymentController;
                return $stripe->stripe();
            }
            elseif ($request->payment_option == 'sslcommerz') {
                $sslcommerz = new PublicSslCommerzPaymentController;
                return $sslcommerz->index($request);
            }
            elseif ($request->payment_option == 'instamojo') {
                $instamojo = new InstamojoController;
                return $instamojo->pay($request);
            }
            elseif ($request->payment_option == 'razorpay') {
                $razorpay = new RazorpayController;
                return $razorpay->payWithRazorpay($request);
            }
            elseif ($request->payment_option == 'paystack') {
                $paystack = new PaystackController;
                return $paystack->redirectToGateway($request);
            }
            elseif ($request->payment_option == 'voguepay') {
                $voguePay = new VoguePayController;
                return $voguePay->customer_showForm();
            }
            elseif ($request->payment_option == 'paynamics') {

                $PaynamicsController = new PaynamicsController;
                return $PaynamicsController->initializePayment($request);
            }
            elseif ($request->payment_option == 'cash_on_delivery') {
                $order = Order::findOrFail($request->session()->get('order_id'));
				$order->payment_status = 'unpaid';
				$order->save();

                if (BusinessSetting::where('type', 'category_wise_commission')->first()->value != 1) {
                    $commission_percentage = BusinessSetting::where('type', 'vendor_commission')->first()->value;
                    foreach ($order->orderDetails as $key => $orderDetail) {
                        $orderDetail->payment_status = 'unpaid';
                        $orderDetail->save();
                        if($orderDetail->product->user->user_type == 'seller'){
                            $seller = $orderDetail->product->user->seller;
                            $seller->admin_to_pay = $seller->admin_to_pay + ($orderDetail->price*(100-$commission_percentage))/100;
                            $seller->save();
                        }
                    }
                }
                else{
                    foreach ($order->orderDetails as $key => $orderDetail) {
                        $orderDetail->payment_status = 'unpaid';
                        $orderDetail->save();
                        if($orderDetail->product->user->user_type == 'seller'){
                            $commission_percentage = $orderDetail->product->category->commision_rate;
                            $seller = $orderDetail->product->user->seller;
                            $seller->admin_to_pay = $seller->admin_to_pay + ($orderDetail->price*(100-$commission_percentage))/100;
                            $seller->save();
                        }
                    }
                }

                $request->session()->put('cart', collect([]));
                $request->session()->forget('order_id');
                $request->session()->forget('delivery_info');
                $request->session()->forget('coupon_id');
                $request->session()->forget('coupon_discount');

                flash("Your order has been placed successfully")->success();
            	return redirect()->route('home');
            }
            elseif ($request->payment_option == 'wallet') {
                $order = Order::findOrFail($request->session()->get('order_id'));
                $grandTotal = $order->grand_total;

                $user = Auth::user();
                $user->balance -= $grandTotal;
                $user->save();

                $order->wallet_deduction = $grandTotal;
				$order->save();

				//$_user = DB::table('users')->where('id', Auth::user()->id)->decrement('balance', floatval($grandTotal));
				return $this->checkout_done($request->session()->get('order_id'), null, Auth::user()->id, false, true);
            }
        }
    }
    //redirects to this method after a successfull checkout
    public function checkout_done($order_id, $payment, $userId = null, $isExternal = false, $isWallet = false)
    {

		$userc = DB::table('users')->where('id', '=', $userId)->first();
        if ($isExternal)
		{
            $orderObj = DB::table('orders')->where('code', '=', $order_id)->first();
            $order_id = $orderObj->id;
		}
        $order = Order::findOrFail($order_id);
        $order->payment_status = 'paid';
        $order->payment_details = $payment;
        $order->save();

        $shipping = 0;
        $subtotal = 0;
        $netValue = 0;

		foreach ($order->orderDetails as $key => $orderDetail) {
			$orderDetail->payment_status = 'paid';
			$orderDetail->save();
			$shipping = $shipping == 0 ? $orderDetail->shipping_cost : $shipping;
			if($orderDetail->product->user->user_type == 'seller'){
				$commission_percentage = $orderDetail->product->category->commision_rate;
				$seller = $orderDetail->product->user->seller;
				$seller->admin_to_pay = $seller->admin_to_pay + ($orderDetail->price*(100-$commission_percentage))/100;
				$seller->save();
			}
		}

        $subtotal = $order->grand_total - $shipping;
		$netValue = ($subtotal / 1.12) * 0.9;

		$_s = Session::get('apiSession');

        $data = array(
			'ShopUserId' => $userc->id
			);

		$url = 'http://localhost:55006/api/BusinessPackage/UserBusinessPackages';
		$options = array(
			'http' => array(
				'header'  => "Content-type: application/json",
				'method'  => 'POST',
				'content' => json_encode($data)
			)
		);
		$context  = stream_context_create($options);
		$result = file_get_contents($url, false, $context);
		$_r = json_decode($result);

		if(count($_r->businessPackages) != 0){
			if ($_r->businessPackages[0]->packageStatus == "2")
			{
				switch($_r->businessPackages[0]->businessPackage->packageCode){
					case "EPKG1":
						$_rewards = ($netValue * 0.0025);
						break;
                    case "EPKG1TRL":
					    $_rewards = ($netValue * 0.0025);
					    break;

					case "EPKG2":
						$_rewards = ($netValue * 0.005);
						break;

					case "EPKG3":
						$_rewards = ($netValue * 0.01);
						break;
					default:
                        $_rewards = 0;
                        break;

				}

                if ($_rewards > 0)
				{
					$_userC = DB::table('users')->where('id', $userc->id)->increment('balance' , floatval($_rewards));

					$wallet = new Wallet;
					$wallet->user_id = $userc->id;
					$wallet->amount = $_rewards;
					$wallet->payment_method = 'Product Rebates';
					$wallet->payment_details = 'Product Rebates';
					$wallet->save();
				}



			}

		}

		$url = 'http://localhost:55006/api/Affiliate/Commission';
		$data = array(
			'amountPaid' => floatval($netValue),
			'ShopUserId' => $userc->id
			);

		// use key 'http' even if you send the request to https://...
		$options = array(
			'http' => array(
				'header'  => "Content-type: application/json \r\n",
				'method'  => 'POST',
				'content' => json_encode($data)
			)
		);
		$context  = stream_context_create($options);
		$result = file_get_contents($url, false, $context);
		$_r = json_decode($result);

		if ($_r->httpStatusCode == "200")
		{
			foreach ($_r->commission as $commissionItem)
			{
				//$_userC = DB::table('users')->where('id', $commissionItem->shopUserId)->increment('balance' , floatval($commissionItem->reward));

				//$wallet = new Wallet;
				//$wallet->user_id = $commissionItem->shopUserId;
				//$wallet->amount = $commissionItem->reward;
				//$wallet->payment_method = 'Product Commission';
				//$wallet->payment_details = 'Product Commission';
				//$wallet->save();
			}


			//flash(__('An error occured: ' . $_r->message))->error();

		}
		else{

		}

		//if ($isWallet || $isExternal)
		//{
		//    $grandTotal = $order->grand_total;
		//    $_user = DB::table('users')->where('id', $userc->id)->decrement('balance', floatval($grandTotal));
		//}


        Session::put('cart', collect([]));
        Session::forget('order_id');
        Session::forget('payment_type');
        Session::forget('delivery_info');
        Session::forget('coupon_id');
        Session::forget('coupon_discount');

        if ($isExternal)
		{
            return response('Callback Successful', 200)->header('Content-Type', 'text/plain');
		}
        else{
            flash(__('Payment completed'))->success();
			return redirect()->route('home');
		}
    }

    public function checkout_failed($order_id, $payment, $isExternal = false)
    {
        if ($isExternal)
		{
            $orderObj = DB::table('orders')->where('code', '=', $order_id)->first();
            $order_id = $orderObj->id;
		}
        $order = Order::findOrFail($order_id);
        $order->payment_status = 'failed';
        $order->payment_details = $payment;
        $order->save();

        $_user = DB::table('users')->where('id', $order->user_id)->increment('balance', floatval($order->wallet_deduction));

        if (BusinessSetting::where('type', 'category_wise_commission')->first()->value != 1) {
            foreach ($order->orderDetails as $key => $orderDetail) {
                $orderDetail->payment_status = 'failed';
                $orderDetail->save();
            }
        }
        else{
            foreach ($order->orderDetails as $key => $orderDetail) {
                $orderDetail->payment_status = 'failed';
                $orderDetail->save();
            }
        }

        Session::put('cart', collect([]));
        Session::forget('order_id');
        Session::forget('payment_type');
        Session::forget('delivery_info');
        Session::forget('coupon_id');
        Session::forget('coupon_discount');

		if ($isExternal)
		{
            return response('Callback Successful: Transaction Failed', 200)->header('Content-Type', 'text/plain');
		}
    }

    public function checkout_cancelled($order_id, $payment, $isExternal = false)
    {
        if ($isExternal)
		{
            $orderObj = DB::table('orders')->where('code', '=', $order_id)->first();
            $order_id = $orderObj->id;
		}
        $order = Order::findOrFail($order_id);
        $order->payment_status = 'cancelled';
        $order->payment_details = $payment;
        $order->save();

		//$_user = DB::table('users')->where('id', $order->user_id)->increment('balance', floatval($order->wallet_deduction));

        if (BusinessSetting::where('type', 'category_wise_commission')->first()->value != 1) {
            foreach ($order->orderDetails as $key => $orderDetail) {
                $orderDetail->payment_status = 'cancelled';
                $orderDetail->save();
            }
        }
        else{
            foreach ($order->orderDetails as $key => $orderDetail) {
                $orderDetail->payment_status = 'cancelled';
                $orderDetail->save();
            }
        }

        Session::put('cart', collect([]));
        Session::forget('order_id');
        Session::forget('payment_type');
        Session::forget('delivery_info');
        Session::forget('coupon_id');
        Session::forget('coupon_discount');

        if ($isExternal)
		{
            return response('Callback Successful: Transaction Cancelled', 200)->header('Content-Type', 'text/plain');
		}
    }

    public function checkout_pending($order_id, $payment, $isExternal = false)
    {
		if ($isExternal)
		{
            $orderObj = DB::table('orders')->where('code', '=', $order_id)->first();
            $order_id = $orderObj->id;
		}
        $order = Order::findOrFail($order_id);
        $order->payment_status = 'pending';
        $order->payment_details = $payment;
        $order->save();

        if (BusinessSetting::where('type', 'category_wise_commission')->first()->value != 1) {
            foreach ($order->orderDetails as $key => $orderDetail) {
                $orderDetail->payment_status = 'pending';
                $orderDetail->save();
            }
        }
        else{
            foreach ($order->orderDetails as $key => $orderDetail) {
                $orderDetail->payment_status = 'pending';
                $orderDetail->save();
            }
        }

        Session::put('cart', collect([]));
        Session::forget('order_id');
        Session::forget('payment_type');
        Session::forget('delivery_info');
        Session::forget('coupon_id');
        Session::forget('coupon_discount');

        if ($isExternal)
		{
            return response('Callback Successful: Transaction Pending', 200)->header('Content-Type', 'text/plain');
		}
    }

    public function get_shipping_info(Request $request)
    {
        if(Session::has('cart') && count(Session::get('cart')) > 0){
            $categories = Category::all();
            return view('frontend.shipping_info', compact('categories'));
        }
        flash(__('Your cart is empty'))->success();
        return back();
    }

    public function store_shipping_info(Request $request)
    {
        $data['name'] = $request->name;
        $data['email'] = $request->email;
        $data['address'] = $request->address;
        $data['country'] = $request->country;
        $data['city'] = $request->city;
        $data['postal_code'] = $request->postal_code;
        $data['phone'] = $request->phone;
        $data['checkout_type'] = $request->checkout_type;

        $shipping_info = $data;
        $request->session()->put('shipping_info', $shipping_info);

        $subtotal = 0;
        $tax = 0;
        $shipping = 0;
        foreach (Session::get('cart') as $key => $cartItem){
            $subtotal += $cartItem['price']*$cartItem['quantity'];
            $tax += $cartItem['tax']*$cartItem['quantity'];
            $shipping += $cartItem['shipping']*$cartItem['quantity'];
        }

        $total = $subtotal + $tax + $shipping;

        if(Session::has('coupon_discount')){
                $total -= Session::get('coupon_discount');
        }

        return view('frontend.delivery_info');
        // return view('frontend.payment_select', compact('total'));
    }

    public function store_delivery_info(Request $request)
    {
        if(Session::has('cart') && count(Session::get('cart')) > 0){
            $cart = $request->session()->get('cart', collect([]));
            $cart = $cart->map(function ($object, $key) use ($request) {
                if(\App\Product::find($object['id'])->added_by == 'admin'){
                    if($request['shipping_type_admin'] == 'home_delivery'){
                        $object['shipping_type'] = 'home_delivery';
                        $object['shipping'] = \App\Product::find($object['id'])->shipping_cost;
                    }
                    else{
                        $object['shipping_type'] = 'pickup_point';
                        $object['pickup_point'] = $request->pickup_point_id_admin;
                    }
                }
                else{
                    if($request['shipping_type_'.\App\Product::find($object['id'])->user_id] == 'home_delivery'){
                        $object['shipping_type'] = 'home_delivery';
                        $object['shipping'] = \App\Product::find($object['id'])->shipping_cost;
                    }
                    else{
                        $object['shipping_type'] = 'pickup_point';
                        $object['pickup_point'] = $request['pickup_point_id_'.\App\Product::find($object['id'])->user_id];
                    }
                }
                return $object;
            });

            $request->session()->put('cart', $cart);

            $total_shipping_points = 0;
            $subtotal = 0;
            $tax = 0;
            $shipping = 0;

            foreach (Session::get('cart') as $key => $cartItem){
				$product = \App\Product::find($cartItem['id']);
				$product_price = DB::table('product_shipping_points')->where([['product_id', '=', $product->id]])->get();
				$total_shipping_points += $product_price[0]->point_value*$cartItem['quantity'];

                $subtotal += $cartItem['price']*$cartItem['quantity'];
                $tax += $cartItem['tax']*$cartItem['quantity'];
                $shipping += $cartItem['shipping']*$cartItem['quantity'];
            }

            $si = Session::get('shipping_info');
			if ($si != null)
			{
				$_psf = DB::table('shipping_fee_type')->where([['range_from', '<=',floatval($total_shipping_points)],['range_to', '>=',floatval($total_shipping_points)],['region', '=',$si['country']]])->get();
				if ($_psf != null)
				{
					$_pt = DB::table('packaging_type')->where([['id', '=',floatval($_psf[0]->packaging_type_id)]])->get();
					$shipping = $_pt != null ? $_pt[0]->unit_price : 0;
				}
				else{
					$shipping = 0;
				}
			}

            $total = $subtotal + $shipping;

            if(Session::has('coupon_discount')){
                    $total -= Session::get('coupon_discount');
            }

            //dd($total);

            return view('frontend.payment_select', compact('total'));
        }
        else {
            flash('Your Cart was empty')->warning();
            return redirect()->route('home');
        }
    }

    public function get_payment_info(Request $request)
    {
        $subtotal = 0;
        $tax = 0;
        $shipping = 0;
        $total_shipping_points = 0;

        foreach (Session::get('cart') as $key => $cartItem){
            $product = \App\Product::find($cartItem['id']);
            $product_price = DB::table('product_shipping_points')->where([['product_id', '=', $product->id]])->get();
			$total_shipping_points += $product_price[0]->point_value*$cartItem['quantity'];

            $subtotal += $cartItem['price']*$cartItem['quantity'];
            $tax += $cartItem['tax']*$cartItem['quantity'];
            //$shipping += $cartItem['shipping']*$cartItem['quantity'];
        }

		$si = Session::get('shipping_info');
		if ($si != null)
		{
			$_psf = DB::table('shipping_fee_type')->where([['range_from', '<=',floatval($total_shipping_points)],['range_to', '>=',floatval($total_shipping_points)],['region', '=',$si['country']]])->get();
			if ($_psf != null)
			{
				$_pt = DB::table('packaging_type')->where([['id', '=',floatval($_psf[0]->packaging_type_id)]])->get();
				$shipping = $_pt != null ? $_pt[0]->unit_price : 0;
			}
			else{
				$shipping = 0;
			}
		}

        $total = $subtotal + $shipping;

        if(Session::has('coupon_discount')){
                $total -= Session::get('coupon_discount');
        }

        return view('frontend.payment_select', compact('total'));
    }

    public function apply_coupon_code(Request $request){
        //dd($request->all());
        $coupon = Coupon::where('code', $request->code)->first();

        if($coupon != null){
            if(strtotime(date('d-m-Y')) >= $coupon->start_date && strtotime(date('d-m-Y')) <= $coupon->end_date){
                if(CouponUsage::where('user_id', Auth::user()->id)->where('coupon_id', $coupon->id)->first() == null){
                    $coupon_details = json_decode($coupon->details);

                    if ($coupon->type == 'cart_base')
                    {
                        $subtotal = 0;
                        $tax = 0;
                        $shipping = 0;
                        foreach (Session::get('cart') as $key => $cartItem)
                        {
                            $subtotal += $cartItem['price']*$cartItem['quantity'];
                            $tax += $cartItem['tax']*$cartItem['quantity'];
                            $shipping += $cartItem['shipping']*$cartItem['quantity'];
                        }
                        $sum = $subtotal+$tax+$shipping;

                        if ($sum > $coupon_details->min_buy) {
                            if ($coupon->discount_type == 'percent') {
                                $coupon_discount =  ($sum * $coupon->discount)/100;
                                if ($coupon_discount > $coupon_details->max_discount) {
                                    $coupon_discount = $coupon_details->max_discount;
                                }
                            }
                            elseif ($coupon->discount_type == 'amount') {
                                $coupon_discount = $coupon->discount;
                            }
                            $request->session()->put('coupon_id', $coupon->id);
                            $request->session()->put('coupon_discount', $coupon_discount);
                            flash('Coupon has been applied')->success();
                        }
                    }
                    elseif ($coupon->type == 'product_base')
                    {
                        $coupon_discount = 0;
                        foreach (Session::get('cart') as $key => $cartItem){
                            foreach ($coupon_details as $key => $coupon_detail) {
                                if($coupon_detail->product_id == $cartItem['id']){
                                    if ($coupon->discount_type == 'percent') {
                                        $coupon_discount += $cartItem['price']*$coupon->discount/100;
                                    }
                                    elseif ($coupon->discount_type == 'amount') {
                                        $coupon_discount += $coupon->discount;
                                    }
                                }
                            }
                        }
                        $request->session()->put('coupon_id', $coupon->id);
                        $request->session()->put('coupon_discount', $coupon_discount);
                        flash('Coupon has been applied')->success();
                    }
                }
                else{
                    flash('You already used this coupon!')->warning();
                }
            }
            else{
                flash('Coupon expired!')->warning();
            }
        }
        else {
            flash('Invalid coupon!')->warning();
        }
        return back();
    }

    public function remove_coupon_code(Request $request){
        $request->session()->forget('coupon_id');
        $request->session()->forget('coupon_discount');
        return back();
    }
}
