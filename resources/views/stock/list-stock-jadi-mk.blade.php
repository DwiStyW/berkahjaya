@extends('layout.master')
@section('title')
    Stock
@endsection
@section('css')
    <!-- DataTables -->
    <link href="{{ URL::asset('/assets/libs/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
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
            Stock
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <table id="datatable" class="dataTable cell-border nowrap"
                        style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead class="bg-secondary text-white">
                            <tr>
                                <th rowspan="2">No</th>
                                <th rowspan="2">Tanggal</th>
                                <th rowspan="2">Supplier</th>
                                @if (Auth::user()->role == '1')
                                    <th colspan="2">Masuk</th>
                                    <th colspan="2">Keluar</th>
                                    <th rowspan="2">Total Volume</th>
                                    <th rowspan="2">Total Rupiah</th>
                                @else
                                    <th>Masuk</th>
                                    <th>Keluar</th>
                                    <th rowspan="2">Total Volume</th>
                                @endif
                            </tr>
                            @if (Auth::user()->role == '1')
                                <tr>
                                    <th>Volume</th>
                                    <th>Rupiah</th>
                                    <th>Volume</th>
                                    <th>Rupiah</th>
                                </tr>
                            @endif
                        </thead>
                        <tbody>
                            @php
                                $no = 1;
                            @endphp
                            @if (count($stock) != 0)
                                @foreach ($stock as $item)
                                    @php
                                        if ($item->ket == 'masuk') {
                                            $saldoVM = $item->volume;
                                            $saldoVK = 0;
                                            $saldoHM = $item->harga;
                                            $saldoHK = 0;
                                        } else {
                                            $saldoVM = 0;
                                            $saldoVK = $item->volume;
                                            $saldoHM = 0;
                                            $saldoHK = $item->volume * $item->harga_master;
                                        }
                                    @endphp
                                    @if ($no == 1)
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td>{{ $item->tanggal }}</td>
                                            <td>{{ $item->supplier }}</td>
                                            @if (Auth::user()->role == '1')
                                                @if ($item->ket == 'masuk')
                                                    <td>{{ $item->volume }}</td>
                                                    <td>{{ 'Rp ' . number_format($item->harga, 0, ',', '.') }}</td>
                                                @else
                                                    <td></td>
                                                    <td></td>
                                                @endif
                                                @if ($item->ket == 'keluar')
                                                    <td>{{ $item->volume }}</td>
                                                    <td>{{ 'Rp ' . number_format($item->volume * $item->harga_master, 0, ',', '.') }}
                                                    </td>
                                                @else
                                                    <td></td>
                                                    <td></td>
                                                @endif
                                            @else
                                                @if ($item->ket == 'masuk')
                                                    <td>{{ $item->volume }}</td>
                                                @else
                                                    <td></td>
                                                @endif
                                                @if ($item->ket == 'keluar')
                                                    <td>{{ $item->volume }}</td>
                                                @else
                                                    <td></td>
                                                @endif
                                            @endif
                                            @php
                                                // volume
                                                $saldomutasiV = 0 + $saldoVM - $saldoVK;
                                                if ($saldoVM > 0) {
                                                    $saldomutasi1V = $saldomutasiV + $saldoVK;
                                                } else {
                                                    $saldomutasi1V = $saldomutasiV - $saldoVM;
                                                }
                                                // harga
                                                $saldomutasiH = 0 + $saldoHM - $saldoHK;
                                                if ($saldoHM > 0) {
                                                    $saldomutasi1H = $saldomutasiH + $saldoHK;
                                                } else {
                                                    $saldomutasi1H = $saldomutasiH - $saldoHM;
                                                }
                                            @endphp
                                            <td>
                                                {{ $saldomutasi1V }}
                                            </td>
                                            @if (Auth::user()->role == '1')
                                                <td>{{ 'Rp ' . number_format($saldomutasi1H, 0, ',', '.') }}</td>
                                            @endif
                                        </tr>
                                    @else
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td>{{ $item->tanggal }}</td>
                                            <td>{{ $item->supplier }}</td>
                                            @if (Auth::user()->role == '1')
                                                @if ($item->ket == 'masuk')
                                                    <td>{{ $item->volume }}</td>
                                                    <td>{{ 'Rp ' . number_format($item->harga, 0, ',', '.') }}</td>
                                                @else
                                                    <td></td>
                                                    <td></td>
                                                @endif
                                                @if ($item->ket == 'keluar')
                                                    <td>{{ $item->volume }}</td>
                                                    <td>{{ 'Rp ' . number_format($item->volume * $item->harga_master, 0, ',', '.') }}
                                                    </td>
                                                @else
                                                    <td></td>
                                                    <td></td>
                                                @endif
                                            @else
                                                @if ($item->ket == 'masuk')
                                                    <td>{{ $item->volume }}</td>
                                                @else
                                                    <td></td>
                                                @endif
                                                @if ($item->ket == 'keluar')
                                                    <td>{{ $item->volume }}</td>
                                                @else
                                                    <td></td>
                                                @endif
                                            @endif
                                            @php
                                                // volume
                                                $sals1V = $saldomutasi1V;
                                                $saldomutasi1V = $sals1V + $saldoVM - $saldoVK;
                                                // harga
                                                $sals1H = $saldomutasi1H;
                                                $saldomutasi1H = $sals1H + $saldoHM - $saldoHK;
                                            @endphp
                                            <td>
                                                {{ round($saldomutasi1V, 4) }}
                                            </td>
                                            @if (Auth::user()->role == '1')
                                                <td>{{ 'Rp ' . number_format($saldomutasi1H, 0, ',', '.') }}</td>
                                            @endif
                                        </tr>
                                    @endif
                                @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                @if (Auth::user()->role == '1')
                                    <td colspan="7">Saldo Akhir</td>
                                @else
                                    <td colspan="5">Saldo Akhir</td>
                                @endif
                                <td>{{ round($saldomutasi1V, 4) }}</td>
                                @if (Auth::user()->role == '1')
                                    <td>{{ 'Rp ' . number_format($saldomutasi1H, 0, ',', '.') }}</td>
                                @endif
                            </tr>
                        </tfoot>
                        @endif
                    </table>
                </div>
            </div>
        </div> <!-- end col -->
    </div> <!-- end row -->
@endsection
@section('script')
    <script src="{{ URL::asset('/assets/libs/datatables/datatables.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/libs/jszip/jszip.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/libs/pdfmake/pdfmake.min.js') }}"></script>
    {{-- <script src="{{ URL::asset('/assets/js/pages/datatables.init.js') }}"></script> --}}
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
