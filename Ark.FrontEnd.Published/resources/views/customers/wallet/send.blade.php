@extends('layouts.app')

@section('content')

<!-- Basic Data Tables -->
<!--===================================================-->
<div class="row">
	<div class="col-md-12">
		<div class="panel">
			<div class="panel-heading">
				<h3 class="panel-title"></h3>
				
			</div>
			<div class="panel-body">
		
				<h1 style="margin:-25px 0px 20px 0px;">{{ __('Send Balance') }}</h1>

				<h4>{{ $view_bag->full_name }}</h4>
				<h5>{{ $view_bag->user_name }}</h5>
				<a style="margin-top:15px" href="{{ route('customers.wallet', $view_bag->user_id) }}" class="btn btn-primary">Back to wallet</a>
		
			</div>
		</div>
	</div>
</div>
	

<div class="row">
	<div class="col-md-5">
		<div class="panel panel-default">
			<div class="panel-body">
				<h5>Available for sending</h5>
				<p style="color:#808080">Allows you to send current wallet balance to other customer.
					<br>Balance updates are reflected in real-time after sending. 
				</p>
				<hr>
				<form action="{{ route('customers.wallet.send.exec') }}" method="post">
					@csrf
					<input type="hidden" class="form-control" id="" name="ID" placeholder="" value="{{ $view_bag->user_id }}" />
					<input type="hidden" class="form-control" id="" name="target_wallet" placeholder="" value="{{ $view_bag->wallet_name }}" />
					<div style="font-size:14px!important">
						<p style="color:#808080">
							Current Balance:
							<span style="float:right;">
								<b style="color:black">{{$view_bag->balance}}</b> PHP
							</span>
						</p>
						<p style="color:#808080">
							Source Wallet:
							<span style="float:right;">
								<b style="color:black">{{ $view_bag->wallet_name }}</b>
							</span>
						</p>
					</div>
					<hr>
					<div class="form-group">
						<label for="">Recipient Source Code</label>
						<div class="input-group">
							<input type="text" class="form-control" name="recepient_address" placeholder="Enter recepient.." autocomplete="off" required />
							<span class="input-group-addon" id="basic-addon1">Source Code</span>
						</div>
						<small id="emailHelp" class="form-text text-muted">Double check your recepient code before you send</small>
					</div>
					<div class="form-group">
						<label for="">Amount To Send</label>
						<div class="input-group input-group-lg">
						 	<span class="input-group-addon" id="basic-addon1">PHP</span>
						 	<input type="text" class="form-control" name="transaction_amount" placeholder="Php" aria-describedby="basic-addon1">
						</div>
					</div>
					<div class="form-group">
						<div class="form-check">
							 <input class="form-check-input" type="checkbox" value="" id="defaultCheck1" required>
							 <label class="form-check-label" for="defaultCheck1">
							   I have double checked the transaction details
							 </label>
						</div>
					</div>

					<button type="submit" class="btn btn-primary" style="float:right">Send Balance</button>
				</form>
			</div>
		</div>
	</div>

</div>

<script>
	
</script>

@endsection
