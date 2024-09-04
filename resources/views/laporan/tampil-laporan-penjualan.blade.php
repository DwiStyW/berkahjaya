@extends('layout.master')
@section('title')
    Laporan Penjualan
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
            font-size: 12px;
        }

        table.dataTable.cell-border tbody th,
        table.dataTable.cell-border tfoot td,
        table.dataTable.cell-border tbody td {
            border-top: 1px solid rgba(0, 0, 0, 0.15);
            border-right: 1px solid rgba(0, 0, 0, 0.15);
            border-bottom: 1px solid rgba(0, 0, 0, 0.15);
            padding: 5px 10px 5px 10px;
            font-size: 12px;
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
            Laporan Penjualan
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="mb-4">
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
                            <div class="float-end mb-2">
                                <a href="javascript:history.back()" class="btn btn-md btn-secondary">back</a>
                                <button type="submit" class="btn btn-md btn-primary">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="mb-3">
                    <a class="btn btn-sm btn-primary" href="/lapor-produksi/{{ $newDateAwal }}/{{ $newDateAkhir }}"
                        class="text-white">Produksi</a>
                    <a class="btn btn-sm btn-primary" href="/lapor-flow/{{ $newDateAwal }}/{{ $newDateAkhir }}"
                        class="text-white">Flow Cas</a>
                    <a class="btn btn-sm btn-primary" href="/lapor-penjualan/{{ $newDateAwal }}/{{ $newDateAkhir }}"
                        class="text-white">Penjualan</a>
                    <a class="btn btn-sm btn-primary" href="/lapor-pembelian/{{ $newDateAwal }}/{{ $newDateAkhir }}"
                        class="text-white">Pembelian</a>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="text-center">
                                <h6>Laporan</h6>
                                <h6>Tanggal : {{ date('d M, Y', strtotime($newDateAwal)) }} sampai
                                    {{ date('d M, Y', strtotime($newDateAkhir)) }}</h6><br>
                            </div>
                            <h6>Penjualan Opc</h6>
                            <table class="dataTable cell-border w-100 mb-3">
                                <thead>
                                    <tr style="background-color: rgb(136, 255, 106)">
                                        <th>Tanggal</th>
                                        <th>Uraian</th>
                                        <th></th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($penjualanOpc as $po)
                                        <tr>
                                            <td>{{ date('d M, Y', strtotime($po->tanggal)) }}</td>
                                            <td>{{ $po->supplier }}</td>
                                            <td>{{ $po->vol_m3 }}</td>
                                            <td>{{ 'Rp ' . number_format($po->total_harga, 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <th>Total</th>
                                        <th></th>
                                        <th>{{ $total_jualOpcVol }}</th>
                                        <th>{{ 'Rp ' . number_format($total_jualOpcHarga, 0, ',', '.') }}</th>
                                    </tr>
                                </tbody>
                            </table>

                            <h6>Penjualan Limbah</h6>
                            <table class="dataTable cell-border w-100 mb-3">
                                <thead>
                                    <tr style="background-color: rgb(136, 255, 106)">
                                        <th>Tanggal</th>
                                        <th>Uraian</th>
                                        <th></th>
                                        <th></th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($penjualanLimbah as $pl)
                                        <tr>
                                            <td>{{ date('d M, Y', strtotime($pl->tanggal)) }}</td>
                                            <td>{{ $pl->supplier }}</td>
                                            <td>{{ $pl->grade }}</td>
                                            <td>{{ $pl->vol_m3 }}</td>
                                            <td>{{ 'Rp ' . number_format($pl->total_harga, 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <th>Total</th>
                                        <th></th>
                                        <th></th>
                                        <th>{{ $total_jualLimbahVol }}</th>
                                        <th>{{ 'Rp ' . number_format($total_jualLimbahHarga, 0, ',', '.') }}</th>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
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
        <script src="https://cdn.jsdelivr.net/gh/ashl1/datatables-rowsgroup@v2.0.0/dataTables.rowsGroup.js"></script>
        <script>
            $(document).ready(function() {
                var table = $('#datatable').DataTable({
                    dom: '<"table-responsive w-100"<t>>',
                    paging: false,
                    // columnDefs: [{
                    //     render: $.fn.dataTable.render.number('.', ',', 0, 'Rp '),
                    //     targets: [3]
                    // }]
                });
            });
        </script>
    @endsection
