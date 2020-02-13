@extends('layouts.app')

@section('content')

<div class="row">
	<div class="col-sm-12">
		<!-- <a href="{{ route('sellers.create')}}" class="btn btn-info pull-right">{{__('add_new')}}</a> -->
	</div>
</div>

<br />

<!-- Basic Data Tables -->
<!--===================================================-->
<div class="panel">
	<div class="panel-heading">
		<h3 class="panel-title">{{__('Customers')}}</h3>
	</div>
	<div class="panel-body">
		<table class="table table-striped table-bordered demo-dt-basic" cellspacing="0" width="100%">
			<thead>
				<tr>
					<th>#</th>
					<th>{{__('Name')}}</th>
					<th>{{__('Email Address')}}</th>
					<th width="10%">{{__('Password')}}</th>
				</tr>
			</thead>
			<tbody>
				
			</tbody>
		</table>

	</div>
</div>

@endsection
