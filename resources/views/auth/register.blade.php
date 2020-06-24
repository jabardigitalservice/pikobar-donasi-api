@extends('layouts.auth')

@section('title')
    Register
@endsection

@section('content')
    <div class="login-box">
        <div class="login-logo">
            <a href="{{ route('backend::home') }}">{{$app_site_title}}</a>
        </div>
        <div class="register-box-body">
            <div class="page-header text-center">
                <h4>REGISTER</h4>
            </div>
            <form method="POST" action="{{ url('/register') }}" id="register">
                {{ csrf_field() }}
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group has-feedback{{ $errors->has('email') ? ' has-error' : '' }}">
                            <input class="form-control" name="first_name" value="{{ old('first_name') }}"
                                   placeholder="First Name">
                            <span class="glyphicon glyphicon-user form-control-feedback"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group has-feedback{{ $errors->has('email') ? ' has-error' : '' }}">
                            <input class="form-control" name="last_name" value="{{ old('last_name') }}"
                                   placeholder="Last Name">
                            <span class="glyphicon glyphicon-user form-control-feedback"></span>
                        </div>
                    </div>
                </div>
                <div class="form-group has-feedback{{ $errors->has('name') ? ' has-error' : '' }}">
                    <input type="text" class="form-control" name="username" value="{{ old('username') }}"
                           placeholder="JohnDoe553">
                    <span class="glyphicon glyphicon-user form-control-feedback"></span>
                    @if ($errors->has('username'))
                        <span class="help-block">
                    <strong>{!! $errors->first('username') !!}</strong>
                </span>
                    @endif
                </div>
                <div class="form-group has-feedback{{ $errors->has('email') ? ' has-error' : '' }}">
                    <input type="email" class="form-control" name="email" value="{{ old('email') }}"
                           placeholder="your@email.com">
                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                    @if ($errors->has('email'))
                        <span class="help-block">
                    <strong>{!! $errors->first('email') !!}</strong>
                </span>
                    @endif
                </div>


                <div class="form-group has-feedback{{ $errors->has('password') ? ' has-error' : '' }}">
                    <input type="password" class="form-control" name="password"
                           placeholder="your password">
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                    @if ($errors->has('password'))
                        <span class="help-block">
                    <strong>{!! $errors->first('password') !!}</strong>
                </span>
                    @endif
                </div>
                <div class="form-group has-feedback{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                    <input type="password" class="form-control" name="password_confirmation"
                           placeholder="password confirmation">
                    <span class="glyphicon glyphicon-log-in form-control-feedback"></span>
                    @if ($errors->has('password_confirmation'))
                        <span class="help-block">
                            <strong>{!! $errors->first('password_confirmation') !!}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group">
                    <div class="checkbox icheck">
                        <label>
                            <input id="chk_agree" name="chk_agree" type="checkbox"> I agree to the <a
                                    href="javascript:void(0);">terms</a>
                        </label>
                        <span class="help-block">
                            <strong>{!! $errors->first('chk_agree') !!}</strong>
                        </span>
                    </div>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-block">
                        REGISTER
                    </button>
                </div>
            </form>
            <ul class="nav nav-pills nav-justified">
                <li><a href="{!! route('login') !!}">LOGIN</a></li>
            </ul>
        </div>

    </div>
@endsection
