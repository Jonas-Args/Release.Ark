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
		
				<h1 style="margin:-25px 0px 20px 0px;">{{ __('Cutomer Wallet') }}</h1>

				<h4>{{ $customer->fname . ' ' . $customer->lname }}</h4>
				<h5>{{ $customer->email }}</h5>
				<h6 style="color:#808080">Source Code: {{ $customer->Uid }}</h6>
			
		
			</div>
		</div>
	</div>
</div>
	

<div class="row">
	<div class="col-md-4">
		<div class="panel panel-default">
			<div class="panel-body">
				<h4>Ark Cash</h4>
				<div style="font-size:14px!important">
					<small style="color:#808080">Exchanges to PHP currency rate</small>
					<br />
					<small style="color:#808080">Can use for withdrawal / send to other account.</small>
				</div>

				<hr />
				<div style="font-size:14px!important">
					<label style="color:#808080">Current Wallet Balance:</label>
					<h4 style="margin:0px">
						<span>0.00</span>
						<span style="color:#82a9d8">PHP</span>
					</h4>
				</div>
				<hr />
				<div style="font-size:14px!important">
					<p style="color:#808080">
						Total Recieved:
						<span style="float:right;">
							<b style="color:black">0.00</b> PHP
						</span>
					</p>
					<p style="color:#808080">Direct Sales Income:
						<span style="float:right;">
							<b style="color:black">0.00</b> PHP
						</span></p>
					<p style="color:#808080">Trimatch Sales Income:
						<span style="float:right;">
							<b style="color:black">0.00</b> PHP
						</span>
					</p>
				</div>
				<br />
				<div class="btn-group dropdown" style="float:right">
					<button class="btn btn-primary dropdown-toggle dropdown-toggle-icon" data-toggle="dropdown" type="button">
						{{__('Actions')}}
						<i class="dropdown-caret"></i>
					</button>
					<ul class="dropdown-menu dropdown-menu-right">
						<li>
							<a href="{{route('customers.wallet.send', $customer->id)}}">
								<i class="fa fa-send" style="margin-right:12px; font-size:16px"></i>{{__('Send')}}
							</a>
						</li>
						<li>
							<a href="{{route('customers.wallet', $customer->Uid)}}">
								<i class="fa fa-cog" style="margin-right:12px; font-size:16px"></i>{{__('Top Up')}}
							</a>
						</li>
						<li>
							<a onclick="confirm_modal('{{route('customers.destroy', $customer->Uid)}}');">
								<i class="fa fa-refresh" style="margin-right:12px; font-size:16px"></i>{{__('Convert')}}
							</a>
						</li>
						<li>
							<a onclick="confirm_modal('{{route('customers.destroy', $customer->Uid)}}');">
								<i class="fa fa-upload" style="margin-right:12px; font-size:16px"></i>{{__('Withdraw')}}
							</a>
						</li>
						<li>
							<a onclick="confirm_modal('{{route('customers.destroy', $customer->Uid)}}');">
								<i class="fa fa-file-text" style="margin-right:12px; font-size:16px"></i>{{__('Transactions')}}
							</a>
						</li>
					</ul>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-4">
		<div class="panel panel-default">
			<div class="panel-body">
				<h4>Ark Credits</h4>
				<div style="font-size:14px!important">
					<small style="color:#808080">Exchanges to PHP currency rate</small>
					<br />
					<small style="color:#808080">Can use for payment when shopping</small>
				</div>

				<hr />
				<div style="font-size:14px!important">
					<label style="color:#808080">Current Wallet Balance:</label>
					<h4 style="margin:0px">
						<span>0.00</span>
						<span style="color:#82a9d8">PHP</span>
					</h4>
				</div>
				<hr />
				<div style="font-size:14px!important">
					<p style="color:#808080">
						Total Recieved:
						<span style="float:right;">
							<b style="color:black">0.00</b> PHP
						</span>
					</p>
					<p style="color:#808080">
						Sales Rebates:
						<span style="float:right;">
							<b style="color:black">0.00</b> PHP
						</span>
					</p>
					<p style="color:#808080">
						Total Deposit:
						<span style="float:right;">
							<b style="color:black">0.00</b> PHP
						</span>
					</p>
				</div>
				<br />
				<div class="btn-group dropdown" style="float:right">
					<button class="btn btn-primary dropdown-toggle dropdown-toggle-icon" data-toggle="dropdown" type="button">
						{{__('Actions')}}
						<i class="dropdown-caret"></i>
					</button>
					<ul class="dropdown-menu dropdown-menu-right">
						<li>
							<a href="{{route('customers.wallet.send', $customer->id)}}">
								<i class="fa fa-send" style="margin-right:12px; font-size:16px"></i>{{__('Send')}}
							</a>
						</li>
						<li>
							<a href="{{route('customers.wallet', $customer->Uid)}}">
								<i class="fa fa-cog" style="margin-right:12px; font-size:16px"></i>{{__('Top Up')}}
							</a>
						</li>
						<li>
							<a onclick="confirm_modal('{{route('customers.destroy', $customer->Uid)}}');">
								<i class="fa fa-refresh" style="margin-right:12px; font-size:16px"></i>{{__('Convert')}}
							</a>
						</li>
						<li>
							<a onclick="confirm_modal('{{route('customers.destroy', $customer->Uid)}}');">
								<i class="fa fa-upload" style="margin-right:12px; font-size:16px"></i>{{__('Withdraw')}}
							</a>
						</li>
						<li>
							<a onclick="confirm_modal('{{route('customers.destroy', $customer->Uid)}}');">
								<i class="fa fa-file-text" style="margin-right:12px; font-size:16px"></i>{{__('Transactions')}}
							</a>
						</li>
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
	
</script>

@endsection
