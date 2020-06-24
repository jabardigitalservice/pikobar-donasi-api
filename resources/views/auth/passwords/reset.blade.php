@extends('layouts.auth')

@section('title')
    Reset Password
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
            <form method="POST" action="{{ route('password.update') }}" id="form_pass_change">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <div class="form-group has-feedback{{ $errors->has('email') ? ' has-error' : '' }}">
                    <input type="email" class="form-control" name="email" placeholder="your@email.com"
                           value="{{ $email ?? old('email') }}" required autocomplete="email">
                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                    @if ($errors->has('email'))
                        <span class="help-block">
                    <strong>{!! $errors->first('email') !!}</strong>
                </span>
                    @endif
                </div>
                <div class="form-group has-feedback{{ $errors->has('password') ? ' has-error' : '' }}">
                    <input type="password" class="form-control" name="password"
                           placeholder="Enter new password" required autocomplete="new-password">
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                    @if ($errors->has('password'))
                        <span class="help-block">
                    <strong>{!! $errors->first('password') !!}</strong>
                </span>
                    @endif
                </div>
                <div class="form-group has-feedback{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                    <input type="password" class="form-control" name="password_confirmation"
                           placeholder="Password confirmation" required autocomplete="new-password">
                    <span class="glyphicon glyphicon-log-in form-control-feedback"></span>
                    @if ($errors->has('password_confirmation'))
                        <span class="help-block">
                    <strong>{!! $errors->first('password_confirmation') !!}</strong>
                </span>
                    @endif
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-block">
                        SUBMIT
                    </button>
                </div>
            </form>
        </div>
    </div>

@endsection
