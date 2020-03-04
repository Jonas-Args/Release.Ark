<?php $__env->startSection('content'); ?>
    <section class="gry-bg py-4">
        <div class="profile">
            <div class="container">
                <div class="row">
                    <div class="col-xl-10 offset-xl-1">
                        <div class="card">
                            <div class="text-center px-35 pt-5">
                                <h3 class="heading heading-4 strong-500">
                                    <?php echo e(__('Create an account.')); ?>

                                </h3>
                            </div>
                            <div class="px-5 py-3 py-lg-5">
                                <div class="row align-items-center">
                                    <div class="col-12 col-lg">
                                        <form class="form-default" role="form" action="<?php echo e(route('register')); ?>" method="POST">
                                            <?php echo csrf_field(); ?>
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <!-- <label><?php echo e(__('fname')); ?></label> -->
                                                        <div class="input-group input-group--style-1">
															<input type="text" class="form-control<?php echo e($errors->has('fname') ? ' is-invalid' : ''); ?>" value="<?php echo e(old('fname')); ?>" placeholder="<?php echo e(__('First Name')); ?>" name="fname" />
                                                            <span class="input-group-addon">
                                                                <i class="text-md la la-user"></i>
                                                            </span>
                                                            <?php if($errors->has('fname')): ?>
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong><?php echo e($errors->first('fname')); ?></strong>
                                                                </span>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

											<div class="row">
												<div class="col-12">
													<div class="form-group">
														<!-- <label><?php echo e(__('mname')); ?></label> -->
														<div class="input-group input-group--style-1">
															<input type="text" class="form-control<?php echo e($errors->has('mname') ? ' is-invalid' : ''); ?>" value="<?php echo e(old('mname')); ?>" placeholder="<?php echo e(__('Middle Name')); ?>" name="mname" />
															<span class="input-group-addon">
																<i class="text-md la la-user"></i>
															</span>
															<?php if($errors->has('mname')): ?>
															<span class="invalid-feedback" role="alert">
																<strong><?php echo e($errors->first('mname')); ?></strong>
															</span>
															<?php endif; ?>
														</div>
													</div>
												</div>
											</div>

											<div class="row">
												<div class="col-12">
													<div class="form-group">
														<!-- <label><?php echo e(__('lname')); ?></label> -->
														<div class="input-group input-group--style-1">
															<input type="text" class="form-control<?php echo e($errors->has('lname') ? ' is-invalid' : ''); ?>" value="<?php echo e(old('lname')); ?>" placeholder="<?php echo e(__('Last Name')); ?>" name="lname" />
															<span class="input-group-addon">
																<i class="text-md la la-user"></i>
															</span>
															<?php if($errors->has('lname')): ?>
															<span class="invalid-feedback" role="alert">
																<strong><?php echo e($errors->first('lname')); ?></strong>
															</span>
															<?php endif; ?>
														</div>
													</div>
												</div>
											</div>
                                            <hr />
											<div class="row" style="display:none">
												<div class="col-12">
													<div class="form-group">
														<!-- <label><?php echo e(__('username')); ?></label> -->
														<div class="input-group input-group--style-1">
															<input type="text" class="form-control<?php echo e($errors->has('username') ? ' is-invalid' : ''); ?>" value="<?php echo e(old('username')); ?>" placeholder="<?php echo e(__('Username')); ?>" name="username" />
															<span class="input-group-addon">
																<i class="text-md la la-envelope"></i>
															</span>
															<?php if($errors->has('username')): ?>
															<span class="invalid-feedback" role="alert">
																<strong><?php echo e($errors->first('username')); ?></strong>
															</span>
															<?php endif; ?>
														</div>
													</div>
												</div>
											</div>
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <!-- <label><?php echo e(__('email')); ?></label> -->
                                                        <div class="input-group input-group--style-1">
															<input type="email" class="form-control<?php echo e($errors->has('email') ? ' is-invalid' : ''); ?>" value="<?php echo e(old('email')); ?>" autocomplete="off" placeholder="<?php echo e(__('Email')); ?>" name="email" />
                                                            <span class="input-group-addon">
                                                                <i class="text-md la la-envelope"></i>
                                                            </span>
                                                            <?php if($errors->has('email')): ?>
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong><?php echo e($errors->first('email')); ?></strong>
                                                                </span>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

											<div class="row">
												<div class="col-12">
													<div class="form-group">
														<!-- <label><?php echo e(__('')); ?></label> -->
														<div class="input-group input-group--style-1">
															<input type="tel" class="form-control<?php echo e($errors->has('mobileNo') ? ' is-invalid' : ''); ?>" value="<?php echo e(old('mobileNo')); ?>" placeholder="<?php echo e(__('Phone Number (Optional)')); ?>" name="mobileNo" />
															<span class="input-group-addon">
																<i class="text-md la la-mobile"></i>
															</span>
															<?php if($errors->has('mobileNo')): ?>
															<span class="invalid-feedback" role="alert">
																<strong><?php echo e($errors->first('mobileNo')); ?></strong>
															</span>
															<?php endif; ?>
														</div>
													</div>
												</div>
											</div>

                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <!-- <label><?php echo e(__('password')); ?></label> -->
                                                        <div class="input-group input-group--style-1">
															<input type="password" class="form-control<?php echo e($errors->has('password') ? ' is-invalid' : ''); ?>" placeholder="<?php echo e(__('Password')); ?>" autocomplete="off" name="password" />
                                                            <span class="input-group-addon">
                                                                <i class="text-md la la-lock"></i>
                                                            </span>
                                                            <?php if($errors->has('password')): ?>
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong><?php echo e($errors->first('password')); ?></strong>
                                                                </span>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <!-- <label><?php echo e(__('confirm_password')); ?></label> -->
                                                        <div class="input-group input-group--style-1">
                                                            <input type="password" class="form-control" placeholder="<?php echo e(__('Confirm Password')); ?>" name="password_confirmation" autocomplete="off">
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
														<!-- <label><?php echo e(__('source_code')); ?></label> -->
														<div class="input-group input-group--style-1">

                                                           
															<input type="text" class="form-control<?php echo e($errors->has('source_code') ? ' is-invalid' : ''); ?>" value="<?php echo e(app('request')->input('ulink')); ?>" placeholder="<?php echo e(__('Source Code (Optional)')); ?>" name="source_code" />
															
															<span class="input-group-addon">
																<i class="text-md la la-lock"></i>
															</span>
															<?php if($errors->has('source_code')): ?>
															<span class="invalid-feedback" role="alert">
																<strong><?php echo e($errors->first('source_code')); ?></strong>
															</span>
															<?php endif; ?>
														</div>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-12">
													<div class="form-group">
														<!-- <label><?php echo e(__('special_code')); ?></label> -->
														<div class="input-group input-group--style-1">
															<input type="text" class="form-control<?php echo e($errors->has('special_code') ? ' is-invalid' : ''); ?>" value="<?php echo e(old('special_code')); ?>" placeholder="<?php echo e(__('Discount Code (Optional)')); ?>" name="special_code" />
															<span class="input-group-addon">
																<i class="text-md la la-lock"></i>
															</span>
															<?php if($errors->has('source_code')): ?>
															<span class="invalid-feedback" role="alert">
																<strong><?php echo e($errors->first('special_code')); ?></strong>
															</span>
															<?php endif; ?>
														</div>
													</div>
												</div>
											</div>
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <div class="g-recaptcha" data-sitekey="<?php echo e(env('CAPTCHA_KEY')); ?>">
                                                            <?php if($errors->has('g-recaptcha-response')): ?>
                                                                <span class="invalid-feedback" style="display:block">
                                                                    <strong><?php echo e($errors->first('g-recaptcha-response')); ?></strong>
                                                                </span>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="checkbox pad-btm text-left">
                                                        <input class="magic-checkbox" type="checkbox" name="checkbox_example_1" id="checkboxExample_1a" required>
                                                        <label for="checkboxExample_1a" class="text-sm"><?php echo e(__('By signing up you agree to our ')); ?> <a  href="<?php echo e(route('terms')); ?>">terms and conditions</a></label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row align-items-center">
                                                <div class="col-12 text-right  mt-3">
                                                    <button type="submit" class="btn btn-styled btn-base-1 w-100 btn-md"><?php echo e(__('Create Account')); ?></button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="col-lg-1 text-center align-self-stretch" style="display:none!important">
                                        <div class="border-right h-100 mx-auto" style="width:1px;"></div>
                                    </div>
									<div class="col-12 col-lg" style="display:none!important">
										<?php if(\App\BusinessSetting::where('type', 'google_login')->first()->value == 1): ?>
										<a href="<?php echo e(route('social.login', ['provider' => 'google'])); ?>" class="btn btn-styled btn-block btn-google btn-icon--2 btn-icon-left px-4 my-4">
											<i class="icon fa fa-google"></i> <?php echo e(__('Login with Google')); ?>

										</a>
										<?php endif; ?>
                                        <?php if(\App\BusinessSetting::where('type', 'facebook_login')->first()->value == 1): ?>
										<a href="<?php echo e(route('social.login', ['provider' => 'facebook'])); ?>" class="btn btn-styled btn-block btn-facebook btn-icon--2 btn-icon-left px-4 my-4">
											<i class="icon fa fa-facebook"></i> <?php echo e(__('Login with Facebook')); ?>

										</a>
										<?php endif; ?>
                                        <?php if(\App\BusinessSetting::where('type', 'twitter_login')->first()->value == 1): ?>
										<a href="<?php echo e(route('social.login', ['provider' => 'twitter'])); ?>" class="btn btn-styled btn-block btn-twitter btn-icon--2 btn-icon-left px-4 my-4">
											<i class="icon fa fa-twitter"></i> <?php echo e(__('Login with Twitter')); ?>

										</a>
										<?php endif; ?>
									</div>
                                </div>
                            </div>
                            <div class="text-center px-35 pb-3">
                                <p class="text-md">
                                    <?php echo e(__('Already have an account?')); ?><a href="<?php echo e(route('user.login')); ?>" class="strong-600"><?php echo e(__('Log In')); ?></a>
                                </p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Projects\Published\Release.Ark\Ark.FrontEnd.Published\resources\views/frontend/user_registration.blade.php ENDPATH**/ ?>