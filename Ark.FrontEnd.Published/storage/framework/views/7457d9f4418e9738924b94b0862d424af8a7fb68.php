<?php $__env->startSection('content'); ?>

<div class="cls-content-lg panel">
	<div class="panel-body">
		<div class="mar-ver pad-btm">
			<h1 class="h3"><?php echo e(__('Create a New Account')); ?></h1>
		</div>

		<form method="POST" action="<?php echo e(route('register')); ?>">
			<?php echo csrf_field(); ?>

			<div class="row">
				<div class="col-sm-6">
					<div class="form-group">
						<input id="name" type="text" class="form-control<?php echo e($errors->has('name') ? ' is-invalid' : ''); ?>" name="name" value="<?php echo e(old('name')); ?>" required autofocus placeholder="First Name">

						<?php if($errors->has('name')): ?>
							<span class="invalid-feedback" role="alert">
								<strong><?php echo e($errors->first('name')); ?></strong>
							</span>
						<?php endif; ?>
					</div>

					<div class="form-group">
						<input id="name" type="text" class="form-control<?php echo e($errors->has('name') ? ' is-invalid' : ''); ?>" name="name" value="<?php echo e(old('name')); ?>" required placeholder="Middle Name" />

						<?php if($errors->has('name')): ?>
						<span class="invalid-feedback" role="alert">
							<strong><?php echo e($errors->first('name')); ?></strong>
						</span>
						<?php endif; ?>
					</div>

					
				</div>
				<div class="col-sm-6">

					<div class="form-group">
						<input id="name" type="text" class="form-control<?php echo e($errors->has('name') ? ' is-invalid' : ''); ?>" name="name" value="<?php echo e(old('name')); ?>" required placeholder="Last Name" />

						<?php if($errors->has('name')): ?>
						<span class="invalid-feedback" role="alert">
							<strong><?php echo e($errors->first('name')); ?></strong>
						</span>
						<?php endif; ?>
					</div>

					
				</div>
			</div>

			<div class="row">
				<div class="col-sm-12">
					<hr />
					<div class="form-group">
						<input id="email" type="email" class="form-control<?php echo e($errors->has('email') ? ' is-invalid' : ''); ?>" name="email" value="<?php echo e(old('email')); ?>" required placeholder="Email" />

						<?php if($errors->has('email')): ?>
						<span class="invalid-feedback" role="alert">
							<strong><?php echo e($errors->first('email')); ?></strong>
						</span>
						<?php endif; ?>
					</div>

					<div class="form-group">
						<input id="password" type="password" class="form-control<?php echo e($errors->has('password') ? ' is-invalid' : ''); ?>" name="password" required placeholder="Password" />

						<?php if($errors->has('password')): ?>
						<span class="invalid-feedback" role="alert">
							<strong><?php echo e($errors->first('password')); ?></strong>
						</span>
						<?php endif; ?>
					</div>


					<div class="form-group">
						<input id="password-confirm" type="password" class="form-control" name="password_confirmation" required placeholder="Confrim Password" />
					</div>
					<hr />
				</div>
			</div>
			
			<div class="row">
				<div class="col-sm-6">

					<div class="form-group">
						<input id="name" type="text" class="form-control<?php echo e($errors->has('name') ? ' is-invalid' : ''); ?>" name="name" value="<?php echo e(old('name')); ?>" placeholder="Source Code" />


					</div>
				</div>
			</div>
			
			

			<div class="col-sm-12">
				<div class="checkbox pad-btm text-left">
					<input id="demo-form-checkbox" class="magic-checkbox" type="checkbox" required />
					<label for="demo-form-checkbox">
						<?php echo e(__('I agree with the')); ?>

						<a href="#" class="btn-link text-bold"><?php echo e(__('Terms and Conditions')); ?></a>
					</label>
				</div>
			</div>

			
			<button type="submit" class="btn btn-primary btn-lg btn-block">
				<?php echo e(__('Register')); ?>

			</button>
		</form>
	</div>
	<div class="pad-all">
		<?php echo e(__('Already have an account')); ?> ? <a href="<?php echo e(route('login')); ?>" class="btn-link mar-rgt text-bold"><?php echo e(__('Sign In')); ?></a>

		<div class="media pad-top bord-top">
			<div class="pull-right">
				<a href="#" class="pad-rgt"><i class="demo-psi-facebook icon-lg text-primary"></i></a>
				<!--<a href="#" class="pad-rgt"><i class="demo-psi-twitter icon-lg text-info"></i></a>-->
				<a href="#" class="pad-rgt"><i class="demo-psi-google-plus icon-lg text-danger"></i></a>
			</div>
			<div class="media-body text-left text-main text-bold">
				<?php echo e(__('Sign Up with')); ?>

			</div>
		</div>
	</div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.blank', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Projects\PHP\Ark\resources\views/auth/register.blade.php ENDPATH**/ ?>