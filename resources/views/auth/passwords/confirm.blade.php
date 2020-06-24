@extends('layouts.auth')

@section('title')
    Password Confirm
@endsection

@section('content')

    <div class="login-box">
        <div class="login-logo">
            <a href="{{ route('backend::home') }}">{{$app_site_title}}</a>
        </div>
        <div class="login-box-body">
            <div class="page-header text-center">
                <h4>Reset Password</h4>
            </div>
            <form method="POST" action="{{ route('password.confirm') }}" id="form_confirm">
                @csrf
                <div class="form-group has-feedback{{ $errors->has('email') ? ' has-error' : '' }}">
                    <input type="email" class="form-control" name="email" placeholder="your@email.com"
                           autofocus>
                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                    @if ($errors->has('email'))
                        <span class="help-block">
                    <strong>{!! $errors->first('email') !!}</strong>
                </span>
                    @endif
                </div>
                <div class="form-group has-feedback{{ $errors->has('password') ? ' has-error' : '' }}">
                    <input type="password" class="form-control" name="password" value=""
                           placeholder="Enter new password">
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                    @if ($errors->has('password'))
                        <span class="help-block">
                    <strong>{!! $errors->first('password') !!}</strong>
                </span>
                    @endif
                </div>
                <div class="form-group has-feedback{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                    <input type="password" class="form-control" name="password_confirmation" value=""
                           placeholder="Password confirmation">
                    <span class="glyphicon glyphicon-log-in form-control-feedback"></span>
                    @if ($errors->has('password_confirmation'))
                        <span class="help-block">
                    <strong>{!! $errors->first('password_confirmation') !!}</strong>
                </span>
                    @endif
                </div>
                <button type="submit" class="btn btn-primary btn-block">
                    CONFIRM PASSWORD
                </button>
            </form>
        </div>
    </div>

@endsection
