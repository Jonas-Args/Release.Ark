<?php $__env->startSection('content'); ?>

    <div class="col-lg-6 col-lg-offset-3">
        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title"><?php echo e(__('Color Settings')); ?></h3>
            </div>

            <!--Horizontal Form-->
            <!--===================================================-->
            <form class="form-horizontal" action="<?php echo e(route('generalsettings.color.store')); ?>" method="POST" enctype="multipart/form-data">
            	<?php echo csrf_field(); ?>
                <div class="panel-body">
                    <div class="row">
                        <div class="color-radio col-sm-3">
                            <label>
                                <input type="radio" name="frontend_color" class="color-control-input" value="default" <?php if(\App\GeneralSetting::first()->frontend_color == 'default'): ?> checked <?php endif; ?>>
                                <span class="color-control-box" style="background:#e62e04;"></span>
                            </label>
                        </div>
                        <div class="color-radio col-sm-3">
                            <label>
                                <input type="radio" name="frontend_color" class="color-control-input" value="1" <?php if(\App\GeneralSetting::first()->frontend_color == '1'): ?> checked <?php endif; ?>>
                                <span class="color-control-box" style="background:#1abc9c;"></span>
                            </label>
                        </div>
                        <div class="color-radio col-sm-3">
                            <label>
                                <input type="radio" name="frontend_color" class="color-control-input" value="2" <?php if(\App\GeneralSetting::first()->frontend_color == '2'): ?> checked <?php endif; ?>>
                                <span class="color-control-box" style="background:#3498db;"></span>
                            </label>
                        </div>
                        <div class="color-radio col-sm-3">
                            <label>
                                <input type="radio" name="frontend_color" class="color-control-input" value="3" <?php if(\App\GeneralSetting::first()->frontend_color == '3'): ?> checked <?php endif; ?>>
                                <span class="color-control-box" style="background:#72bf40;"></span>
                            </label>
                        </div>
                        <div class="color-radio col-sm-3">
                            <label>
                                <input type="radio" name="frontend_color" class="color-control-input" value="4" <?php if(\App\GeneralSetting::first()->frontend_color == '4'): ?> checked <?php endif; ?>>
                                <span class="color-control-box" style="background:#F79F1F;"></span>
                            </label>
                        </div>
                        <div class="color-radio col-sm-3">
                            <label>
                                <input type="radio" name="frontend_color" class="color-control-input" value="5" <?php if(\App\GeneralSetting::first()->frontend_color == '5'): ?> checked <?php endif; ?>>
                                <span class="color-control-box" style="background:#12CBC4;"></span>
                            </label>
                        </div>
                        <div class="color-radio col-sm-3">
                            <label>
                                <input type="radio" name="frontend_color" class="color-control-input" value="6" <?php if(\App\GeneralSetting::first()->frontend_color == '6'): ?> checked <?php endif; ?>>
                                <span class="color-control-box" style="background:#8e44ad;"></span>
                            </label>
                        </div>
                        <div class="color-radio col-sm-3">
                            <label>
                                <input type="radio" name="frontend_color" class="color-control-input" value="7" <?php if(\App\GeneralSetting::first()->frontend_color == '7'): ?> checked <?php endif; ?>>
                                <span class="color-control-box" style="background:#ED4C67;"></span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="panel-footer text-right">
                    <button class="btn btn-purple" type="submit"><?php echo e(__('save')); ?></button>
                </div>
            </form>
            <!--===================================================-->
            <!--End Horizontal Form-->

        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Projects\PHP\Ark\resources\views/general_settings/color.blade.php ENDPATH**/ ?>