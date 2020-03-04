<?php $__env->startSection('content'); ?>
    <div class="cls-content-sm panel">
        <div class="panel-body">
            <h1 class="h3"><?php echo e(__('Verify Your Email Address')); ?></h1>
                <?php if(session('resent')): ?>
                    <div class="alert alert-success" role="alert">
                        <?php echo e(__('A fresh verification link has been sent to your email address.')); ?>

                    </div>
                <?php endif; ?>

            <?php echo e(__('Before proceeding, please check your email for a verification link.')); ?>

            <?php echo e(__('If you did not receive the email')); ?>, <a href="<?php echo e(route('verification.resend')); ?>" class="btn-link text-bold text-main"><?php echo e(__('Click here to request another')); ?></a>.
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.blank', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Projects\Published\Release.Ark\Ark.FrontEnd.Published\resources\views/auth/verify.blade.php ENDPATH**/ ?>