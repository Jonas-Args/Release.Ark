<?php $__env->startSection('content'); ?>
    <div class="pad-btm text-center">
        <h1 class="h3">Congratulations!!!</h1>
        <p>You have successfully completed the installation process. Please Login to continue.</p>
        <div class="row">
            <div class="col-sm-8 col-sm-offset-2">
                <div class="panel bord-all mar-top panel-info">
                    <div class="panel-heading">
                        <h3 class="panel-title">Configure the following setting to run the system properly.</h3>
                    </div>
                    <div class="panel-body">
                        <ul class="list-group mar-no mar-top bord-no">
                            <li class="list-group-item">SMTP Setting</li>
                            <li class="list-group-item">Payment Method Configuration</li>
                            <li class="list-group-item">Social Media Login Configuration</li>
                            <li class="list-group-item">Facebook Chat Configuration</li>
                        </ul>
                    </div>
                </div>
                <div class="panel bord-all mar-top panel-info">
                    <div class="panel-heading">
                        <h3 class="panel-title">Demo account added for test purpose.</h3>
                    </div>
                    <div class="panel-body">
                        <table class="table table-bordered mar-no">
                            <thead>
                                <tr>
                                    <th>User Type</th>
                                    <th>Email</th>
                                    <th>Password</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Customer</td>
                                    <td>customer@example.com</td>
                                    <td>123456</td>
                                </tr>
                                <tr>
                                    <td>Seller</td>
                                    <td>seller@example.com</td>
                                    <td>123456</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="text-center">
        <a href="<?php echo e(env('APP_URL')); ?>" class="btn btn-primary">Go to Frontend Website</a>
        <a href="<?php echo e(env('APP_URL')); ?>/admin" class="btn btn-success">Login to Admin panel</a>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.blank', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Projects\PHP\Ark\resources\views/installation/step6.blade.php ENDPATH**/ ?>