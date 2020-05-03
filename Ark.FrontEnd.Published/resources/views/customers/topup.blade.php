@extends('layouts.app')

@section('content')

<!-- Basic Data Tables -->
<!--===================================================-->
<div class="panel">
	<div class="panel-heading">
	</div>
	<div class="panel-body">
		<h1 style="margin:-25px 0px 20px 0px;">{{ __('Top-Up Requests') }}</h1>
		<table class="table table-striped table-bordered demo-dt-basic" cellspacing="0" width="100%">
			<thead>
				<tr>
					<th>#</th>
					<th>{{__('Name')}}</th>
					<th>{{__('Method')}}</th>
					<th>{{__('Code')}}</th>
					<th width="10%">{{__('Amount')}}</th>
					<th width="10%">{{__('Status')}}</th>
					<th>Request Date</th>
				</tr>
			</thead>
			<tbody>
				@foreach($txs as $key1 => $req)
				@php
				  $withdrawalStatus = "";
				  switch($req->payment_status){
					  case "unpaid":
						  $withdrawalStatus = "Pending";
						break;
					  case "pending":
						  $withdrawalStatus = "Proccessing";
						break;
					  case "paid":
						  $withdrawalStatus = "Completed";
						  break;
					  case "cancelled":
						  $withdrawalStatus = "Cancelled";
						  break;
					  case "rejected":
						  $withdrawalStatus = "Rejected";
						  break;
				  }
				@endphp

				<tr>
					<td>{{$key1+1}}</td>
					<td>{{ $req->user->name }}</td>
					<td>{{ $req->payment_type }}</td>
					<td>{{ $req->code }}</td>
					<td>{{ $req->amount }}</td>
					<td>{{ $withdrawalStatus }}</td>
					<td>{{ date_format(date_create($req->created_at), "Y/m/d H:i:s") }}</td>
					
				</tr>
				@endforeach
			</tbody>
		</table>

	</div>
</div>

<script>
    //AutoRefresh(10000);
    function AutoRefresh(t) {
        setTimeout("location.reload(true);", t);
    }

</script>

@endsection
