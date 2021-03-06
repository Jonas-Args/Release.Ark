<?php $__env->startSection('content'); ?>

<div class="row">
	<div class="col-sm-12">
		<!-- <a href="<?php echo e(route('sellers.create')); ?>" class="btn btn-info pull-right"><?php echo e(__('add_new')); ?></a> -->
	</div>
</div>

<!-- Basic Data Tables -->
<!--===================================================-->

<?php
 $_s = Session::get('apiSession');

 $url = 'http://localhost:55006/api/AdminAccess/UserList';
 $options = array(
	 'http' => array(
		 'method'  => 'GET',
		 'header'    => "Accept-language: en\r\n" .
			 "Cookie: .AspNetCore.Session=". $_s ."\r\n"
	 )
 );
 $context  = stream_context_create($options);
 $result = file_get_contents($url, false, $context);
 $_r = json_decode($result);

 $users = $_r->userList;
 $depositRequests = $_r->userDepositRequests;


?>

<div class="panel">
    <div class="panel-heading">
    </div>
    <div class="panel-body">
		<h1 style="margin:-25px 0px 20px 0px;"><?php echo e(__('Customers List')); ?></h1>

        <table class="table table-striped table-bordered demo-dt-basic" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>#</th>
                    <th><?php echo e(__('Name')); ?></th>
                    <th><?php echo e(__('Email Address')); ?></th>
                    <th><?php echo e(__('Source Code')); ?></th>
                    <th width="10%"><?php echo e(__('Package ')); ?></th>
                    <th width="10%"><?php echo e(__('Status')); ?></th>
					<th>Activation Date</th>
					<th>Options</th>
                </tr>
            </thead>
            <tbody>

                <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($key+1); ?></td>
                        <td><?php echo e($customer->firstName . ' ' .$customer->lastName); ?></td>
                        <td><?php echo e($customer->email); ?></td>
                        <td><?php echo e($customer->uid); ?></td>
                        <td><?php echo e($customer->userBusinessPackage->businessPackage != null ? $customer->userBusinessPackage->businessPackage->packageName : ""); ?></td>
                        <td><?php echo e($customer->userBusinessPackage->businessPackage != null ? $customer->userBusinessPackage->packageStatus == 1 ? "Pending Activation" : "Activated" : ""); ?></td>
						<td><?php echo e($customer->userBusinessPackage->businessPackage != null ? $customer->userBusinessPackage->modifiedOn : ""); ?></td>
                        <td>
                            <div class="btn-group dropdown">
                                <button class="btn btn-primary dropdown-toggle dropdown-toggle-icon" data-toggle="dropdown" type="button">
                                    <?php echo e(__('Actions')); ?> <i class="dropdown-caret"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-right">
									<li>
										<a href="<?php echo e(route('customers.edit', $customer->shopUserId)); ?>"><i class="fa fa-pencil" style="margin-right:12px; font-size:16px"></i><?php echo e(__('Edit')); ?></a>
									</li>
									<li>
										<a href="<?php echo e(route('customers.wallet', $customer->shopUserId)); ?>"><i class="fa fa-credit-card" style="margin-right:12px; font-size:16px"></i><?php echo e(__('Wallet')); ?></a>
									</li>
									<li>
										<a onclick="confirm_modal('<?php echo e(route('customers.destroy', $customer->shopUserId)); ?>');"><i class="fa fa-ban" style="margin-right:12px; font-size:16px"></i><?php echo e(__('Delete')); ?></a>
									</li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>

    </div>
</div>



<script>
	function UpdateDepositRequest(a,_p) {

		var UserBusinessPackageBO = {
			UserPackageID: parseFloat(a),
			DepositStatus: _p
		}

		$.ajax({
			url: config.ApiURL + 'api/BusinessPackage/Update',
			type: "POST",
			data: JSON.stringify(UserBusinessPackageBO),
			contentType: 'application/json',
			success: function (data) {
				//console.log(data);
				if (data.message != undefined && data.httpStatusCode == "200") {
					alert(data.message);
				}
				//window.location = data.RedirectUrl;
				window.location.reload();
			},
			error: function (data, textStatus, jqXHR) {
				console.log(data.responseJSON);
				//alert(data.responseJSON.Status);
				if (data.responseJSON.message != undefined && data.responseJSON.httpStatusCode == "500") {
					alert(data.responseJSON.message);
				}
				window.location.reload();
				//$('#myModal').modal('show');
				//window.location.href = data.responseJSON.RedirectUrl;
			},
		});

		return false
	}
	//AutoRefresh(10000);
	function AutoRefresh(t) {
		setTimeout("location.reload(true);", t);
	}

</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Projects\Published\Release.Ark\Ark.FrontEnd.Published\resources\views/customers/index.blade.php ENDPATH**/ ?>