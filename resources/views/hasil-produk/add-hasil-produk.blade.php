@extends('layout.master')
@section('title')
    Tambah Master Produk
@endsection
@section('css')
    <!-- plugin css -->
    <link href="{{ URL::asset('/assets/libs/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('/assets/libs/spectrum-colorpicker/spectrum-colorpicker.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('/assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('/assets/libs/bootstrap-touchspin/bootstrap-touchspin.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{ URL::asset('/assets/libs/datepicker/datepicker.min.css') }}">
@endsection
@section('content')
    @component('common-components.breadcrumb')
        @slot('pagetitle')
            Berkah Jaya
        @endslot
        @slot('title')
            Tambah Master Produk
        @endslot
    @endcomponent
    <div class="row">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form action="/hasilproduk-store" method="POST">
                            @csrf
                            <div class="mb-3 row">
                                <label for="hasil_produksi" class="col-md-2 col-form-label">Hasil Produksi</label>
                                <div class="col-md-10">
                                    <input class="form-control" type="text" placeholder="nama produk" id="hasil_produksi"
                                        name="hasil_produksi">
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="satuan" class="col-md-2 col-form-label">Satuan</label>
                                <div class="col-md-10">
                                    <select class="form-control select2" name="satuan" id="satuan">
                                        <option value="null" disabled selected>Pilih Satuan</option>
                                        <option value="m3">Meterkubik (m3)</option>
                                        <option value="m">Meter (m)</option>
                                        <option value="pcs">Pcs</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="harga" class="col-md-2 col-form-label">Harga</label>
                                <div class="col-md-10">
                                    <input class="form-control" type="number" placeholder="000" id="harga"
                                        name="harga">
                                </div>
                            </div>
                            <div class="float-end">
                                <a href="javascript:history.back()" class="btn btn-md btn-secondary">back</a>
                                <button type="submit" class="btn btn-md btn-primary">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div> <!-- end col -->
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ URL::asset('/assets/libs/select2/select2.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/libs/spectrum-colorpicker/spectrum-colorpicker.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/libs/bootstrap-touchspin/bootstrap-touchspin.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/libs/bootstrap-maxlength/bootstrap-maxlength.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/libs/datepicker/datepicker.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/js/pages/form-advanced.init.js') }}"></script>
@endsection
