@extends('layouts.backend')

@section('title')
    User Profile
@endsection

@section('content')

    <section class="content-header">
        <h1>User Profile</h1>
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

            <!-- -->
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#nav-profile" data-toggle="tab">Profile</a></li>
                        <li><a href="#nav-password" data-toggle="tab">Password</a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="active tab-pane" id="nav-profile">
                            <form method="POST" class="form-horizontal" enctype="multipart/form-data"
                                  action="{!! route('backend::users.store') !!}">
                                {{ csrf_field() }}
                                <input type="hidden" id="hidden_id" name="hidden_id"
                                       value="{{isset($data) ? $data->id : ""}}"/>
                                <div class="row">
                                    <div class="col-md-3">
                                        <!-- Profile Image -->
                                        <div>
                                            <div class="box-body box-profile">
                                                @if(!empty(($data->avatar)))
                                                    <input type="file" class="hidden" name="avatar" id="avatar-profile"
                                                           value="{{ old('avatar') }}"
                                                           placeholder="" accept="image/*"/>
                                                    <input type="hidden" name="avatar_old" width="128" height="128"
                                                           class="img-responsive"
                                                           value="{!! $data->image->id !!}"/>
                                                    <img id="preview-avatar-profile" width="128px" height="128px"
                                                         class="profile-user-img img-responsive img-circle"
                                                            {!! $data->avatar ? ' src="'.asset(($data->image->image_url)).'"'
                                                            : ' data-src="holder.js/128x128?text=128x128"' !!}/>
                                                    <p class="text-muted text-center" style="margin-top:5px"><a
                                                                id="a-remove-profile-img" href="javascript:;">Remove
                                                            image</a></p>
                                                @else
                                                    <input type="file" class="hidden" name="avatar" id="avatar-profile"
                                                           value=""
                                                           placeholder="" accept="image/*"/>
                                                    <input type="hidden" name="avatar_old" width="128px" height="128px"
                                                           class="img-responsive" value=""/>
                                                    <img id="preview-avatar-profile" width="128px" height="128px"
                                                         class="profile-user-img img-responsive img-circle"
                                                         data-src="holder.js/128x128?text=128x128"/>
                                                    <p class="text-muted text-center" style="margin-top:5px"><a
                                                                id="a-empty-remove-profile-img" href="javascript:;">Remove
                                                            image</a></p>
                                                @endif
                                                <h3 class="profile-username text-center">{{$data->first_name . ' ' . $data->last_name}}</h3>
                                                <p class="text-muted text-center">{{$data->email}}</p>
                                            </div>
                                            <!-- /.box-body -->
                                        </div>
                                        <!-- /.box -->
                                    </div>
                                    <!-- /.col -->

                                    <div class="col-md-9">

                                        <div class="form-group{!! $errors->has('username') ? ' has-error' : '' !!}">
                                            <label for="nick_name" class="col-sm-2 control-label">Username</label>
                                            <div class="col-sm-10">
                                                @php $nickname = isset($data) ? ($data->username) : ""; @endphp
                                                <input name="username" id="username"
                                                       value="{{ old('username') ? old('username') : $nickname }}"
                                                       class="form-control" readonly>
                                                @if ($errors->has('username'))
                                                    <span class="help-block">
                                            <strong>{!! $errors->first('username') !!}</strong>
                                        </span>
                                                @endif
                                            </div>
                                        </div>
                                        <!-- /."form-group -->

                                        <div class="form-group{!! $errors->has('first_name') ? ' has-error' : '' !!}">
                                            <label for="first_name" class="col-sm-2 control-label">First Name</label>
                                            <div class="col-sm-10">
                                                @php $first_name = isset($data) ? ($data->first_name) : ""; @endphp
                                                <input name="first_name" id="first_name"
                                                       value="{{ old('first_name') ? old('first_name') : $first_name }}"
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
                                                @php $last_name = isset($data) ? ($data->last_name) : ""; @endphp
                                                <input name="last_name" id="last_name"
                                                       value="{{ old('last_name') ? old('last_name') : $last_name }}"
                                                       class="form-control">
                                                @if ($errors->has('last_name'))
                                                    <span class="help-block">
                                            <strong>{!! $errors->first('last_name') !!}</strong>
                                        </span>
                                                @endif
                                            </div>
                                        </div>
                                        <!-- /."form-group -->



                                        <div class="form-group{!! $errors->has('email') ? ' has-error' : '' !!}">
                                            <label for="email" class="col-sm-2 control-label">Email</label>
                                            <div class="col-sm-10">
                                                @php $email = isset($data) ? ($data->email) : ""; @endphp
                                                <input name="email" type="email" id="email"
                                                       value="{{ old('email') ? old('email') : $email }}"
                                                       class="form-control">
                                                @if ($errors->has('email'))
                                                    <span class="help-block">
                                            <strong>{!! $errors->first('email') !!}</strong>
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
                                                    <option {!! $data->gender == 'male' ? 'selected' : '' !!} value="male">
                                                        Male
                                                    </option>
                                                    <option {!! $data->gender == 'female' ? 'selected' : '' !!} value="female">
                                                        Female
                                                    </option>
                                                    <option {!! $data->gender == 'other' ? 'selected' : '' !!} value="other">
                                                        Other
                                                    </option>
                                                </select>
                                                @if ($errors->has('gender'))
                                                    <span class="help-block">
                                            <strong>{!! $errors->first('gender') !!}</strong>
                                        </span>
                                                @endif
                                            </div>
                                        </div>
                                        <!-- /."form-group -->
                                        @php
                                            $loggedUser = auth()->user()->roles()->pluck('roles.slug')->toArray();
                                        @endphp
                                        @if($loggedUser[0] === 'owner' || $loggedUser[0] === 'administrator')
                                            <div class="form-group{!! $errors->has('roles') ? ' has-error' : '' !!}">
                                                <label for="roles" class="col-sm-2 control-label">Role</label>
                                                <div class="col-sm-10">
                                                    <select class="select-remote form-control"
                                                            id="roles"
                                                            name="roles">
                                                        @foreach($roles as $role)
                                                            <option {{in_array($role->id, $listSelectedRole ?: []) ? "selected": ""}} value="{{$role->id}}">{{$role->role_name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <!-- /."form-group -->
                                        @else
                                            <div class="form-group{!! $errors->has('roles') ? ' has-error' : '' !!}">
                                                <label for="roleFake" class="col-sm-2 control-label">Role</label>
                                                <div class="col-sm-10">
                                                    <select disabled="" class="select-remote form-control"
                                                            id="roleFake"
                                                            name="roleFake">
                                                        @foreach($roles as $role)
                                                            <option {{in_array($role->id, $listSelectedRole ?: []) ? "selected": ""}} value="{{$role->id}}">{{$role->role_name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        @endif

                                        <div class="form-group">
                                            <div class="col-sm-offset-2 col-sm-10">
                                                <button type="submit" class="btn btn-primary">Submit</button>
                                            </div>
                                        </div>

                                    </div>
                                    <!-- /.col -->

                                </div>

                            </form>
                        </div>

                        <div class="tab-pane" id="nav-password">
                            <form method="POST" class="form-horizontal" enctype="multipart/form-data"
                                  action="{!! route('backend::users.password') !!}">
                                {{ csrf_field() }}
                                <input type="hidden" id="hidden_id" name="hidden_id"
                                       value="{{isset($data) ? $data->id : ""}}"/>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group{!! $errors->has('existing_password') ? ' has-error' : '' !!}">
                                            <label for="existing_password" class="col-sm-2 control-label">Existing
                                                Password</label>
                                            <div class="col-sm-10">
                                                <input name="existing_password" type="password" id="existing_password"
                                                       value=""
                                                       class="form-control">
                                                @if ($errors->has('existing_password'))
                                                    <span class="help-block">
                                                        <strong>{!! $errors->first('existing_password') !!}</strong>
                                                    </span>
                                                @else
                                                    <span class="help-block">
                                                    For security reasons, you must verify your existing password before you may set a new password.
                                                </span>
                                                @endif
                                            </div>
                                        </div>
                                        <!-- /."form-group -->


                                        <div class="form-group{!! $errors->has('email') ? ' has-error' : '' !!}">
                                            <label for="existing_password" class="col-sm-2 control-label">New
                                                Password</label>
                                            <div class="col-sm-10">
                                                <input name="new_password" type="password" id="new_password"
                                                       value=""
                                                       class="form-control">
                                                @if ($errors->has('new_password'))
                                                    <span class="help-block">
                                                        <strong>{!! $errors->first('new_password') !!}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <!-- /."form-group -->

                                        <div class="form-group{!! $errors->has('email') ? ' has-error' : '' !!}">
                                            <label for="confirm_password" class="col-sm-2 control-label">Confirm New
                                                Password</label>
                                            <div class="col-sm-10">
                                                <input name="confirm_password" type="password" id="confirm_password"
                                                       value=""
                                                       class="form-control">
                                                @if ($errors->has('confirm_password'))
                                                    <span class="help-block">
                                                        <strong>{!! $errors->first('confirm_password') !!}</strong>
                                                    </span>
                                                @endif
                                                <span class="help-block" id='message'></span>
                                            </div>
                                        </div>
                                        <!-- /."form-group -->
                                        <div class="form-group">
                                            <div class="col-sm-offset-2 col-sm-10">
                                                <button type="submit" class="btn btn-primary">Submit</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- -->
            </div>
        </div>
    </section>

@endsection

@push('scripts')
    <script src="{{ asset('js/imsky-holder-v2.9.0-0/holder.min.js') }}"></script>
    <script>
        var deleteImage = $('#a-empty-remove-profile-img');

        $(document).on('change', '#avatar-profile', function () {
            CALL.previewImage(this, '#preview-avatar-profile');
            deleteImage.show();
        });
        $(document).on('click', '#preview-avatar-profile', function () {
            $('#avatar-profile').click();
        });
        $('#confirm_password').on('keyup', function () {
            if ($('#new_password').val() == $('#confirm_password').val()) {
                $('#message').html('Password match.').css('color', 'green');
            } else {
                $('#message').html('Password not match.').css('color', 'red');
            }
        });
        // remove category image
        deleteImage.hide();
        deleteImage.click(function () {
            document.getElementById("avatar").value = null;
            $('#preview-avatar-profile').removeAttr('src');
            var image = $('#preview-avatar-profile').attr({
                "data-src": "holder.js/128x128?text=128x128"
            });
            Holder.run({
                images: image[0]
            });
            deleteImage.hide();
        });
    </script>

    @if(isset($data))
        <script>
            $('#a-remove-profile-img').click(function () {
                removeImage = true;
                $.ajax({
                    url: '{!! route($route.".remove.media", $data->id) !!}',
                    type: 'POST',
                    data: {
                        "image_id": '{{ $data->avatar ? $data->avatar : ""  }}'
                    },
                    success: function () {
                        $('#preview-avatar-profile').removeAttr('src');
                        var image = $('#preview-avatar-profile').attr({
                            "data-src": "holder.js/128x128?text=128x128"
                        });
                        Holder.run({
                            images: image[0]
                        });
                        $('#a-remove-profile-img').hide();
                    },
                    error: function (result) {
                        alert('error');
                    }
                });
                return false;
            });
        </script>
    @endif
@endpush
