<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Customer;
use App\BusinessSetting;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
	/*
	|--------------------------------------------------------------------------
	| Register Controller
	|--------------------------------------------------------------------------
	|
	| This controller handles the registration of new users as well as their
	| validation and creation. By default this controller uses a trait to
	| provide this functionality without requiring any additional code.
	|
	*/

	use RegistersUsers;

	/**
	 * Where to redirect users after registration.
	 *
	 * @var string
	 */
	protected $redirectTo = '/';

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('guest');
	}

	/**
	 * Get a validator for an incoming registration request.
	 *
	 * @param  array  $data
	 * @return \Illuminate\Contracts\Validation\Validator
	 */
	protected function validator(array $data)
	{
		return Validator::make($data, [
			'fname' => 'required|string|max:255',
			'mname' => 'required|string|max:255',
			'lname' => 'required|string|max:255',
			'email' => 'required|string|email|max:255|unique:users',
			'password' => 'required|string|min:6|confirmed',
			'source_code' => '',
		]);
	}

	/**
	 * Create a new user instance after a valid registration.
	 *
	 * @param  array  $data
	 * @return \App\User
	 */
	protected function create(array $data)
	{
		if ($data['special_code'] != "" && $data['special_code'] != "ARKPH2020")
		{
			flash(__('Invalid Discount Code'))->error();
			return redirect()->route('user.registration');
		}

		$user = User::create([
		'name' => $data['fname'] . ' ' . $data['lname'],
		'fname' => $data['fname'],
		'mname' => $data['mname'],
		'lname' => $data['lname'],
		'email' => $data['email'],
		'password' => Hash::make($data['password']),
	]);

		$url = 'http://localhost:55006/api/user/create';
		$_data = array(
			'FirstName' => $data['fname'],
			'LastName' => $data['lname'],
			'UserName' => $data['email'],
			'CountryIsoCode2' => $data['special_code'],
			'PhoneNumber' => $data['mobileNo'],
			'Email' => $data['email'],
			'PasswordString' => $data['password'],
			'DirectSponsorID' => $data['source_code'],
			'BinaryPosition' => '1',
			'ShopUserId' => $user->id
			);



		// use key 'http' even if you send the request to https://...
		$options = array(
			'http' => array(
				'header'  => "Content-type: application/json",
				'method'  => 'POST',
				'content' => json_encode($_data)
			)
		);
		$context  = stream_context_create($options);
		$result = file_get_contents($url, false, $context);
		$result = json_decode($result);

		if ($result->httpStatusCode === "500") { /* Handle error */
			flash(__('An error occured: ' . $result->message))->error();
		}

		else{

			if(BusinessSetting::where('type', 'email_verification')->first()->value != 1){
				$user->email_verified_at = date('Y-m-d H:m:s');
				$user->save();
				flash(__('Registration successful.'))->success();
			}
			else {
				flash(__('Registration completed successfully! <br> <p>Please check your registered email for email verification.</p>'))->success();
			}

			$customer = new Customer;
			$customer->user_id = $user->id;
			$customer->save();

			//return redirect()->route('affiliate');

			return $user;
		}
	}
}
