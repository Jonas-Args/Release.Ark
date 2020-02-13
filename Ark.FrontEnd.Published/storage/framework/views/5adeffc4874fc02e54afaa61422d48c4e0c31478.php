<?php $__env->startSection('content'); ?>
    <section class="gry-bg py-4 profile">
        <div class="container">
            <div class="row cols-xs-space cols-sm-space cols-md-space">
                <div class="col-lg-3 d-none d-lg-block">
                    <?php if(Auth::user()->user_type == 'seller'): ?>
                        <?php echo $__env->make('frontend.inc.seller_side_nav', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    <?php elseif(Auth::user()->user_type == 'customer'): ?>
                        <?php echo $__env->make('frontend.inc.customer_side_nav', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    <?php endif; ?>
                </div>

                <div class="col-lg-9">
                    <div class="main-content">
                        <!-- Page title -->
                        <div class="page-title">
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <h2 class="heading heading-6 text-capitalize strong-600 mb-0 d-inline-block">
                                        <?php echo e(__('Conversations')); ?>

                                    </h2>
                                </div>
                                <div class="col-md-6">
                                    <div class="float-md-right">
                                        <ul class="breadcrumb">
                                            <li><a href="<?php echo e(route('home')); ?>"><?php echo e(__('Home')); ?></a></li>
                                            <li><a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a></li>
                                            <li><a href="<?php echo e(route('conversations.index')); ?>"><?php echo e(__('Conversations')); ?></a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card no-border mt-4 p-3">
                            <div class="py-4">
                                <?php $__currentLoopData = $conversations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $conversation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="block block-comment border-bottom">
                                        <div class="row">
                                            <div class="col-1">
                                                <div class="block-image">
                                                    <?php if(Auth::user()->id == $conversation->sender_id): ?>
                                                        <img <?php if($conversation->receiver->avatar_original == null): ?> src="<?php echo e(asset('frontend/images/user.png')); ?>" <?php else: ?> src="<?php echo e(asset($conversation->receiver->avatar_original)); ?>" <?php endif; ?> class="rounded-circle">
                                                    <?php else: ?>
                                                        <img <?php if($conversation->sender->avatar_original == null): ?> src="<?php echo e(asset('frontend/images/user.png')); ?>" <?php else: ?> src="<?php echo e(asset($conversation->sender->avatar_original)); ?>" <?php endif; ?> class="rounded-circle">
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            <div class="col-2">
                                                <p>
                                                    <?php if(Auth::user()->id == $conversation->sender_id): ?>
                                                        <a href="javascript:;"><?php echo e($conversation->receiver->name); ?></a>
                                                    <?php else: ?>
                                                        <a href="javascript:;"><?php echo e($conversation->sender->name); ?></a>
                                                    <?php endif; ?>
                                                    <br>
                                                    <span class="comment-date">
                                                        <?php echo e(date('h:i:m d-m-Y', strtotime($conversation->messages->last()->created_at))); ?>

                                                    </span>
                                                </p>
                                            </div>
                                            <div class="col-9">
                                                <div class="block-body">
                                                    <div class="block-body-inner pb-3">
                                                        <div class="row no-gutters">
                                                            <div class="col">
                                                                <h4 class="heading heading-6">
                                                                    <a href="<?php echo e(route('conversations.show', encrypt($conversation->id))); ?>">
                                                                        <?php echo e($conversation->title); ?>

                                                                    </a>
                                                                    <?php if((Auth::user()->id == $conversation->sender_id && $conversation->sender_viewed == 0) || (Auth::user()->id == $conversation->receiver_id && $conversation->receiver_viewed == 0)): ?>
                                                                        <span class="badge badge-pill badge-danger"><?php echo e(__('New')); ?></span>
                                                                    <?php endif; ?>
                                                                </h4>
                                                            </div>
                                                        </div>
                                                        <p class="comment-text mt-0">
                                                            <?php echo e($conversation->messages->last()->message); ?>

                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                        <div class="pagination-wrapper py-4">
                            <ul class="pagination justify-content-end">
                                <?php echo e($conversations->links()); ?>

                            </ul>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Projects\PHP\Ark\resources\views/frontend/conversations/index.blade.php ENDPATH**/ ?>