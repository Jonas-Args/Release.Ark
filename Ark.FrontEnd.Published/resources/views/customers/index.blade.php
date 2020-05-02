@extends('layouts.app')

@section('content')

<div class="row">
	<div class="col-sm-12">
		<!-- <a href="{{ route('sellers.create')}}" class="btn btn-info pull-right">{{__('add_new')}}</a> -->
	</div>
</div>

<!-- Basic Data Tables -->
<!--===================================================-->

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

<div class="panel">
    <div class="panel-heading">
    </div>
    <div class="panel-body">
		<h1 style="margin:-25px 0px 20px 0px;">{{ __('Customers List') }}</h1>

        <table class="table table-striped table-bordered demo-dt-basic" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{__('Name')}}</th>
                    <th>{{__('Email Address')}}</th>
                    <th>{{__('Source Code')}}</th>
                    <th width="10%">{{__('Package ')}}</th>
                    <th width="10%">{{__('Status')}}</th>
					<th>Activation Date</th>
					<th>Options</th>
                </tr>
            </thead>
            <tbody>

                @foreach($users as $key => $customer)
                    <tr>
                        <td>{{$key+1}}</td>
                        <td>{{$customer->firstName . ' ' .$customer->lastName}}</td>
                        <td>{{$customer->email}}</td>
                        <td>{{$customer->uid}}</td>
                        <td>{{ $customer->userBusinessPackage->businessPackage != null ? $customer->userBusinessPackage->businessPackage->packageName : ""}}</td>
                        <td>{{ $customer->userBusinessPackage->businessPackage != null ? $customer->userBusinessPackage->packageStatus == 1 ? "Pending Activation" : "Activated" : ""}}</td>
						<td>{{ $customer->userBusinessPackage->businessPackage != null ? $customer->userBusinessPackage->modifiedOn : ""}}</td>
                        <td>
                            <div class="btn-group dropdown">
                                <button class="btn btn-primary dropdown-toggle dropdown-toggle-icon" data-toggle="dropdown" type="button">
                                    {{__('Actions')}} <i class="dropdown-caret"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-right">
									<li>
										<a href="{{route('customers.edit', $customer->shopUserId)}}"><i class="fa fa-pencil" style="margin-right:12px; font-size:16px"></i>{{__('Edit')}}</a>
									</li>
									<li>
										<a href="{{route('customers.wallet', $customer->shopUserId)}}"><i class="fa fa-credit-card" style="margin-right:12px; font-size:16px"></i>{{__('Wallet')}}</a>
									</li>
									</li>
									@if ($customer->userBusinessPackage->businessPackage != null && $customer->userBusinessPackage->businessPackage->packageCode == "EPKG1TRL")
										<li>
										    <a href="{{route('client.trial.end.exec', $customer->shopUserId)}}"><i class="fa fa-check"
										            style="margin-right:12px; font-size:16px"></i>{{__('End Trial')}}</a>
										</li>
									@endif
									
									<li style="display:none">
										<a onclick="confirm_modal('{{route('customers.destroy', $customer->shopUserId)}}');"><i class="fa fa-ban" style="margin-right:12px; font-size:16px"></i>{{__('Delete')}}</a>
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

@endsection
