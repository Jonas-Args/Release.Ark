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
use Illuminate\Support\Facades\Log;
use App\OrderDetail;
use App\TopupTransaction;

class PaynamicsController extends Controller
{
	public function initializePayment(Request $request)
	{
		$action = $request['paynamics_action'];

		switch ($action)
		{
			case "package_payment":
				$subtotal = 0.00;
				$itemcount = 0;
				$Items = [];

				$_s = Session::get('apiSession');

				$url = 'http://localhost:55006/api/BusinessPackage/Buy';
				$data = array(
					'BusinessPackageID' => $request['BusinessPackageID'],
					'AmountPaid' => $request['AmountPaid'],
					'FromCurrencyIso3' => $request['FromCurrencyIso3'],
					'DepositStatus' => $request['DepositStatus'],
					'Remarks' => $request['Remarks'],
					'Id' => $request['Id']
				);

				// use key 'http' even if you send the request to https://...
				$options = array(
					'http' => array(
						'header' => "Content-type: application/json",
						'method' => 'POST',
						'content' => json_encode($data)
					)
				);
				$context = stream_context_create($options);
				$results = file_get_contents($url, false, $context);
				$_rs = json_decode($results);




				$url = 'http://localhost:55006/api/BusinessPackage/GetByID?stringId=' . $request['BusinessPackageID'];
				$options = array(
					'http' => array(
						'method' => 'GET',
						'header' => "Accept-language: en\r\n" .
						"Cookie: .AspNetCore.Session=" . $_s . "\r\n"
					)
				);
				$context = stream_context_create($options);
				$result = file_get_contents($url, false, $context);
				$businessPackages = json_decode($result);
				$businessPackages = $businessPackages->businessPackages;

				$Items_itm = ["itemname" => $businessPackages[0]->packageName,
					"quantity" => 1,
					"amount" => number_format($businessPackages[0]->valueTo, 2, '.', '')];
				array_push($Items, $Items_itm);
				$subtotal += floatval($businessPackages[0]->valueTo);
				$data = array(
					'DepositId' => $_rs->userDepositRequests[0]->id,
					'orders' => ["items" => ["Items" => $Items]],
					'amount' => number_format(($subtotal), 2, '.', ''),
					'request_id' => date('Ymd-his'),
					'country' => "PH",
					'mname' => "",
					'state' => $request->session()->get('shipping_info')['country'],
					'city' => $request->session()->get('shipping_info')['city'],
					'address1' => $request->session()->get('shipping_info')['address'],
					'descriptor_note' => 'Sample Note',
					'address2' => 'address 2',
					'client_ip' => $request->ip(),
					'mlogo_url' => 'http://ark.com.ph/public/uploads/logo/ZDTyUE7PUFFK8FUwPxI5mQFVNujByWtHkWfmK2dy.png',
					'mtac_url' => url('aboutus'),
					'pmethod' => '',
					'zip' => 'zip',
				);
				$url = 'http://localhost:55006/api/paynamics';
				$options = array(
					'http' => array(
						'method' => 'POST',
						'content' => json_encode($data),
						'header' => "Accept-language: en\r\n" .
						"Cookie: .AspNetCore.Session=" . $_s . "\r\n" .
						"Content-type: application/json" . "\r\n"
					)
				);
				$context = stream_context_create($options);
				$result = file_get_contents($url, false, $context);
				$_r = json_decode($result);
				$_r = $_r->apiResponse;



				return view('frontend.payWithPaynamics', compact('_r'));

				break;

			case "shop_payment":

				$subtotal = 0.00;
				$itemcount = 0;
				$initUserBalance = floatval(Auth::user()->balance);
				$Items = [];
				$product_price_less_credit = [];


				foreach (Session::get('cart') as $key1 => $cartItem1) {

					$prod_price = $cartItem1['price'] * $cartItem1['quantity'];
					$itemcount += $cartItem1['quantity'];
					$product_price_less_credit[$key1] = (1 / $cartItem1['quantity']) * ($prod_price - ($prod_price - $initUserBalance));
					$initUserBalance -= $product_price_less_credit[$key1] * $cartItem1['quantity'];
				}


				foreach (Session::get('cart') as $key => $cartItem) {
					$product = Product::find($cartItem['id']);
					$subtotal += floatval($cartItem['price'] - $product_price_less_credit[$key]) * $cartItem['quantity'];
					//$Items_itm = ["itemname" => $product->name,
					//		  "quantity" => $cartItem['quantity'],
					//		  "amount" => number_format($cartItem['price'] - floatval(number_format($product_price_less_credit[$key], 2, '.', '')), 2, '.', '')];
					//array_push($Items,$Items_itm);
				}

				// ADD SHIPPING FEE

				$shipping = 0;
				$total_shipping_points = 0;

				$shipping_type = Session::get('cart');
				$shipping_type = $shipping_type[0]['shipping_type'];

				$si = Session::get('shipping_info');
				foreach (Session::get('cart') as $key => $cartItem) {
					if ($shipping_type == 'home_delivery') {
						$product = Product::find($cartItem['id']);

						$product_price = DB::table('product_shipping_points')->where([['product_id', '=', $product->id]])->get();
						$total_shipping_points += $product_price[0]->point_value * $cartItem['quantity'];

						$_psf = DB::table('shipping_fee_type')->where([['range_from', '<=', floatval($total_shipping_points)], ['range_to', '>=', floatval($total_shipping_points)], ['region', '=', $si['country']]])->get();
						if ($_psf != null)
						{
							$_pt = DB::table('packaging_type')->where([['id', '=', floatval($_psf[0]->packaging_type_id)]])->get();
							//var_dump($_pt);
							$shipping = $_pt != null ? $_pt[0]->unit_price : 0;
						}
						else {
							$shipping = 0;
						}
					}
					else {
						$shipping = 0;
					}
				}

				$subtotal += $shipping;
				$Items_itm = ["itemname" => "Ark Transaction",
					"quantity" => 1,
					"amount" => number_format($subtotal, 2, '.', '')];
				array_push($Items, $Items_itm);

				$Items_itm = ["itemname" => "Shipping Fee",
					"quantity" => 1,
					"amount" => number_format($shipping, 2, '.', '')];
				//array_push($Items,$Items_itm);


				$_s = Session::get('apiSession');
				$order = Order::findOrFail($request->session()->get('order_id'));

				$data = array(
					'orders' => ["items" => ["Items" => $Items]],
					'amount' => number_format(($subtotal), 2, '.', ''),
					'request_id' => $order->code,
					'country' => "PH",
					'mname' => "",
					'state' => $request->session()->get('shipping_info')['country'],
					'city' => $request->session()->get('shipping_info')['city'],
					'address1' => $request->session()->get('shipping_info')['address'],
					'descriptor_note' => 'Sample Note',
					'address2' => 'address 2',
					'client_ip' => $request->ip(),
					'mlogo_url' => 'http://ark.com.ph/public/uploads/logo/ZDTyUE7PUFFK8FUwPxI5mQFVNujByWtHkWfmK2dy.png',
					'mtac_url' => url('aboutus'),
					'pmethod' => '',
					'zip' => 'zip',
				);
				$url = 'http://localhost:55006/api/paynamics';
				$options = array(
					'http' => array(
						'method' => 'POST',
						'content' => json_encode($data),
						'header' => "Accept-language: en\r\n" .
						"Cookie: .AspNetCore.Session=" . $_s . "\r\n" .
						"Content-type: application/json" . "\r\n"
					)
				);
				$context = stream_context_create($options);
				$result = file_get_contents($url, false, $context);
				$_r = json_decode($result);
				$_r = $_r->apiResponse;

				$order = Order::findOrFail($request->session()->get('order_id'));
				$user = Auth::user();
				$grandTotal = $user->balance;

				$user->balance -= $grandTotal;
				$user->save();

				$order->wallet_deduction = $grandTotal;
				$order->save();

				return view('frontend.payWithPaynamics', compact('_r'));

				break;

			case "wallet_topup":

				$subtotal = 0.00;
				$itemcount = 0;
				$Items = [];

				$_s = Session::get('apiSession');

				$Items_itm = ["itemname" => "Wallet TopUp",
					"quantity" => 1,
					"amount" => number_format(floatval($request['amount']), 2, '.', '')];
				array_push($Items, $Items_itm);
				$subtotal += floatval($request['amount']);
				$data = array(
					'orders' => ["items" => ["Items" => $Items]],
					'amount' => number_format(($subtotal), 2, '.', ''),
					'request_id' => date('Ymd-his'),
					'country' => "PH",
					'mname' => "",
					'state' => $request->session()->get('shipping_info')['country'],
					'city' => $request->session()->get('shipping_info')['city'],
					'address1' => $request->session()->get('shipping_info')['address'],
					'descriptor_note' => 'Sample Note',
					'address2' => 'address 2',
					'client_ip' => $request->ip(),
					'mlogo_url' => 'http://ark.com.ph/public/uploads/logo/ZDTyUE7PUFFK8FUwPxI5mQFVNujByWtHkWfmK2dy.png',
					'mtac_url' => url('aboutus'),
					'pmethod' => '',
					'zip' => 'zip',
				);
				$url = 'http://localhost:55006/api/paynamics';
				$options = array(
					'http' => array(
						'method' => 'POST',
						'content' => json_encode($data),
						'header' => "Accept-language: en\r\n" .
						"Cookie: .AspNetCore.Session=" . $_s . "\r\n" .
						"Content-type: application/json" . "\r\n"
					)
				);
				$context = stream_context_create($options);
				$result = file_get_contents($url, false, $context);
				$_r = json_decode($result);
				$_r = $_r->apiResponse;

				$topupTransaction = new TopupTransaction;
				$topupTransaction->amount = $data['amount'];
				$topupTransaction->user_id = Auth::user()->id;
				$topupTransaction->payment_type = 'Paynamics';
				$topupTransaction->code = $data['request_id'];
				$topupTransaction->payment_status = 'unpaid';
				$topupTransaction->save();

				return view('frontend.payWithPaynamics', compact('_r'));

				break;
			default:
		}


	}

	public function cancelPayment(Request $request)
	{
		$_cart = Session::get('cart');
		$code =  base64_decode($request['requestid']);
		$walletTx = TopupTransaction::where('code', '=', $code)->first();
		$action = "";

		if ($walletTx != null)
		{
			$action = "wallet_topup";
		}
		else if ($_cart != null){
			$action = "shop_payment";
		}
		else {
			$action = "package_payment";
		}

		switch ($action)
		{
			case "wallet_topup":
				$walletTx->payment_status = "cancelled";
				$walletTx->save();

				flash(__('Transaction Cancelled'))->error();
				return redirect(route('wallet.index'));

				break;
			case "shop_payment":
				$order_id_obj = DB::table('orders')->where([['code', '=', $code]])->get();
				$order_id = $order_id_obj[0]->id;
				$user = Auth::user();
				$user->balance += $order_id_obj[0]->wallet_deduction;
				$user->save();
				$order = Order::findOrFail($order_id);
				$order_details = $order->orderDetails()->getResults()->toArray();
				$order_details_ids = array_column($order_details, 'id');

				OrderDetail::destroy($order_details_ids);
				$order->destroy($order_id);

				flash(__('Transaction Cancelled'))->error();
				return redirect(route('checkout.payment_info'));

				break;
			case "package_payment":
				$data = array(
				'RawBase64' => $request['requestid']
			);

				$url = 'http://localhost:55006/api/paynamics/ProcessCallbackRequestCancel';
				$options = array(
					'http' => array(
						'header' => "Content-type: application/json",
						'method' => 'POST',
						'content' => json_encode($data)
					)
				);
				$context = stream_context_create($options);
				$result = file_get_contents($url, false, $context);
				$_r = json_decode($result);

				flash(__('Transaction Cancelled'))->error();
				return redirect(route('affiliate'));
				break;
			default:
		}

	}

	public function callbackPayment(Request $request)
	{
		$_req = $request->getContent();
		Log::info(json_encode($_req));
		$_req = str_replace("paymentresponse=", "", $_req);

		$rawBase64 =  base64_decode($_req);
		$rawData = $this->namespacedXMLToArray($rawBase64);
		$code = $rawData['application']['request_id'];
		$walletTx = TopupTransaction::where('code', '=', $code)->first();
		$action = "";

		if ($walletTx != null)
		{
			$action = "wallet_topup";
		}
		else{
			$action = "others";
		}

		$data = array(
			'RawBase64' => $_req
		);
		$url = 'http://localhost:55006/api/paynamics/ProcessCallbackRequest';
		$options = array(
			'http' => array(
				'header' => "Content-type: application/json",
				'method' => 'POST',
				'content' => json_encode($data)
			)
		);
		$context = stream_context_create($options);
		$result = file_get_contents($url, false, $context);
		$_r = json_decode($result);

		if ($_r->httpStatusCode == "200")
		{
			$PaynamicsResponse = $_r->paymentResponse;
			$checkoutController = new CheckoutController;
			$walletController = new WalletController;

			if ($PaynamicsResponse->transactionType == "SHOP")
			{
				switch ($action)
				{
					case "wallet_topup":

						switch ($PaynamicsResponse->status)
						{
							case "Success":
								// Transaction Successful
								return $walletController->wallet_payment_done($walletTx);
							case "Error":
								// Transaction Successful with 3DS
								return $walletController->wallet_payment_error($walletTx);
							case "Pending":
								// Transaction Failed
								return $walletController->wallet_payment_pending($walletTx);
							case "Cancelled":
								// Transaction Pending
								return $walletController->wallet_payment_cancel($walletTx);
							default:
								return response('Callback Error or Expired', 500)->header('Content-Type', 'text/plain');
						}

						break;
					case "others":
						switch ($PaynamicsResponse->status)
						{
							case "Success":
								// Transaction Successful
								return $checkoutController->checkout_done($PaynamicsResponse->orderID, $request->rawDetails, $PaynamicsResponse->shopUserId, true);
							case "Error":
								// Transaction Successful with 3DS
								return $checkoutController->checkout_failed($PaynamicsResponse->orderID, $request->rawDetails, true);
							case "Pending":
								// Transaction Failed
								return $checkoutController->checkout_pending($PaynamicsResponse->orderID, $request->rawDetails, true);
							case "Cancelled":
								// Transaction Pending
								return $checkoutController->checkout_cancelled($PaynamicsResponse->orderID, $request->rawDetails, true);
							default:
								return response('Callback Error or Expired', 500)->header('Content-Type', 'text/plain');
						}
						break;
					default:
				}


			}
			else if ($PaynamicsResponse->transactionType == "BP") {
				switch ($PaynamicsResponse->status)
				{
					case "Success":
						// Transaction Successful
						return response('Callback Successful: Transaction Successful', 200)->header('Content-Type', 'text/plain');
					case "Error":
						// Transaction Successful with 3DS
						return response('Callback Successful: Transaction Error', 200)->header('Content-Type', 'text/plain');
					case "Pending":
						// Transaction Failed
						return response('Callback Successful: Transaction Pending', 200)->header('Content-Type', 'text/plain');
					case "Cancelled":
						// Transaction Pending
						return response('Callback Successful: Transaction Cancelled', 200)->header('Content-Type', 'text/plain');
					default:
						return response('Callback Error or Expired', 500)->header('Content-Type', 'text/plain');
				}
			}



		}
		Log::info(json_encode($_r));
		return view('checkout.payment_info');
	}

	public function responsePayment(Request $request)
	{
		$_cart = Session::get('cart');
		if ($_cart != null)
		{
			flash("Your order has been placed successfully")->success();
			return redirect()->route('home');
		//return redirect(route('checkout.payment_info'));
		}
		else {
			flash("Package payment has been placed. Please refresh after few moments.")->success();
			return redirect(route('dashboard'));
		}
	}

	public function payment()
	{
		//Input items of form
		$input = Input::all();
		//get API Configuration
		if (Session::get('payment_type') == 'cart_payment' || Session::get('payment_type') == 'wallet_payment') {
			$api = new Api(env('RAZOR_KEY'), env('RAZOR_SECRET'));
		}
		elseif (Session::get('payment_type') == 'seller_payment') {
			$seller = Seller::findOrFail(Session::get('payment_data')['seller_id']);
			$api = new Api($seller->razorpay_api_key, $seller->razorpay_secret);
		}

		//Fetch payment information by razorpay_payment_id
		$payment = $api->payment->fetch($input['razorpay_payment_id']);

		if (count($input) && !empty($input['razorpay_payment_id'])) {
			$payment_detalis = null;
			try {
				$response = $api->payment->fetch($input['razorpay_payment_id'])->capture(array('amount' => $payment['amount']));
				$payment_detalis = json_encode(array('id' => $response['id'], 'method' => $response['method'], 'amount' => $response['amount'], 'currency' => $response['currency']));
			}
			catch (\Exception $e) {
				return $e->getMessage();
				\Session::put('error', $e->getMessage());
				return redirect()->back();
			}

			// Do something here for store payment details in database...
			if (Session::has('payment_type')) {
				if (Session::get('payment_type') == 'cart_payment') {
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

	function removeNamespaceFromXML( $xml )
	{
		// Because I know all of the the namespaces that will possibly appear in
		// in the XML string I can just hard code them and check for
		// them to remove them
		$toRemove = ['rap', 'turss', 'crim', 'cred', 'j', 'rap-code', 'evic'];
		// This is part of a regex I will use to remove the namespace declaration from string
		$nameSpaceDefRegEx = '(\S+)=["\']?((?:.(?!["\']?\s+(?:\S+)=|[>"\']))+.)["\']?';

		// Cycle through each namespace and remove it from the XML string
		foreach( $toRemove as $remove ) {
			// First remove the namespace from the opening of the tag
			$xml = str_replace('<' . $remove . ':', '<', $xml);
			// Now remove the namespace from the closing of the tag
			$xml = str_replace('</' . $remove . ':', '</', $xml);
			// This XML uses the name space with CommentText, so remove that too
			$xml = str_replace($remove . ':commentText', 'commentText', $xml);
			// Complete the pattern for RegEx to remove this namespace declaration
			$pattern = "/xmlns:{$remove}{$nameSpaceDefRegEx}/";
			// Remove the actual namespace declaration using the Pattern
			$xml = preg_replace($pattern, '', $xml, 1);
		}

		// Return sanitized and cleaned up XML with no namespaces
		return $xml;
	}

	function namespacedXMLToArray($xml)
	{
		// One function to both clean the XML string and return an array
		return json_decode(json_encode(simplexml_load_string($this->removeNamespaceFromXML($xml))), true);
	}
}
