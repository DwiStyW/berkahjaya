@extends('layout.master')
@section('title')
    Hasil Produk
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
            Hasil Produk
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5>Stock Bahan Baku</h5>
                    <table id="datatable" class="dataTable cell-border dt-responsive nowrap mb-4"
                        style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                            <tr>
                                <th>Jenis</th>
                                <th>Volume</th>
                                <th>Rupiah</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                @php
                                    $no_lm = 1;
                                @endphp
                                @if (count($logmasuk) != 0)
                                    @foreach ($logmasuk as $lm)
                                        @php
                                            if ($lm->ket == 'masuk') {
                                                $saldoVM_lm = $lm->volume;
                                                $saldoVK_lm = 0;
                                                $saldoHM_lm = $lm->harga;
                                                $saldoHK_lm = 0;
                                            } else {
                                                $saldoVM_lm = 0;
                                                $saldoVK_lm = $lm->volume;
                                                $saldoHM_lm = 0;
                                                $saldoHK_lm = $lm->harga;
                                            }
                                        @endphp
                                        @if ($no_lm == 1)
                                            @php
                                                $no_lm++;
                                                // volume
                                                $saldomutasiV_lm = 0 + $saldoVM_lm + $saldoVK_lm;
                                                if ($saldoVM_lm > 0) {
                                                    $saldomutasi1V_lm = $saldomutasiV_lm + $saldoVK_lm;
                                                } else {
                                                    $saldomutasi1V_lm = $saldomutasiV_lm - $saldoVM_lm;
                                                }
                                                // harga
                                                $saldomutasiH_lm = 0 + $saldoHM_lm + $saldoHK_lm;
                                                if ($saldoHM_lm > 0) {
                                                    $saldomutasi1H_lm = $saldomutasiH_lm + $saldoHK_lm;
                                                } else {
                                                    $saldomutasi1H_lm = $saldomutasiH_lm - $saldoHM_lm;
                                                }
                                            @endphp
                                        @else
                                            @php
                                                $no_lm++;
                                                // volume
                                                $sals1V_lm = $saldomutasi1V_lm;
                                                $saldomutasi1V_lm = $sals1V_lm + $saldoVM_lm - $saldoVK_lm;
                                                // harga
                                                $sals1H_lm = $saldomutasi1H_lm;
                                                $saldomutasi1H_lm = $sals1H_lm + $saldoHM_lm - $saldoHK_lm;
                                            @endphp
                                        @endif
                                    @endforeach
                                    <th>Log Masuk</th>
                                    <td>
                                        @if (round($saldomutasi1V_lm, 4) == -0)
                                            {{ 0 }}
                                        @else
                                            {{ round($saldomutasi1V_lm, 4) }}
                                        @endif
                                    </td>
                                    <td>{{ 'Rp ' . number_format($saldomutasi1H_lm, 0, ',', '.') }}</td>
                                @endif
                            </tr>
                            <tr>
                                @php
                                    $no_lmk = 1;
                                @endphp
                                @if (count($logmasukkeras) != 0)
                                    @foreach ($logmasukkeras as $lmk)
                                        @php
                                            if ($lmk->ket == 'masuk') {
                                                $saldoVM_lmk = $lmk->volume;
                                                $saldoVK_lmk = 0;
                                                $saldoHM_lmk = $lmk->harga;
                                                $saldoHK_lmk = 0;
                                            } else {
                                                $saldoVM_lmk = 0;
                                                $saldoVK_lmk = $lmk->volume;
                                                $saldoHM_lmk = 0;
                                                $saldoHK_lmk = $lmk->harga;
                                            }
                                        @endphp
                                        @if ($no_lmk == 1)
                                            @php
                                                $no_lmk++;
                                                // volume
                                                $saldomutasiV_lmk = 0 + $saldoVM_lmk + $saldoVK_lmk;
                                                if ($saldoVM_lmk > 0) {
                                                    $saldomutasi1V_lmk = $saldomutasiV_lmk + $saldoVK_lmk;
                                                } else {
                                                    $saldomutasi1V_lmk = $saldomutasiV_lmk - $saldoVM_lmk;
                                                }
                                                // harga
                                                $saldomutasiH_lmk = 0 + $saldoHM_lmk + $saldoHK_lmk;
                                                if ($saldoHM_lmk > 0) {
                                                    $saldomutasi1H_lmk = $saldomutasiH_lmk + $saldoHK_lmk;
                                                } else {
                                                    $saldomutasi1H_lmk = $saldomutasiH_lmk - $saldoHM_lmk;
                                                }
                                            @endphp
                                        @else
                                            @php
                                                $no_lmk++;
                                                // volume
                                                $sals1V_lmk = $saldomutasi1V_lmk;
                                                $saldomutasi1V_lmk = $sals1V_lmk + $saldoVM_lmk - $saldoVK_lmk;
                                                // harga
                                                $sals1H_lmk = $saldomutasi1H_lmk;
                                                $saldomutasi1H_lmk = $sals1H_lmk + $saldoHM_lmk - $saldoHK_lmk;
                                            @endphp
                                        @endif
                                    @endforeach
                                    <th>Log Masuk Keras</th>
                                    <td>
                                        @if (round($saldomutasi1V_lmk, 4) == -0)
                                            {{ 0 }}
                                        @else
                                            {{ round($saldomutasi1V_lmk, 4) }}
                                        @endif
                                    </td>
                                    <td>{{ 'Rp ' . number_format($saldomutasi1H_lmk, 0, ',', '.') }}</td>
                                @endif
                            </tr>
                        </tbody>
                        <tbody>
                            <tr>
                                <th></th>
                                <th>
                                    @php
                                        if (count($logmasuk) == 0) {
                                            $saldomutasi1V_lm = 0;
                                            $saldomutasi1H_lm = 0;
                                        }
                                        if (count($logmasukkeras) == 0) {
                                            $saldomutasi1V_lmk = 0;
                                            $saldomutasi1H_lmk = 0;
                                        }
                                    @endphp
                                    @if (round($saldomutasi1V_lm + $saldomutasi1V_lmk, 4) == -0)
                                        {{ 0 }}
                                    @else
                                        {{ round($saldomutasi1V_lm + $saldomutasi1V_lmk, 4) }}
                                    @endif
                                </th>
                                <th>{{ 'Rp ' . number_format($saldomutasi1H_lm + $saldomutasi1H_lmk, 0, ',', '.') }}</th>
                            </tr>
                        </tbody>
                    </table>

                    <h5 class="mt-4">Stock Bahan Siap Jual</h5>
                    <table id="datatable" class="dataTable cell-border dt-responsive nowrap"
                        style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                            <tr>
                                <th>Jenis</th>
                                <th>Volume</th>
                                <th>Rupiah</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                @php
                                    $no_opc = 1;
                                @endphp
                                @if (count($stockopc) != 0)
                                    @foreach ($stockopc as $opc)
                                        @php
                                            if ($opc->ket == 'masuk') {
                                                $saldoVM_opc = $opc->volume;
                                                $saldoVK_opc = 0;
                                                $saldoHM_opc = $opc->harga;
                                                $saldoHK_opc = 0;
                                            } else {
                                                $saldoVM_opc = 0;
                                                $saldoVK_opc = $opc->volume;
                                                $saldoHM_opc = 0;
                                                $saldoHK_opc = $opc->harga;
                                            }
                                        @endphp
                                        @if ($no_opc == 1)
                                            @php
                                                $no_opc++;
                                                // volume
                                                $saldomutasiV_opc = 0 + $saldoVM_opc + $saldoVK_opc;
                                                if ($saldoVM_opc > 0) {
                                                    $saldomutasi1V_opc = $saldomutasiV_opc + $saldoVK_opc;
                                                } else {
                                                    $saldomutasi1V_opc = $saldomutasiV_opc - $saldoVM_opc;
                                                }
                                                // harga
                                                $saldomutasiH_opc = 0 + $saldoHM_opc + $saldoHK_opc;
                                                if ($saldoHM_opc > 0) {
                                                    $saldomutasi1H_opc = $saldomutasiH_opc + $saldoHK_opc;
                                                } else {
                                                    $saldomutasi1H_opc = $saldomutasiH_opc - $saldoHM_opc;
                                                }
                                            @endphp
                                        @else
                                            @php
                                                $no_opc++;
                                                // volume
                                                $sals1V_opc = $saldomutasi1V_opc;
                                                $saldomutasi1V_opc = $sals1V_opc + $saldoVM_opc - $saldoVK_opc;
                                                // harga
                                                $sals1H_opc = $saldomutasi1H_opc;
                                                $saldomutasi1H_opc = $sals1H_opc + $saldoHM_opc - $saldoHK_opc;
                                            @endphp
                                        @endif
                                    @endforeach
                                    <th>Opc</th>
                                    <td>
                                        @if (round($saldomutasi1V_opc, 4) == -0)
                                            {{ 0 }}
                                        @else
                                            {{ round($saldomutasi1V_opc, 4) }}
                                        @endif
                                    </td>
                                    <td>{{ 'Rp ' . number_format($saldomutasi1H_opc, 0, ',', '.') }}</td>
                                @endif
                            </tr>
                            <tr>
                                @php
                                    $no_ppc = 1;
                                @endphp
                                @if (count($stockppc) != 0)
                                    @foreach ($stockppc as $ppc)
                                        @php
                                            if ($ppc->ket == 'masuk') {
                                                $saldoVM_ppc = $ppc->volume;
                                                $saldoVK_ppc = 0;
                                                $saldoHM_ppc = $ppc->harga;
                                                $saldoHK_ppc = 0;
                                            } else {
                                                $saldoVM_ppc = 0;
                                                $saldoVK_ppc = $ppc->volume;
                                                $saldoHM_ppc = 0;
                                                $saldoHK_ppc = $ppc->harga;
                                            }
                                        @endphp
                                        @if ($no_ppc == 1)
                                            @php
                                                $no_ppc++;
                                                // volume
                                                $saldomutasiV_ppc = 0 + $saldoVM_ppc + $saldoVK_ppc;
                                                if ($saldoVM_ppc > 0) {
                                                    $saldomutasi1V_ppc = $saldomutasiV_ppc + $saldoVK_ppc;
                                                } else {
                                                    $saldomutasi1V_ppc = $saldomutasiV_ppc - $saldoVM_ppc;
                                                }
                                                // harga
                                                $saldomutasiH_ppc = 0 + $saldoHM_ppc + $saldoHK_ppc;
                                                if ($saldoHM_ppc > 0) {
                                                    $saldomutasi1H_ppc = $saldomutasiH_ppc + $saldoHK_ppc;
                                                } else {
                                                    $saldomutasi1H_ppc = $saldomutasiH_ppc - $saldoHM_ppc;
                                                }
                                            @endphp
                                        @else
                                            @php
                                                $no_ppc++;
                                                // volume
                                                $sals1V_ppc = $saldomutasi1V_ppc;
                                                $saldomutasi1V_ppc = $sals1V_ppc + $saldoVM_ppc - $saldoVK_ppc;
                                                // harga
                                                $sals1H_ppc = $saldomutasi1H_ppc;
                                                $saldomutasi1H_ppc = $sals1H_ppc + $saldoHM_ppc - $saldoHK_ppc;
                                            @endphp
                                        @endif
                                    @endforeach
                                    <th>Ppc</th>
                                    <td>
                                        @if (round($saldomutasi1V_ppc, 4) == -0)
                                            {{ 0 }}
                                        @else
                                            {{ round($saldomutasi1V_ppc, 4) }}
                                        @endif
                                    </td>
                                    <td>{{ 'Rp ' . number_format($saldomutasi1H_ppc, 0, ',', '.') }}</td>
                                @endif
                            </tr>
                            <tr>
                                @php
                                    $no_mk = 1;
                                @endphp
                                @if (count($stockmk) != 0)
                                    @foreach ($stockmk as $mk)
                                        @php
                                            if ($mk->ket == 'masuk') {
                                                $saldoVM_mk = $mk->volume;
                                                $saldoVK_mk = 0;
                                                $saldoHM_mk = $mk->harga;
                                                $saldoHK_mk = 0;
                                            } else {
                                                $saldoVM_mk = 0;
                                                $saldoVK_mk = $mk->volume;
                                                $saldoHM_mk = 0;
                                                $saldoHK_mk = $mk->harga;
                                            }
                                        @endphp
                                        @if ($no_mk == 1)
                                            @php
                                                $no_mk++;
                                                // volume
                                                $saldomutasiV_mk = 0 + $saldoVM_mk + $saldoVK_mk;
                                                if ($saldoVM_mk > 0) {
                                                    $saldomutasi1V_mk = $saldomutasiV_mk + $saldoVK_mk;
                                                } else {
                                                    $saldomutasi1V_mk = $saldomutasiV_mk - $saldoVM_mk;
                                                }
                                                // harga
                                                $saldomutasiH_mk = 0 + $saldoHM_mk + $saldoHK_mk;
                                                if ($saldoHM_mk > 0) {
                                                    $saldomutasi1H_mk = $saldomutasiH_mk + $saldoHK_mk;
                                                } else {
                                                    $saldomutasi1H_mk = $saldomutasiH_mk - $saldoHM_mk;
                                                }
                                            @endphp
                                        @else
                                            @php
                                                $no_mk++;
                                                // volume
                                                $sals1V_mk = $saldomutasi1V_mk;
                                                $saldomutasi1V_mk = $sals1V_mk + $saldoVM_mk - $saldoVK_mk;
                                                // harga
                                                $sals1H_mk = $saldomutasi1H_mk;
                                                $saldomutasi1H_mk = $sals1H_mk + $saldoHM_mk - $saldoHK_mk;
                                            @endphp
                                        @endif
                                    @endforeach
                                    <th>Mk</th>
                                    <td>
                                        @if (round($saldomutasi1V_mk, 4) == -0)
                                            {{ 0 }}
                                        @else
                                            {{ round($saldomutasi1V_mk, 4) }}
                                        @endif
                                    </td>

                                    <td>{{ 'Rp ' . number_format($saldomutasi1H_mk, 0, ',', '.') }}</td>
                                @endif
                            </tr>
                        </tbody>
                        <tbody>
                            <tr>
                                <th></th>
                                @php
                                    if (count($stockopc) == 0) {
                                        $saldomutasi1V_opc = 0;
                                        $saldomutasi1H_opc = 0;
                                    }
                                    if (count($stockppc) == 0) {
                                        $saldomutasi1V_ppc = 0;
                                        $saldomutasi1H_ppc = 0;
                                    }
                                    if (count($stockmk) == 0) {
                                        $saldomutasi1V_mk = 0;
                                        $saldomutasi1H_mk = 0;
                                    }
                                @endphp
                                <th>{{ round($saldomutasi1V_opc + $saldomutasi1V_ppc + $saldomutasi1V_mk, 4) }}</th>
                                <th>{{ 'Rp ' . number_format($saldomutasi1H_opc + $saldomutasi1H_ppc + $saldomutasi1H_mk, 0, ',', '.') }}
                                </th>
                            </tr>
                        </tbody>
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
        // $(document).ready(function() {
        //     var table = $('#datatable').DataTable({
        //         dom: '<"table-responsive w-100"<t>>',
        //         paging: false,
        //         // columnDefs: [{
        //         //     render: $.fn.dataTable.render.number('.', ',', 0, 'Rp '),
        //         //     targets: [3]
        //         // }]
        //     });
        // });
    </script>
@endsection
