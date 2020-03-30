@extends('layouts.app')

@section('content')

@php
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

@endphp


<!-- Basic Data Tables -->
<!--===================================================-->
<div class="panel">
	<div class="panel-heading">
	</div>
	<div class="panel-body">
		<h1 style="margin:-25px 0px 20px 0px;">{{ __('Pending Deposit Request') }}</h1>
		<table class="table table-striped table-bordered demo-dt-basic" cellspacing="0" width="100%">
			<thead>
				<tr>
					<th>#</th>
					<th>{{__('Name')}}</th>
					<th>{{__('Email Address')}}</th>
					<th width="10%">{{__('Package')}}</th>
					<th width="10%">{{__('Amount')}}</th>
					<th width="10%">{{__('Method')}}</th>
					<th>Request Date</th>
					<th>Options</th>
				</tr>
			</thead>
			<tbody>
				@foreach($depositRequests as $key1 => $req)
				<tr>
					<td>{{$key1+1}}</td>
					<td>{{$req->firstName . ' ' .$req->lastName}}</td>
					<td>{{$req->email}}</td>
					<td>{{ $req->userBusinessPackage->businessPackage != null ? $req->userBusinessPackage->businessPackage->packageName : ""}}</td>
					<td>{{ $req->userBusinessPackage->businessPackage != null ? number_format($req->userBusinessPackage->userDepositRequest->amount) : ""}}</td>
					<td>{{ $req->userBusinessPackage->businessPackage != null ? $req->userBusinessPackage->userDepositRequest->remarks : ""}}</td>
					<td>{{ $req->userBusinessPackage->businessPackage != null ? $req->userBusinessPackage->userDepositRequest->createdOn : ""}}</td>
					<td>
						<div class="btn-group dropdown">
							<button class="btn btn-primary dropdown-toggle dropdown-toggle-icon" data-toggle="dropdown" type="button">
								{{__('Actions')}}
								<i class="dropdown-caret"></i>
							</button>
							<ul class="dropdown-menu dropdown-menu-right">
								<li>
									<a onclick="UpdateDepositRequest('{{ $req->userBusinessPackage->id }}', 3);">{{__('Approve')}}</a>
								</li>
								<li>
									<a onclick="UpdateDepositRequest('{{ $req->userBusinessPackage->id }}', 8);">{{__('Cancel')}}</a>
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

@endsection
