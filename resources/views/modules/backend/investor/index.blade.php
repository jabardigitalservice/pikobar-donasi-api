@extends('layouts.backend')

@push('custom-style')
    <link rel="stylesheet" href="{{ asset('plugins/datatables/css/datatables.min.css') }}">
@endpush

@section('title')
    List Donatur
@endsection

@section('content')
    <section class="content-header">
        <h1>List Donatur</h1>
        {!! $breadcrumb !!}
    </section>
    <section class="content">
        <div class="box box-primary">
            <div class="box-header with-border">
                <div id="status" class="form-group-sm form-inline">
                    <input type="text" name="donatur_name_filter"
                           class="form-control"
                           id="donatur_name_filter"
                           placeholder="Cari nama..."/>
                    <label class="small" for="donatur_type">Tipe Donatur</label>
                    <select style="width: 100px;" class="input-sm" id="donatur_type"
                            name="donatur_type">
                        <option value="tunai">Tunai</option>
                        <option value="logistik">Sembako</option>
                    </select>
                    <label class="small" for="donatur_status">Status Donatur</label>
                    <select style="width: 150px;" class="input-sm" id="donatur_status"
                            name="donatur_status">
                        <option value="">-</option>
                        <option value="verified">Ter-verifikasi</option>
                        <option value="not_verified">Un-Verifikasi</option>
                        <option value="rejected">Rejected</option>
                    </select>
                    <button class="btn btn-default btn-sm" id="buttonFilter">
                        Filter
                    </button>
                </div>
            </div>

            <div class="box-body">
                <table class="table table-striped table-bordered table-datatables" width="100%">
                    <thead>
                    <tr>
                        <th style="visibility: hidden">No</th>
                        <th>Tgl Terverifikasi</th>
                        <th>Tgl Pendaftaran</th>
                        <th style="visibility: hidden">Tipe</th>
                        <th style="visibility: hidden">Id</th>
                        <th>Nama Donatur</th>
                        <th>Kontak</th>
                        <th>Status</th>
                        <th>Perwakilan</th>
                        <th>Nominal</th>
                        <th>Jumlah</th>
                        <th>Bukti Transfer</th>
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
            var table = $('.table-datatables').DataTable({
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
                        visible: false,
                        className: 'center'
                    },
                    {data: 'donate_date'},
                    {data: 'donate_date'},
                    {
                        data: 'donate_category',
                        name: 'donate_category',
                        visible: false,
                        className: 'center'
                    },
                    {
                        data: 'id',
                        name: 'id',
                        width: '5%',
                        visible: false,
                        className: 'center'
                    },
                    {data: 'investor_name'},
                    {data: 'email'},
                    {data: 'donate_status_name'},
                    {data: 'category_name'},
                    {data: 'amount'},
                    {
                        data: 'quantity',
                        name: 'quantity',
                        visible: false,
                        className: 'center'
                    },
                    {
                        data: 'attachment_id',
                        name: 'attachment_id',
                        width: '10%',
                        className: 'center'
                    },
                ],
                order: [[1, "asc"]],
                columnDefs: [
                    {targets: 0, sortable: false, orderable: false},
                ],
                drawCallback: function () {
                    INIT.tooltip();
                    INIT.run();
                },
            });

            $('#buttonFilter').on('click', function () {
                var donaturType = $('#donatur_type').val();
                var donaturStatus = $('#donatur_status').val();
                var donaturNameFilter = $('#donatur_name_filter').val();
                table.column(3).search(donaturType);
                table.column(5).search(donaturNameFilter);
                table.column(7).search(donaturStatus);
                if (donaturType === 'tunai') {
                    table.column(9).visible(true);
                    table.column(10).visible(false);
                } else {
                    table.column(9).visible(false);
                    table.column(10).visible(true);
                }
                table.draw();
            });

        });
    </script>
@endpush