@extends('layouts.auth')

@section('title')
    Password Verification
@endsection

@section('content')
    <div class="login-box">
        <div class="login-logo">
            <a href="{{ route('backend::home') }}">{{$app_site_title}}</a>
        </div>
        <div class="login-box-body">
            <div class="page-header text-center">
                @if (session('resent'))
                    <div class="alert alert-success" role="alert">
                        {{ __('A fresh verification link has been sent to your email address.') }}
                    </div>
                @endif
            </div>
            <form method="POST" action="{{ route('verification.resend') }}">
                @csrf
                <button type="submit" class="btn btn-primary btn-block">
                    RESEND EMAIL
                </button>
            </form>
        </div>
    </div>
@endsection
