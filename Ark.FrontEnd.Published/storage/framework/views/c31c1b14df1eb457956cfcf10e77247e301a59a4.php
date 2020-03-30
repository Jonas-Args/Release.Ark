<?php $__env->startSection('content'); ?>

<div class="row">
    <div class="col-sm-12">
        <a href="<?php echo e(route('pick_up_points.create')); ?>" class="btn btn-rounded btn-info pull-right"><?php echo e(__('Add New Pick-up Point')); ?></a>
    </div>
</div>

<br>

<!-- Basic Data Tables -->
<!--===================================================-->
<div class="panel">
    <div class="panel-heading">
        <h3 class="panel-title"><?php echo e(__('Pick-up Point')); ?></h3>
    </div>
    <div class="panel-body">
        <table class="table table-striped table-bordered demo-dt-basic" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th width="10%">#</th>
                    <th><?php echo e(__('Name')); ?></th>
                    <th><?php echo e(__('Manager')); ?></th>
                    <th><?php echo e(__('Location')); ?></th>
                    <th><?php echo e(__('Pickup Station Contact')); ?></th>
                    <th><?php echo e(__('Status')); ?></th>
                    
                    <th width="10%"><?php echo e(__('Options')); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $pickup_points; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $pickup_point): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($key+1); ?></td>
                        <td><?php echo e($pickup_point->name); ?></td>
                        <?php if($pickup_point->staff != null): ?>
                            <td><?php echo e($pickup_point->staff->user->name); ?></td>
                        <?php else: ?>
                            <td><div class="label label-table label-danger">
                                <?php echo e(__('No Manager')); ?>

                            </div></td>
                        <?php endif; ?>
                        <td><?php echo e($pickup_point->address); ?></td>
                        <td><?php echo e($pickup_point->phone); ?></td>
                        <td>
                            <?php if($pickup_point->pick_up_status != 1): ?>
                                <div class="label label-table label-danger">
                                    <?php echo e(__('Close')); ?>

                                </div>
                            <?php else: ?>
                                <div class="label label-table label-success">
                                    <?php echo e(__('Open')); ?>

                                </div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="btn-group dropdown">
                                <button class="btn btn-primary dropdown-toggle dropdown-toggle-icon" data-toggle="dropdown" type="button">
                                    <?php echo e(__('Actions')); ?> <i class="dropdown-caret"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-right">
                                    <li><a href="<?php echo e(route('pick_up_points.edit', encrypt($pickup_point->id))); ?>"><?php echo e(__('Edit')); ?></a></li>
                                    <li><a onclick="confirm_modal('<?php echo e(route('pick_up_points.destroy', $pickup_point->id)); ?>');"><?php echo e(__('Delete')); ?></a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>

    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Projects\Published\Release.Ark\Ark.FrontEnd.Published\resources\views/pickup_point/index.blade.php ENDPATH**/ ?>