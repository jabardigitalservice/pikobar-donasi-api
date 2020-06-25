@extends('layouts.backend')

@push('custom-style')
    <link rel="stylesheet" href="{{ asset('plugins/datatables/css/datatables.min.css') }}">
@endpush

@section('title')
    List Users
@endsection

@section('content')
    <section class="content-header">
        <h1>Users</h1>
        {!! $breadcrumb !!}
    </section>
    <section class="content">
        <div class="box box-primary">
            <div class="box-header with-border">
                <a href="{!! route('backend::users.showCreate') !!}" class="btn btn-primary" title="">Add new</a>
            </div>
            <div class="box-body">
                <table class="table table-striped table-bordered table-datatables" width="100%">
                    <thead>
                    <tr>
                        <th style="visibility: hidden">Id</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>

        </div>
    </section>
@endsection

@push('scripts')
    <script src="{{ asset('plugins/datatables/js/datatables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/js/datatables.bootstrap.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/js/datatables.buttons.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/js/buttons.bootstrap.min.js') }}"></script>
    <script>
        $(function () {
            $('.table-datatables').DataTable({
                processing: true,
                serverSide: true,
                lengthChange: true,
                fixedColumns: true,
                autoWidth: false,
                fixedHeader: {
                    "header": false,
                    "footer": false
                },
                ajax: '{!! route($route.".datatables") !!}',
                columns: [
                    {
                        data: 'id',
                        name: 'id',
                        width: '5%',
                        visible: false,
                        className: 'center'
                    },
                    {data: 'username'},
                    {data: 'email'},
                    {data: 'role_name'},
                    {data: 'action', orderable: false, searchable: false, width: '15%', className: 'center action'}
                ],
                order: [[0, "asc"]],
                columnDefs: [
                    {targets: 0, sortable: false, orderable: false},
                    {targets: 1, sortable: true, orderable: true},
                    {targets: 2, width: '120px', sortable: true, orderable: true},
                    {targets: 3, sortable: false, orderable: false},
                    {targets: 4, width: '5%', sortable: false, orderable: false}
                ],
                drawCallback: function () {
                    INIT.tooltip();
                    INIT.run();
                },
            });
        });
    </script>
@endpush