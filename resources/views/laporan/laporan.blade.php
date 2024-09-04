@extends('layout.master')
@section('title')
    Laporan
@endsection
@section('css')
    <link href="{{ URL::asset('/assets/libs/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('/assets/libs/spectrum-colorpicker/spectrum-colorpicker.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('/assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('/assets/libs/bootstrap-touchspin/bootstrap-touchspin.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{ URL::asset('/assets/libs/datepicker/datepicker.min.css') }}">
    <style>
        table.dataTable.cell-border thead th {
            border: 1px solid rgba(0, 0, 0, 0.25);
            text-align: center;
        }

        table.dataTable.cell-border tbody th,
        table.dataTable.cell-border tfoot td,
        table.dataTable.cell-border tbody td {
            border-top: 1px solid rgba(0, 0, 0, 0.15);
            border-right: 1px solid rgba(0, 0, 0, 0.15);
            border-bottom: 1px solid rgba(0, 0, 0, 0.15);
            padding: 5px 10px 5px 10px;
        }

        table.dataTable.cell-border tbody tr th:first-child,
        table.dataTable.cell-border tfoot tr td:first-child,
        table.dataTable.cell-border tbody tr td:first-child {
            border-left: 1px solid rgba(0, 0, 0, 0.15);
        }

        table.dataTable.cell-border tbody tr:first-child th,
        table.dataTable.cell-border tbody tr:first-child td {
            border-top: none;
        }

        table.dataTable thead th,
        tavle.dataTable tbody td {
            max-width: 100px;
        }
    </style>
@endsection
@section('content')
    @component('common-components.breadcrumb')
        @slot('pagetitle')
            Berkah Jaya
        @endslot
        @slot('title')
            Laporan
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-12" style="height:50vh">
            <div class="card">
                <div class="card-body">
                    <form action="/getLaporan" method="POST">
                        @csrf
                        <div class="mb-3 row">
                            <label class="col-md-2 col-form-label">Tanggal</label>
                            <div class="col-md-10">
                                <div class="input-group" id="datepicker2">
                                    <input type="text" class="form-control" placeholder="dd M, yyyy"
                                        data-date-format="dd M, yyyy" data-date-container='#datepicker2'
                                        data-provide="datepicker" data-date-autoclose="true" id="tanggal_awal"
                                        name="tanggal_awal" required>

                                    <span class="input-group-text"><i class="mdi mdi-minus"></i></span>
                                    <input type="text" class="form-control" placeholder="dd M, yyyy"
                                        data-date-format="dd M, yyyy" data-date-container='#datepicker2'
                                        data-provide="datepicker" data-date-autoclose="true" id="tanggal_akhir"
                                        name="tanggal_akhir" required>
                                </div>
                            </div><!-- input-group -->
                        </div>
                        <div class="float-end">
                            <a href="javascript:history.back()" class="btn btn-md btn-secondary">back</a>
                            <button type="submit" class="btn btn-md btn-primary">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div> <!-- end col -->
    </div> <!-- end row -->
@endsection
@section('script')
    <script src="{{ URL::asset('/assets/libs/datatables/datatables.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/libs/select2/select2.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/libs/spectrum-colorpicker/spectrum-colorpicker.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/libs/bootstrap-touchspin/bootstrap-touchspin.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/libs/bootstrap-maxlength/bootstrap-maxlength.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/libs/datepicker/datepicker.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/js/pages/form-advanced.init.js') }}"></script>
@endsection
