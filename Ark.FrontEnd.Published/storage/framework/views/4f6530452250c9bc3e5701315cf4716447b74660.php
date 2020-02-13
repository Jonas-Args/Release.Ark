<?php $__env->startSection('content'); ?>

<div class="col-lg-6 col-lg-offset-3">
    <div class="panel">
        <div class="panel-heading">
            <h3 class="panel-title"><?php echo e(__('Staff Information')); ?></h3>
        </div>

        <!--Horizontal Form-->
        <!--===================================================-->
        <form class="form-horizontal" action="<?php echo e(route('staffs.store')); ?>" method="POST" enctype="multipart/form-data">
        	<?php echo csrf_field(); ?>
            <div class="panel-body">
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="name"><?php echo e(__('Name')); ?></label>
                    <div class="col-sm-9">
                        <input type="text" placeholder="<?php echo e(__('Name')); ?>" id="name" name="name" class="form-control" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="email"><?php echo e(__('Email')); ?></label>
                    <div class="col-sm-9">
                        <input type="text" placeholder="<?php echo e(__('Email')); ?>" id="email" name="email" class="form-control" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="mobile"><?php echo e(__('Phone')); ?></label>
                    <div class="col-sm-9">
                        <input type="text" placeholder="<?php echo e(__('Phone')); ?>" id="mobile" name="mobile" class="form-control" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="password"><?php echo e(__('Password')); ?></label>
                    <div class="col-sm-9">
                        <input type="password" placeholder="<?php echo e(__('Password')); ?>" id="password" name="password" class="form-control" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="name"><?php echo e(__('Role')); ?></label>
                    <div class="col-sm-9">
                        <select name="role_id" required class="form-control demo-select2-placeholder">
                            <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($role->id); ?>"><?php echo e($role->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="panel-footer text-right">
                <button class="btn btn-purple" type="submit"><?php echo e(__('Save')); ?></button>
            </div>
        </form>
        <!--===================================================-->
        <!--End Horizontal Form-->

    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Projects\PHP\Ark\resources\views/staffs/create.blade.php ENDPATH**/ ?>