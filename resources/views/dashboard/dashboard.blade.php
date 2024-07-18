@extends('layout.master')
@section('title')
    @lang('Dashboard')
@endsection
@section('css')
    <!-- DataTables -->
    <link href="{{ URL::asset('/assets/libs/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
    <style>
        table.dataTable.cell-border thead tr th {
            border: 1px solid rgba(0, 0, 0, 0.10);
            text-align: center;
        }

        table.dataTable.cell-border tbody th,
        table.dataTable.cell-border tbody td {
            border-top: 1px solid rgba(0, 0, 0, 0.15);
            border-right: 1px solid rgba(0, 0, 0, 0.15);
            border-bottom: 1px solid rgba(0, 0, 0, 0.15);
            padding: 5px 10px 5px 10px;
        }

        table.dataTable.cell-border thead tr th {
            padding: 5px 10px 5px 10px;
        }


        table.dataTable.cell-border tbody tr th:first-child,
        table.dataTable.cell-border tbody tr td:first-child {
            border-left: 1px solid rgba(0, 0, 0, 0.15);
        }

        table.dataTable.cell-border tbody tr:first-child th,
        table.dataTable.cell-border tbody tr:first-child td {
            border-top: 1px solid rgba(0, 0, 0, 0.15);
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
            Dashboard
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-md-6 @if (Auth::user()->role == '1') col-xl-3 @endif">
            <div class="card">
                <div class="card-body">
                    <div class="float-end mt-2">
                        <div id="persentase_logopc"></div>
                    </div>
                    <div>
                        <h4 class="mb-1 mt-1">
                            <span>
                                {{ $dataProduksi['persentThisweek'] }}
                            </span>%
                        </h4>
                        <p class="text-muted mb-0">Persentase Produksi OPC</p>
                    </div>
                    <p class="text-muted mt-3 mb-0">
                        <span class="{{ $dataProduksi['warna_pro'] }} me-1">
                            <i class="mdi {{ $dataProduksi['ket_pro'] }} me-1"></i>
                            {{ number_format($dataProduksi['persen_pro'], 2) }}%
                        </span>
                        sejak minggu lalu
                    </p>
                </div>
            </div>
        </div> <!-- end col-->

        <div class="col-md-6 @if (Auth::user()->role == '1') col-xl-3 @endif">
            <div class="card">
                <div class="card-body">
                    <div class="float-end mt-2">
                        <div id="produksi-chart"> </div>
                    </div>
                    <div>
                        <h4 class="mb-1 mt-1"><span
                                data-plugin="counterup">{{ $dataProduksi['total_uraian_proopc'] }}</span>
                        </h4>
                        <p class="text-muted mb-0">Total Produksi</p>
                    </div>
                    <p class="text-muted mt-3 mb-0">
                        <span class="{{ $dataProduksi['warna_pro'] }} me-1">
                            <i class="mdi {{ $dataProduksi['ket_pro'] }} me-1"></i>
                            {{ number_format($dataProduksi['persen_pro'], 2) }}%
                        </span>
                        sejak minggu lalu
                    </p>
                </div>
            </div>
        </div> <!-- end col-->
        @if (Auth::user()->role == '1')
            <div class="col-md-6 col-xl-3">
                <div class="card">
                    <div class="card-body">
                        <div class="float-end mt-2">
                            <div id="penjualan-chart"> </div>
                        </div>
                        <div>
                            <h4 class="mb-1 mt-1"><span
                                    data-plugin="counterup">{{ $dataPenjualan['delivery_thisweek'] }}</span>
                            </h4>
                            <p class="text-muted mb-0">Total Penjualan</p>
                        </div>
                        <p class="text-muted mt-3 mb-0">
                            <span class="{{ $dataPenjualan['warna_deliv'] }} me-1">
                                <i class="mdi {{ $dataPenjualan['ket_deliv'] }} me-1"></i>
                                {{ number_format($dataPenjualan['persen_deliv'], 2) }}%
                            </span>
                            sejak minggu lalu
                        </p>
                    </div>
                </div>
            </div> <!-- end col-->

            <div class="col-md-6 col-xl-3">

                <div class="card">
                    <div class="card-body">
                        <div class="float-end mt-2">
                            <div id="growth-chart"></div>
                        </div>
                        <div>
                            <h4 class="mb-1 mt-1">
                                <span id="pendapatan">{{ $dataPendapatan['total_pendapatan'] }}</span>
                            </h4>
                            <p class="text-muted mb-0">Total pendapatan produksi</p>
                        </div>
                        <p class="text-muted mt-3 mb-0">
                            <span class="{{ $dataPendapatan['warna_pendapatan'] }} me-1">
                                <i class="mdi {{ $dataPendapatan['ket_pendapatan'] }} me-1"></i>
                                {{ number_format($dataPendapatan['persen_pendapatan'], 2) }}%
                            </span>
                            sejak minggu lalu
                        </p>
                    </div>
                </div>
            </div> <!-- end col-->
    </div> <!-- end row-->
    @endif
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">Data produksi Terbaru</h4>
                    <div class="table-responsive">
                        <table id="datatable" class="table dataTable cell-border w-100">
                            <thead class="bg-secondary text-white">
                                <tr>
                                    {{-- <th rowspan="2">No</th> --}}
                                    <th rowspan="2">Tanggal</th>
                                    <th rowspan="2">Supplier</th>
                                    <th rowspan="2">Log OPC</th>
                                    <th colspan="2">OPC</th>
                                    <th colspan="2">OPC B</th>
                                    <th colspan="1">PPC</th>
                                    <th colspan="1">MK</th>
                                    <th colspan="1">Ampulur</th>
                                </tr>
                                <tr>
                                    <th data-dt-order="disable">Pcs</th>
                                    <th data-dt-order="disable">m3</th>
                                    <th data-dt-order="disable">Pcs</th>
                                    <th data-dt-order="disable">m3</th>
                                    <th data-dt-order="disable">m</th>
                                    <th data-dt-order="disable">m</th>
                                    <th data-dt-order="disable">pcs</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $no = 1;
                                @endphp
                                @foreach ($ProduksiTable as $item)
                                    <tr>
                                        {{-- <td>{{ $no++ }}</td> --}}
                                        <td>{{ $item->tanggal }}</td>
                                        <td>{{ $item->supplier }}<span class="d-none">{{ $item->kode_produksi }}</span>
                                        </td>
                                        <td>{{ $item->log_opc }}</td>
                                        <td>{{ $item->opc_pcs }}</td>
                                        <td>{{ $item->opc_m3 }}</td>
                                        <td>{{ $item->opcb_pcs }}</td>
                                        <td>{{ $item->opcb_m3 }}</td>
                                        <td>{{ $item->ppc_m }}</td>
                                        <td>{{ $item->mk_m }}</td>
                                        <td>{{ $item->ampulur_pcs }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- end table-responsive -->
                </div>
            </div>
        </div>
    </div>
    <!-- end row -->
@endsection
@section('script')
    <!-- apexcharts -->
    <script src="{{ URL::asset('/assets/libs/datatables/datatables.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/libs/apexcharts/apexcharts.min.js') }}"></script>
    <script src="//cdn.rawgit.com/ashl1/datatables-rowsgroup/v2.0.0/dataTables.rowsGroup.js"></script>
    {{-- <script src="{{ URL::asset('/assets/js/pages/dashboard.init.js') }}"></script> --}}
    <script>
        var persentThisweek = @json($dataProduksi);
        var persentDeliv_Thisweek = @json($dataPenjualan);
        // produksi
        var options_produksi = {
            fill: {
                colors: ['#34c38f']
            },
            series: [persentThisweek.persentThisweek],
            chart: {
                type: 'radialBar',
                width: 45,
                height: 45,
                sparkline: {
                    enabled: true
                }
            },
            dataLabels: {
                enabled: false
            },
            plotOptions: {
                radialBar: {
                    hollow: {
                        margin: 0,
                        size: '60%'
                    },
                    track: {
                        margin: 0
                    },
                    dataLabels: {
                        show: false
                    }
                }
            }
        };

        var chart = new ApexCharts(document.querySelector("#produksi-chart"), options_produksi);
        chart.render();

        // penjualan
        var options_penjualan = {
            fill: {
                colors: ['#5b73e8']
            },
            series: [persentDeliv_Thisweek.persentDeliv_Thisweek],
            chart: {
                type: 'radialBar',
                width: 45,
                height: 45,
                sparkline: {
                    enabled: true
                }
            },
            dataLabels: {
                enabled: false
            },
            plotOptions: {
                radialBar: {
                    hollow: {
                        margin: 0,
                        size: '60%'
                    },
                    track: {
                        margin: 0
                    },
                    dataLabels: {
                        show: false
                    }
                }
            }
        };

        var chart_penjualan = new ApexCharts(document.querySelector("#penjualan-chart"), options_penjualan);
        chart_penjualan.render();

        $(document).ready(function() {
            var nilai = document.getElementById('pendapatan').innerHTML;
            var pendapatan = Number(nilai).toFixed(0)
            document.getElementById('pendapatan').innerHTML = currency(pendapatan);
        });

        function currency(params) {
            var rupiah = new Intl.NumberFormat("id-ID", {
                style: "currency",
                currency: "IDR"
            }).format(params).replace(",00", "");
            return rupiah;
        }
        $(document).ready(function() {
            var table = $('#datatable').DataTable({
                rowsGroup: [0, 1, 2],
                // pageLength: '20',
                // dom: '<"row justify-content-between"<><"row"<f><B>>><"table-responsive"<t>><"row justify-content-between"ip>'
                dom: 't'
            });
        });
    </script>
@endsection
