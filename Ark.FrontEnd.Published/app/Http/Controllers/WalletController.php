<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\PaypalController;
use App\Http\Controllers\StripePaymentController;
use App\Http\Controllers\PublicSslCommerzPaymentController;
use App\Http\Controllers\InstamojoController;
use Auth;
use DB;
use Session;
use App\Wallet;
use App\TopupTransaction;

class WalletController extends Controller
{
    public function index()
    {
        $wallets = Wallet::where('user_id', Auth::user()->id)->paginate(9);
        return view('frontend.wallet', compact('wallets'));
    }

    public function recharge(Request $request)
    {
        $data['amount'] = $request->amount;
        $data['payment_method'] = $request->payment_option;

        // dd($data);

        $request->session()->put('payment_type', 'wallet_payment');
        $request->session()->put('payment_data', $data);

        if ($request->payment_option == 'paypal') {
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
            $voguepay = new VoguePayController;
            return $voguepay->customer_showForm();
        }
        elseif ($request->payment_option == 'paynamics') {
            $PaynamicsController = new PaynamicsController;
			return $PaynamicsController->initializePayment($request);
        }
    }

    public function wallet_payment_done($walletTx) {
        $customerController = new CustomerController;
        $payment_data = new Request;

		$payment_data['transaction_amount'] = $walletTx->amount;
		$payment_data['target_wallet'] = "Ark Credits";
		$payment_data['ID'] = $walletTx->user_id;

        if ($walletTx->payment_status != "paid")
		{
            $customerController->top_up_proccess($payment_data);
            return response('Callback Successful', 200)->header('Content-Type', 'text/plain');
		}
		else{
            return response('Callback Successful: Already Paid', 200)->header('Content-Type', 'text/plain');
		}
		//flash(__('Payment completed'))->success();
		//return redirect()->route('wallet.index');

    }

	public function wallet_payment_error($payment_data) {
        $walletTx = TopupTransaction::where('code', '=', $payment_data->code)->first();
		$walletTx->payment_status = "rejected";
        $walletTx->save();

		//flash(__('Payment completed'))->success();
		//return redirect()->route('wallet.index');
		return response('Callback Successful: Error Transaction', 200)->header('Content-Type', 'text/plain');
    }

    public function wallet_payment_pending($payment_data) {
        $walletTx = TopupTransaction::where('code', '=', $payment_data->code)->first();
		$walletTx->payment_status = "pending";
        $walletTx->save();

		//flash(__('Payment completed'))->success();
		//return redirect()->route('wallet.index');
		return response('Callback Successful: Pending', 200)->header('Content-Type', 'text/plain');
    }

    public function wallet_payment_cancel($payment_data) {
        $walletTx = TopupTransaction::where('code', '=', $payment_data->code)->first();
		$walletTx->payment_status = "cancelled";
        $walletTx->save();

		//flash(__('Payment completed'))->success();
		//return redirect()->route('wallet.index');
		return response('Callback Successful: Cancelled', 200)->header('Content-Type', 'text/plain');
    }

    public function wallet_update(Request $payment_data) {
        $user = DB::table('users')->where('id', $payment_data->ShopUserId)->increment('balance', floatval($payment_data->Reward));

        $wallet = new Wallet;
        $wallet->user_id = $payment_data->ShopUserId;
        $wallet->amount = $payment_data->Reward;
        $wallet->payment_method = $payment_data->Remarks;
        $wallet->source_details = $payment_data->Origin;
        $wallet->payment_details = $payment_data->Computation;
        $wallet->save();

        return response('Recharge Success', 200)->header('Content-Type', 'text/plain');
    }

    public function testPayment(Request $payment_data) {
        $user = DB::table('users')->where('id', 1)->update(['testpayment' => $payment_data->paymentresponse]);

        return response('Success', 200)->header('Content-Type', 'text/plain');
    }
}
