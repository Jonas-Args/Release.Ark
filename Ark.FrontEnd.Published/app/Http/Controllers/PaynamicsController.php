<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Session;
use Redirect;
use App\Order;
use App\Seller;
use App\Product;
use Razorpay\Api\Api;
use Illuminate\Support\Facades\Input;
use Auth;
use DB;

class PaynamicsController extends Controller
{
    public function initializePayment(Request $request)
    {
		$subtotal = 0;
		$itemcount = 0;
        $initUserBalance = floatval(Auth::user()->balance);
        $Items = [];
        $product_price_less_credit = [];


        foreach (Session::get('cart') as $key1 => $cartItem1){
			$subtotal += $cartItem1['price']*$cartItem1['quantity'];
            $prod_price = $cartItem1['price']*$cartItem1['quantity'];
            $itemcount += $cartItem1['quantity'];
			$product_price_less_credit[$key1] = (1 / $cartItem1['quantity']) * ($prod_price - ($prod_price - $initUserBalance));
            $initUserBalance -= $product_price_less_credit[$key1] * $cartItem1['quantity'];
		}


		foreach (Session::get('cart') as $key => $cartItem){
			$product = Product::find($cartItem['id']);
			$Items_itm = ["itemname" => $product->name,
                      "quantity" => $cartItem['quantity'],
                      "amount" => number_format($cartItem['price'] - $product_price_less_credit[$key], 2, '.', '')];
            array_push($Items,$Items_itm);
		}

        // ADD SHIPPING FEE

		$shipping = 0;
		$total_shipping_points = 0;

		$si = Session::get('shipping_info');
		foreach (Session::get('cart') as $key => $cartItem){
			$product = Product::find($cartItem['id']);

			$product_price = DB::table('product_shipping_points')->where([['product_id', '=', $product->id]])->get();
			$total_shipping_points += $product_price[0]->point_value*$cartItem['quantity'];

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

		$Items_itm = ["itemname" => "Shipping Fee",
				  "quantity" => 1,
				  "amount" => number_format($shipping, 2, '.', '')];
		array_push($Items,$Items_itm);
        $subtotal += $shipping;

		$_s = Session::get('apiSession');

        $data = array(
			'orders' => ["items" => ["Items" => $Items]],
			'amount' => number_format(($subtotal - floatval(Auth::user()->balance)), 2, '.', ''),
			'country' => "PH",
			'mname' => "",
			'state' => $request->session()->get('shipping_info')['country'],
			'city' => $request->session()->get('shipping_info')['city'],
			'address1' => $request->session()->get('shipping_info')['address'],
            'descriptor_note' => 'Sample Note',
            'address2' => 'address 2',
            'client_ip' => $request->ip(),
            'mlogo_url' => url('uploads/logo/logo.png'),
            'mtac_url' => url('aboutus'),
            'pmethod' => '',
            'zip' => 'zip',
			);
		$url = 'http://localhost:55006/api/paynamics';
		$options = array(
			'http' => array(
				'method'  => 'POST',
                'content' => json_encode($data),
				'header'    => "Accept-language: en\r\n" .
					"Cookie: .AspNetCore.Session=". $_s ."\r\n" .
					"Content-type: application/json" . "\r\n"
			)
		);
		$context  = stream_context_create($options);
		$result = file_get_contents($url, false, $context);
		$_r = json_decode($result);
		$_r = $_r->apiResponse;

		return view('frontend.payWithPaynamics', compact('_r'));

    }

    public function payWithRazorpay($request)
    {
        if(Session::has('payment_type')){
            if(Session::get('payment_type') == 'cart_payment'){
                $order = Order::findOrFail(Session::get('order_id'));
                return view('frontend.payWithRazorpay', compact('order'));
            }
            elseif (Session::get('payment_type') == 'seller_payment') {
                $seller = Seller::findOrFail(Session::get('payment_data')['seller_id']);
                return view('razorpay.payWithRazorpay', compact('seller'));
            }
            elseif (Session::get('payment_type') == 'wallet_payment') {
                return view('frontend.razor_wallet.payWithRazorpay');
            }
        }

    }

    public function payment()
    {
        //Input items of form
        $input = Input::all();
        //get API Configuration
        if(Session::get('payment_type') == 'cart_payment' || Session::get('payment_type') == 'wallet_payment'){
            $api = new Api(env('RAZOR_KEY'), env('RAZOR_SECRET'));
        }
        elseif (Session::get('payment_type') == 'seller_payment') {
            $seller = Seller::findOrFail(Session::get('payment_data')['seller_id']);
            $api = new Api($seller->razorpay_api_key, $seller->razorpay_secret);
        }

        //Fetch payment information by razorpay_payment_id
        $payment = $api->payment->fetch($input['razorpay_payment_id']);

        if(count($input)  && !empty($input['razorpay_payment_id'])) {
            $payment_detalis = null;
            try {
                $response = $api->payment->fetch($input['razorpay_payment_id'])->capture(array('amount'=>$payment['amount']));
                $payment_detalis = json_encode(array('id' => $response['id'],'method' => $response['method'],'amount' => $response['amount'],'currency' => $response['currency']));
            } catch (\Exception $e) {
                return  $e->getMessage();
                \Session::put('error',$e->getMessage());
                return redirect()->back();
            }

            // Do something here for store payment details in database...
            if(Session::has('payment_type')){
                if(Session::get('payment_type') == 'cart_payment'){
                    $checkoutController = new CheckoutController;
                    return $checkoutController->checkout_done(Session::get('order_id'), $payment_detalis);
                }
                elseif (Session::get('payment_type') == 'seller_payment') {
                    $commissionController = new CommissionController;
                    return $commissionController->seller_payment_done(Session::get('payment_data'), $payment_detalis);
                }
                elseif (Session::get('payment_type') == 'wallet_payment') {
                    $walletController = new WalletController;
                    return $walletController->wallet_payment_done(Session::get('payment_data'), $payment_detalis);
                }
            }
        }
    }
}
