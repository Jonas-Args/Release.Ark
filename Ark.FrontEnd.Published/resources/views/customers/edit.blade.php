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
		
				<h1 style="margin:-25px 0px 20px 0px;">{{ __('Edit Customer') }}</h1>
		
				<form method="POST" action="{{ route('customers.update') }}">
					@csrf
					<input type="hidden" class="form-control" id="" name="ID" placeholder="" value="{{ $customer->id }}" />
					<div class="form-group">
						<label for="exampleInputEmail1">First Name</label>
						<input type="text" class="form-control" id="" name="FirstName" placeholder="" value="{{ $customer->fname }}" />
					</div>
					<div class="form-group">
						<label for="exampleInputEmail1">Middle Name</label>
						<input type="text" class="form-control" id="" name="MiddleName" placeholder="" value="{{ $customer->mname }}" />
					</div>
					<div class="form-group">
						<label for="exampleInputEmail1">Last Name</label>
						<input type="text" class="form-control" id="" name="LastName" placeholder="" value="{{ $customer->lname }}" />
					</div>
					<div class="form-group">
						<label for="exampleInputEmail1">Phone Number</label>
						<input type="tel" class="form-control" id="" name="PhoneNumber" placeholder=""value="{{ $customer->phone }}" />
						<small id="emailHelp" class="form-text text-muted">We'll never share your phone with anyone else.</small>
					</div>
		
					<div class="form-group">
						<label for="exampleInputPassword1">Email</label>
						<input type="email" class="form-control" id="" name="Email" placeholder="" value="{{ $customer->email }}" />
						<small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
					</div>
		
					<br />
					<button type="submit" class="btn btn-primary">Save Changes</button>
				</form>
		
			</div>
		</div>
	</div>
</div>
	

<div class="row">
	<div class="col-md-6">
			<div class="panel">
				<div class="panel-heading">
					<h3 class="panel-title">Change Password</h3>
			
				</div>
				<div class="panel-body">
			
					<form method="POST" action="{{ route('customers.password_change') }}" onsubmit="return validatePassword();">
						@csrf
						<input type="hidden" class="form-control" id="" name="ID" placeholder="" value="{{ $customer->id }}" />

						<div class="form-group">
							<label for="">New Password</label>
							<input type="password" class="form-control" id="PasswordString" name="PasswordString" placeholder="Type new password" autocomplete="off" />
							<small id="emailHelp" class="form-text text-muted">This will override the existing password</small>
						</div>
						<div class="form-group">
							<label for="">Re-enter Password</label>
							<input type="password" class="form-control" id="RePasswordString" placeholder="Re-type password" autocomplete="off" />
						</div>
			
						<br />
						<button type="submit" class="btn btn-primary">Apply</button>
					</form>
			
				</div>
			</div>
		</div>
	
		<div class="col-md-6">
			<div class="panel">
				<div class="panel-heading">
					<h3 class="panel-title">Account Activation</h3>
			
				</div>
				<div class="panel-body">
			
					<form method="POST" action="{{ route('customers.auth_status_change') }}">
						@csrf
						<input type="hidden" class="form-control" id="" name="ID" placeholder="" value="{{ $customer->id }}" />

						 <label>{{__('Account Status')}}</label>
						 <select class="form-control demo-select2" name="LoginStatus" data-minimum-results-for-search="Infinity">
							 <option  @if ($customer->LoginStatus == '1') selected @endif value="1">{{__('Active')}}</option>
							 <option @if ($customer->LoginStatus == '0') selected @endif value="0">{{__('Disabled')}}</option>
							 <option @if ($customer->LoginStatus == '3') selected @endif value="3">{{__('Blocked')}}</option>
						 </select>
			
						<br />
						<button type="submit" style="margin-top:15px" class="btn btn-primary">Apply</button>
					</form>
			
				</div>
			</div>
		</div>
</div>

<script>
	function validatePassword() {
		var v1 = $('#PasswordString').val();
		var v2 = $('#RePasswordString').val();
		if (v1 == v2) {
			return true;
		}
		else {
			alert('Password must match');
			return false;
		}
	}
</script>

@endsection
