<?php $__env->startSection('content'); ?>

<div class="container">
	<div class="p-4">
		<h4>Redirecting to Paynamics..</h4>
	</div>
</div>

<form action="<?php echo e($_r->requestUrl); ?>" method="POST" id="paynamics_pay" style="display: none;">
	<!-- Note that the amount is in paise = 50 INR -->
	<!--amount need to be in paisa-->
	<input type="hidden" name="paymentrequest" value="<?php echo e($_r->paymentrequest); ?>" />
</form>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <script type="text/javascript">
        $(document).ready(function(){
			$('#paynamics_pay').submit()
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Projects\Published\Release.Ark\Ark.FrontEnd.Published\resources\views/frontend/payWithPaynamics.blade.php ENDPATH**/ ?>