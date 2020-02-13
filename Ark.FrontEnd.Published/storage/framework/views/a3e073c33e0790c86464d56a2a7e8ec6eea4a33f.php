<?php $__env->startSection('content'); ?>

<div class="col-lg-6 col-lg-offset-3">
    <div class="panel">
        <div class="panel-heading">
            <h3 class="panel-title"><?php echo e(__('Category Information')); ?></h3>
        </div>

        <!--Horizontal Form-->
        <!--===================================================-->
        <form class="form-horizontal" action="<?php echo e(route('categories.update', $category->id)); ?>" method="POST" enctype="multipart/form-data">
            <input name="_method" type="hidden" value="PATCH">
        	<?php echo csrf_field(); ?>
            <div class="panel-body">
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="name"><?php echo e(__('Name')); ?></label>
                    <div class="col-sm-10">
                        <input type="text" placeholder="<?php echo e(__('Name')); ?>" id="name" name="name" class="form-control" required value="<?php echo e($category->name); ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="banner"><?php echo e(__('Banner')); ?> <small>(200x300)</small></label>
                    <div class="col-sm-10">
                        <input type="file" id="banner" name="banner" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="icon"><?php echo e(__('Icon')); ?> <small>(32x32)</small></label>
                    <div class="col-sm-10">
                        <input type="file" id="icon" name="icon" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label"><?php echo e(__('Meta Title')); ?></label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="meta_title" value="<?php echo e($category->meta_title); ?>" placeholder="<?php echo e(__('Meta Title')); ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label"><?php echo e(__('Description')); ?></label>
                    <div class="col-sm-10">
                        <textarea name="meta_description" rows="8" class="form-control"><?php echo e($category->meta_description); ?></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="name"><?php echo e(__('Slug')); ?></label>
                    <div class="col-sm-10">
                        <input type="text" placeholder="<?php echo e(__('Slug')); ?>" id="slug" name="slug" value="<?php echo e($category->slug); ?>" class="form-control">
                    </div>
                </div>
                <?php if(\App\BusinessSetting::where('type', 'category_wise_commission')->first()->value == 1): ?>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="name"><?php echo e(__('Commission Rate')); ?></label>
                        <div class="col-sm-8">
                            <input type="number" min="0" step="0.01" id="commision_rate" name="commision_rate" value="<?php echo e($category->commision_rate); ?>" class="form-control">
                        </div>
                        <div class="col-lg-2">
                            <option class="form-control">%</option>
                        </div>
                    </div>
                <?php endif; ?>
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

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Projects\PHP\Ark\resources\views/categories/edit.blade.php ENDPATH**/ ?>