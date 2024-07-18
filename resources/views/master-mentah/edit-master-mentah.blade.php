@extends('layout.master')
@section('title')
    Edit Hasil Produk
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
            Edit Hasil Produk
        @endslot
    @endcomponent
    <div class="row">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        @foreach ($mastermentah as $item)
                            <form action="/mastermentah-update/{{ Crypt::encrypt($item->id) }}" method="POST">
                                @csrf
                                <div class="mb-3 row">
                                    <label for="jenis_kayu" class="col-md-2 col-form-label">Jenis Kayu</label>
                                    <div class="col-md-10">
                                        <input class="form-control" type="text" value="{{ $item->jenis_muatan }}"
                                            id="jenis_kayu" name="jenis_kayu">
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="hasil_produksi" class="col-md-2 col-form-label">Rumus Pakem</label>
                                    <div class=" col-md-10">
                                        <div class="input-group">
                                            <input class="form-control" type="text" disabled placeholder="Model * Model">
                                            <input class="form-control" type="text" placeholder="000" id="rumus_a"
                                                value="{{ $item->rumus_a }}" name="rumus_a">
                                            <input class="form-control" type="text" value="{{ $item->rumus_b }}"
                                                id="rumus_b" name="rumus_b">
                                            <input class="form-control" type="text" value="{{ $item->rumus_c }}"
                                                id="rumus_c" name="rumus_c">
                                        </div>
                                    </div>
                                </div>
                                <div class="float-end">
                                    <a href="javascript:history.back()" class="btn btn-md btn-secondary">back</a>
                                    <button type="submit" class="btn btn-md btn-primary">Save</button>
                                </div>
                            </form>
                        @endforeach
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
