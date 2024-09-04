@extends('layout.master')
@section('title')
    Edit Operasional
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
            Edit Operasional
        @endslot
    @endcomponent
    <div class="row">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        @foreach ($operasional as $item)
                            <form action="/operasional-update/{{ Crypt::encrypt($item->id) }}" method="POST">
                                @csrf
                                <div class="mb-3 row">
                                    <label class="col-md-2 col-form-label">Tanggal</label>
                                    <div class="col-md-10">
                                        <div class="input-group" id="datepicker2">
                                            <input type="text" class="form-control" placeholder="dd M, yyyy"
                                                data-date-format="dd M, yyyy" data-date-container='#datepicker2'
                                                data-provide="datepicker" data-date-autoclose="true" id="tanggal"
                                                name="tanggal" required>
                                            <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                        </div>
                                    </div><!-- input-group -->
                                </div>
                                <div class="mb-3 row">
                                    <label for="hasil_produksi" class="col-md-2 col-form-label">Uraian</label>
                                    <div class="col-md-10">
                                        <input class="form-control" type="text" id="uraian" name="uraian"
                                            value="{{ $item->uraian }}">
                                    </div>
                                </div>

                                <div class="mb-3 row">
                                    <label for="hasil_produksi" class="col-md-2 col-form-label">Total</label>
                                    <div class="col-md-10">
                                        <input class="form-control" type="text" placeholder="000" id="total"
                                            name="total" value={{ $item->harga }}>
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
        var tanggal = @json($tanggal);
        if (tanggal != '') {
            let dateFormat1 = moment(tanggal).format('DD MMM, yyyy');
            document.getElementById('tanggal').value = dateFormat1;
        }
    </script>
@endsection
