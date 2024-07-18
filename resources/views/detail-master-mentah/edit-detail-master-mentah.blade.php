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
                        @foreach ($detailmastermentah as $item)
                            <form action="/detailmastermentah-update/{{ Crypt::encrypt($item->id) }}" method="POST">
                                @csrf
                                <input class="form-control" type="hidden" value="{{ $id_master }}" id="id_master"
                                    name="id_master">
                                <div class="mb-3 row">
                                    <label for="hasil_produksi" class="col-md-2 col-form-label">Kelas Model</label>
                                    <div class="col-md-10">
                                        <input class="form-control" type="text" value="{{ $item->kelas_model }}"
                                            id="kelas_model" name="kelas_model">
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="hasil_produksi" class="col-md-2 col-form-label">Model</label>
                                    <div class="col-md-10">
                                        <input class="form-control" type="number" value="{{ $item->model }}"
                                            id="model" name="model" oninput="hitungpakem()">
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="hasil_produksi" class="col-md-2 col-form-label">Pakem</label>
                                    <div class="col-md-10">
                                        <input class="form-control" type="text" value="{{ $item->pakem_pembulatan }}"
                                            id="pakem_pembulatan" name="pakem_pembulatan">
                                        <input type="hidden" name="pakem" id="pakem">
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="hasil_produksi" class="col-md-2 col-form-label">Harga</label>
                                    <div class="col-md-10">
                                        <input class="form-control" type="text" value="{{ $item->harga }}"
                                            id="harga" name="harga">
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
    <script>
        function hitungpakem() {
            let mastermentah = @json($mastermentah);
            var model = document.getElementById('model').value;
            var rumus_a = mastermentah[0].rumus_a;
            var rumus_b = mastermentah[0].rumus_b;
            var rumus_c = mastermentah[0].rumus_c;

            var pakem = model * model * rumus_a * rumus_b / rumus_c;
            var roundedString = pakem.toFixed(3);
            var rounded = Number(roundedString);
            document.getElementById('pakem').value = pakem;
            document.getElementById('pakem_pembulatan').value = rounded;
        }
    </script>
@endsection
