@extends('layout.master')
@section('title')
    Laporan Flow
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
            Laporan Flow
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
                    <a class="btn btn-sm btn-primary active"href="/lapor-flow/{{ $newDateAwal }}/{{ $newDateAkhir }}"
                        class="text-white">Flow Cas</a>

                    <button class="btn btn-sm btn-primary">
                        <a href="/lapor-penjualan/{{ $newDateAwal }}/{{ $newDateAkhir }}" class="text-white">Penjualan</a>
                    </button>
                    <button class="btn btn-sm btn-primary">
                        <a href="/lapor-pembelian/{{ $newDateAwal }}/{{ $newDateAkhir }}"
                            class="text-white">Pembelian</a>
                    </button>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="text-center">
                                <h6>Laporan Flow Cas</h6>
                                <h6>Tanggal : {{ date('d M, Y', strtotime($newDateAwal)) }} sampai
                                    {{ date('d M, Y', strtotime($newDateAkhir)) }}</h6><br>
                            </div>
                            <table class="dataTable cell-border w-100 mb-3">
                                <thead>
                                    <tr style="background-color: rgb(81, 255, 37)">
                                        <th colspan="7">Flow Cas</th>
                                    </tr>
                                    <tr style="background-color: rgb(136, 255, 106)">
                                        <th>Tanggal</th>
                                        <th colspan="3">Uraian</th>
                                        <th>Debet</th>
                                        <th>Kredit</th>
                                        <th>Sisa Saldo</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="text-white">&sum;</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    @php
                                        $no_mk = 1;
                                    @endphp
                                    @foreach ($laporan as $l)
                                        @php
                                            $saldoHM_mk = $l->harga_keluar;
                                            $saldoHK_mk = $l->harga_masuk;
                                        @endphp
                                        @if ($no_mk == 1)
                                            @php
                                                $no_mk++;
                                                // harga
                                                $saldomutasiH_mk = 0 + $saldoHM_mk - $saldoHK_mk;
                                                if ($saldoHM_mk > 0) {
                                                    $saldomutasi1H_mk = $saldomutasiH_mk + $saldoHK_mk;
                                                } else {
                                                    $saldomutasi1H_mk = $saldomutasiH_mk - $saldoHM_mk;
                                                }
                                            @endphp
                                        @else
                                            @php
                                                $no_mk++;
                                                // harga
                                                $sals1H_mk = $saldomutasi1H_mk;
                                                $saldomutasi1H_mk = $sals1H_mk + $saldoHM_mk - $saldoHK_mk;
                                            @endphp
                                        @endif
                                        <tr>
                                            <td>{{ date('d M, Y', strtotime($l->tanggal)) }}</td>
                                            @if ($l->vol_keluar != null)
                                                <td style="background-color: rgb(255, 249, 86)">{{ $l->supplier }}</td>
                                                <td style="background-color: rgb(255, 249, 86)">{{ $l->vol_masuk }}</td>
                                                <td style="background-color: rgb(255, 249, 86)">{{ $l->vol_keluar }}</td>
                                                <td style="background-color: rgb(255, 249, 86)">
                                                    {{ 'Rp ' . number_format($l->vol_keluar * 1450000, 0, ',', '.') }}
                                                </td>
                                            @else
                                                <td>{{ $l->supplier }}</td>
                                                <td>{{ $l->vol_masuk }}</td>
                                                <td>{{ $l->vol_keluar }}</td>
                                                <td></td>
                                            @endif
                                            @if ($l->harga_masuk != null)
                                                <td>{{ 'Rp ' . number_format($l->harga_masuk, 0, ',', '.') }}</td>
                                            @else
                                                <td></td>
                                            @endif
                                            <td>
                                                @if ($saldomutasi1H_mk < 0)
                                                    {{ 'Rp (' . number_format(abs($saldomutasi1H_mk), 0, ',', '.') }})
                                                @else
                                                    {{ 'Rp ' . number_format(abs($saldomutasi1H_mk), 0, ',', '.') }}
                                                @endif

                                            </td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td>{{ date('d M, Y', strtotime($newDateAkhir)) }}</td>
                                        <td style="background-color: rgb(255, 249, 86)">Stock</td>
                                        <td style="background-color: rgb(255, 249, 86)"></td>
                                        <td style="background-color: rgb(255, 249, 86)">{{ round($total_soVol, 4) }}</td>
                                        <td style="background-color: rgb(255, 249, 86)">
                                            {{ 'Rp ' . number_format($total_soHarga, 0, ',', '.') }}
                                        </td>
                                        <td></td>
                                        <td>{{ 'Rp ' . number_format($saldomutasi1H_mk + $total_soHarga, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td>{{ $total_logVol }}</td>
                                        <td>{{ $total_jualOpcVol + $total_soVol }}</td>
                                        <td>{{ 'Rp ' . number_format($total_jualOpcHarga + $total_soHarga, 0, ',', '.') }}
                                        </td>
                                        <td>{{ 'Rp ' . number_format($total_logHarga, 0, ',', '.') }}</td>
                                        <td>{{ 'Rp ' . number_format($saldomutasi1H_mk + $total_soHarga, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-white">&sum;</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
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
