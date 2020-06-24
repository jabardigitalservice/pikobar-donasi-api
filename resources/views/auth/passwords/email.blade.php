@extends('layouts.auth')

@section('title')
    Forgot Password
@endsection

@section('content')
    <div class="login-box">
        <div class="register-logo">
            <a href="{{ route('backend::home') }}">{{$app_site_title}}</a>
        </div>
        <div class="login-box-body">
            <div class="page-header text-center">
                <h4>FORGOT PASSWORD</h4>
            </div>
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif
            <form method="POST" action="{{ route('password.email') }}" id="forgot">
                {{ csrf_field() }}
                <div class="form-group has-feedback{{ $errors->has('email') ? ' has-error' : '' }}">
                    <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}"
                           placeholder="your@email.com" required autocomplete="email">
                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                    @if ($errors->has('email'))
                        <span class="help-block">
                    <strong>{{ $errors->first('email') }}</strong>
                </span>
                    @endif
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-block">
                        SEND ME EMAIL CONFIRMATION
                    </button>
                </div>
            </form>

            <ul class="nav nav-pills nav-justified">
                <li><a href="{!! route('login') !!}">LOGIN</a></li>
                @if(odk_get_settings('enable_registrations')==1)
                    <li><a href="{!! route('register') !!}">REGISTER</a></li>
                @endif
            </ul>
        </div>
    </div>
@endsection

