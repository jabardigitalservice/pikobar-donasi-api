@extends('layouts.auth')

@section('title')
    Login
@endsection

@section('content')
    <div class="login-box">
        <div class="login-logo">
            <a href="{{ route('backend::home') }}">{{$app_site_title}}</a>
        </div>
        <div class="login-box-body">
            <div class="page-header text-center">
                <h4>LOGIN</h4>
            </div>
            <form action="{!! route('login') !!}" method="post" id="login">
                {{ csrf_field() }}
                <div class="form-group has-feedback{{ $errors->has('email') ? ' has-error' : '' }}">
                    <input type="email" class="form-control" name="email" value="{{ old('email') }}"
                           placeholder="your@email.com" required>
                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                    @if ($errors->has('email'))
                        <span class="help-block">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group has-feedback{{ $errors->has('password') ? ' has-error' : '' }}">
                    <input type="password" class="form-control" name="password" placeholder="password">
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                    @if ($errors->has('password'))
                        <span class="help-block">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group">
                    <div class="checkbox icheck">
                        <label>
                            <input type="checkbox" name="remember" id="remember"> Remember Me
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-block">
                        LOGIN
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
