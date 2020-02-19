@extends('frontend.layouts.app')

@section('content')
    <section class="gry-bg py-4">
        <div class="profile">
            <div class="container">
                <div class="row">
                    <div class="col-xl-10 offset-xl-1">
                        <div class="card">
                            <div class="text-center px-35 pt-5">
                                <h3 class="heading heading-4 strong-500">
                                    {{__('Create an account.')}}
                                </h3>
                            </div>
                            <div class="px-5 py-3 py-lg-5">
                                <div class="row align-items-center">
                                    <div class="col-12 col-lg">
                                        <form class="form-default" role="form" action="{{ route('register') }}" method="POST">
                                            @csrf
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <!-- <label>{{ __('fname') }}</label> -->
                                                        <div class="input-group input-group--style-1">
															<input type="text" class="form-control{{ $errors->has('fname') ? ' is-invalid' : '' }}" value="{{ old('fname') }}" placeholder="{{ __('First Name') }}" name="fname" />
                                                            <span class="input-group-addon">
                                                                <i class="text-md la la-user"></i>
                                                            </span>
                                                            @if ($errors->has('fname'))
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $errors->first('fname') }}</strong>
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

											<div class="row">
												<div class="col-12">
													<div class="form-group">
														<!-- <label>{{ __('mname') }}</label> -->
														<div class="input-group input-group--style-1">
															<input type="text" class="form-control{{ $errors->has('mname') ? ' is-invalid' : '' }}" value="{{ old('mname') }}" placeholder="{{ __('Middle Name') }}" name="mname" />
															<span class="input-group-addon">
																<i class="text-md la la-user"></i>
															</span>
															@if ($errors->has('mname'))
															<span class="invalid-feedback" role="alert">
																<strong>{{ $errors->first('mname') }}</strong>
															</span>
															@endif
														</div>
													</div>
												</div>
											</div>

											<div class="row">
												<div class="col-12">
													<div class="form-group">
														<!-- <label>{{ __('lname') }}</label> -->
														<div class="input-group input-group--style-1">
															<input type="text" class="form-control{{ $errors->has('lname') ? ' is-invalid' : '' }}" value="{{ old('lname') }}" placeholder="{{ __('Last Name') }}" name="lname" />
															<span class="input-group-addon">
																<i class="text-md la la-user"></i>
															</span>
															@if ($errors->has('lname'))
															<span class="invalid-feedback" role="alert">
																<strong>{{ $errors->first('lname') }}</strong>
															</span>
															@endif
														</div>
													</div>
												</div>
											</div>
                                            <hr />
											<div class="row" style="display:none">
												<div class="col-12">
													<div class="form-group">
														<!-- <label>{{ __('username') }}</label> -->
														<div class="input-group input-group--style-1">
															<input type="text" class="form-control{{ $errors->has('username') ? ' is-invalid' : '' }}" value="{{ old('username') }}" placeholder="{{ __('Username') }}" name="username" />
															<span class="input-group-addon">
																<i class="text-md la la-envelope"></i>
															</span>
															@if ($errors->has('username'))
															<span class="invalid-feedback" role="alert">
																<strong>{{ $errors->first('username') }}</strong>
															</span>
															@endif
														</div>
													</div>
												</div>
											</div>
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <!-- <label>{{ __('email') }}</label> -->
                                                        <div class="input-group input-group--style-1">
															<input type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" value="{{ old('email') }}" autocomplete="off" placeholder="{{ __('Email') }}" name="email" />
                                                            <span class="input-group-addon">
                                                                <i class="text-md la la-envelope"></i>
                                                            </span>
                                                            @if ($errors->has('email'))
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $errors->first('email') }}</strong>
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

											<div class="row">
												<div class="col-12">
													<div class="form-group">
														<!-- <label>{{ __('') }}</label> -->
														<div class="input-group input-group--style-1">
															<input type="tel" class="form-control{{ $errors->has('mobileNo') ? ' is-invalid' : '' }}" value="{{ old('mobileNo') }}" placeholder="{{ __('Phone Number (Optional)') }}" name="mobileNo" />
															<span class="input-group-addon">
																<i class="text-md la la-mobile"></i>
															</span>
															@if ($errors->has('mobileNo'))
															<span class="invalid-feedback" role="alert">
																<strong>{{ $errors->first('mobileNo') }}</strong>
															</span>
															@endif
														</div>
													</div>
												</div>
											</div>

                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <!-- <label>{{ __('password') }}</label> -->
                                                        <div class="input-group input-group--style-1">
															<input type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" placeholder="{{ __('Password') }}" autocomplete="off" name="password" />
                                                            <span class="input-group-addon">
                                                                <i class="text-md la la-lock"></i>
                                                            </span>
                                                            @if ($errors->has('password'))
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $errors->first('password') }}</strong>
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <!-- <label>{{ __('confirm_password') }}</label> -->
                                                        <div class="input-group input-group--style-1">
                                                            <input type="password" class="form-control" placeholder="{{ __('Confirm Password') }}" name="password_confirmation" autocomplete="off">
                                                            <span class="input-group-addon">
                                                                <i class="text-md la la-lock"></i>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr />
											<div class="row">
												<div class="col-12">
													<div class="form-group">
														<!-- <label>{{ __('source_code') }}</label> -->
														<div class="input-group input-group--style-1">

                                                           
															<input type="text" class="form-control{{ $errors->has('source_code') ? ' is-invalid' : '' }}" value="{{ app('request')->input('ulink') }}" placeholder="{{ __('Source Code (Optional)') }}" name="source_code" />
															
															<span class="input-group-addon">
																<i class="text-md la la-lock"></i>
															</span>
															@if ($errors->has('source_code'))
															<span class="invalid-feedback" role="alert">
																<strong>{{ $errors->first('source_code') }}</strong>
															</span>
															@endif
														</div>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-12">
													<div class="form-group">
														<!-- <label>{{ __('special_code') }}</label> -->
														<div class="input-group input-group--style-1">
															<input type="text" class="form-control{{ $errors->has('special_code') ? ' is-invalid' : '' }}" value="{{ old('special_code') }}" placeholder="{{ __('Special Code (Optional)') }}" name="special_code" />
															<span class="input-group-addon">
																<i class="text-md la la-lock"></i>
															</span>
															@if ($errors->has('source_code'))
															<span class="invalid-feedback" role="alert">
																<strong>{{ $errors->first('special_code') }}</strong>
															</span>
															@endif
														</div>
													</div>
												</div>
											</div>
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <div class="g-recaptcha" data-sitekey="{{ env('CAPTCHA_KEY') }}">
                                                            @if ($errors->has('g-recaptcha-response'))
                                                                <span class="invalid-feedback" style="display:block">
                                                                    <strong>{{ $errors->first('g-recaptcha-response') }}</strong>
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="checkbox pad-btm text-left">
                                                        <input class="magic-checkbox" type="checkbox" name="checkbox_example_1" id="checkboxExample_1a" required>
                                                        <label for="checkboxExample_1a" class="text-sm">{{__('By signing up you agree to our terms and conditions.')}}</label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row align-items-center">
                                                <div class="col-12 text-right  mt-3">
                                                    <button type="submit" class="btn btn-styled btn-base-1 w-100 btn-md">{{ __('Create Account') }}</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="col-lg-1 text-center align-self-stretch" style="display:none!important">
                                        <div class="border-right h-100 mx-auto" style="width:1px;"></div>
                                    </div>
									<div class="col-12 col-lg" style="display:none!important">
										@if(\App\BusinessSetting::where('type', 'google_login')->first()->value == 1)
										<a href="{{ route('social.login', ['provider' => 'google']) }}" class="btn btn-styled btn-block btn-google btn-icon--2 btn-icon-left px-4 my-4">
											<i class="icon fa fa-google"></i> {{__('Login with Google')}}
										</a>
										@endif
                                        @if (\App\BusinessSetting::where('type', 'facebook_login')->first()->value == 1)
										<a href="{{ route('social.login', ['provider' => 'facebook']) }}" class="btn btn-styled btn-block btn-facebook btn-icon--2 btn-icon-left px-4 my-4">
											<i class="icon fa fa-facebook"></i> {{__('Login with Facebook')}}
										</a>
										@endif
                                        @if (\App\BusinessSetting::where('type', 'twitter_login')->first()->value == 1)
										<a href="{{ route('social.login', ['provider' => 'twitter']) }}" class="btn btn-styled btn-block btn-twitter btn-icon--2 btn-icon-left px-4 my-4">
											<i class="icon fa fa-twitter"></i> {{__('Login with Twitter')}}
										</a>
										@endif
									</div>
                                </div>
                            </div>
                            <div class="text-center px-35 pb-3">
                                <p class="text-md">
                                    {{__('Already have an account?')}}<a href="{{ route('user.login') }}" class="strong-600">{{__('Log In')}}</a>
                                </p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
@endsection

@section('script')
    <script type="text/javascript">
        function autoFillSeller(){
            $('#email').val('seller@example.com');
            $('#password').val('123456');
        }
        function autoFillCustomer(){
            $('#email').val('customer@example.com');
            $('#password').val('123456');
        }
    </script>
@endsection
