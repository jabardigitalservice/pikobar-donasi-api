@extends('layouts.backend')

@section('title')
    Paket Sembako
@endsection

@section('content')

    <section class="content-header">
        <h1>Tambah Paket Sembako</h1>
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
                <form method="POST" class="form-horizontal" enctype="multipart/form-data"
                      action="{!! route('backend::sembako-packages.store') !!}">
                    @csrf
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group {!! $errors->has('sku') ? ' has-error' : '' !!}">
                                    <label for="sku" class="control-label">SKU</label>
                                    <input type="text"
                                           id="sku"
                                           class="form-control"
                                           name="sku"
                                           value=""
                                           required>
                                </div>
                                @if ($errors->has('sku'))
                                    <span class="help-block">
                                        <strong>{!! $errors->first('sku') !!}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group{!! $errors->has('package_name') ? ' has-error' : '' !!}">
                                    <label for="package_name" class="control-label">Nama Paket</label>
                                    <input type="text"
                                           id="package_name"
                                           class="form-control"
                                           name="package_name"
                                           value=""
                                           required>
                                </div>
                                @if ($errors->has('package_name'))
                                    <span class="help-block">
                                        <strong>{!! $errors->first('package_name') !!}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group{!! $errors->has('package_description') ? ' has-error' : '' !!}">
                                    <label for="package_description" class="control-label">Deskripsi Paket</label>
                                    <input type="text"
                                           id="package_description"
                                           class="form-control"
                                           name="package_description"
                                           value=""
                                           required>
                                </div>
                                @if ($errors->has('package_description'))
                                    <span class="help-block">
                                        <strong>{!! $errors->first('package_description') !!}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                @if(isset($sembakoItems))

                                    <div class="form-group{!! $errors->has('items') ? ' has-error' : '' !!}">
                                        <label for="sembako[]"
                                               class="control-label">Item Sembako</label>
                                        <select class="select-remote form-control"
                                                multiple="multiple"
                                                id="sembako[]"
                                                name="sembako[]"
                                                style="min-height: 120px;">
                                            @foreach($sembakoItems as $sembakoItem)
                                                <option value="{{$sembakoItem->id}}">{{$sembakoItem->item_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                @endif
                                @if ($errors->has('items'))
                                    <span class="help-block">
                                        <strong>{!! $errors->first('items') !!}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        <button type="submit" id="btn-submit" class="btn btn-success">Submit</button>
                        <a href="{{route('backend::sembako-packages.index')}}" class="btn btn-danger">Back</a>
                    </div>
                </form>
            </div>
        </div>
    </section>

@endsection

@push('scripts')

@endpush