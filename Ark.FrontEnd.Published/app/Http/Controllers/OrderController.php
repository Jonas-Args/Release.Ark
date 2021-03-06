<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Order;
use App\Product;
use App\Color;
use App\OrderDetail;
use App\CouponUsage;
use Session;
use PDF;
use Mail;
use App\Mail\InvoiceEmailManager;
use App\Wallet;




class OrderController extends Controller
{
    /**
     * Display a listing of the resource to seller.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders = DB::table('orders')
            ->orderBy('code', 'desc')
            ->join('order_details', 'orders.id', '=', 'order_details.order_id')
            ->where('order_details.seller_id', Auth::user()->id)
            ->select('orders.id')
            ->distinct()
            ->paginate(9);

        foreach ($orders as $key => $value) {
            $order = \App\Order::find($value->id);
            $order->viewed = 1;
            $order->save();
        }

        return view('frontend.seller.orders', compact('orders'));
    }

    /**
     * Display a listing of the resource to admin.
     *
     * @return \Illuminate\Http\Response
     */
    public function admin_orders(Request $request)
    {
        $orders = DB::table('orders')
            ->orderBy('code', 'desc')
            ->join('order_details', 'orders.id', '=', 'order_details.order_id')
            ->where('order_details.seller_id', Auth::user()->id)
            ->select('orders.id')
            ->distinct()
            ->get();

        return view('orders.index', compact('orders'));
    }

    /**
     * Display a listing of the sales to admin.
     *
     * @return \Illuminate\Http\Response
     */
    public function sales(Request $request)
    {
        $orders = Order::orderBy('code', 'desc')->get();
        return view('sales.index', compact('orders'));
    }

    public function order_index(Request $request)
    {
        if (Auth::user()->user_type == 'staff') {
            //$orders = Order::where('pickup_point_id', Auth::user()->staff->pick_up_point->id)->get();
            $orders = DB::table('orders')
                ->orderBy('code', 'desc')
                ->join('order_details', 'orders.id', '=', 'order_details.order_id')
                ->where('order_details.pickup_point_id', Auth::user()->staff->pick_up_point->id)
                ->select('orders.id')
                ->distinct()
                ->get();

            return view('pickup_point.orders.index', compact('orders'));
        }
        else {
            //$orders = Order::where('shipping_type', 'Pick-up Point')->get();
            $orders = DB::table('orders')
                ->orderBy('code', 'desc')
                ->join('order_details', 'orders.id', '=', 'order_details.order_id')
                ->where('order_details.shipping_type', 'pickup_point')
                ->select('orders.id')
                ->distinct()
                ->get();

            return view('pickup_point.orders.index', compact('orders'));
        }
    }

    public function pickup_point_order_sales_show($id)
    {
        if (Auth::user()->user_type == 'staff') {
            $order = Order::findOrFail(decrypt($id));
            return view('pickup_point.orders.show', compact('order'));
        }
        else {
            $order = Order::findOrFail(decrypt($id));
            return view('pickup_point.orders.show', compact('order'));
        }
    }

    /**
     * Display a single sale to admin.
     *
     * @return \Illuminate\Http\Response
     */
    public function sales_show($id)
    {
        $order = Order::findOrFail(decrypt($id));
        return view('sales.show', compact('order'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $order = new Order;
        if (Auth::check()) {
            $order->user_id = Auth::user()->id;
        }
        else {
            $order->guest_id = mt_rand(100000, 999999);
        }

        $order->shipping_address = json_encode($request->session()->get('shipping_info'));

        // if (Session::get('delivery_info')['shipping_type'] == 'Home Delivery') {
        //     $order->shipping_type = Session::get('delivery_info')['shipping_type'];
        // }
        // elseif (Session::get('delivery_info')['shipping_type'] == 'Pick-up Point') {
        //     $order->shipping_type = Session::get('delivery_info')['shipping_type'];
        //     $order->pickup_point_id = Session::get('delivery_info')['pickup_point_id'];
        // }
        $shipping_type = Session::get('cart');
        $shipping_type = $shipping_type[0]['shipping_type'];

        $order->payment_type = $request->payment_option;
        $order->delivery_viewed = '0';
        $order->payment_status_viewed = '0';
        $order->code = date('Ymd-his');
        $order->date = strtotime('now');

        if ($order->save()) {
            $subtotal = 0;
            $tax = 0;
            $shipping = 0;
            $total_shipping_points = 0;

            $si = Session::get('shipping_info');
            foreach (Session::get('cart') as $key => $cartItem) {
                $product = Product::find($cartItem['id']);

                $product_price = DB::table('product_shipping_points')->where([['product_id', '=', $product->id]])->get();
                $total_shipping_points += $product_price[0]->point_value * $cartItem['quantity'];

                if ($shipping_type == 'home_delivery') {
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

            foreach (Session::get('cart') as $key => $cartItem) {
                $product = Product::find($cartItem['id']);

                $subtotal += $cartItem['price'] * $cartItem['quantity'];
                $tax += $cartItem['tax'] * $cartItem['quantity'];


                $product_variation = null;
                if (isset($cartItem['color'])) {
                    $product_variation .= Color::where('code', $cartItem['color'])->first()->name;
                }
                foreach (json_decode($product->choice_options) as $choice) {
                    $str = $choice->name; // example $str =  choice_0
                    if ($product_variation != null) {
                        $product_variation .= '-' . str_replace(' ', '', $cartItem[$str]);
                    }
                    else {
                        $product_variation .= str_replace(' ', '', $cartItem[$str]);
                    }
                }

                if ($product_variation != null) {
                    $variations = json_decode($product->variations);
                    $variations->$product_variation->qty -= $cartItem['quantity'];
                    $product->variations = json_encode($variations);
                    $product->save();
                }
                else {
                    $product->current_stock -= $cartItem['quantity'];
                    $product->save();
                }

                $order_detail = new OrderDetail;
                $order_detail->order_id = $order->id;
                $order_detail->seller_id = $product->user_id;
                $order_detail->product_id = $product->id;
                $order_detail->variation = $product_variation;
                $order_detail->price = $cartItem['price'] * $cartItem['quantity'];
                $order_detail->tax = $cartItem['tax'] * $cartItem['quantity'];
                $order_detail->shipping_type = $cartItem['shipping_type'];

                if ($cartItem['shipping_type'] == 'home_delivery') {
                    $order_detail->shipping_cost = $shipping; // \App\Product::find($cartItem['id'])->shipping_cost*$cartItem['quantity'];
                }
                else {
                    $order_detail->shipping_cost = 0;
                    $order_detail->pickup_point_id = $cartItem['pickup_point'];
                }

                $order_detail->quantity = $cartItem['quantity'];
                $order_detail->save();

                $product->num_of_sale++;
                $product->save();
            }

            // PROCCESS COMISSIONS
            $order->grand_total = $subtotal + $shipping;

            if ($request->payment_option == 'wallet')
            {
            //$netValue = ($subtotal / 1.12) * 0.9;

            //$_s = Session::get('apiSession');

            //$url = 'http://localhost:55006/api/user/BusinessPackages';
            //$options = array(
            //    'http' => array(
            //        'method'  => 'GET',
            //        'header'    => "Accept-language: en\r\n" .
            //            "Cookie: .AspNetCore.Session=". $_s ."\r\n"
            //    )
            //);
            //$context  = stream_context_create($options);
            //$result = file_get_contents($url, false, $context);
            //$_r = json_decode($result);

            //if(count($_r->businessPackages) != 0){
            //    if ($_r->businessPackages[0]->packageStatus == "2")
            //    {
            //        $user = Auth::user();
            //        switch($_r->businessPackages[0]->businessPackage->packageCode){
            //            case "EPKG1":
            //                $user->balance += ($netValue * 0.0025);
            //                $_rewards = ($netValue * 0.0025);
            //                $user->save();
            //                break;

            //            case "EPKG2":
            //                $user->balance += ($netValue * 0.005);
            //                $_rewards = ($netValue * 0.005);
            //                $user->save();
            //                break;

            //            case "EPKG3":
            //                $user->balance += ($netValue * 0.01);
            //                $_rewards = ($netValue * 0.01);
            //                $user->save();
            //                break;

            //        }

            //        $wallet = new Wallet;
            //        $wallet->user_id = $user->id;
            //        $wallet->amount = $_rewards;
            //        $wallet->payment_method = 'Product Rebates';
            //        $wallet->payment_details = 'Product Rebates';
            //        $wallet->save();
            //    }

            //}

            //$url = 'http://localhost:55006/api/Affiliate/Commission';
            //$data = array(
            //    'amountPaid' => floatval($netValue)
            //    );

            //// use key 'http' even if you send the request to https://...
            //$options = array(
            //    'http' => array(
            //        'header'  => "Content-type: application/json \r\n" .
            //               "Cookie: .AspNetCore.Session=". $_s ."\r\n",
            //        'method'  => 'POST',
            //        'content' => json_encode($data)
            //    )
            //);
            //$context  = stream_context_create($options);
            //$result = file_get_contents($url, false, $context);
            //$_r = json_decode($result);

            //if ($_r->httpStatusCode == "200")
            //{
            //    foreach ($_r->commission as $commissionItem)
            //    {
            //        //$_userC = DB::table('users')->where('id', $commissionItem->shopUserId)->increment('balance' , floatval($commissionItem->reward));

            //        //$wallet = new Wallet;
            //        //$wallet->user_id = $commissionItem->shopUserId;
            //        //$wallet->amount = $commissionItem->reward;
            //        //$wallet->payment_method = 'Product Commission';
            //        //$wallet->payment_details = 'Product Commission';
            //        //$wallet->save();
            //    }


            //    //flash(__('An error occured: ' . $_r->message))->error();

            //}
            //else{

            //}
            }


            if (Session::has('coupon_discount')) {
                $order->grand_total -= Session::get('coupon_discount');
                $order->coupon_discount = Session::get('coupon_discount');

                $coupon_usage = new CouponUsage;
                $coupon_usage->user_id = Auth::user()->id;
                $coupon_usage->coupon_id = Session::get('coupon_id');
                $coupon_usage->save();
            }

            $order->save();

            //stores the pdf for invoice
            $pdf = PDF::setOptions([
                'isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true,
                'logOutputFile' => storage_path('logs/log.htm'),
                'tempDir' => storage_path('logs/')
            ])->loadView('invoices.customer_invoice', compact('order'));
            //$output = $pdf->output();
            //file_put_contents('public/invoices/'.'Order#'.$order->code.'.pdf', $output);

            $array['view'] = 'emails.invoice';
            $array['subject'] = 'Order Placed - ' . $order->code;
            $array['from'] = env('MAIL_USERNAME');
            $array['content'] = 'Hi. Your order has been placed';
            $array['file'] = 'public/invoices/Order#' . $order->code . '.pdf';
            $array['file_name'] = 'Order#' . $order->code . '.pdf';

            //sends email to customer with the invoice pdf attached
            if (env('MAIL_USERNAME') != null) {
                try {
                // Mail::to($request->session()->get('shipping_info')['email'])->queue(new InvoiceEmailManager($array));
                }
                catch (\Exception $e) {

                }

            }
            // unlink($array['file']);

            $request->session()->put('order_id', $order->id);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $order = Order::findOrFail(decrypt($id));
        $order->viewed = 1;
        $order->save();
        return view('orders.show', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
    //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
    //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        if ($order != null) {
            foreach ($order->orderDetails as $key => $orderDetail) {
                $orderDetail->delete();
            }
            $order->delete();
            flash('Order has been deleted successfully')->success();
        }
        else {
            flash('Something went wrong')->error();
        }
        return back();
    }

    public function order_details(Request $request)
    {
        $order = Order::findOrFail($request->order_id);
        //$order->viewed = 1;
        $order->save();
        return view('frontend.partials.order_details_seller', compact('order'));
    }

    public function update_delivery_status(Request $request)
    {
        $order = Order::findOrFail($request->order_id);
        $order->delivery_viewed = '0';
        $order->save();
        if (Auth::user()->user_type == 'admin' || Auth::user()->user_type == 'seller') {
            foreach ($order->orderDetails->where('seller_id', Auth::user()->id) as $key => $orderDetail) {
                $orderDetail->delivery_status = $request->status;
                $orderDetail->save();
            }
        }
        else {
            foreach ($order->orderDetails->where('seller_id', \App\User::where('user_type', 'admin')->first()->id) as $key => $orderDetail) {
                $orderDetail->delivery_status = $request->status;
                $orderDetail->save();
            }
        }
        return 1;
    }

    public function update_payment_status(Request $request)
    {
        $order = Order::findOrFail($request->order_id);
        $request->status = $request->status == 'unpaid' ? 'paid' : 'unpaid';

        if ($order->payment_status != 'paid')
        {
            $order->payment_status_viewed = '0';
            $order->save();
            if ($order->payment_type == 'cash_on_delivery')
            {
                $CheckoutControllerProccess = new CheckoutController();
                $CheckoutControllerProccess->checkout_done($order->code, "cod", $order->user_id, true);
            }

            if (Auth::user()->user_type == 'admin' || Auth::user()->user_type == 'seller') {
                foreach ($order->orderDetails->where('seller_id', Auth::user()->id) as $key => $orderDetail) {
                    $orderDetail->payment_status = $request->status;
                    $orderDetail->save();
                }
            }
            else {
                foreach ($order->orderDetails->where('seller_id', \App\User::where('user_type', 'admin')->first()->id) as $key => $orderDetail) {
                    $orderDetail->payment_status = $request->status;
                    $orderDetail->save();
                }
            }

            $status = 'paid';
            foreach ($order->orderDetails as $key => $orderDetail) {
                if ($orderDetail->payment_status != 'paid') {
                    $status = 'unpaid';
                }
            }
            $order->payment_status = $status;
            $order->save();
            return 1;
        }
        else {
            return 0;
        }




    }
}
