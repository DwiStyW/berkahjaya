@extends('layout.master')
@section('title')
    laporan
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
            Laporan
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
                            <h6>Log dan Opc</h6>
                            <table class="dataTable cell-border w-100 mb-3">
                                <thead>
                                    <tr>
                                        <th>tanggal</th>
                                        <th>Uraian</th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td></td>
                                        <td>Log Datang</td>
                                        <td>{{ $total_proVol }}</td>
                                        {{-- <td>{{ $total_beliVol }}</td> --}}
                                        <td></td>
                                        <td></td>
                                        <td>{{ 'Rp ' . number_format($total_proHarga, 0, ',', '.') }}</td>
                                        {{-- <td>{{ 'Rp ' . number_format($total_beliHarga, 0, ',', '.') }}</td> --}}
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>{{ $jumlah_truk }} Truk</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td>{{ 'Rp ' . number_format($harga_truk, 0, ',', '.') }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-white">&sum;</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    @foreach ($penjualanOpc as $pOpc)
                                        <tr>
                                            <td>{{ date('d M Y', strtotime($pOpc->tanggal)) }}</td>
                                            <td>{{ $pOpc->supplier }}</td>
                                            <td></td>
                                            <td>{{ $pOpc->vol_m3 }}</td>
                                            <td>{{ 'Rp ' . number_format($pOpc->total_harga, 0, ',', '.') }}</td>
                                            <td></td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td>{{ date('d M Y', strtotime($newDateAkhir)) }}</td>
                                        <td>Stock</td>
                                        <td></td>
                                        <td>{{ round($total_soVol, 4) }}</td>
                                        <td>{{ 'Rp ' . number_format($total_soHarga, 0, ',', '.') }}</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td class="text-white">&sum;</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td style="background-color: rgb(255, 77, 77)">

                                            @if ($total_proVol == 0)
                                                {{ '0 %' }}
                                            @else
                                                {{ round((($total_jualOpcVol + $total_soVol) / $total_proVol) * 100) }} %
                                            @endif

                                        </td>
                                        {{-- <td style="background-color: rgb(255, 77, 77)">
                                            {{ round((($total_jualOpcVol + $total_soVol) / $total_beliVol) * 100) }} %</td> --}}
                                        {{-- <td>{{ $total_proVol }}</td> --}}
                                        <td style="background-color: rgb(255, 249, 86)">{{ $total_beliVol }}</td>
                                        <td style="background-color: rgb(255, 249, 86)">
                                            {{ $total_jualOpcVol + $total_soVol }}</td>
                                        <td style="background-color: rgb(255, 194, 133)">
                                            {{ 'Rp ' . number_format($total_jualOpcHarga + $total_soHarga, 0, ',', '.') }}
                                        </td>
                                        <td style="background-color: rgb(47, 234, 0)">
                                            {{ 'Rp ' . number_format($total_jualOpcHarga + $total_soHarga - ($total_proHarga + $harga_truk), 0, ',', '.') }}
                                            {{-- {{ 'Rp ' . number_format($total_jualOpcHarga + $total_soHarga - $total_beliHarga, 0, ',', '.') }} --}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-white">&sum;</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr style="background-color: rgb(47, 234, 0)">
                                        <td></td>
                                        <td>Stock Log</td>
                                        <td>
                                            {{ round($total_slVol, 4) }}</td>
                                        <td></td>
                                        <td></td>
                                        <td>{{ 'Rp ' . number_format($total_slHarga, 0, ',', '.') }}</td>
                                    </tr>
                                </tbody>
                            </table>
                            <h6>Limbah dan Operasional</h6>
                            <table class="dataTable cell-border w-100 mb-3">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Uraian</th>
                                        <th>Debet</th>
                                        <th>Kredit</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($penjualanLimbah as $pL)
                                        <tr>
                                            <td>{{ date('d M Y', strtotime($pL->tanggal)) }}</td>
                                            <td>{{ $pL->supplier }}</td>
                                            <td>{{ 'Rp ' . number_format($pL->total_harga, 0, ',', '.') }}</td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    @endforeach
                                    {{-- <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td>{{ 'Rp ' . number_format($total_jualLimbahHarga, 0, ',', '.') }}</td>
                                        <td></td>
                                        <td></td>
                                    </tr> --}}
                                    <tr>
                                        <td class="text-white">&sum;</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    @foreach ($operasional as $op)
                                        <tr>
                                            <td>{{ date('d M Y', strtotime($op->tanggal)) }}</td>
                                            <td>{{ $op->uraian }}</td>
                                            <td></td>
                                            <td>{{ 'Rp ' . number_format($op->harga, 0, ',', '.') }}</td>
                                            <td></td>
                                        </tr>
                                    @endforeach
                                    @foreach ($operasional_kat as $op_kat)
                                        <tr>
                                            <td>{{ $loop->index + 1 }}</td>
                                            <td>{{ $op_kat->kategori }}</td>
                                            <td></td>
                                            <td>{{ 'Rp ' . number_format($op_kat->harga, 0, ',', '.') }}</td>
                                            <td></td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td>{{ date('d M Y', strtotime($newDateAkhir)) }}</td>
                                        <td>Stock Ppc</td>
                                        <td>{{ 'Rp ' . number_format($total_spHarga, 0, ',', '.') }}</td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>Stock Mk</td>
                                        <td>{{ 'Rp ' . number_format($total_smHarga, 0, ',', '.') }}</td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td class="text-white">&sum;</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td style="background-color: rgb(160, 234, 0)">Tidak Dengan Stock</td>
                                        <td style="background-color: rgb(160, 234, 0)">
                                            {{ 'Rp ' . number_format($total_jualLimbahHarga, 0, ',', '.') }}
                                        </td>
                                        <td style="background-color: rgb(160, 234, 0)">
                                            {{ 'Rp ' . number_format($total_operasional + $total_operasional_kat, 0, ',', '.') }}
                                        </td>
                                        <td style="background-color: rgb(160, 234, 0)">
                                            {{ 'Rp ' . number_format($total_jualLimbahHarga - ($total_operasional + $total_operasional_kat), 0, ',', '.') }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-white">&sum;</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td style="background-color: rgb(47, 234, 0)">Dengan Stock</td>
                                        <td style="background-color: rgb(47, 234, 0)">
                                            {{ 'Rp ' . number_format($total_jualLimbahHarga + $total_spHarga + $total_smHarga, 0, ',', '.') }}
                                        </td>
                                        <td style="background-color: rgb(47, 234, 0)"></td>
                                        <td style="background-color: rgb(47, 234, 0)">
                                            {{ 'Rp ' . number_format($total_jualLimbahHarga + $total_spHarga + $total_smHarga - ($total_operasional + $total_operasional_kat), 0, ',', '.') }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-white">&sum;</td>
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
