<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-lg-6">
            <div class="panel">
                <div class="panel-heading">
                    <h3 class="panel-title text-center"><?php echo e(__('Google Analytics Setting')); ?></h3>
                </div>
                <div class="panel-body">
                    <form class="form-horizontal" action="<?php echo e(route('google_analytics.update')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <div class="form-group">
                            <div class="col-lg-3">
                                <label class="control-label"><?php echo e(__('Google Analytics')); ?></label>
                            </div>
                            <div class="col-lg-6">
                                <label class="switch">
                                    <input value="1" name="google_analytics" type="checkbox" <?php if(\App\BusinessSetting::where('type', 'google_analytics')->first()->value == 1): ?>
                                        checked
                                    <?php endif; ?>>
                                    <span class="slider round"></span>
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <input type="hidden" name="types[]" value="TRACKING_ID">
                            <div class="col-lg-3">
                                <label class="control-label"><?php echo e(__('Tracking ID')); ?></label>
                            </div>
                            <div class="col-lg-6">
                                <input type="text" class="form-control" name="TRACKING_ID" value="<?php echo e(env('TRACKING_ID')); ?>" placeholder="Tracking ID" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-lg-12 text-right">
                                <button class="btn btn-purple" type="submit"><?php echo e(__('Save')); ?></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp73\htdocs\shop\resources\views/business_settings/google_analytics.blade.php ENDPATH**/ ?>