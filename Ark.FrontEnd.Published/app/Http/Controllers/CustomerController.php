<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Customer;
use App\User;
use App\Order;

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
				'method'  => 'POST',
				'header'  => "Content-type: application/json",
                'content' => json_encode($data)
			)
		);

		$context  = stream_context_create($options);
		$result = file_get_contents($url, false, $context);
		$_r = json_decode($result);

		$customer = User::where('id', $id)->first();
        $customer->LoginStatus = $_r->user->loginStatus;
        return view('customers.edit',compact('customer'));
    }

    public function wallet($id)
    {
        $data = array(
			'ShopUserId' => $id
			);

        $url = 'http://localhost:55006/api/AdminAccess/SingleUser';
		$options = array(
			'http' => array(
				'method'  => 'POST',
				'header'  => "Content-type: application/json",
                'content' => json_encode($data)
			)
		);

		$context  = stream_context_create($options);
		$result = file_get_contents($url, false, $context);
		$_r = json_decode($result);

		$customer = User::where('id', $id)->first();
        $customer->LoginStatus = $_r->user->loginStatus;
        $customer->Uid = $_r->user->uid;
        return view('customers.wallet',compact('customer'));
    }

    public function wallet_send($id)
    {
        $data = array(
			'ShopUserId' => $id
			);

        $url = 'http://localhost:55006/api/AdminAccess/SingleUser';
		$options = array(
			'http' => array(
				'method'  => 'POST',
				'header'  => "Content-type: application/json",
                'content' => json_encode($data)
			)
		);

		$context  = stream_context_create($options);
		$result = file_get_contents($url, false, $context);
		$_r = json_decode($result);

		$customer = User::where('id', $id)->first();
        $customer->LoginStatus = $_r->user->loginStatus;
        $customer->Uid = $_r->user->uid;
        return view('customers.wallet_send',compact('customer'));
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
				'method'  => 'POST',
				'header'  => "Content-type: application/json",
                'content' => json_encode($data)
			)
		);

		$context  = stream_context_create($options);
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
				'method'  => 'POST',
				'header'  => "Content-type: application/json",
                'content' => json_encode($data)
			)
		);

		$context  = stream_context_create($options);
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
				'method'  => 'POST',
				'header'  => "Content-type: application/json",
                'content' => json_encode($data)
			)
		);

		$context  = stream_context_create($options);
		$result = file_get_contents($url, false, $context);
		$_r = json_decode($result);

		flash("Account status updated succesfully")->success();
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
        if(Customer::destroy($id)){
            flash(__('Customer has been deleted successfully'))->success();
            return redirect()->route('customers.index');
        }

        flash(__('Something went wrong'))->error();
        return back();
    }
}
