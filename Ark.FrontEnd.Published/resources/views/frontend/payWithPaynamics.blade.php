@extends('frontend.layouts.app')

@section('content')

<div class="container">
	<div class="p-4">
		<h4>Redirecting to Paynamics..</h4>
	</div>
</div>

<form action="{{$_r->requestUrl}}" method="POST" id="paynamics_pay" style="display: none;">
	<!-- Note that the amount is in paise = 50 INR -->
	<!--amount need to be in paisa-->
	<input type="hidden" name="paymentrequest" value="{{$_r->paymentrequest}}" />
</form>

@endsection

@section('script')
    <script type="text/javascript">
        $(document).ready(function(){
			$('#paynamics_pay').submit()
        });
    </script>
@endsection
