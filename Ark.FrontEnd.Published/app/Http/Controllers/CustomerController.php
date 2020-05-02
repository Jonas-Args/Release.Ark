<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Customer;
use App\User;
use App\Order;
use stdClass;
use App\Wallet;
use App\TopupTransaction;

use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $customers = Customer::orderBy('created_at', 'desc')->get();
        return view('customers.index', compact('customers'));
    }

    public function map_to_table($x, $wallet_name) {
        switch ($wallet_name) {
            case "ark_credits":
                $origin = $x['payment_method'] != "Package Consumables" ? "Shop Purchase" : "Package Purchase";
                return array(
                    "ID" => $x['id'],
                    "Date" => date_format(date_create($x['created_at']), "Y/m/d H:i:s"),
                    "Description" => $x['payment_method'],
                    "Originator" => $x['source_details'],
                    "OriginatorID" => $x['user_id'],
                    "Amount" => number_format($x['amount'], 5),
                    "Computation" => $x['payment_details']);
            case "ark_cash":
                return array(
                    "ID" => $x->id,
                    "Date" => date_format(date_create($x->createdOn), "Y/m/d H:i:s"),
                    "Description" => $x->incomeType->incomeTypeName,
                    "Originator" => $x->userAuth->userInfo->firstName . ' ' . $x->userAuth->userInfo->lastName,
                    "OriginatorID" => $x->userAuth->shopUserId,
                    "Amount" => number_format($x->incomePercentage, 5),
                    "Computation" => $x->remarks);
            default:
                return "";
        }

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function deposits(Request $request)
    {
        $customers = Customer::orderBy('created_at', 'desc')->get();
        return view('customers.deposits');
    }

    public function withdrawal(Request $request)
    {
        return view('customers.withdrawal');
    }

    public function top_up_list(Request $request)
    {
        $txs = TopupTransaction::all();
        return view('customers.topup', compact('txs'));
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
    //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = array(
            'ShopUserId' => $id
        );

        $url = 'http://localhost:55006/api/AdminAccess/SingleUser';
        $options = array(
            'http' => array(
                'method' => 'POST',
                'header' => "Content-type: application/json",
                'content' => json_encode($data)
            )
        );

        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        $_r = json_decode($result);

        $customer = User::where('id', $id)->first();
        $customer->LoginStatus = $_r->user->loginStatus;
        return view('customers.edit', compact('customer'));
    }

    public function wallet($id)
    {
        $data = array(
            'ShopUserId' => $id
        );

        $url = 'http://localhost:55006/api/AdminAccess/SingleUser';
        $options = array(
            'http' => array(
                'method' => 'POST',
                'header' => "Content-type: application/json",
                'content' => json_encode($data)
            )
        );

        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        $_r = json_decode($result);

        $customer = User::where('id', $id)->first();
        $customer->LoginStatus = $_r->user->loginStatus;
        $customer->Uid = $_r->user->uid;
        $customer->wallet_credit = $customer->balance;
        $customer->wallet_cash = $_r->userWallets[array_search('ACW', array_column($_r->userWallets, 'walletCode'))]->balance;

        //$customer->wallet_credit_tr
        //$customer->wallet_credit_sr
        //$customer->wallet_credit_td

        return view('customers.wallet', compact('customer'));
    }

    public function wallet_send($string)
    {

        $_x = $this->MALinkDecode($string);

        $view_bag = new stdClass;
        $view_bag->wallet_name = $_x[1];
        $view_bag->user_id = $_x[0];

        $customer = User::where('id', $view_bag->user_id)->first();
        $view_bag->user_name = $customer->email;
        $view_bag->full_name = $customer->fname . ' ' . $customer->lname;

        switch ($view_bag->wallet_name) {
            case "ark_credits":
                $txs = Wallet::where('user_id', $view_bag->user_id)->get()->toArray();
                $view_bag->balance = $customer->balance;
                $view_bag->wallet_name = "Ark Credits";
                break;
            case "ark_cash":

                $data = array(
                    'ShopUserId' => $view_bag->user_id
                );
                $url = 'http://localhost:55006/api/AdminAccess/SingleUser';
                $options = array(
                    'http' => array(
                        'method' => 'POST',
                        'header' => "Content-type: application/json",
                        'content' => json_encode($data)
                    )
                );
                $context = stream_context_create($options);
                $result = file_get_contents($url, false, $context);
                $_res = json_decode($result);
                $txs = $_res->userIncomeTransactions;

                $view_bag->balance = $_res->userWallets[array_search('ACW', array_column($_res->userWallets, 'walletCode'))]->balance;
                $view_bag->wallet_name = "Ark Cash";
                break;
        }

        return view('customers.wallet.send', compact('view_bag'));
    }

    public function wallet_send_proccess(Request $request)
    {
        $user_source = User::findOrFail($request['ID']);
        // $source_wallet = $request['source_wallet'];
        $target_wallet = $request['target_wallet'];
        $recepient_address = $request['recepient_address'];
        $transaction_amount = $request['transaction_amount'];

        $data = array(
            'Uid' => $recepient_address
        );

        $url = 'http://localhost:55006/api/AdminAccess/SingleUser';
        $options = array(
            'http' => array(
                'method' => 'POST',
                'header' => "Content-type: application/json",
                'content' => json_encode($data)
            )
        );

        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        $_r = json_decode($result);
        if ($_r->httpStatusCode == "500")
        {
            flash("Recipient Source Code Does Not Exist")->error();
            return redirect()->back();
        }
        $_r = $_r->user;

        $user_recepient = User::where('id', $_r->shopUserId)->first();
        switch ($target_wallet) {
            case "Ark Credits":

                if (floatval($transaction_amount) > floatval($user_source->balance)) {
                    flash("Insufficient Balance")->error();
                    return redirect()->back();
                }
                $user_recepient->balance += $transaction_amount;

                $wallet = new Wallet;
                $wallet->user_id = $user_recepient->id;
                $wallet->amount = $transaction_amount;
                $wallet->payment_method = 'Received from' . $user_source->fname . ' ' . $user_source->lname;
                $wallet->source_details = $user_source->fname . ' ' . $user_source->lname;
                $wallet->payment_details = '';
                $wallet->save();
                $user_recepient->save();

                $user_source->balance -= $transaction_amount;

                $wallet = new Wallet;
                $wallet->user_id = $user_source->id;
                $wallet->amount = floatval(-1 * $transaction_amount);
                $wallet->payment_method = 'Sent to ' . $user_recepient->fname . ' ' . $user_recepient->lname;
                $wallet->source_details = $user_source->fname . ' ' . $user_source->lname;
                $wallet->payment_details = '';
                $wallet->save();
                $user_source->save();

                break;
            case "Ark Cash":
                $data = array(
                    'SourceShopId' => $user_source->id,
                    'TargetShopId' => $user_recepient->id,
                    'Amount' => $transaction_amount
                );

                $url = 'http://localhost:55006/api/AdminAccess/TransferBalance';
                $options = array(
                    'http' => array(
                        'method' => 'POST',
                        'header' => "Content-type: application/json",
                        'content' => json_encode($data)
                    )
                );
                $context = stream_context_create($options);
                $result = file_get_contents($url, false, $context);
                $_r = json_decode($result);

                if ($_r->httpStatusCode === "500") { /* Handle error */
                    flash(__($_r->message))->error();
                    return redirect()->back();
                }
                break;

        }

        # code...
        flash("Transaction Successful")->success();
        return redirect()->back();
    }

    public function top_up_proccess(Request $request)
    {
        // $source_wallet = $request['source_wallet'];
        $target_wallet = $request['target_wallet'];
        $recepient_address = $request['ID'];
        $transaction_amount = $request['transaction_amount'];

        $data = array(
            'ShopUserId' => $recepient_address
        );

        $url = 'http://localhost:55006/api/AdminAccess/SingleUser';
        $options = array(
            'http' => array(
                'method' => 'POST',
                'header' => "Content-type: application/json",
                'content' => json_encode($data)
            )
        );

        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        $_r = json_decode($result);
        $_r = $_r->user;

        $user_recepient = User::where('id', $_r->shopUserId)->first();
        switch ($target_wallet) {
            case "Ark Credits":
                $user_recepient->balance += $transaction_amount;

                $wallet = new Wallet;
                $wallet->user_id = $user_recepient->id;
                $wallet->amount = $transaction_amount;
                $wallet->payment_method = 'Balance Top-up';
                $wallet->source_details = $user_recepient->fname . ' ' . $user_recepient->lname;
                ;
                $wallet->payment_details = '';
                $wallet->save();
                $user_recepient->save();

                break;
            case "Ark Cash":
                $data = array(
                    'TargetShopId' => $user_recepient->id,
                    'Amount' => $transaction_amount
                );

                $url = 'http://localhost:55006/api/AdminAccess/IncrementBalance';
                $options = array(
                    'http' => array(
                        'method' => 'POST',
                        'header' => "Content-type: application/json",
                        'content' => json_encode($data)
                    )
                );
                $context = stream_context_create($options);
                $result = file_get_contents($url, false, $context);
                $_r = json_decode($result);
                break;

        }

        # code...
        flash("Transaction Successful")->success();
        return redirect()->back();
    }

    public function convert_proccess(Request $request)
    {
        $target_wallet = $request['target_wallet'];
        $recepient_address = $request['ID'];
        $transaction_amount = $request['transaction_amount'];

        $data = array(
            'ShopUserId' => $recepient_address
        );

        $url = 'http://localhost:55006/api/AdminAccess/SingleUser';
        $options = array(
            'http' => array(
                'method' => 'POST',
                'header' => "Content-type: application/json",
                'content' => json_encode($data)
            )
        );

        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        $_r = json_decode($result);
        $_r = $_r->user;

        $user_recepient = User::where('id', $_r->shopUserId)->first();
        switch ($target_wallet) {
            case "Ark Credits":
                $data = array(
                    'TargetShopId' => $user_recepient->id,
                    'Amount' => $transaction_amount
                );

                $url = 'http://localhost:55006/api/AdminAccess/DecrementBalance';
                $options = array(
                    'http' => array(
                        'method' => 'POST',
                        'header' => "Content-type: application/json",
                        'content' => json_encode($data)
                    )
                );
                $context = stream_context_create($options);
                $result = file_get_contents($url, false, $context);
                $_r = json_decode($result);

                if ($_r->httpStatusCode === "500") { /* Handle error */
                    flash(__($_r->message))->error();
                    return redirect()->back();
                }

                $user_recepient->balance += $transaction_amount;

                $wallet = new Wallet;
                $wallet->user_id = $user_recepient->id;
                $wallet->amount = $transaction_amount;
                $wallet->payment_method = 'WALLET CONVERT';
                $wallet->source_details = $user_recepient->fname . ' ' . $user_recepient->lname;
                ;
                $wallet->payment_details = '';
                $wallet->save();
                $user_recepient->save();

                break;
            case "Ark Cash":

                if (floatval($transaction_amount) > floatval($user_recepient->balance)) {
                    flash("Insufficient Balance")->error();
                    return redirect()->back();
                }

                $data = array(
                    'TargetShopId' => $user_recepient->id,
                    'Amount' => $transaction_amount
                );

                $url = 'http://localhost:55006/api/AdminAccess/IncrementBalance';
                $options = array(
                    'http' => array(
                        'method' => 'POST',
                        'header' => "Content-type: application/json",
                        'content' => json_encode($data)
                    )
                );
                $context = stream_context_create($options);
                $result = file_get_contents($url, false, $context);
                $_r = json_decode($result);

                $user_recepient->balance -= $transaction_amount;

                $wallet = new Wallet;
                $wallet->user_id = $user_recepient->id;
                $wallet->amount = floatval(-1 * $transaction_amount);
                $wallet->payment_method = 'WALLET CONVERT';
                $wallet->source_details = $user_recepient->fname . ' ' . $user_recepient->lname;
                $wallet->payment_details = '';
                $wallet->save();
                $user_recepient->save();
                break;

        }

        # code...
        flash("Transaction Successful")->success();
        return redirect()->back();
    }

    public function withdraw_proccess(Request $request)
    {
        $target_wallet = $request['target_wallet'];
        $target_outlet = $request['target_outlet'];
        $outlet_details = $request['outlet_details'];
        $recepient_address = floatval($request['ID']);
        $transaction_amount = floatval($request['transaction_amount']);

        $user_recepient = User::where('id', $recepient_address)->first();

        if ($target_outlet == 'Cheque') {
            if (floatval($transaction_amount) < 1000) {
                flash("Minimum withdrawal for 'Cheque' method is PHP 1000")->error();
                return redirect()->back();
            }
        }

        switch ($target_wallet) {
            case "Ark Credits":

                if (floatval($transaction_amount) > floatval($user_recepient->balance)) {
                    flash("Insufficient Balance")->error();
                    return redirect()->back();
                }

                $data = array(
                    'UserAuthId' => $recepient_address,
                    'Address' => $outlet_details,
                    'TotalAmount' => $transaction_amount,
                    'SourceWalletTypeId' => 20,
                    'Remarks' => $target_outlet
                );

                $user_recepient->balance -= $transaction_amount;

                $wallet = new Wallet;
                $wallet->user_id = $user_recepient->id;
                $wallet->amount = floatval(-1 * $transaction_amount);
                $wallet->payment_method = 'WALLET WITHDRAWAL';
                $wallet->source_details = $user_recepient->fname . ' ' . $user_recepient->lname;
                $wallet->payment_details = '';
                $wallet->save();
                $user_recepient->save();
                break;
            case "Ark Cash":
                $data = array(
                    'TargetShopId' => $user_recepient->id,
                    'Amount' => $transaction_amount
                );

                $url = 'http://localhost:55006/api/AdminAccess/WithdrawBalance';
                $options = array(
                    'http' => array(
                        'method' => 'POST',
                        'header' => "Content-type: application/json",
                        'content' => json_encode($data)
                    )
                );
                $context = stream_context_create($options);
                $result = file_get_contents($url, false, $context);
                $_r = json_decode($result);

                if ($_r->httpStatusCode === "500") { /* Handle error */
                    flash(__($_r->message))->error();
                    return redirect()->back();
                }

                $data = array(
                    'UserAuthId' => $recepient_address,
                    'Address' => $outlet_details,
                    'TotalAmount' => $transaction_amount,
                    'SourceWalletTypeId' => 18,
                    'Remarks' => $target_outlet
                );
                break;

        }

        $url = 'http://localhost:55006/api/AdminAccess/CreateWithdrawalRequest';
        $options = array(
            'http' => array(
                'method' => 'POST',
                'header' => "Content-type: application/json",
                'content' => json_encode($data)
            )
        );

        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        $_r = json_decode($result);

        # code...
        flash("Transaction Successful")->success();
        return redirect()->back();
    }

    public function end_trial_proccess($id)
    {
        $recepient = floatval($id);

        $data = array(
            'ShopUserId' => $recepient
        );

        $url = 'http://localhost:55006/api/AdminAccess/EndTrial';
        $options = array(
            'http' => array(
                'method' => 'POST',
                'header' => "Content-type: application/json",
                'content' => json_encode($data)
            )
        );
        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        $_r = json_decode($result);

        if ($_r->httpStatusCode === "500") { /* Handle error */
            flash(__($_r->message))->error();
            return redirect()->back();
        }

        # code...
        flash("Trial Removed")->success();
        return redirect()->back();
    }

    public function top_up($string)
    {
        $_x = $this->MALinkDecode($string);

        $view_bag = new stdClass;
        $view_bag->wallet_name = $_x[1];
        $view_bag->user_id = $_x[0];

        $customer = User::where('id', $view_bag->user_id)->first();
        $view_bag->user_name = $customer->email;
        $view_bag->full_name = $customer->fname . ' ' . $customer->lname;

        switch ($view_bag->wallet_name) {
            case "ark_credits":
                $txs = Wallet::where('user_id', $view_bag->user_id)->get()->toArray();
                $view_bag->balance = $customer->balance;
                $view_bag->wallet_name = "Ark Credits";
                break;
            case "ark_cash":

                $data = array(
                    'ShopUserId' => $view_bag->user_id
                );
                $url = 'http://localhost:55006/api/AdminAccess/SingleUser';
                $options = array(
                    'http' => array(
                        'method' => 'POST',
                        'header' => "Content-type: application/json",
                        'content' => json_encode($data)
                    )
                );
                $context = stream_context_create($options);
                $result = file_get_contents($url, false, $context);
                $_res = json_decode($result);
                $txs = $_res->userIncomeTransactions;

                $view_bag->balance = $_res->userWallets[array_search('ACW', array_column($_res->userWallets, 'walletCode'))]->balance;
                $view_bag->wallet_name = "Ark Cash";
                break;
        }

        return view('customers.wallet.topup', compact('view_bag'));
    }

    public function convert($string)
    {
        $_x = $this->MALinkDecode($string);

        $view_bag = new stdClass;
        $view_bag->wallet_name = $_x[1];
        $view_bag->user_id = $_x[0];

        $customer = User::where('id', $view_bag->user_id)->first();
        $view_bag->user_name = $customer->email;
        $view_bag->full_name = $customer->fname . ' ' . $customer->lname;

        $data = array(
            'ShopUserId' => $view_bag->user_id
        );
        $url = 'http://localhost:55006/api/AdminAccess/SingleUser';
        $options = array(
            'http' => array(
                'method' => 'POST',
                'header' => "Content-type: application/json",
                'content' => json_encode($data)
            )
        );
        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        $_res = json_decode($result);

        switch ($view_bag->wallet_name) {
            case "ark_credits":
                $view_bag->balance = $customer->balance;
                $view_bag->balance_alt = number_format($_res->userWallets[array_search('ACW', array_column($_res->userWallets, 'walletCode'))]->balance, 3);
                $view_bag->wallet_name = "Ark Credits";
                break;
            case "ark_cash":

                $view_bag->balance_alt = $customer->balance;
                $view_bag->balance = number_format($_res->userWallets[array_search('ACW', array_column($_res->userWallets, 'walletCode'))]->balance, 3);
                $view_bag->wallet_name = "Ark Cash";
                break;
        }
        return view('customers.wallet.convert', compact('view_bag'));
    }

    public function withdraw($string)
    {
        $_x = $this->MALinkDecode($string);

        $view_bag = new stdClass;
        $view_bag->wallet_name = $_x[1];
        $view_bag->user_id = $_x[0];

        $customer = User::where('id', $view_bag->user_id)->first();
        $view_bag->user_name = $customer->email;
        $view_bag->full_name = $customer->fname . ' ' . $customer->lname;

        switch ($view_bag->wallet_name) {
            case "ark_credits":
                $txs = Wallet::where('user_id', $view_bag->user_id)->get()->toArray();
                $view_bag->balance = $customer->balance;
                $view_bag->wallet_name = "Ark Credits";
                break;
            case "ark_cash":

                $data = array(
                    'ShopUserId' => $view_bag->user_id
                );
                $url = 'http://localhost:55006/api/AdminAccess/SingleUser';
                $options = array(
                    'http' => array(
                        'method' => 'POST',
                        'header' => "Content-type: application/json",
                        'content' => json_encode($data)
                    )
                );
                $context = stream_context_create($options);
                $result = file_get_contents($url, false, $context);
                $_res = json_decode($result);
                $txs = $_res->userIncomeTransactions;

                $view_bag->balance = number_format($_res->userWallets[array_search('ACW', array_column($_res->userWallets, 'walletCode'))]->balance, 3);
                $view_bag->wallet_name = "Ark Cash";
                break;
        }
        return view('customers.wallet.withdraw', compact('view_bag'));
    }

    public function wallet_txs($string)
    {

        $_x = $this->MALinkDecode($string);

        $view_bag = new stdClass;
        $view_bag->wallet_name = $_x[1];
        $view_bag->user_id = $_x[0];

        $customer = User::where('id', $view_bag->user_id)->first();
        $view_bag->user_name = $customer->email;
        $view_bag->full_name = $customer->fname . ' ' . $customer->lname;

        switch ($view_bag->wallet_name) {
            case "ark_credits":
                $txs = Wallet::where('user_id', $view_bag->user_id)->orderBy('id', 'DESC')->get()->toArray();
                $view_bag->balance = $customer->balance;
                $view_bag->transactions = array_map(array($this, 'map_to_table'), $txs, array_fill(0, count($txs), $view_bag->wallet_name));
                $view_bag->wallet_name = "Ark Credits";

                break;
            case "ark_cash":

                $data = array(
                    'ShopUserId' => $view_bag->user_id
                );
                $url = 'http://localhost:55006/api/AdminAccess/SingleUser';
                $options = array(
                    'http' => array(
                        'method' => 'POST',
                        'header' => "Content-type: application/json",
                        'content' => json_encode($data)
                    )
                );
                $context = stream_context_create($options);
                $result = file_get_contents($url, false, $context);
                $_res = json_decode($result);
                $txs = $_res->userIncomeTransactions;

                $view_bag->transactions = array_map(array($this, 'map_to_table'), $txs, array_fill(0, count($txs), $view_bag->wallet_name));
                $view_bag->balance = $_res->userWallets[array_search('ACW', array_column($_res->userWallets, 'walletCode'))]->balance;
                $view_bag->wallet_name = "Ark Cash";
                break;
        }



        // $data = array(
        //     'ShopUserId' => $id
        // );

        // $url = 'http://localhost:55006/api/AdminAccess/SingleUser';
        // $options = array(
        //     'http' => array(
        //         'method' => 'POST',
        //         'header' => "Content-type: application/json",
        //         'content' => json_encode($data)
        //     )
        // );

        // $context = stream_context_create($options);
        // $result = file_get_contents($url, false, $context);
        // $_r = json_decode($result);



        return view('customers.wallet.transactions', compact('view_bag'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {

        $user = User::findOrFail($request['ID']);

        $user->fname = $request['FirstName'];
        $user->mname = $request['MiddleName'];
        $user->lname = $request['LastName'];
        $user->email = $request['Email'];
        $user->phone = $request['PhoneNumber'];
        $user->save();

        $data = array(
            'Email' => $request['Email'],
            'FirstName' => $request['FirstName'],
            'LastName' => $request['LastName'],
            'PhoneNumber' => $request['PhoneNumber'],
            'UserName' => $request['UserName'],
            'ShopUserId' => $request['ID']
        );

        $url = 'http://localhost:55006/api/user/update';
        $options = array(
            'http' => array(
                'method' => 'POST',
                'header' => "Content-type: application/json",
                'content' => json_encode($data)
            )
        );

        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        $_r = json_decode($result);

        flash("User information updated succesfully")->success();
        return redirect()->back();
    }

    public function password_change(Request $request)
    {

        $user = User::findOrFail($request['ID']);

        if (strlen($request['PasswordString']) < 6)
        {
            flash("Password must be atleast 6 characters")->error();
            return redirect()->back();
        }


        $user->password = Hash::make($request['PasswordString']);
        $user->save();

        $data = array(
            'PasswordString' => $request['PasswordString'],
            'ShopUserId' => $request['ID']
        );

        $url = 'http://localhost:55006/api/user/PasswordChange';
        $options = array(
            'http' => array(
                'method' => 'POST',
                'header' => "Content-type: application/json",
                'content' => json_encode($data)
            )
        );

        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        $_r = json_decode($result);

        flash("User password updated succesfully")->success();
        return redirect()->back();
    }

    public function auth_status_change(Request $request)
    {

        $user = User::findOrFail($request['ID']);

        $data = array(
            'LoginStatus' => $request['LoginStatus'],
            'ShopUserId' => $request['ID']
        );

        $url = 'http://localhost:55006/api/user/AuthStatusChange';
        $options = array(
            'http' => array(
                'method' => 'POST',
                'header' => "Content-type: application/json",
                'content' => json_encode($data)
            )
        );

        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        $_r = json_decode($result);

        flash("Account status updated succesfully")->success();
        return redirect()->back();
    }

    public function manual_verify_email(Request $request)
    {

        $user = User::findOrFail($request['ID']);

        $url = 'http://localhost:55006/api/user/VerifyEmail';
        $data = array(
            'UserName' => $user['email']
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
        $result = file_get_contents($url, false, $context);
        $_r = json_decode($result);

        if ($_r->httpStatusCode != "500")
        {
            flash("Email verification status updated succesfully")->success();
        }
        else {
            flash("Email verification status update error")->error();
        }


        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Order::where('user_id', Customer::findOrFail($id)->user->id)->delete();
        User::destroy(Customer::findOrFail($id)->user->id);
        if (Customer::destroy($id)) {
            flash(__('Customer has been deleted successfully'))->success();
            return redirect()->route('customers.index');
        }

        flash(__('Something went wrong'))->error();
        return back();
    }

    public function MALinkDecode(string $string)
    {
        return explode(',', base64_decode(urldecode($string)));
    }

}
