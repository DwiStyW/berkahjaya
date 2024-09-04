@extends('layout.master')
@section('title')
    Laporan Produksi
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
            font-size: 9px;
        }

        table.dataTable.cell-border tbody th,
        table.dataTable.cell-border tfoot td,
        table.dataTable.cell-border thead td,
        table.dataTable.cell-border tbody td {
            border-top: 1px solid rgba(0, 0, 0, 0.15);
            border-right: 1px solid rgba(0, 0, 0, 0.15);
            border-bottom: 1px solid rgba(0, 0, 0, 0.15);
            padding: 2px 5px 2px 5px;
            font-size: 9px;
        }

        table.dataTable.cell-border tbody tr th:first-child,
        table.dataTable.cell-border tfoot tr td:first-child,
        table.dataTable.cell-border thead tr td:first-child,
        table.dataTable.cell-border tbody tr td:first-child {
            border-left: 1px solid rgba(0, 0, 0, 0.15);
        }

        table.dataTable.cell-border tbody tr:first-child th,
        table.dataTable.cell-border tbody tr:first-child td {
            border-top: none;
        }

        table.dataTable thead th,
        tavle.dataTable tbody td {
            max-width: 80px;
        }

        table.dataTable tbody td:nth-child(2) {
            max-width: 80px;
        }
    </style>
@endsection
@section('content')
    @component('common-components.breadcrumb')
        @slot('pagetitle')
            Berkah Jaya
        @endslot
        @slot('title')
            Laporan Produksi
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
                    <a class="btn btn-sm btn-primary active" href="/lapor-produksi/{{ $newDateAwal }}/{{ $newDateAkhir }}"
                        class="text-white">Produksi</a>
                    <a class="btn btn-sm btn-primary"href="/lapor-flow/{{ $newDateAwal }}/{{ $newDateAkhir }}"
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
                                <h6>Laporan Produksi</h6>
                                <h6>Tanggal : {{ date('d M, Y', strtotime($newDateAwal)) }} sampai
                                    {{ date('d M, Y', strtotime($newDateAkhir)) }}</h6><br>
                            </div>
                            <table id="datatable" class="dataTable cell-border w-100">
                                <thead>
                                    <tr style="background-color: rgb(136, 255, 106)">
                                        <th rowspan="2">Tgl</th>
                                        <th rowspan="2">Supplier</th>
                                        <th rowspan="2"></th>
                                        <th rowspan="2">Log/m3</th>
                                        <th rowspan="2">Harga</th>
                                        <th colspan="3">OPC</th>
                                        <th colspan="3">OPC B</th>
                                        <th colspan="2">PPC</th>
                                        <th colspan="2">MK</th>
                                        <th colspan="2">Ampulur</th>
                                        <th rowspan="2">Total Produksi</th>
                                        <th rowspan="2">Selisih</th>
                                    </tr>
                                    <tr style="background-color: rgb(136, 255, 106)">
                                        <th data-dt-order="disable">Pcs</th>
                                        <th data-dt-order="disable">m3</th>
                                        <th data-dt-order="disable">total harga</th>
                                        <th data-dt-order="disable">Pcs</th>
                                        <th data-dt-order="disable">m3</th>
                                        <th data-dt-order="disable">total harga</th>
                                        <th data-dt-order="disable">m</th>
                                        <th data-dt-order="disable">total harga</th>
                                        <th data-dt-order="disable">m</th>
                                        <th data-dt-order="disable">total harga</th>
                                        <th data-dt-order="disable">pcs</th>
                                        <th data-dt-order="disable">total harga</th>
                                    </tr>
                                    <tr style="background-color: rgb(136, 255, 106)">
                                        <th style="color: rgb(136, 255, 106)">&sum;</th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                    <tr>
                                        <td class="text-white">&sum;</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $log_m3 = [];
                                        $harga_log = [];
                                        $opc_m3 = [];
                                        $opc_total = [];
                                        $total_selisih = [];
                                    @endphp
                                    @foreach ($produksiGroup as $pg)
                                        @php
                                            $selisih =
                                                $pg->sum_opc_total +
                                                $pg->sum_opcb_total +
                                                $pg->sum_ppc_total +
                                                $pg->sum_ampulur_total -
                                                $pg->harga_log;

                                            array_push($log_m3, $pg->log_opc);
                                            array_push($harga_log, $pg->harga_log);
                                            array_push($opc_m3, $pg->sum_opc_m3);
                                            array_push($opc_total, $pg->sum_opc_total);
                                            array_push($total_selisih, $selisih);
                                        @endphp
                                        @foreach ($produksi as $item)
                                            @if ($item->kode_produksi == $pg->kode_produksi)
                                                @if ($pg->count == 1)
                                                    <tr style="border-bottom: 4px solid rgba(0, 0, 0, 0.15)">
                                                        <td>{{ date('d-M', strtotime($item->tanggal)) }}</td>
                                                        <td @if ($item->harga_log > $item->opc_total) class="bg-danger" @endif>
                                                            {{ $item->supplier }}<span
                                                                class="d-none">{{ $item->kode_produksi }}</span>
                                                        </td>
                                                        <td>{{ round($item->persentase) }}%</td>
                                                        <td>{{ $item->log_opc }}</td>
                                                        <td>{{ 'Rp ' . number_format($item->harga_log, 0, ',', '.') }}
                                                        </td>
                                                        <td>{{ $item->opc_pcs }}</td>
                                                        <td>{{ $item->opc_m3 }}</td>
                                                        <td>{{ 'Rp ' . number_format($item->opc_total, 0, ',', '.') }}
                                                        </td>
                                                        <td>{{ $item->opcb_pcs }}</td>
                                                        <td>{{ $item->opcb_m3 }}</td>
                                                        @if ($item->opcb_total != null)
                                                            <td>{{ 'Rp ' . number_format($item->opcb_total, 0, ',', '.') }}
                                                            </td>
                                                        @else
                                                            <td></td>
                                                        @endif
                                                        <td>{{ $item->ppc_m }}</td>
                                                        @if ($item->ppc_total != null)
                                                            <td>{{ 'Rp ' . number_format($item->ppc_total, 0, ',', '.') }}
                                                            </td>
                                                        @else
                                                            <td></td>
                                                        @endif
                                                        <td>{{ $item->mk_m }}</td>
                                                        @if ($item->mk_total != null)
                                                            <td>{{ 'Rp ' . number_format($item->mk_total, 0, ',', '.') }}
                                                            </td>
                                                        @else
                                                            <td></td>
                                                        @endif
                                                        <td>{{ $item->ampulur_pcs }}</td>
                                                        @if ($item->ampulur_total != null)
                                                            <td>{{ 'Rp ' . number_format($item->ampulur_total, 0, ',', '.') }}
                                                            </td>
                                                        @else
                                                            <td></td>
                                                        @endif
                                                        <td>
                                                            {{ 'Rp ' . number_format($item->opc_total + $item->opcb_total + $item->ppc_total + $item->ampulur_total, 0, ',', '.') }}
                                                        </td>
                                                        <td>
                                                            {{ 'Rp ' . number_format($item->opc_total + $item->opcb_total + $item->ppc_total + $item->ampulur_total - $item->harga_log, 0, ',', '.') }}
                                                        </td>
                                                    </tr>
                                                @else
                                                    <tr>
                                                        <td>{{ date('d-M', strtotime($item->tanggal)) }}</td>
                                                        <td @if ($item->harga_log > $pg->sum_opc_total) class="bg-danger" @endif>
                                                            {{ $item->supplier }}<span
                                                                class="d-none">{{ $item->kode_produksi }}</span>
                                                        </td>
                                                        <td>{{ round($item->persentase) }}%</td>
                                                        <td>{{ $item->log_opc }}</td>
                                                        <td>{{ 'Rp ' . number_format($item->harga_log, 0, ',', '.') }}
                                                        </td>
                                                        <td>{{ $item->opc_pcs }}</td>
                                                        <td>{{ $item->opc_m3 }}</td>
                                                        <td>{{ 'Rp ' . number_format($item->opc_total, 0, ',', '.') }}
                                                        </td>
                                                        <td>{{ $item->opcb_pcs }}</td>
                                                        <td>{{ $item->opcb_m3 }}</td>
                                                        @if ($item->opcb_total != null)
                                                            <td>{{ 'Rp ' . number_format($item->opcb_total, 0, ',', '.') }}
                                                            </td>
                                                        @else
                                                            <td></td>
                                                        @endif
                                                        <td>{{ $item->ppc_m }}</td>
                                                        @if ($item->ppc_total != null)
                                                            <td>{{ 'Rp ' . number_format($item->ppc_total, 0, ',', '.') }}
                                                            </td>
                                                        @else
                                                            <td><span class="d-none">none</span></td>
                                                        @endif
                                                        <td>{{ $item->mk_m }}</td>
                                                        @if ($item->mk_total != null)
                                                            <td>{{ 'Rp ' . number_format($item->mk_total, 0, ',', '.') }}
                                                            </td>
                                                        @else
                                                            <td></td>
                                                        @endif
                                                        <td>{{ $item->ampulur_pcs }}</td>
                                                        @if ($item->ampulur_total != null)
                                                            <td>{{ 'Rp ' . number_format($item->ampulur_total, 0, ',', '.') }}
                                                            </td>
                                                        @else
                                                            <td></td>
                                                        @endif
                                                        <td>
                                                            {{ 'Rp ' . number_format($item->opc_total + $item->opcb_total + $item->ppc_total + $item->ampulur_total, 0, ',', '.') }}
                                                        </td>
                                                        <td></td>
                                                    </tr>
                                                    <tr style="border-bottom: 4px solid rgba(0, 0, 0, 0.15)">
                                                        <td>{{ date('d-M', strtotime($item->tanggal)) }}</td>
                                                        <td @if ($item->harga_log > $pg->sum_opc_total) class="bg-danger" @endif>
                                                            {{ $item->supplier }}<span
                                                                class="d-none">{{ $item->kode_produksi }}</span>
                                                        </td>
                                                        <td>{{ round($item->persentase) }}%</td>
                                                        <td>{{ $item->log_opc }}</td>
                                                        <td>{{ 'Rp ' . number_format($item->harga_log, 0, ',', '.') }}
                                                        </td>
                                                        <td><span class="d-none">none</span></td>
                                                        <td>{{ round($pg->sum_opc_m3, 4) }}</td>
                                                        <td><span class="d-none">none</span></td>
                                                        <td><span class="d-none">none</span></td>
                                                        <td><span class="d-none">none</span></td>
                                                        <td><span class="d-none">none</span></td>
                                                        <td><span class="d-none">none</span></td>
                                                        <td><span class="d-none">none</span></td>
                                                        <td><span class="d-none">none</span></td>
                                                        <td><span class="d-none">none</span></td>
                                                        <td><span class="d-none">none</span></td>
                                                        <td><span class="d-none">none</span></td>
                                                        <td>
                                                            {{ 'Rp ' . number_format($pg->sum_opc_total + $pg->sum_opcb_total + $pg->sum_ppc_total + $pg->sum_ampulur_total, 0, ',', '.') }}
                                                        </td>
                                                        <td>
                                                            {{ 'Rp ' . number_format($pg->sum_opc_total + $pg->sum_opcb_total + $pg->sum_ppc_total + $pg->sum_ampulur_total - $pg->harga_log, 0, ',', '.') }}
                                                        </td>
                                                    </tr>
                                                @endif
                                            @endif
                                        @endforeach
                                    @endforeach

                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td class="text-white">&sum;</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    @php
                                        $selisih_harga_log = array_sum($opc_total) - array_sum($harga_log);
                                        $selisih_log_produksi = array_sum($total_selisih) - $selisih_harga_log;
                                    @endphp
                                    <tr style="background-color:rgb(255, 254, 211);">
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td>{{ round(array_sum($log_m3), 4) }}</td>
                                        <td>{{ 'Rp ' . number_format(array_sum($harga_log), 0, ',', '.') }}</td>
                                        <td></td>
                                        <td>{{ array_sum($opc_m3) }}</td>
                                        <td>{{ 'Rp ' . number_format(array_sum($opc_total), 0, ',', '.') }}</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td>{{ 'Rp ' . number_format(array_sum($total_selisih), 0, ',', '.') }}</td>
                                    </tr>
                                    <tr style="background-color:rgb(255, 254, 211);">
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td>{{ 'Rp ' . number_format($selisih_harga_log, 0, ',', '.') }}</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td>{{ 'Rp ' . number_format($selisih_log_produksi, 0, ',', '.') }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-white">&sum;</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                </tfoot>
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

                    rowsGroup: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18],
                    paging: false,

                    // pageLength: '20',
                    // dom: '<"row justify-content-between"<><"row"<f><B>>><"table-responsive"<t>><"row justify-content-between"ip>'
                    dom: '<"table-responsive w-100"<t>>'
                });
            });
        </script>
    @endsection
