<?php $__env->startSection('content'); ?>
<div class="text-center">
    
    <p class="h3 text-uppercase text-bold"><?php echo e(__('OOPS!')); ?></p>
    <div class="pad-btm">
        <?php echo e(__('This site is under developement. We will be back soon!!')); ?>

    </div>
    <hr class="new-section-sm bord-no">
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.blank', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Projects\PHP\Ark\resources\views/errors/503.blade.php ENDPATH**/ ?>