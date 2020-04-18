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
		
				<h1 style="margin:-25px 0px 20px 0px;">{{ __('Wallet Conversion') }}</h1>

				<h4>{{ $view_bag->full_name }}</h4>
				<h5>{{ $view_bag->user_name }}</h5>
				<a style="margin-top:15px" href="{{ route('customers.wallet', $view_bag->user_id) }}" class="btn btn-primary">Back to wallet</a>
		
			</div>
		</div>
	</div>
</div>
	

<div class="row">
	<form action="{{ route('customers.wallet.convert.exec') }}" method="post">
	<div class="col-md-5">
		<div class="panel panel-default">
			<div class="panel-body">
				<h5>Conversion Details</h5>
				<p style="color:#808080">Allows you to convert balance from one wallet to another.
					<br>Conversions are reflected in real-time after applying. 
				</p>
				<hr>
				
						@csrf
					<input type="hidden" class="form-control" id="" name="ID" placeholder="" value="{{ $view_bag->user_id }}" />
					<input type="hidden" class="form-control" id="" name="target_wallet" placeholder="" value="@if ($view_bag->wallet_name == 'Ark Cash'){{__('Ark Credits')}}@else{{__('Ark Cash')}}@endif" />

					<div class="form-group">
					<label>Source Wallet</label>
					<select class="form-control demo-select2" name="LoginStatus" data-minimum-results-for-search="Infinity" disabled>
						 <option  @if ($view_bag->wallet_name == 'Ark Credits') selected @endif value="ark_credits">Ark Credits</option>
						 <option @if ($view_bag->wallet_name == 'Ark Cash') selected @endif value="ark_cash">Ark Cash</option>
					</select>
				</div>

				<div class="form-group">
					<label>Target Wallet</label>
					<select class="form-control demo-select2" name="" data-minimum-results-for-search="Infinity" disabled>
						 <option  @if ($view_bag->wallet_name == 'Ark Cash') selected @endif value="ark_credits">Ark Credits</option>
						 <option @if ($view_bag->wallet_name == 'Ark Credits') selected @endif value="ark_cash">Ark Cash</option>
					</select>
				</div>

				<div class="form-group">
					<label for="">Amount To Convert</label>
					<div class="input-group input-group-lg">
						<span class="input-group-addon" id="basic-addon1">PHP</span>
						<input type="number" class="form-control" id="transaction_amount" step="0.01" required max="{{$view_bag->balance}}" name="transaction_amount" placeholder="Php" aria-describedby="basic-addon1">
					</div>
					<p id="label_max_val_hit" style="color:red; margin-top:10px; display:none"></p>
				</div>
				<div class="form-group">
					<div class="form-check">
						 <input class="form-check-input" type="checkbox" value="" id="defaultCheck1" required>
						 <label class="form-check-label" for="defaultCheck1">
						   I have double checked the transaction details
						 </label>
					</div>
				</div>
				
				

			</div>
		</div>
	</div>

		<div class="col-md-5">
		<div class="panel panel-default">
			<div class="panel-body">
				<h5>Conversion Summary</h5>
				<div style="font-size:14px!important">
					<p style="color:#808080">
						Source Wallet:
						<span style="float:right;">
							<b style="color:black">{{ $view_bag->wallet_name }}</b>
						</span>
					</p>
					<p style="color:#808080">
						Source Current Balance:
						<span style="float:right;">
							<b style="color:black" id="balance_cat_sw">{{$view_bag->balance}}</b> PHP
						</span>
					</p>
					<p style="color:#808080">
						Source Balance After Transaction:
						<span style="float:right;">
							<b style="color:black" id="balance_at_sw">{{$view_bag->balance}}</b> PHP
						</span>
					</p>
				</div>
				<hr>
				
				<div style="font-size:14px!important">
					<p style="color:#808080">
						Target Wallet:
						<span style="float:right;">
							<b style="color:black">@if ($view_bag->wallet_name == 'Ark Credits') Ark Cash @else Ark Credits @endif</b>
						</span>
					</p>
					<p style="color:#808080">
						Target Current Balance:
						<span style="float:right;">
							<b style="color:black" id="balance_cat_tw">{{$view_bag->balance_alt}}</b> PHP
						</span>
					</p>
					<p style="color:#808080">
						Target Balance After Transaction:
						<span style="float:right;">
							<b style="color:black" id="balance_at_tw">{{$view_bag->balance_alt}}</b> PHP
						</span>
					</p>
				</div>
				<br>
				<button type="submit" class="btn btn-primary" style="float:right">Apply Conversion</button>
			</div>
		</div>
	</div>
	</form>
</div>

<script>
document.getElementById("transaction_amount").addEventListener("input", UpdateViewProperties);

	function UpdateViewProperties() {
		var balance_at_sw = parseFloat(document.getElementById("balance_cat_sw").innerHTML);
		var balance_at_tw = parseFloat(document.getElementById("balance_cat_tw").innerHTML);
		var transaction_amount = parseFloat(document.getElementById("transaction_amount").value);
		var balance_at_sw_res = balance_at_sw - transaction_amount;

		if (transaction_amount > 0) {
            if (balance_at_sw_res > 0) {
				document.getElementById("balance_at_sw").innerHTML = numeral(balance_at_sw - transaction_amount).format('0,0.000');
				document.getElementById("balance_at_tw").innerHTML = numeral(balance_at_tw + transaction_amount).format('0,0.000');
			}
			else {
				transaction_amount = balance_at_sw;
				document.getElementById("balance_at_sw").innerHTML = numeral(balance_at_sw - transaction_amount).format('0,0.000');
				document.getElementById("balance_at_tw").innerHTML = numeral(balance_at_tw + transaction_amount).format('0,0.000');
				document.getElementById("transaction_amount").value = balance_at_sw;
				document.getElementById("label_max_val_hit").innerHTML = 'Your Maximum Convertible is ' + balance_at_sw;
				ShowMaxAmountHit();
            }
			
		}
		else {
			document.getElementById("balance_at_sw").innerHTML = numeral(balance_at_sw).format('0,0.000');
			document.getElementById("balance_at_tw").innerHTML = numeral(balance_at_tw).format('0,0.000');
		}
	

	
	}

    function ShowMaxAmountHit() {
		document.getElementById("label_max_val_hit").style.display = "block";
		document.getElementById("transaction_amount").style.borderColor = "red";
		window.setTimeout(function myfunction() {
			document.getElementById("label_max_val_hit").style.display = "none";
			document.getElementById("transaction_amount").style.borderColor = "";
		}, 5000);
    }
</script>

@endsection
