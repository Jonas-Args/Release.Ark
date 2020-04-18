@extends('layouts.app')

@section('content')

<div class="row">
	<div class="col-sm-12">
		<!-- <a href="{{ route('sellers.create')}}" class="btn btn-info pull-right">{{__('add_new')}}</a> -->
	</div>
</div>

<!-- Basic Data Tables -->
<!--===================================================-->

<div class="row">
	<div class="col-md-12">
		<div class="panel">
			<div class="panel-heading">
				<h3 class="panel-title"></h3>
				
			</div>
			<div class="panel-body">
		
				<h1 style="margin:-25px 0px 20px 0px;">{{ $view_bag->wallet_name . __(' Transactions') }}</h1>

				<h4>{{ $view_bag->full_name }}</h4>
				<h5>Current Wallet Balance: ₱ {{ $view_bag->balance }}</h5>
                <a style="margin-top:15px" href="{{ route('customers.wallet', $view_bag->user_id) }}" class="btn btn-primary">Back to wallet</a>
			</div>
		</div>
	</div>
</div>

<div class="panel">
    <div class="panel-heading">
    </div>
    <div class="panel-body">
		<h3 style="margin:-25px 0px 20px 0px;">{{ __('Wallet Transactions') }}</h3>

        <table class="table table-striped table-bordered demo-dt-basic" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{__('Date')}}</th>
                    <th>{{__('Description')}}</th>
                    <th>{{__('Originator')}}</th>
                    <th width="10%">{{__('Amount')}}</th>
                    <th>{{__('Computation')}}</th>
                </tr>
            </thead>
            <tbody>

                @foreach($view_bag->transactions as $key => $tx)
                    <tr>
						<td>{{ $key+1 }}</td>
						<td>{{ $tx['Date'] }}</td>
                        <td>{{ $tx['Description'] }}</td>
                        <td> 
							<a href="{{route('customers.wallet', $tx['OriginatorID'])}}" style="text-decoration:underline" title="Click to show wallet">{{ $tx['Originator'] }}</a></td>
                        <td>₱ {{ $tx['Amount'] }}</td>
                        <td>{{ $tx['Computation'] }}</td>
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
