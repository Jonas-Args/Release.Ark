@extends('layouts.app')

@section('content')

@php
	 $_s = Session::get('apiSession');

	 $url = 'http://localhost:55006/api/AdminAccess/WithdrawalList';
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
	 $withdrawalRequests = $_r->userWithdrawalRequests;

@endphp


<!-- Basic Data Tables -->
<!--===================================================-->
<div class="panel">
	<div class="panel-heading">
	</div>
	<div class="panel-body">
		<h1 style="margin:-25px 0px 20px 0px;">{{ __('Withdrawal Requests') }}</h1>
		<table class="table table-striped table-bordered demo-dt-basic" cellspacing="0" width="100%">
			<thead>
				<tr>
					<th>#</th>
					<th>{{__('Name')}}</th>
					<th>{{__('Method')}}</th>
					<th width="10%">{{__('Amount')}}</th>
					<th width="10%">{{__('Details')}}</th>
					<th width="10%">{{__('Status')}}</th>
					<th>Request Date</th>
					<th>Options</th>
				</tr>
			</thead>
			<tbody>
				@foreach($withdrawalRequests as $key1 => $req)
				@php
				  $withdrawalStatus = "";
				  switch($req->withdrawalStatus){
					  case 1:
						  $withdrawalStatus = "Pending";
						break;
					  case 2:
						  $withdrawalStatus = "Completed";
						  break;
					  case 10:
						  $withdrawalStatus = "Rejected";
						  break;
				  }
				@endphp

				<tr>
					<td>{{$key1+1}}</td>
					<td>{{$req->userAuth->userInfo->firstName . ' ' .$req->userAuth->userInfo->lastName}}</td>
					<td>{{$req->remarks}}</td>
					<td>{{ $req->totalAmount }}</td>
					<td>{{ $req->address}}</td>
					<td>{{ $withdrawalStatus }}</td>
					<td>{{ date_format(date_create($req->createdOn), "Y/m/d H:i:s") }}</td>
					<td>
						<div class="btn-group dropdown">
							<button class="btn btn-primary dropdown-toggle dropdown-toggle-icon" data-toggle="dropdown" type="button">
								{{__('Actions')}}
								<i class="dropdown-caret"></i>
							</button>
							<ul class="dropdown-menu dropdown-menu-right">
								<li>
									<a onclick="UpdateDepositRequest('{{ $req->id }}', 2);">{{__('Approve')}}</a>
								</li>
								<li>
									<a onclick="UpdateDepositRequest('{{ $req->id }}', 10);">{{__('Cancel')}}</a>
								</li>
							</ul>
						</div>
					</td>
				</tr>
				@endforeach
			</tbody>
		</table>

	</div>
</div>

<script>
    function UpdateDepositRequest(a, _p) {

        var UserWithdrawalRequest = {
            Id: parseFloat(a),
			WithdrawalStatus: _p,
			_token: '{{ csrf_token() }}'
        }

        $.ajax({
            url: '{{route('customers.withdrawal.update.exec')}}',
            type: "POST",
            data: JSON.stringify(UserWithdrawalRequest),
            contentType: 'application/json',
            success: function (data) {
                if (data.message != undefined && data.httpStatusCode == "200") {
                    // alert(data.message);
                }
                window.location.reload();
            },
            error: function (data, textStatus, jqXHR) {
                console.log(data.responseJSON);
                if (data.responseJSON.message != undefined && data.responseJSON.httpStatusCode == "500") {
                    alert(data.responseJSON.message);
                }
                window.location.reload();
            },
        });

        return false
    }
    //AutoRefresh(10000);
    function AutoRefresh(t) {
        setTimeout("location.reload(true);", t);
    }

</script>

@endsection
