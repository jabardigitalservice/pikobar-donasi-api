@extends('layouts.backend')

@section('title')
    Edit Paket Sembako
@endsection

@section('content')
    <section class="content-header">
        <h1>Edit data</h1>
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
                <form method="POST" action="{{route('backend::sembako-items.update', $data['id'])}}">
                    @csrf
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group {!! $errors->has('item_sku') ? ' has-error' : '' !!}">
                                    <label for="sku" class="control-label">SKU</label>
                                    <input type="text"
                                           id="sku"
                                           class="form-control"
                                           name="item_sku"
                                           value="{{ $data['item_sku'] }}"
                                           required>
                                </div>
                                @if ($errors->has('item_sku'))
                                    <span class="help-block">
                                        <strong>{!! $errors->first('item_sku') !!}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group{!! $errors->has('item_name') ? ' has-error' : '' !!}">
                                    <label for="item_name" class="control-label">Nama Item</label>
                                    <input type="text"
                                           id="item_name"
                                           class="form-control"
                                           name="item_name"
                                           value="{{ $data['item_name'] }}"
                                           required>
                                </div>
                                @if ($errors->has('item_name'))
                                    <span class="help-block">
                                        <strong>{!! $errors->first('item_name') !!}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group{!! $errors->has('quantity') ? ' has-error' : '' !!}">
                                    <label for="quantity" class="control-label">Qty</label>
                                    <input type="number"
                                           id="quantity"
                                           class="form-control"
                                           name="quantity"
                                           value="{{ $data['quantity'] }}"
                                           required>
                                </div>
                                @if ($errors->has('quantity'))
                                    <span class="help-block">
                                        <strong>{!! $errors->first('quantity') !!}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group{!! $errors->has('uom') ? ' has-error' : '' !!}">
                                    <label for="uom" class="control-label">Uom</label>
                                    <input type="text"
                                           id="uom"
                                           class="form-control"
                                           name="uom"
                                           value="{{ $data['uom'] }}"
                                           required>
                                </div>
                                @if ($errors->has('uom'))
                                    <span class="help-block">
                                        <strong>{!! $errors->first('uom') !!}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group{!! $errors->has('uom_name') ? ' has-error' : '' !!}">
                                    <label for="uom_name" class="control-label">Nama Uom</label>
                                    <input type="text"
                                           id="uom_name"
                                           class="form-control"
                                           name="uom_name"
                                           value="{{ $data['uom_name'] }}"
                                           required>
                                </div>
                                @if ($errors->has('uom_name'))
                                    <span class="help-block">
                                        <strong>{!! $errors->first('uom_name') !!}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group{!! $errors->has('package_description') ? ' has-error' : '' !!}">
                                    <label for="package_description" class="control-label">Deskripsi Item</label>
                                    <input type="text"
                                           id="package_description"
                                           class="form-control"
                                           name="package_description"
                                           value="{{ $data['package_description'] }}"
                                           required>
                                </div>
                                @if ($errors->has('package_description'))
                                    <span class="help-block">
                                        <strong>{!! $errors->first('package_description') !!}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        <button type="submit" id="btn-submit" class="btn btn-success">Submit</button>
                        <a href="{{route('backend::sembako-items.index')}}" class="btn btn-danger">Back</a>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection

@push('scripts')

@endpush
