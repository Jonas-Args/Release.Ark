<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-lg-6">
        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title text-center"><?php echo e(__('SMTP Settings')); ?></h3>
            </div>
            <div class="panel-body">
                <form class="form-horizontal" action="<?php echo e(route('env_key_update.update')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="form-group">
                        <input type="hidden" name="types[]" value="MAIL_DRIVER">
                        <div class="col-lg-3">
                            <label class="control-label"><?php echo e(__('MAIL DRIVER')); ?></label>
                        </div>
                        <div class="col-lg-6">
                            <select class="demo-select2" name="MAIL_DRIVER">
                                <option value="sendmail" <?php if(env('MAIL_DRIVER') == "sendmail"): ?> selected <?php endif; ?>>Sendmail</option>
                                <option value="smtp" <?php if(env('MAIL_DRIVER') == "smtp"): ?> selected <?php endif; ?>>SMTP</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <input type="hidden" name="types[]" value="MAIL_HOST">
                        <div class="col-lg-3">
                            <label class="control-label"><?php echo e(__('MAIL HOST')); ?></label>
                        </div>
                        <div class="col-lg-6">
                            <input type="text" class="form-control" name="MAIL_HOST" value="<?php echo e(env('MAIL_HOST')); ?>" placeholder="MAIL HOST">
                        </div>
                    </div>
                    <div class="form-group">
                        <input type="hidden" name="types[]" value="MAIL_PORT">
                        <div class="col-lg-3">
                            <label class="control-label"><?php echo e(__('MAIL PORT')); ?></label>
                        </div>
                        <div class="col-lg-6">
                            <input type="text" class="form-control" name="MAIL_PORT" value="<?php echo e(env('MAIL_PORT')); ?>" placeholder="MAIL PORT">
                        </div>
                    </div>
                    <div class="form-group">
                        <input type="hidden" name="types[]" value="MAIL_USERNAME">
                        <div class="col-lg-3">
                            <label class="control-label"><?php echo e(__('MAIL USERNAME')); ?></label>
                        </div>
                        <div class="col-lg-6">
                            <input type="text" class="form-control" name="MAIL_USERNAME" value="<?php echo e(env('MAIL_USERNAME')); ?>" placeholder="MAIL USERNAME" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <input type="hidden" name="types[]" value="MAIL_PASSWORD">
                        <div class="col-lg-3">
                            <label class="control-label"><?php echo e(__('MAIL PASSWORD')); ?></label>
                        </div>
                        <div class="col-lg-6">
                            <input type="text" class="form-control" name="MAIL_PASSWORD" value="<?php echo e(env('MAIL_PASSWORD')); ?>" placeholder="MAIL PASSWORD">
                        </div>
                    </div>
                    <div class="form-group">
                        <input type="hidden" name="types[]" value="MAIL_ENCRYPTION">
                        <div class="col-lg-3">
                            <label class="control-label"><?php echo e(__('MAIL ENCRYPTION')); ?></label>
                        </div>
                        <div class="col-lg-6">
                            <input type="text" class="form-control" name="MAIL_ENCRYPTION" value="<?php echo e(env('MAIL_ENCRYPTION')); ?>" placeholder="MAIL ENCRYPTION">
                        </div>
                    </div>
                    <div class="form-group">
                        <input type="hidden" name="types[]" value="MAIL_FROM_ADDRESS">
                        <div class="col-lg-3">
                            <label class="control-label"><?php echo e(__('MAIL FROM ADDRESS')); ?></label>
                        </div>
                        <div class="col-lg-6">
                            <input type="text" class="form-control" name="MAIL_FROM_ADDRESS" value="<?php echo e(env('MAIL_FROM_ADDRESS')); ?>" placeholder="MAIL FROM ADDRESS" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <input type="hidden" name="types[]" value="MAIL_FROM_NAME">
                        <div class="col-lg-3">
                            <label class="control-label"><?php echo e(__('MAIL FROM NAME')); ?></label>
                        </div>
                        <div class="col-lg-6">
                            <input type="text" class="form-control" name="MAIL_FROM_NAME" value="<?php echo e(env('MAIL_FROM_NAME')); ?>" placeholder="MAIL FROM NAME" required>
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
    <div class="col-lg-6">
        <div class="panel bg-gray-light">
            <div class="panel-body">
                <h4><?php echo e(__('Instruction')); ?></h4>
                <p class="text-danger"><?php echo e(__('Please be carefull when you are configuring SMTP. For incorrect configuration you will get error at the time of order place, new registration, sending newsletter.')); ?></p>
                <hr>
                <p><?php echo e(__('For Non-SSL')); ?></p>
                <ul class="list-group">
                    <li class="list-group-item text-dark">Select 'sendmail' for Mail Driver if you face any issue after configuring 'smtp' as Mail Driver </li>
                    <li class="list-group-item text-dark">Set Mail Host according to your server Mail Client Manual Settings</li>
                    <li class="list-group-item text-dark">Set Mail port as '587'</li>
                    <li class="list-group-item text-dark">Set Mail Encryption as 'ssl' if you face issue with 'tls'</li>
                </ul>
                <p><?php echo e(__('For SSL')); ?></p>
                <ul class="list-group mar-no">
                    <li class="list-group-item text-dark">Select 'sendmail' for Mail Driver if you face any issue after configuring 'smtp' as Mail Driver</li>
                    <li class="list-group-item text-dark">Set Mail Host according to your server Mail Client Manual Settings</li>
                    <li class="list-group-item text-dark">Set Mail port as '465'</li>
                    <li class="list-group-item text-dark">Set Mail Encryption as 'ssl'</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp73\htdocs\shop\resources\views/business_settings/smtp_settings.blade.php ENDPATH**/ ?>