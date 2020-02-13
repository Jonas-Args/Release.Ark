<?php

namespace Illuminate\Foundation\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;

trait RegistersUsers
{
	use RedirectsUsers;

	/**
	 * Show the application registration form.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function showRegistrationForm()
	{
		return view('auth.register');
	}

	/**
	 * Handle a registration request for the application.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function register(Request $request)
	{
		$this->validator($request->all())->validate();

		event(new Registered($user = $this->create($request->all())));

		if ($user == null)
		{
			return redirect('/users/registration');
			//return Redirect::to('users/registration')->with('message', 'Login Failed');
		}
		else{

			$url = 'http://localhost:55006/api/user/authenticate';
			$data = array(
				'UserName' => $request['email'],
				'PasswordString' => $request['password']
				);

			// use key 'http' even if you send the request to https://...
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

			$cookies = array();
			foreach ($http_response_header as $hdr) {
				if (preg_match('/^Set-Cookie:\s*([^;]+)/', $hdr, $matches)) {
					parse_str($matches[1], $tmp);
					$cookies += $tmp;
				}
			}

			$url = 'http://localhost:55006/api/user/Profile';
			$options = array(
				'http' => array(
					'method'  => 'GET',
					'header'    => "Accept-language: en\r\n" .
						"Cookie: .AspNetCore.Session=". $cookies["_AspNetCore_Session"] ."\r\n"
				)
			);
			$context  = stream_context_create($options);
			$result = file_get_contents($url, false, $context);
			$_r = json_decode($result);

			$request->session()->put('apiSession', implode($cookies));
			$request->session()->put('userAuthId', $_r->userAuth->id);
			$request->session()->put('userName', $_r->userAuth->userName);

			$this->guard()->login($user);
			return redirect('/affiliate');
			//return $this->registered($request, $user)
			//                ? redirect('/affiliate') : redirect($this->redirectPath());
		}
	}

	/**
	 * Get the guard to be used during registration.
	 *
	 * @return \Illuminate\Contracts\Auth\StatefulGuard
	 */
	protected function guard()
	{
		return Auth::guard();
	}

	/**
	 * The user has been registered.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  mixed  $user
	 * @return mixed
	 */
	protected function registered(Request $request, $user)
	{
		//
	}
}
