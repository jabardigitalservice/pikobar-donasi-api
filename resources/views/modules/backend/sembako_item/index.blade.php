@extends('layouts.backend')

@push('custom-style')
    <link rel="stylesheet" href="{{ asset('plugins/datatables/css/datatables.min.css') }}">
@endpush

@section('title')
    Item Sembako
@endsection

@section('content')
    <section class="content-header">
        <h1>Item Sembako</h1>
        {!! $breadcrumb !!}
    </section>
    <section class="content">
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
        <div class="box box-primary">
            <div class="box-header with-border">
                <a href="{{route('backend::sembako-items.showCreate')}}"
                   class="btn btn-success">Tambah data item sembako</a>
            </div>
            <div class="box-body">
                <table id="table-sembako-item" class="table table-striped table-bordered table-datatables" width="100%">
                    <thead>
                    <tr>
                        <th>No</th>
                        <th style="visibility: hidden">Id</th>
                        <th>Sku</th>
                        <th>Nama</th>
                        <th>Qty</th>
                        <th>Uom</th>
                        <th>Nama Uom</th>
                        <th>Deskripsi</th>
                        <th>Status</th>
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
            $('#table-sembako-item').DataTable({
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
                        data: 'no',
                        name: 'no',
                        width: '5%',
                        visible: true,
                        className: 'center'
                    },
                    {
                        data: 'id',
                        name: 'id',
                        width: '5%',
                        visible: false,
                        className: 'center'
                    },
                    {
                        data: 'item_sku'
                    },
                    {
                        data: 'item_name',
                        className: 'center'
                    },
                    {
                        data: 'quantity',
                        className: 'center'
                    },
                    {
                        data: 'uom',
                        className: 'center'
                    },
                    {
                        data: 'uom_name',
                        className: 'center'
                    },
                    {
                        data: 'package_description',
                        className: 'center'
                    },
                    {
                        data: 'status',
                        className: 'center'
                    },
                ],
                order: [[2, "desc"]],
                columnDefs: [
                    {targets: 0, sortable: false, orderable: false},
                    {targets: 1, sortable: false, orderable: false},
                    {targets: 2, sortable: true, orderable: true},
                    {targets: 3, sortable: true, orderable: true},
                    {targets: 4, sortable: true, orderable: true},
                    {targets: 5, sortable: true, orderable: true},
                    {targets: 6, sortable: true, orderable: true},
                    {targets: 7, sortable: true, orderable: true},
                    {targets: 8, sortable: true, orderable: true},
                ],
                drawCallback: function () {
                    INIT.tooltip();
                    INIT.run();
                },
            });
        });
    </script>
@endpush