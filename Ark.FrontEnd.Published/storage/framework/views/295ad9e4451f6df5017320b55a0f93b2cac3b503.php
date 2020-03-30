<?php $__env->startSection('content'); ?>

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


<!-- Basic Data Tables -->
<!--===================================================-->
<div class="panel">
	<div class="panel-heading">
	</div>
	<div class="panel-body">
		<h1 style="margin:-25px 0px 20px 0px;"><?php echo e(__('Pending Deposit Request')); ?></h1>
		<table class="table table-striped table-bordered demo-dt-basic" cellspacing="0" width="100%">
			<thead>
				<tr>
					<th>#</th>
					<th><?php echo e(__('Name')); ?></th>
					<th><?php echo e(__('Email Address')); ?></th>
					<th width="10%"><?php echo e(__('Package')); ?></th>
					<th width="10%"><?php echo e(__('Amount')); ?></th>
					<th width="10%"><?php echo e(__('Method')); ?></th>
					<th>Request Date</th>
					<th>Options</th>
				</tr>
			</thead>
			<tbody>
				<?php $__currentLoopData = $depositRequests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key1 => $req): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
				<tr>
					<td><?php echo e($key1+1); ?></td>
					<td><?php echo e($req->firstName . ' ' .$req->lastName); ?></td>
					<td><?php echo e($req->email); ?></td>
					<td><?php echo e($req->userBusinessPackage->businessPackage != null ? $req->userBusinessPackage->businessPackage->packageName : ""); ?></td>
					<td><?php echo e($req->userBusinessPackage->businessPackage != null ? number_format($req->userBusinessPackage->userDepositRequest->amount) : ""); ?></td>
					<td><?php echo e($req->userBusinessPackage->businessPackage != null ? $req->userBusinessPackage->userDepositRequest->remarks : ""); ?></td>
					<td><?php echo e($req->userBusinessPackage->businessPackage != null ? $req->userBusinessPackage->userDepositRequest->createdOn : ""); ?></td>
					<td>
						<div class="btn-group dropdown">
							<button class="btn btn-primary dropdown-toggle dropdown-toggle-icon" data-toggle="dropdown" type="button">
								<?php echo e(__('Actions')); ?>

								<i class="dropdown-caret"></i>
							</button>
							<ul class="dropdown-menu dropdown-menu-right">
								<li>
									<a onclick="UpdateDepositRequest('<?php echo e($req->userBusinessPackage->id); ?>', 3);"><?php echo e(__('Approve')); ?></a>
								</li>
								<li>
									<a onclick="UpdateDepositRequest('<?php echo e($req->userBusinessPackage->id); ?>', 8);"><?php echo e(__('Cancel')); ?></a>
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

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Projects\Published\Release.Ark\Ark.FrontEnd.Published\resources\views/customers/deposits.blade.php ENDPATH**/ ?>