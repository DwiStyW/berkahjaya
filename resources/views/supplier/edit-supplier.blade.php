@extends('layout.master')
@section('title')
    Edit Supplier
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
            Edit Supplier
        @endslot
    @endcomponent
    <div class="row">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        @foreach ($supplier as $item)
                            <form action="/supplier-update/{{ Crypt::encrypt($item->id) }}" method="POST">
                                @csrf
                                <div class="mb-3 row">
                                    <label for="supplier" class="col-md-2 col-form-label">Supplier</label>
                                    <div class="col-md-10">
                                        <input class="form-control" type="text" value="{{ $item->supplier }}"
                                            id="supplier" name="supplier">
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="nama" class="col-md-2 col-form-label">Nama</label>
                                    <div class="col-md-10">
                                        <input class="form-control" type="text" value="{{ $item->nama }}"
                                            id="nama" name="nama">
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="bank" class="col-md-2 col-form-label">Bank</label>
                                    <div class="col-md-10">
                                        <input class="form-control" type="text" value="{{ $item->bank }}"
                                            id="bank" name="bank">
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="rek" class="col-md-2 col-form-label">No rekening</label>
                                    <div class="col-md-10">
                                        <input class="form-control" type="text" value="{{ $item->rek }}"
                                            id="rek" name="rek">
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
