@extends('layouts.backend')

@section('title')
    User Profile
@endsection

@section('content')

    <section class="content-header">
        <h1>Add new user</h1>
        {!! $breadcrumb !!}
    </section>

    <section class="content">
        <div class="box box-primary">
            <div class="box-body">
                @if(session()->has('failed'))
                    <div class="alert alert-danger">
                        {{ session()->get('failed') }}
                    </div>
                @elseif(session()->has('success'))
                    <div class="alert alert-success alert-dismissible">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        {{ session()->get('success') }}
                    </div>
                @endif
                <form method="POST" class="form-horizontal" enctype="multipart/form-data"
                      action="{!! route('backend::users.store') !!}">
                    @csrf
                    <div class="row">
                        <div class="col-md-3">
                            <div>
                                <div class="box-body box-profile">
                                    <input type="file" class="hidden" name="avatar" id="avatar-profile"
                                           value=""
                                           placeholder="" accept="image/*"/>
                                    <input type="hidden" name="avatar_old" width="128px" height="128px"
                                           class="img-responsive" value=""/>
                                    <img id="preview-avatar-profile" width="128px" height="128px"
                                         class="profile-user-img img-responsive img-circle"
                                         data-src="holder.js/128x128?text=128x128">
                                    <div class="clear">&nbsp;</div>
                                    <h5 class="profile-username text-center">Your Avatar</h5>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-9">

                            <div class="form-group{!! $errors->has('username') ? ' has-error' : '' !!}">
                                <label for="username" class="col-sm-2 control-label">Username</label>
                                <div class="col-sm-10">
                                    <input name="username" id="username"
                                           value=""
                                           class="form-control">
                                    @if ($errors->has('username'))
                                        <span class="help-block">
                                            <strong>{!! $errors->first('username') !!}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <!-- /."form-group -->

                            <div class="form-group{!! $errors->has('email') ? ' has-error' : '' !!}">
                                <label for="email" class="col-sm-2 control-label">Email</label>
                                <div class="col-sm-10">
                                    <input name="email" type="email" id="email"
                                           value=""
                                           class="form-control">
                                    @if ($errors->has('email'))
                                        <span class="help-block">
                                            <strong>{!! $errors->first('email') !!}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <!-- /."form-group -->

                            <div class="form-group{!! $errors->has('password') ? ' has-error' : '' !!}">
                                <label for="password" class="col-sm-2 control-label">Password</label>
                                <div class="col-sm-10">
                                    <input name="password" type="password" id="password"
                                           value=""
                                           class="form-control">
                                    @if ($errors->has('password'))
                                        <span class="help-block">
                                            <strong>{!! $errors->first('password') !!}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <!-- /."form-group -->

                            <div class="form-group{!! $errors->has('first_name') ? ' has-error' : '' !!}">
                                <label for="first_name" class="col-sm-2 control-label">First Name</label>
                                <div class="col-sm-10">
                                    <input name="first_name" id="first_name"
                                           value=""
                                           class="form-control">
                                    @if ($errors->has('first_name'))
                                        <span class="help-block">
                                            <strong>{!! $errors->first('first_name') !!}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <!-- /."form-group -->

                            <div class="form-group{!! $errors->has('last_name') ? ' has-error' : '' !!}">
                                <label for="last_name" class="col-sm-2 control-label">Last Name</label>
                                <div class="col-sm-10">
                                    <input name="last_name" id="last_name"
                                           value=""
                                           class="form-control">
                                    @if ($errors->has('last_name'))
                                        <span class="help-block">
                                            <strong>{!! $errors->first('last_name') !!}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <!-- /."form-group -->

                            <div class="form-group{!! $errors->has('gender') ? ' has-error' : '' !!}">
                                <label for="gender" class="col-sm-2 control-label">Gender</label>
                                <div class="col-sm-10">
                                    <select class="select-remote form-control"
                                            id="gender"
                                            name="gender">
                                        <option value="">Select gender...</option>
                                        <option value="male">Male</option>
                                        <option value="female">Female</option>
                                        <option value="other">Other</option>
                                    </select>
                                    @if ($errors->has('gender'))
                                        <span class="help-block">
                                            <strong>{!! $errors->first('gender') !!}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <!-- /."form-group -->

                            <div class="form-group{!! $errors->has('role') ? ' has-error' : '' !!}">
                                <label for="role" class="col-sm-2 control-label">Role</label>
                                <div class="col-sm-10">
                                    <select class="select-remote form-control"
                                            id="roles"
                                            name="roles">
                                        @foreach($roles as $role)
                                            <option value="{{$role->id}}">{{$role->role_name}}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('roles'))
                                        <span class="help-block">
                                            <strong>{!! $errors->first('roles') !!}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <!-- /."form-group -->

                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </div>
                            <!-- /."form-group -->

                        </div>
                    </div>

                </form>
            </div>
        </div>
    </section>

@endsection

@push('scripts')
    <script src="{{ asset('js/imsky-holder-v2.9.0-0/holder.min.js') }}"></script>
    <script>
        $(document).on('change', '#avatar-profile', function () {
            CALL.previewImage(this, '#preview-avatar-profile');
        });
        $(document).on('click', '#preview-avatar-profile', function () {
            $('#avatar-profile').click();
        });
    </script>
@endpush