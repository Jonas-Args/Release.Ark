<?php $__env->startSection('content'); ?>

    <section class="gry-bg py-4">
        <div class="container">
            <div class="row">
                <div class="col">
                    <div class=" bg-white">
						<img style="margin:auto; margin-right:auto; display:block; width:100%" src="<?php echo e(asset('uploads/brands/ABOUT_ARK.png')); ?>" alt="About Us Photo" />
                        <?php
                         //   echo \App\Policy::where('name', 'terms')->first()->content;
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Projects\Published\Release.Ark\Ark.FrontEnd.Published\resources\views/frontend/policies/aboutus.blade.php ENDPATH**/ ?>