@extends('layouts.backend')

@section('title')
    Tambah data donasi
@endsection

@section('content')
    <section class="content-header">
        <h1>Tambah data donasi</h1>
        {!! $breadcrumb !!}
    </section>

    <section class="content">
        <div class="box box-primary">
            <div class="box-body">
                @if ($errors->has('errors'))
                    <div class="alert alert-danger alert-dismissible">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        <strong>{!! $errors->first('errors') !!}</strong>
                    </div>
                @endif
                <form method="POST" action="{{route('backend::statistics.store')}}">
                    @csrf
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group {!! $errors->has('personal_investor') ? ' has-error' : '' !!}">
                                    <label for="personal_investor" class="control-label">Jumlah Donatur
                                        Perorangan</label>
                                    <input type="number"
                                           id="personal_investor"
                                           class="form-control"
                                           name="personal_investor"
                                           value="{{ old('personal_investor') }}"
                                           required>
                                </div>
                                @if ($errors->has('personal_investor'))
                                    <span class="help-block">
                                        <strong>{!! $errors->first('personal_investor') !!}</strong>
                                    </span>
                                @else
                                    <span class="help-block">
                                        Data sebelumnya : <strong>{!! $data['personal_investor'] !!}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group{!! $errors->has('company_investor') ? ' has-error' : '' !!}">
                                    <label for="company_investor" class="control-label">Jumlah Donatur
                                        Perusahaan</label>
                                    <input type="number"
                                           id="company_investor"
                                           class="form-control"
                                           name="company_investor"
                                           value="{{ old('company_investor') }}"
                                           required>
                                </div>
                                @if ($errors->has('company_investor'))
                                    <span class="help-block">
                                        <strong>{!! $errors->first('company_investor') !!}</strong>
                                    </span>
                                @else
                                    <span class="help-block">
                                        Data sebelumnya : <strong>{!! $data['company_investor'] !!}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group{!! $errors->has('total_goods') ? ' has-error' : '' !!}">
                                    <label for="total_goods" class="control-label">Jumlah Barang Terkumpul</label>
                                    <input type="number"
                                           id="total_goods"
                                           class="form-control"
                                           name="total_goods"
                                           value="{{ old('total_goods') }}"
                                           required>
                                </div>
                                @if ($errors->has('total_goods'))
                                    <span class="help-block">
                                        <strong>{!! $errors->first('total_goods') !!}</strong>
                                    </span>
                                @else
                                    <span class="help-block">
                                        Data sebelumnya : <strong>{!! $data['total_goods'] !!}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group{!! $errors->has('total_cash') ? ' has-error' : '' !!}">
                                    <label for="total_cash" class="control-label">Jumlah Donasi Terkumpul</label>
                                    <input type="text"
                                           id="total_cash"
                                           class="form-control"
                                           name="total_cash"
                                           value="{{ old('total_cash') }}"
                                           required>
                                </div>
                                @if ($errors->has('total_cash'))
                                    <span class="help-block">
                                        <strong>{!! $errors->first('total_cash') !!}</strong>
                                    </span>
                                @else
                                    <span class="help-block">
                                        Data sebelumnya : <strong><div id="div_total_cash">{!! $data['total_cash'] !!}</div></strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        <button type="submit" id="btn-submit" class="btn btn-success">Submit</button>
                        <a href="{{route('backend::statistics.index')}}" class="btn btn-danger">Back</a>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script src="{{ asset('js/numerictext/autonumeric.js') }}"></script>
    <script>
        var currencyOptions = {
            digitGroupSeparator: '.',
            decimalCharacter: ',',
            decimalPlaces: 2,
            modifyValueOnWheel: false,
            selectNumberOnly: true,
            decimalCharacterAlternative: '.',
            currencySymbol: '',
            unformatOnSubmit: true
        };
        new AutoNumeric('#total_cash', currencyOptions);
        new AutoNumeric('#div_total_cash', currencyOptions);
    </script>
@endpush
