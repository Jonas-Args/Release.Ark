@extends('layouts.blank')

@section('content')
<div class="cls-content-sm panel">
    <div class="panel-body">
        <h1 class="h3">{{ __('Reset Password') }}</h1>
        <p class="pad-btm">{{__('Enter your new password and confirm password.')}} </p>
        <form method="POST" action="{{ route('client.password.change') }}">
            @csrf

            <input type="hidden" name="ID" value="{{ $id }}" />

            <div class="form-group">
                <input id="password" type="password" class="form-control" name="PasswordString" placeholder="New Password" required />
            </div>

            <div class="form-group">
                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" placeholder="Confirm Password" required />
            </div>

            <div class="form-group text-right">
                <button type="submit" class="btn btn-primary btn-lg btn-block">
                    {{ __('Reset Password') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
