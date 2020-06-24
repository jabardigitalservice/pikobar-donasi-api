@extends('layouts.backend')

@push('custom-style')
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="{{ asset('css/sidebar-builder/sidebar-builder.css') }}">
    <style>
        .tab-pan-title {
            background-color: #f47536 !important;
            font-weight: 600;
            padding: 5px;
            margin-top: 10px;
            color: #fff;
        }
        .form-group, .tab-pan-title {
            margin-bottom: 10px;
        }
        .box-body {
            min-height: 420px;
        }
    </style>
@endpush

@section('title')
    Sidebar
@endsection

@section('content')
    <section class="content-header">
        <h1>Sidebars</h1>
        {!! $breadcrumb !!}
    </section>
    <section class="content">

        @if (count($errors) > 0)
            @foreach ($errors->all() as $error)
                <div class="alert alert-danger alert-dismissible" id="formMessage" role="alert">
                    {{ $error }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endforeach
        @endif
        <div class="box box-primary">
        </div>
        <div class="box-body">
            <div class='row' style="min-height:700px;">
                <div class="col-md-5">
                    <div class="nav-tabs-custom">

                        <ul class="nav nav-tabs primary">
                            <li>
                                <a href="#" data-action="LOAD" data-load-to='#menu-entry'
                                   data-href='{{url('admin/sidebars/show')}}/{!!config('covid19.DEFAULT_SIDEBAR_ID')!!}'>
                                    Admin Menu &nbsp;<span class="pull-right">
                                        <i class="fa fa-angle-double-right"></i>
                                    </span>
                                </a>
                            </li>
                        </ul>

                        <!-- LIST OF SIDEBAR -->
                        <div class="tab-content">
                            <div class="tab-pane active" id="details">
                                <div class="dd" id="nestable">
                                    <ol class="dd-list">
                                        @php
                                            $menuData = odk_admin_sidebar(false);
                                        @endphp
                                        @if(isset($menuData))
                                            @foreach($menuData as $menu)
                                                <li class="dd-item dd3-item" data-id="{!!$menu['id']!!}">
                                                    <div class="dd-handle dd3-handle"></div>
                                                    <div class="dd3-content">
                                                        @if (empty($menu['children']))
                                                            @if(isset($menu['url']))
                                                                <a href="#" data-action="LOAD"
                                                                   data-load-to='#menu-entry'
                                                                   data-href='{{url('admin/sidebars/showChildForm')}}/{!!$menu['id']!!}'>
                                                                    <i class="{!! !empty($menu['icon']) ?  $menu['icon'] : '' !!}">
                                                                    </i> {!!$menu['menu_title']!!}
                                                                    <span class="pull-right">
                                                                        <i class="fa fa-angle-double-right"></i>
                                                                    </span>
                                                                </a>
                                                            @endif
                                                        @else
                                                            <a href="#" data-action="LOAD" data-load-to='#menu-entry'
                                                               data-href='{{url('admin/sidebars/show')}}/{!!$menu['id']!!}'>
                                                                <i class="{!! !empty($menu['icon']) ?  $menu['icon'] : '' !!}"></i> {!!$menu['menu_title']!!}
                                                                <span class="pull-right">
                                                                <i class="fa fa-angle-double-right"></i>
                                                            </span>
                                                            </a>
                                                        @endif
                                                    </div>
                                                    <ol class="dd-list">
                                                        @include('modules/backend/sidebar/sub', ['menu' => $menu['children']])
                                                    </ol>
                                                </li>
                                            @endforeach
                                        @endif
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="loading-content">
                        <span class="spinner"></span>
                    </div>
                    <div id='menu-entry'></div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script src="{{ asset('js/jquery/jquery-ui/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('js/sidebar-builder/index.js') }}"></script>

    <script type="text/javascript">
        $(document).ready(function () {
            var updateMenu = function (e) {

                $('.loading-content').show();
                $('#menu-entry').hide();

                var out = $(e.target).nestable('serialize');
                out = JSON.stringify(out);
                var formData = new FormData();
                formData.append('tree', out);
                // default admin root
                var url = '{!! url('admin/sidebars/menu/'. config("covid19.DEFAULT_SIDEBAR_ID") .'/tree') !!}';
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (data, textStatus, jqXHR) {
                        console.log(data);
                        $(".loading-content").hide();
                        $('#menu-entry').show();
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        var msg = "Sorry but there was an error: ";
                        Swal.fire({
                            icon: 'error',
                            text: msg,
                            type: 'warning',
                        });
                        $(".loading-content").hide();
                        $('#menu-entry').show();
                    }
                });
            };

            $('.dd').nestable().on('change', updateMenu);

            $('.loading-content').show();

            //load first root menu
            $('#menu-entry').load('{{url('admin/sidebars/show')}}/{{config("covid19.DEFAULT_SIDEBAR_ID")}}',
                function (response, status, xhr) {
                    if (status === "error") {
                        var msg = "Sorry but there was an error: ";
                        Swal.fire({
                            icon: 'error',
                            text: msg,
                            type: 'warning',
                        });
                    }
                    $(".loading-content").hide();
                    $('#menu-entry').show();
                });

            $('body').on('click', '[data-action]', function (e) {
                e.preventDefault();
                $('.loading-content').show();
                $('#menu-entry').hide();
                var $tag = $(this);
                $('#menu-entry').load($tag.data('href'),
                    function (response, status, xhr) {
                        if (status === "error") {
                            var msg = "Sorry but there was an error: ";
                            Swal.fire({
                                icon: 'error',
                                text: msg,
                                type: 'warning',
                            });
                        }
                        $(".loading-content").hide();
                        $('#menu-entry').show();
                    });
            });
        });
    </script>
@endpush
