<?php

namespace Illuminate\Foundation\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Wallet;

trait AuthenticatesUsers
{
    use RedirectsUsers, ThrottlesLogins;

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {
        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }
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

		if ($_r->httpStatusCode == "500")
		{
			flash(__('An error occured: ' . $_r->message))->error();
			return redirect('/users/login');
		}

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

		if ($_r->userRole->accessRole != "Admin")
		{
			//return redirect('/users/login');

			$url = 'http://localhost:55006/api/user/BusinessPackages';
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



		}

        if ($this->attemptLogin($request)) {
            if ($_r->businessPackages != null)
			{
				if ($_r->businessPackages[0]->packageStatus == "2")
				{
					$user = Auth::user();
					if (floatval($user->balance) == 0)
					{
						//$user->balance = $user->balance + $_r->businessPackages[0]->businessPackage->consumables;
						//$user->save();

						//$wallet = new Wallet;
						//$wallet->user_id = $user->id;
						//$wallet->amount = $_r->businessPackages[0]->businessPackage->consumables;
						//$wallet->payment_method = 'Package Consumables';
						//$wallet->payment_details = 'Package Consumables';
						//$wallet->save();
					}

				}
			}
            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validateLogin(Request $request)
    {
        $request->validate([
            $this->username() => 'required|string',
            'password' => 'required|string',
        ]);
    }

    /**
     * Attempt to log the user into the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function attemptLogin(Request $request)
    {
        return $this->guard()->attempt(
            $this->credentials($request), $request->filled('remember')
        );
    }

    /**
     * Get the needed authorization credentials from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        return $request->only($this->username(), 'password');
    }

    /**
     * Send the response after the user was authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    protected function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate();

        $this->clearLoginAttempts($request);

        return $this->authenticated($request, $this->guard()->user())
                ?: redirect()->intended($this->redirectPath());
    }

    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        //
    }

    /**
     * Get the failed login response instance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        throw ValidationException::withMessages([
            $this->username() => [trans('auth.failed')],
        ]);
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        return 'email';
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        return $this->loggedOut($request) ?: redirect('/');
    }

    /**
     * The user has logged out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    protected function loggedOut(Request $request)
    {
        //
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard();
    }
}
